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
                        <i data-feather="user"></i><span class="d-none d-sm-block"> عرض بيانات الاشتراك </span>
                    </a>
                </li>


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
                                    src="{{ $subscription?->parents->logo ??''}}" alt="users avatar"
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
                                    <label for="name">Order ID</label>
                                    <input type="text" class="form-control" placeholder="Name" value="{{ $subscription?->transaction_id }}" name="name" id="name" disabled/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">plan name</label>
                                    <input type="text" class="form-control" placeholder="plan_name" value="{{ $subscription?->plan_name}}" name="name" id="name" disabled/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">amount</label>
                                    <input type="text" class="form-control" placeholder="amount" value="{{ $subscription?->currency .' '.$subscription?->amount  }}" name="name" id="name" disabled/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">start_date</label>
                                    <input type="text" class="form-control" placeholder="start_date" value="{{$subscription?->start_date  }}" name="name" id="name" disabled/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">end_date</label>
                                    <input type="text" class="form-control" placeholder="end_date" value="{{$subscription?->end_date  }}" name="name" id="name" disabled/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">duration</label>
                                    <input type="text" class="form-control" placeholder="end_date" value="{{daysDifference($subscription?->start_date,$subscription?->end_date).' '.'days'   }}" name="name" id="name" disabled/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">remainder</label>
                                    <input type="text" class="form-control" placeholder="end_date" value="{{daysDifference(\Carbon\Carbon::now(),$subscription?->end_date).' '.'days'   }}" name="name" id="name" disabled/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">status</label>
                                    <input type="text" class="form-control" placeholder="status" value="{{statusAr($subscription?->status)   }}" name="name" id="name" disabled/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">payment_method</label>
                                    <input type="text" class="form-control" placeholder="payment_method" value="{{$subscription?->payment_method  }}" name="name" id="name" disabled/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="username">البريد الالكتروني</label>
                                    <input type="text" class="form-control" placeholder="username" value="{{ $subscription?->parents->email ??''}}" name="username" id="username" disabled/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone">الهاتف</label>
                                    <input type="tel" class="form-control" placeholder="Phone" value="{{ $subscription?->parents->phone ??'' }}" name="phone" id="phone" disabled/>
                                </div>
                            </div>
                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city_name"> اسم المدبنة </label>
                                    <input type="text" class="form-control" placeholder="city name" value="{{ isset($subscription?->parents->city_name) ? $subscription?->parents->city_name  : 'غير معرف'  }}" name="city_name" id="city_name" disabled/>
                                </div>
                            </div> --}}

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address">العنوان</label>
                                    <input type="text" class="form-control" placeholder="address" value="{{ $subscription?->parents->address ??''}}" name="address" id="address" disabled/>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address">تاريخ  الانضمام</label>
                                    <input type="text" class="form-control" placeholder="type" value="{{isset($subscription?->parents->created_at) ? $subscription?->parents->created_at  : 'غير معرف'  }}" name="Joining_Date" id="Joining_Date" disabled/>
                                </div>
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
