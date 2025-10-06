    <aside class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item"><a class="navbar-brand" href="{{ route('dashboard.home') }}">
                        <span class="brand-logo">
                            <img src="{{ image_or_placeholder(settings()->dashboard_logo_full_path) }}"
                                alt="{{ $settings?->name }}">
                        </span>
                        <h2 class="brand-text" style="color: black">{{ $settings?->name }}</h2>
                    </a></li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" style="margin: 27px 0;"
                        data-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4"
                            data-feather="x"></i><i
                            class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                            data-ticon="disc"></i></a></li>
            </ul>
        </div>

        <div class="shadow-bottom"></div>

        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

                <li class="nav-item @if (itemIsActive('dashboard', 'home')) {{ 'active' }} @endif">
                    <a class="d-flex align-items-center" href="{{ route('dashboard.home') }}">
                        <i data-feather="home"></i>
                        <span class="menu-title text-truncate" data-i18n="Home">الرئيسية</span>
                    </a>
                </li>



                @if (auth()->user()->canany([
                            'super',
                            'merchants-list',
                            'merchants-show',
                            'merchants-create',
                            'merchants-edit',
                            'merchants-destroy',
                        ]))
                    <li class="navigation-header"><span data-i18n="merchants">المستخدمين</span><i
                            data-feather="more-horizontal"></i></li>
                @endif
                @if (auth()->user()->canany(['super', 'schools-show', 'schools-create', 'schools-edit', 'schools-destroy']))
                    <li class="nav-item has-sub @if (isActive('question')) {{ 'sidebar-group-active open' }} @endif">
                        <a class="d-flex align-items-center" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-school"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6"></path>
                                <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4"></path>
                            </svg>
                            <span class="menu-title text-truncate" data-i18n="question">الأسئله الشائعة</span>
                        </a>
                        <ul class="menu-content">
                            @if (auth()->user()->canany(['super', 'schools-show', 'schools-create', 'schools-edit', 'schools-destroy']))
                                <li class="@if (itemIsActive('question', 'index')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.question.index') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">عرض</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->canany(['super', 'schools-create']))
                                <li class="@if (itemIsActive('question', 'create')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.question.create') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="New">اضافة</span>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif
                @if (auth()->user()->canany(['super', 'staff-list', 'staff-show', 'staff-create', 'staff-edit', 'staff-destroy']))
                    <li
                        class="nav-item has-sub @if (isActive('app')) {{ 'sidebar-group-active open' }} @endif">
                        <a class="d-flex align-items-center" href="#">
                            <i data-feather='briefcase'></i>
                            <span class="menu-title text-truncate" data-i18n="Staff">تطبيقات</span>
                        </a>
                        <ul class="menu-content">
                            @if (auth()->user()->canany(['super', 'staff-list', 'staff-show', 'staff-create', 'staff-edit', 'staff-destroy']))
                                <li class="@if (itemIsActive('app', 'index')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.app.index') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">عرض</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->canany(['super', 'staff-create']))
                                <li class="@if (itemIsActive('app', 'create')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.app.create') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="New">اضافة</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (auth()->user()->canany(['super', 'schools-show', 'schools-create', 'schools-edit', 'schools-destroy']))
                    <li
                        class="nav-item has-sub @if (isActive('trip-type')) {{ 'sidebar-group-active open' }} @endif">
                        <a class="d-flex align-items-center" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-school"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6"></path>
                                <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4"></path>
                            </svg>
                            <span class="menu-title text-truncate" data-i18n="trip-type">انواع الرحلات </span>
                        </a>
                        <ul class="menu-content">
                            @if (auth()->user()->canany(['super', 'schools-show', 'schools-create', 'schools-edit', 'schools-destroy']))
                                <li class="@if (itemIsActive('trip-type', 'index')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.trip-type.index') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">عرض</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->canany(['super', 'schools-create']))
                                <li class="@if (itemIsActive('trip-type', 'create')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('dashboard.trip-type.create') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="New">اضافة</span>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif
                @if (auth()->user()->canany(['super', 'schools-show', 'schools-create', 'schools-edit', 'schools-destroy']))
                    <li
                        class="nav-item has-sub @if (isActive('schools')) {{ 'sidebar-group-active open' }} @endif">
                        <a class="d-flex align-items-center" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-school"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6"></path>
                                <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4"></path>
                            </svg>
                            <span class="menu-title text-truncate" data-i18n="schools">المدارس</span>
                        </a>
                        <ul class="menu-content">
                            @if (auth()->user()->canany(['super', 'schools-show', 'schools-create', 'schools-edit', 'schools-destroy']))
                                <li class="@if (itemIsActive('schools', 'index')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.schools.index') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">عرض</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->canany(['super', 'schools-create']))
                                <li class="@if (itemIsActive('schools', 'create')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.schools.create') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="New">اضافة</span>
                                    </a>
                                </li>
                            @endif

                            @if (auth()->user()->canany(['super', 'students-create']))
                                <li class="@if (itemIsActive('schools', 'upload')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.students.upload') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="New">اضافة مجموعة من الطلاب من
                                            ملف
                                            اكسل</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (auth()->user()->canany(['super', 'attendants-show', 'attendants-create', 'attendants-edit', 'attendants-destroy']))
                    <li
                        class="nav-item has-sub @if (isActive('attendants')) {{ 'sidebar-group-active open' }} @endif">
                        <a class="d-flex align-items-center" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-plus"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                <path d="M16 11h6m-3 -3v6"></path>
                            </svg>
                            <span class="menu-title text-truncate" data-i18n="attendants">المرافقين</span>
                        </a>
                        <ul class="menu-content">
                            @if (auth()->user()->canany(['super', 'attendants-show', 'attendants-create', 'attendants-edit', 'attendants-destroy']))
                                <li class="@if (itemIsActive('attendants', 'index')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('dashboard.attendants.index') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">عرض</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->canany(['super', 'attendants-create']))
                                <li class="@if (itemIsActive('attendants', 'create')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('dashboard.attendants.create') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="New">اضافة</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (auth()->user()->canany(['super', 'parents-show', 'parents-create', 'parents-edit', 'parents-destroy']))
                    <li
                        class="nav-item has-sub @if (isActive('parents')) {{ 'sidebar-group-active open' }} @endif">
                        <a class="d-flex align-items-center" href="#">
                            <i data-feather='user'></i>
                            <span class="menu-title text-truncate" data-i18n="parents">اولياء الامور</span>
                        </a>
                        <ul class="menu-content">
                            @if (auth()->user()->canany(['super', 'parents-show', 'parents-create', 'parents-edit', 'parents-destroy']))
                                <li class="@if (itemIsActive('parents', 'index')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.parents.index') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">عرض</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->canany(['super', 'parents-create']))
                                <li class="@if (itemIsActive('parents', 'create')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.parents.create') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="New">اضافة</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (auth()->user()->canany(['super', 'buses-show', 'buses-create', 'buses-edit', 'buses-destroy']))
                    <li
                        class="nav-item has-sub @if (isActive('buses')) {{ 'sidebar-group-active open' }} @endif">
                        <a class="d-flex align-items-center" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bus"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M6 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                <path d="M18 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                <path d="M4 17h-2v-11a1 1 0 0 1 1 -1h14a5 7 0 0 1 5 7v5h-2m-4 0h-8"></path>
                                <path d="M16 5l1.5 7l4.5 0"></path>
                                <path d="M2 10l15 0"></path>
                                <path d="M7 5l0 5"></path>
                                <path d="M12 5l0 5"></path>
                            </svg>
                            <span class="menu-title text-truncate" data-i18n="buses">الباصات </span>
                        </a>
                        <ul class="menu-content">
                            @if (auth()->user()->canany(['super', 'buses-show', 'buses-create', 'buses-edit', 'buses-destroy']))
                                <li class="@if (itemIsActive('buses', 'index')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.buses.index') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">عرض</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->canany(['super', 'buses-create']))
                                <li class="@if (itemIsActive('buses', 'create')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.buses.create') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="New">اضافة</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif


                @if (auth()->user()->canany(['super', 'students-show', 'students-create', 'students-edit', 'students-destroy']))
                    <li
                        class="nav-item has-sub @if (isActive('students')) {{ 'sidebar-group-active open' }} @endif">
                        <a class="d-flex align-items-center" href="#">
                            <i data-feather='users'></i>
                            <span class="menu-title text-truncate" data-i18n="students">الطلاب</span>
                        </a>
                        <ul class="menu-content">
                            @if (auth()->user()->canany(['super', 'students-show', 'students-create', 'students-edit', 'students-destroy']))
                                <li class="@if (itemIsActive('students', 'index')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.students.index') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">عرض</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->canany(['super', 'students-create']))
                                <li class="@if (itemIsActive('students', 'create')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.students.create') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="New">اضافة</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->canany(['super', 'students-edit']))
                                <li class="@if (itemIsActive('students', 'add-to-bus')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('dashboard.students.add.to.bus') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="New">اضافة الطلاب الي
                                            الباص</span>
                                    </a>
                                </li>
                            @endif

                            @if (auth()->user()->canany(['super', 'students-edit']))
                                <li class="@if (itemIsActive('students', 'add-to-bus')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('dashboard.students.export-data') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="New">استخراج باينات الطلاب</span>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif


                @if (auth()->user()->canany(['super', 'absences-show', 'absences-create', 'absences-edit', 'absences-destroy']))
                    <li
                        class="nav-item has-sub @if (isActive('absences')) {{ 'sidebar-group-active open' }} @endif">
                        <a class="d-flex align-items-center" href="#">
                            <i data-feather='file'></i>
                            <span class="menu-title text-truncate" data-i18n="absences">الغياب</span>
                        </a>
                        <ul class="menu-content">
                            @if (auth()->user()->canany(['super', 'absences-show', 'absences-create', 'absences-edit', 'absences-destroy']))
                                <li class="@if (itemIsActive('absences', 'index')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.absences.index') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">عرض</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->canany(['super', 'absences-create']))
                                <li class="@if (itemIsActive('absences', 'create')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.absences.create') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="New">اضافة</span>
                                    </a>
                                </li>
                            @endif



                        </ul>
                    </li>
                @endif


                @if (auth()->user()->canany(['super', 'addresses-show', 'addresses-create', 'addresses-edit', 'addresses-destroy']))
                    <li
                        class="nav-item has-sub @if (isActive('addresses')) {{ 'sidebar-group-active open' }} @endif">
                        <a class="d-flex align-items-center" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-address-book"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M20 6v12a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2z">
                                </path>
                                <path d="M10 16h6"></path>
                                <path d="M13 11m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                <path d="M4 8h3"></path>
                                <path d="M4 12h3"></path>
                                <path d="M4 16h3"></path>
                            </svg>
                            <span class="menu-title text-truncate" data-i18n="addresses">العنوين</span>
                        </a>
                        <ul class="menu-content">
                            @if (auth()->user()->canany(['super', 'addresses-show', 'addresses-create', 'addresses-edit', 'addresses-destroy']))
                                <li class="@if (itemIsActive('addresses', 'index')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.addresses.index') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">عرض</span>
                                    </a>
                                </li>
                            @endif




                        </ul>
                    </li>
                @endif

                @if (auth()->user()->canany([
                            'super',
                            'school_messages-show',
                            'school_messages-create',
                            'school_messages-edit',
                            'absences-destroy',
                        ]))
                    <li
                        class="nav-item has-sub @if (isActive('school_messages')) {{ 'sidebar-group-active open' }} @endif">
                        <a class="d-flex align-items-center" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-telegram"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M15 10l-4 4l6 6l4 -16l-18 7l4 2l2 6l3 -4"></path>
                            </svg>
                            <span class="menu-title text-truncate" data-i18n="school_messages">رسائل المدرسة</span>
                        </a>
                        <ul class="menu-content">
                            @if (auth()->user()->canany([
                                        'super',
                                        'school_messages-show',
                                        'school_messages-create',
                                        'school_messages-edit',
                                        'absences-destroy',
                                    ]))
                                <li class="@if (itemIsActive('school_messages', 'index')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('dashboard.school_messages.index') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">عرض</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->canany(['super', 'school_messages-create']))
                                <li class="@if (itemIsActive('school_messages', 'create')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('dashboard.school_messages.create') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="New">اضافة</span>
                                    </a>
                                </li>
                            @endif



                        </ul>
                    </li>
                @endif
                @if (auth()->user()->canany(['super', 'trips-show', 'trips-create', 'trips-edit', 'trips-destroy']))
                    <li
                        class="nav-item has-sub @if (isActive('trips')) {{ 'sidebar-group-active open' }} @endif">
                        <a class="d-flex align-items-center" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-time"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M11.795 21h-6.795a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4"></path>
                                <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                                <path d="M15 3v4"></path>
                                <path d="M7 3v4"></path>
                                <path d="M3 11h16"></path>
                                <path d="M18 16.496v1.504l1 1"></path>
                            </svg>
                            <span class="menu-title text-truncate" data-i18n="trips">الرحلات</span>
                        </a>
                        <ul class="menu-content">
                            @if (auth()->user()->canany(['super', 'trips-show', 'trips-create', 'trips-edit', 'trips-destroy']))
                                <li class="@if (itemIsActive('trips', 'index')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.trips.index') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">عرض</span>
                                    </a>
                                </li>
                            @endif




                        </ul>
                    </li>
                @endif


                @if (auth()->user()->canany([
                            'super',
                            'grades-list',
                            'grades-show',
                            'grades-create',
                            'grades-edit',
                            'grades-delete',
                            'roles-list',
                            'roles-show',
                            'roles-create',
                            'roles-edit',
                            'roles-delete',
                            'staff-list',
                            'staff-show',
                            'staff-create',
                            'staff-edit',
                            'staff-delete',
                            'settings-show',
                            'settings-edit',
                        ]))
                    <li class="navigation-header"><span data-i18n="Settings && Staff">الاعدادات والمشرفين</span><i
                            data-feather="more-horizontal"></i></li>
                @endif
                @if (auth()->user()->canany(['super', 'trips-show', 'trips-create', 'trips-edit', 'trips-destroy']))

                    {{-- @if (auth()->user()->canany(['super', 'notifications-show', 'notifications-create', 'notifications-edit', 'notifications-destroy'])) --}}
                    <li
                        class="nav-item has-sub @if (isActive('notifications')) {{ 'sidebar-group-active open' }} @endif">
                        <a class="d-flex align-items-center" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bell"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 22c1.1 0 2 -.9 2 -2h-4c0 1.1 .9 2 2 2z"></path>
                                <path d="M18 8a6 6 0 0 0 -12 0v4a6 6 0 0 0 -2 4h16a6 6 0 0 0 -2 -4v-4z"></path>
                            </svg>
                            <span class="menu-title text-truncate" data-i18n="notifications">الإشعارات</span>
                        </a>
                        <ul class="menu-content">
                            @if (auth()->user()->canany([
                                        'super',
                                        'notifications-show',
                                        'notifications-create',
                                        'notifications-edit',
                                        'notifications-destroy',
                                    ]))
                                <li class="@if (itemIsActive('notifications', 'index')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('dashboard.notifications.index') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">عرض</span>
                                    </a>
                                </li>
                                <li class="@if (itemIsActive('notifications', 'create')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('dashboard.notifications.create') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">انشاء</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (auth()->user()->canany(['super', 'roles-list', 'roles-show', 'roles-create', 'roles-edit', 'roles-destroy']))
                    <li
                        class="nav-item has-sub @if (isActive('roles')) {{ 'sidebar-group-active open' }} @endif">
                        <a class="d-flex align-items-center" href="#">
                            <i data-feather="activity"></i>
                            <span class="menu-title text-truncate" data-i18n="Roles">logger</span>
                        </a>
                        <ul class="menu-content">
                            @if (auth()->user()->canany(['super', 'roles-list', 'roles-show', 'roles-create', 'roles-edit', 'roles-destroy']))
                                <li class="@if (itemIsActive('logger', 'index')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.logger.index') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">عرض</span>
                                    </a>
                                </li>
                            @endif
                            {{-- @if (auth()->user()->canany(['super', 'roles-create']))
                                <li class="@if (itemIsActive('roles', 'create')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.roles.create') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="New">تنظيف</span>
                                    </a>
                                </li>
                            @endif --}}
                        </ul>
                    </li>
                @endif
                @if (auth()->user()->canany(['super', 'roles-list', 'roles-show', 'roles-create', 'roles-edit', 'roles-destroy']))
                    @if (auth()->user()->canany(['super', 'roles-list', 'roles-show', 'roles-create', 'roles-edit', 'roles-destroy']))
                        <li class="@if (itemIsActive('mqtt', 'index')) {{ 'active' }} @endif">
                            <a class="d-flex align-items-center" href="{{ route('dashboard.mqtt.index') }}">
                                <i data-feather="message-square"></i>
                                <span class="menu-item text-truncate" data-i18n="List">اختبار سسيتم mqtt للاشعارات
                                    والرسائل</span>
                            </a>
                        </li>
                        <li class="@if (itemIsActive('mqtt', 'index')) {{ 'active' }} @endif">
                            <a class="d-flex align-items-center" href="{{ route('dashboard.socket.io') }}">
                                <i data-feather="message-square"></i>
                                <span class="menu-item text-truncate" data-i18n="List">socket.io</span>
                            </a>
                        </li>
                    @endif
                @endif
                @if (auth()->user()->canany(['super', 'roles-list', 'roles-show', 'roles-create', 'roles-edit', 'roles-destroy']))
                    <li
                        class="nav-item has-sub @if (isActive('roles')) {{ 'sidebar-group-active open' }} @endif">
                        <a class="d-flex align-items-center" href="#">
                            <i data-feather='pocket'></i>
                            <span class="menu-title text-truncate" data-i18n="Roles">الادوار</span>
                        </a>
                        <ul class="menu-content">
                            @if (auth()->user()->canany(['super', 'roles-list', 'roles-show', 'roles-create', 'roles-edit', 'roles-destroy']))
                                <li class="@if (itemIsActive('roles', 'index')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.roles.index') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">عرض</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->canany(['super', 'roles-create']))
                                <li class="@if (itemIsActive('roles', 'create')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.roles.create') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="New">اضافة</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (auth()->user()->canany(['super', 'staff-list', 'staff-show', 'staff-create', 'staff-edit', 'staff-destroy']))
                    <li
                        class="nav-item has-sub @if (isActive('staff')) {{ 'sidebar-group-active open' }} @endif">
                        <a class="d-flex align-items-center" href="#">
                            <i data-feather='tag'></i>
                            <span class="menu-title text-truncate" data-i18n="Staff">الكوبونات</span>
                        </a>
                        <ul class="menu-content">
                            @if (auth()->user()->canany(['super', 'staff-list', 'staff-show', 'staff-create', 'staff-edit', 'staff-destroy']))
                                <li class="@if (itemIsActive('coupon', 'index') && Request::input('model') === 'schools') {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('dashboard.coupon.index', ['model' => 'schools']) }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">عرض المدارس</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->canany(['super', 'staff-list', 'staff-show', 'staff-create', 'staff-edit', 'staff-destroy']))
                                <li class="@if (itemIsActive('coupon', 'index') && Request::input('model') === 'parents') {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('dashboard.coupon.index', ['model' => 'parents']) }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">عرض اولاياء الامور</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->canany(['super', 'staff-create']))
                                <li class="@if (itemIsActive('coupon', 'create') && Request::input('model') === 'parents') {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('dashboard.coupon.create', ['model' => 'parents']) }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="New"> اضافة لاولياء الامور</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->canany(['super', 'staff-create']))
                                <li class="@if (itemIsActive('coupon', 'create') && Request::input('model') === 'schools') {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('dashboard.coupon.create', ['model' => 'schools']) }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="New"> اضافة للمدارس</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (auth()->user()->canany(['super', 'staff-list', 'staff-show', 'staff-create', 'staff-edit', 'staff-destroy']))
                    <li
                        class="nav-item has-sub @if (isActive('projects')) {{ 'sidebar-group-active open' }} @endif">
                        <a class="d-flex align-items-center" href="#">
                            <i data-feather='briefcase'></i>
                            <span class="menu-title text-truncate" data-i18n="Staff">المشاريع</span>
                        </a>
                        <ul class="menu-content">
                            @if (auth()->user()->canany(['super', 'staff-list', 'staff-show', 'staff-create', 'staff-edit', 'staff-destroy']))
                                <li class="@if (itemIsActive('projects', 'index')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.projects.index') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">عرض</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->canany(['super', 'staff-create']))
                                <li class="@if (itemIsActive('projects', 'create')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.projects.create') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="New">اضافة</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (auth()->user()->canany(['super', 'staff-list', 'staff-show', 'staff-create', 'staff-edit', 'staff-destroy']))
                    <li
                        class="nav-item has-sub @if (isActive('staff')) {{ 'sidebar-group-active open' }} @endif">
                        <a class="d-flex align-items-center" href="#">
                            <i data-feather='dollar-sign'></i>
                            <span class="menu-title text-truncate" data-i18n="Staff">الاشتراكات</span>
                        </a>
                        <ul class="menu-content">
                            @if (auth()->user()->canany(['super', 'staff-list', 'staff-show', 'staff-create', 'staff-edit', 'staff-destroy']))
                                <li class="@if (itemIsActive('subscription', 'index')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('dashboard.subscription.index') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">عرض</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->canany(['super', 'staff-create']))
                                <li class="@if (itemIsActive('staff', 'create')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('dashboard.subscription.create') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="New">اضافة</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (auth()->user()->canany(['super', 'staff-list', 'staff-show', 'staff-create', 'staff-edit', 'staff-destroy']))
                    <li
                        class="nav-item has-sub @if (isActive('staff')) {{ 'sidebar-group-active open' }} @endif">
                        <a class="d-flex align-items-center" href="#">
                            <i data-feather='briefcase'></i>

                            <span class="menu-title text-truncate" data-i18n="Staff">الاعلانات</span>
                        </a>
                        <ul class="menu-content">
                            @if (auth()->user()->canany(['super', 'staff-list', 'staff-show', 'staff-create', 'staff-edit', 'staff-destroy']))
                                <li class="@if (itemIsActive('adesSchool', 'index')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('dashboard.adesSchool.index') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">الاعلانات</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->canany(['super', 'staff-create']))
                                <li class="@if (itemIsActive('staff', 'create')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('dashboard.adesSchool.create') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="New">اضافة</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (auth()->user()->canany(['super', 'staff-list', 'staff-show', 'staff-create', 'staff-edit', 'staff-destroy']))
                    <li
                        class="nav-item has-sub @if (isActive('staff')) {{ 'sidebar-group-active open' }} @endif">
                        <a class="d-flex align-items-center" href="#">
                            <i data-feather='user-check'></i>
                            <span class="menu-title text-truncate" data-i18n="Staff">المشرفين</span>
                        </a>
                        <ul class="menu-content">
                            @if (auth()->user()->canany(['super', 'staff-list', 'staff-show', 'staff-create', 'staff-edit', 'staff-destroy']))
                                <li class="@if (itemIsActive('staff', 'index')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.staff.index') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="List">عرض</span>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->canany(['super', 'staff-create']))
                                <li class="@if (itemIsActive('staff', 'create')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.staff.create') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="New">اضافة</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif


                @if (auth()->user()->canany(['super', 'general-settings-edi']))
                    <li
                        class="nav-item has-sub @if (isActive('settings')) {{ 'sidebar-group-active open' }} @endif">
                        <a class="d-flex align-items-center" href="#">
                            <i data-feather='settings'></i>
                            <span class="menu-title text-truncate" data-i18n="Settings">الاعدادات</span>
                        </a>
                        <ul class="menu-content">
                            @if (auth()->user()->canany(['super', 'general-settings-edit']))
                                <li class="@if (itemIsActive('settings', 'general')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('dashboard.settings.general') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="General">الاعدادات العامة</span>
                                    </a>
                                </li>
                            @endif

                            {{-- @if (auth()->user()->canany(['super', 'smtp-settings-edit']))
                                <li class="@if (itemIsActive('settings', 'seo')) {{'active'}} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.settings.seo') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="SEO">SEO</span>
                                    </a>
                                </li>
                            @endif --}}

                            @if (auth()->user()->canany(['super', 'smtp-settings-edit']))
                                <li class="@if (itemIsActive('settings', 'smtp')) {{ 'active' }} @endif">
                                    <a class="d-flex align-items-center" href="{{ route('dashboard.settings.smtp') }}">
                                        <i data-feather="circle"></i>
                                        <span class="menu-item text-truncate" data-i18n="SMTP">اعدادات الـ SMTP</span>
                                    </a>
                                </li>
                            @endif



                        </ul>
                    </li>

                @endif

            </ul>
        </div>
    </aside>
