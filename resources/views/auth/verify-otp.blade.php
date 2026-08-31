<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP | TechTV Network</title>
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
                <span class="editorial-badge">OTP Verification</span>
            </div>

            <div class="login-brand-mid">
                <h2 style="font-family: 'Poppins', sans-serif; font-size: 1.1rem; color: #38bdf8; margin: 0; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 800;">Step 2 of 3</h2>
                <h1 class="login-brand-tagline">Check Your Email for the 6-Digit Code.</h1>
                <p style="color: #94a3b8; font-size: 0.95rem; line-height: 1.6; margin-top: 1rem;">
                    We've generated a secure single-use token and emailed it to your registered inbox. Enter the code to authorize your password reset.
                </p>
            </div>

            <div class="login-left-footer">
                <span>Code Expires in 15 Minutes</span>
            </div>
        </div>

        {{-- Right Side: OTP Input Form --}}
        <div class="login-right-form">
            <div class="login-form-card">
                
                <a href="{{ url('/forgot-password?email=' . urlencode($email)) }}" class="login-back-link">
                    &larr; Resend or Change Email
                </a>

                <h1 class="login-form-title">Enter OTP Code</h1>
                <p class="login-form-subtitle">Enter the 6-digit verification code sent to <strong>{{ $email }}</strong></p>

                @if(session('success'))
                    <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('dev_otp_code'))
                    <div style="background: #eff6ff; border: 1px solid #93c5fd; color: #1e40af; padding: 0.85rem 1rem; border-radius: 8px; margin-bottom: 1.25rem; font-size: 0.88rem; display: flex; align-items: center; justify-content: space-between;">
                        <span>🔧 Local Testing OTP: <strong style="font-family: monospace; font-size: 1.1rem; letter-spacing: 2px;">{{ session('dev_otp_code') }}</strong></span>
                        <button type="button" onclick="document.getElementById('otp_code').value='{{ session('dev_otp_code') }}'" style="background: #2563eb; color: white; border: none; padding: 0.25rem 0.65rem; border-radius: 4px; font-size: 0.75rem; cursor: pointer;">Fill</button>
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
                    <form action="{{ url('/verify-otp') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        
                        <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                            <label for="otp_code" style="font-size: 0.8rem; font-weight: 700; color: var(--text); text-transform: uppercase;">6-Digit OTP Code</label>
                            <input type="text" id="otp_code" name="otp_code" maxlength="6" pattern="\d{6}" placeholder="123456" required autofocus 
                                   style="padding: 0.85rem; border: 2px solid #0B193C; border-radius: 8px; outline: none; font-size: 1.5rem; text-align: center; letter-spacing: 8px; font-family: monospace; font-weight: 700;">
                        </div>

                        <button type="submit" 
                                style="padding: 0.85rem; border: none; border-radius: 8px; background: #0B193C; color: white; font-weight: 700; font-family: 'Poppins', sans-serif; font-size: 0.95rem; cursor: pointer; transition: background 0.2s; margin-top: 0.5rem;"
                                onmouseover="this.style.background='#1E293B';" onmouseout="this.style.background='#0B193C';">
                            Verify OTP & Continue →
                        </button>
                    </form>

                    <div style="text-align: center; margin-top: 1rem;">
                        <form action="{{ url('/forgot-password') }}" method="POST" style="margin: 0; display: inline;">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">
                            <button type="submit" style="background: none; border: none; color: #0284c7; font-size: 0.85rem; cursor: pointer; text-decoration: underline;">
                                Didn't receive code? Resend OTP
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>

</body>
</html>
