<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Services\MerchantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Order;

class MerchantController extends Controller
{
    public function __construct(
        MerchantService $merchantService
    ) {}

    /**
     * Useful order statistics for the merchant API.
     * 
     * @param Request $request Will include a from and to date
     * @return JsonResponse Should be in the form {count: total number of orders in range, commission_owed: amount of unpaid commissions for orders with an affiliate, revenue: sum order subtotals}
     */
    public function orderStats(Request $request): JsonResponse
    {
        // TODO: Complete this method
        $orders = DB::table('orders')->whereBetween('created_at', [$from, $to])->get();
        $countorders = count($orders);
        $commission_owed = DB::table('orders')->where('payout_status',Order::STATUS_UNPAID)->sum('commission_owed');
        $revenue = DB::table('orders')->sum('subtotal');
        return json_encode(array('count'=>$countorders,'commission_owed'=>$commission_owed,'revenue'=>$revenue));
    }
}
