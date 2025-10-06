@extends('dashboard.layouts.app')
@push('page_vendor_css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/forms/select/select2.min.css">
@endpush
@push('page_styles')
@endpush
@section('content')
    <section id="multilingual-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom p-2">
                        <h4 class="card-title">قائمة / الاشتراكات</h4>
                    </div>
                    <div class="card-header border-bottom p-1">
                        <form action="{{ route('dashboard.subscription.index') }}" method="GET" style="min-width: 100%">
                            <div class="row">

                                <div class="col-md-3  my-50">
                                    <select id="select_page" class="form-control operator" name="parant_id">
                                        <option value="" selected> اولياء الامور</option>
                                        @foreach ($allParent as $value)
                                            <option value="{{ $value->id }}">
                                                {{ $value->id . ' - ' . $value->email }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3  my-50">

                                    <input type="text" class="form-control" placeholder="email"
                                        value="{{ request()->email }}" name="email" id="email" />
                                </div>
                                <div class="col-md-3 my-50">
                                    <button type="submit" class="btn btn-primary btn-block">بحث</button>
                                </div>
                                <div class="col-md-3 my-50">
                                    <a href="{{ route('dashboard.subscription.create') }}"
                                        class="btn btn-success btn-block">اضافة</a>
                                </div>


                            </div>



                        </form>


                    </div>
                    <div class="table-responsive min-height-17rem">
                        <table class="dt-multilingual table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>الحالة </th>
                                    <th> الخطة</th>
                                    <th> الاسم</th>
                                    <th> البريد الالكتروني</th>
                                    {{-- <th> المدرسة</th> --}}
                                    <th> القيمة</th>
                                    <th>المدة</th>
                                    <th>الوسيلة </th>

                                    {{-- <th> الطلاب</th> --}}
                                    <th>تاريخ الاشتراك</th>
                                    <th>الاعدادت</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subscription as $index => $subscrip)
                                    @foreach ($subscrip?->subscription as $parentS)
                                        <tr>
                                            {{-- @dd($parentS) --}}
                                            <td> @isset($parentS->transaction_id)
                                                    {{ $parentS->transaction_id }}
                                                @else
                                                    undefined
                                                @endisset
                                            </td>
                                            <td> {{ statusAr($parentS?->status) }}</td>

                                            <td> @isset($parentS->plan_name)
                                                    {{ $parentS->plan_name }}
                                                @else
                                                    undefined
                                                @endisset
                                            </td>

                                            <td>{{ $subscrip?->name }}</td>
                                            <td>{{ $subscrip?->email }}</td>

                                            <td>{{ $parentS?->amount }} <span>{{ $parentS?->currency }}</span></td>


                                            <td>{{ daysDifference($subscrip->subscription[0]->start_date, $subscrip->subscription[0]->end_date) }}
                                            </td>
                                            <td> {{ $parentS?->payment_method }}</td>

                                            <td>{{ $parentS?->created_at }}</td>



                                            <td>
                                                <div class="dropdown">
                                                    <a class="btn btn-success btn-sm dropdown-toggle" href="#"
                                                        role="button" id="dropdownMenuLink" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        العمليات
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right"
                                                        aria-labelledby="dropdownMenuLink">




                                                        @if (auth()->user()->canany(['super', 'attendants-show']))
                                                            <a href="{{ route('dashboard.subscription.show', $parentS->id) }}"
                                                                class="dropdown-item" style="padding: 6px;" title="show">
                                                                <i data-feather='eye'></i>
                                                                عرض
                                                            </a>
                                                        @endif


                                                        @if (auth()->user()->canany(['super', 'attendants-edit']))
                                                            <a href="{{ route('dashboard.subscription.edit', $parentS->id) }}"
                                                                class="dropdown-item" style="padding: 6px;" title="Edit">
                                                                <i data-feather='edit'></i>
                                                                تعديل
                                                            </a>
                                                        @endif

                                                        @if (auth()->user()->canany(['super', 'attendant-destroy']))
                                                            <a onclick="event.preventDefault();"
                                                                data-delete="delete-form-{{ $index }}"
                                                                href="{{ route('dashboard.subscription.destroy', $parentS->id) }}"
                                                                class="dropdown-item but_delete_action"
                                                                style="padding: 6px;" title="Delete">
                                                                <i data-feather='trash'></i>
                                                                حذف

                                                            </a>
                                                            <form id="delete-form-{{ $index }}"
                                                                action="{{ route('dashboard.subscription.destroy', $parentS->id) }}"
                                                                method="POST" style="display: none;">
                                                                @method('DELETE')
                                                                @csrf
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                    {{-- <div class="mt-2">
                        {{ $subscription->appends(request()->query())->links() }}
                    </div> --}}
                </div>
            </div>
        </div>
        {{-- {{ $subscription->appends(request()->query())->links('vendor.pagination.bootstrap-4') }} --}}

    </section>
    {{ $subscription->links('vendor.pagination.bootstrap-4') }}

@endsection

@push('page_scripts_vendors')
    <script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
@endpush

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('.basic-select2').select2();
        });
    </script>
@endpush
