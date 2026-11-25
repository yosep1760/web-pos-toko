<?php

namespace App\Http\Controllers;

use App\Exports\SalesReportExport;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        [$start, $end] = $this->dateRange($request);

        $transactions = Transaction::with('user')
            ->whereBetween('created_at', [$start, $end])
            ->when($request->filled('cashier'), fn ($q) =>
                $q->where('user_id', $request->cashier))
            ->latest()
            ->get();

        $totalPenjualan = $transactions->sum('total');
        $jumlahTransaksi = $transactions->count();

        // Daftar kasir untuk isi dropdown
        $cashiers = User::where('role', 'kasir')
            ->orderBy('name')->get();

        return view('reports.sales', compact(
            'transactions', 'totalPenjualan', 'jumlahTransaksi',
            'start', 'end', 'cashiers'
        ));
    }

    public function export(Request $request)
    {
        [$start, $end] = $this->dateRange($request);

        $filename = 'laporan-penjualan-' . $start->format('Ymd')
            . '-' . $end->format('Ymd') . '.xlsx';

        return Excel::download(
            new SalesReportExport($start, $end, $request->cashier),
            $filename
        );
    }

    private function dateRange(Request $request): array
    {
        $start = $request->filled('start')
            ? Carbon::parse($request->start)
            : now()->subDays(6)->startOfDay();
        $end = $request->filled('end')
            ? Carbon::parse($request->end)->endOfDay()
            : now()->endOfDay();

        return [$start, $end];
    }
}