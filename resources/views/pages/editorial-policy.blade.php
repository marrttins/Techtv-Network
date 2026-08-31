@extends('layouts.layout')

@section('title', 'Editorial Policy & Fact-Checking | TechTV Network')
@section('meta_description', 'TechTV Network Editorial Guidelines: Our commitment to journalistic integrity, fact-checking, corrections, source verification, and sponsored content transparency.')

@section('content')
<div class="page-header" style="background: linear-gradient(135deg, #0B193C 0%, #1e293b 100%); padding: 3.5rem 0; color: #ffffff; margin-bottom: 2.5rem; text-align: center;">
    <div class="container">
        <h1 style="font-family: 'Outfit', sans-serif; font-size: 2.5rem; font-weight: 800; margin-bottom: 0.75rem; color: #ffffff;">Editorial Policy & Standards</h1>
        <p style="color: #94a3b8; font-size: 1rem; max-width: 650px; margin: 0 auto;">
            Our commitment to truth, accuracy, independence, and accountability in technology and business reporting.
        </p>
    </div>
</div>

<div class="container" style="max-width: 860px; margin-bottom: 4rem; line-height: 1.8; color: var(--text-main, #334155);">
    <div class="policy-content" style="background: var(--surface, #ffffff); border: 1px solid var(--border, #e2e8f0); border-radius: 12px; padding: 2.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">

        <section style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">1. Journalistic Mission & Integrity</h2>
            <p>
                <strong>TechTV Network</strong> is dedicated to delivering independent, credible, and insightful journalism covering technology, enterprise innovation, telecommunications, startups, government policy, and Africa's emerging digital economy. We adhere strictly to core journalistic principles: accuracy, fairness, impartiality, and public interest.
            </p>
        </section>

        <section style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">2. Fact-Checking & Source Verification</h2>
            <p>
                Every story published by TechTV Network undergoes rigorous verification:
            </p>
            <ul style="padding-left: 1.5rem; margin-bottom: 1rem;">
                <li><strong>Direct Sourcing:</strong> Whenever possible, claims, market figures, and quotes are verified directly with primary sources, official government gazettes, regulatory disclosures (NCC, NITDA, CBN, SEC), or authorized executive spokespersons.</li>
                <li><strong>Multiple Confirmation:</strong> Unattributed or anonymous tips require corroboration from independent secondary sources before publication.</li>
                <li><strong>Attribution:</strong> Statements and data from external research firms, think tanks, or peer publications are clearly attributed and linked.</li>
            </ul>
        </section>

        <section style="margin-bottom: 2rem; background: #f8fafc; border-left: 4px solid #3b82f6; padding: 1.25rem 1.5rem; border-radius: 0 8px 8px 0;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.4rem; font-weight: 700; color: #0B193C; margin-bottom: 0.5rem;">3. Corrections & Retractions Policy</h2>
            <p>
                We strive for 100% accuracy. When an error of fact occurs, TechTV Network promptly updates the article and issues an explicit editorial correction notice at the bottom of the piece explaining what was corrected and when.
            </p>
            <p style="margin-bottom: 0;">
                If you believe a story contains an inaccuracy, please email our newsdesk at <a href="mailto:{{ $siteSettings['site_email'] ?? 'editorial@techtv.com.ng' }}" style="color: var(--accent, #e02020); font-weight: 600;">{{ $siteSettings['site_email'] ?? 'editorial@techtv.com.ng' }}</a> with the subject line <em>"Correction Request"</em> and link to the article.
            </p>
        </section>

        <section style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">4. Sponsored Content & Advertising Transparency</h2>
            <p>
                TechTV Network maintains a strict wall between editorial decision-making and commercial/advertising operations:
            </p>
            <ul style="padding-left: 1.5rem; margin-bottom: 1rem;">
                <li><strong>Clear Demarcation:</strong> Paid articles, brand features, press releases, or commercial partner content are prominently labeled as <em>"Sponsored"</em>, <em>"Partner Content"</em>, or <em>"Advertisement"</em>.</li>
                <li><strong>No Pay-for-Play:</strong> Advertisers and sponsors have zero editorial control or preview rights over independent news reporting or analysis.</li>
            </ul>
        </section>

        <section style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">5. Conflict of Interest & Ethics</h2>
            <p>
                Our editors and journalists must disclose any financial interests, shareholdings, or personal ties with companies they cover. They are prohibited from accepting payments, favors, or valuable gifts intended to influence editorial slant.
            </p>
        </section>

        <section>
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0B193C; margin-bottom: 0.75rem;">6. Contact the Editorial Board</h2>
            <p>
                For news tips, executive interview requests, or press releases:
            </p>
            <p>
                Email: <a href="mailto:{{ $siteSettings['site_email'] ?? 'news@techtv.com.ng' }}" style="color: var(--accent, #e02020); font-weight: 600;">{{ $siteSettings['site_email'] ?? 'news@techtv.com.ng' }}</a><br>
                TechTV Network Editorial Headquarters, Lagos, Nigeria.
            </p>
        </section>

    </div>
</div>
@endsection
