<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $offices = Office::query()
            ->when($request->name, fn($q, $name) => $q->where('name', 'like', "%$name%"))
            ->orderBy('name')
            ->paginate(10);

        return view('superadmin.offices.index', compact('offices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.offices.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:offices',
            'abbreviation' => 'required|string|max:50'
        ]);

        // Generate a random username and password since they're required in the DB
        $username = 'office_' . strtolower(str_replace(' ', '_', $request->abbreviation)) . '_' . rand(1000, 9999);
        $password = bcrypt('password123'); // Default password, should be changed later

        Office::create([
            'name' => $request->name,
            'abbreviation' => $request->abbreviation,
            'username' => $username,
            'password' => $password
        ]);

        return redirect()->route('admin.offices.index')
            ->with('success', 'Office created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Office $office)
    {
        return view('superadmin.offices.edit', compact('office'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Office $office)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:offices,name,' . $office->id,
            'abbreviation' => 'required|string|max:50'
        ]);

        // Don't update username and password, keep existing values
        $office->update([
            'name' => $request->name,
            'abbreviation' => $request->abbreviation
        ]);

        return redirect()->route('admin.offices.index')
            ->with('success', 'Office updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Office $office)
    {
        $office->delete();

        return redirect()->route('admin.offices.index')
            ->with('success', 'Office deleted successfully.');
    }
}
