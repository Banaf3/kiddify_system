<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Your Kiddify OTP Code</title>
    <style>
        /* Reset styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #E0F2F7 0%, #FFF9E6 100%);
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .email-wrapper {
            width: 100%;
            padding: 30px 15px;
            background: linear-gradient(135deg, #E0F2F7 0%, #FFF9E6 100%);
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(90, 156, 181, 0.15);
        }

        /* Header with logo - Kiddify theme colors */
        .email-header {
            background: linear-gradient(135deg, #5A9CB5 0%, #FAAC68 100%);
            padding: 40px 30px;
            text-align: center;
            border-bottom: 4px solid #FACE68;
        }

        .logo-container {
            margin-bottom: 15px;
        }

        .logo-container img {
            max-width: 180px;
            height: auto;
            display: inline-block;
        }

        .email-header h1 {
            color: #ffffff;
            margin: 10px 0 0 0;
            font-size: 24px;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Content area */
        .email-content {
            padding: 40px 30px;
            background-color: #ffffff;
        }

        .greeting {
            font-size: 18px;
            color: #333333;
            margin-bottom: 20px;
        }

        .greeting strong {
            color: #5A9CB5;
        }

        /* Purpose badge - Kiddify orange */
        .purpose-badge {
            display: inline-block;
            background: linear-gradient(135deg, #FAAC68 0%, #FACE68 100%);
            color: #ffffff;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            margin: 10px 0 20px 0;
            box-shadow: 0 3px 10px rgba(250, 172, 104, 0.3);
        }

        /* OTP Box - matching Kiddify theme */
        .otp-box {
            background: linear-gradient(135deg, #F0F9FC 0%, #FFF9E6 100%);
            border: 3px solid #5A9CB5;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
            box-shadow: 0 5px 20px rgba(90, 156, 181, 0.1);
        }

        .otp-label {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #5A9CB5;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .otp-code {
            font-size: 48px;
            font-weight: 900;
            letter-spacing: 12px;
            color: #5A9CB5;
            margin: 15px 0;
            text-shadow: 0 2px 4px rgba(90, 156, 181, 0.2);
            font-family: 'Courier New', monospace;
        }

        .otp-expiry {
            margin: 10px 0 0 0;
            font-size: 13px;
            color: #FAAC68;
            font-weight: 600;
        }

        /* Info section */
        .info-section {
            background-color: #F0F9FC;
            border-left: 4px solid #5A9CB5;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }

        .info-section h3 {
            color: #5A9CB5;
            margin: 0 0 10px 0;
            font-size: 16px;
        }

        .info-section ul {
            margin: 10px 0;
            padding-left: 20px;
        }

        .info-section li {
            color: #333333;
            line-height: 1.8;
            margin-bottom: 8px;
        }

        /* Security warning - Kiddify yellow accent */
        .security-warning {
            background: linear-gradient(135deg, #FFF9E6 0%, #FFFBF0 100%);
            border: 2px solid #FACE68;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
        }

        .security-warning-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .security-icon {
            font-size: 24px;
            margin-right: 10px;
        }

        .security-warning h4 {
            color: #D9A640;
            margin: 0;
            font-size: 16px;
        }

        .security-warning p {
            color: #666666;
            margin: 10px 0 0 0;
            line-height: 1.6;
        }

        /* Masked email display */
        .masked-email {
            background-color: #F0F9FC;
            padding: 10px 15px;
            border-radius: 8px;
            display: inline-block;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
            color: #5A9CB5;
            font-size: 14px;
            font-weight: 600;
        }

        /* Footer - Kiddify theme */
        .email-footer {
            background: linear-gradient(135deg, #F0F9FC 0%, #FFF9E6 100%);
            padding: 30px;
            text-align: center;
            border-top: 3px solid #5A9CB5;
        }

        .email-footer p {
            color: #666666;
            margin: 8px 0;
            font-size: 13px;
            line-height: 1.5;
        }

        .email-footer .copyright {
            color: #5A9CB5;
            font-weight: 600;
            margin-top: 15px;
        }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                padding: 15px 10px;
            }

            .email-content {
                padding: 25px 20px;
            }

            .otp-code {
                font-size: 36px;
                letter-spacing: 8px;
            }

            .logo-container img {
                max-width: 140px;
            }

            .email-header h1 {
                font-size: 20px;
            }
        }

        /* General text styles */
        p {
            line-height: 1.7;
            color: #333333;
            margin: 15px 0;
        }

        a {
            color: #5A9CB5;
            text-decoration: none;
            font-weight: 600;
        }

        a:hover {
            color: #FAAC68;
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header with Kiddify Logo -->
            <div class="email-header">
                <div class="logo-container">
                    <img src="https://kiddifysystem-production.up.railway.app/images/kiddify_logo.svg"
                        alt="Kiddify Logo">
                </div>
                <h1>🔐 Security Verification</h1>
            </div>

            <!-- Main Content -->
            <div class="email-content">
                <p class="greeting">Hello <strong>{{ $user->name }}</strong>,</p>

                <p>We received a request for a one-time password (OTP) to verify your identity.</p>

                <!-- Purpose Badge -->
                <div style="text-align: center;">
                    <span
                        class="purpose-badge">{{ $purpose === 'login' ? '🔑 Login Verification' : '👤 Profile Access' }}</span>
                </div>

                <!-- Masked Email -->
                <p style="text-align: center; margin: 15px 0;">
                    <span style="font-size: 13px; color: #666;">Sent to:</span><br>
                    <span class="masked-email">{{ $maskedEmail }}</span>
                </p>

                <!-- OTP Box -->
                <div class="otp-box">
                    <p class="otp-label">Your Verification Code</p>
                    <div class="otp-code">{{ $otp }}</div>
                    <p class="otp-expiry">⏱️ Valid for 10 minutes</p>
                </div>

                <!-- What's This For -->
                <div class="info-section">
                    <h3>📋 What's this for?</h3>
                    <ul>
                        @if ($purpose === 'login')
                            <li>Complete your login to your Kiddify account</li>
                            <li>This code was requested after entering your credentials</li>
                            <li>Enter this code on the verification page to proceed</li>
                        @else
                            <li>Access your profile settings securely</li>
                            <li>This is an additional security step to protect your account</li>
                            <li>Enter this code to view or edit your profile information</li>
                        @endif
                    </ul>
                </div>

                <!-- Security Warning -->
                <div class="security-warning">
                    <div class="security-warning-header">
                        <span class="security-icon">⚠️</span>
                        <h4>Security Notice</h4>
                    </div>
                    <p>
                        <strong>If you didn't request this code</strong>, please ignore this email and consider changing
                        your password immediately.
                        Never share this OTP code with anyone, including Kiddify staff. We will never ask for your
                        verification code.
                    </p>
                </div>

                <p style="color: #666666; font-size: 13px; text-align: center; margin-top: 30px;">
                    This OTP will expire in 10 minutes for your security.<br>
                    Check your spam folder if you don't see this email in your inbox.
                </p>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <p class="copyright">&copy; {{ date('Y') }} Kiddify. All rights reserved.</p>
                <p>This is an automated security email. Please do not reply to this message.</p>
                <p style="margin-top: 15px; font-size: 12px;">
                    Kiddify Learning Platform<br>
                    Empowering young minds through education
                </p>
            </div>
        </div>
    </div>
</body>

</html>