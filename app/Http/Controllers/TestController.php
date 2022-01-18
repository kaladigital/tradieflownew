<?php

namespace App\Http\Controllers;


use App\Console\Commands\ActiveCampaignQueueEngine;
use App\Console\Commands\EmailQueueProcessEngine;
use App\Console\Commands\EventSMSReminderEngine;
use App\Console\Commands\InvoiceStripeWiseTransferEngine;
use App\Console\Commands\InvoiceWisePayoutTransferEngine;
use App\Console\Commands\RemoveTempQuoteImages;
use App\Console\Commands\SitemapGeneratorEngine;
use App\Console\Commands\StripeUpdateSubscriptionPrices;
use App\Console\Commands\SubscriptionExpireMessageEngine;
use App\Console\Commands\SubscriptionHandleEngine;
use App\Console\Commands\TrialExpireBeforeOneDayAdminAlertEngine;
use App\Console\Commands\TrialExpireNotificationsEngine;
use App\Console\Commands\TwilioAccessTokenExpiryEngine;
use App\Console\Commands\TwilioAuPhoneRegionAvailableEngine;
use App\Console\Commands\UserDeveloperInviteEmailEngine;
use App\Console\Commands\UserFormQueueProcessEngine;
use App\Console\Commands\XeroInvoicePaidStatusEngine;
use App\Helpers\ActiveCampaignHelper;
use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Helpers\NotificationHelper;
use App\Helpers\WiseHelper;
use App\Helpers\XeroHelper;
use App\Models\CallHistory;
use App\Models\Client;
use App\Models\ClientPhone;
use App\Models\ClientValue;
use App\Models\Country;
use App\Models\Event;
use App\Models\Industry;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\TextMessage;
use App\Models\TextMessageMedia;
use App\Models\User;
use App\Models\UserBusinessType;
use App\Models\UserForm;
use App\Models\UserFormData;
use App\Models\UserGoogleToken;
use App\Models\UserIndustry;
use App\Models\UserOnboarding;
use App\Models\UserReferralCode;
use App\Models\UserRegisterQueue;
use App\Models\UserSubscription;
use App\Models\UserTwilioPhone;
use App\Models\UserXeroAccount;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use DB;
use Dompdf\Dompdf;
use Session;
use function GuzzleHttp\Psr7\str;

class TestController extends Controller
{
    protected function decodeJWTRequest($string)
    {
        try {
            return JWT::decode($string, env('MOBILE_API_KEY'), array('HS256'));
        } catch (\Exception $e) {
            exit('wrong jwt encoding');
        }
    }

    public function htmlPdf()
    {
        return view('test.pdf_generator');
    }

    public function generatePdf(Request $request)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($request['content']);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream();
    }



    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
//        var_dump(env('STRIPE_USD_GST_PRODUCT_ID'));die;
//        $t = new ActiveCampaignQueueEngine();
//        $t->handle();die;
//        $wise_helper = new WiseHelper();
//        var_dump($wise_helper->getProfileID());die;
//
//        $t = new InvoiceWisePayoutTransferEngine();
//        $resp = $t->handle();
//        var_dump($resp);die;
//        $data = [
//            "sourceCurrency" => 'AUD',
//            "targetCurrency" => 'AUD',
//            "sourceAmount" => 100,
//            "profile" =>  $wise_helper->business_profile_id
//        ];
//
//        $data = $wise_helper->createQuote($data);
//        var_dump($data);die;
//
//        $quote_id = '565f1de4-486a-4757-8c59-6d128aa3a015';

//        $data = [
//            "currency" => "GBP",
//            "type" => "sort_code",
//            "profile" => $wise_helper->business_profile_id,
//            "accountHolderName" => "Anna Johnson",
//            "legalType" => "PRIVATE",
//            "details" => [
//                "sortCode" => "231470",
//                "accountNumber" => "28821822"
//            ]
//        ];
//
//        var_dump($wise_helper->createRecipient($data));die;

        $recipient = '148145721';

//        $data = [
//            "targetAccount" => $recipient,
//            "quoteUuid" => $quote_id,
//            "customerTransactionId" => '123e4567-e89b-12d3-a456-426614174011',
//            "details" => [
//                "reference" => "to my friend",
//                "transferPurpose" => "verification.transfers.purpose.pay.bills",
//                "sourceOfFunds" => "verification.source.of.funds.other"
//            ]
//        ];
//
//        var_dump($wise_helper->createTransfer($data));
//
//        $transfer_id = '50325779';
//
//        $data = [
//            'type' => 'BALANCE'
//        ];
//
//        var_dump($wise_helper->fundTransfer($transfer_id, $data));

        //balanceTransactionId

        //createRecipient

//        var_dump($data);die;
//        $t = new InvoiceStripeWiseTransferEngine();
//        var_dump($t->handle());die;
//        Helper::addActiveCampaignQueueItem('4','qido.js@gmail.com','trial_tag');
//        die('queued');
//
//        $twilio = new \Twilio\Rest\Client(env("TWILIO_ACCOUNT_SID"), env("TWILIO_AUTH_TOKEN"));
//        $twilio_country_availability = Constant::GET_TWILIO_COUNTRY_AVAILABLE_FILTERS();
//        $local = $twilio->availablePhoneNumbers('AU')
//            ->mobile
//            ->read([
//                "contains" => "4********"
//            ], 1);
//            var_dump($local['0']->toArray());die;

//        $t = new TrialExpireBeforeOneDayAdminAlertEngine();
//        var_dump($t->handle());die;
//        $status = NotificationHelper::sendTradieDigitalReviewSignupComplete('1234', $expiry_date_format, 'Get Fast, Get Organized', 'cool_php@mail.ru');
//        var_dump($status);die;
//        die('sent');

//        $client = new \Google_Client();
//        $client->setAuthConfig(base_path('google_credentials.json'));
//        $client->addScope([
//            'https://www.googleapis.com/auth/calendar',
//            'https://www.googleapis.com/auth/calendar.events',
//            'https://www.googleapis.com/auth/contacts',
//            'https://mail.google.com'
//        ]);
//        $client->setRedirectUri(env('APP_URL').'/test/google');
//        $client->setAccessType('offline');
//        $client->setPrompt("consent");
//        $client->setIncludeGrantedScopes(true);   // incremental auth
//        $auth_url = $client->createAuthUrl();
//        return redirect($auth_url);
//
//        $google_token = UserGoogleToken::first();
//
//        $client = new \Google_Client();
//        $client->setAuthConfig(base_path('google_credentials.json'));
//        $client->addScope([
//            'https://www.googleapis.com/auth/calendar',
//            'https://www.googleapis.com/auth/calendar.events',
//            'https://www.googleapis.com/auth/contacts',
//            'https://mail.google.com'
//        ]);
//
//        $client->setRedirectUri(env('APP_URL').'/test/google');
//        $client->setAccessType('offline');
//        $client->setPrompt("consent");
//        $client->setIncludeGrantedScopes(true);   // incremental auth
////
//        $get_access_token = $client->fetchAccessTokenWithRefreshToken($google_token->refresh_token);
////        var_dump($get_access_token);die;
//
//        $service = new \Google_Service_Gmail($client);
//
//        /**Get Single Thread*/
//        $threads = $service->users_threads->get('me', '179c9db5f9f4e4f6');
//        foreach ($threads as $item) {
//            $messagePayload = $item->getPayload();
//
//            $parts = $messagePayload->getParts();
//
//            foreach ($parts as $p_item) {
//
//                foreach ($p_item as $p) {
//                    if ($p->getBody()->data) {
//                        $message = base64_decode($p->getBody()->data);
//                        echo $message.' <br><br>';
//                    }
//                }
//
////                echo $message.'<br><br>';
//            }
//
////            var_dump($item->getPayload());die;
////            $date_obj = Carbon::createFromTimestamp($item->internalDate);
////            echo $date_obj->format('Y-m-d H:i:s').' '.$item->snippet.'<br><br>';
//        }
//        var_dump(count($thread));die;
//die('1234');
//        $optParams = [];
//        $optParams['maxResults'] = 10; // Return Only 5 Messages
//        $optParams['labelIds'] = 'INBOX'; // Only show messages in Inbox
////        $optParams['pageToken'] = '14699020300933339166';
////        $threads->nextPageToken
//        $threads = $service->users_threads->listUsersThreads('me', $optParams);
////        var_dump($threads->resultSizeEstimate);die;
////        foreach ($threads as $item){
////            echo $item->id.'__'.$item->historyId.'__'.$item->snippet.'<br><br><br><br>';
////        }
//
//        die;
////
////        $optParams = [];
////        $optParams['maxResults'] = 2; // Return Only 5 Messages
////        $optParams['labelIds'] = 'INBOX'; // Only show messages in Inbox
////        $messages = $service->users_messages->listUsersMessages('me',$optParams);
////        $list = $messages->getMessages();
////
////        $message_id = $list['0']->getId();
////
////        $optParamsGet = [];
////        $optParamsGet['format'] = 'full'; // Display message in payload
////        $message = $service->users_messages->get('me',$message_id,$optParamsGet);
////        $messagePayload = $message->getPayload();
//////        var_dump($messagePayload->getBody());die;
////        $headers = $messagePayload->getHeaders();
////        $parts = $messagePayload->getParts();
////
////        foreach ($parts as $item) {
////            $message = base64_decode($parts['0']->getBody()->data);
////            echo $message.'<br><br>';
////        }

//        die;


//        var_dump(base64_decode($message));die;

//        var_dump(count($list));die;


//        var_dump($service->users);die;

//        $user = 'me';
//        $results = $service->users_labels->listUsersLabels($user);
//
//        var_dump($results);die;


        /**Contacts API*/
//        $people_service = new \Google_Service_PeopleService($client);
        /**Find By Email*/
//        $res = $people_service->people->searchContacts([
//            'query' => 'cool_php@mail.ru',
//            'readMask' => 'names,emailAddresses,addresses'
//        ]);
//        $total_found = count($res->results);

        /**Get All*/
//        $people = $people_service->people_connections->listPeopleConnections(
//            'people/me', array('personFields' => 'names,emailAddresses,addresses,clientData,organizations'));
////
//        foreach ($people as $item) {
//            //$item->names
//            if (isset($item->emailAddresses['0']) && $item->emailAddresses['0']->value == 'cool_php@mail.ru') {
////                var_dump($item->resourceName);die;
//
//            }
//        }

//        die('1');

//        $people_obj = new \Google_Service_PeopleService_Person();
//        $name1 = new \Google_Service_People_Name();
//        $name1->displayName = 'John Doe';
//        $name1->familyName = 'Doe';
//        $name1->givenName = 'John';
//
//        $people_obj->setNames($name1);
//
//        $people_email = new \Google_Service_People_EmailAddress();
//        $people_email->value = 'cool_php@mail.ru';
//        $people_obj->setEmailAddresses([$people_email]);
//
//        $orgs = new \Google_Service_People_Organization();
//        $orgs->title = 'Codebonapp';
//        $people_obj->setOrganizations([$orgs]);

//
//        $contact_api = $people_service->people->createContact($people_obj);
          //resourceName
//        var_dump($contact_api);die;
//        die('not found');

//        var_dump($people);die;



//        /**Calendar & Events*/
//        $service = new \Google_Service_Calendar($client);

        // Print the next 10 events on the user's calendar.
//        $calendarId = 'primary';
//        $optParams = array(
//            'maxResults' => 10,
//            'orderBy' => 'startTime',
//            'singleEvents' => true,
//            'timeMin' => Carbon::createFromFormat('Y-m-d H:i:s','2021-06-18 00:00:00')->format('c'),
//            'timeMax' => Carbon::createFromFormat('Y-m-d H:i:s','2021-06-18 23:59:00')->format('c'),
//        );
//        $results = $service->events->listEvents($calendarId, $optParams);
//        $events = $results->getItems();

//        foreach ($events as $item) {
////            var_dump($item->getStart());die;
//            echo $item->getSummary()."<br>";
//////            $event_id = $item->getId();
//////            var_dump($event_id);die;
////            //name $item->getSummary()
//////            var_dump($item->status, $item->getSummary(), $item->getDescription() ? $item->getDescription() : ' no description');
//////            var_dump($item->getSummary().' '.$item->getId());
//        }
////
//        die;

//
//        $event = new \Google_Service_Calendar_Event(array(
//            'summary' => 'Best Meeting some test test test 1234',
//            'description' => 'no desc',
//            'start' => [
//                'dateTime' => '2021-06-18T09:00:00-07:00',
////                'timeZone' => 'America/Los_Angeles',
//            ],
//            'end' => [
//                'dateTime' => '2021-06-18T09:00:00-07:00',
////                'timeZone' => 'America/Los_Angeles',
//            ],
////            'end' =>   Carbon::now()->format('c'),
//        ));
//
//        $calendarId = 'primary';
//        $event = $service->events->insert($calendarId, $event);
//        var_dump($event);die;
//
//
////        $event = $service->events->get('primary', '52qina3iq30484ufefktu9nugu');
////        $event->setSummary('Another cool title here');
////        $updatedEvent = $service->events->update('primary', $event->getId(), $event);
////        die('done');


//        die;
//        var_dump($get_access_token['access_token'],$events);die;
    }

    public function google(Request $request)
    {
        $client = new \Google_Client();
        $client->setAuthConfig(base_path('google_credentials.json'));
        $client->addScope([
            'https://www.googleapis.com/auth/calendar',
            'https://www.googleapis.com/auth/calendar.events',
            'https://www.googleapis.com/auth/contacts',
            'https://mail.google.com'
        ]);
        $client->setRedirectUri(env('APP_URL').'/test/google');
        $client->setAccessType('offline');
        $client->setPrompt("consent");
//        $client->setApprovalPrompt("force");
//        $client->setAccessType('offline');
//        $client->setIncludeGrantedScopes(true);   // incremental auth
        $client->authenticate($request['code']);
        $token_obj = $client->getAccessToken();
        $user_token = UserGoogleToken::where('user_id','=','4')
            ->first();

        if (!$user_token) {
            $user_token = new UserGoogleToken();
            $user_token->user_id = '4';
        }

        $user_token->access_token = $token_obj['access_token'];
        $user_token->refresh_token = $token_obj['refresh_token'];
        $user_token->save();
        die('done');
    }
}
