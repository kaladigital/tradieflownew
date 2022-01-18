<?php
namespace App\Helpers;

class Constant
{

    public static function GET_TWILIO_PHONE_TYPES()
    {
        return [
            'local' => 'Local',
            'mobile' => 'Mobile',
            'toll-free' => 'Toll-Free'
        ];
    }

    public static function GET_TWILIO_AVAILABLE_NUMBERS(){
        return [
            'AR' => 'AR',
            'AT' => 'AT',
            'AU' => 'AU',
            'BA' => 'BA',
            'BB' => 'BB',
            'BE' => 'BE',
            'BG' => 'BG',
            'BJ' => 'BJ',
            'BR' => 'BR',
            'BW' => 'BW',
            'CA' => 'CA',
            'CH' => 'CH',
            'CL' => 'CL',
            'CO' => 'CO',
            'CY' => 'CY',
            'CZ' => 'CZ',
            'DE' => 'DE',
            'DK' => 'DK',
            'DO' => 'DO',
            'DZ' => 'DZ',
            'EC' => 'EC',
            'EE' => 'EE',
            'FI' => 'FI',
            'FR' => 'FR',
            'GB' => 'GB',
            'GD' => 'GD',
            'GE' => 'GE',
            'GH' => 'GH',
            'GN' => 'GN',
            'GR' => 'GR',
            'HK' => 'HK',
            'HU' => 'HU',
            'ID' => 'ID',
            'IL' => 'IL',
            'IN' => 'IN',
            'IS' => 'IS',
            'IT' => 'IT',
            'JM' => 'JM',
            'JP' => 'JP',
            'KE' => 'KE',
            'KR' => 'KR',
            'KY' => 'KY',
            'LT' => 'LT',
            'LU' => 'LU',
            'ML' => 'ML',
            'MO' => 'MO',
            'MU' => 'MU',
            'MX' => 'MX',
            'MY' => 'MY',
            'NA' => 'NA',
            'NG' => 'NG',
            'NI' => 'NI',
            'NL' => 'NL',
            'NZ' => 'NZ',
            'PA' => 'PA',
            'PE' => 'PE',
            'PH' => 'PH',
            'PL' => 'PL',
            'PR' => 'PR',
            'PT' => 'PT',
            'RO' => 'RO',
            'RS' => 'RS',
            'SD' => 'SD',
            'SE' => 'SE',
            'SG' => 'SG',
            'SI' => 'SI',
            'SK' => 'SK',
            'SV' => 'SV',
            'TH' => 'TH',
            'TN' => 'TN',
            'TT' => 'TT',
            'UG' => 'UG',
            'US' => 'US',
            'VE' => 'VE',
            'VI' => 'VI',
            'VN' => 'VN',
            'ZA' => 'ZA'
        ];
    }

    public static function GET_ALLOWED_IMAGE_EXTENSIONS()
    {
        return ['jpg','jpeg','png'];
    }

    public static function GET_TIME_INTERVAL_VALUES()
    {
        return [
            '00:00' => '12:00 AM',
            '00:05' => '12:05 AM',
            '00:10' => '12:10 AM',
            '00:15' => '12:15 AM',
            '00:20' => '12:20 AM',
            '00:25' => '12:25 AM',
            '00:30' => '12:30 AM',
            '00:35' => '12:35 AM',
            '00:40' => '12:40 AM',
            '00:45' => '12:45 AM',
            '00:50' => '12:50 AM',
            '00:55' => '12:55 AM',
            '01:00' => '1:00 AM',
            '01:05' => '1:05 AM',
            '01:10' => '1:10 AM',
            '01:15' => '1:15 AM',
            '01:20' => '1:20 AM',
            '01:25' => '1:25 AM',
            '01:30' => '1:30 AM',
            '01:35' => '1:35 AM',
            '01:40' => '1:40 AM',
            '01:45' => '1:45 AM',
            '01:50' => '1:50 AM',
            '01:55' => '1:55 AM',
            '02:00' => '2:00 AM',
            '02:05' => '2:05 AM',
            '02:10' => '2:10 AM',
            '02:15' => '2:15 AM',
            '02:20' => '2:20 AM',
            '02:25' => '2:25 AM',
            '02:30' => '2:30 AM',
            '02:35' => '2:35 AM',
            '02:40' => '2:40 AM',
            '02:45' => '2:45 AM',
            '02:50' => '2:50 AM',
            '02:55' => '2:55 AM',
            '03:00' => '3:00 AM',
            '03:05' => '3:05 AM',
            '03:10' => '3:10 AM',
            '03:15' => '3:15 AM',
            '03:20' => '3:20 AM',
            '03:25' => '3:25 AM',
            '03:30' => '3:30 AM',
            '03:35' => '3:35 AM',
            '03:40' => '3:40 AM',
            '03:45' => '3:45 AM',
            '03:50' => '3:50 AM',
            '03:55' => '3:55 AM',
            '04:00' => '4:00 AM',
            '04:05' => '4:05 AM',
            '04:10' => '4:10 AM',
            '04:15' => '4:15 AM',
            '04:20' => '4:20 AM',
            '04:25' => '4:25 AM',
            '04:30' => '4:30 AM',
            '04:35' => '4:35 AM',
            '04:40' => '4:40 AM',
            '04:45' => '4:45 AM',
            '04:50' => '4:50 AM',
            '04:55' => '4:55 AM',
            '05:00' => '5:00 AM',
            '05:05' => '5:05 AM',
            '05:10' => '5:10 AM',
            '05:15' => '5:15 AM',
            '05:20' => '5:20 AM',
            '05:25' => '5:25 AM',
            '05:30' => '5:30 AM',
            '05:35' => '5:35 AM',
            '05:40' => '5:40 AM',
            '05:45' => '5:45 AM',
            '05:50' => '5:50 AM',
            '05:55' => '5:55 AM',
            '06:00' => '6:00 AM',
            '06:05' => '6:05 AM',
            '06:10' => '6:10 AM',
            '06:15' => '6:15 AM',
            '06:20' => '6:20 AM',
            '06:25' => '6:25 AM',
            '06:30' => '6:30 AM',
            '06:35' => '6:35 AM',
            '06:40' => '6:40 AM',
            '06:45' => '6:45 AM',
            '06:50' => '6:50 AM',
            '06:55' => '6:55 AM',
            '07:00' => '7:00 AM',
            '07:05' => '7:05 AM',
            '07:10' => '7:10 AM',
            '07:15' => '7:15 AM',
            '07:20' => '7:20 AM',
            '07:25' => '7:25 AM',
            '07:30' => '7:30 AM',
            '07:35' => '7:35 AM',
            '07:40' => '7:40 AM',
            '07:45' => '7:45 AM',
            '07:50' => '7:50 AM',
            '07:55' => '7:55 AM',
            '08:00' => '8:00 AM',
            '08:05' => '8:05 AM',
            '08:10' => '8:10 AM',
            '08:15' => '8:15 AM',
            '08:20' => '8:20 AM',
            '08:25' => '8:25 AM',
            '08:30' => '8:30 AM',
            '08:35' => '8:35 AM',
            '08:40' => '8:40 AM',
            '08:45' => '8:45 AM',
            '08:50' => '8:50 AM',
            '08:55' => '8:55 AM',
            '09:00' => '9:00 AM',
            '09:05' => '9:05 AM',
            '09:10' => '9:10 AM',
            '09:15' => '9:15 AM',
            '09:20' => '9:20 AM',
            '09:25' => '9:25 AM',
            '09:30' => '9:30 AM',
            '09:35' => '9:35 AM',
            '09:40' => '9:40 AM',
            '09:45' => '9:45 AM',
            '09:50' => '9:50 AM',
            '09:55' => '9:55 AM',
            '10:00' => '10:00 AM',
            '10:05' => '10:05 AM',
            '10:10' => '10:10 AM',
            '10:15' => '10:15 AM',
            '10:20' => '10:20 AM',
            '10:25' => '10:25 AM',
            '10:30' => '10:30 AM',
            '10:35' => '10:35 AM',
            '10:40' => '10:40 AM',
            '10:45' => '10:45 AM',
            '10:50' => '10:50 AM',
            '10:55' => '10:55 AM',
            '11:00' => '11:00 AM',
            '11:05' => '11:05 AM',
            '11:10' => '11:10 AM',
            '11:15' => '11:15 AM',
            '11:20' => '11:20 AM',
            '11:25' => '11:25 AM',
            '11:30' => '11:30 AM',
            '11:35' => '11:35 AM',
            '11:40' => '11:40 AM',
            '11:45' => '11:45 AM',
            '11:50' => '11:50 AM',
            '11:55' => '11:55 AM',
            '12:00' => '12:00 PM',
            '12:05' => '12:05 PM',
            '12:10' => '12:10 PM',
            '12:15' => '12:15 PM',
            '12:20' => '12:20 PM',
            '12:25' => '12:25 PM',
            '12:30' => '12:30 PM',
            '12:35' => '12:35 PM',
            '12:40' => '12:40 PM',
            '12:45' => '12:45 PM',
            '12:50' => '12:50 PM',
            '12:55' => '12:55 PM',
            '13:00' => '1:00 PM',
            '13:05' => '1:05 PM',
            '13:10' => '1:10 PM',
            '13:15' => '1:15 PM',
            '13:20' => '1:20 PM',
            '13:25' => '1:25 PM',
            '13:30' => '1:30 PM',
            '13:35' => '1:35 PM',
            '13:40' => '1:40 PM',
            '13:45' => '1:45 PM',
            '13:50' => '1:50 PM',
            '13:55' => '1:55 PM',
            '14:00' => '2:00 PM',
            '14:05' => '2:05 PM',
            '14:10' => '2:10 PM',
            '14:15' => '2:15 PM',
            '14:20' => '2:20 PM',
            '14:25' => '2:25 PM',
            '14:30' => '2:30 PM',
            '14:35' => '2:35 PM',
            '14:40' => '2:40 PM',
            '14:45' => '2:45 PM',
            '14:50' => '2:50 PM',
            '14:55' => '2:55 PM',
            '15:00' => '3:00 PM',
            '15:05' => '3:05 PM',
            '15:10' => '3:10 PM',
            '15:15' => '3:15 PM',
            '15:20' => '3:20 PM',
            '15:25' => '3:25 PM',
            '15:30' => '3:30 PM',
            '15:35' => '3:35 PM',
            '15:40' => '3:40 PM',
            '15:45' => '3:45 PM',
            '15:50' => '3:50 PM',
            '15:55' => '3:55 PM',
            '16:00' => '4:00 PM',
            '16:05' => '4:05 PM',
            '16:10' => '4:10 PM',
            '16:15' => '4:15 PM',
            '16:20' => '4:20 PM',
            '16:25' => '4:25 PM',
            '16:30' => '4:30 PM',
            '16:35' => '4:35 PM',
            '16:40' => '4:40 PM',
            '16:45' => '4:45 PM',
            '16:50' => '4:50 PM',
            '16:55' => '4:55 PM',
            '17:00' => '5:00 PM',
            '17:05' => '5:05 PM',
            '17:10' => '5:10 PM',
            '17:15' => '5:15 PM',
            '17:20' => '5:20 PM',
            '17:25' => '5:25 PM',
            '17:30' => '5:30 PM',
            '17:35' => '5:35 PM',
            '17:40' => '5:40 PM',
            '17:45' => '5:45 PM',
            '17:50' => '5:50 PM',
            '17:55' => '5:55 PM',
            '18:00' => '6:00 PM',
            '18:05' => '6:05 PM',
            '18:10' => '6:10 PM',
            '18:15' => '6:15 PM',
            '18:20' => '6:20 PM',
            '18:25' => '6:25 PM',
            '18:30' => '6:30 PM',
            '18:35' => '6:35 PM',
            '18:40' => '6:40 PM',
            '18:45' => '6:45 PM',
            '18:50' => '6:50 PM',
            '18:55' => '6:55 PM',
            '19:00' => '7:00 PM',
            '19:05' => '7:05 PM',
            '19:10' => '7:10 PM',
            '19:15' => '7:15 PM',
            '19:20' => '7:20 PM',
            '19:25' => '7:25 PM',
            '19:30' => '7:30 PM',
            '19:35' => '7:35 PM',
            '19:40' => '7:40 PM',
            '19:45' => '7:45 PM',
            '19:50' => '7:50 PM',
            '19:55' => '7:55 PM',
            '20:00' => '8:00 PM',
            '20:05' => '8:05 PM',
            '20:10' => '8:10 PM',
            '20:15' => '8:15 PM',
            '20:20' => '8:20 PM',
            '20:25' => '8:25 PM',
            '20:30' => '8:30 PM',
            '20:35' => '8:35 PM',
            '20:40' => '8:40 PM',
            '20:45' => '8:45 PM',
            '20:50' => '8:50 PM',
            '20:55' => '8:55 PM',
            '21:00' => '9:00 PM',
            '21:05' => '9:05 PM',
            '21:10' => '9:10 PM',
            '21:15' => '9:15 PM',
            '21:20' => '9:20 PM',
            '21:25' => '9:25 PM',
            '21:30' => '9:30 PM',
            '21:35' => '9:35 PM',
            '21:40' => '9:40 PM',
            '21:45' => '9:45 PM',
            '21:50' => '9:50 PM',
            '21:55' => '9:55 PM',
            '22:00' => '10:00 PM',
            '22:05' => '10:05 PM',
            '22:10' => '10:10 PM',
            '22:15' => '10:15 PM',
            '22:20' => '10:20 PM',
            '22:25' => '10:25 PM',
            '22:30' => '10:30 PM',
            '22:35' => '10:35 PM',
            '22:40' => '10:40 PM',
            '22:45' => '10:45 PM',
            '22:50' => '10:50 PM',
            '22:55' => '10:55 PM',
            '23:00' => '11:00 PM',
            '23:05' => '11:05 PM',
            '23:10' => '11:10 PM',
            '23:15' => '11:15 PM',
            '23:20' => '11:20 PM',
            '23:25' => '11:25 PM',
            '23:30' => '11:30 PM',
            '23:35' => '11:35 PM',
            '23:40' => '11:40 PM',
            '23:45' => '11:45 PM',
            '23:50' => '11:50 PM',
            '23:55' => '11:55 PM',
        ];
    }

    public static function GET_WEEK_DAYS()
    {
        return [
            'mon' => 'Monday',
            'tue' => 'Tuesday',
            'wed' => 'Wednesday',
            'thu' => 'Thursday',
            'fri' => 'Friday',
            'sat' => 'Saturday',
            'sun' => 'Sunday',
        ];
    }

    public static function GET_FONTS()
    {
        return [
            'roboto' => 'Roboto',
            'open_sans' => 'Open Sans',
            'noto_sans' => 'Noto Sans JP',
            'lato' => 'Lato',
        ];
    }

    public static function GET_CLIENT_STATUS_LIST()
    {
        return [
            'not-listed' => 'Not Listed',
            'lead' => 'Lead',
            'quote-meeting' => 'Quote Meeting',
            'work-in-progress' => 'Work In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];
    }

    public static function GET_ALL_EVENT_TYPES()
    {
        return [
            'quote-meeting' => 'Quote',
            'work-in-progress' => 'Work',
            'remind-me' => 'Follow-up',
            'other' => 'Other'
        ];
    }

    public static function GET_TWILO_API_URL()
    {
        return 'https://api.twilio.com';
    }

    public static function GET_TASK_PAGE_TASKS_PER_PAGE()
    {
        return 10;
    }

    public static function GET_MISSED_NEW_LEADS_PER_PAGE_ITEMS()
    {
        return 10;
    }

    public static function GET_FOLLOW_UPS_PER_PAGE()
    {
        return 10;
    }

    public static function GET_TASK_STATUS_TYPES()
    {
        return [
            'pending',
            'completed'
        ];
    }

    public static function GET_DISCOUNT_TYPES()
    {
        return [
            'percentage',
            'amount'
        ];
    }

    public static function GET_DISCOUNT_TYPES_LIST()
    {
        return [
            'percentage' => 'Percentage',
            'amount' => 'Amount'
        ];
    }

    public static function GET_INVOICES_PER_PAGE()
    {
        return 10;
    }

    public static function GET_CLIENT_STATUSES_LIST()
    {
        return [
            'not-listed',
            'lead',
            'quote-meeting',
            'work-in-progress',
            'completed',
            'cancelled'
        ];
    }

    public static function GET_EVENT_STATUSES_LIST()
    {
        return [
            'quote-meeting' => 'Quote Meeting',
            'work-in-progress' => 'Work',
            'remind-me' => 'Follow Up',
            'other' => 'Other'
        ];
    }

    public static function GET_ACITIVIES_PER_PAGE()
    {
        return 10;
    }

    public static function GET_TWILIO_COUNTRY_AVAILABLE_FILTERS()
    {
        return [
            'ca' => [
                'type' => 'tollFree',
                'capabilities' => [
                    'smsEnabled' => true,
                    'voiceEnabled' => true,
                    'mmsEnabled' => true
                ]
            ],
            'us' => [
                'type' => 'tollFree',
                'capabilities' => [
                    'smsEnabled' => true,
                    'voiceEnabled' => true,
                    'mmsEnabled' => true
                ]
            ],
            'au' => [
                'type' => 'tollFree',
                'capabilities' => [
                    'smsEnabled' => true,
                    'voiceEnabled' => true
                ]
            ],
            'gb' => [
                'type' => 'tollFree',
                'capabilities' => [
                    'smsEnabled' => true,
                    'voiceEnabled' => true,
                    'mmsEnabled' => true
                ]
            ]
        ];
    }

    public static function GET_TOPIC_MESSAGES_COUNT()
    {
        return 100;
    }

    public static function GET_CALL_HISTORY_TYPES()
    {
        return [
            'incoming' => 'Incoming',
            'outgoing' => 'Outgoing',
            'missed' => 'Missed'
        ];
    }

    public static function GET_FINAL_SUBSCRIPTION_EXPIRY_DAYS()
    {
        return 7;
    }

    public static function GET_CLIENT_PROFILE_HISTORY_ITEMS()
    {
        return 8;
    }

    public static function GET_CLIENT_LOCATION_TYPES()
    {
        return [
            'work' => 'Work',
            'office' => 'Office',
            'home' => 'Home'
        ];
    }

    public static function GET_REVIEW_RATE_SCORES()
    {
        return [
            'terrible' => 1,
            'poor' => 2,
            'average' => 3,
            'very-good' => 4,
            'excellent' => 5
        ];
    }

    public static function GET_RATE_SCORE_POINTS()
    {
        return [
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5'
        ];
    }

    public static function GET_REVIEWS_PAGE_DISPLAY_ITEMS()
    {
        return 8;
    }

    public static function GET_ALLOWED_UPLOAD_IMAGE_SIZE()
    {
        /**In Kilobytes*/
        return 2 * 1024 * 1024;
    }

    public static function GET_REFERRAL_PAID_FREE_MONTHS()
    {
        return 1;
    }

    public static function GET_ADMIN_REFERRAL_GIVEAWAY_MONTHS()
    {
        return [
            '1' => '1 Month',
            '2' => '2 Months',
            '3' => '3 Months',
            '4' => '4 Months'
        ];
    }

    public static function GET_INVOICE_RECURRING_PERIODS()
    {
        return [
            'day' => 'Day(s)',
            'month' => 'Month(s)',
            'year' => 'Year(s)'
        ];
    }

    public static function GET_ADMIN_USER_STATUSES()
    {
        return [
            'no_interaction' => 'No interaction',
            'called' => 'Called',
            'pending' => 'Pending',
            'demo_booked' => 'Demo booked',
            'subscribed' => 'Subscribed',
            'cancelled' => 'Cancelled'
        ];
    }

    public static function GET_ACCOUNT_HOLDER_TYPES()
    {
        return [
            'individual' => 'Individual',
            'business' => 'Business'
        ];
    }

    public static function GET_TWILIO_AUSTRALIA_AREA_CODES_LIST()
    {
        return [
            '2' => 'New South Wales, Australian Capital Territory',
            '3' => 'Victoria, Tasmania',
            '4' => 'Australia-wide',
            '7' => 'Queensland',
            '8' => 'Western Australia, South Australia, Northern Territory'
        ];
    }
}
