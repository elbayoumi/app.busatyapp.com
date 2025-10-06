@extends('dashboard.trips.layouts.app')
@section('trips')
    <div class="tab-content">
        <!-- Account Tab starts -->
        <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
            <div class="table-responsive">
                <table class="dt-multilingual table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>اسم الطالب</th>
                            <th>المرحلة</th>
                            <th>حالة الطالب</th>
                            <th>الاعدادات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (\App\Models\Student::with('attendance')->whereHas('attendance', function ($e) use ($trip) {$e->where('trip_id', $trip->id); })->get() as $index => $student)
                            <tr class="@if (in_array($student->id, $absence_ids)) bg-light-warning @endif">
                                <td>{{ $student->id }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->grade->name }}</td>

                                <td style="min-width: 200px">
                                    <span class="text-{{ $student->attendance->where('trip_id', $trip->id)->first()->tr_attendence_status()['color'] }}">{{ $student->attendance->where('trip_id', $trip->id)->first()->tr_attendence_status()['text'] }}</span>
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-success btn-sm dropdown-toggle" href="#" role="button"
                                            id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            العمليات
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                            @if (auth()->user()->canany(['super', 'attendance-edit']) && $trip->trip_type == 'end' && $trip->status == 0  && $student->attendance->where('trip_id', $trip->id)->first()->attendence_status == 1)
                                            <a href="{{ route('dashboard.attendances.students.end', $student->attendance->where('trip_id', $trip->id)->first()->id) }}"
                                                class="dropdown-item" style="padding: 6px;" title="Edit">
                                                <i data-feather='eye'></i>
                                                انهاء رحلة الطالب
                                            </a>
                                            @endif
                                            @if(auth()->user()->canany(['super', 'attendances-edit']) && $trip->status == 0)
                                            <a  class="dropdown-item" style="padding: 6px;" data-toggle="modal" data-target="#exampleModal-{{ $student->attendance->where('trip_id', $trip->id)->first()->id}}">
                                                <i data-feather='edit'></i>
                                                تعديل الغياب
                                            </a>
                                            @endif
                                            @if (auth()->user()->canany(['super', 'trips-show']) && $student->attendance->where('trip_id', $trip->id)->first()->attendence_status == 1  && $trip->status == 0)
                                            <a href="#"
                                                class="dropdown-item" style="padding: 6px;" title="Edit">
                                                <i data-feather='map-pin'></i>
                                                عرض علي الخريطة
                                            </a>
                                            @endif
                                          

                                          @if (auth()->user()->canany(['super', 'attendances-show']))
                                          <a href="{{ route('dashboard.attendances.show', $student->attendance->where('trip_id', $trip->id)->first()->id) }}"
                                              class="dropdown-item" style="padding: 6px;" title="show">
                                              <i data-feather='eye'></i>
                                              عرض بيانات الغياب
                                          </a>
                                          @endif

                                          @if(auth()->user()->canany(['super', 'addresses-destroy'])  && $trip->status == 0)
                                          <a onclick="event.preventDefault();" data-delete="delete-form-{{$index}}" href="{{ route('dashboard.attendances.destroy', $student->attendance->where('trip_id', $trip->id)->first()->id) }}" class="m-portlet__nav-link btn m-btn m-btn--hover-blog m-btn--icon m-btn--icon-only m-btn--pill but_delete_action" style="padding: 6px;" title="Delete">
                                              <i data-feather='trash'></i>
                                              حذف بيانات الغياب
                                          </a>
                                          <form id="delete-form-{{$index}}" action="{{ route('dashboard.attendances.destroy', $student->attendance->where('trip_id', $trip->id)->first()->id) }}" method="POST" style="display: none;">
                                              @method('DELETE')
                                              @csrf
                                          </form>
                                      @endif
                                        </div>
                                    </div>
                                </td>
<!-- Modal -->
                                <div class="modal fade" id="exampleModal-{{$student->attendance->where('trip_id', $trip->id)->first()->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">
                                    <div class="modal-dialog" role="document">
                                        <form action="{{ route('dashboard.attendances.update', $student->attendance->where('trip_id', $trip->id)->first()->id) }}" method="post">
                                          @csrf
                                          @method('put')
                                            <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="exampleModalLabel">{{ $student->name }}</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                            <label class="block text-gray-500 font-semibold sm:border-r sm:pr-4">
                                                <input name="attendences"
                                                       {{$student->attendance->where('trip_id', $trip->id)->first()->attendence_status == 1 ? 'checked' : '' }}
                                                       class="leading-tight" type="radio" value="presence">
                                                <span class="text-info">حضور</span>
                                            </label>

                                            <label class="ml-4 block text-gray-500 font-semibold">
                                                <input name="attendences"
                                                       {{ $student->attendance->where('trip_id', $trip->id)->first()->attendence_status == 0 ? 'checked' : '' }}
                                                       class="leading-tight" type="radio" value="absent">
                                                <span class="text-danger">غياب</span>
                                            </label>
                                            @if ($trip->trip_type == 'end')
                                            <label class="ml-4 block text-gray-500 font-semibold">
                                                <input name="attendences"
                                                       {{ $student->attendance->where('trip_id', $trip->id)->first()->attendence_status == 3 ? 'checked' : '' }}
                                                       class="leading-tight" type="radio" value="at_home">
                                                <span class="text-success">وصل الطالب الي المنزل</span>
                                            </label>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                          <button type="submit" class="btn btn-primary">حفظ</button>
                                        </div>
                                        </div>

                                        </form>

                                    </div>
                                  </div>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
        <!-- Account Tab ends -->
    </div>
@endsection
