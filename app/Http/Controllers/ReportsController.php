<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\FullReportExport;
use Barryvdh\DomPDF\Facade\PDF;

class ReportsController extends Controller
{
    public function exportFull(Request $request){
        $range=$request->input('range','7');
        $chart =$request-> input('chart');
        $export = new FullReportExport($range, $chart);
    // Use the PDF facade correctly (imported as PDF)
    $pdf = PDF::loadView('exports.full_report', $export->data());
    return $pdf->download("full-report-{$range}.pdf");
    }
}
