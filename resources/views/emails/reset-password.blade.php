<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reset Your Kiddify Password</title>
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

        /* Button - matching Kiddify theme */
        .reset-button-container {
            text-align: center;
            margin: 35px 0;
        }

        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #5A9CB5 0%, #4A8CA0 100%);
            color: #ffffff !important;
            padding: 16px 40px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 700;
            box-shadow: 0 5px 20px rgba(90, 156, 181, 0.3);
            transition: all 0.3s ease;
        }

        .reset-button:hover {
            background: linear-gradient(135deg, #4A8CA0 0%, #5A9CB5 100%);
            box-shadow: 0 7px 25px rgba(90, 156, 181, 0.4);
            transform: translateY(-2px);
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

        /* Expiry info box */
        .expiry-box {
            background: linear-gradient(135deg, #FFF9E6 0%, #FFFBF0 100%);
            border: 2px solid #FACE68;
            border-radius: 12px;
            padding: 15px 20px;
            margin: 20px 0;
            text-align: center;
        }

        .expiry-box p {
            color: #D9A640;
            margin: 5px 0;
            font-weight: 600;
            font-size: 14px;
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

            .reset-button {
                padding: 14px 30px;
                font-size: 15px;
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
                    <img src="{{ config('app.url') }}/images/kiddify_logo.svg" alt="Kiddify Logo">
                </div>
                <h1>🔑 Password Reset Request</h1>
            </div>

            <!-- Main Content -->
            <div class="email-content">
                <p class="greeting">Hello<strong>{{ $user ? ' ' . $user->name : '' }}</strong>,</p>

                <p>We received a request to reset the password for your Kiddify account.</p>

                <!-- Purpose Badge -->
                <div style="text-align: center;">
                    <span class="purpose-badge">🔐 Password Reset</span>
                </div>

                <!-- Reset Button -->
                <div class="reset-button-container">
                    <a href="{{ $resetUrl }}" class="reset-button">
                        Reset Password
                    </a>
                </div>

                <!-- Expiry Info -->
                <div class="expiry-box">
                    <p>⏱️ This link will expire in {{ $expireMinutes }} minutes</p>
                </div>

                <!-- What's This For -->
                <div class="info-section">
                    <h3>📋 What happens next?</h3>
                    <ul>
                        <li>Click the "Reset Password" button above</li>
                        <li>You'll be taken to a secure page to create a new password</li>
                        <li>Choose a strong password with at least 8 characters</li>
                        <li>After resetting, you can log in with your new password</li>
                    </ul>
                </div>

                <!-- Security Warning -->
                <div class="security-warning">
                    <div class="security-warning-header">
                        <span class="security-icon">⚠️</span>
                        <h4>Security Notice</h4>
                    </div>
                    <p>
                        <strong>If you didn't request this password reset</strong>, please ignore this email and your
                        password will remain unchanged. Your account is secure and no changes will be made.
                        Never share your password or reset links with anyone, including Kiddify staff.
                    </p>
                </div>

                <p style="color: #666666; font-size: 13px; text-align: center; margin-top: 30px;">
                    If the button above doesn't work, copy and paste this URL into your browser:<br>
                    <span style="word-break: break-all; color: #5A9CB5;">{{ $resetUrl }}</span>
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
