@component('mail::message')

{{-- PDMS LOGO --}}
<div style="text-align: center; margin-bottom: 25px;">
    <img
        src="{{ asset('images/pdms-logo.png') }}"
        alt="PDMS Logo"
        width="100"
        style="
            width: 100px;
            max-width: 100px;
            height: auto;
            display: inline-block;
        "
    >
</div>

# Hello!

You are receiving this email because we received a password reset request for your account.

@component('mail::button', [
    'url' => $url,
    'color' => 'success'
])
Reset Password
@endcomponent

This password reset link will expire in **60 minutes**.

If you did not request a password reset, no further action is required.

Thanks,  
**Personnel Unit**  
Department of Education - Leyte Division

---

If you're having trouble clicking the **"Reset Password"** button, copy and paste the URL below into your web browser:

<a href="{{ $url }}" style="
    color: #15803d;
    word-break: break-all;
    text-decoration: underline;
">
    {{ $url }}
</a>

@endcomponent