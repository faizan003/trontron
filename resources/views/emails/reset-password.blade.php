<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your TronX Password</title>
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #374151;
            background-color: #f9fafb;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        /* Header with TronX branding */
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #9333ea 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.7; }
            50% { transform: scale(1.1); opacity: 0.3; }
        }
        
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: white;
            margin-bottom: 8px;
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .header-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            position: relative;
            z-index: 2;
        }
        
        /* Content section */
        .content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
        }
        
        .message {
            color: #6b7280;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 32px;
        }
        
        /* Security notice */
        .security-notice {
            background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
            border-left: 4px solid #f59e0b;
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 32px;
        }
        
        .security-notice .icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 8px;
            vertical-align: middle;
        }
        
        .security-notice .text {
            color: #92400e;
            font-size: 14px;
            font-weight: 500;
        }
        
        /* Reset button */
        .button-container {
            text-align: center;
            margin: 32px 0;
        }
        
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #2563eb 0%, #9333ea 100%);
            color: white;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .reset-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .reset-button:hover::before {
            left: 100%;
        }
        
        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.6);
        }
        
        /* Alternative link */
        .alternative-link {
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            margin: 24px 0;
            word-break: break-all;
        }
        
        .alternative-link-text {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 8px;
        }
        
        .alternative-link-url {
            color: #2563eb;
            font-size: 12px;
            text-decoration: none;
        }
        
        /* Footer */
        .footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        
        .footer-text {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .footer-links {
            margin-top: 16px;
        }
        
        .footer-links a {
            color: #2563eb;
            text-decoration: none;
            margin: 0 12px;
            font-size: 14px;
        }
        
        .footer-links a:hover {
            text-decoration: underline;
        }
        
        /* Responsive design */
        @media (max-width: 600px) {
            .email-container {
                margin: 0 16px;
                border-radius: 12px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .footer {
                padding: 20px;
            }
            
            .logo {
                font-size: 28px;
            }
            
            .greeting {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div style="background-color: #f9fafb; padding: 40px 20px;">
        <div class="email-container">
            <!-- Header -->
            <div class="header">
                <div class="logo">TronX</div>
                <div class="header-subtitle">Secure Staking Platform</div>
            </div>
            
            <!-- Content -->
            <div class="content">
                <div class="greeting">Hello {{ $user->name }}! 👋</div>
                
                <div class="message">
                    We received a request to reset your TronX account password. If you made this request, click the button below to create a new password.
                </div>
                
                <!-- Security Notice -->
                <div class="security-notice">
                    <span class="icon">🔒</span>
                    <span class="text">This reset link will expire in 60 minutes for your security.</span>
                </div>
                
                <!-- Reset Button -->
                <div class="button-container">
                    <a href="{{ $resetUrl }}" class="reset-button">
                        🔐 Reset My Password
                    </a>
                </div>
                
                <!-- Alternative Link -->
                <div class="alternative-link">
                    <div class="alternative-link-text">If the button doesn't work, copy and paste this link:</div>
                    <a href="{{ $resetUrl }}" class="alternative-link-url">{{ $resetUrl }}</a>
                </div>
                
                <div class="message">
                    <strong>Didn't request this?</strong><br>
                    If you didn't request a password reset, you can safely ignore this email. Your password will remain unchanged.
                </div>
                
                <!-- Security Tips -->
                <div style="background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%); padding: 20px; border-radius: 8px; margin-top: 24px; border-left: 4px solid #3b82f6;">
                    <div style="color: #1e40af; font-weight: 600; margin-bottom: 8px;">🛡️ Security Tips:</div>
                    <ul style="color: #1e40af; font-size: 14px; margin-left: 20px;">
                        <li>Never share your password with anyone</li>
                        <li>Use a strong, unique password</li>
                        <li>Enable 2FA for extra security</li>
                    </ul>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <div class="footer-text">
                    This email was sent by <strong>TronX</strong><br>
                    Your trusted TRON staking platform
                </div>
                
                <div class="footer-links">
                    <a href="{{ url('/') }}">Visit Website</a>
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                    <a href="mailto:support@tronxearn.site">Support</a>
                </div>
                
                <div style="margin-top: 16px; color: #9ca3af; font-size: 12px;">
                    © {{ date('Y') }} TronX. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</body>
</html> 