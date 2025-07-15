<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\reqOffice;
use App\Models\Reference;
use Illuminate\Http\Request;

class ticketController extends Controller
{
    /**
     * Display a listing of the tickets.
     */
    public function index()
    {
        $tickets = Ticket::all();
        return view('layouts.pages.ticket.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new ticket.
     */
   public function create()
{
    $positions = \App\Models\positionModel::orderBy('name')->pluck('name');
    $reqOffices = reqOffice::orderBy('reqOffice')->pluck('reqOffice');
    $references = Reference::orderBy('reference_type')->pluck('reference_type', 'id');
    $statuses = \App\Models\Status::orderBy('name')->pluck('name'); // <-- Add this line

    return view('layouts.pages.ticket.create', compact('positions', 'reqOffices', 'references', 'statuses'));
}

    /**
     * Store a newly created ticket in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullName' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'contactNumber' => 'required|string|max:255',
            'emailAddress' => 'required|email|max:255',
            'reqOffice' => 'required|string|max:255',
            'reference' => 'required|string|max:255', // or 'reference_id' if using foreign key
            'authority' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'unitResponsible' => 'required|string|max:255',
        ]);

        // If 'Others' is selected in reqOffice, use the custom value
        if ($request->reqOffice === 'Others' && $request->filled('reqOffice_other')) {
            $validated['reqOffice'] = $request->reqOffice_other;

            // Save 'Others' to session
            $otherOffices = session('other_offices', []);
            if (!in_array($request->reqOffice_other, $otherOffices)) {
                $otherOffices[] = $request->reqOffice_other;
                session(['other_offices' => $otherOffices]);
            }
        }

        Ticket::create($validated);

        return redirect()->route('ticket.index')->with('success', 'Ticket created successfully.');
    }

    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified ticket.
     */
    public function edit(Ticket $ticket)
    {
        $positions = \App\Models\positionModel::orderBy('name')->pluck('name')->toArray();
        $reqOffices = reqOffice::orderBy('reqOffice')->pluck('reqOffice')->toArray();
        $references = Reference::orderBy('reference_type')->pluck('reference_type', 'id');

        return view('layouts.pages.ticket.edit', compact('ticket', 'positions', 'reqOffices', 'references'));
    }

    /**
     * Update the specified ticket in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'fullName' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'contactNumber' => 'required|string|max:255',
            'emailAddress' => 'required|email|max:255',
            'reqOffice' => 'required|string|max:255',
            'reference' => 'required|string|max:255',
            'authority' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'unitResponsible' => 'required|string|max:255',
        ]);

        $ticket->update($validated);

        return redirect()->route('ticket.index')->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified ticket from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('ticket.index')->with('success', 'Ticket deleted successfully.');
    }

    public function newCount()
    {
        $count = Ticket::where('status', 'new')->count();
        return response()->json(['count' => $count]);
    }
}
