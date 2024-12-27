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
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Withdraw funds</h1>
                             @if (session('status'))
                            <div class="mb-4 font-medium text-sm text-danger">
                                Error: {{ session('status') }}
                            </div>
                            @else
                            <div class="mb-4 font-smal text-sm">You are withdrawing funds from this wallet</div>
                            @endif
                        </div>
                        <form class="user">
                            @csrf
                            <div class="form-group">
                                <input type="text"  id="to_address" required name="to_address" onChange="setValue(this,'hiddenToAddress')" :value="old('to_address')" class="form-control form-control-user" placeholder="Enter receiver ({{ $wallet->chain }}) address">
                            </div>
                            <div class="form-group">
                                <input name="amount" required id="amount" type="text" onChange="setValue(this,'hiddenAmount')" :value="old('amount')" class="form-control form-control-user" placeholder="Amount">
                            </div>
                            <div class="form-group">
                                <select class="form-control select form-control" onChange="setValue(this, 'hiddenCurrency')">
                                    <option>{{ $response[0]['tag'] }}</option>
                                    @php echo $response[0]['tag'] != 'btc' ? "<option>USDT</option>" : ""; @endphp
                                </select>
                            </div>
                           <a data-toggle="modal" data-target="#withdrawModal" class="btn btn-primary btn-user btn-block">
                                Withdraw
                            </a>
                           
                        </form>
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
          
    </div>
    <!-- /.container-fluid -->
 <!-- Logout Modal-->
    <div class="modal fade" id="withdrawModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                  <form class="user" method="post" action="{{ route('wallet-withdraw-post') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to withdraw?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to withdraw funds from {{ $response[0]['wallet_address'] }} ?</p>
                        <input type="hidden"  id="hiddenToAddress" required name="to_address" class="form-control form-control-user">
                        <input name="amount" required id="hiddenAmount" type="hidden" class="form-control form-control-user">
                        <input name="currency" value="{{ $response[0]['tag'] }}" required id="hiddenCurrency" type="hidden" class="form-control form-control-user">
                        <input name="from_address" value="{{ $response[0]['wallet_address'] }}" required type="hidden" class="form-control form-control-user">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" href="{{ url('logout') }}">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    
<script type="text/javascript">
  function setValue(input1, Id) {
    var input2 = document.getElementById(Id);
    input2.value = input1.value;
  }
</script>
  @endsection