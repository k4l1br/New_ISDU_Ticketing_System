<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function index()
    {
        // List all offices
        return view('admin.offices.index');
    }

    public function create()
    {
        // Show form to create a new office
        return view('admin.offices.create');
    }

    public function store(Request $request)
    {
        // Store a new office
        // TODO: Add validation and saving logic
        return redirect()->route('admin.offices.index');
    }

    public function edit($id)
    {
        // Show form to edit an office
        // TODO: Fetch office by $id
        return view('admin.offices.edit');
    }

    public function update(Request $request, $id)
    {
        // Update an office
        // TODO: Add validation and update logic
        return redirect()->route('admin.offices.index');
    }

    public function destroy($id)
    {
        // Delete an office
        // TODO: Add delete logic
        return redirect()->route('admin.offices.index');
    }
}
