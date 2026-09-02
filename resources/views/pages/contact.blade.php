@extends('layouts.layout')

@section('title', 'Contact Us | TechTV Network')
@section('meta_description', 'Contact the TechTV Network editorial newsdesk, submit press releases, pitch executive interviews, or inquire about digital advertising.')

@section('content')
<div class="page-header" style="background: linear-gradient(135deg, #0B193C 0%, #1e293b 100%); padding: 3.5rem 0; color: #ffffff; margin-bottom: 2.5rem; text-align: center;">
    <div class="container">
        <h1 style="font-family: 'Outfit', sans-serif; font-size: 2.5rem; font-weight: 800; margin-bottom: 0.75rem; color: #ffffff;">Contact TechTV Network</h1>
        <p style="color: #cbd5e1; font-size: 1rem; max-width: 650px; margin: 0 auto; line-height: 1.6;">
            We welcome news tips, editorial submissions, executive interview requests, and brand partnership inquiries.
        </p>
    </div>
</div>

<div class="container" style="max-width: 1000px; margin-bottom: 4rem;">
    
    {{-- Success Alert --}}
    @if(session('success'))
        <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 1.15rem 1.5rem; border-radius: 10px; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.85rem; font-size: 0.95rem; box-shadow: 0 4px 12px rgba(22, 101, 52, 0.08);">
            <span style="font-size: 1.4rem; flex-shrink: 0;">✓</span>
            <div>
                <strong>Message Sent!</strong> {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- Validation Errors Alert --}}
    @if($errors->any())
        <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 1.15rem 1.5rem; border-radius: 10px; margin-bottom: 2rem; font-size: 0.92rem; box-shadow: 0 4px 12px rgba(153, 27, 27, 0.08);">
            <div style="font-weight: 700; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.5rem;">
                <span>⚠️</span>
                <span>Please correct the errors below before submitting:</span>
            </div>
            <ul style="margin: 0; padding-left: 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 1.15fr; gap: 2.5rem;" class="contact-grid-responsive">
        
        {{-- Contact Information Cards --}}
        <div>
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.6rem; font-weight: 800; color: #0B193C; margin-bottom: 1.25rem;">
                Get in Touch
            </h2>
            <p style="color: var(--text-muted); line-height: 1.7; margin-bottom: 1.75rem;">
                Have a breaking story, investigative lead, or tech intelligence report? Connect directly with our editorial and corporate desks:
            </p>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 10px; padding: 1.25rem; display: flex; gap: 1rem; align-items: flex-start; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                    <div style="background: rgba(224, 32, 32, 0.1); color: var(--accent, #e02020); width: 44px; height: 44px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                        ✉️
                    </div>
                    <div>
                        <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.05rem; margin: 0 0 0.25rem 0; font-weight: 700;">General Inquiries & Support</h3>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0 0 0.35rem 0;">Primary contact mailbox for all inquiries.</p>
                        <a href="mailto:{{ $contact_email ?? config('mail.from.address', 'info@techtvnetwork.ng') }}" style="color: var(--accent, #e02020); font-weight: 700; font-size: 0.92rem; text-decoration: none;">
                            {{ $contact_email ?? config('mail.from.address', 'info@techtvnetwork.ng') }}
                        </a>
                    </div>
                </div>

                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 10px; padding: 1.25rem; display: flex; gap: 1rem; align-items: flex-start; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                    <div style="background: rgba(11, 25, 60, 0.1); color: #0B193C; width: 44px; height: 44px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                        📢
                    </div>
                    <div>
                        <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.05rem; margin: 0 0 0.25rem 0; font-weight: 700;">Advertising & Partnerships</h3>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0 0 0.35rem 0;">Sponsored content, banner ads, and executive showcases.</p>
                        <a href="mailto:{{ $contact_email ?? config('mail.from.address', 'info@techtvnetwork.ng') }}?subject=Advertising%20Inquiry" style="color: var(--accent, #e02020); font-weight: 700; font-size: 0.92rem; text-decoration: none;">
                            {{ $contact_email ?? config('mail.from.address', 'info@techtvnetwork.ng') }}
                        </a>
                    </div>
                </div>

                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 10px; padding: 1.25rem; display: flex; gap: 1rem; align-items: flex-start; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                    <div style="background: rgba(16, 185, 129, 0.1); color: #10b981; width: 44px; height: 44px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
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
        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 2.25rem; box-shadow: 0 6px 20px rgba(0,0,0,0.04);">
            <div style="border-left: 3px solid #0B193C; padding-left: 0.75rem; margin-bottom: 1.5rem;">
                <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.4rem; font-weight: 800; color: #0B193C; margin: 0 0 0.25rem 0;">
                    Send Us a Message
                </h2>
                <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">
                    Messages are delivered directly to <strong>{{ $contact_email ?? config('mail.from.address', 'info@techtvnetwork.ng') }}</strong>.
                </p>
            </div>

            <form action="{{ url('/contact') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
                @csrf
                
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="input-field" placeholder="e.g. Adebayo Ogunlesi" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg); box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="input-field" placeholder="e.g. adebayo@example.com" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg); box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Subject / Department *</label>
                    <select name="subject" class="input-field" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg); box-sizing: border-box; font-weight: 500;">
                        <option value="Editorial / News Tip" {{ old('subject') === 'Editorial / News Tip' ? 'selected' : '' }}>Editorial / News Tip</option>
                        <option value="Press Release Submission" {{ old('subject') === 'Press Release Submission' ? 'selected' : '' }}>Press Release Submission</option>
                        <option value="Advertising & Sponsorship" {{ old('subject') === 'Advertising & Sponsorship' ? 'selected' : '' }}>Advertising & Sponsorship</option>
                        <option value="Titans of Tech (TOTCE/TOTA/ITEF)" {{ old('subject') === 'Titans of Tech (TOTCE/TOTA/ITEF)' ? 'selected' : '' }}>Titans of Tech (TOTCE/TOTA/ITEF)</option>
                        <option value="Executive Interview Request" {{ old('subject') === 'Executive Interview Request' ? 'selected' : '' }}>Executive Interview Request</option>
                        <option value="General Inquiry" {{ old('subject') === 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">Message Content *</label>
                    <textarea name="message" rows="5" required class="input-field" placeholder="Provide full details regarding your inquiry..." style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; background: var(--bg); box-sizing: border-box; resize: vertical;">{{ old('message') }}</textarea>
                </div>

                {{-- Anti-Bot Security Math Captcha Box --}}
                <div style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 1.15rem; margin-top: 0.25rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem; flex-wrap: wrap; gap: 0.5rem;">
                        <label style="font-size: 0.85rem; font-weight: 700; color: #1e293b; display: inline-flex; align-items: center; gap: 0.4rem; margin: 0;">
                            <span>🛡️ Security Verification (Anti-Bot Check)</span>
                        </label>
                        <span style="font-size: 0.78rem; color: #64748b; font-weight: 600;">Math Challenge</span>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                        <div style="background: #0B193C; color: #ffffff; font-weight: 800; font-size: 1.1rem; padding: 0.6rem 1.15rem; border-radius: 6px; letter-spacing: 1px; font-family: monospace; user-select: none;">
                            {{ $captcha_question ?? '5 + 3' }} = ?
                        </div>
                        <div style="flex: 1 1 140px;">
                            <input type="number" name="captcha_answer" required class="input-field" placeholder="Your Answer" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #94a3b8; border-radius: 6px; background: #ffffff; font-size: 1rem; font-weight: 700; box-sizing: border-box;">
                        </div>
                    </div>
                    <small style="color: #64748b; font-size: 0.78rem; margin-top: 0.45rem; display: block;">
                        Please solve the math problem above to confirm you are human before submitting.
                    </small>
                </div>

                <button type="submit" class="btn-submit" style="width: 100%; padding: 0.85rem; border-radius: 6px; font-weight: 700; cursor: pointer; font-size: 1rem; margin-top: 0.5rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <span>✉️</span>
                    <span>Send Message</span>
                </button>
            </form>
        </div>

    </div>
</div>

<style>
@media (max-width: 860px) {
    .contact-grid-responsive {
        grid-template-columns: 1fr !important;
        gap: 2rem !important;
    }
}
</style>
@endsection

