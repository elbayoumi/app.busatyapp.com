<!doctype html>
<html lang="en-US">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>Reset Password Email - Busaty</title>
    <meta name="description" content="Reset Password Email Template for Busaty.">
    <style type="text/css">
        a:hover {
            text-decoration: underline !important;
        }

        body {
            margin: 0px;
            background-color: #f2f3f8;
            font-family: 'Open Sans', sans-serif;
        }

        table {
            max-width: 670px;
            margin: 0 auto;
        }

        h1 {
            color: #1e1e2d;
            font-weight: 500;
            font-size: 32px;
            font-family: 'Rubik', sans-serif;
        }

        p {
            color: #455056;
            font-size: 15px;
            line-height: 24px;
            margin: 0;
        }

        .activation-button {
            background: #20e277;
            text-decoration: none !important;
            font-weight: 500;
            color: #fff;
            text-transform: uppercase;
            font-size: 14px;
            padding: 10px 24px;
            display: inline-block;
            border-radius: 50px;
        }

        .email-container {
            background: #fff;
            border-radius: 3px;
            text-align: center;
            box-shadow: 0 6px 18px 0 rgba(0, 0, 0, 0.06);
        }

        .divider {
            margin: 29px 0 26px;
            border-bottom: 1px solid #cecece;
            width: 100px;
            display: inline-block;
        }

        .logo {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8">
        <tr>
            <td>
                <table border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="height:80px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;" class="logo">
                            <!-- شعار -->
                            <img width="60" src="https://stage.busatyapp.com/uploads/staffs_logo/f8Y74rX3oyXUXNwcdk7gaPq9FgF2d9lAC9rYd026.jpg" alt="logo">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="email-container">
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 35px;">
                                        <h1>Busaty</h1>
                                        <span class="divider"></span>
                                        <p>Thank you for registering on Busaty App.</p>
                                        <p>Your activation code is: <b>{{ $mail_data['code'] }}</b></p>
                                        <p>To activate your account, please click the link below:</p>
                                        <p>
                                            <a href="{{ $mail_data['activation_link'] }}" class="activation-button">
                                                Activate Your Account
                                            </a>
                                        </p>
                                        <p style="color:#455056; font-size:14px; margin-top:15px;">
                                            Please note that this link is valid for 60 minutes only.
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                            <!-- حقوق الملكية -->
                            <p style="font-size:14px; color:rgba(69, 80, 86, 0.74); line-height:18px; margin:0;">&copy; 2024 Busaty, All Rights Reserved.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:80px;">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
