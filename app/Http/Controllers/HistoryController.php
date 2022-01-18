<?php

namespace App\Http\Controllers;
use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Models\CallHistory;
use App\Models\Client;
use App\Models\ClientPhone;
use App\Models\Country;
use App\Models\UserFormData;
use App\Models\UserTwilioPhone;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Session;

class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('active_subscription');
    }

    public function index(Request $request)
    {
        $auth_user = Auth::user();
        if ($request['type'] && !in_array($request['type'], ['missed', 'leads', 'forms'])) {
            return redirect('history');
        }

        $missed_no_client_call_history = CallHistory::selectRaw('count(call_history.call_history_id) as total')
            ->leftJoin('client as c','c.client_id','=','call_history.client_id')
            ->where('call_history.user_id', '=', $auth_user->user_id)
            ->where('call_history.type', '=', 'missed')
            ->whereNull('c.client_id');

        $lead_call_history = CallHistory::selectRaw('count(call_history.call_history_id) as total')
            ->leftJoin('client as c','c.client_id','=','call_history.client_id')
            ->where('call_history.user_id', '=', $auth_user->user_id)
            ->whereNotNull('c.client_id');

        $missed_user_form_total = UserFormData::selectRaw('count(user_form_data.user_form_data_id) as total')
            ->where('user_form_data.user_id', '=', $auth_user->user_id);

        $other_no_client_total = CallHistory::selectRaw('count(call_history.call_history_id) as total')
            ->leftJoin('client as c','c.client_id','=','call_history.client_id')
            ->where('call_history.user_id', '=', $auth_user->user_id)
            ->whereIn('call_history.type',['outgoing','incoming'])
            ->whereNull('c.client_id');

        $totals = $missed_no_client_call_history
            ->unionAll($lead_call_history)
            ->unionAll($missed_user_form_total)
            ->unionAll($other_no_client_total)
            ->get();

        $missed_query = CallHistory::selectRaw('call_history.call_history_id as item_id')
            ->selectRaw('"missed" as type')
            ->selectRaw('"" as name')
            ->selectRaw('call_history.phone as phone')
            ->selectRaw('"" as upfront_value')
            ->selectRaw('"" as ongoing_value')
            ->selectRaw('"" as company')
            ->selectRaw('call_history.type as history_type')
            ->selectRaw('call_history.created_at as created_at')
            ->selectRaw('c.client_id as client_id')
            ->leftJoin('client as c','c.client_id','=','call_history.client_id')
            ->where('call_history.user_id', '=', $auth_user->user_id)
            ->where('call_history.type', '=', 'missed')
            ->whereNull('c.client_id');

        $leads_query = CallHistory::selectRaw('call_history.call_history_id as item_id')
            ->selectRaw('"leads" as type')
            ->selectRaw('c.name as name')
            ->selectRaw('call_history.phone as phone')
            ->selectRaw('client_value.upfront_value as upfront_value')
            ->selectRaw('client_value.ongoing_value as ongoing_value')
            ->selectRaw('c.company as company')
            ->selectRaw('call_history.type as history_type')
            ->selectRaw('call_history.created_at as created_at')
            ->selectRaw('c.client_id as client_id')
            ->leftJoin('client as c','c.client_id','=','call_history.client_id')
            ->leftJoin('client_value','client_value.client_id','=','c.client_id')
            ->where('call_history.user_id', '=', $auth_user->user_id)
            ->whereNotNull('c.client_id')
            ->groupBy('call_history.call_history_id');

        $forms_query = UserFormData::selectRaw('user_form_data.user_form_data_id as item_id')
            ->selectRaw('"forms" as type')
            ->selectRaw('case when c.client_id is not null then c.name else user_form_data.contact_name end as name')
            ->selectRaw('case when client_phone.client_phone_id is not null then client_phone.phone else user_form_data.contact_phone end as phone')
            ->selectRaw('client_value.upfront_value as upfront_value')
            ->selectRaw('client_value.upfront_value as ongoing_value')
            ->selectRaw('c.company as company')
            ->selectRaw('"forms" as history_type')
            ->selectRaw('user_form_data.created_at as created_at')
            ->selectRaw('c.client_id as client_id')
            ->leftJoin('client as c','c.client_id','=','user_form_data.client_id')
            ->leftJoin('client_value','client_value.client_id','=','c.client_id')
            ->leftJoin('client_phone','client_phone.client_id','=','c.client_id')
            ->where('user_form_data.user_id', '=', $auth_user->user_id)
            ->groupBy('user_form_data.user_form_data_id');

        switch ($request['type']){
            case 'missed':
                $items = $missed_query
                    ->orderBy('created_at','desc')
                    ->paginate(20);
            break;
            case 'leads':
                $items = $leads_query
                    ->orderBy('created_at','desc')
                    ->paginate(20);
            break;
            case 'forms':
                $items = $forms_query
                    ->paginate(20);
            break;
            default:
                $not_lead_other_calls = CallHistory::selectRaw('call_history.call_history_id as item_id')
                    ->selectRaw('"other" as type')
                    ->selectRaw('"" as name')
                    ->selectRaw('call_history.phone as phone')
                    ->selectRaw('"" as upfront_value')
                    ->selectRaw('"" as ongoing_value')
                    ->selectRaw('"" as company')
                    ->selectRaw('call_history.type as history_type')
                    ->selectRaw('call_history.created_at as created_at')
                    ->selectRaw('c.client_id as client_id')
                    ->leftJoin('client as c','c.client_id','=','call_history.client_id')
                    ->where('call_history.user_id', '=', $auth_user->user_id)
                    ->whereIn('call_history.type',['outgoing','incoming'])
                    ->whereNull('c.client_id');

                $items = $missed_query
                    ->unionAll($leads_query)
                    ->unionAll($forms_query)
                    ->unionAll($not_lead_other_calls)
                    ->orderBy('created_at','desc')
                    ->paginate(20);
        }

        $today_date_format = Carbon::now()->format('Y-m-d');
        $event_types = Constant::GET_ALL_EVENT_TYPES();
        unset($event_types['other']);
        $user_twilio_phone = UserTwilioPhone::where('user_id','=',$auth_user->user_id)->first();
        $dial_countries = Country::where('is_twilio','=','1')->pluck('number','code');
        $client_statuses = Constant::GET_CLIENT_STATUS_LIST();
        return view('history.index',compact(
            'auth_user',
            'totals',
            'request',
            'items',
            'today_date_format',
            'event_types',
            'user_twilio_phone',
            'dial_countries',
            'client_statuses'
        ));
    }

    public function details(Request $request)
    {
        $auth_user = request()->user();
        $response_data = [
            'client_id' => null,
            'phone' => null,
            'recorded_audio' => null,
            'recorded_audio_playtime' => null,
            'name' => null,
            'company' => null,
            'upfront_value' => null,
            'ongoing_value' => null,
            'client_status' => null,
            'client_status_label' => null,
            'has_email' => false,
            'form_data_url' => null,
            'form_data' => null
        ];

        $record_found = false;
        $client = [];
        if ($request['type'] == 'forms') {
            $user_form_data = UserFormData::with('Client.ClientPhone','Client.ClientLastValue')
                ->where('user_id','=',$auth_user->user_id)
                ->find($request['id']);

            if ($user_form_data) {
                if ($user_form_data->Client) {
                    $client = $user_form_data->Client;
                }
                else{
                    $response_data['phone'] = $user_form_data->contact_phone;
                    $response_data['name'] = $user_form_data->contact_name;
                    $response_data['has_email'] = false;
                    $response_data['has_email'] = $user_form_data->email ? true : false;
                }
                $response_data['form_data_url'] = $user_form_data->url;
                $response_data['form_data'] = $user_form_data->contact_response;
            }

            $record_found = true;
        }
        else{
            $call_history = CallHistory::with('Client.ClientLastValue')
                ->where('user_id','=',$auth_user->user_id)
                ->find($request['id']);

            if ($call_history) {
                $response_data['phone'] = $call_history->phone;

                if ($call_history->Client) {
                    $client = $call_history->Client;
                }

                $record_found = true;
            }
        }

        if ($record_found) {
            if ($client) {
                $response_data['client_id'] = $client->client_id;
                $response_data['name'] = $client->name;
                $response_data['company'] = $client->company;
                if ($client->ClientLastValue) {
                    $response_data['upfront_value'] = $client->ClientLastValue->upfront_value;
                    $response_data['ongoing_value'] = $client->ClientLastValue->ongoing_value;
                }

                $statuses = Constant::GET_CLIENT_STATUS_LIST();
                $response_data['client_status'] = $client->status;
                $response_data['client_status_label'] = isset($statuses[$client->status]) ? $statuses[$client->status] : $client->status;
                $response_data['has_email'] = $client->email ? true : false;
            }
            else{
                $response_data['client_status'] = 'not-listed';
                $response_data['client_status_label'] = 'Not Listed';
            }

            /**Call History*/
            $call_history = Helper::getPhoneCallHistory($auth_user, $response_data['phone'],$response_data['client_id'],$request['page']);

            return response()->json([
                'status' => true,
                'client' => $response_data,
                'call_history' => $call_history
            ]);
        }

        return response()->json([
            'status' => false,
            'error' => 'Something went wrong'
        ]);
    }

    public function downloadRecording($id)
    {
        $auth_user = request()->user();
        $call_history = CallHistory::with('Client.ClientLastValue')
            ->where('user_id','=',$auth_user->user_id)
            ->find($id);

        if ($call_history && $call_history->recorded_audio_file && Storage::disk('records')->exists($call_history->recorded_audio_file)) {
            return response()->download(public_path('records/'.$call_history->recorded_audio_file), 'Record_'.$call_history->created_at->format('m_d_Y').'.mp3');
        }

        return redirect()
            ->back();
    }

    public function searchClients(Request $request)
    {
        $auth_user = request()->user();
        $clients = ClientPhone::select('client.client_id','client.name','client_phone.phone')
            ->leftJoin('client','client.client_id','=','client_phone.client_id')
            ->where('client.user_id','=',$auth_user->user_id);

        if ($request['term']) {
            $clients->where(function($query) use ($request){
                $query
                    ->where('client.name','like','%'.$request['term'].'%')
                    ->orWhere('client_phone.phone','like','%'.$request['term'].'%');
            });
        }

        $clients = $clients
            ->orderBy('name','asc')
            ->take(25)
            ->get();

        return response()->json([
            'status' => true,
            'clients' => $clients->toArray()
        ]);
    }

    public function preCall($id, $type)
    {
        $auth_user = request()->user();
        $client = [];
        if ($type == 'forms') {
            $user_form_data = UserFormData::with('Client')->where('user_id','=',$auth_user->user_id)->find($id);
            if ($user_form_data && $user_form_data->Client) {
                $client = $user_form_data->Client;
                $client->phone = $user_form_data->contact_phone;
            }
        }
        else{
            $call_history = CallHistory::where('user_id','=',$auth_user->user_id)->find($id);
            if ($call_history && $call_history->Client) {
                $client = $call_history->Client;
                $client->phone = $call_history->contact_phone;
            }
        }

        if ($client && $client->phone) {
            $client_name = $client->name;
            if ($client->company) {
                $client_name .= '('.$client->company.')';
            }

            Session::put('client_outgoing_call_details',[
                'client_id' => $client->client_id,
                'client_name' => $client_name,
                'phone' => $call_history->phone,
                'phone_format' => null
            ]);
            Session::save();
            return redirect('client/'.$call_history->client_id);
        }

        return redirect('history');
    }

    public static function dialCallHistoryTrack(Request $request)
    {
        $user = request()->user();
        Helper::trackOutgoingCall($user, $request['phone'], $request['client_id'], $request['twilio_call_id']);
        return response()->json([
            'status' => true
        ]);
    }
}
