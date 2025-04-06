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

        foreach ($orderData as $item) {
            BusinessComponent::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        // (Optional) handle new components
        if ($request->filled('new_components')) {
            foreach ($request->input('new_components') as $index => $type) {
                BusinessComponent::create([
                    'business_id' => $business->id,
                    'type' => $type,
                    'order' => count($orderData) + $index,
                ]);
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
