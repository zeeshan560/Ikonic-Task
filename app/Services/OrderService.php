<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use App\Services\ApiService;

class OrderService
{
    public function __construct(
        protected AffiliateService $affiliateService
    ) {}

    /**
     * Process an order and log any commissions.
     * This should create a new affiliate if the customer_email is not already associated with one.
     * This method should also ignore duplicates based on order_id.
     *
     * @param  array{order_id: string, subtotal_price: float, merchant_domain: string, discount_code: string, customer_email: string, customer_name: string} $data
     * @return void
     */
    public function processOrder(array $data)
    {
        // TODO: Complete this method
        $user = User::select('id')
                           ->where('email', '=', $data['customer_email'])
                           ->get();
        $affiliate_s = Affiliate::select('*')
                           ->where('user_id', '=', $user->id)
                           ->get();
        $merchant = Merchant::select('id','default_commission_rate')
                           ->where('user_id', '=', $user->id)
                           ->get();
        if(count($affiliate_s) == 0){
            $api_service = new ApiService;
            $affiliate = new Affiliate;
            $affiliate->user_id = $user->id;
            $affiliate->merchant_id = $merchant->id;
            $affiliate->commission_rate = $merchant->default_commission_rate;
            $affiliate->discount_code = $api_service->createDiscountCode($merchant);
            $affiliate->save();
        }
        $order_s = Order::select('id')
                           ->where('id', '=', $data['order_id'])
                           ->get();
        if(count($order_s) == 0){
            $order = new Order;
            $order->merchant_id = $merchant->id;
            $order->affiliate_id = $affiliate->id;
            $order->commission_owed = $merchant->default_commission_rate;
            $order->payout_status = Order::STATUS_PAID;
            $order->discount_code = $api_service->createDiscountCode($merchant);
            $order->save();
        }
    }
}
