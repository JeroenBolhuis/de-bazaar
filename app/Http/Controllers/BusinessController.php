<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Business;
use App\Models\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BusinessController extends Controller
{
    /**
     * Display business settings.
     */
    public function settings(): View
    {
        $business = auth()->user()->business;
        return view('business.settings', compact('business'));
    }

    /**
     * Update business settings.
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'custom_url' => 'nullable|string|max:255|unique:businesses,custom_url,' . auth()->user()->business_id,
        ]);

        $business = auth()->user()->business;
        $business->update([
            'name' => $request->name,
            'custom_url' => $request->custom_url ? Str::slug($request->custom_url) : null,
        ]);

        return redirect()->route('business.settings')
            ->with('success', __('Business settings updated successfully.'));
    }

    /**
     * Update business theme settings.
     */
    public function updateTheme(Request $request): RedirectResponse
    {
        // TODO: Implement theme update
        return redirect()->route('business.settings')
            ->with('success', __('Business theme updated successfully.'));
    }

    /**
     * Update business domain settings.
     */
    public function updateDomain(Request $request): RedirectResponse
    {
        // TODO: Implement domain update
        return redirect()->route('business.settings')
            ->with('success', __('Business domain updated successfully.'));
    }


    /**
     * Display business landing page by custom URL.
     */
    public function showByCustomUrl(string $customUrl): View
    {
        $business = Business::where('custom_url', $customUrl)
            ->with(['components' => function($query) {
                $query->orderBy('order');
            }])
            ->firstOrFail();

        return view('business.landing-page', compact('business'));
    }

    /**
     * Display component editor page.
     */
    public function editComponents(): View
    {
        $business = auth()->user()->business;
        $components = $business->components()
            ->orderBy('order')
            ->get();
            
        $availableTypes = Component::TYPE_OPTIONS;
        $typeLabels = Component::TYPE_OPTIONS;

        return view('business.edit', compact('components', 'availableTypes', 'typeLabels'));
    }

    /**
     * Add a new component.
     */
    public function addComponent(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => [
                'required',
                'string',
                'in:' . implode(',', Component::TYPE_OPTIONS)
            ],
        ]);

        $business = auth()->user()->business;
        $maxOrder = $business->components()->max('order') ?? 0;

        // Create the component
        $business->components()->create([
            'type' => $request->type,
            'order' => $maxOrder + 1,
        ]);

        return redirect()->route('business.components.edit')
            ->with('success', __('Component added successfully.'));
    }

    /**
     * Reorder components.
     */
    public function reorderComponents(Request $request): RedirectResponse
    {
        $business = auth()->user()->business;
        
        if ($request->has('move_up')) {
            $componentId = $request->move_up;
            $component = $business->components()
                ->where('id', $componentId)
                ->firstOrFail();
            
            $currentOrder = $component->order;
            
            // Find the component above
            $componentAbove = $business->components()
                ->where('order', $currentOrder - 1)
                ->first();
            
            if ($componentAbove) {
                // Swap orders
                $component->update(['order' => $currentOrder - 1]);
                $componentAbove->update(['order' => $currentOrder]);
            }
        } elseif ($request->has('move_down')) {
            $componentId = $request->move_down;
            $component = $business->components()
                ->where('id', $componentId)
                ->firstOrFail();
            
            $currentOrder = $component->order;
            
            // Find the component below
            $componentBelow = $business->components()
                ->where('order', $currentOrder + 1)
                ->first();
            
            if ($componentBelow) {
                // Swap orders
                $component->update(['order' => $currentOrder + 1]);
                $componentBelow->update(['order' => $currentOrder]);
            }
        }

        return redirect()->route('business.components.edit')
            ->with('success', __('Components reordered successfully.'));
    }

    /**
     * Delete a component.
     */
    public function deleteComponent(int $componentId): RedirectResponse
    {
        $business = auth()->user()->business;
        
        // Find and delete the component
        $component = $business->components()
            ->where('id', $componentId)
            ->firstOrFail();
        
        $component->delete();

        // Reorder remaining components
        $remainingComponents = $business->components()
            ->orderBy('order')
            ->get();
            
        foreach ($remainingComponents as $index => $component) {
            $component->update(['order' => $index + 1]);
        }

        return redirect()->route('business.components.edit')
            ->with('success', __('Component deleted successfully.'));
    }
}