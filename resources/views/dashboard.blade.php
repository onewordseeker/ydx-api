<x-app-layout>
    <div class="dashboard">
        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
        <div class="icons">
            <i class="fa-regular fa-message" style="color: #f4f4f4;"></i>
            <a href="profile"><i class="fa fa-gear" style="color: #f4f4f4;"></i></a>

            <div class="dropdown">
                <button class="dropdown-toggle" onclick="toggleDropdown()">
                    <span class="dropdown-icon"> <i class="fa-regular fa-user" style="color: #f4f4f4;"></i></span>
                </button>
                <ul class="dropdown-menu" id="dropdownMenu" style="padding: 0px;">
                    <a href="logout" style="text-decoration: none; height: 18px; padding: 0px 7px; background-color: #cc9a4f; border-radius: 50px; font-size: 12px;">Logout</a>
                </ul>
            </div>
        </div>

        <div class="main-body">
            <div class="heading">Overview</div>
            <div class="grid">
                <div class="stats">
                    <div class="round">
                        <p><span class="text-warning">{{ $user->rewards->count() ? $user->rewards[$user->rewards->count() - 1]->amount : 0 }}</span></p>
                        <p>Current Reward</p>
                    </div>
                </div>
                <div class="stats">
                    <div class="stats-inner">
                        <p>Name: <span class="text-warning">{{ Auth()->user()->first_name . ' ' . Auth()->user()->last_name  }}</span></p>
                    </div>
                    <div class="stats-inner">
                        <p>Email: <span class="text-warning">{{ Auth()->user()->email }}</span></p>
                    </div>
                </div>
                <div class="stats">
                    <div class="stats-inner">
                        <p>Phone Number: <span class="text-warning">{{ Auth()->user()->contact }}</span></p>
                    </div>
                    <div class="stats-inner">
                        <p>KYC Verified: <span class="text-warning">Yes</span></p>
                    </div>
                </div>
                <div class="stats">
                    <div class="stats-inner">
                        <p>Reward Sent: <span class="text-warning">{{ $user->rewards->count() ? $user->rewards[$user->rewards->count() - 1]->amount : 0 }}</span></p>
                    </div>
                    <div class="stats-inner">
                        <p>Max ID: <span class="text-warning">{{ $user->max_id ? $user->max_id : 'null' }}</span></p>
                    </div>
                </div>
            </div>
            <div class="total">
                <p>Total Reward Sent:</p>
                <span class="text-warning">{{ $user->rewards->count() ? $user->rewards[$user->rewards->count() - 1]->amount : 0 }}</span>
                <p class="text-warning">Basic</p>
            </div>
        </div>
        <div class="main-body second">
            <div class="heading">
                <p>Ranking</p>
            </div>
            <div class="text-bottom">
                <p>Current Ranking:</p> <span class="text-green">No Ranking</span>
            </div>
            <div class="text-bottom">
                <p>Next Ranking:</p> <span class="text-brown">Iron</span>
            </div>
            <div class="text-bottom">
                <p>Personal Investment:</p> <span>USD 1500</span>
            </div>
            <div class="text-bottom">
                <p>Direct Sponser</p> <span>Atleast 5 Direct associate referral line</span>
            </div>
            <div class="text-bottom">
                <p>Team Investment</p><span>USD 25000</span>
            </div>
            <div class="text-bottom">
                <p>Ranking Bonus</p><span>6%</span>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function toggleDropdown() {
        var dropdownMenu = document.getElementById("dropdownMenu");
        dropdownMenu.style.display = dropdownMenu.style.display === "none" ? "block" : "none";
    }
</script>