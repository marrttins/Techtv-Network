@extends('layouts.layout')

@section('title', 'Cookie Policy | TechTV Network')
@section('meta_description', 'Learn how TechTV Network uses cookies, analytics, and advertising technologies like Google AdSense to optimize your experience.')

@section('content')
<div class="page-header" style="background: linear-gradient(135deg, #0B193C 0%, #1e293b 100%); padding: 3.5rem 0; color: #ffffff; margin-bottom: 2.5rem; text-align: center;">
    <div class="container">
        <h1 style="font-family: 'Outfit', sans-serif; font-size: 2.5rem; font-weight: 800; margin-bottom: 0.75rem; color: #ffffff;">Cookie Policy</h1>
        <p style="color: #94a3b8; font-size: 1rem; max-width: 650px; margin: 0 auto;">
            Last Updated: {{ date('F d, Y') }} • How we use cookies to deliver quality content and tailored advertising.
        </p>
    </div>
</div>

<div class="container" style="max-width: 860px; margin-bottom: 4rem; line-height: 1.8; color: var(--text-main, #334155);">
    <div class="policy-content" style="background: var(--surface, #ffffff); border: 1px solid var(--border, #e2e8f0); border-radius: 12px; padding: 2.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">

        <section style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">1. What Are Cookies?</h2>
            <p>
                Cookies are small text files placed on your device (computer, tablet, or smartphone) when you browse websites. Cookies help websites remember your preferences, keep you signed in, understand user behavior, and provide personalized content and advertisements.
            </p>
        </section>

        <section style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">2. Types of Cookies We Use</h2>
            
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <div style="border: 1px solid var(--border, #e2e8f0); border-radius: 8px; padding: 1.25rem; background: #fafafa;">
                    <h3 style="font-size: 1.15rem; font-weight: 700; color: #0B193C; margin-bottom: 0.35rem;">🔒 Essential / Necessary Cookies</h3>
                    <p style="margin: 0; font-size: 0.95rem;">
                        These cookies are vital for the proper operation of the website, such as security, CSRF protection, session management, and font rendering. They cannot be turned off.
                    </p>
                </div>

                <div style="border: 1px solid var(--border, #e2e8f0); border-radius: 8px; padding: 1.25rem; background: #fafafa;">
                    <h3 style="font-size: 1.15rem; font-weight: 700; color: #0B193C; margin-bottom: 0.35rem;">📊 Analytics & Performance Cookies</h3>
                    <p style="margin: 0; font-size: 0.95rem;">
                        We use analytics cookies to measure article pageviews, reader navigation flows, and server response times so we can continually optimize our publication quality.
                    </p>
                </div>

                <div style="border: 1px solid var(--border, #e2e8f0); border-radius: 8px; padding: 1.25rem; background: #fafafa;">
                    <h3 style="font-size: 1.15rem; font-weight: 700; color: #0B193C; margin-bottom: 0.35rem;">🎯 Advertising & Google AdSense Cookies</h3>
                    <p style="margin: 0; font-size: 0.95rem;">
                        These cookies are set by third-party advertising partners including <strong>Google AdSense</strong> and DoubleClick. They track your interests across websites to display relevant, high-quality ads and prevent the same advertisement from repeatedly showing to you.
                    </p>
                </div>
            </div>
        </section>

        <section style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">3. Google DoubleClick & AdSense Controls</h2>
            <p>
                Google uses cookies (such as DART cookies) to serve ads based on prior visits to our website or other websites. You can control your ad personalization settings at:
            </p>
            <p>
                👉 <a href="https://adssettings.google.com/" target="_blank" rel="noopener noreferrer" style="color: var(--accent, #e02020); font-weight: 600; text-decoration: underline;">Google Ad Settings (adssettings.google.com)</a>
            </p>
        </section>

        <section style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">4. Managing & Disabling Cookies in Your Browser</h2>
            <p>
                You can configure your web browser to block or alert you about cookies:
            </p>
            <ul style="padding-left: 1.5rem; margin-bottom: 1rem;">
                <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" rel="noopener" style="color: var(--accent, #e02020);">Google Chrome Cookie Settings</a></li>
                <li><a href="https://support.mozilla.org/en-US/kb/cookies-information-websites-store-on-your-computer" target="_blank" rel="noopener" style="color: var(--accent, #e02020);">Mozilla Firefox Cookie Settings</a></li>
                <li><a href="https://support.apple.com/guide/safari/manage-cookies-sfri11471/mac" target="_blank" rel="noopener" style="color: var(--accent, #e02020);">Apple Safari Cookie Settings</a></li>
                <li><a href="https://support.microsoft.com/en-us/microsoft-edge/delete-cookies-in-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" target="_blank" rel="noopener" style="color: var(--accent, #e02020);">Microsoft Edge Cookie Settings</a></li>
            </ul>
        </section>

        <section>
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">5. Contact Us</h2>
            <p>
                If you have questions about our Cookie Policy, please email us at <a href="mailto:{{ $siteSettings['site_email'] ?? 'privacy@techtv.com.ng' }}" style="color: var(--accent, #e02020); font-weight: 600;">{{ $siteSettings['site_email'] ?? 'privacy@techtv.com.ng' }}</a>.
            </p>
        </section>

    </div>
</div>
@endsection
