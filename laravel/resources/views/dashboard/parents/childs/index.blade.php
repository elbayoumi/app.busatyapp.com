@extends('dashboard.layouts.app')
@push('page_vendor_css')
@endpush
@push('page_styles')
@endpush
@section('content')
    <section id="multilingual-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">ولي الامر {{ $Parent->name }} </h4>

                    </div>
                    <div class="table-responsive">
                        <table class="dt-multilingual table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th> اسم الابن </th>

                                    <th> تاريخ الاضافة</th>
                                    <th>العمليات</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($childs as $index => $child)
                                    <tr>
                                        <td>{{ $child->id }}</td>
                                        <td>{{ $child->name }}</td>
                                        <td>{{ $child->created_at }}</td>

                                        <td>

                                            @if(auth()->user()->canany(['super', 'parents-show']))
                                            <a href="{{ route('dashboard.parents.child-show', $child->id) }}" class="m-portlet__nav-link btn m-btn m-btn--hover-blog m-btn--icon m-btn--icon-only m-btn--pill" style="padding: 6px;" title="Show">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                             @endif
                                    </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('page_scripts_vendors')
@endpush

@push('page_scripts')
@endpush
