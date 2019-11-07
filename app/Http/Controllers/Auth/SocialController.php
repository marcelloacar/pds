<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator,Redirect,Response,File;
use Socialite;
use App\Shop\Customers\Customer;

class SocialController extends Controller
{
	public function redirect($provider)
	{
		return Socialite::driver($provider)->redirect();
	}

	public function callback($provider)
	{
		$getInfo = Socialite::driver($provider)->user(); 
		$user = $this->createUser($getInfo,$provider); 
		auth()->login($user); 
		return redirect()->to('/home');
	}

	function createUser($getInfo,$provider){
		$user = Customer::where('provider_id', $getInfo->id)->first();
		if (!$user) {
			$user = Customer::create([
				'name'     => $getInfo->name,
				'email'    => $getInfo->email,
				'provider' => $provider,
				'provider_id' => $getInfo->id
			]);
		}
		return $user;
	}
}