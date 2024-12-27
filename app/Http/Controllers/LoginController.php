<?php

namespace App\Http\Controllers;

use App\Models\AppSettings;
use App\Models\User;
use App\Models\Wallets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Denpa\Bitcoin\Client as BitcoinClient;

class LoginController extends Controller
{
    //
   
    public function create(Request $request)
    {
        try {
            $tron = create_ethereumWallet();
            $tron = $tron['data'];
            $mnemonics = '';
            $data = [];
            if(isset($tron->mnemonics)) {
                $mnemonics = $tron->mnemonics;
                $user_data = ['mnemonics' => $mnemonics, 'active' => 1];
                $user = new User($user_data);
                $user->save();
                create_wallets($user, $tron);
                return response()->json(['user' => $user,'message' => 'success', 'status' => 200], 200);
            } else {
                return response()->json(['message' => 'error occured', 'status' => 401], 400);
            }
            return response()->json(['data' => $data, 'message' => 'success', 'status' => 400], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage(), 'status' => 500], 500);
        }
    }
    public function import(Request $request)
    {
        // dd(tronEstimatedGasFee());
        // dd(get_btc_balance('166rvdvWJj9mEJfCPzzMWBuHkcce8kuHqE'));
        // $btc = bitcoinConnection();
        // $response = $btc->request('getblockchaininfo');
        // dd($response);
        // $bitcoind = new BitcoinClient([
        //     'scheme'        => 'http',                 // optional, default http
        //     'host'          => '144.172.79.61',            // optional, default localhost
        //     'port'          => 8332,                   // optional, default 8332
        //     'user'          => 'bitcoin_node',              // required
        //     'password'      => 'A123NodeBtc',          // required
        // ]);
        // $block = $bitcoind->getblockchaininfo();
 
        // dd($block);
        
        // dd($block);
        // return;
        // $ch = curl_init();
        // $username = 'root';
        // $pwd = 'ay8gw7x8DdeV4R';
        
        // curl_setopt_array(
        //     $ch, array(
        //     CURLOPT_URL => "$username:$pwd@144.172.79.61:8332",
        //     CURLOPT_RETURNTRANSFER => true
        // ));
        
        // $output = curl_exec($ch);
        // echo $output;
        // curl_close($ch);
        // exit;
        
        $user = User::where(['mnemonics' => $request->mnemonics])->first();
        try {
            if($user) {
                if($user->active) {
                    $wallets = Wallets::where(['user_id' => $user->id])->get();
                    Auth::login($user);
                    $token = auth()->user()->createToken('API Token')->plainTextToken;
                    return response()->json(['data' => $token, 'wallets' => $wallets, 'message' => 'success', 'status' => 200], 200);
                } else {
                    return response()->json(['message' => 'You mnemonics are blocked, please contact admin.', 'status' => 401], 401);
                }
            } else {
                $eth = import_ethWallet($request->mnemonics);
                // chicken marine music portion sponsor client zebra excuse what nest shoot leopard
                if(isset($eth['data']->mnemonics)) {
                    if($eth['data']->wallet[0]->walletPrivateKey) {
                        $tron = $eth['data'];
                        $mnemonics = '';
                        if(isset($tron->mnemonics)) {
                            $mnemonics = $tron->mnemonics;
                            $user_data = ['mnemonics' => $mnemonics, 'active' => 1];
                            $user = new User($user_data);
                            $user->save();
                            $user = User::where($user_data)->first();
                            Auth::login($user);
                            create_wallets($user, $tron);
                            $token = auth()->user()->createToken('API Token')->plainTextToken;
                            return response()->json(['user' => $user, 'data' => $token, 'message' => 'success', 'status' => 200], 200);
                        } else {
                            return response()->json(['message' => 'error occured', 'status' => 401], 400);
                        }
                    } else {
                        return response()->json(['message' => 'Error occured while importing wallet', 'status' => 401], 400);
                    }
                } else {
                    return response()->json(['message' => 'Mnemonics are invalid', 'status' => 401], 400);
                }
            }
            return response()->json(['message' => 'invalid mnemonics', 'status' => 401], 401);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage(), 'status' => 500], 500);
        }
    }

    public function getSettings(Request $request) {
        try {
          	// dd(get_btc_balance('166rvdvWJj9mEJfCPzzMWBuHkcce8kuHqE'));
            $settings = AppSettings::get();
            if($settings) {
                return response()->json(['data' => $settings, 'message' => 'success', 'status' => 200], 200);
            }
            return response()->json(['message' => 'No settigns found', 'status' => 400], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage(), 'status' => 500], 500);
        }
    }

}
