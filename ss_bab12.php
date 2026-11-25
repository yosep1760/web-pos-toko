<?php
use Illuminate\Support\Facades\DB;
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$mode = $argv[1] ?? 'cek';
$f = __DIR__ . '/storage/ss_bab12_id.txt';
if ($mode === 'set') {
    $p = DB::table('products')->where('code', 'UJI-009')->whereNull('deleted_at')->first();
if (!$p) {
    fwrite(STDERR, "Produk UJI-009 tidak ditemukan atau tidak aktif.\n");
    exit(1);
}
    DB::table('products')->where('id', $p->id)->update(['stock' => 1]);
    file_put_contents($f, $p->id);
}
$id = (int) @file_get_contents($f);
$p = DB::table('products')->where('id', $id)->first();
echo PHP_EOL . "=== KONDISI " . strtoupper($mode) . " ===" . PHP_EOL;
echo "Produk uji       : " . $p->code . " - " . $p->name . PHP_EOL;
echo "Stok produk      : " . $p->stock . PHP_EOL;
echo "Baris transactions        : " . DB::table('transactions')->count() . PHP_EOL;
echo "Baris transaction_details : " . DB::table('transaction_details')->count() . PHP_EOL;
echo "Waktu cek        : " . date('d-m-Y H:i:s') . PHP_EOL . PHP_EOL;