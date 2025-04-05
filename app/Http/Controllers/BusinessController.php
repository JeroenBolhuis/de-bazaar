<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Business;
use App\Models\Advertisement;

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
        $business = auth()->user()->business;
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'kvk_number' => ['required', 'string', 'max:20', Rule::unique('businesses')->ignore($business->id)],
            'vat_number' => ['required', 'string', 'max:20', Rule::unique('businesses')->ignore($business->id)],
        ]);

        $business->update($validated);

        return redirect()->route('business.settings')
            ->with('success', __('Business settings updated successfully.'));
    }

    /**
     * Update business theme settings.
     */
    public function updateTheme(Request $request): RedirectResponse
    {
        $business = auth()->user()->business;
        
        $validated = $request->validate([
            'primary_color' => ['required', 'string', 'regex:/^#[0-9A-F]{6}$/i'],
            'secondary_color' => ['required', 'string', 'regex:/^#[0-9A-F]{6}$/i'],
        ]);

        $themeSettings = $business->theme_settings ?? [];
        $themeSettings['primary_color'] = $validated['primary_color'];
        $themeSettings['secondary_color'] = $validated['secondary_color'];

        $business->update(['theme_settings' => $themeSettings]);

        return redirect()->route('business.settings')
            ->with('success', __('Business theme updated successfully.'));
    }

    /**
     * Update business domain settings.
     */
    public function updateDomain(Request $request): RedirectResponse
    {
        $business = auth()->user()->business;
        
        $validated = $request->validate([
            'domain' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
                Rule::unique('businesses')->ignore($business->id),
            ],
        ]);

        $business->update(['domain' => $validated['domain']]);

        return redirect()->route('business.settings')
            ->with('success', __('Business domain updated successfully.'));
    }

    /**
     * Display the business landing page.
     */
    public function landingPage($domain)
    {
        $business = Business::where('domain', $domain)->firstOrFail();
        $listings = Advertisement::where('user_id', $business->user_id)
            ->where('is_active', true)
            ->latest()
            ->paginate(9);

        return view('business.landing', compact('business', 'listings'));
    }

    /**
     * Update landing page content.
     */
    public function updateLandingPage(Request $request): RedirectResponse
    {
        // TODO: Implement landing page update
        return redirect()->route('business.landing-page')
            ->with('success', __('Landing page updated successfully.'));
    }

    /**
     * Display API settings.
     */
    public function apiSettings(): View
    {
        // TODO: Implement API settings view
        return view('business.api-settings');
    }

    /**
     * Generate new API key.
     */
    public function generateApiKey(Request $request): RedirectResponse
    {
        // TODO: Implement API key generation
        return redirect()->route('business.api-settings')
            ->with('success', __('New API key generated successfully.'));
    }

    /**
     * Upload and process CSV file for bulk listings.
     */
    public function uploadCsv(Request $request): RedirectResponse
    {
        // TODO: Implement CSV upload and processing
        return redirect()->route('business.settings')
            ->with('success', __('CSV file processed successfully.'));
    }

    /**
     * Display contract management.
     */
    public function contracts(): View
    {
        // TODO: Implement contracts view
        return view('business.contracts');
    }

    /**
     * Upload signed contract.
     */
    public function uploadContract(Request $request): RedirectResponse
    {
        // TODO: Implement contract upload
        return redirect()->route('business.contracts')
            ->with('success', __('Contract uploaded successfully.'));
    }

    /**
     * Generate PDF contract.
     */
    public function generateContract(string $id)
    {
        // TODO: Implement PDF contract generation
        // Return PDF response
    }
}
