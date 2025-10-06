@extends('dashboard.layouts.app')
@push('page_vendor_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
@endpush
@push('page_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/css/plugins/forms/pickers/form-flat-pickr.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/css/pages/app-user.min.css">
@endpush
@section('content')

<!-- users edit start -->
<section class="app-user-edit">
    <div class="card">
        <div class="card-header border-bottom">
            <h4 class="card-title"> اضف ابن </h4>

        </div>
        <div class="card-body mt-3">

            <!-- Account Tab starts -->
            <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                <!-- users edit account form start -->
                <form method="POST" action="{{ route('dashboard.parents.store-child', $Parent->id) }}" class="m-form m-form--fit m-form--label-align-right" enctype="multipart/form-data">
                    @csrf
                    <div class="row">



                        {{-- <div class="col-md-4">
                            <div class="form-group">
                                <label for="student_id">الطالب</label>
                                <select class="form-control" name="student_id" id="student_id" required>
                                    <option value="" disabled> اختر الطلب </option>
                                    @foreach($Student as $Stu)
                                    <option value="{{$Stu->id}}">{{$Stu->name}}</option>
                                    @endforeach


                                </select>
                            </div>
                        </div> --}}


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="parent_key"> (KEY) الكود الاول</label>
                                <input type="text" class="form-control" placeholder="key" value="{{ old('parent_key') }}" name="parent_key" id="parent_key" required />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="parent_secret"> (Secret) الكود الثاني</label>
                                <input type="text" class="form-control" placeholder="secret" value="{{ old('parent_secret') }}" name="parent_secret" id="parent_secret" required />
                            </div>
                        </div>
                        <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                            <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1"> اضف </button>
                        </div>
                    </div>
                </form>
                <!-- users edit account form ends -->
            </div>
            <!-- Account Tab ends -->



            </div>
        </div>
    </div>
</section>
<!-- users edit ends -->

@endsection

@push('page_scripts_vendors')
<script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
<script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
@endpush

@push('page_scripts')
    <script src="{{ asset('assets/dashboard') }}/app-assets/js/scripts/pages/app-user-edit.min.js"></script>
    <script src="{{ asset('assets/dashboard') }}/app-assets/js/scripts/components/components-navs.min.js"></script>
@endpush
