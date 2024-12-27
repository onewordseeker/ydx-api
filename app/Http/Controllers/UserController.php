<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Wallets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function index()
    {
        Artisan::call('cache:clear');
        Artisan::call('optimize');
        Artisan::call('route:cache');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('config:cache');
        Artisan::call('config:clear');
        return response()->json(['message' => 'success', 'status' => 200], 200);
    }

    function logout()
    {
        auth()->guard('api')->logout();
        return response()->json(['message' => 'logout successful', 'status' => 200], 200);
    }

    function checkLogin() {
        return response()->json(['message' => 'success', 'status' => 200], 200);
    }

    public function getAllBalances(Request $request)
    {
        $user = auth()->user();
        $wallets = get_wallet($user);
        try {

            $balances = get_balances($user);
            $rates = getRates();
            $eth_gas_estimated = estimate_gas_price();
            $tron_gas_estimated = tronEstimatedGasFee();
            $response['coinData'] = [
                 [
                    'image' => 'bitcoin',
                    'name' => 'Bitcoin',
                    'tag' => 'BTC',
                    'balance' => (1 * number_format((float)$balances['btc'], 5, '.', '')),
                    'amount_usd' => (1 * number_format((float)($rates->bitcoin->usd * $balances['btc']), 4, '.', '')),
                    'rate' => $rates->bitcoin->usd,
                    'wallet_address' => $wallets['btc'],
                    'chain' => 'bitcoin',
                    'gas_fee' => 0.00023
                ],
                [
                    'image' => 'ethereum',
                    'name' => 'Ethereum',
                    'tag' => 'ETH',
                    'balance' => (1 * number_format((float)$balances['erc20_eth'], 4, '.', '')),
                    'amount_usd' => (1 * number_format((float)($rates->ethereum->usd * $balances['erc20_eth']), 4, '.', '')),
                    'rate' => $rates->ethereum->usd,
                    'wallet_address' => $wallets['ethereum'],
                    'chain' => 'ethereum',
                    'gas_fee' => $eth_gas_estimated
                ],
                [
                    'image' => 'tron',
                    'name' => 'Tron',
                    'tag' => 'TRX',
                    'balance' => (1 * number_format((float)$balances['trc20_trx'], 2, '.', '')),
                    'amount_usd' => (1 * number_format((float)($rates->tron->usd * $balances['trc20_trx']), 2, '.', '')),
                    'rate' => $rates->tron->usd,
                    'wallet_address' => $wallets['tron'],
                    'chain' => 'tron',
                    'gas_fee' => $tron_gas_estimated
                ],
                [
                    'image' => 'usdt',
                    'name' => 'USDT(ERC20)',
                    'tag' => 'USDT',
                    'balance' => (1 * number_format((float)$balances['erc20_usdt'], 2, '.', '')),
                    'amount_usd' => (1 * number_format((float)($balances['erc20_usdt']), 2, '.', '')),
                    'rate' => 1,
                    'wallet_address' => $wallets['ethereum'],
                    'chain' => 'ethereum',
                    'gas_fee' => $eth_gas_estimated
                ],
                [
                    'image' => 'usdt',
                    'name' => 'USDT(TRC20)',
                    'tag' => 'USDT',
                    'balance' => (1 * number_format((float)$balances['trc20_usdt'], 2, '.', '')),
                    'amount_usd' => (1 * number_format((float)($balances['trc20_usdt']), 2, '.', '')),
                    'rate' => 1,
                    'wallet_address' => $wallets['tron'],
                    'chain' => 'tron',
                    'gas_fee' => $tron_gas_estimated
                ],
                
               
            ];

            return response()->json(['data' => $response, 'message' => 'success', 'status' => 200], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage(), 'status' => 500], 500);
        }
    }

      public function getTransactions(Request $req)
    {
        try {
            $chain = strtolower($req->chain);
            $data = get_transactions(auth()->user(), $chain);
            return response()->json(['data' => $data, 'message' => 'success', 'status' => 200], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage(), 'status' => 500], 500);
        }
    }

    public function transfer(Request $req)
    {
        try {
            $_user = auth()->user();
            if(!$_user->active) {
                return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
            }
            $user = User::where('id', $_user->id)->first();
            $to = $req->to_address;
            $amount = $req->amount;
            $currency = $req->currency;
            $chain = $req->chain;
            if ($user && $to && $amount && $currency && $chain) {
                $contract_address = Null;
                if ($currency == 'usdt') {
                    if ($chain == 'ethereum')
                        $contract_address = env('erc20_usdt_contract_address');
                    else if ($chain == 'tron')
                        $contract_address = env('trc20_usdt_contract_address');
                }
              if($chain == 'bitcoin') {
              	$chain = 'btc';
              }
                $wallet = Wallets::where(['user_id' => $user->id, 'chain' => $chain])->first();
                if ($chain == 'ethereum') {
                    $response = transfer_erc20($wallet->wallet_address, Crypt::decryptString($wallet->private_key), $to,  $amount, $contract_address);
                    if (isset($response['hash'])) {
                        Transactions::create([
                            'user_id' => $user->id,
                            'hash' => $response['hash'],
                            'amount' => $amount,
                            'to_address' => $to,
                            'from_address' => $wallet->wallet_address,
                            'in_out' => 'transfer',
                            'currency' => $currency,
                            'chain' => 'ethereum',
                            'swap' => '0',
                        ]);
                        return response()->json(['message' => 'success', 'hash' => $response['hash'], 'status_code' => 200], 200);
                    } else {
                        return response()->json(['message' => 'error occured', 'error' => $response['error'], 'status_code' => 200], 200);
                    }
                } else if ($chain == 'tron') {
                    $response = transfer_trc20($wallet->wallet_address, Crypt::decryptString($wallet->private_key), $to,  $amount, $contract_address);
                    if (isset($response['hash'])) {
                        Transactions::create([
                            'user_id' => $user->id,
                            'hash' => $response['hash'],
                            'amount' => $amount,
                            'to_address' => $to,
                            'from_address' => $wallet->wallet_address,
                            'in_out' => 'transfer',
                            'currency' => $currency,
                            'chain' => 'tron',
                            'swap' => '0',
                        ]);
                        return response()->json(['message' => 'success', 'hash' => $response['hash'], 'status_code' => 200], 200);
                    } else {
                        return response()->json(['message' => 'error occured', 'error' => $response['error'], 'response' => $response, 'status_code' => 200], 200);
                    }
                } else if ($chain == 'btc') {  
                  $response = transfer_btc($wallet->wallet_address, Crypt::decryptString($wallet->private_key), $to,  $amount);
                    if (isset($response['hash'])) {
                        Transactions::create([
                            'user_id' => $user->id,
                            'hash' => $response['hash'],
                            'amount' => $amount,
                            'to_address' => $to,
                            'from_address' => $wallet->wallet_address,
                            'in_out' => 'transfer',
                            'currency' => $currency,
                            'chain' => 'btc',
                            'swap' => '0',
                        ]);
                        return response()->json(['message' => 'success', 'hash' => $response['hash'], 'status_code' => 200], 200);
                    } else {
                        return response()->json(['message' => 'error occured', 'error' => $response['error'], 'response' => $response, 'status_code' => 200], 200);
                    }
                } else {
                return response()->json(['message' => 'An error occurred: Invalid Request Data'], 401);
            }
            } else {
                return response()->json(['message' => 'An error occurred: Invalid Request Data'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function swap(Request $req)
    {
        try {
            $_user = auth()->user();
              if(!$_user->active) {
                return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
            }
            $user = User::where('id', $_user->id)->first();
            $to = $req->to;
            $from = $req->from;
            $amount = $req->amount;
            $chain = $req->chain;
            if ($user && $to && $amount && $from && $chain) {
                $wallet = Wallets::where(['user_id' => $user->id, 'chain' => $chain])->first();
                if ($chain == 'ethereum') {
                    $response = [];
                    if ($from == 'eth') {
                        $response = swapETHtoUSDT(Crypt::decryptString($wallet->private_key), $amount);
                    } else if ($from == 'usdt') {
                        $response = swapUSDTtoETH(Crypt::decryptString($wallet->private_key), $amount);
                    }
                    if (isset($response->hash)) {
                        Transactions::create([
                            'user_id' => $user->id,
                            'hash' => $response->hash,
                            'amount' => $amount,
                            'to_address' => $wallet->wallet_address,
                            'from_address' => $wallet->wallet_address,
                            'in_out' => 'swap',
                            'currency' => $from . ' to ' . $to,
                            'chain' => 'ethereum',
                            'swap' => '1',
                        ]);
                        return response()->json(['message' => 'success', 'hash' => $response->hash, 'status_code' => 200], 200);
                    } else {
                        return response()->json(['message' => 'Please check your balance and re-initiate your transaction', 'error' => $response->msg, 'status_code' => 400], 400);
                    }
                } else if ($chain == 'tron') {
                    $response = [];
                    if ($from == 'trx') {
                        $response = swapTRXtoUSDT(Crypt::decryptString($wallet->private_key), $amount);
                    } else if ($from == 'usdt') {
                        $response = swapUSDTtoTRX(Crypt::decryptString($wallet->private_key), $amount);
                    }
                    if (isset($response->hash)) {
                        Transactions::create([
                            'user_id' => $user->id,
                            'hash' => $response->hash,
                            'amount' => $amount,
                            'to_address' => $wallet->wallet_address,
                            'from_address' => $wallet->wallet_address,
                            'in_out' => 'swap',
                            'currency' => $from . ' to ' . $to,
                            'chain' => 'tron',
                            'swap' => '1',
                        ]);
                        return response()->json(['message' => 'success', 'hash' => $response->hash, 'status_code' => 200], 200);
                    } else {
                        return response()->json(['message' => 'error occured', 'error' => isset($response->error) ? $response->error : 'unknown', 'status_code' => 400], 400);
                    }
                }
            } else {
                return response()->json(['message' => 'An error occurred: Invalid Request Data'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function getSwapHistory(Request $req)
    {
        $user = Auth()->user();
        try {
            $transactions = Transactions::where(['swap' => '1', 'user_id' => $user->id])->get();
            return response()->json(['data' => $transactions, 'message' => 'success', 'status' => 200], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage(), 'status' => 500], 500);
        }
    }

    public function exportWallet(Request $req)
    {
        $chain = $req->chain;
        $user = Auth()->user();
          if(!$user->active) {
                return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
            }
        try {
            $wallet = Wallets::where(['user_id' => $user->id, 'chain' => $chain])->first();
            $wallet['private_key'] = Crypt::decryptString($wallet->private_key);
            $wallet['user'] = $user;
            return response()->json(['data' => $wallet, 'message' => 'success', 'status' => 200], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage(), 'status' => 500], 500);
        }
    }
}
