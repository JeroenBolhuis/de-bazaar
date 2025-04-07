<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Contract;
use Symfony\Component\HttpFoundation\Response;

class EnsureContractsAccepted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            $unsignedContracts = Contract::getUnsignedActiveContracts($request->user());
            
            if ($unsignedContracts->isNotEmpty()) {
                return redirect()->route('contracts.index')
                    ->with('warning', 'You must accept all contracts before proceeding.');
            }
        }

        return $next($request);
    }
}
