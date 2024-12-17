<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class APIController extends Controller
{
    public function calculatePriceBeforeTax(Request $request)
    {
        $validated = $request->validate([
            'total' => 'required|numeric',
            'persen_pajak' => 'required|numeric'
        ]);

        $total = $validated['total'];
        $persenPajak = $validated['persen_pajak'];

        $netSales = ceil($total / (1 + ($persenPajak / 100)));
        $pajakRp = floor($netSales * ($persenPajak / 100));

        return response()->json([
            'net_sales' => $netSales,
            'pajak_rp' => $pajakRp
        ]);
    }

    public function calculateTotalDiscountLevel(Request $request)
    {
        $validated = $request->validate([
            'discounts' => 'required|array',
            'discounts.*.diskon' => 'required|numeric',
            'total_sebelum_diskon' => 'required|numeric'
        ]);

        $totalSebelumDiskon = $validated['total_sebelum_diskon'];
        $arrDiskon = $validated['discounts'];

        $sisaHarga = $totalSebelumDiskon;
        $totalHargaDiskon = 0;

        foreach ($arrDiskon as $item) {
            $persenDiskon = floatval($item['diskon']);
            $totalDiskon = round($sisaHarga * ($persenDiskon / 100));

            $totalHargaDiskon += $totalDiskon;
            $sisaHarga -= $totalDiskon;
        }

        $totalHargaSetelahDiskon = $totalSebelumDiskon - $totalHargaDiskon;

        return response()->json([
            'total_diskon' => $totalHargaDiskon,
            'total_harga_setelah_diskon' => $totalHargaSetelahDiskon
        ]);
    }

    public function calculateShareRevenue(Request $request)
    {
        $validated = $request->validate([
            'harga_sebelum_markup' => 'required|numeric',
            'markup_persen' => 'required|numeric',
            'share_persen' => 'required|numeric'
        ]);

        $hargaSebelumMarkup = $validated['harga_sebelum_markup'];
        $persenMarkup = $validated['markup_persen'];
        $persenKomisi = $validated['share_persen'];

        $jumlahMarkup = round($hargaSebelumMarkup * ($persenMarkup / 100));
        $hargaSetelahMarkup = $hargaSebelumMarkup + $jumlahMarkup;

        $jumlahHasilKomisi = round($hargaSetelahMarkup * ($persenKomisi / 100));
        $netResto = $hargaSetelahMarkup - $jumlahHasilKomisi;

        return response()->json([
            'net_untuk_resto' => $netResto,
            'share_untuk_ojol' => $jumlahHasilKomisi
        ]);
    }
}
