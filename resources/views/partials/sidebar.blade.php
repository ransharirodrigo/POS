<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-shop"></i> @lang('messages.common.app_name')
    </div>
    <div class="sidebar-menu">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2"></i> @lang('messages.nav.dashboard')</a>
        @if(Auth::user()->role === 'super admin')
            <a href="{{ route('staff.index') }}" class="{{ request()->routeIs('staff.*') ? 'active' : '' }}"><i class="bi bi-people"></i> @lang('messages.nav.staff')</a>
        @endif 
        @canany(['category view', 'category manage'])
            <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}"><i class="bi bi-tags"></i> @lang('messages.categories.title')</a>
        @endcanany
        @canany(['product view', 'product manage'])
            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}"><i class="bi bi-box-seam"></i> @lang('messages.nav.products')</a>
        @endcanany
       
        <a href="#"><i class="bi bi-cart3"></i> @lang('messages.nav.sales')</a>
        <a href="#"><i class="bi bi-graph-up"></i> @lang('messages.nav.reports')</a>
        <a href="#"><i class="bi bi-gear"></i> @lang('messages.nav.settings')</a>
    </div>
</nav>