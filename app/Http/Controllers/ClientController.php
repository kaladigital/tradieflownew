<?php

namespace App\Http\Controllers;
use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Http\Requests\ClientRequest;
use App\Models\CallHistory;
use App\Models\Client;
use App\Models\ClientHistory;
use App\Models\ClientLocation;
use App\Models\ClientNote;
use App\Models\ClientPhone;
use App\Models\ClientValue;
use App\Models\Country;
use App\Models\Event;
use App\Models\EventLocation;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\TextMessage;
use App\Models\UserForm;
use App\Models\UserFormData;
use App\Models\UserTask;
use App\Models\UserTwilioPhone;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('active_subscription');
    }

    public function index(Request $request)
    {
        $auth_user = request()->user();
        if ($request['mode'] == 'funnel') {
            $clients = [];
        }
        else{
            $clients = Client::with('ClientLastValue')
                ->select([
                    'client.client_id',
                    'client.name',
                    'client.status',
                    'client.company',
                    'client.source_type',
                    'client.source_text',
                    'call_history.call_history_id',
                    'call_history.recorded_audio_file',
                    'call_history.recorded_playtime_format'
                ])
                ->leftJoin('client_value as cv','cv.client_id','=','client.client_id')
                ->leftJoin('call_history',function($query){
                    $query
                        ->on('call_history.client_id','=','client.client_id')
                        ->whereNotNull('call_history.recorded_audio_file');
                })
                ->where('client.user_id','=',$auth_user->user_id);

            /**Check Status*/
            if ($request['status'] && array_key_exists($request['status'],Constant::GET_CLIENT_STATUS_LIST())) {
                $clients->where('client.status','=',$request['status']);
            }

            /**Check Values*/
            if ($request['from_value']) {
                if ($request['recurring']) {
                    if ($request['recurring'] == 'upfront') {
                        $clients->where('cv.upfront_value','>=',$request['from_value']);
                    }
                    else{
                        $clients->where('cv.ongoing_value','>=',$request['from_value']);
                    }
                }
                else{
                    $clients
                        ->where(function($query) use ($request){
                            $query->where('cv.upfront_value','>=',$request['from_value'])
                                ->orWhere('cv.ongoing_value','>=',$request['from_value']);
                        });
                }
            }

            if ($request['to_value']) {
                if ($request['recurring']) {
                    if ($request['recurring'] == 'upfront') {
                        $clients->where('cv.upfront_value', '<=', $request['to_value']);
                    }
                    else{
                        $clients->where('cv.ongoing_value','<=',$request['to_value']);
                    }
                }
                else {
                    $clients
                        ->where(function ($query) use ($request) {
                            $query->where('cv.upfront_value', '<=', $request['to_value'])
                                ->orWhere('cv.ongoing_value', '<=', $request['to_value']);
                        });
                }
            }

            /**Check Types*/
            if ($request['type']) {
                if ($request['type'] == 'individual') {
                    $clients->whereRaw('LENGTH(client.company) = 0');
                }
                elseif($request['type'] == 'company') {
                    $clients->whereNotNull('client.company');
                }
            }

            /**Check Value Type*/
            if ($request['recurring']) {
                if ($request['recurring'] == 'upfront') {
                    $clients->where('cv.upfront_value','>','0');
                }
                elseif($request['recurring'] == 'recurring') {
                    $clients->where('cv.ongoing_value','>','0');
                }
            }

            switch ($request['sort_by']) {
                case 'lead_asc':
                    $clients->orderBy('client.name');
                break;
                case 'lead_desc':
                    $clients->orderBy('client.name','desc');
                break;
                case 'value_asc':
                    if ($request['recurring']) {
                        if ($request['recurring'] == 'upfront') {
                            $clients
                                ->orderBy('cv.upfront_value')
                                ->orderBy('cv.ongoing_value');
                        }
                        else{
                            $clients
                                ->orderBy('cv.ongoing_value')
                                ->orderBy('cv.upfront_value');
                        }
                    }
                    else{
                        $clients
                            ->orderBy('cv.upfront_value')
                            ->orderBy('cv.ongoing_value');
                    }
                break;
                case 'value_desc':
                    if ($request['recurring']) {
                        if ($request['recurring'] == 'upfront') {
                            $clients
                                ->orderBy('cv.upfront_value','desc')
                                ->orderBy('cv.ongoing_value','desc');
                        }
                        else{
                            $clients
                                ->orderBy('cv.ongoing_value','desc')
                                ->orderBy('cv.upfront_value','desc');
                        }
                    }
                    else{
                        $clients
                            ->orderBy('cv.upfront_value','desc')
                            ->orderBy('cv.ongoing_value','desc');
                    }
                break;
                case 'source_asc':
                    $clients->orderBy('client.source_type');
                break;
                case 'source_desc':
                    $clients->orderBy('client.source_type','desc');
                break;
                case 'page_asc':
                    $clients->orderBy('client.source_text');
                break;
                case 'page_desc':
                    $clients->orderBy('client.source_text','desc');
                break;
                case 'status_asc':
                    $clients->orderBy('client.status');
                break;
                case 'status_desc':
                    $clients->orderBy('client.status','desc');
                break;
                case 'duration_asc':
                    $clients->orderBy('call_history.recorded_playtime_seconds');
                break;
                case 'duration_desc':
                    $clients->orderBy('call_history.recorded_playtime_seconds','desc');
                break;
                default:
                    $clients->orderBy('client.created_at','desc');
                break;
            }

            $clients = $clients
                ->groupBy('client.client_id')
                ->paginate(10);

            if ($request['page'] && $request['page'] > 1 && !$clients->count()) {
                $request_params = $request->all();
                $request_params['page'] -= 1;
                return redirect('client?'.http_build_query($request_params));
            }
        }

        $view_mode = ($request['mode'] && $request['mode'] == 'funnel') ? 'funnel' : 'list';
        $client_statuses = Constant::GET_CLIENT_STATUS_LIST();
        $user_twilio_phone = UserTwilioPhone::where('user_id','=',$auth_user->user_id)->first();
        $phone_countries = Country::select('number','code')
            ->where('is_twilio','=','1')
            ->pluck('number','code');
        return view('client.index',compact(
            'auth_user',
            'clients',
            'client_statuses',
            'request',
            'view_mode',
            'phone_countries',
            'user_twilio_phone'
        ));
    }

    public function updateStatus(Request $request)
    {
        $auth_user = request()->user();
        $client = Client::with('ClientLastValue')
            ->where('user_id','=',$auth_user->user_id)->find($request['id']);
        if ($client && array_key_exists($request['status'],Constant::GET_CLIENT_STATUS_LIST())) {
            $client->status = $request['status'];
            $client->update();

            if ($client->ClientLastValue) {
                $old_status = $client->ClientLastValue->status;
                $client->ClientLastValue->status = $client->status;
                if (!$client->ClientLastValue->unique_code) {
                    $client->ClientLastValue->unique_code = md5($client->ClientLastValue->client_value_id.uniqid().rand(1,100));
                }

                $client->ClientLastValue->update();

                if ($request['status'] == 'completed' && $client->email && $old_status !== $request['status']) {
                    Helper::queueLeaveReviewRequest($client->email, 'email', $client->User, $client->ClientLastValue->unique_code);
                }
            }
        }

        return response()->json([
            'status' => true
        ]);
    }

    public function destroy(Request $request)
    {
        $auth_user = request()->user();
        $clients = Client::where('user_id','=',$auth_user->user_id)
            ->whereIn('client_id',$request['client_ids'])
            ->get();

        foreach ($clients as $item) {
            ClientLocation::where('client_id','=',$item)->delete();
            ClientPhone::where('client_id','=',$item)->delete();
            ClientValue::where('client_id','=',$item)->delete();

            Event::where('client_id','=',$item)->chunk(1000,function($items){
                foreach ($items as $item) {
                    EventLocation::where('event_id','=',$item->event_id)->delete();
                    $item->delete();
                }
            });

            Invoice::where('client_id','=',$item)->chunk(1000,function($items){
                foreach ($items as $item) {
                    InvoiceItem::where('invoice_id','=',$item->invoice_id)->delete();
                    $item->delete();
                }
            });

            TextMessage::where('client_id','=',$item)
                ->update([
                    'client_id' => null
                ]);

            UserTask::where('client_id','=',$item)->delete();
            CallHistory::where('client_id','=',$item)->delete();
            $item->delete();
        }

        return response()->json([
            'status' => true
        ]);
    }

    public function store(Request $request)
    {
        if (!$request['name'] || !$request['phone']) {
            return response()->json([
                'status' => false,
                'error' => 'Please specify name and phone for the client'
            ]);
        }

        if (strlen($request['upfront_value']) && $request['upfront_value'] < 0) {
            return response()->json([
                'status' => false,
                'error' => 'Please specify valid upfront amount'
            ]);
        }

        if (strlen($request['ongoing_value']) && $request['ongoing_value'] < 0) {
            return response()->json([
                'status' => false,
                'error' => 'Please specify valid ongoing amount'
            ]);
        }

        if (!array_key_exists($request['status'],Constant::GET_CLIENT_STATUS_LIST())) {
            return response()->json([
                'status' => false,
                'error' => 'Client stage is not valid'
            ]);
        }

        if ($request['email'] && !filter_var($request['email'],FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'status' => false,
                'error' => 'Please specify valid email'
            ]);
        }

        $get_client_phone_country = Country::where('is_twilio','=','1')
            ->where('code','=',$request['phone_country'])
            ->first();

        if (!$get_client_phone_country) {
            return response()->json([
                'status' => false,
                'error' => 'Phone country not supported'
            ]);
        }

        $auth_user = request()->user();
        $request['user_id'] = $auth_user->user_id;
        $client = Client::create($request->only('user_id','name','email','phone','company','email','status'));

        /**Add Client Phone*/
        $get_phone_format = Helper::convertPhoneToFormat($request['phone_country'], $request['phone']);
        if (!$get_phone_format) {
            return response()->json([
                'status' => false,
                'error' => 'Not supported phone number entered'
            ]);
        }

        $model = new ClientPhone();
        $model->client_id = $client->client_id;
        $model->phone = $get_client_phone_country->number.preg_replace('/[^0-9.]+/', '', $request['phone']);
        $model->phone_format = $get_phone_format;
        $model->country_code = $get_client_phone_country->code;
        $model->country_number = $get_client_phone_country->number;
        $model->save();

        /**Get Form Data*/
        $get_user_form_data = UserFormData::where('user_id','=',$auth_user->user_id)
            ->where('is_converted','=','0')
            ->where(function($query) use ($model){
                $query
                    ->where('contact_phone','=',$model->phone)
                    ->orWhere('phone_format','=',$model->phone_format);
            })
            ->whereNull('client_id')
            ->get();

        foreach ($get_user_form_data as $item) {
            $item->is_converted = '1';
            $item->client_id = $client->client_id;
            $item->update();

            /**Track History*/
            Helper::addClientActivity($client->client_id, $item->user_form_data_id, 'Form Received', null, null, null, 'form');
        }

        /**Add Client Value*/
        $model = new ClientValue();
        $model->client_id = $client->client_id;
        $model->status = $request['status'];
        $model->upfront_value = $request['upfront_value'] ? floatval($request['upfront_value']) : null;
        $model->ongoing_value = $request['ongoing_value'] ? floatval($request['ongoing_value']) : null;
        $model->unique_code = md5($client->client_id.$client->user_id.uniqid());
        $model->save();
        return response()->json([
            'status' => true
        ]);
    }

    public function loadFunnelData(Request $request)
    {
        $auth_user = request()->user();
        if (!array_key_exists($request['status'],Constant::GET_CLIENT_STATUS_LIST())) {
            return response()->json([
                'status' => false,
                'clients' => []
            ]);
        }

        $clients = Client::select([
            'client.client_id',
            'client.name',
            'client.status',
            'cv.upfront_value',
            'cv.ongoing_value'
        ])
        ->leftJoin('client_value as cv','cv.client_id','=','client.client_id')
        ->where('client.user_id','=',$auth_user->user_id)
        ->where('client.status','=',$request['status']);

        foreach ($request['filter'] as $item) {
            switch ($item['name']) {
                case 'from_value':
                    if ($item['value']) {
                        $clients
                            ->where(function ($query) use ($item) {
                                $query->where('cv.upfront_value', '>=', $item['value'])
                                    ->orWhere('cv.ongoing_value', '>=', $item['value']);
                            });
                    }
                break;
                case 'to_value':
                    if ($item['value']) {
                        $clients
                            ->where(function ($query) use ($item) {
                                $query
                                    ->where('cv.upfront_value', '<=', $item['value'])
                                    ->orWhere('cv.ongoing_value', '<=', $item['value']);
                            });
                    }
                break;
                case 'type':
                    if ($item['value'] == 'individual') {
                        $clients->whereNull('client.company');
                    }
                    elseif($item['value'] == 'company') {
                        $clients->whereNotNull('client.company');
                    }
                break;
                case 'recurring':
                    if ($item['value'] == 'upfront') {
                        $clients->where('cv.upfront_value','>','0');
                    }
                    elseif($item['value'] == 'recurring') {
                        $clients->where('cv.ongoing_value','>','0');
                    }
                break;
            }
        }

        $clients = $clients
            ->orderBy('client.created_at','desc')
            ->get();

        return response()->json([
            'status' => false,
            'clients' => $clients
        ]);
    }

    public function show($id)
    {
        $auth_user = request()->user();
        $client = Client::with('ClientValue','ClientLocation','ClientPhone')->where('user_id','=',$auth_user->user_id)->find($id);
        if (!$client) {
            return redirect('client');
        }

        $client_tasks = UserTask::where('user_id','=',$auth_user->user_id)
            ->where(function($query) use ($client){
                $query
                    ->whereNull('client_id')
                    ->orWhere('client_id','=',$client->client_id);
            })
            ->get();

        $history_page_limit = Constant::GET_CLIENT_PROFILE_HISTORY_ITEMS();
        $client_history = ClientHistory::select([
            'client_history.*',
            'call_history.recorded_audio_file',
            'call_history.recorded_playtime_format',
            'call_history.recorded_playtime_seconds',
            'event.event_id',
            'user_form_data.created_at as form_created_date',
            'user_form_data.user_form_data_id'
        ])
            ->selectRaw('date_format(call_history.created_at,"%a, %e %M") as call_start_date')
            ->selectRaw('date_format(call_history.created_at,"%H:%i") as call_start_time')
            ->selectRaw('date_format(date_add(call_history.created_at, interval `call_history`.`recorded_playtime_seconds` second),"%H:%i") as call_end_time')
            ->leftJoin('call_history','call_history.call_history_id','=','client_history.related_id')
            ->leftJoin('event','event.event_id','=','client_history.related_id')
            ->leftJoin('user_form_data','user_form_data.user_form_data_id','=','client_history.related_id')
            ->where('client_history.client_id','=',$client->client_id)
            ->orderBy('client_history.created_at','desc')
            ->take($history_page_limit + 1)
            ->get()
            ->toArray();

        $has_more_history = count($client_history) > $history_page_limit ? true : false;
        $client_history = $has_more_history ? array_slice($client_history,0,$history_page_limit) : $client_history;

        $client_statuses = Constant::GET_CLIENT_STATUS_LIST();
        $client_progress = Helper::calculateClientProgress($client->status);
        $total_earned = 0;
        $outstanding_payments = 0;
        $outstanding_payments_percentage = 0;
        if ($client->status == 'work-in-progress' || $client->status == 'completed') {
            $total_earned = Invoice::where('client_id','=',$client->client_id)
                ->whereIn('status',['sent-email','sent-text'])
                ->where('has_paid','=','1')
                ->sum('total_gross_amount');

            $total_sent = Invoice::where('client_id','=',$client->client_id)
                ->whereIn('status',['sent-email','sent-text'])
                ->sum('total_gross_amount');


            $outstanding_payments_percentage = ($total_earned && $total_sent) ? ceil($total_earned/$total_sent*100) : 0;
            $outstanding_payments = number_format($total_sent - $total_earned,2);
        }

        $today_day_format = Carbon::now()->format('M j');
        $event_types = Constant::GET_ALL_EVENT_TYPES();
        unset($event_types['other']);
        $phone_countries = Country::select('number','code')
            ->where('is_twilio','=','1')
            ->pluck('number','code');
        $view_name = ($auth_user->user_id == 6) ? 'show_demo' : 'show';
        $get_outgoing_call = Session::get('client_outgoing_call_details');
        $client_outgoing_call_details = null;
        $user_twilio_phone = UserTwilioPhone::where('user_id','=',$auth_user->user_id)->where('status','=','active')->first();
        if ($get_outgoing_call && $get_outgoing_call['client_id'] == $client->client_id) {
            Session::forget('client_outgoing_call_details');
            Session::save();
            $client_outgoing_call_details = [
                'to_phone_number' => $get_outgoing_call['phone'],
                'client_name' => $get_outgoing_call['client_name'],
                'client_id' => $get_outgoing_call['client_id'],
                'phone_format' => $get_outgoing_call['phone_format']
            ];
        }

        $status_label = (isset($client_statuses[$client->status])) ? $client_statuses[$client->status] : 'N/A';
        $total_client_phones = $client->ClientPhone->count();
        $get_call_client_id = Session::get('pre_call_client_id');
        $call_client = ($get_call_client_id && $get_call_client_id == $id) ? true : false;
        if ($call_client) {
            Session::forget('pre_call_client_id');
        }
        return view('client.'.$view_name,compact(
            'auth_user',
            'client',
            'client_statuses',
            'client_progress',
            'total_earned',
            'outstanding_payments_percentage',
            'outstanding_payments',
            'today_day_format',
            'event_types',
            'phone_countries',
            'client_tasks',
            'client_history',
            'has_more_history',
            'history_page_limit',
            'user_twilio_phone',
            'client_outgoing_call_details',
            'status_label',
            'total_client_phones',
            'call_client'
        ));
    }

    public function update(ClientRequest $request, $id)
    {
        $auth_user = request()->user();
        $client = Client::where('user_id','=',$auth_user->user_id)->find($id);
        if (!$client) {
            return redirect('client');
        }

        $client_values = json_decode($request['client_values'],true);
        if (!$client_values) {
            return redirect()
                ->back()
                ->with('error','Please add at least one project');
        }

        if ($request['email']) {
            if (!filter_var($request['email'],FILTER_VALIDATE_EMAIL)) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error','Please specify valid email');
            }

            /**If email changed*/
            if ($client->email != $request['email']) {
                Helper::addClientActivity($client->client_id, $client->client_id, 'Email Address Added', $request['email'], null, null, 'email_added');
            }
        }

        $client->update($request->only('name','email','company','status'));
        $client_status_list = Constant::GET_CLIENT_STATUSES_LIST();

        /**Update Values*/
        $client_value_ids = [];
        foreach ($client_values as $item) {
            if ($item['status'] && in_array($item['status'],$client_status_list)) {
                if (isset($item['id']) && $item['id']) {
                    $model = ClientValue::where('client_id','=',$client->client_id)->find($item['id']);
                    if (!$model) {
                        continue;
                    }

                    if (!$model->unique_code) {
                        $model->unique_code = md5($model->client_value_id.uniqid().rand(1,100));
                    }

                    if ($item['status'] == 'completed' && $client->email && $model->status !== $item['status']) {
                        Helper::queueLeaveReviewRequest($client->email, 'email', $client->User, $model->unique_code);
                    }
                }
                else{
                    $model = new ClientValue();
                    $model->client_id = $client->client_id;
                    $model->unique_code = md5($client->client_id.$client->user_id.uniqid());
                }

                $model->upfront_value = (isset($item['upfront_value']) && is_numeric($item['upfront_value']) && $item['upfront_value'] > 0) ? $item['upfront_value']: null;
                $model->ongoing_value = (isset($item['ongoing_value']) && is_numeric($item['ongoing_value']) && $item['ongoing_value'] > 0) ? $item['ongoing_value']: null;
                $model->status = $item['status'];
                $model->project_name = $item['project_name'];
                $model->save();

                $client_value_ids[] = $model->client_value_id;
            }
        }

        ClientValue::where('client_id','=',$client->client_id)
            ->whereNotIn('client_value_id',$client_value_ids)
            ->delete();

        $latest_client_value = ClientValue::where('client_id','=',$client->client_id)
            ->orderBy('created_at','desc')
            ->take('1')
            ->first();

        if ($latest_client_value) {
            $client->status = $latest_client_value->status;

        }
        else{
            ClientValue::create([
                'client_id' => $client->client_id,
                'status' => 'not-listed',
                'unique_code' => md5($client->user_id.$client->client_id.'_'.uniqid())
            ]);
            $client->status = 'not-listed';
        }

        $client->update();

        /**Update Addresses*/
        $client_addresses = json_decode($request['client_addresses'],true);
        if ($client_addresses) {
            $client_location_ids = [];
            foreach ($client_addresses as $item) {
                if ($item['city'] || $item['zip'] || $item['address']) {
                    if (isset($item['id']) && $item['id']) {
                        $model = ClientLocation::where('client_id','=',$client->client_id)->find($item['id']);
                        if (!$model) {
                            continue;
                        }
                    }
                    else{
                        $model = new ClientLocation();
                        $model->client_id = $client->client_id;
                    }

                    $model->city = $item['city'];
                    $model->zip = $item['zip'];
                    $model->address = $item['address'];
                    $model->save();

                    $client_location_ids[] = $model->client_location_id;
                }
            }

            ClientLocation::where('client_id','=',$client->client_id)
                ->whereNotIn('client_location_id',$client_location_ids)
                ->delete();
        }
        else{
            ClientLocation::where('client_id','=',$client->client_id)->delete();
        }

        /**Update Addresses*/
        $client_phones = json_decode($request['client_phones'],true);
        if (!$client_phones) {
            return redirect()
                ->back();
        }

        $client_phone_ids = [];
        $client_phones_list = [];
        $client_formatted_phones = [];
        foreach ($client_phones as $item) {
            if ($item['phone']) {
                if (isset($item['id']) && $item['id']) {
                    $model = ClientPhone::where('client_id','=',$client->client_id)->find($item['id']);
                    if (!$model) {
                        continue;
                    }
                }
                else{
                    $model = new ClientPhone();
                    $model->client_id = $client->client_id;
                }

                $get_phone_format = Helper::convertPhoneToFormat($item['country'], $item['phone']);
                if ($get_phone_format) {
                    if (!$get_phone_format) {
                        return response()->json([
                            'status' => false,
                            'error' => 'Not supported phone number entered'
                        ]);
                    }

                    $get_country = Country::where('is_twilio','=','1')->where('code','=',$item['country'])->first();
                    if ($get_country) {
                        $model->phone = $item['phone'];
                        $model->phone_format = $get_phone_format;
                        $model->country_code = $get_country->code;
                        $model->country_number = $get_country->number;

                        $model->save();
                        $client_phone_ids[] = $model->client_phone_id;
                        $client_phones_list[] = $model->phone;
                        $client_formatted_phones[] = $model->phone_format;
                    }
                }
            }
        }

        ClientPhone::where('client_id','=',$client->client_id)
            ->whereNotIn('client_phone_id',$client_phone_ids)
            ->delete();

        /**Get Form Data*/
        $get_user_form_data = UserFormData::where('user_id','=',$auth_user->user_id)
            ->where('is_converted','=','0')
            ->where(function($query) use ($client_phones_list, $client_formatted_phones){
                $phones = array_merge_recursive($client_phones_list, $client_formatted_phones);
                if ($phones) {
                    $query->whereIn('contact_phone',$phones);
                }
            })
            ->whereNull('client_id')
            ->get();

        foreach ($get_user_form_data as $item) {
            $item->is_converted = '1';
            $item->client_id = $client->client_id;
            $item->update();

            /**Track History*/
            Helper::addClientActivity($client->client_id, $item->user_form_data_id, 'Form Received', null, null, null, 'form');
        }

        return redirect('client/'.$client->client_id);
    }

    public function setStatus($id, $status)
    {
        $auth_user = request()->user();
        $client = Client::with('ClientLastValue')
            ->where('user_id','=',$auth_user->user_id)
            ->find($id);

        if (!$client) {
            return redirect('client');
        }

        if (array_key_exists($status,Constant::GET_CLIENT_STATUS_LIST())) {
            $client->status = $status;
            $client->update();

            if ($client->ClientLastValue) {
                $old_status = $client->ClientLastValue->status;
                $client->ClientLastValue->status = $status;

                if (!$client->ClientLastValue->unique_code) {
                    $client->ClientLastValue->unique_code = md5($client->ClientLastValue->client_value_id.uniqid().rand(1,100));
                }

                $client->ClientLastValue->update();
                if ($status == 'completed' && $client->email && $old_status !== $status) {
                    Helper::queueLeaveReviewRequest($client->email, 'email', $client->User, $client->ClientLastValue->unique_code);
                }
            }
        }

        return redirect('client/'.$client->client_id);
    }

    public function addEvent(Request $request)
    {
        try{
            $start_date_time = Carbon::createFromFormat('D, j F, Y H:i',$request['start_date'])->format('Y-m-d H:i:s');
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Please specify valid start date'
            ]);
        }

        try{
            $end_date_time = Carbon::createFromFormat('D, j F, Y H:i',$request['end_date'])->format('Y-m-d H:i:s');
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Please specify valid end date'
            ]);
        }

        /**Check Status*/
        if (!array_key_exists($request['status'],Constant::GET_ALL_EVENT_TYPES())) {
            return response()->json([
                'status' => false,
                'error' => 'Status not found'
            ]);
        }

        $auth_user = request()->user();
        if (!$request['client_id']) {
            return response()->json([
                'status' => false,
                'error' => 'Client not found'
            ]);
        }

        $client = Client::where('user_id','=',$auth_user->user_id)->find($request['client_id']);
        if (!$client) {
            return response()->json([
                'status' => false,
                'error' => 'Client not found'
            ]);
        }

        $client_phone = ($request['client_id']) ? ClientPhone::where('client_id','=',$client->client_id)->first() : $client_phone;

        $model = new Event();
        $model->user_id = $auth_user->user_id;
        $model->client_id = $client->client_id;
        $model->start_date_time = $start_date_time;
        $model->end_date_time = $end_date_time;
        $model->status = $request['status'];
        $model->other_status_text = (in_array($request['status'],['other','remind-me']) && isset($request['other_text'])) ? $request['other_text'] : null;
        $model->client_name = $client->name;
        $model->upfront_value = (isset($request['upfront_value']) && is_numeric($request['upfront_value']) && $request['upfront_value'] > 0) ? $request['upfront_value'] : null;
        $model->ongoing_value = (isset($request['ongoing_value']) && is_numeric($request['ongoing_value']) && $request['ongoing_value'] > 0) ? $request['ongoing_value'] : null;
        $model->phone = ($client_phone) ? $client_phone->phone : null;
        $model->save();

        /**Add Locations*/
        if (isset($request['locations'])) {
            $event_locations = [];
            $today_format = Carbon::now()->format('Y-m-d H:i:s');
            foreach ($request['locations'] as $item) {
                if ($item['city'] || $item['zip'] || $item['address']) {
                    $event_locations[] = [
                        'event_id' => $model->event_id,
                        'city' => $item['city'],
                        'zip' => $item['zip'],
                        'address' => $item['address'],
                        'created_at' => $today_format,
                        'updated_at' => $today_format
                    ];
                }
            }

            if ($event_locations) {
                EventLocation::insert($event_locations);
            }
        }

        return response()->json([
            'status' => true
        ]);
    }

    public function addFollowUp(Request $request)
    {
        try{
            $start_date_time = Carbon::createFromFormat('D, j F, Y H:i',$request['start_date'])->format('Y-m-d H:i:s');
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Please specify valid start date'
            ]);
        }

        try{
            $end_date_time = Carbon::createFromFormat('D, j F, Y H:i',$request['end_date'])->format('Y-m-d H:i:s');
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Please specify valid end date'
            ]);
        }

        $auth_user = request()->user();
        $client = Client::where('user_id','=',$auth_user->user_id)->find($request['client_id']);
        if (!$client) {
            return redirect('client');
        }

        $client_phone = ClientPhone::where('client_id','=',$client->client_id)->first();

        $model = new Event();
        $model->user_id = $auth_user->user_id;
        $model->client_id = $client->client_id;
        $model->start_date_time = $start_date_time;
        $model->end_date_time = $end_date_time;
        $model->status = 'remind-me';
        $model->client_name = $client->name;
        $model->upfront_value = (isset($request['upfront_value']) && is_numeric($request['upfront_value']) && $request['upfront_value'] > 0) ? $request['upfront_value'] : null;
        $model->ongoing_value = (isset($request['ongoing_value']) && is_numeric($request['ongoing_value']) && $request['ongoing_value'] > 0) ? $request['ongoing_value'] : null;
        $model->phone = ($client_phone) ? $client_phone->phone : null;
        $model->save();

        /**Add Locations*/
        if (isset($request['locations'])) {
            $event_locations = [];
            $today_format = Carbon::now()->format('Y-m-d H:i:s');
            foreach ($request['locations'] as $item) {
                if ($item['city'] || $item['zip'] || $item['address']) {
                    $event_locations[] = [
                        'event_id' => $model->event_id,
                        'city' => $item['city'],
                        'zip' => $item['zip'],
                        'address' => $item['address'],
                        'created_at' => $today_format,
                        'updated_at' => $today_format
                    ];
                }
            }

            if ($event_locations) {
                EventLocation::insert($event_locations);
            }
        }

        return response()->json([
            'status' => true
        ]);
    }

    public function addNote(Request $request)
    {
        $auth_user = request()->user();
        $client = Client::where('user_id','=',$auth_user->user_id)->find($request['client_id']);
        if ($client) {
            $model = new ClientNote();
            $model->client_id = $client->client_id;
            $model->note = $request['note'];
            $model->save();
            return response()->json([
                'status' => true,
                'time_format' => $model->created_at->format('H:i'),
                'date_format' => $model->created_at->format('d/m/Y H:i')
            ]);
        }

        return response()->json([
            'status' => false,
            'error' => 'Client not found'
        ]);
    }

    public function addTask(Request $request)
    {
        $auth_user = request()->user();
        $model = new UserTask();
        $model->user_id = $auth_user->user_id;
        if ($request['title'] && $request['description']) {
            if (!$request['client_id'] || $request['client_id'] == 'global') {
                $model->client_id = null;
            } else {
                $client = Client::where('user_id', '=', $auth_user->user_id)->find($request['client_id']);
                if (!$client) {
                    return response()->json([
                        'status' => false,
                        'error' => 'Client not found'
                    ]);
                }

                $model->client_id = $client->client_id;
            }

            $model->title = $request['title'];
            $model->description = $request['description'];
            $model->status = 'pending';
            $model->save();
            return response()->json([
                'status' => true,
                'id' => $model->user_task_id
            ]);
        }

        return response()->json([
            'status' => false,
            'error' => 'Client not found'
        ]);
    }

    public function checkTask(Request $request)
    {
        $auth_user = request()->user();
        $client = Client::where('user_id','=',$auth_user->user_id)->find($request['client_id']);
        if ($client) {
            $user_task = UserTask::where('user_id','=',$auth_user->user_id)->find($request['id']);
            if ($user_task) {
                $user_task->status = ($request['checked']) ? 'completed' : 'pending';
                $user_task->update();
                return response()->json([
                    'status' => true
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'error' => 'Client not found'
        ]);
    }

    public function searchTaskClient(Request $request)
    {
        $auth_user = request()->user();
        $clients = Client::select('client_id','name')
            ->where('user_id','=',$auth_user->user_id)
            ->where('client_id','!=',$request['client']);

        if ($request['term']) {
            $clients->where('name','like','%'.$request['term'].'%');
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

    public function leadInformation(Request $request)
    {
        $auth_user = request()->user();
        $client = Client::where('user_id','=',$auth_user->user_id)->find($request['client_id']);
        $first_call_date = $user_form_date = null;
        if ($client) {
            $user_form = UserFormData::where('user_id','=',$auth_user->user_id)
                ->where('client_id','=',$client->client_id)
                ->first();

            $user_form_date = ($user_form) ? $user_form->created_at->format('M j, Y') : null;
            $first_call = CallHistory::where('user_id','=',$auth_user->user_id)
                ->where('client_id','=',$client->client_id)
                ->first();

            $first_call_date = ($first_call) ? $first_call->created_at->format('M j, Y') : null;
        }

        return response()->json([
            'status' => true,
            'first_call_date' => $first_call_date,
            'user_form_date' => $user_form_date
        ]);
    }

    public function loadHistory(Request $request)
    {
        $auth_user = request()->user();
        $client = Client::where('user_id','=',$auth_user->user_id)->find($request['client_id']);
        if ($client && is_numeric($request['page'])) {
            $history_page_limit = Constant::GET_CLIENT_PROFILE_HISTORY_ITEMS();
            $client_history = ClientHistory::select([
                'client_history.client_history_id',
                'client_history.related_id',
                'client_history.title',
                'client_history.description',
                'client_history.start_date_time',
                'client_history.end_date_time',
                'client_history.type',
                'client_history.created_at',
                'call_history.recorded_audio_file',
                'call_history.recorded_playtime_format',
                'call_history.recorded_playtime_seconds',
                'event.event_id',
                'user_form_data.created_at as form_created_date',
                'user_form_data.user_form_data_id'
            ])
                ->selectRaw('date_format(call_history.created_at,"%a, %e %M") as call_start_date')
                ->selectRaw('date_format(call_history.created_at,"%H:%i") as call_start_time')
                ->selectRaw('date_format(date_add(call_history.created_at, interval `call_history`.`recorded_playtime_seconds` second),"%H:%i") as call_end_time')
                ->leftJoin('call_history','call_history.call_history_id','=','client_history.related_id')
                ->leftJoin('event','event.event_id','=','client_history.related_id')
                ->leftJoin('user_form_data','user_form_data.user_form_data_id','=','client_history.related_id')
                ->where('client_history.client_id', '=', $client->client_id)
                ->orderBy('client_history.created_at', 'desc')
                ->skip($request['page'] * $history_page_limit)
                ->take($history_page_limit + 1)
                ->get()
                ->toArray();

            $has_more_history = count($client_history) > $history_page_limit ? true : false;
            $client_history = $has_more_history ? array_slice($client_history,0,$history_page_limit) : $client_history;
            foreach ($client_history as $key => &$value) {
                switch ($value['type']) {
                    case 'form':
                        $value['created_at'] = $value['form_created_date'];
                        $value['form_created_date_format'] = Carbon::parse($value['form_created_date'])->format('F j, Y H:i');
                    break;
                    case 'event':
                        $value['date_time_format'] = Helper::clientHistoryEventDateFormat($value['start_date_time'],$value['end_date_time']);
                    break;
                    case 'invoice_added':
                        $value['form_created_date_format'] = Carbon::parse($value['form_created_date'])->format('F j, Y H:i');
                    break;
                }
                $value['time_ago_format'] = Helper::convertDateToFriendlyFormat(Carbon::parse($value['created_at']));
            }

            return response()->json([
                'status' => true,
                'items' => $client_history,
                'has_more_items' => $has_more_history
            ]);
        }

        return response()->json([
            'status' => false,
            'error' => 'Client not found'
        ]);
    }

    public function loadEventsHistory(Request $request)
    {
        $auth_user = request()->user();
        $event_date_obj = Carbon::parse($request['start_date']);
        $get_events = Event::select('event_id','start_date_time','client.name')
            ->leftJoin('client','client.client_id','=','event.client_id')
            ->where('event.user_id','=',$auth_user->user_id)
            ->where(DB::raw('date(event.start_date_time)'),'=',$event_date_obj->copy()->format('Y-m-d'))
            ->whereNotNull('client.client_id')
            ->groupBy('event.event_id')
            ->orderBy('event.start_date_time','asc')
            ->get();

        $events = [];
        foreach ($get_events as $item) {
            $events[] = [
                'name' => $item->name,
                'event_id' => $item->event_id,
                'start_time' => Carbon::parse($item->start_date_time)->format('H:i')
            ];
        }

        return response()->json([
            'status' => true,
            'date_week_name' => strtoupper($event_date_obj->copy()->format('D')),
            'date_format' => $event_date_obj->copy()->format('j M, Y'),
            'events' => $events
        ]);
    }

    public function deleteEvent(Request $request)
    {
        $auth_user = request()->user();
        $event = Event::where('user_id','=',$auth_user->user_id)->find($request['event_id']);
        if ($event) {
            $event->delete();
        }

        return response()->json([
            'status' => true
        ]);
    }

    public function formDetails(Request $request)
    {
        $auth_user = request()->user();
        $user_form_data = UserFormData::where('user_id','=',$auth_user->user_id)->find($request['id']);
        return response()->json([
            'status' => true,
            'form_data' => $user_form_data ? $user_form_data->contact_response : '',
        ]);
    }

    public function preCall($id)
    {
        Session::put('pre_call_client_id',$id);
        return redirect('client/'.$id);
    }

    public function invoiceDetails(Request $request)
    {
        $auth_user = request()->user();
        $invoice = Invoice::with('Client')
            ->where('user_id','=',$auth_user->user_id)
            ->where('client_id','=',$request['client_id'])
            ->find($request['invoice_id']);

        $is_overdue = (!$invoice->has_paid && Carbon::parse($invoice->due_date)->timestamp < Carbon::now()->timestamp) ? true : false;
        $overdue_days = Carbon::parse($invoice->due_date)->diffInDays(Carbon::now());
        return response()->json([
            'status' => true,
            'has_paid' => $invoice->has_paid,
            'is_overdue' => $is_overdue,
            'overdue_days' => $overdue_days
        ]);
    }
}
