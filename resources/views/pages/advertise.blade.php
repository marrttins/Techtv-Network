@extends('layouts.layout')

@section('title', 'Advertise | TechTV Network')

@section('content')

<div class="sp-wrap container" style="padding-top: 2rem; padding-bottom: 4rem;">
    <div class="sp-layout" style="grid-template-columns: 1fr; max-width: 900px; margin: 0 auto;">
        <main class="sp-main">
            <div style="margin-bottom: 2rem;">
                <span class="badge-pill-category" style="margin-bottom: 0.75rem; display: inline-block;">PARTNERSHIPS & ADVERTISING</span>
                <h1 class="sp-title" style="font-size: 2.25rem; font-weight: 800; line-height: 1.25; margin-bottom: 1rem;">Advertise With Us</h1>
                <p style="font-size: 1.05rem; color: #475569; line-height: 1.7;">
                    Reach a highly engaged audience of tech enthusiasts, business leaders, founders, and decision-makers across Africa and beyond by advertising with TechTV Network.
                </p>
            </div>
            
            <div class="sp-content">
                <!-- Why Advertise With Us -->
                <div style="background: #f8fafc; border: 1px solid var(--border); border-radius: 12px; padding: 1.75rem; margin-bottom: 2.5rem;">
                    <h3 style="font-family: 'Poppins', sans-serif; font-size: 1.35rem; font-weight: 700; color: var(--text); margin-top: 0; margin-bottom: 1rem;">
                        Why Advertise With Us?
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem;">
                        <div style="background: #ffffff; padding: 1.25rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <h4 style="font-size: 1rem; font-weight: 700; color: var(--accent); margin: 0 0 0.5rem 0;">Targeted Audience</h4>
                            <p style="font-size: 0.88rem; color: #64748b; margin: 0; line-height: 1.5;">Direct access to tech professionals, innovators, startup founders, and C-level executives.</p>
                        </div>
                        <div style="background: #ffffff; padding: 1.25rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <h4 style="font-size: 1rem; font-weight: 700; color: var(--accent); margin: 0 0 0.5rem 0;">Omnichannel Reach</h4>
                            <p style="font-size: 0.88rem; color: #64748b; margin: 0; line-height: 1.5;">Multi-platform visibility across our web portal, YouTube channel, daily newsletter, and social media.</p>
                        </div>
                        <div style="background: #ffffff; padding: 1.25rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <h4 style="font-size: 1rem; font-weight: 700; color: var(--accent); margin: 0 0 0.5rem 0;">Verified Analytics</h4>
                            <p style="font-size: 0.88rem; color: #64748b; margin: 0; line-height: 1.5;">Transparent impression and click tracking reports provided for every single placement.</p>
                        </div>
                    </div>
                </div>

                <!-- Ad Placements Section -->
                <div style="margin-bottom: 3rem;">
                    <div style="border-left: 4px solid var(--accent); padding-left: 1rem; margin-bottom: 1.5rem;">
                        <h2 style="font-family: 'Poppins', sans-serif; font-size: 1.6rem; font-weight: 800; color: var(--text); margin: 0;">
                            Ad Placements
                        </h2>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
                        <!-- Header Banner -->
                        <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                <h4 style="font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 700; color: #1e293b; margin: 0;">Header Banner</h4>
                                <span style="background: #fee2e2; color: #b91c1c; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.5rem; border-radius: 4px;">970×150</span>
                            </div>
                            <p style="font-size: 0.9rem; color: #64748b; line-height: 1.6; margin: 0;">
                                Exclusive, single-advertiser placement above the site header on every page.
                            </p>
                        </div>

                        <!-- Leaderboard -->
                        <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                <h4 style="font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 700; color: #1e293b; margin: 0;">Leaderboard</h4>
                                <span style="background: #e0f2fe; color: #0369a1; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.5rem; border-radius: 4px;">728×90</span>
                            </div>
                            <p style="font-size: 0.9rem; color: #64748b; line-height: 1.6; margin: 0;">
                                Full-width placement between content sections and category feeds.
                            </p>
                        </div>

                        <!-- Medium Rectangle -->
                        <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                <h4 style="font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 700; color: #1e293b; margin: 0;">Medium Rectangle</h4>
                                <span style="background: #fef3c7; color: #b45309; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.5rem; border-radius: 4px;">300×250</span>
                            </div>
                            <p style="font-size: 0.9rem; color: #64748b; line-height: 1.6; margin: 0;">
                                High-impact in-article and sidebar placement.
                            </p>
                        </div>

                        <!-- Half Page -->
                        <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                <h4 style="font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 700; color: #1e293b; margin: 0;">Half Page</h4>
                                <span style="background: #f3e8ff; color: #7e22ce; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.5rem; border-radius: 4px;">300×600</span>
                            </div>
                            <p style="font-size: 0.9rem; color: #64748b; line-height: 1.6; margin: 0;">
                                Premium high-visibility sidebar placement with maximum engagement.
                            </p>
                        </div>
                    </div>

                    <!-- Tracking Notice -->
                    <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 1rem 1.25rem; display: flex; align-items: center; gap: 0.75rem;">
                        <span style="font-size: 1.25rem;">📊</span>
                        <p style="font-size: 0.92rem; color: #1e40af; margin: 0; font-weight: 500;">
                            Every placement includes real impression and click tracking, reported back to you.
                        </p>
                    </div>
                </div>

                <!-- Get Started Section -->
                <div style="background: linear-gradient(135deg, #0B193C 0%, #1E293B 100%); color: #ffffff; border-radius: 12px; padding: 2.5rem 2rem; text-align: center; box-shadow: 0 10px 25px rgba(11, 25, 60, 0.2);">
                    <h3 style="font-family: 'Poppins', sans-serif; font-size: 1.75rem; font-weight: 800; margin: 0 0 1rem 0; color: #ffffff;">
                        Get Started
                    </h3>
                    <p style="font-size: 1rem; color: #cbd5e1; max-width: 600px; margin: 0 auto 1.75rem; line-height: 1.6;">
                        Reach out via our <a href="{{ url('/contact') }}" style="color: #f87171; text-decoration: underline; font-weight: 600;">Contact page</a> with your brand details and preferred placement, and our team will get back to you with rates and availability.
                    </p>
                    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                        <a href="{{ url('/contact') }}" class="btn-action" style="background: var(--accent); color: #ffffff; border: none; padding: 0.85rem 2rem; font-size: 0.95rem; font-weight: 700; border-radius: 9999px; text-decoration: none; display: inline-block;">
                            Contact Our Ads Team →
                        </a>
                        <a href="mailto:ads@techtvnetwork.ng" class="btn-action" style="background: rgba(255,255,255,0.1); color: #ffffff; border: 1px solid rgba(255,255,255,0.25); padding: 0.85rem 1.75rem; font-size: 0.95rem; font-weight: 600; border-radius: 9999px; text-decoration: none; display: inline-block;">
                            Email ads@techtvnetwork.ng
                        </a>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

@endsection
