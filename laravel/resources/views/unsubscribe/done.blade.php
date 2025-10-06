{{-- resources/views/unsubscribe/done.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Unsubscribed | Busaty</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body { font-family: system-ui, -apple-system, Arial; background:#f7f8fa; margin:0; }
    .card { max-width:560px; margin:10vh auto; background:#fff; border-radius:12px; padding:24px; text-align:center; box-shadow:0 10px 30px rgba(0,0,0,.06); }
    .muted { color:#6b7280; font-size:14px; }
    .btn { display:inline-block; background:#1E88E5; color:#fff; padding:10px 16px; border-radius:8px; text-decoration:none; font-weight:700; }
  </style>
</head>
<body>
  <div class="card">
    <h2 style="margin:0 0 8px;">You're unsubscribed</h2>
    <p class="muted">We’ve updated your preferences for {{ $email ?? 'your email' }}. You won’t receive marketing emails anymore.</p>
    <p class="muted">Want to fine-tune what you get? <a href="{{ route('notifications.preferences.show', ['email' => $email ?? '']) }}">Manage preferences</a>.</p>
    <a class="btn" href="{{ url('/') }}">Back to Busaty</a>
  </div>
</body>
</html>
