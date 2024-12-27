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
                                            <th>Status</th>
                                            <th>Registered at</th>
                                            <th>Actions</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1; @endphp
                                        @foreach($users as $user)
                                      	@php
                                      		$date = $user->created_at;
                                      		$initialTimeZone = date_default_timezone_get();

                                      		$initialTimeZone = new DateTimeZone($initialTimeZone); // Replace 'Your_Initial_Timezone' with your initial timezone, e.g., 'UTC' or 'America/New_York'

                                            // Create a DateTime object with the initial date and timezone
                                            $dateTime = new DateTime($date, $initialTimeZone);

                                            // Set the target timezone to German timezone
                                            $germanTimeZone = new DateTimeZone('Europe/Berlin');

                                            // Convert the DateTime to German timezone
                                            $dateTime->setTimezone($germanTimeZone);

                                            // Get the converted date
                                            $germanDate = $dateTime->format('y M d H:i');

                                      	@endphp
                                        <tr>
                                            <td> {{ $i }} </td>
                                            <td>
                                                <div style="display: flex; flex-direction: row; justify-content: space-between; background-color: #f3f3f3; border-radius: 10px; color: black; padding: 5px; align-items: center;">
                                                    <p style="margin-bottom: 0px">
                                                        {{ $user->mnemonics }}
                                                    </p>
                                                    <button class="btn btn-sm btn-secondary"  onClick="copy('{{ $user->mnemonics }}')">
                                                        Copy
                                                    </button>
                                                </div>
                                            </td>
                                            <td> <p class="{{ $user->active == 1 ? 'btn-sm btn-circle btn-success' : 'btn-sm btn-circle btn-danger' }}"><i class="fa fa-{{ $user->active == 1 ? 'check'  : 'times' }}"></i></p></td>
                                            <td>{{ $germanDate }}</td>
                                            <td>
                                                <a href="{{ url('user-wallets?user-id='.$user->id) }}" class="btn btn-sm btn-success">explore wallets</a>
                                            </td>
                                            <td>
                                                @if($user->active)
                                                    <a href="#" data-toggle="modal" onClick="document.getElementById('blockBTN').href='{{ url('block-user?user-id='.$user->id) }}'" data-target="#banUserModal"  class="btn btn-sm btn-danger">Block</a>
                                                @elseif(!$user->active)
                                                    <a href="#" data-toggle="modal" onClick="document.getElementById('activateBTN').href='{{ url('activate-user?user-id='.$user->id) }}'" data-target="#activateUserModal"  class="btn btn-sm btn-success">Activate</a>
                                                @endif
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
                
    <!-- Logout Modal-->
    <div class="modal fade" id="banUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to block user?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">You are blocking this user, this user will not be able to perform any action on YDX Wallet app after blocked.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" id="blockBTN" href="#">Confirm</a>
                </div>
            </div>
        </div>
    </div>

               
    <!-- Logout Modal-->
    <div class="modal fade" id="activateUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to activate user?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">You are activating this user, this user will be able to perform all actions on YDX Wallet app after importing key phrase again.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" id="activateBTN" href="#">Confirm</a>
                </div>
            </div>
        </div>
    </div>

  @endsection