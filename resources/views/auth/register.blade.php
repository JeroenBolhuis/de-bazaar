<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" id="registrationForm" class="space-y-6">
        @csrf

        <!-- Basic Information -->
        <div class="space-y-4">
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                <p class="mt-1 text-sm text-gray-500 hidden" id="name-error"></p>
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                <p class="mt-1 text-sm text-gray-500 hidden" id="email-error"></p>
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                <p class="mt-1 text-sm text-gray-500" id="password-requirements">
                    Password must be at least 8 characters and contain at least one uppercase letter, one number, and one special character.
                </p>
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                <p class="mt-1 text-sm text-gray-500 hidden" id="password-match-error">Passwords do not match</p>
            </div>
        </div>

        <!-- Role Toggles -->
        <div class="mt-6 space-y-4">
            <!-- Seller Toggle -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="is_seller" class="sr-only peer" name="is_seller">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                    </label>
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-300">I want to sell items</span>
                </div>
            </div>

            <!-- Hidden role input -->
            <input type="hidden" name="role" id="role_input" value="user">
        </div>

        <!-- Seller Fields (Hidden by default) -->
        <div id="sellerFields" class="mt-4 space-y-4 hidden">
            <div>
                <x-input-label for="phone" :value="__('Phone Number')" />
                <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" pattern="[0-9]{10}" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                <p class="mt-1 text-sm text-gray-500">Format: 0612345678</p>
            </div>

            <div>
                <x-input-label for="address" :value="__('Address')" />
                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="postal_code" :value="__('Postal Code')" />
                    <x-text-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code" :value="old('postal_code')" pattern="[0-9]{4}[A-Za-z]{2}" />
                    <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                    <p class="mt-1 text-sm text-gray-500">Format: 1234AB</p>
                </div>

                <div>
                    <x-input-label for="city" :value="__('City')" />
                    <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" />
                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                </div>
            </div>

            <!-- Business Toggle (Only visible when seller is toggled) -->
            <div id="business_toggle" class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="is_business" class="sr-only peer" name="is_business">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                    </label>
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-300">This is a business account</span>
                </div>
            </div>
        </div>

        <!-- Business Fields (Hidden by default) -->
        <div id="businessFields" class="mt-4 space-y-4 hidden">
            <div>
                <x-input-label for="business_name" :value="__('Business Name')" />
                <x-text-input id="business_name" class="block mt-1 w-full" type="text" name="business_name" :value="old('business_name')" />
                <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="kvk_number" :value="__('KVK Number')" />
                    <x-text-input id="kvk_number" class="block mt-1 w-full" type="text" name="kvk_number" :value="old('kvk_number')" pattern="[0-9]{8}" />
                    <x-input-error :messages="$errors->get('kvk_number')" class="mt-2" />
                    <p class="mt-1 text-sm text-gray-500">8-digit Chamber of Commerce number</p>
                </div>

                <div>
                    <x-input-label for="vat_number" :value="__('VAT Number')" />
                    <x-text-input id="vat_number" class="block mt-1 w-full" type="text" name="vat_number" :value="old('vat_number')" pattern="[0-9]{9}B[0-9]{2}" />
                    <x-input-error :messages="$errors->get('vat_number')" class="mt-2" />
                    <p class="mt-1 text-sm text-gray-500">Format: 123456789B12</p>
                </div>
            </div>

            <div>
                <x-input-label for="domain" :value="__('Business Website')" />
                <x-text-input id="domain" class="block mt-1 w-full" type="url" name="domain" :value="old('domain')" placeholder="https://example.com" />
                <x-input-error :messages="$errors->get('domain')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4" id="submit-button">
                <span id="spinner" class="hidden inline-flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                </span>
                <span id="submit-text">{{ __('Register') }}</span>
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registrationForm');
            const isSellerToggle = document.getElementById('is_seller');
            const isBusinessToggle = document.getElementById('is_business');
            const businessToggleDiv = document.getElementById('business_toggle');
            const sellerFields = document.getElementById('sellerFields');
            const businessFields = document.getElementById('businessFields');
            const roleInput = document.getElementById('role_input');
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password_confirmation');
            const passwordMatchError = document.getElementById('password-match-error');
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('email-error');
            const submitButton = document.getElementById('submit-button');
            const spinner = document.getElementById('spinner');
            const submitText = document.getElementById('submit-text');

            // Initialize form state based on old values
            const oldRole = "{{ old('role', 'user') }}";
            if (oldRole === 'seller' || oldRole === 'business') {
                isSellerToggle.checked = true;
                sellerFields.classList.remove('hidden');
                
                const sellerRequiredFields = ['phone', 'address', 'postal_code', 'city'];
                toggleRequiredFields(sellerRequiredFields, true);
                
                if (oldRole === 'business') {
                    isBusinessToggle.checked = true;
                    businessFields.classList.remove('hidden');
                    
                    const businessRequiredFields = ['business_name', 'kvk_number', 'vat_number', 'domain'];
                    toggleRequiredFields(businessRequiredFields, true);
                }
            }
            
            // Set role input value
            roleInput.value = oldRole;

            // Function to toggle loading state
            function setLoading(isLoading) {
                submitButton.disabled = isLoading;
                spinner.classList.toggle('hidden', !isLoading);
                submitText.classList.toggle('hidden', isLoading);
                if (isLoading) {
                    submitButton.classList.add('opacity-75', 'cursor-not-allowed');
                } else {
                    submitButton.classList.remove('opacity-75', 'cursor-not-allowed');
                }
            }

            // Function to toggle required attributes
            function toggleRequiredFields(elements, required) {
                elements.forEach(element => {
                    const input = document.getElementById(element);
                    if (input) {
                        if (required) {
                            input.setAttribute('required', 'required');
                        } else {
                            input.removeAttribute('required');
                            input.value = ''; // Clear the field when hiding
                        }
                    }
                });
            }

            // Handle seller toggle
            isSellerToggle.addEventListener('change', function() {
                const sellerRequiredFields = ['phone', 'address', 'postal_code', 'city'];
                
                if (this.checked) {
                    sellerFields.classList.remove('hidden');
                    toggleRequiredFields(sellerRequiredFields, true);
                    roleInput.value = 'seller';
                } else {
                    sellerFields.classList.add('hidden');
                    businessFields.classList.add('hidden');
                    isBusinessToggle.checked = false;
                    toggleRequiredFields(sellerRequiredFields, false);
                    roleInput.value = 'user';
                }
            });

            // Handle business toggle
            isBusinessToggle.addEventListener('change', function() {
                const businessRequiredFields = ['business_name', 'kvk_number', 'vat_number', 'domain'];
                
                if (this.checked) {
                    businessFields.classList.remove('hidden');
                    toggleRequiredFields(businessRequiredFields, true);
                    roleInput.value = 'business';
                } else {
                    businessFields.classList.add('hidden');
                    toggleRequiredFields(businessRequiredFields, false);
                    roleInput.value = 'seller';
                }
            });

            // Real-time password validation
            function validatePassword() {
                const value = password.value;
                const hasUpperCase = /[A-Z]/.test(value);
                const hasNumber = /[0-9]/.test(value);
                const hasSpecialChar = /[!@#$%^&*]/.test(value);
                const isLongEnough = value.length >= 8;

                const isValid = hasUpperCase && hasNumber && hasSpecialChar && isLongEnough;
                password.classList.toggle('border-red-500', !isValid);
                document.getElementById('password-requirements').classList.toggle('text-red-500', !isValid);

                return isValid;
            }

            // Real-time password match validation
            function validatePasswordMatch() {
                const match = password.value === passwordConfirm.value;
                passwordMatchError.classList.toggle('hidden', match);
                passwordConfirm.classList.toggle('border-red-500', !match);
                return match;
            }

            // Real-time email validation
            function validateEmail() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const valid = emailRegex.test(emailInput.value);
                emailError.textContent = valid ? '' : 'Please enter a valid email address';
                emailError.classList.toggle('hidden', valid);
                emailInput.classList.toggle('border-red-500', !valid);
                return valid;
            }

            // Add event listeners for real-time validation
            password.addEventListener('input', validatePassword);
            passwordConfirm.addEventListener('input', validatePasswordMatch);
            emailInput.addEventListener('input', validateEmail);

            // Form submission handling
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Form submission started');
                
                // Clear field validation errors
                const allErrorMessages = document.querySelectorAll('.text-red-500, .border-red-500');
                allErrorMessages.forEach(el => {
                    if (el.classList.contains('border-red-500')) {
                        el.classList.remove('border-red-500');
                    }
                    if (el.classList.contains('text-red-500') && el.id !== 'password-requirements') {
                        el.classList.add('hidden');
                    }
                });

                const isPasswordValid = validatePassword();
                const isPasswordMatch = validatePasswordMatch();
                const isEmailValid = validateEmail();

                if (isPasswordValid && isPasswordMatch && isEmailValid) {
                    setLoading(true);
                    console.log('Form is valid, submitting...');

                    // Create form data object
                    const formData = new FormData(form);
                    
                    // Standard form submission instead of fetch to better handle Laravel's validation
                    form.submit();
                } else {
                    console.log('Form validation failed');
                                        
                    if (!isPasswordValid) {
                        document.getElementById('password-requirements').classList.add('text-red-500');
                        const listItem = document.createElement('li');
                        listItem.textContent = 'Password does not meet the requirements';
                        errorList.appendChild(listItem);
                    }
                    if (!isPasswordMatch) {
                        passwordMatchError.classList.remove('hidden');
                        const listItem = document.createElement('li');
                        listItem.textContent = 'Passwords do not match';
                        errorList.appendChild(listItem);
                    }
                    if (!isEmailValid) {
                        emailError.classList.remove('hidden');
                        const listItem = document.createElement('li');
                        listItem.textContent = 'Please enter a valid email address';
                        errorList.appendChild(listItem);
                    }
                }
            });
        });
    </script>
</x-guest-layout>
