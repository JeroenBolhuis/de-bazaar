<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Basic validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(['user', 'seller', 'business'])],
        ];

        // Custom error messages
        $customMessages = [
            'email.unique' => 'This email address is already registered.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];

        // Add conditional validation rules based on role
        if ($request->role === 'seller' || $request->role === 'business') {
            $rules = array_merge($rules, [
                'phone' => ['required', 'string', 'max:20'],
                'address' => ['required', 'string', 'max:255'],
                'postal_code' => ['required', 'string', 'max:10'],
                'city' => ['required', 'string', 'max:100'],
            ]);

            $customMessages = array_merge($customMessages, [
                'phone.required' => 'Phone number is required.',
                'address.required' => 'Address is required.',
                'postal_code.required' => 'Postal code is required.',
                'city.required' => 'City is required.',
            ]);
        }

        if ($request->role === 'business') {
            $rules = array_merge($rules, [
                'business_name' => ['required', 'string', 'max:255'],
                'kvk_number' => ['required', 'string', 'size:8', 'unique:businesses'],
                'vat_number' => ['required', 'string', 'regex:/^[0-9]{9}B[0-9]{2}$/', 'unique:businesses'],
                'domain' => ['required', 'string', 'max:255', 'unique:businesses'],
            ]);

            $customMessages = array_merge($customMessages, [
                'business_name.required' => 'Business name is required.',
                'kvk_number.required' => 'KVK number is required.',
                'kvk_number.size' => 'KVK number must be exactly 8 digits.',
                'kvk_number.unique' => 'This KVK number is already registered.',
                'vat_number.required' => 'VAT number is required.',
                'vat_number.regex' => 'VAT number must be in format: 123456789B12.',
                'vat_number.unique' => 'This VAT number is already registered.',
                'domain.required' => 'Business website is required.',
                'domain.unique' => 'This domain is already registered.',
            ]);
        }

        try {
            $validated = $request->validate($rules, $customMessages);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed: ' . json_encode($e->errors()));
            throw $e;
        }

        // Create business if role is business
        $business_id = null;
        if ($request->role === 'business') {
            try {
                \Log::info('Creating business with data: ' . json_encode([
                    'name' => $validated['business_name'],
                    'kvk_number' => $validated['kvk_number'],
                    'vat_number' => $validated['vat_number'],
                    'domain' => $validated['domain'],
                ]));

                $business = Business::create([
                    'name' => $validated['business_name'],
                    'kvk_number' => $validated['kvk_number'],
                    'vat_number' => $validated['vat_number'],
                    'domain' => $validated['domain'],
                ]);
                $business_id = $business->id;

                \Log::info('Business created successfully with ID: ' . $business_id);
            } catch (\Exception $e) {
                \Log::error('Failed to create business: ' . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
                
                // Check for specific error types
                if (strpos($e->getMessage(), 'Data truncated') !== false) {
                    return back()->withErrors(['vat_number' => 'The VAT number format is invalid. Please use the format: 123456789B12'])->withInput();
                }
                
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    if (strpos($e->getMessage(), 'kvk_number') !== false) {
                        return back()->withErrors(['kvk_number' => 'This KVK number is already registered.'])->withInput();
                    }
                    if (strpos($e->getMessage(), 'vat_number') !== false) {
                        return back()->withErrors(['vat_number' => 'This VAT number is already registered.'])->withInput();
                    }
                    if (strpos($e->getMessage(), 'domain') !== false) {
                        return back()->withErrors(['domain' => 'This domain is already registered.'])->withInput();
                    }
                }
                
                return back()->withErrors(['business' => 'Failed to create business account: ' . $e->getMessage()])->withInput();
            }
        }

        // Create user
        try {
            \Log::info('Creating user with data: ' . json_encode([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'business_id' => $business_id,
            ]));

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'business_id' => $business_id,
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'postal_code' => $validated['postal_code'] ?? null,
                'city' => $validated['city'] ?? null,
            ]);

            \Log::info('User created successfully with ID: ' . $user->id);
        } catch (\Exception $e) {
            \Log::error('Failed to create user: ' . $e->getMessage());
            if ($business_id) {
                Business::destroy($business_id);
            }
            return back()->withErrors(['user' => 'Failed to create user account: ' . $e->getMessage()])->withInput();
        }

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
