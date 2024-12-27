@extends('layouts.app')
    @section('content')
     
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Content Row -->
                         <div class="text-center">
                        <div class="error mx-auto" data-text="404">404</div>
                        <p class="lead text-gray-800 mb-5">Record Not Found</p>
                        <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
                        <a href="{{ url('') }}">&larr; Back to Dashboard</a>
                    </div>

            
                </div>
                <!-- /.container-fluid -->
    @endsection