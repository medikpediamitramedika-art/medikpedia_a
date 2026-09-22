<?php

namespace App\Http\Controllers;

use App\Models\PurchaseHistory;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class PurchaseHistoryController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'buyer_type'      => ['required', 'string', 'in:umum,apotik,dokter,pbf'],
            'buyer_name'      => ['required_unless:buyer_type,apotik,dokter', 'nullable', 'string', 'max:255'],
            'requester_name'  => ['nullable', 'string', 'max:255'],
            'outlet_name'     => ['nullable', 'string', 'max:255'],
            'pbf_name'        => ['nullable', 'string', 'max:255'],
            'pbf_address'     => ['nullable', 'string'],
            'phone'           => ['nullable', 'string', 'max:255'],
            'address'         => ['nullable', 'string'],
            'kecamatan'       => ['nullable', 'string', 'max:255'],
            'kota'            => ['nullable', 'string', 'max:255'],
            'sia'             => ['nullable', 'string', 'max:255'],
            'sipa'            => ['nullable', 'string', 'max:255'],
            'no_izin_pbf'     => ['nullable', 'string', 'max:255'],
            'apj'             => ['nullable', 'string', 'max:255'],
            'items'           => ['nullable', 'array'],
            'items.*.id'      => ['nullable', 'integer', 'exists:medicines,id'],
            'items.*.nama_obat' => ['nullable', 'string', 'max:255'],
            'items.*.name'     => ['nullable', 'string', 'max:255'],
            'items.*.quantity'=> ['nullable', 'integer', 'min:1'],
            'items.*.qty'     => ['nullable', 'integer', 'min:1'],
            'items.*.harga'   => ['nullable', 'integer', 'min:0'],
            'items.*.price'   => ['nullable', 'integer', 'min:0'],
            'items.*.catatan' => ['nullable', 'string'],
            'items.*.note'    => ['nullable', 'string'],
            'items.*.potongan'=> ['nullable', 'integer', 'min:0'],
            'items.*.discount'=> ['nullable', 'integer', 'min:0'],
            'total'           => ['required', 'integer', 'min:0'],
            'original_total'  => ['nullable', 'integer', 'min:0'],
            'discounted_total'=> ['nullable', 'integer', 'min:0'],
            'approval_status' => ['nullable', 'string', 'in:pending,approved,rejected'],
            'payment_method'  => ['nullable', 'string', 'max:255'],
            'payment_term_days' => ['nullable', 'integer', 'min:1'],
        ]);

        if (in_array($data['buyer_type'], ['apotik', 'dokter'], true)) {
            $data['buyer_name'] = $data['requester_name'] ?? ($data['buyer_name'] ?? null);
        }

        $paymentMethod = trim((string) ($data['payment_method'] ?? ''));
        $paymentTermDays = (int) ($data['payment_term_days'] ?? 0);
        if (in_array($data['buyer_type'], ['apotik', 'dokter'], true) && $paymentMethod === 'Tempo' && $paymentTermDays < 1) {
            return response()->json([
                'message' => 'Jumlah hari tempo wajib diisi untuk metode pembayaran Tempo.',
                'errors' => ['payment_term_days' => ['Jumlah hari tempo wajib diisi untuk metode pembayaran Tempo.']],
            ], 422);
        }

        if ($paymentMethod !== 'Tempo') {
            $data['payment_term_days'] = null;
        }

        $items = is_array($data['items'] ?? null) ? $data['items'] : [];
        $originalTotal = 0;
        $discountedTotal = 0;

        foreach ($items as $index => $item) {
            $qty = (int) ($item['quantity'] ?? $item['qty'] ?? 0);
            $price = (int) ($item['harga'] ?? $item['price'] ?? 0);
            if ($data['buyer_type'] === 'apotik' && !empty($item['id']) && $price <= 0) {
                $price = (int) round((float) (Medicine::whereKey($item['id'])->value('harga_modal') ?? 0));
            }
            $discount = min(
                (int) ($item['potongan'] ?? $item['discount'] ?? 0),
                $qty * $price
            );
            $subtotal = $qty * $price;
            $originalTotal += $subtotal;
            $discountedTotal += max(0, $subtotal - $discount);

            $items[$index]['harga'] = $price;
            $items[$index]['potongan'] = $discount;
        }

        $data['items'] = $items;
        $data['original_total'] = $originalTotal;
        $data['discounted_total'] = $discountedTotal;
        $data['total'] = $discountedTotal;
        $data['approval_status'] = $data['approval_status'] ?? 'pending';

        $history = DB::transaction(function () use ($data, $items) {
            $quantities = [];
            foreach ($items as $item) {
                if (empty($item['id']) || empty($item['quantity'])) {
                    continue;
                }

                $productId = (int) $item['id'];
                $quantities[$productId] = ($quantities[$productId] ?? 0) + (int) $item['quantity'];
            }

            $products = Medicine::whereIn('id', array_keys($quantities))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($quantities as $productId => $quantity) {
                $product = $products->get($productId);
                if (!$product || $product->stok < $quantity) {
                    $productName = $product?->nama_obat ?? 'Produk #' . $productId;
                    return [
                        'error' => "Stok {$productName} tidak mencukupi. Stok tersisa: " . ($product?->stok ?? 0) . '.',
                    ];
                }
            }

            foreach ($quantities as $productId => $quantity) {
                $products->get($productId)->decrement('stok', $quantity);
            }

            return ['history' => PurchaseHistory::create($data)];
        });

        if (isset($history['error'])) {
            return response()->json([
                'message' => $history['error'],
                'errors' => ['items' => [$history['error']]],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'id' => $history['history']->id,
            'invoice_url' => URL::temporarySignedRoute(
                'orders.invoice',
                now()->addDays(30),
                ['order' => $history['history']->id]
            ),
        ]);
    }

    public function invoice(PurchaseHistory $order)
    {
        $items = is_string($order->items) ? json_decode($order->items, true) : ($order->items ?? []);
        $items = is_array($items) ? $items : [];
        $productIds = collect($items)->pluck('id')->filter()->map(fn ($id) => (int) $id)->unique();
        $products = Medicine::whereIn('id', $productIds)->get()->keyBy('id');
        $items = array_map(function (array $item) use ($products): array {
            $product = $products->get((int) ($item['id'] ?? 0));
            if ($product) {
                $item['nama_obat'] = $item['nama_obat'] ?? $item['name'] ?? $product->nama_obat;
                if (!isset($item['harga']) && !isset($item['price'])) {
                    $item['harga'] = (int) ($product->harga_modal ?: $product->harga);
                }
            }
            return $item;
        }, $items);

        return view('orders.invoice', compact('order', 'items'));
    }

    public function purchaseOrder(PurchaseHistory $order)
    {
        $items = is_string($order->items) ? json_decode($order->items, true) : ($order->items ?? []);
        $items = is_array($items) ? $items : [];
        $items = array_map(function (array $item): array {
            if (!empty($item['id'])) {
                $item['harga_modal'] = Medicine::whereKey($item['id'])->value('harga_modal')
                    ?: ($item['harga'] ?? $item['price'] ?? 0);
            }
            return $item;
        }, $items);

        return view('admin.purchase-order', compact('order', 'items'));
    }
}
