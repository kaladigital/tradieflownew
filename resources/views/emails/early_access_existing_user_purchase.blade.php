<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradieFlow Payment Success Email</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <style>
        a {
            text-decoration: underline;
            color: #43d14f;
        }

        a:hover {
            text-decoration: none;
        }
    </style>
</head>
<body style="Margin:0;padding:0;background-color:#FFFFFF;font-family:'Poppins', sans-serif; font-weight: 400;">
<center class="wrapper" style="width:100%;table-layout:fixed;padding-top:40px;padding-bottom:40px;background-color:#FFFFFF;">
    <div class="webkit" style="max-width:600px;">
        <table class="outer" align="center" style="width:100%;max-width:600px;margin:0 auto;border-spacing:0;border:1px solid #ededed;border-radius:8px;font-family:'Poppins', sans-serif;color:#000000;">
            <tr>
                <td style="padding:0;padding: 32px;">
                    <table width="100%" style="border-spacing:0;border-spacing: 0; text-align: center;">
                        <tr>
                            <td style="padding:0;text-align: center;">
                                <a href="{{ $other_parameters['app_url'] }}" style="text-decoration:none;display:block; margin-left: auto; margin-right: auto; max-width: 275px; image-rendering: -webkit-optimize-contrast;">
                                    <img src="{{ $other_parameters['app_url'] }}/images/logo.png" alt="TradieFlow logo" style="border:0;">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:0;padding-top: 32px; text-align: center;">
                                <h1 style="margin:0;padding:0;font-family:'Poppins', sans-serif;font-size: 30px; line-height: 36px; font-weight: 500;">
                                    <span style="display: inline; color: #43d14f;">
                                        Payment
                                    </span>
                                    Successful
                                </h1>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:0;padding-top: 16px; font-size: 17px; line-height: 24px; text-align: center">
                                <p style="margin:0;margin-bottom: 16px;padding:0;font-family:'Poppins', sans-serif;">You have successfully payed for our {{ $other_parameters['plan_name'] }}. Your subscription expires on {{ $other_parameters['expiry_date_format'] }}.</p>
                                <p style="margin:0;margin-bottom: 32px; padding:0;font-family:'Poppins', sans-serif;">Please log in to the system to start using TradieFlow by clicking the following button:</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center">
                                <a href="{{ $other_parameters['login_url'] }}" style="display: block; margin-left: auto; margin-right: auto; font-size: 16px; font-weight: 600; font-family:'Poppins',sans-serif; text-decoration: none !important; border-radius: 4px; max-width: 258px; image-rendering: -webkit-optimize-contrast;">
                                    <img src="{{ $other_parameters['app_url'] }}/images/email-login.png" alt="Login" width="258" height="48">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 40px; font-size: 17px; line-height: 24px;">
                                <p style="margin:0;margin-bottom: 16px;padding:0;font-family:'Poppins', sans-serif; text-align: center;">
                                    Questions about setting up TradieFlow? Get in touch with us at
                                    <a href="mailto:info@tradieflow.co">info@tradieflow.co</a>
                                </p>
                                <p style="margin: 0;padding:0;font-family:'Poppins', sans-serif; text-align: center;">
                                    If you did not request this email, don’t worry – just ignore it.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 78px; font-size: 17px; line-height: 24px;">
                                <p style="margin:0;padding:0;font-family:'Poppins', sans-serif; text-align: center;">
                                    Tailored to your needs by Tradie<span style="display: inline; color: #43d14f;">Flow</span>
                                </p>
                                <p style="margin:0;padding:0;font-family:'Poppins', sans-serif; text-align: center;">
                                    <a href="{{ $other_parameters['app_url'] }}" style="margin-right: 12px; display: inline; vertical-align:middle;">Homepage</a>
                                    <span style="display: inline; vertical-align: middle;">.</span>
                                    <a href="{{ $other_parameters['app_url'] }}/free-trial" style="margin-left: 12px; display: inline; vertical-align:middle;">Free Trial</a>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</center>
</body>
</html>
