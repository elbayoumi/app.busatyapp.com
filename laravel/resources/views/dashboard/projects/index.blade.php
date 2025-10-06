@extends('dashboard.layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 text-center">المشاريع</h1>
    <div class="mb-4 text-center">
        <a href="{{ route('dashboard.projects.create') }}" class="btn btn-primary btn-lg">إضافة مشروع جديد</a>
        <a href="{{ route('dashboard.projects.json') }}" class="btn btn-info btn-lg" target="_blank">استخراج كـ JSON</a>
    </div>

    <div class="row">
        @foreach($projects as $project)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-light">
                <div class="card-body">
                    <h5 class="card-title">{{ $project->name }}</h5>
                    <p class="card-text">{{ $project->description }}</p>
                    <p class="card-text"><strong>Client ID:</strong> {{ $project->client_id }}</p>
                    <p class="card-text"><strong>اسم المستخدم:</strong> {{ $project->username }}</p>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dashboard.projects.edit', $project) }}" class="btn btn-warning">تعديل</a>
                        <a href="{{ route('dashboard.project.json', $project->id) }}" class="btn btn-success">تحميل</a>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{ $project->id }}">
                            حذف
                        </button>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteModal{{ $project->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $project->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel{{ $project->id }}">تأكيد الحذف</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            هل أنت متأكد أنك تريد حذف هذا المشروع: <strong>{{ $project->name }}</strong>؟
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                            <form action="{{ route('dashboard.projects.destroy', $project) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">نعم، احذف المشروع</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
