@extends('layouts.layout')

@section('title', 'Contact Us | TechTV Network')

@section('content')

<div class="sp-wrap container" style="padding-top: 1.25rem; padding-bottom: 4rem;">
    <div class="sp-layout" style="grid-template-columns: 1fr; max-width: 800px; margin: 0 auto;">
        <main class="sp-main">
            <h1 class="sp-title">Contact Us</h1>
            
            <div class="sp-content">
                <p>We'd love to hear from you. Whether you have a news tip, a business inquiry, or just want to say hello, feel free to reach out.</p>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
                    <div style="background: var(--surface); padding: 2rem; border-radius: 8px; border: 1px solid var(--border);">
                        <h4 style="font-family: 'Outfit', sans-serif; margin-bottom: 0.5rem;">Editorial Team</h4>
                        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1rem;">For press releases, news tips, and editorial inquiries.</p>
                        <a href="mailto:editor@techtvnetwork.ng" style="color: var(--accent); font-weight: 500;">editor@techtvnetwork.ng</a>
                    </div>
                    
                    <div style="background: var(--surface); padding: 2rem; border-radius: 8px; border: 1px solid var(--border);">
                        <h4 style="font-family: 'Outfit', sans-serif; margin-bottom: 0.5rem;">General Inquiries</h4>
                        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1rem;">For partnerships, general questions, and support.</p>
                        <a href="mailto:info@techtvnetwork.ng" style="color: var(--accent); font-weight: 500;">info@techtvnetwork.ng</a>
                    </div>
                </div>

                <h3 style="margin-top: 3rem; font-family: 'Outfit', sans-serif; font-size: 1.5rem;">Send a Message</h3>
                <form action="#" method="POST" style="display: flex; flex-direction: column; gap: 1.5rem; margin-top: 1.5rem;">
                    @csrf
                    <div>
                        <label style="display: block; font-size: 0.9rem; margin-bottom: 0.5rem;">Full Name</label>
                        <input type="text" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 4px; background: var(--bg); color: var(--text);" placeholder="John Doe">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.9rem; margin-bottom: 0.5rem;">Email Address</label>
                        <input type="email" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 4px; background: var(--bg); color: var(--text);" placeholder="john@example.com">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.9rem; margin-bottom: 0.5rem;">Message</label>
                        <textarea rows="5" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 4px; background: var(--bg); color: var(--text);" placeholder="How can we help you?"></textarea>
                    </div>
                    <button type="button" onclick="alert('Message feature coming soon!')" style="padding: 1rem; background: var(--accent); color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">Send Message</button>
                </form>
            </div>
        </main>
    </div>
</div>

@endsection
