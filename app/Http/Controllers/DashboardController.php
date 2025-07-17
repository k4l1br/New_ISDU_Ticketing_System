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
    // Only use real data
    $units = Ticket::select('unitResponsible')
                  ->distinct()
                  ->whereNotNull('unitResponsible')
                  ->pluck('unitResponsible')
                  ->toArray();

    $data = collect($units)->map(function($unit) {
        $in_progress = Ticket::where('unitResponsible', $unit)
                            ->where('status', 'like', '%in progress%')
                            ->count();
        $no_action = Ticket::where('unitResponsible', $unit)
                          ->where('status', 'like', '%no action%')
                          ->count();
        $completed = Ticket::where('unitResponsible', $unit)
                          ->where('status', 'like', '%complete%')
                          ->count();
        $total = $in_progress + $no_action + $completed;
        if ($total === 0) {
            return [
                'unit' => $unit,
                'in_progress' => 0,
                'no_action' => 0,
                'completed' => 0,
                'total' => 0
            ];
        }
        return [
            'unit' => $unit,
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
        $range = $request->query('range', 'weekly');

        if ($range === 'monthly') {
            $labels = [];
            $completed = [];
            $created = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $labels[] = $date->format('M');
                $completed[] = Ticket::where('status', 'like', '%complete%')
                                ->whereMonth('created_at', $date->month)
                                ->whereYear('created_at', $date->year)
                                ->count();
                $created[] = Ticket::whereMonth('created_at', $date->month)
                                ->whereYear('created_at', $date->year)
                                ->count();
            }
        } else {
            $labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            $completed = [];
            $created = [];
            $startOfWeek = Carbon::now()->startOfWeek();
            for ($i = 0; $i < 7; $i++) {
                $day = $startOfWeek->copy()->addDays($i);
                $completed[] = Ticket::where('status', 'like', '%complete%')
                                ->whereDate('created_at', $day)
                                ->count();
                $created[] = Ticket::whereDate('created_at', $day)
                                ->count();
            }
        }

        return response()->json([
            'labels' => $labels,
            'completed' => $completed,
            'created' => $created
        ]);
    }
}