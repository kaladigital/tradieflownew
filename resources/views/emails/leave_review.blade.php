<!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradieFlow HTML Email</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <style type="text/css">
        body {
            Margin: 0;
            padding: 0;
            background-color: #FFFFFF;
            font-family: 'Poppins', sans-serif;
        }

        table {
            border-spacing: 0;
        }

        td {
            padding: 0;
        }

        img {
            border: 0;
        }

        p,
        h1,
        h2,
        h3 {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #FFFFFF;
        }

        .webkit {
            max-width: 600px;
        }

        .outer {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            border-spacing: 0;
            border: 1px solid #ededed;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            color: #000000;
        }

        a {
            text-decoration: none;
            display: inline-block;
            vertical-align: top;
        }

        .ratting-group a {
            width: 58px;
            height: 48px;
            float: left;
        }

        .ratting-group .icon-selected,
        .ratting-group:hover .icon-default,
        .ratting-group a:hover~a .icon-selected {
            display: none;
        }

        .ratting-group:hover .icon-selected,
        .ratting-group a:hover~a .icon-default {
            display: inline-block;
        }

        @media screen and (max-width: 600px) {}

        @media screen and (max-width: 400px) {}

    </style>
</head>

<body>
<center class="wrapper">
    <div class="webkit">
        <table class="outer" align="center">
            <tr>
                <td style="padding: 32px;">
                    <table width="100%" style="border-spacing: 0; text-align: center">
                        <tr>
                            <td style="text-align: center;">
                                <a href="{{ $other_parameters['app_url'] }}">
                                    @if($other_parameters['logo'])
                                        <img src="{{ $other_parameters['app_url'] }}/review-logo/{{ $other_parameters['logo'] }}" alt="Company logo" title="Company logo" style="max-width: 250px;">
                                    @else
                                        <img src="{{ $other_parameters['app_url'] }}/images/company-logo.png" alt="Company logo" title="Company logo" style="max-width: 250px;">
                                    @endif
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 32px; text-align: center;">
                                <h1 style="font-size: 30px; line-height: 36px; font-weight: medium; font-weight: 500;">
                                    How did
                                    @if($other_parameters['company_name'])
                                        {{ $other_parameters['company_name'].' ('.$other_parameters['name'].')' }}
                                    @else
                                        {{ $other_parameters['name'] }}
                                    @endif
                                    do?
                                </h1>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 16px; font-size: 17px; line-height: 24px;">
                                <p>We have seen that your project with {{ $other_parameters['name'] }} just got completed.</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 16px; padding-bottom: 32px; font-size: 17px; line-height: 24px;">
                                <p>
                                    Could you leave a review about {{ $other_parameters['first_name'] }}’s work? This feedback would not only help us, but it helps hundreds of other potential customers. And it does not take more than 60 seconds.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="background-color: #EFF2F9; border-radius: 4px; padding: 32px;">
                                <table style="border-spacing: 0; text-align: center;">
                                    <tr>
                                        <td>
                                            <h2 style="font-size: 24px; line-height: 32px; font-weight: medium; font-weight: 500;">
                                                How would you rate your experience with {{ $other_parameters['first_name'] }}?
                                            </h2>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 4px; font-size: 14px; -webkit-text-size-adjust: 100%; color: #000000; color: #000000B3;">
                                            <p>
                                                (1 star represents “Extremely Poor” and 5 stars represents “Extremely Positive”)
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding-top: 12px;">
                                            <table style="border-spacing:0; max-width: 290px;" align="center">
                                                <tr>
                                                    <td style="text-align: center">
                                                        <div class="ratting-group">
                                                            <a href="{{ $other_parameters['url'] }}?r=terrible">
                                                                <img class="icon-default" width="48" height="48" src="{{ $other_parameters['app_url'] }}/images/default-star-square2x.png" alt="Terrible">
                                                                <img class="icon-selected" width="48" height="48" src="{{ $other_parameters['app_url'] }}/images/selected-star-square2x.png" alt="Terrible">
                                                            </a>
                                                            <a href="{{ $other_parameters['url'] }}?r=poor">
                                                                <img class="icon-default" width="48" height="48" src="{{ $other_parameters['app_url'] }}/images/default-star-square2x.png" alt="Poor">
                                                                <img class="icon-selected" width="48" height="48" src="{{ $other_parameters['app_url'] }}/images/selected-star-square2x.png" alt="Poor">
                                                            </a>
                                                            <a href="{{ $other_parameters['url'] }}?r=average">
                                                                <img class="icon-default" width="48" height="48" src="{{ $other_parameters['app_url'] }}/images/default-star-square2x.png" alt="Average">
                                                                <img class="icon-selected" width="48" height="48" src="{{ $other_parameters['app_url'] }}/images/selected-star-square2x.png" alt="Average">
                                                            </a>
                                                            <a href="{{ $other_parameters['url'] }}?r=very-good">
                                                                <img class="icon-default" width="48" height="48" src="{{ $other_parameters['app_url'] }}/images/default-star-square2x.png" alt="Very Good">
                                                                <img class="icon-selected" width="48" height="48" src="{{ $other_parameters['app_url'] }}/images/selected-star-square2x.png" alt="Very Good">
                                                            </a>
                                                            <a href="{{ $other_parameters['url'] }}?r=excellent">
                                                                <img class="icon-default" width="48" height="48" src="{{ $other_parameters['app_url'] }}/images/default-star-square2x.png" alt="Excellent">
                                                                <img class="icon-selected" width="48" height="48" src="{{ $other_parameters['app_url'] }}/images/selected-star-square2x.png" alt="Excellent">
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
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
