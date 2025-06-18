<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StakingPlan;
use Illuminate\Http\Request;

class StakingPlanController extends Controller
{
    public function index()
    {
        $plans = StakingPlan::latest()->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'interest_rate' => 'required|numeric|min:0',
            'minimum_amount' => 'required|numeric|min:0',
            'maximum_amount' => 'required|numeric|min:0',
        ]);

        StakingPlan::create($request->all());
        return redirect()->route('admin.plans.index')->with('success', 'Plan created successfully');
    }

    public function edit(StakingPlan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, StakingPlan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'interest_rate' => 'required|numeric|min:0',
            'minimum_amount' => 'required|numeric|min:0',
            'maximum_amount' => 'required|numeric|min:0',
        ]);

        $plan->update($request->all());
        return redirect()->route('admin.plans.index')->with('success', 'Plan updated successfully');
    }

    public function destroy(StakingPlan $plan)
    {
        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted successfully');
    }
}
