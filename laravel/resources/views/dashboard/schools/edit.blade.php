@extends('dashboard.layouts.app')
@push('page_vendor_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
@endpush
@push('page_styles')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashboard') }}/app-assets/css/plugins/forms/pickers/form-flat-pickr.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashboard') }}/app-assets/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/css/pages/app-user.min.css">
@endpush
@section('content')
    <!-- users edit start -->
    <section class="app-user-edit">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center active" id="account-tab" data-toggle="tab"
                            href="#account" aria-controls="account" role="tab" aria-selected="true">
                            <i data-feather="user"></i><span class="d-none d-sm-block">تعديل بيانات المدرسة</span>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" id="information-tab" data-toggle="tab"
                        href="#information" aria-controls="information" role="tab" aria-selected="false">
                        <i data-feather="info"></i><span class="d-none d-sm-block">Information</span>
                    </a>
                </li> --}}

                </ul>
                <div class="tab-content">
                    <!-- Account Tab starts -->
                    <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                        <!-- users edit account form start -->
                        <form action="{{ route('dashboard.schools.update', $school->id) }}" method="post"
                            enctype="multipart/form-data" class="mt-2">
                            @csrf
                            @method('PUT')
                            <div class="row">


                                <div class="col-md-12">
                                    <!-- users edit media object start -->
                                    <div class="media mb-2">
                                        {{-- <img src="{{ image_or_placeholder($school->logo_full_path, 'profile') }}" alt="users avatar"
                                            class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                                            height="90" width="90" /> --}}

                                        <img src="{{ $school->logo_path }}" alt="users avatar"
                                            class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                                            height="90" width="90">

                                        <div class="media-body mt-50">
                                            {{-- <h4>Eleanor Aguilar</h4> --}}
                                            <div class="col-12 d-flex mt-1 px-0">
                                                <label class="btn btn-primary mr-75 mb-0" for="change-picture">
                                                    <span class="d-none d-sm-block">اختر صورة</span>
                                                    <input class="form-control" type="file" name="logo"
                                                        id="change-picture" hidden
                                                        accept="image/png, image/jpeg, image/jpg" />
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
                                <!-- users edit media object ends -->

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">اسم</label>
                                        <input type="text" class="form-control" placeholder="Name"
                                            value="{{ $school->name }}" name="name" id="name" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email">البريد الالكترونى</label>
                                        <input type="email" class="form-control" placeholder="Email"
                                            value="{{ $school->email }}" name="email" id="email" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="phone">رقم الهاتف</label>
                                        <input type="tel" class="form-control" placeholder="Phone"
                                            value="{{ $school->phone }}" name="phone" id="phone" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="password">الرقم السرى</label>
                                        <input type="password" class="form-control" placeholder="Password" name="password"
                                            id="password" />
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">status</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="1" @if ($school->status == 1) selected @endif>مفعل
                                            </option>
                                            <option value="0" @if ($school->status == 0) selected @endif>غير
                                                مفعل</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email_verified_at">حالة الايميل</label>
                                        <select class="form-control" name="email_verified_at" id="email_verified_at">
                                            <option value="1" @if ($school->email_verified_at == 1) selected @endif>مفعل
                                            </option>
                                            <option value="0" @if ($school->email_verified_at == null) selected @endif>غير
                                                مفعل</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address">العنوان</label>
                                        <input type="text" class="form-control" placeholder="address"
                                            value="{{ $school->address }}" name="address" id="address" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="location">الموقع</label>
                                        <input type="text" class="form-control" placeholder="location"
                                             name="location" id="location" />
                                    </div>
                                </div>
                                {{-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="city_name">اسم المدينة</label>
                                        <input type="text" class="form-control" placeholder="city_name"
                                            value="{{ $school->city_name }}" name="city_name" id="city_name" />
                                    </div>
                                </div> --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="lat">latitude</label>
                                        <input type="text" class="form-control" placeholder="latitude"
                                            value="{{ $school->latitude }}" name="latitude" id="lat" />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="lng">longitude</label>
                                        <input type="text" class="form-control" placeholder="longitude"
                                            value="{{ $school->longitude }}" name="longitude" id="lng" />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="searchInput">للبحث</label>
                                        <input type="text" class="form-control" placeholder="بحث" id="searchInput"
                                            value="{{ $school->city_name }} {{ $school->address }}"
                                            name="searchInput" />

                                        <input type="button" class="btn btn-primary mb-1 mt-1 mb-sm-0 mr-0 mr-sm-1"
                                            value="search" id="searchInputBtn" />
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="points">نقط الطول والعرض</label>
                                        <input type="text" class="form-control" placeholder="نقط الطول والعرض"
                                            value="{{ old('points') }}" name="points" id="points" />
                                    </div>
                                </div>
                                <div class="col-lg-12">

                                    <div id="googleMap" style="height:300px;"></div>


                                </div>

                                <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                    <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">Save
                                        Changes</button>
                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
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
        let points = document.getElementById('points')
        points.addEventListener('change', () => {
            let pointss = points.value;
            let pointarray = pointss.split(", ");
            document.getElementById('lat').value = pointarray[0];
            document.getElementById('lng').value = pointarray[1];
            // console.log(pointss.split(", "));
        })
        document.getElementById('searchInputBtn').addEventListener('click', () => {

            window.open(`http://maps.google.com/?q=${document.getElementById('searchInput').value}`, '_blank');
        })

        var long = "{{ $school->longitude != null ? $school->longitude : 31.233334 }}";
        var lat = "{{ $school->latitude != null ? $school->latitude : 30.033333 }}";
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
                    $("#location").val(results[0].formatted_address);
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
                        $("#location").val(results[0].formatted_address);
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
@endpush
