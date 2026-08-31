@extends('layouts.layout')

@section('title', 'Terms of Service | TechTV Network')
@section('meta_description', 'Terms of Service and Conditions governing the use of TechTV Network media platform, content licensing, user contributions, and copyright.')

@section('content')
<div class="page-header" style="background: linear-gradient(135deg, #0B193C 0%, #1e293b 100%); padding: 3.5rem 0; color: #ffffff; margin-bottom: 2.5rem; text-align: center;">
    <div class="container">
        <h1 style="font-family: 'Outfit', sans-serif; font-size: 2.5rem; font-weight: 800; margin-bottom: 0.75rem; color: #ffffff;">Terms of Service</h1>
        <p style="color: #94a3b8; font-size: 1rem; max-width: 650px; margin: 0 auto;">
            Last Updated: {{ date('F d, Y') }} • Please read these terms carefully before using our platform.
        </p>
    </div>
</div>

<div class="container" style="max-width: 860px; margin-bottom: 4rem; line-height: 1.8; color: var(--text-main, #334155);">
    <div class="policy-content" style="background: var(--surface, #ffffff); border: 1px solid var(--border, #e2e8f0); border-radius: 12px; padding: 2.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">

        <section style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">1. Acceptance of Terms</h2>
            <p>
                By accessing or using <strong>TechTV Network</strong> (<a href="{{ url('/') }}" style="color: var(--accent, #e02020);">{{ url('/') }}</a>), you acknowledge that you have read, understood, and agree to be bound by these Terms of Service, along with our <a href="{{ url('/privacy-policy') }}" style="color: var(--accent, #e02020); text-decoration: underline;">Privacy Policy</a> and <a href="{{ url('/cookie-policy') }}" style="color: var(--accent, #e02020); text-decoration: underline;">Cookie Policy</a>. If you do not agree to these terms, please discontinue use of the platform immediately.
            </p>
        </section>

        <section style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">2. Intellectual Property Rights</h2>
            <p>
                All original articles, editorial analyses, images, multimedia broadcasts, logos, trademarks, and design elements published on TechTV Network are the intellectual property of TechTV Network and protected by applicable copyright, trademark, and intellectual property laws.
            </p>
            <ul style="padding-left: 1.5rem; margin-bottom: 1rem;">
                <li>You may share article excerpts and links provided you clearly credit <strong>TechTV Network</strong> with an active, do-follow hyperlink to the original article.</li>
                <li>Full syndication, automated scraping, or commercial republication of complete articles without express written authorization is strictly prohibited.</li>
            </ul>
        </section>

        <section style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">3. User Conduct & Comment Guidelines</h2>
            <p>
                When interacting with TechTV Network (including posting comments or submitting feedback), you agree not to post or transmit content that:
            </p>
            <ul style="padding-left: 1.5rem; margin-bottom: 1rem;">
                <li>Is unlawful, defamatory, abusive, threatening, racially offensive, or hateful.</li>
                <li>Contains spam, unsolicited advertising, affiliate promotions, or deceptive links.</li>
                <li>Infringes on third-party intellectual property or privacy rights.</li>
            </ul>
            <p>
                TechTV Network reserves the right, at its sole discretion, to review, edit, or delete user comments and block violators without prior notice.
            </p>
        </section>

        <section style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">4. Editorial Independence & Financial Disclaimer</h2>
            <p>
                TechTV Network delivers news, executive perspectives, and market insights for general informational and educational purposes only. Content on this site does not constitute financial, investment, legal, or professional advice. Readers should seek appropriate professional counsel before making business or investment decisions.
            </p>
        </section>

        <section style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">5. Limitation of Liability</h2>
            <p>
                To the fullest extent permitted by law, TechTV Network, its directors, editors, and contributors shall not be liable for any direct, indirect, incidental, or consequential damages resulting from your use of, or inability to use, this website or its content.
            </p>
        </section>

        <section style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">6. Modifications to Terms</h2>
            <p>
                We reserve the right to revise or replace these Terms of Service at any time. Your continued use of the website following any modifications signifies acceptance of the updated terms.
            </p>
        </section>

        <section>
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">7. Contact Us</h2>
            <p>
                For questions concerning these Terms of Service, please reach out via <a href="mailto:{{ $siteSettings['site_email'] ?? 'legal@techtv.com.ng' }}" style="color: var(--accent, #e02020); font-weight: 600;">{{ $siteSettings['site_email'] ?? 'legal@techtv.com.ng' }}</a>.
            </p>
        </section>

    </div>
</div>
@endsection
