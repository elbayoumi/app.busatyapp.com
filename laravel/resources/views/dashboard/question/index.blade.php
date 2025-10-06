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
                    <div class="card-header border-bottom p-2">
                        <h4 class="card-title">قائمة / الاسألة الشائعة</h4>
                    </div>
                    <div class="card-header border-bottom p-1">

                        <form action="{{ route('dashboard.question.index') }}" method="GET" style="min-width: 100%">
                            <div class="row my-50 mx-2">
                                <div class="row col-md-3 ">
                                    <input type="text" class="form-control" placeholder="للبحث"
                                        value="{{ request()->text }}" name="text" id="text" />
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select id="type" name="type" class="form-control">
                                            <option value="" disabled selected>Select a type</option>
                                            <option value="parents">Parents</option>
                                            <option value="schools">Schools</option>
                                            <option value="attendants">Attendants</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <select name="status" class="form-control basic-select2">
                                        <option value="" selected>الجميع</option>
                                        <option value="1">مفعلة</option>
                                        <option value="0">غير مفعلة</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary btn-block">بحث</button>
                                </div>
                            </div>




                    </div>

                    </form>

                </div>

                <div class="table-responsive min-height-17rem">
                    <table class="dt-multilingual table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>التطبيق</th>
                                <th>السؤال</th>
                                <th>الإجابة</th>
                                <th>الحالة</th>
                                <th>اللغه</th>
                                <th>العمليات</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_question as $index => $question)
                                <tr>
                                    <td>{{ $question->id }}</td>

                                    <td>{{ $question->type }}</td>
                                    <td>{{ $question->question }}</td>
                                    <td>{{ $question->answer }}</td>
                                    <td>
                                        <span type="button"
                                            class="btn  rounded-circle @if ($question->status) btn-success
@else
btn-danger @endif  "></span>
                                    </td>
                                    <td>{{ $question->lang }}</td>


                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-success btn-sm dropdown-toggle" href="#" role="button"
                                                id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                العمليات
                                            </a>
                                            @if (auth()->user()->canany(['super', 'grades-create']))
                                                <div class="dropdown-menu dropdown-menu-right"
                                                    aria-labelledby="dropdownMenuLink">
                                            @endif
                                            @if (auth()->user()->canany(['super', 'question-show']))
                                                <a href="{{ route('dashboard.question.show', $question->id) }}"
                                                    class="dropdown-item" style="padding: 6px;" title="Show">
                                                    <i data-feather='eye'></i>
                                                    عرض بيانات السؤال الشائع
                                                </a>
                                            @endif
                                            @if (auth()->user()->canany(['super', 'question-edit']))
                                                <a href="{{ route('dashboard.question.edit', $question->id) }}"
                                                    class="dropdown-item" style="padding: 6px;" title="Edit">
                                                    <i data-feather='edit'></i>
                                                    تعديل بيانات السؤال الشائع
                                                </a>
                                            @endif

                                            @if (auth()->user()->canany(['super', 'question-destroy']))
                                                <a onclick="event.preventDefault();"
                                                    data-delete="delete-form-{{ $index }}"
                                                    href="{{ route('dashboard.question.destroy', $question->id) }}"
                                                    class="dropdown-item but_delete_action" style="padding: 6px;"
                                                    title="Delete">
                                                    <i data-feather='trash'></i>
                                                    حذف بيانات السؤال الشائع

                                                </a>
                                                <form id="delete-form-{{ $index }}"
                                                    action="{{ route('dashboard.question.destroy', $question->id) }}"
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
                </tbody>
                </table>

            </div>
        </div>
        </div>
        </div>
        {{ $all_question->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}

    </section>
@endsection

@push('page_scripts_vendors')
@endpush

@push('page_scripts')
@endpush
