<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class TopProductsExport implements FromCollection, WithHeadings,
    WithMapping, WithTitle
{
    private int $no = 0;

    public function __construct(
        protected $start,
        protected $end,
        protected $cashierId = null
    ) {}

    public function collection()
    {
        return Product::select('products.code', 'products.name')
            ->selectRaw('SUM(transaction_details.qty) as total_qty,
                SUM(transaction_details.subtotal) as total_omzet')
            ->join('transaction_details',
                'transaction_details.product_id', '=', 'products.id')
            ->join('transactions',
                'transactions.id', '=',
                'transaction_details.transaction_id')
            ->whereBetween('transactions.created_at',
                [$this->start, $this->end])
            ->when($this->cashierId, fn ($q) =>
                $q->where('transactions.user_id', $this->cashierId))
            ->groupBy('products.id', 'products.code', 'products.name')
            ->orderByDesc('total_qty')
            ->get();
    }

    public function headings(): array
    {
        return ['No', 'Kode', 'Nama Produk', 'Terjual', 'Omzet'];
    }

    public function map($row): array
    {
        return [
            ++$this->no,
            $row->code,
            $row->name,
            $row->total_qty,
            $row->total_omzet,
        ];
    }

    public function title(): string
    {
        return 'Produk Terlaris';
    }
}