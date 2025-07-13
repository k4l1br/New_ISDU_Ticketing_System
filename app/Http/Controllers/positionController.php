<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use App\Models\positionModel;

class positionController extends Controller
{
    // Show all positions
    public function index()
    {
        $positions = \App\Models\positionModel::all();
        return view('position.index', compact('positions'));
    }

    // Show create form
    public function create()
    {
        return view('position.create');
    }

    // Store new position
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:position_models,name',
        ]);

        $position = new positionModel();
        $position->name = $validated['name'];
        $position->save();

        return redirect()->route('position.index')->with('success', 'Position created successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $position = \App\Models\positionModel::findOrFail($id);
        return view('position.edit', compact('position'));
    }

    // Update position (only name)
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:position_models,name,' . $id,
        ]);

        $position = \App\Models\positionModel::findOrFail($id);
        $position->name = $validated['name'];
        $position->save();

        return redirect()->route('position.index')->with('success', 'Position updated successfully.');
    }

      public function destroy($id)
    {
        $position = \App\Models\positionModel::findOrFail($id);
        $position->delete();

        return redirect()->route('position.index')->with('success', 'Position deleted successfully.');
    }
}