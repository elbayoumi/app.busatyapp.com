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
        <div class="card-body">
            <ul class="nav nav-pills" role="tablist">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center active" id="account-tab"
                        data-toggle="tab" href="#account" aria-controls="account" role="tab" aria-selected="true">
                        <i data-feather="user"></i><span class="d-none d-sm-block"> بيانات الطالب </span>
                    </a>
                </li>


                @if ($student->my_Parents->count() > 0)

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" id="feather-tab" data-toggle="tab" href="#feather" aria-controls="feather" role="tab" aria-selected="false">
                        <i data-feather="user"></i><span class="d-none d-sm-block">  اولياء امر الطالب </span>
                    </a>
                </li>

                @endif

                @if ($student->bus != null)
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" id="bus-tab" data-toggle="tab" href="#bus" aria-controls="bus" role="tab" aria-selected="false">
                        <i  class="fa fa-bus"></i><span class="d-none d-sm-block"> الباص</span>
                    </a>
                </li>
                @endif




                @if ($student->schools != null)
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" id="schools-tab" data-toggle="tab" href="#schools" aria-controls="schools" role="tab" aria-selected="false">
                        <i  class="fa fa-bus"></i><span class="d-none d-sm-block"> بيانات المدرسة</span>
                    </a>
                </li>
                @endif


            </ul>
            <div class="tab-content">
                <!-- Account Tab starts -->
                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                    <!-- users edit account form start -->
                    <form class="mt-2">
                        <div class="row">

                            <div class="col-md-12">
                                <!-- users edit media object start -->
                                <div class="media mb-2">
                                    <img
                                    src="{{ $student->logo_path }}" alt="users avatar"
                                    class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                                    height="90" width="90">
                                    <div class="media-body mt-50">
                                        {{-- <h4>Eleanor Aguilar</h4> --}}
                                        <div class="col-12 d-flex mt-1 px-0">

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
                                    <input type="text" class="form-control" placeholder="Name" value="{{ $student->name }}" name="name" id="name" disabled/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone"> الهاتف</label>
                                    <input type="tel" class="form-control" placeholder="Phone" value="{{isset($student->phone) ? $student->phone  : 'غير معرف'  }}" name="phone" id="phone" disabled/>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address">العنوان</label>
                                    <input type="text" class="form-control" placeholder="address" value="{{ $student->address }}" name="address" id="address" disabled/>
                                </div>
                            </div>

                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="religion_id">الديانة</label>
                                    <input type="text" class="form-control" placeholder="religion_id" value="{{isset($student->religion->name) ? $student->religion->name  : 'غير معرف' }}" name="religion_id" id="religion_id" disabled/>
                                </div>
                            </div> --}}


                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="Date_Birth">تاريخ الميلاد</label>
                                    <input type="text" class="form-control" placeholder="Date_Birth" value="{{isset($student->Date_Birth) ? $student->Date_Birth  : 'غير معرف'  }}" name="Date_Birth" id="Date_Birth" disabled/>
                                </div>
                            </div> --}}


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="parent_key">الكود الاول (KEY)</label>
                                    <input type="text" class="form-control" placeholder="parent_key" value="{{ $student->parent_key  }}" name="parent_key" id="parent_key" disabled/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="parent_secret">الكود الثاني (SECRET)</label>
                                    <input type="text" class="form-control" placeholder="parent_secret" value="{{ $student->parent_secret  }}" name="parent_secret" id="parent_secret" disabled/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender">النوع</label>
                                    <input type="text" class="form-control" placeholder="gender" value="{{isset($student->gender->name) ? $student->gender->name  : 'غير معرف' }}" name="Date_Birth" id="gender" disabled/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="location">location</label>
                                    <input type="text" class="form-control" placeholder="location"
                                        value="{{ old('location') }}" name="location" id="location" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="school_id"> اسم المدرسة</label>
                                    <input type="text" class="form-control" placeholder="school_id" value="{{ $student->schools->name  }}" name="school_id" id="school_id" disabled/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="grade_id">المرحلة الدراسية</label>
                                    <input type="text" class="form-control" placeholder="grade_id" value="{{ $student->grade->name  }}" name="grade_id" id="grade_id" disabled/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="classroom_id">الصف الدراسي</label>
                                    <input type="text" class="form-control" placeholder="classroom_id" value="{{ $student->classroom->name  }}" name="classroom_id" id="classroom_id" disabled/>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div id="googleMap" style="height:300px;"></div>

                            </div>

                        </div>

                    </form>
                    <!-- users edit account form ends -->
                </div>
                <!-- Account Tab ends -->
                @if ($student->my_Parents->count() > 0)

                <div class="tab-pane" id="feather" aria-labelledby="feather-tab" role="tabpanel">
                    <!-- users edit Info form start -->
                    <form class="form-validate">

                        <div class="table-responsive">
                            <table class="dt-multilingual table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>صور</th>
                                        <th>الاسم </th>
                                        <th> البريد الاكتروني</th>
                                        <th>رقم الهاتف</th>
                                        <th> العنوان</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($student->my_Parents as $Parents)
                                    <tr>
                                            <td>{{ $Parents->id }}</td>
                                            <td>
                                                <img width="60"
                                                src="{{ $Parents->logo_path }}"
                                               class="m--img-rounded m--marginless" alt="cover">
                                            </td>
                                            <td>{{ $Parents->name }}</td>
                                            <td>{{ $Parents->email }}</td>
                                            <td>{{ $Parents->phone }}</td>
                                            <td>{{ $Parents->address }}</td>

                                        </tr>
                                    @endforeach


                                </tbody>
                            </table>

                        </div>

                    </form>
                    <!-- users edit Info form ends -->
                </div>

                @endif
                @if ($student->bus != null)

                <div class="tab-pane" id="bus" aria-labelledby="bus-tab" role="tabpanel">
                    <!-- users edit Info form start -->
                    <form class="form-validate">

                        <div class="row">


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name"> الاسم</label>
                                <input type="text" class="form-control" value="{{ $student->bus->name }}" name="name" id="name" disabled/>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="phone"> رقم الباص</label>
                                <input type="tel" class="form-control"  value="{{ $student->bus->car_number }}" name="phone" id="phone" disabled/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="city_name"> السائق </label>
                                <input type="text" class="form-control" value="{{ isset($student->bus->driver->name ) ? $student->bus->driver->name : 'لم يتم تحديد السائق' }}" name="city_name" id="city_name" disabled/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="city_name"> المشرف </label>
                                <input type="text" class="form-control"value="{{ isset($student->bus->admin->name) ? $student->bus->admin->name : 'لم يتم تحديد المشرف' }}" name="city_name" id="city_name" disabled/>
                            </div>
                        </div>

                        <div class="col-md-4" style="display: block">
                            <div class="form-group">
                                <label for="trip_type">نوع اشتراك الباص</label>
                                <select class="form-control " id="myDIV" name="trip_type" id="trip_type" disabled>
                                    <option value="" disabled> اختر النوع </option>
                                    <option value="full_day"   @if ($student->trip_type == 'full_day') selected @endif> ذهاب وعودة </option>
                                    <option value="start_day"    @if ($student->trip_type == 'start_day') selected @endif> ذهب فقد </option>
                                    <option value="end_day"  @if ($student->trip_type == 'end_day') selected @endif> عودة فقد </option>

                                </select>
                            </div>
                        </div>
                    </div>
                    </form>
                    <!-- users edit Info form ends -->
                </div>

                @endif


                @if ($student->schools != null)

                <div class="tab-pane" id="schools" aria-labelledby="schools-tab" role="tabpanel">
                    <!-- users edit Info form start -->
                    <form class="form-validate">

                        <div class="row">


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name"> الاسم</label>
                                <input type="text" class="form-control" value="{{ $student->schools->name }}" name="name" id="name" disabled/>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="phone"> رقم الهاتف </label>
                                <input type="tel" class="form-control"  value="{{ $student->schools->phone }}" name="phone" id="phone" disabled/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="city_name"> البريد الالكتروني </label>
                                <input type="text" class="form-control" value="{{ $student->schools->email }}" name="city_name" id="city_name" disabled/>
                            </div>
                        </div>


                    </div>
                    </form>
                    <!-- users edit Info form ends -->
                </div>

                @endif
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
        var long = "{{ $student->longitude != null ? $student->longitude : 31.233334 }}";
        var lat = "{{ $student->latitude != null ? $student->latitude : 30.033333 }}";
        var geocoder;
        var googleMap;

        function myMap() {

            var mapProp = {
                center: new google.maps.LatLng(lat, long),
                zoom: 5,
            };


            googleMap = new google.maps.Map(document.getElementById("googleMap"), mapProp);


            var marker = new google.maps.Marker({
                position: mapProp.center,
                animation: google.maps.Animation.BOUNCE
            });

            marker.setMap(googleMap);

            geocoder = new google.maps.Geocoder();

            var address = $('#addressSearchField').val();

            geocoder.geocode({
                'address': address
            }, function(results, status) {

                if (status == 'OK') {

                    var location = results[0].geometry.location;

                    marker.setPosition(location);

                    googleMap.setCenter(location);
                } else {
                    console.log('Geocode was not successful for the following reason: ' + status);
                }

            });
            geocoder.geocode({
                'location': marker.getPosition()
            }, function(results, status) {
                if (status == 'OK') {
                    $('#lat').val(marker.getPosition().lat());
                    $('#lng').val(marker.getPosition().lng());
                    $("#location").val(results[0].formatted_address);
                    $('#city_name').val(results[0].address_components[2].long_name);

                } else {
                    console.log('Geocode was not successful for the following reason: ' + status);
                }
            });

            marker.addListener("click", () => {
                googleMap.setZoom(8);
                googleMap.setCenter(marker.getPosition());
            });


            googleMap.addListener("click", (mapsMouseEvent) => {
                let clickAddress = mapsMouseEvent.latLng.toJSON();
                marker.setPosition(clickAddress);

                geocoder.geocode({
                    'location': marker.getPosition()
                }, function(results, status) {
                    if (status == 'OK') {
                        $('#lat').val(marker.getPosition().lat());
                        $('#lng').val(marker.getPosition().lng());
                        $("#location").val(results[0].formatted_address);
                        $('#city_name').val(results[0].address_components[2].long_name);

                    } else {
                        console.log('Geocode was not successful for the following reason: ' + status);
                    }
                });


                $('#lat').val(clickAddress.lat);
                $('#lng').val(clickAddress.lng);

            });

            ///////////////////Branch Google Map //////////////////
            branchGoogleMap = new google.maps.Map(document.getElementById("branch-google-map"), mapProp);


            var branch_marker = new google.maps.Marker({
                position: mapProp.center,
                animation: google.maps.Animation.BOUNCE
            });

            branch_marker.setMap(branchGoogleMap);

            branchGeocoder = new google.maps.Geocoder();

            branchGeocoder.geocode({
                'location': branch_marker.getPosition()
            }, function(results, status) {
                if (status == 'OK') {
                    $('#branch-lat').val(branch_marker.getPosition().lat());
                    $('#branch-lng').val(branch_marker.getPosition().lng());
                    $("#address").val(results[0].formatted_address);
                } else {
                    console.log('Geocode was not successful for the following reason: ' + status);
                }
            });

            branch_marker.addListener("click", () => {
                branchGoogleMap.setZoom(8);
                branchGoogleMap.setCenter(branch_marker.getPosition());
            });


            branchGoogleMap.addListener("click", (mapsMouseEvent) => {
                let clickAddress = mapsMouseEvent.latLng.toJSON();
                branch_marker.setPosition(clickAddress);

                branchGeocoder.geocode({
                    'location': branch_marker.getPosition()
                }, function(results, status) {
                    if (status == 'OK') {
                        $('#branch-lat').val(branch_marker.getPosition().lat());
                        $('#branch-lng').val(branch_marker.getPosition().lng());
                        $("#address").val(results[0].formatted_address);
                    } else {
                        console.log('Geocode was not successful for the following reason: ' + status);
                    }
                });


                $('#branch-lat').val(clickAddress.lat);
                $('#branch-lng').val(clickAddress.lng);

            });
        }
    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=myMap&&language=ar&region=EGasync defer">
    </script>

    <script>
        $(document).ready(function() {
            $('select[name="grade_id"]').on('change', function() {
                var school_id = $('input[name="school_id"]').data("id");
                console.log(school_id);
                var grade_id = $(this).val();
                if (grade_id) {
                    $.ajax({
                        url: "{{ URL::to('dashboard/classrooms/get-classrooms') }}/" + grade_id,
                        type: "GET",
                        dataType: "json",
                        data: {
                            school_id: school_id
                        },
                        success: function(data) {
                            $('select[name="classroom_id"]').empty();
                            $('select[name="classroom_id"]').append(
                                '<option value="" disabled> اختر صف دراسي</option>');

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
