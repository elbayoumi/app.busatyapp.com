{{-- resources/views/unsubscribe/show.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Unsubscribe | Busaty</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body { font-family: system-ui, -apple-system, Arial; background:#f7f8fa; margin:0; }
    .card { max-width:560px; margin:6vh auto; background:#fff; border-radius:12px; padding:24px; box-shadow:0 10px 30px rgba(0,0,0,.06); }
    .btn { display:inline-block; background:#dc2626; color:#fff; padding:12px 18px; border-radius:8px; text-decoration:none; font-weight:700; }
    .muted { color:#6b7280; font-size:14px; }
    input, textarea { width:100%; padding:12px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; }
    label { font-weight:600; margin-bottom:6px; display:block; }
  </style>
</head>
<body>
  <div class="card">
    <h2 style="margin:0 0 8px;">Manage Email Subscription</h2>
    <p class="muted">Confirm your email address and optionally tell us why you're unsubscribing.</p>

    @if ($errors->any())
      <div style="background:#fef2f2; color:#991b1b; padding:12px; border-radius:8px; margin:10px 0;">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('unsubscribe.process') }}">
      @csrf
      <div style="margin:12px 0;">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $email ?? '') }}" required>
      </div>
      <div style="margin:12px 0;">
        <label for="reason">Reason (optional)</label>
        <textarea id="reason" name="reason" rows="3" placeholder="Too many emails, not relevant, etc.">{{ old('reason') }}</textarea>
      </div>
      <input type="hidden" name="token" value="{{ $token ?? '' }}">
      <button class="btn" type="submit">Unsubscribe</button>
    </form>

    <p class="muted" style="margin-top:14px;">
      Prefer fewer emails instead? <a href="{{ route('notifications.preferences.show', ['email' => $email ?? '']) }}">Update preferences</a>.
    </p>
  </div>
</body>
</html>
