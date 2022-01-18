<?php

namespace App\Http\Controllers;
use App\Helpers\Constant;
use App\Models\Client;
use App\Models\Event;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $auth_user = request()->user();
        $event_types = Constant::GET_ALL_EVENT_TYPES();
        return view('calendar.index',compact(
            'auth_user',
            'event_types'
        ));
    }

    public function loadEvents(Request $request)
    {
        $auth_user = request()->user();
        $get_calendar_events = Event::select([
            'event.event_id',
            'event.status',
            'event.start_date_time',
            'event.end_date_time',
            'event.upfront_value',
            'event.ongoing_value',
            'client.name',
            'client.company'
        ])
            ->where('event.user_id','=',$auth_user->user_id)
            ->where(DB::raw('DATE(event.start_date_time)'),'>=',substr($request['start'],0,10))
            ->where(DB::raw('DATE(event.start_date_time)'),'<=',Carbon::createFromFormat('Y-m-d', substr($request['end'],0,10))->addDay(-1)->format('Y-m-d'))
            ->leftJoin('client','client.client_id','=','event.client_id')
            ->where('client.status','!=','cancelled');

        if ($request['filter']) {
            $get_calendar_events->whereIn('event.status',explode(',',$request['filter']));
        }

        $get_calendar_events = $get_calendar_events
            ->get();

        $calendar_events = [];
        foreach ($get_calendar_events as $item) {
            $calendar_events[] = [
                'id' => $item->event_id,
                'title' => $item->name.($item->company ? '('.$item->company.')' : ''),
                'start' => $item->start_date_time,
                'end' => $item->end_date_time,
            ];
        }

        return response()->json($calendar_events);
    }

    public function searchClient(Request $request)
    {
        $auth_user = request()->user();
        $get_clients = Client::select('client.client_id','client.name','client.company')
            ->leftJoin('client_phone',function($query) use ($request){
                $query->on('client_phone.client_id','=','client.client_id')
                    ->where('client_phone.phone','like','%'.$request['term'].'%');
            })
            ->where('client.user_id','=',$auth_user->user_id)
            ->where(function($query) use ($request){
                $query
                    ->where('client.name','like','%'.$request['term'].'%')
                    ->orWhereNotNull('client_phone.client_phone_id');
            })
            ->groupBy('client.client_id')
            ->orderBy('client.name','asc')
            ->take(10)
            ->get();

        return response()->json([
            'status' => true,
            'clients' => $get_clients
        ]);
    }
}
