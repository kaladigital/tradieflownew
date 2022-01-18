<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradieFlow Setup Invitation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&amp;display=swap" rel="stylesheet">
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
            <tbody>
            <tr>
                <td style="padding:0;padding: 32px;">
                    <table width="100%" style="border-spacing:0;border-spacing: 0; text-align: center;">
                        <tbody>
                        <tr>
                            <td style="padding:0;text-align: center;">
                                <a href="{{ $other_parameters['APP_URL'] }}"
                                   style="text-decoration:none;display:block; max-width: 270px; margin-left: auto; margin-right: auto;">
                                    <img src="{{ $other_parameters['APP_URL'] }}/images/tradieFlowLogo2x.png" alt="TradieFlow logo" width="269px" height="30" style="border:0;">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:0;padding-top: 32px; text-align: center;">
                                <h1 style="margin:0;padding:0;font-family:'Poppins', sans-serif;font-size: 30px; line-height: 36px; font-weight: 500;">
                                    <span style="display: inline; color: #43D14F;">
                                        {{ $other_parameters['CLIENT_NAME'] }}
                                    </span>
                                    completed configuring Forms and asked you to set up TradieFlow.
                                </h1>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:0;padding-top: 16px; font-size: 17px; line-height: 24px;">
                                <p style="margin:0;padding:0;font-family:'Poppins', sans-serif;">
                                    TradieFlow is a job management software for trades and contractor businesses. tradieflow handles leads, schedules quotes, books in jobs, sends invoices, and collects payment all from the very same app.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:0;padding-top: 16px; padding-bottom: 32px; font-size: 17px; line-height: 24px;">
                                <p style="margin:0;padding:0;font-family:'Poppins', sans-serif;">You were asked add a couple of scripts to {{ $other_parameters['CLIENT_NAME'] }}'s website so they can make the most out of TradieFlow</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center">
                                <a href="#" style="display: block; margin-left: auto; margin-right:
										auto; font-size: 16px;
										font-weight: 600; font-family:'Poppins', sans-serif; text-decoration: none !important;
										border-radius: 4px; max-width: 258px;">
                                    <img src="{{ $other_parameters['APP_URL'] }}/images/button2x.png" alt="Setup TradieFlow" width="258" height="48">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 32px; font-size: 17px; line-height: 24px;">
                                <p style="margin:0;padding:0;font-family:'Poppins', sans-serif;">Questions about
                                    setting up
                                    TradieFlow? Get in
                                    touch with us at <a
                                            href="{{ $other_parameters['APP_URL'] }}/contact-us">www.tradieflow.co/contact-us</a></p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 40px; font-size: 17px; line-height: 24px;">
                                <p style="margin:0;padding:0;font-family:'Poppins', sans-serif; text-align: center;">
                                    Tailored
                                    to your needs by
                                    Tradie<span style="display: inline; color: #43D14F;">Flow</span></p>
                                <p style="margin:0;padding:0;font-family:'Poppins', sans-serif; text-align: center;"><a
                                            href="{{ $other_parameters['APP_URL'] }}/" style="margin-right: 12px; display: inline; vertical-align:
											middle;">Homepage</a> <span style="display: inline; vertical-align: middle;">.</span> <a
                                            href="{{ $other_parameters['APP_URL'] }}/free-demo" style="margin-left: 12px; display: inline; vertical-align:
											middle;">Free
                                        Demo</a></p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</center>
</body>
</html>
