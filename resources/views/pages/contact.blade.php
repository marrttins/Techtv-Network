@extends('layouts.layout')

@section('title', 'Contact Us | TechTV Network')
@section('meta_description', 'Contact the TechTV Network editorial newsdesk, submit press releases, pitch executive interviews, or inquire about digital advertising.')

@section('content')
<div class="page-header" style="background: linear-gradient(135deg, #0B193C 0%, #1e293b 100%); padding: 3.5rem 0; color: #ffffff; margin-bottom: 2.5rem; text-align: center;">
    <div class="container">
        <h1 style="font-family: 'Outfit', sans-serif; font-size: 2.5rem; font-weight: 800; margin-bottom: 0.75rem; color: #ffffff;">Contact TechTV Network</h1>
        <p style="color: #94a3b8; font-size: 1rem; max-width: 650px; margin: 0 auto;">
            We welcome news tips, editorial submissions, executive interview requests, and brand partnership inquiries.
        </p>
    </div>
</div>

<div class="container" style="max-width: 960px; margin-bottom: 4rem;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem;" class="contact-grid-responsive">
        
        {{-- Contact Information Cards --}}
        <div>
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.6rem; font-weight: 800; color: #0B193C; margin-bottom: 1.25rem;">
                Get in Touch
            </h2>
            <p style="color: var(--text-muted); line-height: 1.7; margin-bottom: 1.75rem;">
                Have a breaking story, investigative lead, or tech intelligence report? Connect directly with our desks:
            </p>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 8px; padding: 1.25rem; display: flex; gap: 1rem; align-items: flex-start;">
                    <div style="background: rgba(224, 32, 32, 0.1); color: var(--accent, #e02020); width: 42px; height: 42px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                        📰
                    </div>
                    <div>
                        <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.05rem; margin: 0 0 0.25rem 0; font-weight: 700;">Newsdesk & Press Releases</h3>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0 0 0.35rem 0;">For news releases, editorial pitches, and op-eds.</p>
                        <a href="mailto:{{ $siteSettings['site_email'] ?? 'news@techtv.com.ng' }}" style="color: var(--accent, #e02020); font-weight: 600; font-size: 0.9rem;">
                            {{ $siteSettings['site_email'] ?? 'news@techtv.com.ng' }}
                        </a>
                    </div>
                </div>

                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 8px; padding: 1.25rem; display: flex; gap: 1rem; align-items: flex-start;">
                    <div style="background: rgba(11, 25, 60, 0.1); color: #0B193C; width: 42px; height: 42px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                        📢
                    </div>
                    <div>
                        <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.05rem; margin: 0 0 0.25rem 0; font-weight: 700;">Advertising & Partnerships</h3>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0 0 0.35rem 0;">Sponsored content, banner ads, and event sponsorship.</p>
                        <a href="mailto:{{ $siteSettings['site_email'] ?? 'ads@techtv.com.ng' }}" style="color: var(--accent, #e02020); font-weight: 600; font-size: 0.9rem;">
                            {{ $siteSettings['site_email'] ?? 'ads@techtv.com.ng' }}
                        </a>
                    </div>
                </div>

                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 8px; padding: 1.25rem; display: flex; gap: 1rem; align-items: flex-start;">
                    <div style="background: rgba(16, 185, 129, 0.1); color: #10b981; width: 42px; height: 42px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                        📍
                    </div>
                    <div>
                        <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.05rem; margin: 0 0 0.25rem 0; font-weight: 700;">Editorial Headquarters</h3>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">
                            {{ $siteSettings['site_address'] ?? 'Lagos, Nigeria • Operating Across Pan-Africa' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Interactive Contact Form --}}
        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 2.25rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.4rem; font-weight: 800; color: #0B193C; margin-bottom: 0.5rem;">
                Send Us a Direct Message
            </h2>
            <p style="color: var(--text-muted); font-size: 0.88rem; margin-bottom: 1.5rem;">
                Fill in the form below and our editorial team will respond promptly.
            </p>

            <form id="contactForm" onsubmit="handleContactSubmit(event)" style="display: flex; flex-direction: column; gap: 1.25rem;">
                @csrf
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.35rem;">Full Name *</label>
                    <input type="text" required class="input-field" placeholder="e.g. Adebayo Ogunlesi" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg);">
                </div>

                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.35rem;">Email Address *</label>
                    <input type="email" required class="input-field" placeholder="e.g. adebayo@example.com" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg);">
                </div>

                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.35rem;">Subject / Department</label>
                    <select class="input-field" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg);">
                        <option value="editorial">Editorial / News Tip</option>
                        <option value="press">Press Release Submission</option>
                        <option value="advertising">Advertising & Sponsorship</option>
                        <option value="events">Titans of Tech (TOTCE/TOTA/ITEF)</option>
                        <option value="general">General Inquiries</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.35rem;">Message Content *</label>
                    <textarea rows="5" required class="input-field" placeholder="Provide full details regarding your inquiry..." style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg);"></textarea>
                </div>

                <button type="submit" class="btn-submit" style="width: 100%; padding: 0.85rem; border-radius: 6px; font-weight: 700; cursor: pointer;">
                    Send Message
                </button>
                <div id="contactFeedback" style="display: none; padding: 0.75rem; border-radius: 6px; font-size: 0.9rem; text-align: center;"></div>
            </form>
        </div>

    </div>
</div>

<script>
function handleContactSubmit(e) {
    e.preventDefault();
    const fb = document.getElementById('contactFeedback');
    fb.style.display = 'block';
    fb.style.background = '#dcfce7';
    fb.style.color = '#166534';
    fb.style.border = '1px solid #86efac';
    fb.innerHTML = '✓ Thank you! Your message has been received. Our editorial desk will be in touch shortly.';
    e.target.reset();
}
</script>

<style>
@media (max-width: 768px) {
    .contact-grid-responsive {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection

