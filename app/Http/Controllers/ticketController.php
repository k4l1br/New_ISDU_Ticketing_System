<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\reqOffice;
use App\Models\Reference;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ticketController extends Controller
{
    /**
     * Display a listing of the tickets.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            // Super admin can see all tickets
            $tickets = Ticket::all();
        } elseif ($user->isAdmin()) {
            // Admin can see tickets assigned to their unit OR assigned to them by name
            $tickets = Ticket::where(function($query) use ($user) {
                if ($user->unit) {
                    $query->where('unitResponsible', $user->unit);
                }
                $query->orWhere('unitResponsible', $user->name);
            })->get();
        } else {
            $tickets = collect(); // No tickets for other users
        }
        
        return view('layouts.pages.ticket.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new ticket.
     */
   public function create()
    {
        $user = Auth::user();
        
        // Only super admin can create tickets
        if (!$user->isSuperAdmin()) {
            abort(403, 'Access denied. Only super administrators can create tickets.');
        }
        
        $positions = \App\Models\positionModel::orderBy('name')->pluck('name');
        $reqOffices = reqOffice::orderBy('req_office')->pluck('req_office');
        $references = Reference::orderBy('reference_type')->pluck('reference_type', 'id');
        $statuses = \App\Models\Status::orderBy('name')->pluck('name');
        
        // Get all admin users for assignment
        $adminUsers = User::where('role', 'admin')
                         ->orderBy('name')
                         ->get(['id', 'name', 'unit'])
                         ->toArray();
        
        // Debug: Log available admin users
        Log::info('Available admin users for assignment:', $adminUsers);

        return view('layouts.pages.ticket.create', compact('positions', 'reqOffices', 'references', 'statuses', 'adminUsers'));
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Only super admin can create tickets
        if (!$user->isSuperAdmin()) {
            abort(403, 'Access denied. Only super administrators can create tickets.');
        }
        
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
            'description' => 'nullable|string',
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

        // Map camelCase form field names to snake_case database column names
        $ticketData = [
            'full_name' => $validated['fullName'],
            'position' => $validated['position'],
            'designation' => $validated['designation'],
            'contact_number' => $validated['contactNumber'],
            'email_address' => $validated['emailAddress'],
            'req_office' => $validated['reqOffice'],
            'reference' => $validated['reference'],
            'authority' => $validated['authority'],
            'status' => $validated['status'],
            'unitResponsible' => $validated['unitResponsible'],
            'description' => $validated['description'] ?? null,
        ];

        Ticket::create($ticketData);

        return redirect()->route('ticket.index')->with('success', 'Ticket created successfully.');
    }

    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket)
    {
        $user = Auth::user();
        
        // Check if user can view this ticket
        if ($user->isSuperAdmin() || 
            ($user->isAdmin() && ($ticket->unitResponsible === $user->name || 
                                 ($user->unit && $ticket->unitResponsible === $user->unit)))) {
            return view('layouts.pages.ticket.show', compact('ticket'));
        }
        
        abort(403, 'Access denied.');
    }

    /**
     * Show the form for editing the specified ticket.
     */
    public function edit(Ticket $ticket)
    {
        $user = Auth::user();
        
        // Check if user can edit this ticket
        if (!$user->isSuperAdmin() && !($user->isAdmin() && 
            ($ticket->unitResponsible === $user->name || 
             ($user->unit && $ticket->unitResponsible === $user->unit)))) {
            abort(403, 'Access denied.');
        }
        
        $positions = \App\Models\positionModel::orderBy('name')->pluck('name')->toArray();
        $reqOffices = reqOffice::orderBy('req_office')->pluck('req_office')->toArray();
        $references = Reference::orderBy('reference_type')->pluck('reference_type', 'id');
        $statuses = \App\Models\Status::orderBy('name')->pluck('name');
        
        // Get available units from admin users
        $availableUnits = User::where('role', 'admin')
                             ->whereNotNull('unit')
                             ->distinct()
                             ->pluck('unit')
                             ->toArray();

        return view('layouts.pages.ticket.edit', compact('ticket', 'positions', 'reqOffices', 'references', 'statuses', 'availableUnits'));
    }

    /**
     * Update the specified ticket in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $user = Auth::user();
        
        // Check if user can update this ticket
        if (!$user->isSuperAdmin() && !($user->isAdmin() && 
            ($ticket->unitResponsible === $user->name || 
             ($user->unit && $ticket->unitResponsible === $user->unit)))) {
            abort(403, 'Access denied.');
        }

        if ($user->isSuperAdmin()) {
            // Super admin can update all fields
            $rules = [
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
            ];
            
            $validated = $request->validate($rules);
        } else {
            // Admin can only update status
            $validated = $request->validate([
                'status' => 'required|string|max:255',
            ]);
        }

        $ticket->update($validated);

        return redirect()->route('ticket.index')->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified ticket from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $user = Auth::user();
        
        // Only super admin can delete tickets
        if (!$user->isSuperAdmin()) {
            abort(403, 'Access denied.');
        }
        
        $ticket->delete();
        return redirect()->route('ticket.index')->with('success', 'Ticket deleted successfully.');
    }

    /**
     * Display tickets assigned to current admin user
     */
    public function myTickets()
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403, 'Access denied.');
        }
        
        // Admin can see tickets assigned to their unit OR assigned to them by name
        $tickets = Ticket::where(function($query) use ($user) {
            if ($user->unit) {
                $query->where('unitResponsible', $user->unit);
            }
            $query->orWhere('unitResponsible', $user->name);
        })->get();
        
        return view('layouts.pages.ticket.my-tickets', compact('tickets'));
    }

    public function newCount()
    {
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            $count = Ticket::where('status', 'new')->count();
        } elseif ($user->isAdmin()) {
            $count = Ticket::where('status', 'new')
                          ->where(function($query) use ($user) {
                              if ($user->unit) {
                                  $query->where('unitResponsible', $user->unit);
                              }
                              $query->orWhere('unitResponsible', $user->name);
                          })
                          ->count();
        } else {
            $count = 0;
        }
        
        return response()->json(['count' => $count]);
    }
}
