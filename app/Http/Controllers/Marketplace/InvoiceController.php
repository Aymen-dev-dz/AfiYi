<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function download(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        // Generate a simple text invoice as a downloadable file
        $lines   = [];
        $lines[] = "=== FACTURE AF IYI ===";
        $lines[] = "Référence : {$order->reference}";
        $lines[] = "Date       : {$order->created_at->format('d/m/Y')}";
        $lines[] = "Client     : " . Auth::user()->name;
        $lines[] = "";
        $lines[] = "--- Articles ---";

        foreach ($order->items as $item) {
            $lines[] = sprintf(
                "%-40s x%d  %s EUR",
                $item->product_name,
                $item->quantity,
                number_format($item->subtotal, 2)
            );
        }

        $lines[] = "";
        $lines[] = str_repeat("-", 60);
        $lines[] = sprintf("TOTAL : %s EUR", number_format($order->total_price, 2));
        $lines[] = "";
        $lines[] = "Merci pour votre achat sur AF IYI !";

        $content  = implode("\n", $lines);
        $filename = "facture_{$order->reference}.txt";

        return response($content, 200, [
            'Content-Type'        => 'text/plain',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
