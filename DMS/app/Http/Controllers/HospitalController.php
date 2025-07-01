<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Inertia\Inertia;

class HospitalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hospitals = Hospital::all();

        return Inertia::render("Hospitals/Index", [
            'hospitals' => $hospitals
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("Hospitals/Create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'branch' => 'required|string|max:255',
        ]);

        Hospital::create($validated);

        return redirect()->route('hospitals.index')->with('success', 'Hospital created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Hospital $hospital)
    {
        return Inertia::render("Hospitals/Show", [
            'hospital' => $hospital
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hospital $hospital)
    {
        return Inertia::render("Hospitals/Edit", [
            'hospital' => $hospital
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hospital $hospital)
    {
        $validated = $request->validate([
            'name'=> 'required/string/max:255',
            'branch'=> 'required/string/max:255',
        ]);

        $hospital->update($validated);

        return redirect()->route('hospitals.index')->with('success', 'Hospital updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hospital $hospital)
    {
        $hospital->delete();

        return redirect()->route('hospitals.index')->with('success', 'Hospital deleted successfully.');
    }

    public function trashed()
    {
        $trashedHospitals = Hospital::onlyTrashed()->get();

        return Inertia::render("Hospitals/Trashed", [
            'trashedHospitals' => $trashedHospitals
        ]);
    }

    public function restore($id)
    {
        $hospital = Hospital::withTrashed()->findOrFail($id);
        $hospital->restore();

        return redirect()->route('hospitals.index')->with('success', 'Hospital restored successfully.');
    }

    public function forceDelete($id)
    {
        $hospital = Hospital::withTrashed()->findOrFail($id);
        $hospital->forceDelete();

        return redirect()->route('hospitals.index')->with('success', 'Hospital permanently deleted.');
    }
}
