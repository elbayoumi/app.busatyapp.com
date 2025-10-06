<header>
    {{-- Main Navbar --}}
    <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
        <div class="navbar-container d-flex justify-content-between w-100">

            {{-- 🔍 Search Box --}}
            <x-layout.header-search />

            {{-- Right Side Navigation --}}
            <ul class="nav navbar-nav align-items-center ml-auto">

                {{-- 🌐 Language Switcher --}}
                @include('dashboard.layouts.inc.dropdown_language')

                {{-- 🌙 Dark Mode Toggle --}}
                <li class="nav-item d-none d-lg-block">
                    <a id="dark_mode" class="nav-link nav-link-style" title="Toggle Dark Mode">
                        <i class="ficon" data-feather="moon"></i>
                    </a>
                </li>

                {{-- 🔔 Notifications Dropdown --}}
                @include('dashboard.layouts.inc.dropdown_notification')

                {{-- 🙋‍♂️ User Dropdown --}}
                @include('dashboard.layouts.inc.dropdown_user')

            </ul>
        </div>
    </nav>

    {{-- 🔍 Optional Advanced Search Dropdown (hidden by default) --}}
    <ul class="main-search-list-defaultlist-other-list d-none">
        <li class="auto-suggestion justify-content-between">
            <a class="d-flex align-items-center justify-content-between w-100 py-50">
                <div class="d-flex justify-content-start">
                    <span class="mr-75" data-feather="alert-circle"></span>
                    <span>No results found.</span>
                </div>
            </a>
        </li>
    </ul>
</header>
