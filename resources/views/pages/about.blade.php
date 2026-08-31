@extends('layouts.layout')

@section('title', 'About Us | TechTV Network')
@section('meta_description', 'TechTV Network is a Nigerian-based African technology and business media platform delivering trusted news, original insights, executive interviews, and industry intelligence.')

@section('content')

<div class="sp-wrap container" style="padding-top: 2rem; padding-bottom: 4.5rem;">
    <div class="sp-layout" style="grid-template-columns: 1fr; max-width: 880px; margin: 0 auto;">
        <main class="sp-main">
            <!-- Header Section -->
            <div style="margin-bottom: 2rem; text-align: center;">
                <span class="badge-pill-category" style="margin-bottom: 0.85rem; display: inline-block; font-size: 0.8rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;">WHO WE ARE</span>
                <h1 class="sp-title" style="font-size: 2.5rem; font-weight: 800; line-height: 1.2; margin-bottom: 0.75rem; color: var(--text);">About TechTV Network</h1>
                <p style="font-size: 1.25rem; font-weight: 600; color: var(--accent); margin: 0 auto; max-width: 650px; line-height: 1.5;">
                    Africa’s Voice for Technology & Business Insight
                </p>
            </div>
            
            <div class="sp-content" style="line-height: 1.85; font-size: 1.08rem; color: var(--text);">
                <!-- Featured Image -->
                <div style="margin-bottom: 2.5rem; overflow: hidden; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
                    <img src="{{ asset('uploads/about_us_tech.png') }}" alt="TechTV Network Team and Vision" style="width: 100%; height: auto; max-height: 460px; object-fit: cover; display: block; transition: transform 0.3s ease;">
                </div>
                
                <!-- Main Intro Narrative -->
                <div style="margin-bottom: 2.75rem; display: flex; flex-direction: column; gap: 1.25rem;">
                    <p style="font-size: 1.15rem; color: #1e293b; font-weight: 500; line-height: 1.85;">
                        <strong>TechTV Network</strong> is a Nigerian-based African technology and business media platform delivering trusted news, original insights, executive interviews, thought leadership content, and industry intelligence across technology, innovation, entrepreneurship, and the digital economy.
                    </p>
                    
                    <p style="color: #475569;">
                        As a dynamic multimedia platform, TechTV Network connects innovators, business leaders, policymakers, investors, and technology enthusiasts through compelling storytelling, strategic conversations, and impactful content that informs, inspires, and drives transformation across Africa.
                    </p>
                    
                    <p style="color: #475569;">
                        Beyond media, TechTV Network drives industry engagement through its flagship platforms, including the Titans of Tech Conference & Expo (TOTCE), Titans of Tech Africa Awards (TOTA), and International Tech & Energy Fest (ITEF) bringing together leading voices, innovators, businesses, government institutions, investors, and development partners shaping Africa’s technology future.
                    </p>
                </div>

                <!-- Flagship Initiatives Section -->
                <div style="margin-top: 3.5rem; margin-bottom: 3.5rem;">
                    <div style="border-left: 4px solid var(--accent); padding-left: 1rem; margin-bottom: 1.75rem;">
                        <h2 style="font-family: 'Poppins', sans-serif; font-size: 1.75rem; font-weight: 800; color: var(--text); margin: 0;">
                            Our Flagship Initiatives
                        </h2>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <!-- TOTCE -->
                        <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 12px; padding: 1.75rem; box-shadow: 0 4px 12px rgba(0,0,0,0.03); transition: transform 0.2s, box-shadow 0.2s;">
                            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: baseline; gap: 0.5rem; margin-bottom: 0.75rem;">
                                <h3 style="font-family: 'Poppins', sans-serif; font-size: 1.25rem; font-weight: 700; color: #0f172a; margin: 0;">
                                    Titans of Tech Conference & Expo (TOTCE)
                                </h3>
                                <a href="https://www.totce.com.ng/" target="_blank" rel="noopener noreferrer" style="color: var(--accent); font-weight: 600; font-size: 0.95rem; text-decoration: underline;">
                                    www.totce.com.ng &rarr;
                                </a>
                            </div>
                            <p style="color: #475569; margin: 0; font-size: 1rem; line-height: 1.7;">
                                One of Africa’s premier technology gatherings, bringing together policymakers, business leaders, innovators, investors, academics, and technology professionals to explore emerging trends, foster collaboration, and advance digital transformation across the continent.
                            </p>
                        </div>

                        <!-- TOTA -->
                        <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 12px; padding: 1.75rem; box-shadow: 0 4px 12px rgba(0,0,0,0.03); transition: transform 0.2s, box-shadow 0.2s;">
                            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: baseline; gap: 0.5rem; margin-bottom: 0.75rem;">
                                <h3 style="font-family: 'Poppins', sans-serif; font-size: 1.25rem; font-weight: 700; color: #0f172a; margin: 0;">
                                    Titans of Tech Africa Awards (TOTA)
                                </h3>
                                <a href="https://www.titansoftechawards.com/" target="_blank" rel="noopener noreferrer" style="color: var(--accent); font-weight: 600; font-size: 0.95rem; text-decoration: underline;">
                                    www.titansoftechawards.com &rarr;
                                </a>
                            </div>
                            <p style="color: #475569; margin: 0; font-size: 1rem; line-height: 1.7;">
                                A prestigious recognition platform celebrating excellence, innovation, leadership, and outstanding contributions to Africa’s technology ecosystem and digital economy.
                            </p>
                        </div>

                        <!-- ITEF -->
                        <div style="background: #ffffff; border: 1px solid var(--border); border-radius: 12px; padding: 1.75rem; box-shadow: 0 4px 12px rgba(0,0,0,0.03); transition: transform 0.2s, box-shadow 0.2s;">
                            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: baseline; gap: 0.5rem; margin-bottom: 0.75rem;">
                                <h3 style="font-family: 'Poppins', sans-serif; font-size: 1.25rem; font-weight: 700; color: #0f172a; margin: 0;">
                                    International Tech & Energy Fest (ITEF)
                                </h3>
                                <a href="https://www.itef.com.ng/" target="_blank" rel="noopener noreferrer" style="color: var(--accent); font-weight: 600; font-size: 0.95rem; text-decoration: underline;">
                                    www.itef.com.ng &rarr;
                                </a>
                            </div>
                            <p style="color: #475569; margin: 0; font-size: 1rem; line-height: 1.7;">
                                A unique platform that converges stakeholders from the technology, energy, telecommunications, infrastructure, and sustainability sectors to explore innovative solutions, promote investment opportunities, and drive conversations around Africa’s energy transition and digital future.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Our Commitment Section -->
                <div style="margin-top: 3.5rem; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; border-radius: 16px; padding: 2.5rem; box-shadow: 0 12px 30px rgba(15,23,42,0.15);">
                    <div style="border-left: 4px solid var(--accent); padding-left: 1rem; margin-bottom: 1.5rem;">
                        <h2 style="font-family: 'Poppins', sans-serif; font-size: 1.75rem; font-weight: 800; color: #ffffff; margin: 0;">
                            Our Commitment
                        </h2>
                    </div>

                    <p style="color: #e2e8f0; font-size: 1.08rem; line-height: 1.8; margin-bottom: 1.25rem;">
                        At TechTV Network, we are committed to journalistic integrity, factual reporting, innovation-driven storytelling, and content that creates measurable value for our audience.
                    </p>

                    <p style="color: #cbd5e1; font-size: 1.08rem; line-height: 1.8; margin-bottom: 1.5rem;">
                        We believe technology is not merely an industry, it is the engine of Africa’s future prosperity.
                    </p>

                    <div style="border-top: 1px solid rgba(255,255,255,0.15); padding-top: 1.5rem; margin-top: 1.5rem;">
                        <p style="color: #94a3b8; font-size: 0.95rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            Every story we tell, every event we host, and every partnership we build is guided by one purpose:
                        </p>
                        <p style="font-family: 'Poppins', sans-serif; font-size: 1.5rem; font-weight: 800; color: var(--accent, #f97316); margin: 0;">
                            “Advancing Africa’s Digital Future.”
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@endsection
