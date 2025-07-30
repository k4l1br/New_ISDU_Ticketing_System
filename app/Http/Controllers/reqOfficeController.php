<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\reqOffice;

class reqOfficeController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $offices = reqOffice::all();
        return view('reqOffice.index', compact('offices'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('reqOffice.create');
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $request->validate([
            'reqOffice' => 'required|string|max:255|unique:req_offices,req_office',
        ]);
        // Map camelCase input to snake_case for DB
        $data = [
            'req_office' => $request->input('reqOffice'),
        ];
        reqOffice::create($data);
        return redirect()->route('reqOffice.index')->with('success', 'Requesting Office created successfully.');
    }

    // Display the specified resource.
    public function show($id)
    {
        $office = reqOffice::findOrFail($id);
        return view('reqOffice.show', compact('office'));
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $office = reqOffice::findOrFail($id);
        return view('reqOffice.edit', compact('office'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        $request->validate([
            'reqOffice' => 'required|string|max:255|unique:req_offices,req_office,' . $id,
        ]);
        $office = reqOffice::findOrFail($id);
        $data = [
            'req_office' => $request->input('reqOffice'),
        ];
        $office->update($data);
        return redirect()->route('reqOffice.index')->with('success', 'Requesting Office updated successfully.');
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $office = reqOffice::findOrFail($id);
        $office->delete();
        return redirect()->route('reqOffice.index')->with('success', 'Requesting Office deleted successfully.');
    }
}