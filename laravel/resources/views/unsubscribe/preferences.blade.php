{{-- resources/views/unsubscribe/preferences.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Email Preferences | Busaty</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body { font-family: system-ui, -apple-system, Arial; background:#f7f8fa; margin:0; }
    .card { max-width:640px; margin:6vh auto; background:#fff; border-radius:12px; padding:24px; box-shadow:0 10px 30px rgba(0,0,0,.06); }
    .row { display:flex; gap:12px; align-items:center; margin:10px 0; }
    .muted { color:#6b7280; font-size:14px; }
    .btn { display:inline-block; background:#1E88E5; color:#fff; padding:10px 16px; border-radius:8px; text-decoration:none; font-weight:700; }
    input[type=checkbox] { width:18px; height:18px; }
  </style>
</head>
<body>
  <div class="card">
    <h2 style="margin:0 0 8px;">Email Preferences</h2>
    <p class="muted">Choose which emails you’d like to receive at <strong>{{ $email }}</strong>.</p>

    @if (session('ok'))
      <div style="background:#ecfdf5; color:#065f46; padding:12px; border-radius:8px; margin:10px 0;">
        {{ session('ok') }}
      </div>
    @endif

    <form method="POST" action="{{ route('notifications.preferences.update') }}">
      @csrf
      <input type="hidden" name="email" value="{{ $email }}">
      <div class="row"><input type="checkbox" name="preferences[newsletters]" value="1" {{ !empty($prefs['newsletters']) ? 'checked' : '' }}> <label>Newsletters</label></div>
      <div class="row"><input type="checkbox" name="preferences[product_updates]" value="1" {{ !empty($prefs['product_updates']) ? 'checked' : '' }}> <label>Product updates</label></div>
      <div class="row"><input type="checkbox" disabled checked> <label>Security alerts (always on)</label></div>
      <div class="row"><input type="checkbox" name="preferences[marketing]" value="1" {{ !empty($prefs['marketing']) ? 'checked' : '' }}> <label>Marketing</label></div>

      <p class="muted">You can unsubscribe from all marketing at any time.</p>
      <button class="btn" type="submit">Save preferences</button>
    </form>
  </div>
</body>
</html>
