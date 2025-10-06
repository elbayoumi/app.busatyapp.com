@extends('dashboard.layouts.app')

@section('content')
<div class="container">
    <h1>تعديل مشروع</h1>

    <form action="{{ route('dashboard.projects.update', $project) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">اسم المشروع:</label>
            <input type="text" id="name" name="name" value="{{ $project->name }}" required class="form-control">
        </div>
        <div class="form-group">
            <label for="description">وصف المشروع:</label>
            <textarea id="description" name="description" required class="form-control">{{ $project->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="client_id">Client ID:</label>
            <input type="text" id="client_id" disabled name="client_id" value="{{ $project->client_id }}" required class="form-control">
        </div>
        <div class="form-group">
            <label for="username">اسم المستخدم:</label>
            <input type="text" id="username" name="username" value="{{ $project->username }}" required class="form-control">
        </div>
        <div class="form-group">
            <label for="password">كلمة المرور (اترك  إذا لم تكن هناك تغييرات):</label>
            <input type="password" id="password" name="password" value="{{ $project->password }}" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">تحديث المشروع</button>
    </form>
</div>
@endsection
