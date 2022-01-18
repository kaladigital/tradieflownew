<?php

namespace App\Http\Controllers;
use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Models\CallHistory;
use App\Models\ClientPhone;
use App\Models\UserNotification;
use App\Models\UserTwilioPhone;
use Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return redirect('settings/account');
    }

    public function getCallFlowToken()
    {
        $auth_user = Auth::user();

        try{
            $twilioAccountSid = env('TWILIO_ACCOUNT_SID');
            $twilioApiKey = env('TWILIO_DESKTOP_API_KEY'); //dekstop
            $twilioApiSecret = env('TWILIO_DESKTOP_API_SECRET'); //desktop
            $outgoingApplicationSid = env('TWILIO_DESKTOP_OUTGOING_APPLICATION_ID'); //desktop and mobile
            $identity = $auth_user->twilio_company_unique_name;

            $token = new \Twilio\Jwt\AccessToken(
                $twilioAccountSid,
                $twilioApiKey,
                $twilioApiSecret,
                3600 * 24,
                $identity
            );

            // Create Voice grant
            $voiceGrant = new \Twilio\Jwt\Grants\VoiceGrant();
            $voiceGrant->setOutgoingApplicationSid($outgoingApplicationSid);

            // Optional: add to allow incoming calls
            $voiceGrant->setIncomingAllow(true);

            // Add grant to token
            $token->addGrant($voiceGrant);

            // render token to string
            $token = $token->toJWT();
        }
        catch (\Exception $e) {
            $token = null;
        }

        return response()->json([
            'status' => true,
            'token' => $token
        ]);
    }

    public function getNotifications(Request $request)
    {
        $auth_user = request()->user();
        $get_notifications = Helper::getNotificationItems($auth_user);
        return response()->json([
            'status' => true,
            'notifications' => $get_notifications['unread_notifications'],
            'has_more_items' => $get_notifications['has_more_items']
        ]);
    }

    public function makeCall(Request $request)
    {
        $twilio = new \Twilio\Rest\Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
        $call = $twilio->calls
            ->create("+37477230525", // to
                "+17606217519", // from
                [
                    "url" => "http://demo.twilio.com/docs/voice.xml"
                ]
            );

        return response()->json([
            'status' => true,
            'sid' => $call->sid
        ]);

//        var_dump($call->sid);die;
    }

    public function incomingCall(Request $request)
    {
        $user = request()->user();
        $client_phone = ClientPhone::with('Client.ClientLastValue')
            ->select('client_phone.*')
            ->leftJoin('client as c', 'c.client_id', '=', 'client_phone.client_id')
            ->where('c.user_id', '=', $user->user_id)
            ->where('client_phone.phone', '=', $request['from'])
            ->first();

        $client_details = [];
        if ($client_phone) {
            $client_statuses = Constant::GET_EVENT_STATUSES_LIST();
            $client_details = [
                'client_id' => $client_phone->client_id,
                'name' => $client_phone->Client->name.($client_phone->Client->company ? ' ('.$client_phone->Client->company.')' : ''),
                'value' => $client_phone->Client->ClientLastValue ? ($client_phone->Client->ClientLastValue->ongoing_value ? $client_phone->Client->ClientLastValue->ongoing_value : $client_phone->Client->ClientLastValue->upfront_value) : '',
                'status' => $client_phone->Client->status,
                'status_label' => (isset($client_statuses[$client_phone->Client->status])) ? $client_statuses[$client_phone->Client->status] : '',
            ];
        }

        /**Add History*/
//        $model = new CallHistory();
//        $model->client_id = ($client_phone) ? $client_phone->client_id : null;
//        $model->phone = $request['from'];
//        $model->type = 'incoming';
//        $model->twilio_call_id = $request['call_sid'];
//        $model->save();

        return response()->json([
            'status' => true,
            'client' => $client_details
        ]);
    }

    public function callStatusTrack(Request $request)
    {
        if (array_key_exists($request['type'],Constant::GET_CALL_HISTORY_TYPES())) {
            $auth_user = request()->user();
            $has_recorded = CallHistory::where('twilio_call_id','=',$request['call_sid'])->first();
            if (!$has_recorded) {
                $twilio = new \Twilio\Rest\Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));

                $call = $twilio->calls($request['call_sid'])
                    ->fetch();

                if ($call) {
                    $client_id = null;
                    $allow_track_history = false;
                    $user_twilio_phone = UserTwilioPhone::where('user_id','=',$auth_user->user_id)->first();
                    if ($request['type'] == 'missed') {

                        if ($user_twilio_phone->phone == $call->to || 'client:'.$auth_user->twilio_company_unique_name == $call->to) {
                            $client_phone = ClientPhone::select('*')
                                ->leftJoin('client','client.client_id','=','client_phone.client_id')
                                ->where('client_phone.phone','=',$request['from_number'])
                                ->where('client.user_id','=',$auth_user->user_id)
                                ->first();

                            $client_id = $client_phone ? $client_phone->client_id : null;
                            $allow_track_history = true;
                        }
                    }

                    if ($allow_track_history) {
                        $model = new CallHistory();
                        $model->user_id = $auth_user->user_id;
                        $model->client_id = $client_id;
                        $model->phone = $request['from_number'];
                        $model->type = $request['type'];
                        $model->twilio_call_id = $request['call_sid'];
                        $model->save();
                    }
                }
            }
        }

        return response()->json([
            'status' => true
        ]);
    }
}
