<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="x-apple-disable-message-reformatting">
  <meta name="format-detection" content="telephone=no,address=no,email=no,date=no,url=no">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Confirm Your Email | Busaty</title>

  <!-- Some clients ignore <style>, بس بنسيب reset بسيط -->
  <style>
    /* Prevent Gmail iOS font size bump */
    body, table, td, a { -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; text-size-adjust:100%; }
    table, td { mso-table-lspace:0pt; mso-table-rspace:0pt; border-collapse:collapse !important; }
    img { -ms-interpolation-mode:bicubic; border:0; outline:none; text-decoration:none; height:auto; line-height:100%; display:block; }
    a[x-apple-data-detectors] { color: inherit !important; text-decoration: none !important; }
    /* Dark-mode hint (not all clients) */
    @media (prefers-color-scheme: dark) {
      .bg { background:#0b0f14 !important; }
      .card { background:#10161c !important; color:#e7eaf0 !important; }
      .muted { color:#9aa4b2 !important; }
      .divider { background:#1e2a35 !important; }
      .btn { background:#2c7be5 !important; color:#ffffff !important; }
      .link { color:#8ab4f8 !important; }
    }
  </style>
  <!--[if mso]>
    <style type="text/css">
      body, table, td, a { font-family: Arial, sans-serif !important; }
    </style>
  <![endif]-->
</head>

<body style="margin:0; padding:0; background-color:#f9f9f9; font-family: Arial, sans-serif;" class="bg">
  <!-- Hidden preheader: يظهر في Preview بتاع الإيميل -->
  <div style="display:none; max-height:0; overflow:hidden; mso-hide:all;">
    Verify your email to activate your Busaty account. Link and code expire in 1 hour.
  </div>

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td align="center" style="padding: 20px;">
        <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0"
               class="card"
               style="width:100%; max-width:600px; background-color:#ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
          <tr>
            <td align="center" style="padding:0 10px 10px;">
              <!-- Logo -->
              <img src="{{ asset('logo.png') }}" alt="Busaty Logo" width="120" style="margin-bottom: 16px;" />
              <h2 style="color: #111827; margin:0 0 6px; font-size:22px;">Welcome to <span style="color:#1E88E5;">Busaty</span>!</h2>
              <p class="muted" style="color:#6b7280; font-size:14px; line-height:1.6; margin:0;">
                Thanks for joining us! Please verify your email to activate your account.
              </p>
            </td>
          </tr>

          <tr><td style="padding:16px 0 0;"><div class="divider" style="height:1px; background:#eeeeee;"></div></td></tr>

          <tr>
            <td align="center" style="padding: 16px 10px 0;">
              <!-- Option 1: Activation Link -->
              <p style="font-size: 15px; color:#374151; margin:0 0 10px;">1) Click the button to confirm instantly:</p>
              <a href="{{ $mail_data['activation_link'] }}"
                 class="btn"
                 style="display:inline-block; background-color:#1E88E5; color:#ffffff; padding: 12px 24px; text-decoration: none; font-size: 16px; border-radius: 6px; font-weight:700; box-shadow: 0 3px 6px rgba(0,0,0,0.1);">
                 Verify Email
              </a>

              <!-- Option 2: Activation Code -->
              <p style="margin: 18px 0 8px; font-size: 15px; color:#374151;">
                2) Or enter this code in the app:
              </p>
              <p style="font-size: 26px; font-weight: 800; letter-spacing:3px; color: #1E88E5; margin:0;">
                {{ $mail_data['code'] }}
              </p>

              <!-- Validity Note -->
              <p style="color: #d32f2f; font-size: 13px; margin: 10px 0 0;">
                ⚠️ Link & code expire in 1 hour.
              </p>

              <!-- Divider -->
              <div class="divider" style="height:1px; background:#eeeeee; margin: 20px 0;"></div>

              <!-- Safety -->
              <p style="color: #6b7280; font-size: 13px; margin: 0;">
                Didn’t request this? You can safely ignore this email.
              </p>

              <!-- Footer -->
              <p class="muted" style="font-size: 12px; color: #9ca3af; margin: 16px 0 0; line-height:1.6;">
                © {{ date('Y') }} Busaty — All Rights Reserved<br />
                Need help? <a href="mailto:support@busaty.org" class="link" style="color:#1E88E5; text-decoration:none;">support@busaty.org</a>
              </p>

              <!-- Unsubscribe + Preferences -->
              <p style="font-size: 12px; margin-top: 10px;">
                If you no longer wish to receive emails from us, you can
                <a href="{{ URL::signedRoute('unsubscribe.oneclick', ['email' => $mail_data['email']]) }}"
                   class="link"
                   style="color: #e53935; text-decoration:none;">unsubscribe here</a>.
                &nbsp;|&nbsp;
                <a href="{{ route('notifications.preferences.show', ['email' => $mail_data['email']]) }}"
                   class="link"
                   style="color:#6b7280; text-decoration:none;">Manage preferences</a>
              </p>
            </td>
          </tr>
        </table>

        <!-- Optional tracking pixel -->
        @if(!empty($mail_data['tracking_pixel']))
          <img src="{{ $mail_data['tracking_pixel'] }}" width="1" height="1" style="display:block; border:0;" alt="">
        @endif
      </td>
    </tr>
  </table>
</body>
</html>
