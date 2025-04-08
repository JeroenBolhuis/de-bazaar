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
                $query->withPivot(['id', 'title', 'content', 'order'])
                    ->orderBy('business_components.order');
            }, 'users', 'advertisements' => function($query) {
                $query->orderBy('created_at', 'desc')
                    ->take(3);
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
            ->withPivot(['id', 'title', 'content', 'order'])
            ->orderBy('business_components.order')
            ->get();
            
        $availableTypes = Component::pluck('label', 'type')->toArray();

        return view('business.edit', compact('components', 'availableTypes'));
    }

    /**
     * Add a new component.
     */
    public function addComponent(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => 'required|string|max:255',
        ]);

        $business = auth()->user()->business;
        $maxOrder = $business->components()->max('business_components.order') ?? 0;

        // Find the component type
        $component = Component::where('type', $request->type)->firstOrFail();

        // Create the business component
        $business->components()->attach($component->id, [
            'order' => $maxOrder + 1,
            'title' => $component->label, // Use the component's label as default title
            'content' => ''
        ]);

        // Refresh the business to get the latest components
        $business->refresh();

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
                ->wherePivot('id', $componentId)
                ->firstOrFail();
            
            $currentOrder = $component->pivot->order;
            
            // Find the component above
            $componentAbove = $business->components()
                ->wherePivot('order', $currentOrder - 1)
                ->first();
            
            if ($componentAbove) {
                // Swap orders
                $business->components()->updateExistingPivot($component->id, [
                    'order' => $currentOrder - 1
                ]);
                $business->components()->updateExistingPivot($componentAbove->id, [
                    'order' => $currentOrder
                ]);
            }
        } elseif ($request->has('move_down')) {
            $componentId = $request->move_down;
            $component = $business->components()
                ->wherePivot('id', $componentId)
                ->firstOrFail();
            
            $currentOrder = $component->pivot->order;
            
            // Find the component below
            $componentBelow = $business->components()
                ->wherePivot('order', $currentOrder + 1)
                ->first();
            
            if ($componentBelow) {
                // Swap orders
                $business->components()->updateExistingPivot($component->id, [
                    'order' => $currentOrder + 1
                ]);
                $business->components()->updateExistingPivot($componentBelow->id, [
                    'order' => $currentOrder
                ]);
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
        
        // Find the business component by its pivot ID
        $businessComponent = $business->components()
            ->wherePivot('id', $componentId)
            ->firstOrFail();

        // Delete the specific pivot record
        $business->components()
            ->wherePivot('id', $componentId)
            ->detach($businessComponent->id);

        // Reorder remaining components
        $remainingComponents = $business->components()
            ->orderBy('business_components.order')
            ->get();
            
        foreach ($remainingComponents as $index => $component) {
            $business->components()->updateExistingPivot($component->id, [
                'order' => $index + 1
            ]);
        }

        return redirect()->route('business.components.edit')
            ->with('success', __('Component deleted successfully.'));
    }

    /**
     * Update a component's content.
     */
    public function updateComponent(Request $request, int $pivotId): RedirectResponse
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ]);

        \Log::info('Updating component', [
            'pivot_id' => $pivotId,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        // Update the business component directly using the pivot ID
        $updated = DB::table('business_components')
            ->where('id', $pivotId)
            ->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);

        \Log::info('Update result', ['updated' => $updated]);

        return redirect()->route('business.components.edit')
            ->with('success', __('Component updated successfully.'));
    }
}