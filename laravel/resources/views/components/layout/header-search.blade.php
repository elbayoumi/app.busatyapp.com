@push('page_styles')
    <style>
        .search-input {
            background: #f8f9fa;
            border-radius: 0.75rem;
            padding: 0.5rem 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            position: relative;
            transition: all 0.3s ease;
        }

        .search-input:focus-within {
            background: #fff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
        }

        #school-search {
            border: none;
            background: transparent;
            padding-left: 2.5rem;
            font-weight: 500;
            color: #343a40;
            font-size: 1rem;
            width: 100%;
        }

        .search-input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            pointer-events: none;
        }

        .search-input-close {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            cursor: pointer;
            transition: color 0.2s;
        }

        .search-input-close:hover {
            color: #dc3545;
        }

        .search-results,
        .recent-searches {
            border-radius: 0.75rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            animation: fadeIn 0.4s ease;
            transition: all 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .search-loading {
            padding: 1.5rem 0;
        }

        .recent-search-item {
            transition: background 0.2s;
            cursor: pointer;
        }

        .recent-search-item:hover {
            background-color: #f1f3f5;
        }

        .badge.bg-success,
        .badge.bg-secondary,
        .badge.bg-primary {
            font-size: 0.75rem;
            padding: 0.3em 0.6em;
            border-radius: 0.5rem;
        }

        .recent-searches .list-group-item {
            border: none;
            border-bottom: 1px solid #eee;
            padding: 0.5rem 1rem;
            background-color: white;
        }

        .recent-searches .list-group-item:last-child {
            border-bottom: none;
        }

        .recent-searches .border-bottom {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }

        .spinner-border {
            width: 2rem;
            height: 2rem;
        }
    </style>
@endpush


<div class="bookmark-wrapper d-flex align-items-center">
    <ul class="nav navbar-nav d-xl-none">
        <li class="nav-item">
            <a class="nav-link menu-toggle" href="javascript:void(0);">
                <i class="ficon" data-feather="menu"></i>
            </a>
        </li>
    </ul>

    <ul class="nav navbar-nav bookmark-icons">
        <li class="nav-item nav-search">
            <a class="nav-link nav-link-search">
                <i class="ficon" data-feather="search"></i>
            </a>
            <div class="search-input position-relative">
                <div class="search-input-icon"><i data-feather="search"></i></div>

                <input id="school-search" class="form-control input" type="text" placeholder="بحث عن المدرسة"
                    autocomplete="off">

                <!-- نتائج البحث -->
                <!-- داخل search-results -->
                <div class="search-results bg-white border shadow-sm rounded position-absolute w-100"
                    style="z-index: 1000; display: none; max-height: 300px; overflow-y: auto;">
                    <div class="search-loading text-center py-3" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">جارٍ التحميل...</span>
                        </div>
                    </div>
                    <div class="search-results-list"></div>
                </div>

                <!-- عمليات البحث الأخيرة -->
                <div class="recent-searches bg-white border shadow-sm rounded position-absolute w-100"
                    style="z-index: 999; display: none; max-height: 300px; overflow-y: auto;">
                    <div class="d-flex justify-content-between align-items-center px-2 py-1 border-bottom">
                        <strong>عمليات البحث الأخيرة</strong>
                        <button type="button" class="btn btn-sm btn-link text-danger clear-recent">مسح</button>
                    </div>
                    <ul class="list-group recent-search-list mb-0"></ul>
                </div>

                <div class="search-input-close"><i data-feather="x"></i></div>
            </div>
        </li>
    </ul>
</div>

@push('page_scripts')
    <script>
        $(document).ready(function() {
            const storageKey = 'recent_school_searches';
            const maxRecent = 5;
            let timer;

            function loadRecentSearches() {
                const stored = JSON.parse(localStorage.getItem(storageKey)) || [];
                const html = stored.map(item => `
                <li class="list-group-item recent-search-item d-flex align-items-center justify-content-between" style="cursor:pointer;">
                    <div>
                        <i data-feather="clock" class="me-1 text-muted"></i>
                        <span class="search-text">${item.text}</span>
                    </div>
                    ${item.success === true
                        ? '<span class="badge bg-success">✔</span>'
                        : item.success === false
                            ? '<span class="badge bg-secondary">✖</span>'
                            : ''
                    }
                </li>
            `).join('');
                $('.recent-search-list').html(html);
                $('.recent-searches').show();
                feather.replace();
            }

            function saveSearch(query, isSuccess = null) {
                let stored = JSON.parse(localStorage.getItem(storageKey)) || [];
                stored = stored.filter(item => item.text !== query);
                stored.unshift({
                    text: query,
                    success: isSuccess
                });
                if (stored.length > maxRecent) stored = stored.slice(0, maxRecent);
                localStorage.setItem(storageKey, JSON.stringify(stored));
            }

            function clearSearches() {
                localStorage.removeItem(storageKey);
                $('.recent-search-list').empty();
            }

            $('#school-search').on('input', function() {
                const query = $(this).val().trim();
                clearTimeout(timer);

                if (query.length === 0) {
                    $('.search-results').hide();
                    loadRecentSearches();
                    return;
                }

                $('.search-results').show();
                $('.search-loading').show();
                $('.search-results-list').hide();

                timer = setTimeout(() => {
                    $.ajax({
                        url: "{{ route('dashboard.global-search') }}",
                        method: 'GET',
                        data: {
                            text: query
                        },
                        success: function(data) {
                            const hasResults = Array.isArray(data) && data.length > 0;
                            saveSearch(query, hasResults);

                            let html = '<ul class="list-group mt-1 mb-0">';
                            if (hasResults) {
                                data.forEach(item => {
                                    html += `
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="${item.link}" class="text-decoration-none flex-grow-1">${item.name}</a>
                                        <span class="badge bg-primary">${item.type}</span>
                                    </li>`;
                                });
                            } else {
                                html +=
                                    `<li class="list-group-item text-muted text-center">لا توجد نتائج</li>`;
                            }
                            html += '</ul>';

                            $('.search-loading').hide();
                            $('.search-results-list').html(html).fadeIn();
                            loadRecentSearches();
                        },
                        error: function() {
                            $('.search-loading').hide();
                            $('.search-results-list').html(
                                `<div class="text-danger text-center p-2">حدث خطأ أثناء البحث</div>`
                            ).fadeIn();
                        }
                    });
                }, 800); // 800ms debounce
            });

            $('#school-search').on('focus', function() {
                if ($(this).val().trim() === '') {
                    loadRecentSearches();
                }
            });

            $(document).on('click', '.recent-search-item', function() {
                const text = $(this).find('.search-text').text();
                $('#school-search').val(text).trigger('input');
            });

            $(document).on('click', '.clear-recent', function(e) {
                e.preventDefault();
                clearSearches();
            });

            $('.search-input-close').on('click', function() {
                $('#school-search').val('');
                $('.search-results').fadeOut();
                $('.search-results-list').empty();
                $('.search-loading').hide();
            });

            loadRecentSearches();
        });
    </script>
@endpush
