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
    <style>
        #myDIV {}
    </style>
    @endpush



@section('content')
    <!-- users edit start -->
    <section class="app-user-edit">
        <div class="card">

            <div class="card-header border-bottom">
                <h4 class="card-title">اضافة / طالب</h4>

            </div>
            <div class="card-body mt-3">

                <!-- Account Tab starts -->
                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                    <!-- users edit account form start -->
                    <form method="POST" action="{{ route('dashboard.students.store') }}"
                        class="m-form m-form--fit m-form--label-align-right" enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            <div class="col-md-12">
                                <!-- users edit media object start -->
                                <div class="media mb-2">
                                    <img src="{{ image_or_placeholder('', 'profile') }}" alt="users avatar"
                                        class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                                        height="90" width="90" />
                                    <div class="media-body mt-50">
                                        {{-- <h4>Eleanor Aguilar</h4> --}}
                                        <div class="col-12 d-flex mt-1 px-0">
                                            <label class="btn btn-primary mr-75 mb-0" for="change-picture">
                                                <span class="d-none d-sm-block">اختر صورة</span>
                                                <input class="form-control" type="file" name="logo"
                                                    id="change-picture" hidden accept="image/png, image/jpeg, image/jpg" />
                                                <span class="d-block d-sm-none">
                                                    <i class="mr-0" data-feather="edit"></i>
                                                </span>
                                            </label>
                                            <button class="btn btn-outline-secondary d-block d-sm-none">
                                                <i class="mr-0" data-feather="trash-2"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- users edit media object ends -->
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">اسم الطالب</label>
                                    <input type="text" class="form-control" placeholder="Name"
                                        value="{{ old('name') }}" name="name" id="name" required
                                        autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone">الهاتف</label>
                                    <input type="tel" class="form-control" placeholder="Phone"
                                        value="{{ old('phone') }}" name="phone" id="phone"  />
                                </div>
                            </div>

{{-- 
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label for="Date_Birth">تاريخ الميلاد</label>
                                    <input id="Date_Birth" type="text" class="form-control birthdate-picker"
                                        value="{{ old('Date_Birth') }}" name="Date_Birth" id="Date_Birth"
                                        autocomplete="off" placeholder="YYYY-MM-DD" />
                                </div>
                            </div> --}}

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender_id">النوع</label>
                                    <select class="form-control" name="gender_id" id="gender_id" >
                                        <option value="" disabled> اختر النوع</option>
                                        <option value="">غير معرف</option>
                                        @foreach ($genders as $gender)
                                            <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                                        @endforeach


                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="school_id">المدرسة</label>
                                    <select class="form-control basic-select2" name="school_id" id="school_id" required>
                                        <option value="" disabled> اختر المدرسة </option>
                                        @foreach ($school as $sch)
                                            <option value="{{ $sch->id }}">{{ $sch->name }}</option>
                                        @endforeach


                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="grade_id">المرحلة الدراسية</label>
                                    <select class="form-control" name="grade_id" id="grade_id" required>
                                        <option value="" disabled> اختر المرحلة </option>



                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="classroom_id">الصفوف الدراسية</label>
                                    <select class="form-control" name="classroom_id" id="classroom_id" required>
                                        <option value="" disabled> اختر الصف </option>



                                    </select>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bus_id ">الباصات</label>
                                    <select class="form-control  basic-select2" name="bus_id" id="bus_id">
                                        <option value="" disabled> اختر الباص </option>



                                    </select>
                                </div>
                            </div>



                            <div class="col-md-4" style="display: none">
                                <div class="form-group">
                                    <label for="trip_type">نوع اشتراك الباص</label>
                                    <select class="form-control " id="myDIV" name="trip_type" id="trip_type">
                                        <option value="" disabled> اختر النوع </option>
                                        <option value="full_day"> ذهاب وعودة </option>
                                        <option value="start_day"> ذهب فقد </option>
                                        <option value="end_day"> عودة فقد </option>

                                    </select>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status"> الحالة</label>
                                    <select class="form-control" name="status" id="status" >
                                        <option value="" disabled>اختر الحالة</option>
                                        <option value="1">:: مفعل ::</option>
                                        <option value="0">:: غير مفعل ::</option>


                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="lat">latitude</label>
                                    <input type="text" class="form-control" placeholder="latitude"
                                        value="{{ old('latitude') }}" name="latitude" id="lat"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="lng">longitude</label>
                                    <input type="text" class="form-control" placeholder="longitude"
                                        value="{{ old('longitude') }}" name="longitude" id="lng"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address">العنوان</label>
                                    <input type="text" class="form-control" placeholder="address"
                                        value="{{ old('address') }}" name="address" id="address" />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="location">location</label>
                                    <input type="text" class="form-control" placeholder="location"
                                        value="{{ old('location') }}" name="location" id="location" />
                                </div>
                            </div>

                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city_name">اسم المدينة</label>
                                    <input type="text" class="form-control" placeholder="City name"
                                        value="{{ old('city_name') }}" name="city_name" id="city_name"/>
                                </div>
                            </div> --}}
                            <div class="col-md-12" >
                                <div id="googleMap" style="height:300px;"></div>


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
    {{-- <script src="https://code.jquery.com/jquery-3.5.0.js"></script> --}}
<script>
var geocoder;
var googleMap;

function myMap() {

    var myLatlng = { lat: 30.033333, lng: 31.233334 };
var mapProp= {
center:myLatlng,
zoom:5,
};

var googleMap = new google.maps.Map(document.getElementById("googleMap"),mapProp);

var marker = new google.maps.Marker({
position:mapProp.center
});

var geocoder = new google.maps.Geocoder();

marker.setMap(googleMap);

if (navigator.geolocation) {


    navigator.geolocation.getCurrentPosition(function(position) {
        var pos = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
        };

        marker.setPosition(pos);

        $('#lat').val(marker.getPosition().lat());
        $('#lng').val(marker.getPosition().lng());
        $('#city_name').val(results[0].address_components[2].long_name);
        $("#location").val(results[0].formatted_address);

    });
}

marker.addListener("click", () => {
googleMap.setZoom(8);
googleMap.setCenter(marker.getPosition());
});

googleMap.addListener("click", (mapsMouseEvent) => {
let clickAddress = mapsMouseEvent.latLng.toJSON();
marker.setPosition(clickAddress);


    geocoder.geocode( {'location': marker.getPosition()}, function(results, status) {
        if (status == 'OK') {
            $('#lat').val(marker.getPosition().lat());
            $('#lng').val(marker.getPosition().lng());
            $('#city_name').val(results[0].address_components[2].long_name);
            $("#location").val(results[0].formatted_address);

        } else {
            console.log('Geocode was not successful for the following reason: ' + status);
        }
    });
});

}

</script>

<script
src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=myMap&&language=ar&region=EGasync defer">
</script>
    <script>
         $(document).ready(function() {
            $('.basic-select2').select2();
        });
        $(document).ready(function() {
            $('select[name="school_id"]').on('change', function() {
                var school_id = $(this).val();
                if (school_id) {
                    $.ajax({
                        url: "{{ URL::to('dashboard/grades/get-grades') }}/" + school_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="grade_id"]').empty();
                            $('select[name="classroom_id"]').empty();
                            $('select[name="grade_id"]').append('<option value="" disabled> اختر مرحلة دراسي</option>');
                            $('select[name="classroom_id"]').append('<option value="" disabled> اختر صف دراسي</option>');
                            $.each(data, function(key, value) {
                                $('select[name="grade_id"]').append('<option value="' +
                                    key + '">' + value + '</option>');
                            });
                        },
                    });
                } else {
                    console.log('AJAX load did not work');
                }
            });
        });

        $(document).ready(function() {
            $('select[name="school_id"]').on('change', function() {
                var school_id = $(this).val();
                if (school_id) {
                    $.ajax({
                        url: "{{ URL::to('dashboard/buses/get-bus') }}/" + school_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="bus_id"]').empty();
                            $('select[name="bus_id"]').append(
                                '<option value="" disabled selected> اختر الباص</option>');
                            $('select[name="bus_id"]').append(
                                '<option value="">لا يستخدم باص</option>');
                            $.each(data, function(key, value) {
                                $('select[name="bus_id"]').append('<option value="' +
                                    key + '">' + value + '</option>');
                            });
                        },
                    });
                } else {
                    console.log('AJAX load did not work');
                }
            });
        });

        $(document).ready(function() {
            $('select[name="grade_id"]').on('change', function() {
                var school_id = $('select[name="school_id"]').val();
                var grade_id = $(this).val();
                if (grade_id) {
                    $.ajax({
                        url: "{{ URL::to('dashboard/classrooms/get-classrooms') }}/" + grade_id,
                        type: "GET",
                        dataType: "json",
                        data:{school_id:school_id},
                        success: function(data) {
                            $('select[name="classroom_id"]').empty();
                            $('select[name="classroom_id"]').append('<option value="" disabled> اختر صف دراسي</option>');
                            $.each(data, function(key, value) {
                                $('select[name="classroom_id"]').append(
                                    '<option value="' + key + '">' + value +
                                    '</option>');
                            });
                        },
                    });
                } else {
                    console.log('AJAX load did not work');
                }
            });
        });

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
