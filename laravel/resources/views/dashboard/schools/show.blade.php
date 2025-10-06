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
                        <i data-feather="user"></i><span class="d-none d-sm-block">بيانات المدرسة</span>
                    </a>
                </li>
                @if ($school->grades->count() > 0)
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" id="grade-tab" data-toggle="tab" href="#grade" aria-controls="grade" role="tab" aria-selected="false">
                        <i  class="fa fa-school"></i><span class="d-none d-sm-block">بيانات المرحل الدراسية</span>
                    </a>
                </li>
                @endif
                @if ($school->classrooms->count() > 0)
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" id="classrooms-tab" data-toggle="tab" href="#classrooms" aria-controls="classrooms" role="tab" aria-selected="false">
                        <i  class="fa fa-building"></i><span class="d-none d-sm-block">بيانات الصفوف الدراسية</span>
                    </a>
                </li>
                @endif


                @if ($school->buses->count() > 0)
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" id="buses-tab" data-toggle="tab" href="#buses" aria-controls="buses" role="tab" aria-selected="false">
                        <i  class="fa fa-bus"></i><span class="d-none d-sm-block">بيانات  البصات</span>
                    </a>
                </li>
                @endif

                @if ($admins->count()  > 0)
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" id="attendants-tab" data-toggle="tab" href="#attendants" aria-controls="attendants" role="tab" aria-selected="false">
                        <i  class="fa fa-user"></i><span class="d-none d-sm-block">بيانات   المشرفين</span>
                    </a>
                </li>
                @endif
                @if ($drivers->count()  > 0)
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" id="drivers-tab" data-toggle="tab" href="#drivers" aria-controls="drivers" role="tab" aria-selected="false">
                        <i  class="fa fa-user"></i><span class="d-none d-sm-block">بيانات   السائق</span>
                    </a>
                </li>
                @endif


                @if ($school->students->count() > 0)
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" id="students-tab" data-toggle="tab" href="#students" aria-controls="students" role="tab" aria-selected="false">
                        <i  class="fa fa-user"></i><span class="d-none d-sm-block">بيانات  الطلاب</span>
                    </a>
                </li>
                @endif

                @if ($parents->count() > 0)
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" id="parents-tab" data-toggle="tab" href="#parents" aria-controls="parents" role="tab" aria-selected="false">
                        <i  class="fa fa-user"></i><span class="d-none d-sm-block">اولياء الامور</span>
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
                                    src="{{ $school->logo_path }}" alt="users avatar"
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
                                    <label for="name">اسم المدرسة</label>
                                    <input type="text" class="form-control" placeholder="Name" value="{{ $school->name }}" name="name" id="name" disabled/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" placeholder="Email" value="{{ $school->email }}" name="email" id="email" disabled/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone">رقم الهاتف</label>
                                    <input type="tel" class="form-control" placeholder="Phone" value="{{ $school->phone }}" name="phone" id="phone" disabled/>
                                </div>
                            </div>

                            @if ($school->city_name != null)

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city_name">اسم المدينة</label>
                                    <input type="text" class="form-control" placeholder="city name" value="{{ $school->city_name }}" name="city_name" id="city_name" disabled/>
                                </div>
                            </div>
                            @endif

                            @if ($school->address != null)

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address">العنوان</label>
                                    <input type="text" class="form-control" placeholder="address" value="{{ $school->address }}" name="address" id="address" disabled/>
                                </div>
                            </div>

                            @endif
                            @if ($school->myCoupons->count())

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="coupons">عدد  الكوبونات المستخدمه</label>
                                    <input type="text" class="form-control" placeholder="coupons" value="{{ $school->myCoupons->count() }}" name="coupons" id="coupons" disabled/>
                                </div>
                            </div>

                            @endif

                            <div class="col-md-12">
                                <div id="googleMap" style="height:300px;"></div>

                            </div>

                        </div>
                    </form>
                    <!-- users edit account form ends -->
                </div>
                <!-- Account Tab ends -->

                @if ($school->grades->count() > 0)

                <div class="tab-pane" id="grade" aria-labelledby="grade-tab" role="tabpanel">

                    <div class="table-responsive">
                        <table class="dt-multilingual table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>الاسم</th>
                                    <th>عدد الطلاب</th>
                                    <th>عدد الصفوف</th>


                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($school->grades as $index => $grades)
                                    <tr>
                                        <td>{{ $grades->id }}</td>
                                        <td>{{ $grades->name }}</td>
                                        <td>{{ $grades->students->count() }}</td>
                                        <td>{{ $grades->classroomg->count() }}</td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
                @endif

                @if ($school->classrooms->count() > 0)

                <div class="tab-pane" id="classrooms" aria-labelledby="classrooms-tab" role="tabpanel">

                    <div class="table-responsive">
                        <table class="dt-multilingual table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>الاسم</th>
                                    <th>المرحلة</th>
                                    <th>عدد الطلاب</th>


                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($school->classrooms as $index => $classrooms)
                                    <tr>
                                        <td>{{ $classrooms->id }}</td>
                                        <td>{{ $classrooms->name }}</td>
                                        <td>{{ $classrooms->grade->name }}</td>
                                        <td>{{ $classrooms->students->count() }}</td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
                @endif


                @if ($school->buses->count() > 0)

                <div class="tab-pane" id="buses" aria-labelledby="buses-tab" role="tabpanel">

                    <div class="table-responsive">
                        <table class="dt-multilingual table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>الاسم</th>
                                    <th>رقم الباص</th>
                                    <th>السائق</th>
                                    <th>المشرف</th>
                                    <th>عدد الطلاب</th>


                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($school->buses as $index => $buses)
                                    <tr>
                                        <td>{{ $buses->id }}</td>

                                        <td>{{ $buses->name }}</td>
                                        <td>{{ $buses->car_number }}</td>

                                        <td>{{ isset($buses->attendant_driver->name) ? $buses->attendant_driver->name : 'لم يتم تحديد السائق'}}</td>
                                        <td>{{ isset($buses->attendant_admins->name) ? $buses->attendant_admins->name : 'لم يتم تحديد المشرف'}}</td>

                                        <td>{{ $buses->students->count() }}</td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
                @endif
                @if ($admins->count() > 0)
                <div class="tab-pane" id="attendants" aria-labelledby="attendants-tab" role="tabpanel">

                    <div class="table-responsive">
                        <table class="dt-multilingual table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>صورة</th>
                                    <th>الاسم</th>
                                    <th>رقم الهاتف</th>
                                    <th>اسم الباص</th>


                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($admins as $index => $admins)
                                    <tr>
                                        <td>{{ $admins->id }}</td>
                                        <td>

                                            <img width="60"
                                             src="{{ $admins->logo_path }}"
                                            class="m--img-rounded m--marginless" alt="cover">

                                        </td>
                                        <td>{{ $admins->name }}</td>
                                        <td>{{ $admins->phone }}</td>
                                        <td>{{ isset($admins->bus) != null ?  $admins->bus->name : ''}}</td>



                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>


                </div>
                @endif

                @if ($drivers->count() > 0)
                <div class="tab-pane" id="drivers" aria-labelledby="drivers-tab" role="tabpanel">

                    <div class="table-responsive">
                        <table class="dt-multilingual table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>صورة</th>
                                    <th>الاسم</th>
                                    <th>رقم الهاتف</th>
                                    <th>اسم الباص</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($drivers as $index => $driver)
                                    <tr>
                                        <td>{{ $driver->id }}</td>
                                        <td>

                                            <img width="60"
                                             src="{{ $driver->logo_path }}"
                                            class="m--img-rounded m--marginless" alt="cover">

                                        </td>
                                        <td>{{ $driver->name }}</td>
                                        <td>{{ $driver->phone }}</td>
                                        <td>{{ isset($driver->bus) != null ?  $driver->bus->name : ''}}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>


                </div>
                @endif

                @if ($school->students->count() > 0)
                <div class="tab-pane" id="students" aria-labelledby="students-tab" role="tabpanel">

                    <div class="table-responsive">
                        <table class="dt-multilingual table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>صورة</th>
                                    <th>الاسم</th>
                                    <th>رقم الهاتف</th>
                                    <th>المرحلة الدرسية</th>
                                    <th>الصفوف الدرسية</th>
                                    <th> عدد اوليائ الامور</th>


                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($school->students as $index => $student)
                                    <tr>
                                        <td>{{ $student->id }}</td>
                                        <td>

                                            <img width="60"
                                             src="{{ $student->logo_path }}"
                                            class="m--img-rounded m--marginless" alt="cover">

                                        </td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->phone }}</td>
                                        <td>{{ $student->grade->name }}</td>
                                        <td>{{ $student->classroom->name }}</td>
                                        <td>{{ $student->my_Parents->count() }}</td>



                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>


                </div>
                @endif
                @if ($parents->count() > 0)
                <div class="tab-pane" id="parents" aria-labelledby="parents-tab" role="tabpanel">

                    <div class="table-responsive">
                        <table class="dt-multilingual table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>صورة</th>
                                    <th>الاسم</th>
                                    <th>رقم الهاتف</th>



                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($parents as $index => $parent)
                                    <tr>
                                        <td>{{ $parent->id }}</td>
                                        <td>

                                            <img width="60"
                                             src="{{ $parent->logo_path }}"
                                            class="m--img-rounded m--marginless" alt="cover">

                                        </td>
                                        <td>{{ $parent->name }}</td>
                                        <td>{{ $parent->phone }}</td>




                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>


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
        var long = "{{ $school->longitude != null ? $school->longitude : 31.233334 }}";
var lat = "{{ $school->latitude  != null ? $school->latitude : 30.033333 }}";

var geocoder;
var googleMap;

function myMap() {


var mapProp= {
center:new google.maps.LatLng(lat,long),
zoom:7,
};


var googleMap = new google.maps.Map(document.getElementById("googleMap"),mapProp);


var marker = new google.maps.Marker({
position:mapProp.center,
animation:google.maps.Animation.BOUNCE
});

marker.setMap(googleMap);


geocoder = new google.maps.Geocoder();

geocoder.geocode( {'location': marker.getPosition()}, function(results, status) {
if (status == 'OK') {
  $('#lng').val(marker.getPosition().lng());
  $('#lat').val(marker.getPosition().lat());

  var lat = marker.getPosition().lat();
  var lng = marker.getPosition().lng();


    console.log(lat);
    console.log(lng);
    var link = "https://maps.google.com/?q="+lat+","+lng;

    $("#address").attr('href',link);
} else {
    console.log('Geocode was not successful for the following reason: ' + status);
}
});


}

</script>
<script
src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=myMap&&language=ar&region=EGasync defer">
</script>
@endpush
