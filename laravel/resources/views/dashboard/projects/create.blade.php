@extends('dashboard.layouts.app')

@section('content')
<div class="container">
    <h1>إضافة مشروع جديد</h1>

    <form action="{{ route('dashboard.projects.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">اسم المشروع:</label>
            <div class="input-group">
                <input type="text" id="name" name="name" required class="form-control">
                <div class="input-group-append">
                    <button type="button" class="btn btn-secondary" onclick="fillName()">عشوائي</button>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="description">وصف المشروع:</label>
            <div class="input-group">
                <textarea id="description" name="description" required class="form-control"></textarea>
                <div class="input-group-append">
                    <button type="button" class="btn btn-secondary" onclick="fillDescription()">عشوائي</button>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="username">اسم المستخدم:</label>
            <div class="input-group">
                <input type="text" id="username" name="username" required class="form-control">
                <div class="input-group-append">
                    <button type="button" class="btn btn-secondary" onclick="fillUsername()">عشوائي</button>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="password">كلمة المرور:</label>
            <div class="input-group">
                <input type="password" id="password" name="password" required class="form-control" placeholder="كلمة المرور">
                <div class="input-group-append">
                    <button type="button" class="btn btn-secondary" onclick="fillPassword()">عشوائي</button>
                </div>
            </div>
            <div class="form-check">
                <input type="checkbox" id="show-password" class="form-check-input" onclick="togglePasswordVisibility()">
                <label for="show-password" class="form-check-label">إظهار كلمة المرور</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">إنشاء مشروع جديد</button>
    </form>
</div>

<script>
function fillName() {
    document.getElementById('name').value = 'project_' + Math.floor(Math.random() * 1000);
}

function fillDescription() {
    document.getElementById('description').value = 'description_' + Math.floor(Math.random() * 100);
}

function generateRandomString(length) {
    const charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';
    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * charset.length);
        result += charset[randomIndex];
    }
    return result;
}

function fillClientId() {
    const randomClientId = generateRandomString(20); // توليد Client ID مكون من 10 خانات
    document.getElementById('client_id').value = randomClientId;
}

function fillUsername() {
    document.getElementById('username').value = 'user_' +generateRandomUsername(6);
}

function generateRandomPassword(length) {
    const charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+[]{}|;:,.<>?';
    let password = '';
    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * charset.length);
        password += charset[randomIndex];
    }
    return password;
}
function generateRandomUsername(length) {
    const charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let password = '';
    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * charset.length);
        password += charset[randomIndex];
    }
    return password;
}
function fillPassword() {
    const randomPassword = generateRandomPassword(25); // توليد كلمة مرور 10 خانات
    document.getElementById('password').value = randomPassword;

    // إظهار كلمة المرور بعد ملئها
    document.getElementById('show-password').checked = true; // تفعيل الـ Checkbox
    togglePasswordVisibility(); // تغيير النوع إلى 'text'
}

function togglePasswordVisibility() {
    const passwordField = document.getElementById('password');
    const showPasswordCheckbox = document.getElementById('show-password');
    passwordField.type = showPasswordCheckbox.checked ? 'text' : 'password';
}
</script>
@endsection
