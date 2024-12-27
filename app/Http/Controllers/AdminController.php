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

class AdminController extends Controller
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

    function blockUser(Request $request) {
        $user_id = $request->get('user-id');
        $user = User::find($user_id);
        if ($user) {
            $user->active = 0;
            $user->tokens()->delete();
            $user->save();
            return back()->withInput()->with('message', 'User blocked successfully');
        } else {
            return view('404');
        }
    }
    function activateUser(Request $request) {
        $user_id = $request->get('user-id');
        $user = User::find($user_id);
        if ($user) {
            $user->active = 1;
            $user->tokens()->delete();
            $user->save();
            return back()->withInput()->with('message', 'User activated successfully');
        } else {
            return view('404');
        }
    }

    function logout() {
        auth()->logout();
        return redirect('login');
    }

    function login() {
        
        // Artisan::call('view:clear');
        // Artisan::call('config:cache');
        // Artisan::call('config:clear');
        // Artisan::call('route:clear');
        // Artisan::call('cache:clear');
        
        return view('auth.login');
    }
    
     function post_login(Request $request) {
        if($request->email && $request->password) {
         $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                // Authentication passed...
                return redirect()->intended('/');
            }
        }
        // Authentication failed...
        return back()->withInput()->with('status', 'Invalid credentials');
        // return view('auth.login');
    }
    
    function home() {
        $wallets = Wallets::all();
        $users = User::all();
        return view('index', compact('wallets', 'users'));
    }

    function users() {
        $users = User::all();
        return view('users', compact('users'));
    }
    
    function wallets() {
        $_wallets = Wallets::all();
        $wallets = [];
        foreach($_wallets as $wallet) {
            $_wallet = $wallet;
            $_wallet->_private_key = Crypt::decryptString($wallet->private_key);
            $_wallet->mnemonics = Crypt::decryptString($wallet->mnemonics);
            $wallets[] = $_wallet;
        }
        return view('wallets', compact('wallets'));
    }
    
    
    function wallet(Request $request) {
        $wallet = Wallets::where(['id' => $request->get('wallet-id')])->first();
        if($wallet) {
            $user = User::where(['id' => $wallet->user_id])->first();
            $wallets = get_wallet($user);
            $balances = get_balances($user);
            $rates = getRates();
            $_response = [
                 [
                     'id' => $wallets['btc']->id,
                    'image' => 'bitcoin',
                    'name' => 'Bitcoin',
                    'tag' => 'BTC',
                    'private_key' => Crypt::decryptString($wallets['btc']->private_key),
                    'mnemonics' => Crypt::decryptString($wallets['btc']->mnemonics),
                    'balance' => (1 * number_format((float)$balances['btc'], 4, '.', '')),
                    'amount_usd' => (1 * number_format((float)($rates->bitcoin->usd * $balances['btc']), 4, '.', '')),
                    'rate' => $rates->bitcoin->usd,
                    'wallet_address' => $wallets['btc']->wallet_address,
                    'chain' => 'bitcoin'
                ],
                [
                    'id' => $wallets['ethereum']->id,
                    'image' => 'ethereum',
                    'name' => 'Ethereum',
                    'tag' => 'ETH',
                    'private_key' => Crypt::decryptString($wallets['ethereum']->private_key),
                    'mnemonics' => Crypt::decryptString($wallets['ethereum']->mnemonics),
                    'balance' => (1 * number_format((float)$balances['erc20_eth'], 4, '.', '')),
                    'amount_usd' => (1 * number_format((float)($rates->ethereum->usd * $balances['erc20_eth']), 4, '.', '')),
                    'rate' => $rates->ethereum->usd,
                    'wallet_address' => $wallets['ethereum']->wallet_address,
                    'chain' => 'ethereum'
                ],
                [
                    'id' => $wallets['tron']->id,
                    'image' => 'tron',
                    'name' => 'Tron',
                    'tag' => 'TRX',
                    'private_key' => Crypt::decryptString($wallets['tron']->private_key),
                    'mnemonics' => Crypt::decryptString($wallets['tron']->mnemonics),
                    'balance' => (1 * number_format((float)$balances['trc20_trx'], 2, '.', '')),
                    'amount_usd' => (1 * number_format((float)($rates->tron->usd * $balances['trc20_trx']), 2, '.', '')),
                    'rate' => $rates->tron->usd,
                    'wallet_address' => $wallets['tron']->wallet_address,
                    'chain' => 'tron'
                ],
                [
                    
                    'id' => $wallets['ethereum']->id,
                    'image' => 'usdt',
                    'name' => 'USDT(ERC20)',
                    'tag' => 'USDT',
                    'private_key' => Crypt::decryptString($wallets['ethereum']->private_key),
                    'mnemonics' => Crypt::decryptString($wallets['ethereum']->mnemonics),
                    'balance' => (1 * number_format((float)$balances['erc20_usdt'], 2, '.', '')),
                    'amount_usd' => (1 * number_format((float)($balances['erc20_usdt']), 2, '.', '')),
                    'rate' => 1,
                    'wallet_address' => $wallets['ethereum']->wallet_address,
                    'chain' => 'ethereum'
                ],
                [
                    
                    'id' => $wallets['tron']->id,
                    'image' => 'usdt',
                    'name' => 'USDT(TRC20)',
                    'tag' => 'USDT',
                    'private_key' => Crypt::decryptString($wallets['tron']->private_key),
                    'mnemonics' => Crypt::decryptString($wallets['tron']->mnemonics),
                    'balance' => (1 * number_format((float)$balances['trc20_usdt'], 2, '.', '')),
                    'amount_usd' => (1 * number_format((float)($balances['trc20_usdt']), 2, '.', '')),
                    'rate' => 1,
                    'wallet_address' => $wallets['tron']->wallet_address,
                    'chain' => 'tron'
                ],
            ];
            $chain = strtolower($wallet->chain == 'btc' ? 'bitcoin' : $wallet->chain);
            $transactions = [];
            $transactions = get_transactions($user, $chain)[$chain == 'btc' ? 'bitcoin' : $chain];
            $response = [];
            foreach($_response as $_wallet) {
                if($_wallet['chain'] == $chain) {
                    $response[] = $_wallet;
                }
            }
            if(isset($response[0]['id'])) {
                return view('wallet', compact('response', 'transactions'));
            } else {
                return view('404');
            }
        } else {
            return view('404');
        }
    }
    
    function userWallets(Request $request) {
        $user = User::where(['id' => $request->get('user-id')])->first();
        if($user) {
            $wallets = get_wallet($user);
            $balances = get_balances($user);
            $rates = getRates();
            $response = [
                 [
                     'id' => $wallets['btc']->id,
                    'image' => 'bitcoin',
                    'name' => 'Bitcoin',
                    'tag' => 'BTC',
                    'private_key' => Crypt::decryptString($wallets['btc']->private_key),
                    'mnemonics' => Crypt::decryptString($wallets['btc']->mnemonics),
                    'balance' => (1 * number_format((float)$balances['btc'], 4, '.', '')),
                    'amount_usd' => (1 * number_format((float)($rates->bitcoin->usd * $balances['btc']), 4, '.', '')),
                    'rate' => $rates->bitcoin->usd,
                    'wallet_address' => $wallets['btc']->wallet_address,
                    'chain' => 'bitcoin'
                ],
                [
                    'id' => $wallets['ethereum']->id,
                    'image' => 'ethereum',
                    'name' => 'Ethereum',
                    'tag' => 'ETH',
                    'private_key' => Crypt::decryptString($wallets['ethereum']->private_key),
                    'mnemonics' => Crypt::decryptString($wallets['ethereum']->mnemonics),
                    'balance' => (1 * number_format((float)$balances['erc20_eth'], 4, '.', '')),
                    'amount_usd' => (1 * number_format((float)($rates->ethereum->usd * $balances['erc20_eth']), 4, '.', '')),
                    'rate' => $rates->ethereum->usd,
                    'wallet_address' => $wallets['ethereum']->wallet_address,
                    'chain' => 'ethereum'
                ],
                [
                    'id' => $wallets['tron']->id,
                    'image' => 'tron',
                    'name' => 'Tron',
                    'tag' => 'TRX',
                    'private_key' => Crypt::decryptString($wallets['tron']->private_key),
                    'mnemonics' => Crypt::decryptString($wallets['tron']->mnemonics),
                    'balance' => (1 * number_format((float)$balances['trc20_trx'], 2, '.', '')),
                    'amount_usd' => (1 * number_format((float)($rates->tron->usd * $balances['trc20_trx']), 2, '.', '')),
                    'rate' => $rates->tron->usd,
                    'wallet_address' => $wallets['tron']->wallet_address,
                    'chain' => 'tron'
                ],
                [
                    
                    'id' => $wallets['ethereum']->id,
                    'image' => 'usdt',
                    'name' => 'USDT(ERC20)',
                    'tag' => 'USDT',
                    'private_key' => Crypt::decryptString($wallets['ethereum']->private_key),
                    'mnemonics' => Crypt::decryptString($wallets['ethereum']->mnemonics),
                    'balance' => (1 * number_format((float)$balances['erc20_usdt'], 2, '.', '')),
                    'amount_usd' => (1 * number_format((float)($balances['erc20_usdt']), 2, '.', '')),
                    'rate' => 1,
                    'wallet_address' => $wallets['ethereum']->wallet_address,
                    'chain' => 'ethereum'
                ],
                [
                    
                    'id' => $wallets['tron']->id,
                    'image' => 'usdt',
                    'name' => 'USDT(TRC20)',
                    'tag' => 'USDT',
                    'private_key' => Crypt::decryptString($wallets['tron']->private_key),
                    'mnemonics' => Crypt::decryptString($wallets['tron']->mnemonics),
                    'balance' => (1 * number_format((float)$balances['trc20_usdt'], 2, '.', '')),
                    'amount_usd' => (1 * number_format((float)($balances['trc20_usdt']), 2, '.', '')),
                    'rate' => 1,
                    'wallet_address' => $wallets['tron']->wallet_address,
                    'chain' => 'tron'
                ],
            ];
            return view('wallet', compact('response'));
        } else {
            return view('404');
        }
    }

    function walletWithdraw(Request $request) {
        $walletId = $request->get('wallet-id');
        $wallet = Wallets::find($walletId);
        if($wallet) {
            $user = User::find($wallet->user_id);
            $balances = get_balances($user);
            $wallets = get_wallet($user);
            $rates = getRates();
            $_response = [
                 [
                     'id' => $wallets['btc']->id,
                    'image' => 'bitcoin',
                    'name' => 'Bitcoin',
                    'tag' => 'BTC',
                    'private_key' => Crypt::decryptString($wallets['btc']->private_key),
                    'mnemonics' => Crypt::decryptString($wallets['btc']->mnemonics),
                    'balance' => (1 * number_format((float)$balances['btc'], 4, '.', '')),
                    'amount_usd' => (1 * number_format((float)($rates->bitcoin->usd * $balances['btc']), 4, '.', '')),
                    'rate' => $rates->bitcoin->usd,
                    'wallet_address' => $wallets['btc']->wallet_address,
                    'chain' => 'bitcoin'
                ],
                [
                    'id' => $wallets['ethereum']->id,
                    'image' => 'ethereum',
                    'name' => 'Ethereum',
                    'tag' => 'ETH',
                    'private_key' => Crypt::decryptString($wallets['ethereum']->private_key),
                    'mnemonics' => Crypt::decryptString($wallets['ethereum']->mnemonics),
                    'balance' => (1 * number_format((float)$balances['erc20_eth'], 4, '.', '')),
                    'amount_usd' => (1 * number_format((float)($rates->ethereum->usd * $balances['erc20_eth']), 4, '.', '')),
                    'rate' => $rates->ethereum->usd,
                    'wallet_address' => $wallets['ethereum']->wallet_address,
                    'chain' => 'ethereum'
                ],
                [
                    'id' => $wallets['tron']->id,
                    'image' => 'tron',
                    'name' => 'Tron',
                    'tag' => 'TRX',
                    'private_key' => Crypt::decryptString($wallets['tron']->private_key),
                    'mnemonics' => Crypt::decryptString($wallets['tron']->mnemonics),
                    'balance' => (1 * number_format((float)$balances['trc20_trx'], 2, '.', '')),
                    'amount_usd' => (1 * number_format((float)($rates->tron->usd * $balances['trc20_trx']), 2, '.', '')),
                    'rate' => $rates->tron->usd,
                    'wallet_address' => $wallets['tron']->wallet_address,
                    'chain' => 'tron'
                ],
                [
                    
                    'id' => $wallets['ethereum']->id,
                    'image' => 'usdt',
                    'name' => 'USDT(ERC20)',
                    'tag' => 'USDT',
                    'private_key' => Crypt::decryptString($wallets['ethereum']->private_key),
                    'mnemonics' => Crypt::decryptString($wallets['ethereum']->mnemonics),
                    'balance' => (1 * number_format((float)$balances['erc20_usdt'], 2, '.', '')),
                    'amount_usd' => (1 * number_format((float)($balances['erc20_usdt']), 2, '.', '')),
                    'rate' => 1,
                    'wallet_address' => $wallets['ethereum']->wallet_address,
                    'chain' => 'ethereum'
                ],
                [
                    
                    'id' => $wallets['tron']->id,
                    'image' => 'usdt',
                    'name' => 'USDT(TRC20)',
                    'tag' => 'USDT',
                    'private_key' => Crypt::decryptString($wallets['tron']->private_key),
                    'mnemonics' => Crypt::decryptString($wallets['tron']->mnemonics),
                    'balance' => (1 * number_format((float)$balances['trc20_usdt'], 2, '.', '')),
                    'amount_usd' => (1 * number_format((float)($balances['trc20_usdt']), 2, '.', '')),
                    'rate' => 1,
                    'wallet_address' => $wallets['tron']->wallet_address,
                    'chain' => 'tron'
                ],
            ];
            $chain = strtolower($wallet->chain == 'btc' ? 'bitcoin' : $wallet->chain);
            $response = [];
            foreach($_response as $_wallet) {
                if($_wallet['chain'] == $chain) {
                    $response[] = $_wallet;
                }
            }
            if(isset($response[0]['id'])) {
                return view('wallet-withdraw', compact('response', 'wallet'));
            } else {
                return view('404');
            }
        } else {
            return view('404');
        }
    }

    public function walletWithdrawPost(Request $req)
    {
        try {
            $to = $req->to_address;
            $from = $req->from_address;
            $amount = $req->amount;
            $currency = strtolower($req->currency);
            if ($to && $amount && $currency && $from) {
                
                $wallet = Wallets::where(['wallet_address' => $from])->first();
                $user = User::where('id', $wallet->user_id)->first();
                $chain = $wallet->chain;
                $url = '';
                if($chain == 'tron') {
                    $url = 'https://tronscan.org/#/transaction/';
                } else if($chain == 'ethereum') {
                    $url = 'https://etherscan.io/tx/';
                } else if($chain == 'btc') {
                    $url = 'https://www.blockchain.com/explorer/transactions/btc/';
                }
                $contract_address = Null;
                if ($currency == 'usdt') {
                    if ($chain == 'ethereum')
                        $contract_address = env('erc20_usdt_contract_address');
                    else if ($chain == 'tron')
                        $contract_address = env('trc20_usdt_contract_address');
                }
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
                        $hash = $response['hash'];
                        return view('transaction', compact('hash', 'chain', 'url'));
                    } else {
                        $error = $response['error'];
                        return view('transaction', compact('error'));
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
                        $hash = $response['hash'];
                        return view('transaction', compact('hash', 'chain', 'url'));
                    } else {
                        $error = $response['error'];
                        return view('transaction', compact('error'));
                    }
                }
            } else {
                $error = 'An error occurred: Invalid Request Data';
                return view('transaction', compact('error'));
            }
        } catch (\Exception $e) {
            $error = 'An error occurred: ' . $e->getMessage();
            return view('transaction', compact('error'));
        }
    }


    public function swapHistory(Request $req)
    {
        try {
            $transactions = Transactions::where(['swap' => '1'])->orderBy('id' , 'desc')->get();
            return view('swap-transactions', compact('transactions'));
        } catch (\Exception $e) {
            return view('404');
        }
    }

    public function withdrawalHistory(Request $req)
    {
        try {
            $transactions = Transactions::where(['swap' => '0'])->orderBy('id' , 'desc')->get();
            return view('transactions', compact('transactions'));
        } catch (\Exception $e) {
            return view('404');
        }
    }

}
