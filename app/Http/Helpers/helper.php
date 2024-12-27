<?php

use App\Models\Wallets;
use Illuminate\Support\Facades\Crypt;
include 'http.php';

// DB functions
function create_wallets($user, $eth) {
    try {
        $wallets = [];
        $tron = import_tronWallet($eth->mnemonics)['data'];
        if(isset($tron->mnemonics)) {
            $_wallets = [];
            $_wallets['mnemonics'] = Crypt::encryptString($tron->mnemonics);
            $_wallets['wallet_address'] = $tron->wallet[0]->address;
            $_wallets['private_key'] = Crypt::encryptString($tron->wallet[0]->privateKey);
            $_wallets['chain'] = 'tron';
            $_wallets['user_id'] = $user->id;
            $wallets[] = $_wallets;
        }
        if(isset($eth->mnemonics)) {
            $_wallets = [];
            $_wallets['mnemonics'] = Crypt::encryptString($eth->mnemonics);
            $_wallets['wallet_address'] = $eth->wallet[0]->walletAddress;
            $_wallets['private_key'] = Crypt::encryptString($eth->wallet[0]->walletPrivateKey);
            $_wallets['chain'] = 'ethereum';
            $_wallets['user_id'] = $user->id;
            $wallets[] = $_wallets;
        }
        $btc = import_btcWallet($eth->mnemonics)['data'];
        if(isset($btc->mnemonics)) {
            $_wallets = [];
            $_wallets['mnemonics'] = Crypt::encryptString($btc->mnemonics);
            $_wallets['wallet_address'] = $btc->wallet->walletAddress;
            $_wallets['private_key'] = Crypt::encryptString($btc->wallet->WIF);
            $_wallets['chain'] = 'btc';
            $_wallets['user_id'] = $user->id;
            $wallets[] = $_wallets;
        }
        $wallet = Wallets::insert($wallets);
        if(isset($btc->mnemonics)) {
            // importBTCWalletToNode($btc->wallet->WIF, $btc->wallet->walletAddress);
        }
        return $wallet;
    } catch (Exception $ex) {
        return ['message' => 'Error occured while creating wallet', 'exception' => $ex, 'status_code' => 501];
    }
}
function get_balances($user) {
    $balances = [];
    $_wallets = get_wallet($user);
    $usdt_balance = get_erc20_balance($_wallets['ethereum']->wallet_address, env('erc20_usdt_contract_address'))['data'];
    if(isset($usdt_balance->balance)) {
        $balances['erc20_usdt'] = $usdt_balance->balance;
    } else {
        $balances['erc20_usdt'] = 0;
    }
    $eth_balance = get_erc20_balance($_wallets['ethereum']->wallet_address, '')['data'];

    if(isset($eth_balance->balance)) {
        $balances['erc20_eth'] = $eth_balance->balance;
    } else {
        $balances['erc20_eth'] = 0;
    }
    $tron_balance = get_trc20_balance($_wallets['tron']->wallet_address, '');
    $balances['trc20_trx'] = $tron_balance;
    $trc_usdt_balance = get_trc20_balance($_wallets['tron']->wallet_address, env('trc20_usdt_contract_address'));
    $balances['trc20_usdt'] = $trc_usdt_balance;
    $balances['btc'] = get_btc_balance($_wallets['btc']->wallet_address);
    return $balances;
}
function get_transactions($user, $chain) {
    $transactions = ['tron' => [], 'ethereum' => [], 'btc' => []];
    $_wallets = get_wallet($user);
    if($chain == 'tron' || $chain == 'usdt(trc20)') {
        $trx_txns = get_trc20_transactions($_wallets['tron']->wallet_address);
        $transactions['tron'] = $trx_txns;
    } else if ($chain == 'ethereum') {
        $ethereum_txns = get_ethereum_transactions($_wallets['ethereum']->wallet_address);
        $transactions['ethereum'] = $ethereum_txns;
    } else if ($chain == 'usdt(erc20)') {
        $ethereum_txns = get_erc20_transactions($_wallets['ethereum']->wallet_address);
        $transactions['ethereum'] = $ethereum_txns;
    } else if ($chain == 'bitcoin') {
        $transactions['bitcoin'] = get_btc_transactions($_wallets['btc']->wallet_address);
    }
    // $trc_usdt_txns = get_trc20_transactions($_wallets['tron']->wallet_address);
    // if(isset($trc_usdt_txns['token_txns']->data)) {
    //     $transactions['tron_token'] = $trc_usdt_txns['token_txns']->data;
    // }
    return $transactions;
}

// create wallets
function create_tronWallet() {
    $wallet_api = env('personal_node');
    $data['query'] = $wallet_api . 'create-tron-wallet';
    $data['method'] = 'get';
    $data['function_called'] = 'Create Wallet';
    return ApiRequestsExecute($data);
}
function create_ethereumWallet() {
    $wallet_api = env('personal_node');
    $data['query'] = $wallet_api . 'create-mnemonics-wallet-eth?numWallet=1';
    $data['method'] = 'get';
    $data['function_called'] = 'Create Wallet';
    return ApiRequestsExecute($data);
}
function import_ethWallet($mnemonics) {
    $wallet_api = env('personal_node');
    $data['query'] = $wallet_api . 'get-eth-wallet-from-mnemonics';
    $data['method'] = 'get';
    $data['fields'] = ['mnemonics' => $mnemonics];
    $data['data_type'] = 'urlencoded';
    $data['function_called'] = 'Import Wallet';
    return ApiRequestsExecute($data);
}
function import_tronWallet($mnemonics) {
    $wallet_api = env('personal_node');
    $data['query'] = $wallet_api . 'get-tron-wallet-from-mnemonics';
    $data['method'] = 'get';
    $data['fields'] = ['mnemonics' => $mnemonics];
    $data['data_type'] = 'urlencoded';
    $data['function_called'] = 'Import Wallet';
    return ApiRequestsExecute($data);
}
function import_btcWallet($mnemonics) {
    $wallet_api = env('personal_node');
    $data['query'] = $wallet_api . 'get-btc-wallet-from-mnemonics';
    $data['method'] = 'get';
    $data['fields'] = ['mnemonics' => $mnemonics];
    $data['data_type'] = 'urlencoded';
    $data['function_called'] = 'Import Wallet';
    return ApiRequestsExecute($data);
}
function get_btcUnspentTxns($address) {
    $wallet_api = env('personal_node');
    $data['query'] = $wallet_api . 'get-btc-wallet-unspent';
    $data['method'] = 'get';
    $data['fields'] = ['address' => $address];
    $data['data_type'] = 'urlencoded';
    $data['function_called'] = 'Unspent Transactions';
    return ApiRequestsExecute($data);
}

// Transfer funds
function transfer_trc20($from_address, $from_privateKey, $address, float $amount, $smart_contract = null) {
    $contract = tron($from_privateKey);
    if ($smart_contract) {
        $response = $contract['contract']->transfer($address, $amount, $from_address);
        if (isset($response['result'])) {
            if(isset($response['txID'])) {
                return ['hash' => $response['txID']];
            }
        } else {
            return ['error' => 'transaction could not process. Please check your current balance'];
        }
    } else {
        $response = $contract['tron']->send($address, $amount, $from_address);
        if (isset($response['result'])) {
            if(isset($response['txID'])) {
                return ['hash' => $response['txID']];
            }
        } else {
            return ['error' => 'transaction could not process. Please check your current balance'];
        }
    }
}
function transfer_erc20($from, $from_private_key, $to, $amount, $smart_contract) {
    $nonce = getNonce($from);
    // dd($nonce);
    $nonce = $nonce['data'];
    $transaction_data = [
        'from_address' => $from,
        'to_address' => $to,
        'value' => $amount,
        'nonce' => $nonce->nonce,
        'from_private_key' =>$from_private_key
    ];

    $gas_price = validate_gas_price();
    if ($gas_price) {
        $transaction_data['gasLimit'] = '180000';
        $transaction_data['gasPrice'] = $gas_price;
    }
    if ($smart_contract) {
        $transaction_data['contract_address'] = $smart_contract;
        $response = ERC20_token_Send($transaction_data)['data'];
        if (isset($response->hash)) {
            return ['hash' => $response->hash];
        } else if(isset($response->msg)){
            return ['error' => $response->msg];
        } else {
            return ['error' => 'transaction could not process'];
        }
    } else {
        $response = ERC20_Send($transaction_data)['data'];
        if (isset($response->hash)) {
            return ['hash' => $response->hash];
        } else if(isset($response->msg)){
            return ['error' => $response->msg];
        } else {
            return ['error' => 'transaction could not process'];
        }
    }
}
function transfer_btc($from, $from_private_key, $to, $amount,) {
    $bitcoind = bitcoinConnection();
    $selectedInputs = [];
    $totalAmount = 0.00000000;
    $feePerByte = getNetworkFee($bitcoind);
    try {
         // Step 1: Get UTXOs
      	 $unspentList = [];
      	 $unspentResponse = get_btcUnspentTxns($from)['data'];
      	 if(isset($unspentResponse->result->data)){
        	if(isset($unspentResponse->result->data->list)) {
            	$unspentList = $unspentResponse->result->data->list;
            }
        }
      	
        foreach ($unspentList as $input) {
            // Check if $input is an array
            $selectedInputs[] = [
                'txid' => $input->tx_hash,
                'vout' => $input->tx_output_n,
            ];
            $totalAmount += $input->value/100000000;

            if ($totalAmount >= ($amount)) {
                // break;
            }
        }
        $changeAmount = (float) $totalAmount - (float) $amount;
        $fee = $feePerByte * strlen($bitcoind->createrawtransaction($selectedInputs, [$to => (string)$amount, $from => (string)$changeAmount])->get());
        $remaining = $changeAmount - $fee;
        	
		if($remaining <= 0) {
        	return ['error' => $totalAmount];
        }
        $rawTransaction = $bitcoind->createrawtransaction($selectedInputs,[
            $to => number_format($amount,8),
            $from => number_format($remaining,8)
        ]);
        $rawTransaction = $rawTransaction->get();
        // Step 3: Sign Raw Transaction
        $signedTransaction = $bitcoind->signrawtransactionwithkey($rawTransaction, [$from_private_key]);
        $signedTransaction = $signedTransaction->get();
        // Step 4: Broadcast Transaction
        $transactionId = $bitcoind->sendrawtransaction($signedTransaction['hex']);
        $transactionId = $transactionId->get();
        return ['hash' => $transactionId];
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }
    
}

// get_balance
function get_trc20_balance($address, $contract_address) {
    $tron = tron();
    if(!$contract_address) {
        if (array_key_exists('tron', $tron)) {
            return $tron['tron']->getBalance($address) / 1000000;
        }
    } else {
        if (array_key_exists('contract', $tron)) {
            return $tron['contract']->balanceOf($address);
        }
    }
}
function get_erc20_balance($wallet_address, $contract_address) {
    $wallet_api = env('personal_node');
    if($contract_address) {
        $data['query'] = $wallet_api . 'token/mainnet/address/'.$wallet_address.'/'.$contract_address;
    } else {
        $data['query'] = $wallet_api . 'ether/mainnet/address/'.$wallet_address;
    }
    $data['method'] = 'get';
    $data['function_called'] = 'Create Wallet';
    return ApiRequestsExecute($data);
}
function get_btc_balance($wallet_address) {
    $wallet_api = env('personal_node');
    $data['query'] = $wallet_api . 'get-btc-wallet-balance';
    $data['method'] = 'get';
  	$data['data_type'] = 'urlencoded';
    $data['fields'] = ['address' => $wallet_address];
    $data['function_called'] = 'Get BTC Balance';
  	$responnse = ApiRequestsExecute($data)['data'];
  	$balance = 0;
  	if(isset($responnse->result->data)) {
      $balance = isset($responnse->result->data->balance) ? $responnse->result->data->balance/100000000 : 0;
    }
    return $balance;
}

// get_transactions
function get_trc20_transactions($wallet_address) {
    $tron = tron();
    $contract = $tron['contract'];
    $transactions = [];
    $trx = $contract->getTransactions($wallet_address);
    $trc = $contract->getTokenTransactions($wallet_address);
    foreach($trx['data'] as $txn) {
        if(isset($txn['ret'])) {
            if($txn['ret'][0]['contractRet'] == 'SUCCESS') {
           if(isset($txn['raw_data']['contract'][0]['parameter']['value']['amount'])) {
                if(is_nan($txn['raw_data']['contract'][0]['parameter']['value']['amount'])) {
                    foreach($trc['data'] as $tkn_txn) {
                        if($tkn_txn['transaction_id'] == $txn['txID']) {
                            $transactions[] = [
                                'hash' => $txn['txID'],
                                'value' => $tkn_txn['value']/10000000,
                                'to' => $tkn_txn['to'],
                                'timeStamp' => date('Y-d-m h:i', $tkn_txn['block_timestamp']/1000),
                                'from' => $tkn_txn['from'],
                                'token' => $tkn_txn['token_info']
                            ];
                        }
                    }
                } else {
                    
                    $transactions[] = [
                        'hash' => $txn['txID'],
                        'blockNumber' => $txn['blockNumber'],
                        'timeStamp' => date('Y-d-m h:i', $txn['block_timestamp']/1000),
                        'value' => $txn['raw_data']['contract'][0]['parameter']['value']['amount'] > 0.00001 ? number_format($txn['raw_data']['contract'][0]['parameter']['value']['amount']/1000000) : number_format($txn['raw_data']['contract'][0]['parameter']['value']['amount']),
                        'to' => $tron['tron']->fromHex($txn['raw_data']['contract'][0]['parameter']['value']['to_address']),
                        'from' => $tron['tron']->fromHex($txn['raw_data']['contract'][0]['parameter']['value']['owner_address'])
                    ];
                }
            }
        }
        }
    }
    return $transactions;
}
function get_erc20_transactions($wallet_address) {
    $url = 'https://api.etherscan.io/api?module=account&action=tokentx&contract_address='.env('erc20_usdt_contract_address').'&address=' . $wallet_address . '&sort=asc&apikey=RR2CE464DEKSBPPTJ23B6MAAFQFPGSJCZ2';
    $response = file_get_contents($url);
    $response = json_decode($response);
    if (isset($response->result)) {
        $txns = [];
        foreach($response->result as $key => $value) {
            if($value->value == 0)
            continue;
            $value->value = $value->tokenDecimal ?  number_format((float)$value->value / ('1'.str_repeat(0, $value->tokenDecimal)), 2, '.', '') :  number_format((float)$value->value, 2, '.', '');
            $value->timeStamp = date('Y-m-d h:i', $value->timeStamp);
            $txns[] = $value;
        }
        return $txns;
    } else {
        return [];
    }
}
function get_ethereum_transactions($wallet_address) {
    $url = 'https://api.etherscan.io/api?module=account&action=txlist&address=' . $wallet_address . '&sort=asc&apikey=RR2CE464DEKSBPPTJ23B6MAAFQFPGSJCZ2';
    $response = file_get_contents($url);
    $response = json_decode($response);
    if (isset($response->result)) {
        $txns = [];
        foreach($response->result as $key => $value) {
            if($value->value == 0)
            continue;
            $value->value = $value->value / 1000000000000000000;
            $value->timeStamp = date('Y-m-d h:i', $value->timeStamp);
            $txns[] = $value;
        }
        return $txns;
    } else {
        return [];
    }
}

function get_btc_transactions($wallet_address) {
    $wallet_api = env('personal_node');
    $data['query'] = $wallet_api . 'get-btc-wallet-transactions';
    $data['method'] = 'get';
  	$data['data_type'] = 'urlencoded';
    $data['fields'] = ['address' => $wallet_address];
    $data['function_called'] = 'Get BTC Balance';
  	$responnse = ApiRequestsExecute($data)['data'];
  	$transactions = [];
  	if(isset($responnse->result->data)) {
      foreach($responnse->result->data->list as $txn) {
        $transactions[] = [
          'hash' => $txn->hash,
          'blockNumber' => $txn->block_height,
          'timeStamp' => date('Y-d-m h:i', $txn->block_time),
          'value' => $txn->inputs_value/100000000,
          'to' => $txn->outputs[0]->addresses[0],
          'from' => $txn->inputs[0]->prev_addresses[0]
        ];
      }
    }
    return $transactions;
}


function get_btc_transaction($bitcoind, $txid) {
    $transcation = $bitcoind->request('gettransaction',$txid);
    $transcation = $transcation->get();
    return $transcation;
}

// swap
function swapETHtoUSDT($private_key, $amount) {
    $wallet_api = env('personal_node');
    $data['query'] = $wallet_api . 'eth-to-usdt';
    $data['method'] = 'post';
    $data['fields'] = ['ETH_AMOUNT' => $amount, 'PRIVATE_KEY' => $private_key];
    $data['function_called'] = 'Swap';
    return ApiRequestsExecute($data)['data'];
}
function swapUSDTtoETH($private_key, $amount) {
    $wallet_api = env('personal_node');
    $data['query'] = $wallet_api . 'usdt-to-eth';
    $data['method'] = 'post';
    $data['fields'] = ['USDT_AMOUNT' => $amount, 'PRIVATE_KEY' => $private_key];
    $data['function_called'] = 'Swap';
    return ApiRequestsExecute($data)['data'];
}
function swapTRXtoUSDT($private_key, $amount) {
    $wallet_api = env('personal_node');
    $data['query'] = $wallet_api . 'tron-to-usdt';
    $data['method'] = 'get';
    $data['data_type'] = 'urlencoded';
    $data['fields'] = ['trx_amount' => $amount, 'private_key' => $private_key];
    $data['function_called'] = 'Swap';
    return ApiRequestsExecute($data)['data'];
}
function swapUSDTtoTRX($private_key, $amount) {
    $wallet_api = env('personal_node');
    $data['query'] = $wallet_api . 'usdt-to-tron';
    $data['method'] = 'get';
    $data['data_type'] = 'urlencoded';
    $data['fields'] = ['usdt_amount' => $amount, 'private_key' => $private_key];
    $data['function_called'] = 'Swap';
    return ApiRequestsExecute($data)['data'];
}

// estimated gas fee
function getNetworkFee($bitcoind){
    $bitcoind = bitcoinConnection();
    $feeInfo = $bitcoind->estimatesmartfee(2); // You can adjust the confirmation target as needed
    $feeRate = $feeInfo['feerate'] ?? 0.0001; // Use a default value if fee estimation fails
    // Convert fee rate to satoshis per byte
    $feePerByte = $feeRate / 1000;
    return $feePerByte;
}
function tronEstimatedGasFee() {
    // $api_url = 'https://api.trongrid.io/v1/transactions?limit=1&order_by=timestamp,desc';
    // $data['query'] = $api_url;
    // $data['method'] = 'get';
    // $data['function_called'] = 'Estimated Gas Wallet';
    // return ApiRequestsExecute($data);
    return rand(1, mt_getrandmax() - 1) / mt_getrandmax() * 10;
}

// import btc wallet to node
function importBTCWalletToNode($private, $wallet_address) {
    $bitcoind = bitcoinConnection();
    $bitcoind->request('importprivkey', $private, $wallet_address, false);
}

