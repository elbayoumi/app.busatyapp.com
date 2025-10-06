{{-- resources/views/emails/verification-code.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="x-apple-disable-message-reformatting">
  <meta name="format-detection" content="telephone=no,address=no,email=no,date=no,url=no">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirm your email | Busaty</title>
  <style>
    body,table,td,a{ -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; text-size-adjust:100%;}
    table,td{ mso-table-lspace:0pt; mso-table-rspace:0pt; border-collapse:collapse!important;}
    img{ -ms-interpolation-mode:bicubic; border:0; outline:0; text-decoration:none; height:auto; line-height:100%; display:block;}
    a[x-apple-data-detectors]{ color:inherit!important; text-decoration:none!important;}
  </style>
</head>
<body style="margin:0; padding:0; background:#f9f9f9; font-family:Arial, sans-serif;">
  <!-- Preheader (hidden) -->
  <div style="display:none; max-height:0; overflow:hidden; mso-hide:all;">
    Verify your email to activate your Busaty account. Link and code expire in 1 hour.
  </div>

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center" style="padding:20px;">
        <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background:#fff; border-radius:8px; padding:20px;">
          <tr>
            <td align="center" style="padding:0 10px 10px;">
              <img src="{{ asset('logo.png') }}" width="120" alt="Busaty" style="margin-bottom:16px;">
              <h2 style="margin:0 0 8px; color:#111827;">Welcome to <span style="color:#1E88E5;">Busaty</span>!</h2>
              <p style="margin:0; color:#6b7280; font-size:14px;">Please verify your email to activate your account.</p>
            </td>
          </tr>
          <tr><td style="height:1px; background:#eee; margin:16px 0;"></td></tr>
          <tr>
            <td align="center" style="padding:16px 10px 0;">
              <p style="margin:0 0 10px; color:#374151; font-size:15px;">1) Click the button to confirm instantly:</p>
              <a href="{{ $mail_data['activation_link'] }}"
                 style="display:inline-block; background:#1E88E5; color:#fff; padding:12px 24px; border-radius:6px; text-decoration:none; font-weight:700;">
                Verify Email
              </a>
              <p style="margin:18px 0 8px; color:#374151; font-size:15px;">2) Or use this code in the app:</p>
              <p style="margin:0; color:#1E88E5; font-weight:800; font-size:26px; letter-spacing:3px;">{{ $mail_data['code'] }}</p>
              <p style="margin:10px 0 0; color:#d32f2f; font-size:13px;">⚠️ Link & code expire in 1 hour.</p>
              <div style="height:1px; background:#eee; margin:20px 0;"></div>
              <p style="margin:0; color:#6b7280; font-size:13px;">Didn’t request this? You can safely ignore this email.</p>
              <p style="margin:16px 0 0; color:#9ca3af; font-size:12px; line-height:1.6;">
                © {{ date('Y') }} Busaty — All Rights Reserved ·
                <a href="mailto:support@busaty.org" style="color:#1E88E5; text-decoration:none;">support@busaty.org</a>
              </p>
              <p style="margin:10px 0 0; font-size:12px;">
                If you no longer wish to receive emails from us, you can
                <a href="{{ URL::signedRoute('unsubscribe.oneclick', ['email' => $mail_data['email']]) }}" style="color:#e53935; text-decoration:none;">unsubscribe here</a>.
              </p>
            </td>
          </tr>
        </table>
        @if(!empty($mail_data['tracking_pixel']))
          <img src="{{ $mail_data['tracking_pixel'] }}" width="1" height="1" style="display:block;" alt="">
        @endif
      </td>
    </tr>
  </table>
</body>
</html>
