<!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradieFlow HTML Email</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <style>
        a {
            text-decoration: underline;
            color: #3962FA;
        }

        a:hover {
            text-decoration: none;
        }

    </style>
</head>

<body style="Margin:0;padding:0;background-color:#FFFFFF;font-family:'Poppins', sans-serif; font-weight: 400;">
<center class="wrapper"
        style="width:100%;table-layout:fixed;padding-top:40px;padding-bottom:40px;background-color:#FFFFFF;">
    <div class="webkit" style="max-width:600px;">
        <table class="outer" align="center"
               style="width:100%;max-width:600px;margin:0 auto;border-spacing:0;border:1px solid #ededed;border-radius:8px;font-family:'Poppins', sans-serif;color:#000000;">
            <tr>
                <td style="padding:0;padding: 32px;">
                    <table width="100%" style="border-spacing:0;border-spacing: 0; text-align: center;">
                        <tr>
                            <td style="padding:0;text-align: left;">
                                <a href="{{ $other_parameters['app_url'] }}" style="text-decoration:none;display:block; max-width: 270px;">
                                    <img src="{{ $other_parameters['app_url'] }}/images/logo.png" alt="TradieFlow logo" width="269px" height="30" style="border:0;">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:0;padding-top: 32px; text-align: left;">
                                <h1 style="margin:0;padding:0;font-family:'Poppins', sans-serif;font-size: 30px; line-height: 36px; font-weight: 500;">
                                    <span style="display: inline; color: #43D14F;">
                                        Invoice paid
                                    </span>
                                    by
                                    {{ $other_parameters['invoice']->Client->name }}
                                </h1>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:0;padding-top: 16px; font-size: 17px; line-height: 24px; text-align: left">
                                <p style="margin-top:0; margin-bottom: 20px;padding:0;font-family:'Poppins', sans-serif;">
                                    Dear {{ $other_parameters['invoice']->User->name }},
                                </p>
                                <p style="margin-top:0; margin-bottom: 20px;padding:0;font-family:'Poppins', sans-serif;">
                                    {{ $other_parameters['invoice']->Client->name }} has just paid you {{ $other_parameters['invoice']->total_gross_amount }} {{ strtoupper($other_parameters['invoice']->currency) }}. The associated invoice number is {{ $other_parameters['invoice']->invoice_number_label }}. Please keep in mind that direct bank transfers can take up to a few days.
                                </p>
                                <p style="margin-top:0; margin-bottom: 20px;padding:0;font-family:'Poppins', sans-serif;">
                                    Best Regards,<br>
                                    The Tradie<span style="display: inline; color: #43D14F;">Flow</span> Team
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 40px; font-size: 17px; line-height: 24px;">
                                <p style="margin:0;padding:0;font-family:'Poppins', sans-serif; text-align: left;">
                                    Sent by using Tradie
                                    <span style="display: inline; color: #43D14F;">Flow</span>
                                </p>
                                <p style="margin:0;padding:0;font-family:'Poppins', sans-serif; text-align: left;">
                                    <a href="{{ $other_parameters['app_url'] }}" style="margin-right: 12px; display: inline; vertical-align:middle;">Homepage</a> <span style="display: inline; vertical-align: middle;">.</span>
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
