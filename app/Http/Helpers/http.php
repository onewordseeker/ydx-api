<?php

use App\Models\Wallets;
use IEXBase\TronAPI\Provider\HttpProvider;
use IEXBase\TronAPI\Tron;
use IEXBase\TronAPI\TRC20Contract;
use IEXBase\TronAPI\Exception\TronException;
use Illuminate\Support\Facades\Crypt;
use Denpa\Bitcoin\Client as BitcoinClient;

function ApiRequestsExecute($data = null)
{
    // dd($data);
    $auth = isset($data['auth']) ? $data['auth'] : 'Z4beILBI8a7zYjSW0Jg0oNRyllXCrRdeAgmArQqgCfWB3t9F';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $data['query']);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    if ($data['method'] == 'post') {
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 0);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        if (isset($data['fields'])) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data['fields']));
        }
    }
    if ($data['method'] == 'get') {
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        if (isset($data['fields'])) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data['fields']));
        }
    }
    if (isset($data['data_type'])) {
        if ($data['data_type'] == 'urlencoded') {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Authorization: " . $auth,
                "Content-Type: application/x-www-form-urlencoded",
                'api-key: ' . $auth
            ));
        }
    } else {
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: " . $auth,
            "Content-Type: application/json",
            'api-key: ' . $auth
        ));
    }
    // RESPONSE
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    return ['data' => json_decode($response), 'response_code' => $httpcode];
}

function tron($private_key = null)
{
    try {
        $fullNode = new HttpProvider('https://api.trongrid.io');
        $solidityNode = new HttpProvider('https://api.trongrid.io');
        $eventServer = new HttpProvider('https://api.trongrid.io');
        $tron = new Tron($fullNode, $solidityNode, $eventServer, null, null, $private_key);
        $contract = new TRC20Contract($tron, 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t');
        return ['tron' => $tron, 'contract' => $contract];
    } catch (TronException $e) {
        exit($e->getMessage());
    }
}

function get_wallet($user, $_wallet = null) {
    $wallets = Wallets::where(['user_id' => $user->id])->get();
    $_wallets = [];
    foreach($wallets as $wallet) {
        $_wallets[$wallet->chain] = $wallet;
    }
    if($_wallet)
    return $_wallets[$wallet];
    else return $_wallets;
}

function getNonce($address)
{
    $wallet_api = env('personal_node');
    $data['query'] = $wallet_api . 'ether/mainnet/nonce/' . $address;
    $data['method'] = 'get';
    $data['function_called'] = 'Get Balance';
    return  ApiRequestsExecute($data);
}


function validate_gas_price()
{
    $ch = curl_init();
    $url = 'https://api.etherscan.io/api?module=gastracker&action=gasoracle&apikey=RR2CE464DEKSBPPTJ23B6MAAFQFPGSJCZ2';
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    $data = json_decode($response);
    if ($data->result->FastGasPrice) {
        $safeGasPrice = $data->result->FastGasPrice;
        return $safeGasPrice * 1000000000;
    } else {
        return 0;
    }
}


function estimate_gas_price()
{
    $ch = curl_init();
    $url = 'https://api.etherscan.io/api?module=proxy&action=eth_getBlockByNumber&tag=latest&boolean=true&apikey=RR2CE464DEKSBPPTJ23B6MAAFQFPGSJCZ2';
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    $data = json_decode($response);
    if (isset($data->result) && isset($data->result->transactions)) {
        $latestTransaction = end($data->result->transactions); // Get the latest transaction
        $gasPriceWei = hexdec($latestTransaction->gasPrice);
        $gasUsedWei = hexdec($latestTransaction->gas);
        $gasFeeWei = $gasPriceWei * $gasUsedWei; // Gas fee in Wei
        $gasFeeEth = $gasFeeWei / 1e18;
        return $gasFeeEth ? $gasFeeEth : 0.000394;
    } else {
        return 0.000394;
    }
}

function getRates() {
    $rates = file_get_contents('https://api.coingecko.com/api/v3/simple/price?ids=ethereum,bitcoin,tron&vs_currencies=usd');
    $rates = json_decode($rates);
    return $rates;
}

function ERC20_Send($post_data)
{
    $wallet_api = env('personal_node');
    $data['query'] = $wallet_api . 'ether/mainnet/transfer';
    $data['method'] = 'post';
    $data['function_called'] = 'Send Transaction';
    $data['fields'] = $post_data;
    return ApiRequestsExecute($data);
}

function ERC20_token_Send($post_data)
{
    $wallet_api = env('personal_node');
    $data['query'] = $wallet_api . 'token/mainnet/transfer';
    $data['method'] = 'post';
    $data['function_called'] = 'Send Transaction';
    $data['fields'] = $post_data;
    return  ApiRequestsExecute($data);
}

function bitcoinConnection() {
    $rpcUser = 'bitcoin_node';
    $rpcPassword = 'A123NodeBtc';
    $rpcHost = '144.172.79.61';
    $rpcPort = 8332;
    $bitcoind = new BitcoinClient([
        'scheme'        => 'http',                 // optional, default http
        'host'          => $rpcHost,            // optional, default localhost
        'port'          => $rpcPort,                   // optional, default 8332
        'user'          => $rpcUser,              // required
        'password'      => $rpcPassword,          // required
        'ca'            => '/etc/ssl/ca-cert.pem',  // optional, for use with https scheme
        'preserve_case' => false,                  // optional, send method names as defined instead of lowercasing them
    ]);
    return $bitcoind;
}





