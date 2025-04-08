<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contracts Overview</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Contracts Overview</h1>
        
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-3 px-4 text-left border-b">User</th>
                    <th class="py-3 px-4 text-left border-b">Email</th>
                    <th class="py-3 px-4 text-left border-b">All Contracts Signed</th>
                    <th class="py-3 px-4 text-left border-b">Signed Contracts</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border-b">
                        <td class="py-3 px-4">{{ $user->name }}</td>
                        <td class="py-3 px-4">{{ $user->email }}</td>
                        <td class="py-3 px-4">
                            @if($user->contracts->count() === $activeContracts->count())
                                <span class="text-green-600 font-semibold">Yes</span>
                            @else
                                <span class="text-red-600 font-semibold">No</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <ul class="list-disc list-inside">
                                @foreach($user->contracts as $contract)
                                    <li>{{ $contract->title[app()->getLocale()] ?? $contract->title['nl'] }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="mt-8 text-sm text-gray-600">
            <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</body>
</html> 