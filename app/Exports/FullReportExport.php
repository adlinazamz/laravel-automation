<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FullReportExport
{
    protected $range;
    protected $chart;

    public function __construct($range, $chart){
        $this-> range=$range;
        $this-> chart =$chart;
    }

    public function data(){
        switch ($this ->range){
            case 'today':
                $start=Carbon::today();
                $end =Carbon::today()->endOfDay();
            break;
            case 'yesterday':
                $start = Carbon::yesterday();
                $end=Carbon::yesterday()->endOfDay();
            break;
            case '7':
                $start = Carbon::now()->subDays(6)->startOfDay();
                $end=Carbon::now()->endOfDay();
            break;
            case '30':
                $start = Carbon::now()->subDays(29)->startOfDay();
                $end=Carbon::now()->endOfDay();
            break;
            case '90':
                $start = Carbon::now()->subDays(89)->startOfDay();
                $end=Carbon::now()->endOfDay();
            break;
            default:
                abort(400, 'Invalid range selected');
        }

        $summary = DB::table('products')
            ->selectRaw("type, COUNT(*) as total, 
                         SUM(CASE WHEN DATE(created_at) BETWEEN ? AND ? THEN 1 ELSE 0 END) as created_count,
                         SUM(CASE WHEN DATE(updated_at) BETWEEN ? AND ? THEN 1 ELSE 0 END) as updated_count", 
                         [$start, $end, $start, $end])
            ->groupBy('type')
            ->get();

        // Per-day details
        $details = DB::table('products')
            ->whereBetween('created_at', [$start, $end])
            ->orWhereBetween('updated_at', [$start, $end])
            ->orderBy('created_at')
            ->get();

        return [
            'range'   => $this->range,
            'chart'   => $this->chart,
            'summary' => $summary,
            'details' => $details,
            'start'   => $start,
            'end'     => $end,
        ];
    }
}
