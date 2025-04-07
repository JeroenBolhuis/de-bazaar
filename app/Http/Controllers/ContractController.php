<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::all();
        return view('contracts.index', compact('contracts'));
    }

    public function create()
    {
        return view('contracts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title.nl' => 'required|string|max:255',
            'title.en' => 'required|string|max:255',
            'content.nl' => 'required|string',
            'content.en' => 'required|string',
        ]);

        $contract = Contract::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('contracts.index')
            ->with('success', 'Contract created successfully.');
    }

    public function edit(Contract $contract)
    {
        return view('contracts.edit', compact('contract'));
    }

    public function update(Request $request, Contract $contract)
    {
        $contract->update([
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('contracts.index')
            ->with('success', 'Contract status updated successfully.');
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();

        return redirect()
            ->route('contracts.index')
            ->with('success', 'Contract deleted successfully.');
    }

    public function accept(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Get all active contracts that the user hasn't accepted yet
        $unsignedContracts = Contract::getUnsignedActiveContracts($user);
        
        // Attach all unsigned contracts to the user
        foreach ($unsignedContracts as $contract) {
            $user->contracts()->attach($contract->id);
        }

        return redirect()->back()->with('success', 'All contracts have been accepted successfully.');
    }
}
