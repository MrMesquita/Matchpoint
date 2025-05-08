<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your MatchPoint Password</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #121212;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #333333;
        }

        table {
            border-spacing: 0;
            width: 100%;
        }

        td {
            padding: 0;
        }

        img {
            border: 0;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #121212;
            padding: 20px 0;
        }

        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-radius: 8px;
            overflow: hidden;
        }

        .header {
            background-color: #44281d;
            color: #ffffff;
            text-align: center;
            padding: 30px 20px;
        }

        .header h1 {
            font-size: 28px;
            margin: 0;
        }

        .header h2 {
            font-size: 18px;
            margin: 10px 0 0;
            font-weight: 400;
        }

        .content {
            padding: 30px 20px;
            text-align: center;
            color: #333333;
        }

        .content p {
            margin: 15px 0;
        }

        .code-box {
            display: inline-block;
            padding: 15px 30px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
            margin: 25px 0;
            cursor: pointer;
            background: linear-gradient(135deg, #44281d, #2a1a12);
        }

        .code-box a {
            color: #ffffff;
            text-decoration: none;
        }

        .code-box:hover {
            background: linear-gradient(135deg, #392219, #19100b);
        }

        .security-note {
            background-color: #fff8e1;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 25px 0;
            text-align: left;
            font-size: 14px;
            color: #333333;
        }

        .footer {
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #777777;
        }

        @media only screen and (max-width: 600px) {
            .content, .header, .footer {
                padding-left: 15px !important;
                padding-right: 15px !important;
            }

            .code-box {
                font-size: 24px !important;
                letter-spacing: 3px !important;
                padding: 10px 20px !important;
            }
        }
    </style>
</head>
<body>
<center class="wrapper">
    <table class="main" role="presentation">
        <tr>
            <td class="header">
                <h1>MatchPoint</h1>
                <h2>Password Reset Request</h2>
            </td>
        </tr>
        <tr>
            <td class="content">
                <p>Hello,</p>
                <p>You've requested to reset your MatchPoint account password. Here's you recovery password</p>
                <div class="code-box">
                    <a href="{{ $urlRecoveryToken }}" target="_blank">
                        Change password
                    </a>
                </div>
                <div class="security-note">
                    <strong>Important:</strong>
                    <p>This code will expire in 5 minutes. For your security, never share this code with anyone,
                        including MatchPoint support.</p>
                </div>
                <p>If you didn't request a password reset, please secure your account by changing your password
                    immediately.</p>
                <p>Need help? <a href="mailto:suport.matchpoint@gmail.com"
                                 style="color: #44281d; text-decoration: none;">Contact our support team</a></p>
            </td>
        </tr>
        <tr>
            <td class="footer">
                <p>© <span>{{ date('YYYY') }}</span> MatchPoint API. All rights reserved.</p>
            </td>
        </tr>
    </table>
</center>
</body>
</html>
