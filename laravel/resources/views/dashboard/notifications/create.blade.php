@extends('dashboard.layouts.app')
@push('page_vendor_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/forms/select/select2.min.css">
@endpush

@push('page_styles')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashboard') }}/app-assets/css/plugins/forms/form-validation.css">
@endpush

@section('content')
    <!-- notifications edit start -->
    <section class="app-notification-edit">
        <div class="card">
            <div class="card-header border-bottom">
                <h4 class="card-title">إضافة / تعديل إشعار</h4>
            </div>
            <div class="card-body mt-3">

                <!-- notifications edit form start -->
                <form method="POST"
                    action="{{ isset($notification) ? route('dashboard.notifications.update', $notification->id) : route('dashboard.notifications.store') }}"
                    class="m-form m-form--fit m-form--label-align-right" enctype="multipart/form-data">
                    @csrf
                    @if (isset($notification))
                        @method('PUT')
                    @endif
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title_ar">العنوان (عربي)</label>
                                <input type="text" class="form-control" placeholder="العنوان بالعربي" name="title[ar]"
                                    id="title_ar"
                                    value="{{ old('title.ar', isset($notification) ? $notification->title['ar'] : '') }}"
                                    required />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title_en">العنوان (إنجليزي)</label>
                                <input type="text" class="form-control" placeholder="Title in English" name="title[en]"
                                    id="title_en"
                                    value="{{ old('title.en', isset($notification) ? $notification->title['en'] : '') }}"
                                    required />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="body_ar">المحتوى (عربي)</label>
                                <textarea class="form-control" placeholder="المحتوى بالعربي" name="body[ar]" id="body_ar" required>{{ old('body.ar', isset($notification) ? $notification->body['ar'] : '') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="body_en">المحتوى (إنجليزي)</label>
                                <textarea class="form-control" placeholder="Body in English" name="body[en]" id="body_en" required>{{ old('body.en', isset($notification) ? $notification->body['en'] : '') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="default_body_ar"> للابليكيشن المحتوى  (عربي)</label>
                                <textarea class="form-control" placeholder="للابليكيشن المحتوى بالعربي" name="default_body[ar]" id="default_body_ar" >{{ old('default_body.ar', isset($notification) ? $notification->default_body['ar'] : '') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="default_body_en">للابليكيشن المحتوى  (إنجليزي)</label>
                                <textarea class="form-control" placeholder="Body in English" name="default_body[en]" id="default_body_en" >{{ old('default_body.en', isset($notification) ? $notification->default_body['en'] : '') }}</textarea>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                        <div class="form-group">
                            <label for="for_model_type">نوع المستلم</label>
                            <input type="text" class="form-control" placeholder="Model type for whom the notification is intended" name="for_model_type" id="for_model_type" value="{{ old('for_model_type', isset($notification) ? $notification->for_model_type : '') }}" required />
                        </div>
                    </div> --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="to_model_type">نوع المستلم</label>
                                <select class="form-control" name="to_model_type" id="to_model_type" required
                                    onchange="updateSelectedModelIndex('to', this)">
                                    <option value="">اختر نوع المستلم</option>
                                    @foreach ($models as $model)
                                        <option value="{{ $model['className'] }}"
                                            {{ isset($notification) && $notification->to_model_type == $model['className'] ? 'selected' : '' }}>
                                            {{ removeAppModels($model['className']) }}
                                            <!-- تأكد من تغيير 'name' حسب اسم الحقل الذي تريد عرضه -->
                                        </option>
                                    @endforeach

                                </select>

                                <!-- Checkboxes for additional properties -->
                                <div id="to_additional" style="display: none;">
                                    @foreach ($models as $index => $model)
                                        @if (isset($model['additional']))
                                            <div class="additional-checkboxes card p-1 mt-2" data-model="{{ str_replace('\\', '', $model['className']) }}" data-index="{{ $index }}" style="display: none;">
                                                <div class="card-header bg-primary text-white">
                                                    {{ removeAppModels($model['className']) }} Additional Options (نوع المستلم)
                                                </div>
                                                <div class="card-body">
                                                    @foreach ($model['additional'] as $key => $value)
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="to_model_additional[]"
                                                                value="{{ $value }}" id="additional-{{ $key }}"
                                                                {{ isset($notification) && in_array($value, $notification->to_model_additional) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="additional-{{ $key }}">
                                                                {{ $value }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="for_model_type">نوع المرسل </label>
                                <select class="form-control" name="for_model_type" id="for_model_type" required
                                    onchange="updateSelectedModelIndex('for', this)">
                                    <option value="">اختر نوع المرسل </option>
                                    @foreach ($models as $model)
                                        <option value="{{ $model['className'] }}"
                                            {{ isset($notification) && $notification->for_model_type == $model['className'] ? 'selected' : '' }}>
                                            {{ removeAppModels($model['className']) }}
                                            <!-- تأكد من تغيير 'name' حسب اسم الحقل الذي تريد عرضه -->
                                        </option>
                                    @endforeach
                                </select>
                                <!-- Checkboxes for additional properties -->
                                <div id="for_additional" style="display: none;">
                                    @foreach ($models as $index => $model)
                                        @if (isset($model['additional']))
                                            <div class="additional-checkboxes card p-1 mt-2"
                                                data-model="{{ str_replace('\\', '', $model['className']) }}"
                                                data-index="{{ $index }}" style="display: none;">
                                                <div class="card-header bg-success text-white">
                                                    {{ removeAppModels($model['className']) }} Additional Options (نوع المرسل)
                                                </div>
                                                <div class="card-body">
                                                    @foreach ($model['additional'] as $key => $value)
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="for_model_additional[]"
                                                                value="{{ $value }}" id="for-additional-{{ $key }}"
                                                                {{ isset($notification) && in_array($value, $notification->for_model_additional) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="for-additional-{{ $key }}">
                                                                {{ $value }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                            </div>
                        </div>
                        {{--
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="to_model_type">نوع المرسل إليه</label>
                            <input type="text" class="form-control" placeholder="Model type to whom the notification is sent" name="to_model_type" id="to_model_type" value="{{ old('to_model_type', isset($notification) ? $notification->to_model_type : '') }}" required />
                        </div>
                    </div> --}}

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="group">المجموعة</label>
                                <select class="form-control" name="group" id="group">
                                    <option value="1"
                                        {{ isset($notification) && $notification->group ? 'selected' : '' }}>نعم</option>
                                    <option value="0"
                                        {{ isset($notification) && !$notification->group ? 'selected' : '' }}>لا</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                            <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">حفظ</button>
                        </div>
                    </div>
                </form>
                <!-- notifications edit form ends -->
            </div>
        </div>
    </section>
    <!-- notifications edit ends -->
@endsection

@push('page_scripts_vendors')
    <script>
        // function updateSelectedModelIndex(type, selectElement) {
        //     const models = @json($models); // Get models from the server side
        //     const selectedIndex = selectElement.selectedIndex - 1; // Adjust for the placeholder option
        //     const selectedModel = models[selectedIndex]; // Get the selected model
        //     const additionalDiv = document.getElementById(type + '_additional');

        //     // Hide all additional divs first
        //     const checkboxes = additionalDiv.querySelectorAll('.additional-checkboxes');
        //     checkboxes.forEach(checkbox => checkbox.style.display = 'none');

        //     if (selectedModel) {
        //         // Show the additional properties corresponding to the selected model
        //         const additionalCheckboxes = additionalDiv.querySelector(`[data-model="${selectedModel['className']}"]`);

        //         if (additionalCheckboxes) {
        //             additionalCheckboxes.style.display = 'block';
        //             additionalDiv.style.display = 'block'; // Show the additional section
        //         }

        //         // You can now access selectedModel.additional or other properties as needed
        //         console.log('additionalCheckboxes', additionalCheckboxes);
        //         console.log('Selected Model Index:', selectedIndex);
        //         console.log('Selected Model:', selectedModel);
        //     } else {
        //         additionalDiv.style.display = 'none'; // Hide if no selection
        //     }
        // }
        function updateSelectedModelIndex(type, selectElement) {
            const models = @json($models); // Get models from the server side
            const selectedIndex = selectElement.selectedIndex - 1; // Adjust for the placeholder option
            const selectedModel = models[selectedIndex]; // Get the selected model
            const additionalDiv = document.getElementById(type + '_additional');

            // Hide all additional divs first
            const checkboxes = additionalDiv.querySelectorAll('.additional-checkboxes');
            checkboxes.forEach(checkbox => checkbox.style.display = 'none');

            console.log('Selected Model:', selectedModel); // Check the selected model's className

            if (selectedModel) {
                // Show the additional properties corresponding to the selected model
                const additionalCheckboxes = additionalDiv.querySelector(`[data-model="${selectedModel['className']}"]`);

                console.log('Looking for:', `[data-model="${selectedModel['className']}"]`); // Debugging
                console.log('Additional Checkboxes:', additionalCheckboxes); // Check if it's found

                if (additionalCheckboxes) {
                    additionalCheckboxes.style.display = 'block';
                    additionalDiv.style.display = 'block'; // Show the additional section
                }

            } else {
                additionalDiv.style.display = 'none'; // Hide if no selection
            }
        }
    </script>
    <script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
@endpush

@push('page_scripts')
    <script src="{{ asset('assets/dashboard') }}/app-assets/js/scripts/pages/app-user-edit.min.js"></script>
@endpush
