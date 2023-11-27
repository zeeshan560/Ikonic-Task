<?php

namespace App\Services;

use App\Jobs\PayoutOrderJob;
use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
class MerchantService
{
    /**
     * Register a new user and associated merchant.
     * Hint: Use the password field to store the API key.
     * Hint: Be sure to set the correct user type according to the constants in the User model.
     *
     * @param array{domain: string, name: string, email: string, api_key: string} $data
     * @return Merchant
     */
    public function register(array $data): Merchant
    {
        // TODO: Complete this method
        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['api_key'];
        $user->type = User::TYPE_MERCHANT;
        $user->save();

		$merchant = new Merchant;
        $merchant->user_id = $user->id;
        $merchant->domain = $data['domain'];
        $merchant->display_name = $data['name'];
        $merchant->turn_customers_into_affiliates = 1;
        $merchant->default_commission_rate = 0.1;
        $merchant->save();

        return $merchant;
    }

    /**
     * Update the user
     *
     * @param array{domain: string, name: string, email: string, api_key: string} $data
     * @return void
     */
    public function updateMerchant(User $user, array $data): void 
    {
        // TODO: Complete this method
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['api_key'];
        $user->update();
    }

    /**
     * Find a merchant by their email.
     * Hint: You'll need to look up the user first.
     *
     * @param string $email
     * @return Merchant|null
     */
    public function findMerchantByEmail(string $email): ?Merchant
    {
        // TODO: Complete this method        
        $user = User::select('id')
                           ->where('email', '=', $email)
                           ->get();
        $merchant = Merchant::select('*')
                           ->where('user_id', '=', $user->id)
                           ->get();
        return $merchant|null;
    }

    /**
     * Pay out all of an affiliate's orders.
     * Hint: You'll need to dispatch the job for each unpaid order.
     *
     * @param Affiliate $affiliate
     * @return void
     */
    public function payout(Affiliate $affiliate)
    {
        // TODO: Complete this method
        Order::where('affiliate_id', '=', $affiliate->id)->update(['payout_status' => Order::STATUS_PAID]);
    }
}
