<div class="sidebar-wrapper" data-sidebar-layout="stroke-svg">
    <div>
        <div class="logo-wrapper">
            <a href="{{ route('dashboard') }}">
                <h4 class="text-primary">Tontine Parfums</h4>
            </a>
            <div class="back-btn"><i class="fa-solid fa-angle-left"></i></div>
            <div class="toggle-sidebar">
                <i class="status_toggle middle sidebar-toggle" data-feather="grid"></i>
            </div>
        </div>
        
        <nav class="sidebar-main">
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i data-feather="home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    @if(auth()->user()->isAdmin())
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                            <i data-feather="truck"></i>
                            <span>Fournisseurs</span>
                        </a>
                    </li>
                    @endif

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title {{ request()->routeIs('perfumes.*') ? 'active' : '' }}" href="{{ route('perfumes.index') }}">
                            <i data-feather="package"></i>
                            <span>Parfums</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title {{ request()->routeIs('tontines.*') ? 'active' : '' }}" href="{{ route('tontines.index') }}">
                            <i data-feather="briefcase"></i>
                            <span>Tontines</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title {{ request()->routeIs('subscriptions.*') ? 'active' : '' }}" href="{{ route('subscriptions.index') }}">
                            <i data-feather="users"></i>
                            <span>Mes Inscriptions</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title {{ request()->routeIs('payments.*') ? 'active' : '' }}" href="{{ route('payments.index') }}">
                            <i data-feather="credit-card"></i>
                            <span>Paiements</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
