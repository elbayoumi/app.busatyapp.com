@extends('dashboard.layouts.app')

@push('page_vendor_css')
@endpush

@push('page_styles')
    <style>
        body {
            background: #f8fafc;
        }

        .log-container {
            padding: 32px 24px;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
        }

        .log-header {
            border-bottom: 1px solid #e3e3e3;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }

        .log-title {
            font-size: 1.6rem;
            font-weight: bold;
            color: #2196f3;
            letter-spacing: 1px;
        }

        .btn-clear {
            font-size: 1rem;
            padding: 8px 24px;
            border-radius: 0.7rem;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.08);
        }

        /* Table */
        .table-logs {
            width: 100%;
            table-layout: fixed;
            /* prevent auto expanding */
            border-radius: 0.7rem;
            overflow: hidden;
            background: #f4f8fb;
        }

        .table-logs th {
            background: linear-gradient(90deg, #2196f3 0, #21cbf3 100%);
            color: #fff;
            font-size: 1.1rem;
            font-weight: bold;
            border: none;
        }

        .table-logs td {
            font-size: 1rem;
            vertical-align: middle;
            border: none;
            overflow: hidden;
        }

        /* Fix first column */
        .table-logs th:first-child,
        .table-logs td:first-child {
            width: 90px;
            white-space: nowrap;
        }

        .log-info {
            background-color: #e3f2fd !important;
        }

        .log-warning {
            background-color: #fffde7 !important;
        }

        .log-error {
            background-color: #ffebee !important;
        }

        .log-index {
            font-weight: bold;
            color: #2196f3;
            text-align: center;
        }

        .log-entry {
            font-family: 'Fira Mono', 'Consolas', monospace;
            font-size: 1rem;
            color: #333;
            padding: 8px 0;
            max-width: 0;
        }

        /* One-line preview */
        .log-summary {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none;
            padding: 6px 0;
            min-width: 0;
        }

        .log-one-line {
            display: block;
            flex: 1 1 auto;
            min-width: 0;
            max-width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .log-chev {
            width: 18px;
            height: 18px;
            transition: transform .2s ease;
            flex: 0 0 auto;
        }

        .log-summary[aria-expanded="true"] .log-chev {
            transform: rotate(90deg);
        }

        /* Full log */
        .log-full {
            margin: 6px 0 0 0;
            padding: 10px 0 0 26px;
            white-space: pre-wrap;
            word-break: break-word;
            font-family: 'Fira Mono', 'Consolas', monospace;
            font-size: 0.95rem;
            color: #2b2b2b;
            display: none;
            border-left: 2px solid #d0e7ff;
            max-width: 100%;
            overflow-x: auto;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin: 32px 0 0 0;
            gap: 8px;
        }

        .pagination a {
            padding: 10px 22px;
            background: linear-gradient(90deg, #2196f3 0, #21cbf3 100%);
            color: #fff;
            border-radius: 0.7rem;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.2s;
            box-shadow: 0 2px 8px rgba(33, 203, 243, 0.08);
        }

        .pagination a:hover {
            background: #1976d2;
        }

        .pagination span {
            padding: 10px 22px;
            background-color: #6c757d;
            color: #fff;
            border-radius: 0.7rem;
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
    <section id="log-viewer">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="log-container">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center log-header">
                        <div class="log-title">
                            <i data-feather="file-text"></i> سجلات النظام
                        </div>
                        <form action="{{ route('dashboard.logger.clear') }}" method="GET">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-clear">
                                <i data-feather="trash-2"></i> مسح السجلات
                            </button>
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-logs table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">رقم الخطأ</th>
                                    <th>البيان</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $errorNumber = 1; @endphp
                                @forelse ($logs as $index => $log)
                                    @php
                                        $log = is_array($log) ? implode("\n", $log) : (string) $log;
                                        $lines = explode("\n", trim($log));
                                        $firstLine = trim($lines[0] ?? '');
                                        $rest = trim(implode("\n", array_slice($lines, 1)));
                                        $hasMore = strlen($rest) > 0;

                                        $isError = str_contains($log, 'ERROR');
                                        $isWarning = str_contains($log, 'WARNING');
                                        $rowClass = $isError ? 'log-error' : ($isWarning ? 'log-warning' : 'log-info');
                                    @endphp
                                    <tr class="{{ $rowClass }}">
                                        <td class="log-index text-danger text-center">
                                            @if ($isError)
                                                {{ $errorNumber++ }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="log-entry">
                                            <div class="log-item" data-log="{{ $index }}">
                                                <div class="log-summary" role="button" aria-expanded="false"
                                                    tabindex="0">
                                                    <i data-feather="chevron-right" class="log-chev"></i>
                                                    <span class="log-one-line"
                                                        title="{{ $firstLine }}">{{ $firstLine }}</span>
                                                </div>
                                                @if ($hasMore)
                                                    <pre class="log-full" id="log-full-{{ $index }}">{!! e($rest) !!}</pre>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-5">
                                            <i data-feather="info"></i> لا توجد سجلات حالياً
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination">
                        @if ($currentPage > 1)
                            <a href="{{ route('dashboard.logger.index', ['page' => $currentPage - 1]) }}">&laquo; السابق</a>
                        @endif

                        <span>صفحة {{ $currentPage }} من {{ $lastPage }}</span>

                        @if ($currentPage < $lastPage)
                            <a href="{{ route('dashboard.logger.index', ['page' => $currentPage + 1]) }}">التالي
                                &raquo;</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('page_scripts_vendors')
    <script src="https://unpkg.com/feather-icons"></script>
@endpush

@push('page_scripts')
    <script>
        function safeFeather() {
            if (window.feather && typeof window.feather.replace === 'function') {
                window.feather.replace();
            }
        }

        function bindLogToggles() {
            var summaries = document.querySelectorAll('.log-summary');
            summaries.forEach(function(summary) {
                if (summary.dataset.bound === '1') return;
                summary.dataset.bound = '1';

                var wrapper = summary.closest('.log-item');
                var full = wrapper.querySelector('.log-full');
                if (!full) return;

                function toggle() {
                    var expanded = summary.getAttribute('aria-expanded') === 'true';
                    summary.setAttribute('aria-expanded', expanded ? 'false' : 'true');
                    full.style.display = expanded ? 'none' : 'block';
                }

                summary.addEventListener('click', function(e) {
                    e.preventDefault();
                    toggle();
                });
                summary.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        toggle();
                    }
                });
            });
        }

        function initLogViewer() {
            safeFeather();
            bindLogToggles();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initLogViewer);
        } else {
            initLogViewer();
        }
    </script>
@endpush
