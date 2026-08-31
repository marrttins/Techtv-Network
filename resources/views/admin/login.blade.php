<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | TechTV Network</title>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}?v={{ file_exists(public_path('assets/css/app.css')) ? filemtime(public_path('assets/css/app.css')) : time() }}">
</head>
<body style="margin: 0; padding: 0; font-family: 'Inter', sans-serif;">

    <div class="login-split-container">
        
        {{-- Left Side: Navy Blue Branding & Stats Panel --}}
        <div class="login-left-brand">
            <div class="login-brand-header">
                <a href="{{ url('/') }}">
                    <img src="{{ isset($siteSettings['site_logo']) ? asset($siteSettings['site_logo']) : asset('assets/img/logo.jpg') }}" alt="TechTV Network Logo" style="height: 38px; width: auto;">
                </a>
                <span class="editorial-badge">Editorial Portal</span>
            </div>

            <div class="login-brand-mid">
                <h2 style="font-family: 'Poppins', sans-serif; font-size: 1.1rem; color: var(--accent); margin: 0; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 800;">TechTV CMS</h2>
                <h1 class="login-brand-tagline">Empowering Africa's Digital Frontier with Insights.</h1>
                
                {{-- Dynamic database stats --}}
                <div class="login-stats-grid">
                    <div>
                        <div class="login-stat-number">{{ number_format(\App\Models\Post::count()) }}</div>
                        <div class="login-stat-label">Published Articles</div>
                    </div>
                    <div>
                        <div class="login-stat-number">{{ number_format(\App\Models\Category::count()) }}</div>
                        <div class="login-stat-label">News Categories</div>
                    </div>
                    <div>
                        <div class="login-stat-number">{{ number_format(\App\Models\User::count()) }}</div>
                        <div class="login-stat-label">CMS Authors</div>
                    </div>
                </div>
            </div>

            <div class="login-left-footer">
                <span>CMS Version 2.4.0 • Built for TechTV Network</span>
            </div>
        </div>

        {{-- Right Side: Sign-In Panel --}}
        <div class="login-right-form">
            <div class="login-form-card">
                
                {{-- Return Link --}}
                <a href="{{ url('/') }}" class="login-back-link">
                    &larr; Return to TechTV Network
                </a>

                <h1 class="login-form-title">Sign in to TechTV</h1>
                <p class="login-form-subtitle">Enter your editorial login credentials below.</p>

                {{-- Success Notification --}}
                @if(session('success'))
                    <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 0.9rem 1.15rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
                        <span>✓</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                {{-- Forced Reset Alert --}}
                @if(session('forced_forgot_password'))
                    <div style="background: #fee2e2; border: 1px solid #f87171; color: #991b1b; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                        <div style="font-weight: 700; display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.35rem;">
                            <span>🔒</span>
                            <span>Account Locked</span>
                        </div>
                        <p style="margin: 0 0 0.75rem 0; line-height: 1.4;">
                            Your account is permanently locked due to failed attempts following cooldown. You must reset your password via Email OTP to regain access.
                        </p>
                        <a href="{{ url('/forgot-password?email=' . urlencode(session('locked_email', old('email')))) }}" 
                           style="display: inline-block; background: #991b1b; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 700; font-size: 0.85rem;">
                            Reset Password with OTP →
                        </a>
                    </div>
                @elseif(session('show_forgot_link'))
                    <div style="background: #fef3c7; border: 1px solid #fde047; color: #854d0e; padding: 0.85rem 1rem; border-radius: 8px; margin-bottom: 1.25rem; font-size: 0.88rem; display: flex; justify-content: space-between; align-items: center;">
                        <span>Need immediate access?</span>
                        <a href="{{ url('/forgot-password?email=' . urlencode(session('locked_email', old('email')))) }}" style="color: #0369a1; font-weight: 700; text-decoration: underline;">
                            Reset Password →
                        </a>
                    </div>
                @endif

                {{-- Form Card --}}
                <div class="login-form-body">
                    <form action="{{ url('/login') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
                        @csrf
                        
                        {{-- Email input --}}
                        <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                            <label for="email" style="font-size: 0.8rem; font-weight: 700; color: var(--text); text-transform: uppercase;">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="editor@techtvnetwork.ng" required autofocus 
                                   style="padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 0.9rem; transition: border-color 0.2s;"
                                   onfocus="this.style.borderColor='#0B193C';" onblur="this.style.borderColor='var(--border)';">
                            @error('email')
                                <span style="font-size: 0.78rem; color: #ef4444; font-weight: 600;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Password input --}}
                        <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <label for="password" style="font-size: 0.8rem; font-weight: 700; color: var(--text); text-transform: uppercase;">Password</label>
                                <a href="{{ url('/forgot-password') }}" style="color: #0284c7; font-size: 0.8rem; text-decoration: none; font-weight: 600;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                    Forgot Password?
                                </a>
                            </div>
                            <input type="password" id="password" name="password" placeholder="••••••••" required 
                                   style="padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 0.9rem; transition: border-color 0.2s;"
                                   onfocus="this.style.borderColor='#0B193C';" onblur="this.style.borderColor='var(--border)';">
                            @error('password')
                                <span style="font-size: 0.78rem; color: #ef4444; font-weight: 600;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" 
                                style="padding: 0.85rem; border: none; border-radius: 6px; background: #0B193C; color: white; font-weight: 700; font-family: 'Poppins', sans-serif; font-size: 0.95rem; cursor: pointer; transition: background 0.2s; margin-top: 0.5rem;"
                                onmouseover="this.style.background='#1E293B';" onmouseout="this.style.background='#0B193C';">
                            LOGIN
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>

</body>
</html>
