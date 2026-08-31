<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TechTV Password Reset OTP</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f1f5f9; margin: 0; padding: 40px 20px;">
    <div style="max-width: 520px; margin: 0 auto; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <div style="background: #0B193C; padding: 24px; text-align: center;">
            <h1 style="color: #ffffff; font-size: 20px; margin: 0; font-family: 'Poppins', sans-serif;">TechTV Network</h1>
            <span style="color: #94a3b8; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Security & Account Recovery</span>
        </div>
        <div style="padding: 32px 28px;">
            <h2 style="color: #1e293b; font-size: 18px; margin: 0 0 12px 0;">Hello {{ $user->name }},</h2>
            <p style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0 0 24px 0;">
                We received a request to reset the password for your TechTV Editorial account. Use the 6-digit verification code (OTP) below to verify your identity.
            </p>
            
            <div style="background: #f8fafc; border: 2px dashed #0B193C; border-radius: 8px; padding: 18px; text-align: center; margin-bottom: 24px;">
                <span style="font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 6px;">Your 6-Digit OTP Code</span>
                <span style="font-family: monospace; font-size: 32px; font-weight: 800; color: #0B193C; letter-spacing: 6px;">{{ $otp }}</span>
            </div>

            <p style="color: #64748b; font-size: 13px; line-height: 1.5; margin: 0 0 16px 0;">
                ⏱️ This code is valid for <strong>15 minutes</strong>. If you did not make this request, please ignore this email or contact the administrator immediately.
            </p>
        </div>
        <div style="background: #f8fafc; padding: 16px 28px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8;">
            &copy; {{ date('Y') }} TechTV Network. All rights reserved.
        </div>
    </div>
</body>
</html>
