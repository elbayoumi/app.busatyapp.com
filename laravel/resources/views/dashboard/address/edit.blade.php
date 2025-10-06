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
            <h4 class="card-title">  تعديل / طلب تغير العنوان</h4>

        </div>
        <div class="card-body">

            <div class="tab-content">
                <!-- Account Tab starts -->
                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                    @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div>{{$error}}</div>
                    @endforeach
                @endif
                    <!-- users edit account form start -->
                    <form action="{{route('dashboard.addresses.update', $address->id) }}" method="post" enctype="multipart/form-data" class="mt-2">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="New_address">العنوان الجديد</label>
                                    <input id="New_address" type="text" class="form-control" value="{{ $address->New_address }}" name="New_address" id="New_address" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="latitude">latitude</label>
                                    <input type="text" class="form-control" placeholder="latitude"
                                        value="{{ $address->latitude }}" name="latitude" id="address"/>
                                </div>
                            </div>
    
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="longitude">longitude</label>
                                    <input type="text" class="form-control" placeholder="longitude"
                                        value="{{ $address->longitude }}" name="longitude" id="longitude"/>
                                </div>
                            </div>
    
                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">حفظ </button>
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

    <script>
            function displayNoneFunction() {
            var x = document.getElementById("myDIV").parentElement.parentElement;
            if (x.style.display === "block") {
                x.style.display = "none";
            } else {
                x.style.display = "none";
            }
        }

        function displayBlockFunction() {
            var x = document.getElementById("myDIV").parentElement.parentElement;
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "block";
            }
        }

        $(document).ready(function() {
            $('select[name="bus_id"]').on('change', function() {
                var bus_id = $(this).val();
                var trip_type = $('select[name="trip_type"]');

                if (bus_id == '') {

                    displayNoneFunction()

                } else {

                    displayBlockFunction()

                }
            });
        });
    </script>
@endpush
