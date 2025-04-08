<?php

namespace App\Services;

use App\Models\Advertisement;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use League\Csv\Writer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdvertisementCsvService
{
    protected $requiredHeaders = [
        'title',
        'description',
        'price',
        'type',
        'condition',
        'wear_per_day',
        'auction_start_date',
        'auction_end_date',
        'image'
    ];

    protected $imageMapping = [];

    public function import($csvContent, $dryRun = true, $user = null, $imageFiles = [])
    {
        $csv = Reader::createFromString($csvContent);
        $csv->setHeaderOffset(0);
        $headers = $csv->getHeader();

        // Validate headers
        $missingHeaders = array_diff($this->requiredHeaders, $headers);
        if (!empty($missingHeaders)) {
            throw new \Exception('Missing required headers: ' . implode(', ', $missingHeaders));
        }

        // Process and validate image files
        $this->processImageFiles($imageFiles);

        $results = [
            'created' => 0,
            'errors' => [],
            'dry_run' => $dryRun
        ];

        // Count existing advertisements per type for the user
        $userAdvertCounts = [];
        if ($user) {
            $userAdvertCounts = Advertisement::where('user_id', $user->id)
                ->selectRaw('type, count(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray();
        }

        DB::beginTransaction();
        try {
            foreach ($csv->getRecords() as $offset => $record) {
                try {
                    // Check advertisement limit per type
                    $type = $record['type'] ?? '';
                    $currentCount = $userAdvertCounts[$type] ?? 0;
                    if ($currentCount >= 4) {
                        throw new \Exception("You have reached the maximum limit of {$type} advertisements (4).");
                    }

                    $this->processRecord($record, $results, $dryRun, $user);
                    
                    // If not dry run and successful, increment the count
                    if (!$dryRun) {
                        $userAdvertCounts[$type] = ($userAdvertCounts[$type] ?? 0) + 1;
                    }
                } catch (\Exception $e) {
                    $results['errors'][] = "Row " . ($offset + 2) . ": " . $e->getMessage();
                }
            }

            if ($dryRun || !empty($results['errors'])) {
                DB::rollBack();
            } else {
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $results;
    }

    protected function processRecord($record, &$results, $dryRun, $user = null)
    {
        // Base validation rules
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:listing,rental,auction',
        ];

        // Add type-specific validation rules
        if (isset($record['type'])) {
            switch ($record['type']) {
                case 'auction':
                    $rules['auction_start_date'] = 'required|date';
                    $rules['auction_end_date'] = 'required|date|after:auction_start_date';
                    break;
                case 'rental':
                    $rules['condition'] = 'required|numeric|min:0|max:100';
                    $rules['wear_per_day'] = 'required|numeric|min:0|max:100';
                    break;
            }
        }

        $validator = Validator::make($record, $rules);

        if ($validator->fails()) {
            throw new \Exception('Validation failed: ' . implode(', ', $validator->errors()->all()));
        }

        // Prepare advertisement data
        $advertisementData = [
            'title' => $record['title'],
            'description' => $record['description'],
            'price' => $record['price'],
            'type' => $record['type'],
            'user_id' => $user ? $user->id : null
        ];

        // Add type-specific data
        if ($record['type'] === 'auction') {
            $advertisementData['auction_start_date'] = Carbon::parse($record['auction_start_date']);
            $advertisementData['auction_end_date'] = Carbon::parse($record['auction_end_date']);
        } elseif ($record['type'] === 'rental') {
            $advertisementData['condition'] = $record['condition'];
            $advertisementData['wear_per_day'] = $record['wear_per_day'];
        }

        // Handle image
        if (!empty($record['image'])) {
            $advertisementData['image'] = $this->processImageField($record['image']);
        }

        if ($dryRun) {
            $results['created']++;
            return;
        }

        Advertisement::create($advertisementData);
        $results['created']++;
    }

    protected function processImageFiles($imageFiles)
    {
        if (empty($imageFiles)) {
            Log::debug('No image files provided');
            return;
        }

        foreach ($imageFiles as $file) {
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $hashedName = Str::random(40) . '.' . $extension;
            
            // Store directly in public storage like the create form does
            $file->storeAs('advertisements', $hashedName, 'public');
            
            // Map both the original name and the file name in the CSV to the hashed name
            $this->imageMapping[$originalName] = $hashedName;
            $this->imageMapping[strtolower($originalName)] = $hashedName;
        }
    }

    protected function processImageField($imageName)
    {
        if (empty($imageName)) {
            return null;
        }

        // Check case-insensitive mapping
        $lowerImageName = strtolower($imageName);
        if (isset($this->imageMapping[$lowerImageName])) {
            return 'advertisements/' . $this->imageMapping[$lowerImageName];
        }

        // If the image name matches a file in our mapping (new uploaded image)
        if (isset($this->imageMapping[$imageName])) {
            return 'advertisements/' . $this->imageMapping[$imageName];
        }

        throw new \Exception("Image file not found: {$imageName}");
    }

    protected function finalizeImages()
    {
        // No need to move files anymore as they're stored directly in the right place
    }

    protected function cleanupTemporaryImages()
    {
        // No need to clean up temporary files anymore
    }
} 