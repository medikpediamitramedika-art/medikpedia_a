<?php

namespace App\Http\Controllers;

use App\Models\PurchaseHistory;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PurchaseHistoryController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'buyer_type'      => ['required', 'string', 'in:umum,apotik,dokter,pbf'],
            'buyer_name'      => ['required', 'string', 'max:255'],
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
            'total'           => ['required', 'integer', 'min:0'],
            'original_total'  => ['nullable', 'integer', 'min:0'],
            'discounted_total'=> ['nullable', 'integer', 'min:0'],
            'approval_status' => ['nullable', 'string', 'in:pending,approved,rejected'],
            'payment_method'  => ['nullable', 'string', 'max:255'],
        ]);

        $items = is_array($data['items'] ?? null) ? $data['items'] : [];
        $originalTotal = 0;
        $discountedTotal = 0;

        foreach ($items as $item) {
            $qty = (int) ($item['quantity'] ?? $item['qty'] ?? 0);
            $price = (int) ($item['harga'] ?? $item['price'] ?? 0);
            $discount = (int) ($item['potongan'] ?? 0);
            $subtotal = $qty * $price;
            $originalTotal += $subtotal;
            $discountedTotal += max(0, $subtotal - $discount);
        }

        $data['original_total'] = $data['original_total'] ?? $originalTotal;
        $data['discounted_total'] = $data['discounted_total'] ?? $discountedTotal;
        $data['approval_status'] = $data['approval_status'] ?? 'pending';

        $history = PurchaseHistory::create($data);

        return response()->json([
            'success' => true,
            'id' => $history->id,
            'invoice_url' => URL::temporarySignedRoute(
                'orders.invoice',
                now()->addDays(30),
                ['order' => $history->id]
            ),
        ]);
    }

    public function invoice(PurchaseHistory $order)
    {
        $items = is_string($order->items) ? json_decode($order->items, true) : ($order->items ?? []);
        $items = is_array($items) ? $items : [];

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
