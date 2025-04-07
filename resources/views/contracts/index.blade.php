<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('warning'))
                        <div class="mb-4 bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                        {{ session('warning') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Contracts</h2>
                        @auth
                            @if(auth()->user()->isAdmin())
                                <div class="flex space-x-4">
                                    <a href="{{ route('contracts.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                        Create New Contract
                                    </a>
                                    <a href="{{ route('contracts.export-pdf') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                        Export Overview PDF
                                    </a>
                                </div>
                            @endif
                        @endauth
                    </div>

                    <div class="space-y-6">
                        @foreach($contracts as $contract)
                            <div class="border dark:border-gray-700 rounded-lg p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-xl font-semibold">{{ $contract->title[app()->getLocale()] ?? $contract->title['nl'] }}</h3>
                                        <span class="inline-flex items-center mt-1 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $contract->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $contract->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                    @auth
                                        @if(auth()->user()->isAdmin())
                                            <div class="flex space-x-2">
                                                <a href="{{ route('contracts.edit', $contract) }}" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                                    Edit Status
                                                </a>
                                                <form action="{{ route('contracts.destroy', $contract) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this contract?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                                <div class="whitespace-pre-line">{!! nl2br(e($contract->content[app()->getLocale()] ?? $contract->content['nl'])) !!}</div>
                            </div>
                        @endforeach
                    </div>

                    @auth
                        <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold mb-4">Contract Status</h3>
                            
                            @php
                                $unsignedContracts = \App\Models\Contract::getUnsignedActiveContracts(auth()->user());
                            @endphp

                            @if($unsignedContracts->isEmpty())
                                <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                                You have agreed to all current contracts
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg mb-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                                There are {{ $unsignedContracts->count() }} new contract(s) that require your acceptance
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route('contracts.accept') }}" method="POST" class="flex justify-end">
                                    @csrf
                                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Accept All Contracts
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 