@extends('dashboard.trips.layouts.app')
@section('trips')
            <div class="tab-content">
                <!-- Account Tab starts -->
                <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                 <tr>
                                    <th>اسم المدرسة</th>
                                    <td>{{ $trip->school->name  }}</td>
                                 </tr>
                                 <tr>
                                    <th>اسم الباص</th>
                                    <td>{{isset($trip->bus->name) != null ?  $trip->bus->name : 'Undefined'}}</td>
                                 </tr>
                                 <tr>
                                    <th>نوع الرحلة</th>
                                    <td>{{$trip->tr_trip_type()}}</td>
                                 </tr>
                                 <tr>
                                    <th>حالة الرحلة</th>
                                    <td class="text-{{ $trip->tr_status()['color'] }}">{{ $trip->tr_status()['text']}}</td>
                                </tr>

                                 <tr>
                                    <th>تاريخ الرحلة</th>
                                    <td>{{isset($trip->trips_date ) != null ?  $trip->trips_date  : 'Undefined'}}</td>
                                 </tr>
                            </tbody>

                        </table>
                    </div>

                </div>
                <!-- Account Tab ends -->
            </div>
@endsection
