<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\EmailSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class UnsubscribeController extends Controller
{
    /** Normalize incoming email */
    protected function normalizeEmail(?string $email): ?string
    {
        return $email ? strtolower(trim($email)) : null;
    }

    /** Show unsubscribe confirmation page (no writes on GET) */
    public function show(Request $request)
    {
        $email = $this->normalizeEmail($request->query('email'));
        $token = $request->query('token');

        $subscription = $email
            ? EmailSubscription::where('email', $email)->first()
            : null;

        return view('unsubscribe.show', compact('subscription', 'email', 'token'));
    }

    /** Process unsubscribe (form submit) */
    public function process(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email:rfc'],
            'reason' => ['nullable', 'string', 'max:255'],
            'token'  => ['nullable', 'string', 'max:128'],
        ]);

        $email = $this->normalizeEmail($data['email']);

        $sub = EmailSubscription::firstOrCreate(['email' => $email], []);

        // Optional token check if your links include tokens
        if (!empty($data['token']) && $sub->token && !hash_equals($sub->token, $data['token'])) {
            return back()->withErrors(['email' => __('Invalid unsubscribe link.')])->withInput();
        }

        $sub->fill([
            'subscribed'       => false,
            'reason'           => $data['reason'] ?? null,
            'unsubscribed_at'  => now(),
            'ip'               => $request->ip(),
            'user_agent'       => (string) $request->userAgent(),
        ])->save();

        return view('unsubscribe.done', ['email' => $sub->email]);
    }

    /** One-click route from List-Unsubscribe header (signed URL only) */
    public function oneClick(Request $request)
    {
        if (! URL::hasValidSignature($request)) {
            abort(403, 'Invalid or expired link.');
        }

        $email = $this->normalizeEmail($request->query('email'));
        abort_unless($email, 400, 'Missing email.');

        $sub = EmailSubscription::firstOrCreate(['email' => $email], []);
        $sub->fill([
            'subscribed'       => false,
            'reason'           => 'one-click',
            'unsubscribed_at'  => now(),
            'ip'               => $request->ip(),
            'user_agent'       => (string) $request->userAgent(),
        ])->save();

        return view('unsubscribe.done', ['email' => $sub->email, 'oneClick' => true]);
    }

    /** Show granular preferences (no writes on GET) */
    public function preferencesShow(Request $request)
    {
        $email = $this->normalizeEmail($request->query('email'));
        abort_unless($email, 400, 'Missing email.');

        $sub = EmailSubscription::where('email', $email)->first();

        $prefs = $sub?->preferences ?? [
            'newsletters'      => true,
            'product_updates'  => true,
            'security_alerts'  => true, // usually forced ON
            'marketing'        => true,
        ];

        return view('unsubscribe.preferences', [
            'email'       => $email,
            'prefs'       => $prefs,
            'subscribed'  => (bool) ($sub?->subscribed ?? true),
        ]);
    }

    /** Update preferences */
    public function preferencesUpdate(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email:rfc'],
            'preferences' => ['required', 'array'],
            'preferences.newsletters'     => ['required', 'boolean'],
            'preferences.product_updates' => ['required', 'boolean'],
            'preferences.security_alerts' => ['required', 'boolean'],
            'preferences.marketing'       => ['required', 'boolean'],
        ]);

        $email = $this->normalizeEmail($data['email']);

        $sub = EmailSubscription::firstOrCreate(['email' => $email], []);

        // Usually keep security alerts ON regardless of input
        $prefs = [
            'newsletters'      => (bool) $data['preferences']['newsletters'],
            'product_updates'  => (bool) $data['preferences']['product_updates'],
            'security_alerts'  => true,
            'marketing'        => (bool) $data['preferences']['marketing'],
        ];

        $sub->preferences = $prefs;
        $sub->save();

        return redirect()
            ->route('email.preferences.show', ['email' => $sub->email])
            ->with('ok', __('Preferences updated.'));
    }
}
