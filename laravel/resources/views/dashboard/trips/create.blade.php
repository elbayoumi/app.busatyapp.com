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
            <h4 class="card-title">اضافة/ رحلة جديدة</h4>

        </div>
        <div class="card-body mt-1">

            <!-- Account Tab starts -->
            <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                <!-- users edit account form start -->
                <form method="POST" action="{{ route('dashboard.trips.store', $attendant->id) }}" class="m-form m-form--fit m-form--label-align-right" enctype="multipart/form-data">
                    @csrf
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lat">latitude</label>
                                <input type="text" class="form-control" placeholder="latitude"
                                    value="{{ $attendant->schools->latitude }}" name="latitude" id="lat"/>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lng">longitude</label>
                                <input type="text" class="form-control" placeholder="longitude"
                                    value="{{ $attendant->schools->longitude }}" name="longitude" id="lng"/>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="trip_type">نوع الرحلة</label>
                                <select class="form-control" name="trip_type" id="trip_type">
                                    <option value="start">بداية اليوم</option>
                                    <option value="end">نهاية اليوم</option>

                               </select>
                            </div>
                        </div>
                        <div class="col-lg-12">

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
        var long = "{{ $attendant->schools->longitude != null ? $attendant->schools->longitude : 31.233334 }}";
var lat = "{{ $attendant->schools->latitude != null ? $attendant->schools->latitude : 30.033333 }}";
var geocoder;
var googleMap;

function myMap() {

var mapProp= {
center:new google.maps.LatLng(lat,long),
zoom:5,
};


googleMap = new google.maps.Map(document.getElementById("googleMap"),mapProp);


var marker = new google.maps.Marker({
position:mapProp.center,
animation:google.maps.Animation.BOUNCE
});

marker.setMap(googleMap);

geocoder = new google.maps.Geocoder();

var address = $('#addressSearchField').val(); 

geocoder.geocode( {'address': address}, function(results, status) { 

if (status == 'OK') { 

var location = results[0].geometry.location; 

marker.setPosition(location); 

googleMap.setCenter(location); 
} else { 
console.log('Geocode was not successful for the following reason: ' + status); 
} 

});
geocoder.geocode( {'location': marker.getPosition()}, function(results, status) {
if (status == 'OK') {
    $('#lat').val(marker.getPosition().lat());
    $('#lng').val(marker.getPosition().lng());


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

geocoder.geocode( {'location': marker.getPosition()}, function(results, status) {
    if (status == 'OK') {
        $('#lat').val(marker.getPosition().lat());
        $('#lng').val(marker.getPosition().lng());

    } else {
        console.log('Geocode was not successful for the following reason: ' + status);
    }
});


$('#lat').val(clickAddress.lat);
$('#lng').val(clickAddress.lng);

});

///////////////////Branch Google Map //////////////////
branchGoogleMap = new google.maps.Map(document.getElementById("branch-google-map"),mapProp);


var branch_marker = new google.maps.Marker({
position:mapProp.center,
animation:google.maps.Animation.BOUNCE
});

branch_marker.setMap(branchGoogleMap);

branchGeocoder = new google.maps.Geocoder();

branchGeocoder.geocode( {'location': branch_marker.getPosition()}, function(results, status) {
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

branchGeocoder.geocode( {'location': branch_marker.getPosition()}, function(results, status) {
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


        $(document).ready(function () {
            $('select[name="my__parent_id"]').on('change', function () {
                var my__parent_id = $(this).val();
                if (my__parent_id) {
                    $.ajax({
                        url: "{{ URL::to('dashboard/students/get-students') }}/" + my__parent_id,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('select[name="student_id"]').empty();
                            $.each(data, function (key, value) {
                                $('select[name="student_id"]').append('<option value="' + key + '">' + value + '</option>');
                            });
                        },
                    });
                } else {
                    console.log('AJAX load did not work');
                }
            });
        });
    </script>
@endpush
