{{-- resources/views/emails/verification-code_plain.blade.php --}}
Confirm your email | Busaty

Please verify your email to activate your account.

1) Verify instantly: {{ $mail_data['activation_link'] }}

2) Or use this code in the app: {{ $mail_data['code'] }}

Note: Link & code expire in 1 hour.

If you didn’t request this, ignore this email.

Unsubscribe: {{ URL::signedRoute('unsubscribe.oneclick', ['email' => $mail_data['email']]) }}
Support: support@busaty.org
