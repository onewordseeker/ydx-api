@extends('layouts.app')
    @section('content')
     
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Content Row -->
                         <div class="text-center">
                        <div class="error mx-auto" style="font-size: 26px" data-text="{{ isset($hash) ? 'Congratulations !' : 'Transaction failed' }}">
                            {{ isset($hash) ? 'Congratulations !' : 'Transaction failed' }}
                        </div>
                        <p class="lead text-gray-800 mb-5">{{ isset($hash) ? 'Transaction is processing, you can explore the transaction below.' : (isset($error) ? $error : 'unknown error occured') }}</p>
                        <p class="text-gray-500 mb-0">{{ isset($hash) ? $hash : '' }}</p>
                        @php echo isset($hash) ? "<a target='_blank' href='".$url.$hash."'>&larr; Open Transaction</a>" : ''; @endphp
                        @php echo isset($error) ? "<a href=".url('')."<i class='fas fa-external-link-alt'></i> Back to Dashboard</a>" : ''; @endphp

                    </div>

            
                </div>
                <!-- /.container-fluid -->
    @endsection