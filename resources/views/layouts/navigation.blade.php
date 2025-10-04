<!-- Navigation Links -->
<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-nav-link>

    @auth
        @if(auth()->user()->isUser())
            <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                {{ __('My Reports') }}
            </x-nav-link>
        @endif

        @if(auth()->user()->isAdmin())
            <x-nav-link :href="route('admin.accounts.index')" :active="request()->routeIs('admin.accounts.*')">
                {{ __('Users') }}
            </x-nav-link>
            <x-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">
                {{ __('All Reports') }}
            </x-nav-link>
            <x-nav-link :href="route('admin.study-programs.index')" :active="request()->routeIs('admin.study-programs.*')">
                {{ __('Study Programs') }}
            </x-nav-link>
        @endif
    @endauth
</div>
