<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private function getCounts()
    {
        /** @var User $user */
        $user = Auth::user();
        
        if ($user->role === 'super_admin') {
            // Super admin sees all tickets
            return [
                'in_progress' => Ticket::where('status', 'In Progress')->count(),
                'no_action'   => Ticket::where('status', 'No Action')->count(),
                'completed'   => Ticket::where('status', 'Complete')->count(),
            ];
        } elseif ($user->role === 'admin') {
            // Admin sees tickets assigned to their unit OR assigned to them by name
            return [
                'in_progress' => Ticket::where('status', 'In Progress')
                                      ->where(function($query) use ($user) {
                                          if ($user->unit) {
                                              $query->where('unit_responsible', $user->unit);
                                          }
                                          $query->orWhere('unit_responsible', $user->name);
                                      })->count(),
                'no_action'   => Ticket::where('status', 'No Action')
                                      ->where(function($query) use ($user) {
                                          if ($user->unit) {
                                              $query->where('unit_responsible', $user->unit);
                                          }
                                          $query->orWhere('unit_responsible', $user->name);
                                      })->count(),
                'completed'   => Ticket::where('status', 'Complete')
                                      ->where(function($query) use ($user) {
                                          if ($user->unit) {
                                              $query->where('unit_responsible', $user->unit);
                                          }
                                          $query->orWhere('unit_responsible', $user->name);
                                      })->count(),
            ];
        } else {
            // Other users see no tickets
            return [
                'in_progress' => 0,
                'no_action'   => 0,
                'completed'   => 0,
            ];
        }
    }

    public function index()
    {
        $data = $this->getCounts();
        return view('shared.dashboard', ['data' => $data]);
    }

    public function getData()
    {
        $user = Auth::user();
        if (!$user || !($user->role === 'admin' || $user->role === 'super_admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return response()->json($this->getCounts());
    }

  public function getTicketsPerUnit()
{
    /** @var User $user */
    $user = Auth::user();
    if (!$user || !($user->role === 'admin' || $user->role === 'super_admin')) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    
    if ($user->role === 'super_admin') {
        // Super admin sees all admin users who have tickets assigned
        $adminUsers = Ticket::select('unit_responsible')
                          ->distinct()
                          ->whereNotNull('unit_responsible')
                          ->pluck('unit_responsible')
                          ->toArray();
    } elseif ($user->role === 'admin') {
        // Admin sees tickets assigned to their unit OR assigned to them by name
        // Get unique unit responsible values for tickets this admin can see
        $adminUsers = Ticket::where(function($query) use ($user) {
                if ($user->unit) {
                    $query->where('unit_responsible', $user->unit);
                }
                $query->orWhere('unit_responsible', $user->name);
            })
            ->select('unit_responsible')
            ->distinct()
            ->whereNotNull('unit_responsible')
            ->pluck('unit_responsible')
            ->toArray();
    } else {
        // Other users see no data
        $adminUsers = [];
    }

    $data = collect($adminUsers)->map(function($adminUser) use ($user) {
        // If the current user is an admin, only show counts for tickets they can access
        if ($user->role === 'admin' && $user->role !== 'super_admin') {
            $baseQuery = Ticket::where('unit_responsible', $adminUser)
                               ->where(function($query) use ($user) {
                                   if ($user->unit) {
                                       $query->where('unit_responsible', $user->unit);
                                   }
                                   $query->orWhere('unit_responsible', $user->name);
                               });
        } else {
            $baseQuery = Ticket::where('unit_responsible', $adminUser);
        }
        
        $in_progress = (clone $baseQuery)->where('status', 'In Progress')->count();
        $no_action = (clone $baseQuery)->where('status', 'No Action')->count();
        $completed = (clone $baseQuery)->where('status', 'Complete')->count();
        $total = $in_progress + $no_action + $completed;
        
        return [
            'unit' => $adminUser,
            'in_progress' => $in_progress,
            'no_action' => $no_action,
            'completed' => $completed,
            'total' => $total
        ];
    })->toArray();

    return response()->json(['data' => $data]);
}
    public function tasksReport(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user || !($user->role === 'admin' || $user->role === 'super_admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $range = $request->query('range', 'weekly');

        if ($range === 'monthly') {
            $labels = [];
            $completed = [];
            $created = [];
            $no_action = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $labels[] = $date->format('M');
                
                if ($user->role === 'super_admin') {
                    // Super admin sees all tickets
                    $completed[] = Ticket::where('status', 'Complete')
                                    ->whereMonth('created_at', $date->month)
                                    ->whereYear('created_at', $date->year)
                                    ->count();
                    $no_action[] = Ticket::where('status', 'No Action')
                                    ->whereMonth('created_at', $date->month)
                                    ->whereYear('created_at', $date->year)
                                    ->count();
                    $created[] = Ticket::whereMonth('created_at', $date->month)
                                    ->whereYear('created_at', $date->year)
                                    ->count();
                } elseif ($user->role === 'admin') {
                    // Admin sees tickets assigned to their unit OR assigned to them by name
                    $completed[] = Ticket::where('status', 'Complete')
                                    ->where(function($query) use ($user) {
                                        if ($user->unit) {
                                            $query->where('unit_responsible', $user->unit);
                                        }
                                        $query->orWhere('unit_responsible', $user->name);
                                    })
                                    ->whereMonth('created_at', $date->month)
                                    ->whereYear('created_at', $date->year)
                                    ->count();
                    $no_action[] = Ticket::where('status', 'No Action')
                                    ->where(function($query) use ($user) {
                                        if ($user->unit) {
                                            $query->where('unit_responsible', $user->unit);
                                        }
                                        $query->orWhere('unit_responsible', $user->name);
                                    })
                                    ->whereMonth('created_at', $date->month)
                                    ->whereYear('created_at', $date->year)
                                    ->count();
                    $created[] = Ticket::where(function($query) use ($user) {
                                        if ($user->unit) {
                                            $query->where('unit_responsible', $user->unit);
                                        }
                                        $query->orWhere('unit_responsible', $user->name);
                                    })
                                    ->whereMonth('created_at', $date->month)
                                    ->whereYear('created_at', $date->year)
                                    ->count();
                } else {
                    $completed[] = 0;
                    $no_action[] = 0;
                    $created[] = 0;
                }
            }
        } else {
            $labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            $completed = [];
            $created = [];
            $no_action = [];
            $startOfWeek = Carbon::now()->startOfWeek();
            for ($i = 0; $i < 7; $i++) {
                $day = $startOfWeek->copy()->addDays($i);
                
                if ($user->role === 'super_admin') {
                    // Super admin sees all tickets
                    $completed[] = Ticket::where('status', 'Complete')
                                    ->whereDate('created_at', $day)
                                    ->count();
                    $no_action[] = Ticket::where('status', 'No Action')
                                    ->whereDate('created_at', $day)
                                    ->count();
                    $created[] = Ticket::whereDate('created_at', $day)
                                    ->count();
                } elseif ($user->role === 'admin') {
                    // Admin sees tickets assigned to their unit OR assigned to them by name
                    $completed[] = Ticket::where('status', 'Complete')
                                    ->where(function($query) use ($user) {
                                        if ($user->unit) {
                                            $query->where('unit_responsible', $user->unit);
                                        }
                                        $query->orWhere('unit_responsible', $user->name);
                                    })
                                    ->whereDate('created_at', $day)
                                    ->count();
                    $no_action[] = Ticket::where('status', 'No Action')
                                    ->where(function($query) use ($user) {
                                        if ($user->unit) {
                                            $query->where('unit_responsible', $user->unit);
                                        }
                                        $query->orWhere('unit_responsible', $user->name);
                                    })
                                    ->whereDate('created_at', $day)
                                    ->count();
                    $created[] = Ticket::where(function($query) use ($user) {
                                        if ($user->unit) {
                                            $query->where('unit_responsible', $user->unit);
                                        }
                                        $query->orWhere('unit_responsible', $user->name);
                                    })
                                    ->whereDate('created_at', $day)
                                    ->count();
                } else {
                    $completed[] = 0;
                    $no_action[] = 0;
                    $created[] = 0;
                }
            }
        }

        return response()->json([
            'labels' => $labels,
            'completed' => $completed,
            'created' => $created,
            'no_action' => $no_action
        ]);
    }
}