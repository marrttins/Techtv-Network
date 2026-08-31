<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | TechTV Network</title>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}?v={{ file_exists(public_path('assets/css/app.css')) ? filemtime(public_path('assets/css/app.css')) : time() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body style="margin: 0; padding: 0; font-family: 'Inter', sans-serif; background: #f8fafc;">

    <div class="login-split-container">
        
        {{-- Left Side: Navy Blue Branding --}}
        <div class="login-left-brand">
            <div class="login-brand-header">
                <a href="{{ url('/') }}">
                    <img src="{{ isset($siteSettings['site_logo']) ? asset($siteSettings['site_logo']) : asset('assets/img/logo.jpg') }}" alt="TechTV Network Logo" style="height: 38px; width: auto;">
                </a>
                <span class="editorial-badge">Account Security</span>
            </div>

            <div class="login-brand-mid">
                <h2 style="font-family: 'Poppins', sans-serif; font-size: 1.1rem; color: #38bdf8; margin: 0; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 800;">Password Recovery</h2>
                <h1 class="login-brand-tagline">Secure 2-Factor OTP Email Verification.</h1>
                <p style="color: #94a3b8; font-size: 0.95rem; line-height: 1.6; margin-top: 1rem;">
                    Enter your registered email address. We'll send a 6-digit OTP code to verify your identity and help you reset your password.
                </p>
            </div>

            <div class="login-left-footer">
                <span>TechTV Security Service • Automated 2FA OTP</span>
            </div>
        </div>

        {{-- Right Side: Form --}}
        <div class="login-right-form">
            <div class="login-form-card">
                
                <a href="{{ url('/login') }}" class="login-back-link">
                    &larr; Back to Sign In
                </a>

                <h1 class="login-form-title">Forgot Password</h1>
                <p class="login-form-subtitle">Enter your email address below to receive an OTP verification code.</p>

                @if(session('success'))
                    <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                        <ul style="margin: 0; padding-left: 1.25rem;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="login-form-body">
                    <form action="{{ url('/forgot-password') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
                        @csrf
                        
                        <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                            <label for="email" style="font-size: 0.8rem; font-weight: 700; color: var(--text); text-transform: uppercase;">Your Registered Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $prefilledEmail ?? '') }}" placeholder="editor@techtvnetwork.ng" required autofocus 
                                   style="padding: 0.85rem; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; font-size: 0.95rem;">
                        </div>

                        <button type="submit" 
                                style="padding: 0.85rem; border: none; border-radius: 8px; background: #0B193C; color: white; font-weight: 700; font-family: 'Poppins', sans-serif; font-size: 0.95rem; cursor: pointer; transition: background 0.2s; margin-top: 0.5rem;"
                                onmouseover="this.style.background='#1E293B';" onmouseout="this.style.background='#0B193C';">
                            Send OTP Code →
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>

</body>
</html>
