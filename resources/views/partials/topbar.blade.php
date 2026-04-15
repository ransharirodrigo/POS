<div class="topbar bg-white shadow-sm mb-4 d-flex justify-content-between align-items-center px-4 py-3">
    <div class="d-flex align-items-center">
        <button class="sidebar-toggle me-3" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
    </div>
    <div class="d-flex align-items-center gap-3 ms-auto">
        <a href="{{ route('pos.index') }}" class="btn btn-primary">
            <i class="bi bi-cart-plus me-1"></i> POS
        </a>
    </div>
    <div class="user-dropdown ms-3">
        <div class="user-dropdown-toggle d-flex align-items-center gap-2" onclick="document.getElementById('logoutForm').submit()" style="cursor: pointer;">
            <div class="user-avatar">{{ strtoupper(Auth::user()->fullName[0] . substr(Auth::user()->fullName, strpos(Auth::user()->fullName, ' ') + 1, 1)) }}</div>
            <span>{{ Auth::user()->fullName }}</span>
            <i class="bi bi-box-arrow-right"></i>
        </div>
        <form id="logoutForm" method="POST" action="{{ route('logout') }}">
            @csrf
        </form>
    </div>
</div>