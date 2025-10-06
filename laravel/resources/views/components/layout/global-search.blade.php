<div class="position-relative w-100">
    <div class="search-wrapper d-flex align-items-center px-3 py-2 bg-light rounded-pill shadow-sm position-relative">
        <i class="ficon me-2" data-feather="search"></i>
        <input
            id="global-search"
            type="text"
            class="form-control border-0 bg-transparent flex-grow-1"
            placeholder="بحث سريع..."
            autocomplete="off"
        >
        <span class="search-clear text-muted" role="button"><i data-feather="x"></i></span>
    </div>

    {{-- Dropdown --}}
    <div class="search-dropdown position-absolute mt-2 bg-white rounded shadow-lg w-100 d-none"
        style="z-index: 1050; max-height: 350px; overflow-y: auto;">
        <div class="search-loading text-center py-3 d-none">
            <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
        </div>

        <div class="search-results-list px-2 py-2"></div>

        <div class="search-history px-3 py-2 border-top d-none">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong>بحث سابق</strong>
                <button class="btn btn-sm btn-outline-danger clear-history">مسح</button>
            </div>
            <ul class="list-group list-group-flush recent-search-list"></ul>
        </div>
    </div>
</div>
@push('page_styles')
<style>
    .search-wrapper input:focus {
        outline: none;
        box-shadow: none;
    }

    .search-clear {
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .search-clear:hover {
        color: #dc3545;
    }

    .search-dropdown {
        animation: fadeSlideIn 0.3s ease;
    }

    @keyframes fadeSlideIn {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .search-item {
        transition: background 0.2s;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 6px;
    }

    .search-item:hover {
        background: #f8f9fa;
    }

    .search-item small {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .spinner-border {
        width: 1.75rem;
        height: 1.75rem;
    }
</style>
@endpush
@push('page_scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const $input = $('#global-search');
    const $dropdown = $('.search-dropdown');
    const $resultsList = $('.search-results-list');
    const $loading = $('.search-loading');
    const $history = $('.search-history');
    const $recentList = $('.recent-search-list');
    const $clearBtn = $('.clear-history');
    const $clearSearch = $('.search-clear');

    const storageKey = 'global_search_history';
    const maxHistory = 5;
    let debounceTimer;

    function showDropdown() {
        $dropdown.removeClass('d-none');
    }

    function hideDropdown() {
        $dropdown.addClass('d-none');
    }

    function saveToHistory(text) {
        let stored = JSON.parse(localStorage.getItem(storageKey)) || [];
        stored = stored.filter(item => item !== text);
        stored.unshift(text);
        stored = stored.slice(0, maxHistory);
        localStorage.setItem(storageKey, JSON.stringify(stored));
        loadHistory();
    }

    function loadHistory() {
        const stored = JSON.parse(localStorage.getItem(storageKey)) || [];
        if (!stored.length) {
            $history.addClass('d-none');
            return;
        }

        const html = stored.map(item => `
            <li class="list-group-item search-item d-flex justify-content-between align-items-center">
                <span>${item}</span>
                <i class="text-muted small" data-feather="clock"></i>
            </li>
        `).join('');

        $recentList.html(html);
        $history.removeClass('d-none');
        feather.replace();
    }

    function clearHistory() {
        localStorage.removeItem(storageKey);
        loadHistory();
    }

    $clearBtn.on('click', clearHistory);

    $input.on('focus', function () {
        if (!$(this).val()) {
            loadHistory();
            showDropdown();
        }
    });

    $input.on('input', function () {
        const query = $(this).val().trim();
        clearTimeout(debounceTimer);

        if (!query) {
            loadHistory();
            return;
        }

        $loading.removeClass('d-none');
        $resultsList.empty();
        showDropdown();
        $history.addClass('d-none');

        debounceTimer = setTimeout(() => {
            $.get("{{ route('dashboard.global-search') }}", { text: query }, function (data) {
                $loading.addClass('d-none');
                if (!Array.isArray(data) || !data.length) {
                    $resultsList.html('<div class="text-center text-muted py-2">لا توجد نتائج</div>');
                    return;
                }

                const html = data.map(item => `
                    <div class="search-item" onclick="window.location.href='${item.link}'">
                        <strong>${item.name}</strong><br>
                        <small>${item.type}</small>
                    </div>
                `).join('');

                $resultsList.html(html);
                saveToHistory(query);
            }).fail(() => {
                $loading.addClass('d-none');
                $resultsList.html('<div class="text-danger text-center py-2">خطأ في الاتصال بالسيرفر</div>');
            });
        }, 500);
    });

    $(document).on('click', '.recent-search-list .search-item', function () {
        const text = $(this).find('span').text().trim();
        $input.val(text).trigger('input');
    });

    $clearSearch.on('click', function () {
        $input.val('');
        $resultsList.empty();
        hideDropdown();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.search-wrapper, .search-dropdown').length) {
            hideDropdown();
        }
    });

    // Init
    loadHistory();
});
</script>
@endpush
