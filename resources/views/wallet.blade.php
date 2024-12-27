@extends('layouts.app')
    @section('content')
      
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">YDX App wallets</h1>
        <p class="mb-4">Below are the wallets, those are currently registered on YDX app.</p>
        <div class="row">
            
            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Wallet address</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Wallet options:</div>
                                <a class="dropdown-item" href="{{ url('wallet-withdraw?wallet-id='.$response[0]['id']) }}"><i class="fa fa-upload mr-1" style="color: grey"></i> Withdraw</a>
                                <a class="dropdown-item" onClick="copy('{{ $response[0]['private_key'] }}')" href="javascript:void(0)"><i class="fa fa-clipboard mr-1" style="color: grey"></i> Copy Private key</a>
                                <a class="dropdown-item" onClick="copy('{{ $response[0]['mnemonics'] }}')" href="javascript:void(0)"><i class="fa fa-clipboard mr-1" style="color: grey"></i> Copy Mnemonics</a>
                                <div class="dropdown-divider"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2" style="text-align: center"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ $response[0]['wallet_address'] }}" />
                        </div>
                        <div class="row" style="justify-content: center; margin-top: 20px">
                             <button class="btn btn-sm btn-warning" style="margin-right: 10px" onClick="copy('{{ $response[0]['private_key'] }}')">
                                Copy Private key
                            </button>
                            <a href="{{ url('wallet-withdraw?wallet-id='.$response[0]['id']) }}" style="margin-right: 10px" class="btn btn-sm btn-info">Withdraw</a>
                        </div>
                    </div>
                </div>
            </div>
            
             <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Wallet details</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                 <div class="dropdown-header">Wallet options:</div>
                                <a class="dropdown-item" href="{{ url('wallet-withdraw?wallet-id='.$response[0]['id']) }}"><i class="fa fa-upload mr-1" style="color: grey"></i> Withdraw</a>
                                <a class="dropdown-item" onClick="copy('{{ $response[0]['private_key'] }}')" href="javascript:void(0)"><i class="fa fa-clipboard mr-1" style="color: grey"></i> Copy Private key</a>
                                <a class="dropdown-item" onClick="copy('{{ $response[0]['mnemonics'] }}')" href="javascript:void(0)"><i class="fa fa-clipboard mr-1" style="color: grey"></i> Copy Mnemonics</a>
                                <div class="dropdown-divider"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                            <div class="row justify-content-space-between card mb-4 py-3 border-left-primary" style="flex-direction: row"><div class="col-md-3">Wallet address</div><div class="col-md-9 row"><a class="dropdown-item" onClick="copy('{{ $response[0]['wallet_address'] }}')" href="javascript:void(0)"><i class="fa fa-clipboard mr-1" style="color: grey"></i>{{ $response[0]['wallet_address'] }}</a></div></div>
                            <div class="row justify-content-space-between card mb-4 py-3 border-left-primary" style="flex-direction: row"><div class="col-md-3">{{ $response[0]['tag'] }} Balance</div><div class="col-md-9">{{ $response[0]['balance'] }} {{ $response[0]['tag'] }}</div></div>
                            
                            @if(isset($response[1]['tag'])) <div class="row justify-content-space-between card mb-4 py-3 border-left-primary" style="flex-direction: row"><div class="col-md-3">{{ $response[1]['tag'] }} Balance</div><div class="col-md-9">{{ $response[1]['balance'] }} {{ $response[1]['tag'] }}</div></div> @endif
                            <div class="row justify-content-space-between card mb-4 py-3 border-left-primary" style="flex-direction: row"><div class="col-md-3">Chain</div><div class="col-md-9"> {{ $response[0]['chain'] }}</div></div>
                            
                        </div>
                    </div>
                </div>
            </div>

        </div>
          <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Transactions list</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Hash</th>
                                            <th>Amount</th>
                                            <th>Chain</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1; @endphp
                                      	@php if(isset($transactions)) { @endphp
                                        @foreach($transactions as $transaction)
                                        @php $transaction = (array) $transaction; @endphp
                                         <tr>
                                            <td> {{ $i }} </td>
                                            <td>
                                                <div style="display: flex; flex-direction: row; justify-content: space-between; background-color: #f3f3f3; border-radius: 10px; color: black; padding: 5px; align-items: center;">
                                                    <p style="margin-bottom: 0px">
                                                        {{ $transaction['hash'] }}
                                                    </p>
                                                    <button class="btn btn-sm btn-secondary"  onClick="copy('{{ $transaction['hash'] }}')">
                                                        Copy
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <div style="display: flex; flex-direction: row; justify-content: space-between; background-color: #f3f3f3; border-radius: 10px; color: black; padding: 5px; align-items: center;">
                                                    <p style="margin-bottom: 0px">
                                                        {{ $transaction['value'] }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td>{{ $response[0]['chain'] }}</td>
                                            <td>
                                                <a target="_blank" href="{{ $response[0]['chain'] == 'tron' ? 'https://tronscan.org/#/transaction/'.$transaction['hash'] : ($response[0]['chain'] == 'ethereum' ? 'https://etherscan.io/tx/'.$transaction['hash'] : ($response[0]['chain'] == 'bitcoin' ? 'https://www.blockchain.com/explorer/transactions/btc/'.$transaction['hash'] : '#')) }}" class="btn btn-sm btn-success">Explore transaction</a>
                                            </td>
                                        </tr>
                                        @php $i++; @endphp
                                        @endforeach
                                      	@php } @endphp
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

    </div>
    <!-- /.container-fluid -->

  @endsection