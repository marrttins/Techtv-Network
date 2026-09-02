<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Inquiry</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f1f5f9; padding: 30px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0B193C 0%, #1e293b 100%); padding: 30px; text-align: center;">
                            <h1 style="color: #ffffff; font-size: 22px; font-weight: 800; margin: 0 0 6px 0; letter-spacing: -0.5px;">
                                TechTV Network
                            </h1>
                            <p style="color: #cbd5e1; font-size: 13px; margin: 0; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">
                                New Website Contact Message
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 30px 30px 20px 30px;">
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px; margin-bottom: 24px;">
                                <table width="100%" cellpadding="6" cellspacing="0" border="0" style="font-size: 14px;">
                                    <tr>
                                        <td width="30%" style="color: #64748b; font-weight: 600;">Sender Name:</td>
                                        <td style="color: #0f172a; font-weight: 700;">{{ $senderName }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #64748b; font-weight: 600;">Sender Email:</td>
                                        <td>
                                            <a href="mailto:{{ $senderEmail }}" style="color: #0284c7; text-decoration: none; font-weight: 600;">
                                                {{ $senderEmail }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="color: #64748b; font-weight: 600;">Subject / Dept:</td>
                                        <td style="color: #0f172a; font-weight: 600;">{{ $msgSubject }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #64748b; font-weight: 600;">Date & Time:</td>
                                        <td style="color: #64748b;">{{ now()->format('M d, Y - h:i A') }} (WAT)</td>
                                    </tr>
                                    @if(!empty($clientIp))
                                    <tr>
                                        <td style="color: #64748b; font-weight: 600;">IP Address:</td>
                                        <td style="color: #94a3b8; font-family: monospace; font-size: 12px;">{{ $clientIp }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>

                            <h2 style="font-size: 15px; font-weight: 700; color: #0B193C; margin: 0 0 10px 0; text-transform: uppercase; letter-spacing: 0.5px;">
                                Message Content:
                            </h2>

                            <div style="background: #ffffff; border-left: 4px solid #e02020; border-top: 1px solid #f1f5f9; border-right: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; padding: 18px; border-radius: 0 8px 8px 0; font-size: 15px; line-height: 1.7; color: #334155; white-space: pre-wrap; word-break: break-word;">{{ $msgBody }}</div>

                            <!-- Quick Reply Button -->
                            <div style="text-align: center; margin-top: 30px; margin-bottom: 10px;">
                                <a href="mailto:{{ $senderEmail }}?subject={{ urlencode('Re: ' . $msgSubject) }}" style="display: inline-block; background: #0B193C; color: #ffffff; text-decoration: none; font-weight: 700; font-size: 14px; padding: 12px 28px; border-radius: 6px;">
                                    ✉️ Reply to {{ $senderName }}
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px; text-align: center; font-size: 12px; color: #94a3b8;">
                            <p style="margin: 0 0 4px 0;">This email was sent from the contact form on <a href="{{ url('/') }}" style="color: #64748b; text-decoration: none; font-weight: 600;">TechTV Network</a>.</p>
                            <p style="margin: 0;">Recipient: <strong>{{ $adminEmail ?? config('mail.from.address', 'info@techtvnetwork.ng') }}</strong></p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
