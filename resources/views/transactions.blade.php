@extends('layouts.app')
    @section('content')
      
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">YDX App users</h1>
                    <p class="mb-4">Below are the users, those are currently registered on YDX app.</p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Withdrawal Transactions</h6>
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
                                        @foreach($transactions as $transaction)
                                         <tr>
                                            <td> {{ $i }} </td>
                                            <td>
                                                <div style="display: flex; flex-direction: row; justify-content: space-between; background-color: #f3f3f3; border-radius: 10px; color: black; padding: 5px; align-items: center;">
                                                    <p style="margin-bottom: 0px">
                                                        {{ $transaction->hash }}
                                                    </p>
                                                    <button class="btn btn-sm btn-secondary"  onClick="copy('{{ $transaction->hash }}')">
                                                        Copy
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <div style="display: flex; flex-direction: row; justify-content: space-between; background-color: #f3f3f3; border-radius: 10px; color: black; padding: 5px; align-items: center;">
                                                    <p style="margin-bottom: 0px">
                                                        {{ $transaction->amount }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td>{{ $transaction->chain }}</td>
                                            <td>
                                                <a target="_blank" href="{{ $transaction->chain == 'tron' ? 'https://tronscan.org/#/transaction/'.$transaction->hash : ($transaction->chain == 'ethereum' ? 'https://etherscan.io/tx/'.$transaction->hash : ($transaction->chain == 'bitcoin' ? 'https://www.blockchain.com/explorer/transactions/btc/'.$transaction->hash : '#')) }}" class="btn btn-sm btn-success">Explore transaction</a>
                                            </td>
                                        </tr>
                                        @php $i++; @endphp
                                        @endforeach
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

  @endsection