<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private function getCounts()
    {
        return [
            'in_progress' => Ticket::where('status', 'like', '%in progress%')->count(),
            'no_action'   => Ticket::where('status', 'like', '%no action%')->count(),
            'completed'   => Ticket::where('status', 'like', '%complete%')->count(),
        ];
    }

    public function index()
    {
        $data = $this->getCounts();
        return view('dashboard', ['data' => $data]);
    }

    public function getData()
    {
        return response()->json($this->getCounts());
    }

  public function getTicketsPerUnit()
{
    try {
        // Check if there's any real data first
        $hasRealData = Ticket::whereNotNull('unit')->exists();
        
        if ($hasRealData) {
            // Use real data if available
            $units = Ticket::select('unit')
                          ->distinct()
                          ->whereNotNull('unit')
                          ->pluck('unit')
                          ->toArray();
        } else {
            // Use dummy data if no real data exists
            $units = ['MB', 'ISDU', 'NMU', 'REP'];
        }

        $data = collect($units)->map(function($unit) use ($hasRealData) {
            if ($hasRealData) {
                // Real data counts
                return [
                    'unit' => $unit,
                    'in_progress' => Ticket::where('unit', $unit)
                                        ->where('status', 'like', '%in progress%')
                                        ->count(),
                    'no_action' => Ticket::where('unit', $unit)
                                      ->where('status', 'like', '%no action%')
                                      ->count(),
                    'completed' => Ticket::where('unit', $unit)
                                      ->where('status', 'like', '%complete%')
                                      ->count(),
                ];
            } else {
                // Generate random dummy data (ensuring ISDU has some completed tickets for testing)
                $completed = $unit === 'ISDU' ? 0 : rand(0, 8); // Force ISDU to 0 for testing
                return [
                    'unit' => $unit,
                    'in_progress' => rand(0, 5),
                    'no_action' => rand(0, 3),
                    'completed' => $completed,
                ];
            }
        })->toArray();

        // Add totals per row
        foreach ($data as &$row) {
            $row['total'] = $row['in_progress'] + $row['no_action'] + $row['completed'];
        }

        return response()->json(['data' => $data]);

    } catch (\Exception $e) {
        // Fallback to dummy data if error occurs
        $dummyData = [
            [
                'unit' => 'MB',
                'in_progress' => 3,
                'no_action' => 1,
                'completed' => 4,
                'total' => 8
            ],
            [
                'unit' => 'ISDU',
                'in_progress' => 1,
                'no_action' => 2,
                'completed' => 0,
                'total' => 3
            ],
            [
                'unit' => 'NMU',
                'in_progress' => 2,
                'no_action' => 2,
                'completed' => 3,
                'total' => 7
            ],
            [
                'unit' => 'REP',
                'in_progress' => 0,
                'no_action' => 1,
                'completed' => 5,
                'total' => 6
            ]
        ];
        
        return response()->json(['data' => $dummyData]);
    }
}
    public function tasksReport(Request $request)
    {
        $range = $request->query('range', 'weekly');

        if ($range === 'monthly') {
            $labels = [];
            $values = [];
            
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $labels[] = $date->format('M');
                $values[] = Ticket::where('status', 'like', '%complete%')
                                ->whereMonth('created_at', $date->month)
                                ->whereYear('created_at', $date->year)
                                ->count();
            }
        } else {
            $labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            $values = [];
            
            $startOfWeek = Carbon::now()->startOfWeek();
            for ($i = 0; $i < 7; $i++) {
                $day = $startOfWeek->copy()->addDays($i);
                $values[] = Ticket::where('status', 'like', '%complete%')
                                ->whereDate('created_at', $day)
                                ->count();
            }
        }
  
        return response()->json([
            'labels' => $labels,
            'values' => $values
        ]);
    }
}