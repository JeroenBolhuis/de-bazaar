<?php
namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BusinessComponentController extends Controller
{
    use AuthorizesRequests;
    public function create(Business $business)
    {
        return view('business.components.create', compact('business'));
    }

    public function store(Request $request, Business $business)
    {
        $request->validate([
            'type' => 'required|in:intro_text,image,featured_ads',
            'content' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'business_id' => $business->id,
            'type' => $request->type,
            'order' => $business->components()->count() + 1,
        ];

        if ($request->type === 'intro_text') {
            $data['content'] = $request->content;
        }

        if ($request->type === 'image' && $request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('business_images', 'public');
        }

        BusinessComponent::create($data);

        return redirect()->route('business.settings')->with('success', 'Component added!');
    }

    public function reorder(Request $request, Business $business)
    {
        $orderData = json_decode($request->order, true);

        // Handle updates to existing components
        if ($request->has('components')) {
            foreach ($request->components as $id => $data) {
                $component = BusinessComponent::findOrFail($id);
                
                if ($component->type === 'intro_text' && isset($data['content'])) {
                    $component->content = $data['content'];
                }
                
                if ($component->type === 'image' && isset($data['image'])) {
                    // Delete old image if it exists
                    if ($component->image_path) {
                        Storage::disk('public')->delete($component->image_path);
                    }
                    
                    // Store new image
                    $component->image_path = $data['image']->store('business_images', 'public');
                }

                if ($component->type === 'featured_ads' && isset($data['ad_types'])) {
                    $component->settings = array_merge($component->settings ?? [], [
                        'ad_types' => $data['ad_types']
                    ]);
                }
                
                $component->save();
            }
        }

        // Update component order
        foreach ($orderData as $item) {
            if (strpos($item['id'], 'new_') === 0) {
                continue; // Skip new components as they'll be handled below
            }
            BusinessComponent::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        // Handle new components
        if ($request->has('new_components')) {
            foreach ($request->new_components as $tempId => $data) {
                $componentData = [
                    'business_id' => $business->id,
                    'type' => $data['type'],
                    'order' => count($orderData),
                ];

                if ($data['type'] === 'intro_text' && isset($data['content'])) {
                    $componentData['content'] = $data['content'];
                }

                if ($data['type'] === 'image' && isset($data['image'])) {
                    $componentData['image_path'] = $data['image']->store('business_images', 'public');
                }

                if ($data['type'] === 'featured_ads' && isset($data['ad_types'])) {
                    $componentData['settings'] = [
                        'ad_types' => $data['ad_types']
                    ];
                }

                BusinessComponent::create($componentData);
            }
        }

        return redirect()->back()->with('success', 'Layout updated!');
    }

    public function builder(Business $business)
    {
        $user = auth()->user();

        // Only allow the owner to access their own builder
        if ($user->business_id !== $business->id) {
            abort(403);
        }

        return view('business.components.builder', compact('business'));
    }



}
