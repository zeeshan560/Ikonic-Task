<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\PayoutOrderJob;
use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;

class GreetingsController extends Controller
{
    public function index(){
		
		$data = array
		(
			'domain' => 'schimmel.com',
			'name' => 'Kassandra Koch',
			'email' => 'oosinski@gmail.com',
			'api_key' => 's,&y0v\SCN2Z$k'
		);
		
        $userid = User::insertUser($data);
        $data['userid'] = $userid;        
		$merchant = Merchant::insertMerchant($data);
		echo "<pre>"; print_r($merchant); exit;		
	}
}
