<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\reqOffice;
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
        // Get all unique requesting offices from reqOffice table
        $reqOffices = reqOffice::orderBy('reqOffice')->pluck('reqOffice');
        return view('layouts.pages.ticket.create', compact('reqOffices'));
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
            'reference' => 'required|string|max:255',
            'authority' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'unitResponsible' => 'required|string|max:255',
        ]);

        // If 'Others' is selected, use the custom value and store it in session for future use
        if ($request->reqOffice === 'Others' && $request->filled('reqOffice_other')) {
            $validated['reqOffice'] = $request->reqOffice_other;
            // Save to session for future population
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
        // return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified ticket.
     */
    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', compact('ticket'));
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

