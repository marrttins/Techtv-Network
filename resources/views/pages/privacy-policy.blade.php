@extends('layouts.layout')

@section('title', 'Privacy Policy | TechTV Network')
@section('meta_description', 'TechTV Network Privacy Policy detailing our data protection, cookie usage, Google AdSense compliance, and user rights under NDPR and GDPR.')

@section('content')
<div class="page-header" style="background: linear-gradient(135deg, #0B193C 0%, #1e293b 100%); padding: 3.5rem 0; color: #ffffff; margin-bottom: 2.5rem; text-align: center;">
    <div class="container">
        <h1 style="font-family: 'Outfit', sans-serif; font-size: 2.5rem; font-weight: 800; margin-bottom: 0.75rem; color: #ffffff;">Privacy Policy</h1>
        <p style="color: #94a3b8; font-size: 1rem; max-width: 650px; margin: 0 auto;">
            Last Updated: {{ date('F d, Y') }} • Committed to transparent data practices and your privacy.
        </p>
    </div>
</div>

<div class="container" style="max-width: 860px; margin-bottom: 4rem; line-height: 1.8; color: var(--text-main, #334155);">
    
    <div class="policy-content" style="background: var(--surface, #ffffff); border: 1px solid var(--border, #e2e8f0); border-radius: 12px; padding: 2.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">

        <section style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">1. Introduction & Overview</h2>
            <p>
                Welcome to <strong>TechTV Network</strong> ("TechTV", "we", "our", or "us"). TechTV Network operates the website located at <a href="{{ url('/') }}" style="color: var(--accent, #e02020); text-decoration: underline;">{{ url('/') }}</a>. We provide technology news, executive interviews, business analysis, and multimedia intelligence across Africa’s digital economy.
            </p>
            <p>
                This Privacy Policy explains how we collect, use, disclose, and safeguard your personal information when you visit our website, read our articles, subscribe to newsletters, or interact with our services. We comply with the <strong>Nigeria Data Protection Regulation (NDPR)</strong>, the <strong>General Data Protection Regulation (GDPR)</strong>, and international privacy standards.
            </p>
        </section>

        <section style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">2. Information We Collect</h2>
            <p>We may collect information about you in a variety of ways:</p>
            <ul style="padding-left: 1.5rem; margin-bottom: 1rem;">
                <li style="margin-bottom: 0.5rem;"><strong>Personal Data You Provide:</strong> When you subscribe to our newsletter, leave comments, contact our editorial team, or register for events (such as Titans of Tech Conference & Expo), you may provide your name, email address, company name, and phone number.</li>
                <li style="margin-bottom: 0.5rem;"><strong>Automatically Collected Data:</strong> When you access our platform, our servers automatically log standard information including your IP address, browser type, operating system, referring URLs, device identifiers, and pages viewed.</li>
                <li style="margin-bottom: 0.5rem;"><strong>Cookies & Tracking Technologies:</strong> We use cookies, log files, and web beacons to enhance your user experience, analyze traffic patterns, and deliver relevant advertisements.</li>
            </ul>
        </section>

        <section style="margin-bottom: 2rem; background: #f8fafc; border-left: 4px solid var(--accent, #e02020); padding: 1.25rem 1.5rem; border-radius: 0 8px 8px 0;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.4rem; font-weight: 700; color: #0B193C; margin-bottom: 0.5rem;">3. Google AdSense & Third-Party Advertising Disclosures</h2>
            <p>
                TechTV Network partners with third-party advertising companies, including <strong>Google AdSense</strong>, to serve advertisements when you visit our website.
            </p>
            <ul style="padding-left: 1.5rem; margin-bottom: 0.5rem;">
                <li style="margin-bottom: 0.5rem;">
                    <strong>DoubleClick DART Cookie:</strong> Google, as a third-party vendor, uses cookies to serve ads on TechTV Network. Google's use of the DART cookie enables it to serve ads to our users based on their visit to our site and other sites on the Internet.
                </li>
                <li style="margin-bottom: 0.5rem;">
                    <strong>Opt-Out of DART Cookie:</strong> Users may opt out of the use of the DART cookie by visiting the <a href="https://policies.google.com/technologies/ads" target="_blank" rel="noopener noreferrer" style="color: var(--accent, #e02020); font-weight: 600; text-decoration: underline;">Google Ad and Content Network Privacy Policy</a>.
                </li>
                <li style="margin-bottom: 0.5rem;">
                    <strong>Personalized Advertising Controls:</strong> You can manage or disable personalized advertising by visiting <a href="https://adssettings.google.com/" target="_blank" rel="noopener noreferrer" style="color: var(--accent, #e02020); font-weight: 600; text-decoration: underline;">Google Ads Settings</a> or through the <a href="https://www.aboutads.info/choices/" target="_blank" rel="noopener noreferrer" style="color: var(--accent, #e02020); font-weight: 600; text-decoration: underline;">Network Advertising Initiative Opt-Out Page</a>.
                </li>
            </ul>
        </section>

        <section style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">4. How We Use Your Information</h2>
            <p>We use the information we collect to:</p>
            <ul style="padding-left: 1.5rem; margin-bottom: 1rem;">
                <li>Deliver trusted news, technology analyses, editorial content, and live streams.</li>
                <li>Send curated daily/weekly newsletters (which you can unsubscribe from at any time).</li>
                <li>Moderate user comments and combat spam.</li>
                <li>Analyze website performance, traffic trends, and reader engagement.</li>
                <li>Serve customized, non-intrusive advertisements in compliance with industry standards.</li>
                <li>Comply with applicable legal obligations and prevent fraud.</li>
            </ul>
        </section>

        <section style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">5. Data Protection Rights (NDPR, GDPR & CCPA)</h2>
            <p>Depending on your location, you hold the following rights regarding your personal data:</p>
            <ul style="padding-left: 1.5rem; margin-bottom: 1rem;">
                <li><strong>Right of Access:</strong> You have the right to request copies of your personal data.</li>
                <li><strong>Right to Rectification:</strong> You have the right to request that we correct any inaccurate or incomplete information.</li>
                <li><strong>Right to Erasure (Right to be Forgotten):</strong> You have the right to request that we delete your personal data under certain conditions.</li>
                <li><strong>Right to Restrict or Object to Processing:</strong> You have the right to object to our processing of your personal data or direct marketing.</li>
            </ul>
            <p>
                To exercise any of these rights, please contact our Data Protection Officer at <a href="mailto:{{ $siteSettings['site_email'] ?? 'info@techtv.com.ng' }}" style="color: var(--accent, #e02020); font-weight: 600;">{{ $siteSettings['site_email'] ?? 'info@techtv.com.ng' }}</a>.
            </p>
        </section>

        <section style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">6. Third-Party Links & Social Media</h2>
            <p>
                Our platform contains links to external websites, YouTube videos, and social media platforms. TechTV Network is not responsible for the privacy practices or content of third-party websites. We encourage you to review their privacy policies before providing personal data.
            </p>
        </section>

        <section style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">7. Contact Information</h2>
            <p>
                If you have questions, comments, or concerns about this Privacy Policy or our data practices, please contact us at:
            </p>
            <div style="background: #f8fafc; border: 1px solid var(--border, #e2e8f0); border-radius: 8px; padding: 1.25rem;">
                <p style="margin: 0 0 0.35rem 0;"><strong>TechTV Network</strong></p>
                <p style="margin: 0 0 0.35rem 0;">Email: <a href="mailto:{{ $siteSettings['site_email'] ?? 'info@techtv.com.ng' }}" style="color: var(--accent, #e02020);">{{ $siteSettings['site_email'] ?? 'info@techtv.com.ng' }}</a></p>
                <p style="margin: 0 0 0.35rem 0;">Website: <a href="{{ url('/') }}" style="color: var(--accent, #e02020);">{{ url('/') }}</a></p>
                <p style="margin: 0;">Lagos, Nigeria</p>
            </div>
        </section>

    </div>
</div>
@endsection
