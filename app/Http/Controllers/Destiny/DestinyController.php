<?php

namespace App\Http\Controllers\Destiny;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class DestinyController extends Controller
{
    public function connect(Request $request, $token = null)
    {
        $token = $token ?? $request->query('token');

        if (!$token) {
            abort(404, 'Destiny token missing.');
        }

        $item = OrderItem::where('destiny_token', $token)->first();

        if (!$item) {
            abort(404, 'Invalid Destiny token.');
        }

        // Must be paid
        if (!$item->order->isPaid()) {
            return redirect()->route('dashboard')->with('error', 'Order not paid. Destiny Connection unavailable.');
        }

        // Destiny matches are now unlimited (free) for all users!
        // session(['destiny_unlocked' => true]);

        // Store access in session
        session(['destiny_unlocked' => true]);

        return redirect()->route('destiny.lobby')->with('success', 'Destiny Connection Unlocked!');
    }

    public function qrCode($token)
    {
        $item = OrderItem::where('destiny_token', $token)->firstOrFail();
        
        $url = route('destiny.connect', ['token' => $token]);
        
        $options = new \chillerlan\QRCode\QROptions([
            'version'         => 5,
            'outputInterface' => \chillerlan\QRCode\Output\QRMarkupSVG::class,
            'eccLevel'        => \chillerlan\QRCode\Common\EccLevel::L,
            'svgAddXmlHeader' => true,
            'outputBase64'    => false,
        ]);
        
        $qrcode = new \chillerlan\QRCode\QRCode($options);
        $svg = $qrcode->render($url);
        
        return response($svg)->header('Content-Type', 'image/svg+xml');
    }

    public function downloadQrCode($token)
    {
        $item = OrderItem::where('destiny_token', $token)->firstOrFail();
        
        $url = route('destiny.connect', ['token' => $token]);
        
        $options = new \chillerlan\QRCode\QROptions([
            'version'         => 5,
            'outputInterface' => \chillerlan\QRCode\Output\QRMarkupSVG::class,
            'eccLevel'        => \chillerlan\QRCode\Common\EccLevel::L,
            'svgAddXmlHeader' => true,
            'outputBase64'    => false,
        ]);
        
        $qrcode = new \chillerlan\QRCode\QRCode($options);
        $svg = $qrcode->render($url);
        
        return response($svg)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="destiny-qrcode-' . $item->order->reference . '.svg"');
    }
}
