<?php

namespace App\Http\Controllers;
use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Http\Requests\ClientRequest;
use App\Http\Requests\InvoiceRequest;
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

class InvoicesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('active_subscription');
    }

    public function index(Request $request)
    {
        $auth_user = Auth::user();
        $invoices = Invoice::with('Client')
            ->leftJoin('client as c', 'c.client_id', '=', 'invoice.client_id')
            ->where('invoice.user_id', '=', $auth_user->user_id);

        if ($request['client_stage']) {
            $invoices->where('c.status', '=', $request['client_stage']);
        }

        if ($request['amount_from']) {
            $invoices->where('invoice.total_gross_amount', '>=', $request['amount_from']);
        }

        if ($request['amount_to']) {
            $invoices->where('invoice.total_gross_amount', '<=', $request['amount_to']);
        }

        if ($request['raised_start_date']) {
            try {
                $raised_start_date = Carbon::createFromFormat('D, j F', $request['raised_start_date'])->format('Y-m-d');
                $invoices->where('invoice.created_at', '>=', $raised_start_date);
            } catch (\Exception $e) {
                $request['raised_start_date'] = null;
            }
        }

        if ($request['raised_end_date']) {
            try {
                $raised_end_date = Carbon::createFromFormat('D, j F', $request['raised_end_date'])->format('Y-m-d');
                $invoices->where('invoice.created_at', '<=', $raised_end_date);
            } catch (\Exception $e) {
                $request['raised_end_date'] = null;
            }
        }

        if ($request['client_type']) {
            if ($request['client_type'] == 'individual') {
                $invoices->whereRaw('LENGTH(c.company) = 0');
            } elseif ($request['client_type'] == 'company') {
                $invoices->whereNotNull('c.company');
            }
        }

        if ($request['client_id']) {
            $invoices->where('client_id', '=', $request['client_id']);
        }

        if ($request['invoice_type']) {
            if ($request['invoice_type'] == 'payed') {
                $invoices->where('invoice.has_paid', '=', '1');
            } elseif ($request['invoice_type'] == '') {
                $invoices
                    ->where('invoice.has_paid', '=', '0')
                    ->where('invoice.due_date', '<', Carbon::now()->format('Y-m-d'));
            }
        }

        if ($request['sort_by']) {
            switch ($request['sort_by']) {
                case 'number_asc':
                    $invoices->orderBy('invoice.invoice_number_label');
                    break;
                case 'number_desc':
                    $invoices->orderBy('invoice.invoice_number_label', 'desc');
                    break;
                case 'client_asc':
                    $invoices->orderBy('c.name');
                    break;
                case 'client_desc':
                    $invoices->orderBy('c.name', 'desc');
                    break;
                case 'raised_on_asc':
                    $invoices->orderBy('invoice.created_at');
                    break;
                case 'raised_on_desc':
                    $invoices->orderBy('invoice.created_at', 'desc');
                    break;
                case 'payed_on_asc':
                    $invoices->orderBy('invoice.paid_date');
                    break;
                case 'payed_on_desc':
                    $invoices->orderBy('invoice.paid_date', 'desc');
                    break;
                case 'amount_asc':
                    $invoices->orderBy('invoice.total_gross_amount');
                    break;
                case 'amount_desc':
                    $invoices->orderBy('invoice.total_gross_amount', 'desc');
                    break;
                default:
                    $invoices->orderBy('invoice.created_at', 'desc');
                    break;
            }
        }

        $invoices = $invoices
            ->paginate(10);

        if ($request['page'] && $request['page'] > 1 && !$invoices->count()) {
            $request_params = $request->all();
            $request_params['page'] -= 1;
            return redirect('invoices?' . http_build_query($request_params));
        }

        $client_statuses = Constant::GET_EVENT_STATUSES_LIST();
        $total_earned = Invoice::selectRaw('ifnull(sum(net_value_without_tax),0) as total')->where('user_id', '=', $auth_user->user_id)->where('has_paid', '=', '1');
        $total_paid = Invoice::selectRaw('ifnull(sum(total_gross_amount),0) as total')->where('user_id', '=', $auth_user->user_id)->where('has_paid', '=', '1');
        $total_outstanding = Invoice::selectRaw('ifnull(sum(total_gross_amount),0) as total')->where('user_id', '=', $auth_user->user_id)->where('has_paid', '=', '0')->where('due_date', '>', Carbon::now()->format('Y-m-d'));
        $total_overdue = Invoice::selectRaw('ifnull(sum(total_gross_amount),0) as total')->where('user_id', '=', $auth_user->user_id)->where('has_paid', '=', '0')->where('due_date', '<=', Carbon::now()->format('Y-m-d'));

        $invoice_totals = $total_earned
            ->unionAll($total_paid)
            ->unionAll($total_outstanding)
            ->unionAll($total_overdue)
            ->get();

        $current_year = Carbon::now()->format('Y');
        $previous_year = $current_year - 1;
        $chart_data = Invoice::selectRaw('ifnull(sum(net_value_without_tax),0) as total')
            ->where('user_id', '=', $auth_user->user_id)
            ->where('has_paid', '=', '1')
            ->where(DB::raw('year(paid_date)'), '=', $previous_year)
            ->where(DB::raw('month(paid_date)'), '=', '12');

        for ($i = 1; $i <= 12; $i++) {
            $query_obj = Invoice::selectRaw('ifnull(sum(net_value_without_tax),0) as total')
                ->where('user_id', '=', $auth_user->user_id)
                ->where('has_paid', '=', '1')
                ->where(DB::raw('year(paid_date)'), '=', $current_year)
                ->where(DB::raw('month(paid_date)'), '=', $i);

            $chart_data->unionAll($query_obj);
        }

        $next_year = $current_year + 1;
        $next_year_query = Invoice::selectRaw('ifnull(sum(net_value_without_tax),0) as total')
            ->where('user_id', '=', $auth_user->user_id)
            ->where('has_paid', '=', '1')
            ->where(DB::raw('year(paid_date)'), '=', $next_year)
            ->where(DB::raw('month(paid_date)'), '=', '12');

        $chart_data = $chart_data
            ->unionAll($next_year_query)
            ->get();

        return view('invoices.index', compact(
            'auth_user',
            'invoices',
            'request',
            'client_statuses',
            'invoice_totals',
            'chart_data',
            'previous_year',
            'next_year'
        ));
    }

    public function paymentReceived($id)
    {
        $invoice = Invoice::find($id);
        if ($invoice && !$invoice->has_paid) {
            $invoice->has_paid = '1';
            $invoice->paid_date = Carbon::now()->format('Y-m-d H:i:s');
            $invoice->update();
        }

        return redirect()
            ->back();
    }

    public function duplicate($id)
    {
        $invoice = Invoice::find($id);
        if ($invoice) {
            $invoice_data = $invoice->toArray();
            unset($invoice_data['invoice_id']);
            unset($invoice_data['xero_invoice_id']);
            unset($invoice['created_at']);
            unset($invoice_data['updated_at']);
            $invoice_data['invoice_unique_number'] = $invoice->user_id . $invoice->client_id . uniqid();
            $invoice_data['status'] = 'pending';
            $invoice_data['has_paid'] = '0';
            $invoice_data['paid_date'] = null;
            $invoice_data['is_recurring'] = '0';
            $invoice_data['recurring_type'] = null;
            $invoice_data['recurring_num'] = null;
            $invoice_data['next_recurring_date'] = null;
            $model = Invoice::create($invoice_data);
            $model->invoice_number_label = Carbon::now()->format('Y-nj') . ($model->invoice_id + 1000);
            $model->update();
            return redirect('invoices/' . $model->invoice_id . '/edit');
        }

        return redirect()
            ->back();
    }

    public function delete(Request $request)
    {
        $auth_user = request()->user();
        Invoice::where('user_id', '=', $auth_user->user_id)->whereIn('invoice_id', $request['invoice_ids'])->delete();
        return response()->json([
            'status' => true
        ]);
    }

    public static function clientSearch(Request $request)
    {
        $auth_user = request()->user();
        $clients = ClientPhone::select('client.client_id', 'client.name', 'client.company', 'client_phone.phone')
            ->leftJoin('client', 'client.client_id', '=', 'client_phone.client_id')
            ->where('client.user_id', '=', $auth_user->user_id);

        if ($request['term']) {
            $clients->where('client.name', 'like', '%' . $request['term'] . '%');
        }

        $clients = $clients
            ->groupBy('client.client_id')
            ->orderBy('name', 'asc')
            ->take(10)
            ->get();

        return response()->json([
            'status' => true,
            'clients' => $clients->toArray()
        ]);
    }

    public function create()
    {
        $auth_user = Auth::user();
        $discount_types = Constant::GET_DISCOUNT_TYPES_LIST();
        $currency_list = Helper::getAvailableCurrenciesList();
        $recurring_periods = Constant::GET_INVOICE_RECURRING_PERIODS();
        $invoice = new Invoice();
        $invoice->recurring_num = '1';
        $invoice->issued_date = Carbon::now()->format('F j, Y');
        $invoice->payment_deadline_days = 7;
        $invoice->due_date = Carbon::now()->addDays($invoice->payment_deadline_days)->format('F j, Y');
        $invoice->status = 'pending';
        $country_id = Helper::getCountryList();
        $current_date_format = Carbon::now()->format('Y-m-d');
        return view('invoices.create', compact(
            'auth_user',
            'discount_types',
            'currency_list',
            'recurring_periods',
            'invoice',
            'country_id',
            'current_date_format'
        ));
    }

    public function store(InvoiceRequest $request)
    {
        $auth_user = request()->user();
        $client = Client::where('user_id', '=', $auth_user->user_id)->find($request['client_id']);
        if (!$client) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Client not found');
        }

        $request['user_id'] = $auth_user->user_id;
        $request['status'] = 'pending';
        $request['invoice_unique_number'] = $auth_user->user_id . $client->client_id . uniqid();

        try {
            $due_date_obj = Carbon::createFromFormat('F j, Y', $request['due_date']);
            $request['issued_date'] = Carbon::createFromFormat('F j, Y', $request['issued_date'])->format('Y-m-d');
            $request['due_date'] = $due_date_obj->copy()->format('Y-m-d');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Please double check issues and due dates');
        }

        try {
            $invoice_items = json_decode($request['invoice_items']);
            if (!$invoice_items) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Please add at least one invoice item');
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Please add at least one invoice item');
        }

        $request['is_public_note'] = ($request['is_public_note']) ? $request['is_public_note'] : 0;
        $request['payment_deadline_days'] = Carbon::now()->diffInDays($due_date_obj);
        $model = Invoice::create($request->only([
            'user_id',
            'client_id',
            'country_id',
            'phone',
            'email',
            'address',
            'city',
            'state',
            'zip',
            'gst_number',
            'currency',
            'discount_type',
            'discount',
            'is_recurring',
            'status',
            'note',
            'is_public_note',
            'invoice_unique_number',
            'recurring_type',
            'recurring_num',
            'issued_date',
            'due_date',
            'payment_deadline_days',
            'next_recurring_date'
        ]));

        $invoice_item_data = [];
        $created_date = Carbon::now()->format('Y-m-d H:i:s');
        $no_tax_total = 0;
        $tax_total = 0;
        foreach ($invoice_items as $key => $item) {
            $tax = $item->tax ? $item->tax : 0;
            $invoice_item_data[] = [
                'invoice_id' => $model->invoice_id,
                'title' => $item->title,
                'description' => $item->description ?? '',
                'unit_price' => $item->price,
                'tax_rate' => $tax,
                'qty' => $item->qty,
                'order_num' => $key,
                'created_at' => $created_date,
                'updated_at' => $created_date
            ];

            if ($item->qty && $item->price) {
                $total_price = $item->qty * $item->price;
                $no_tax_total += $total_price;

                if ($tax) {
                    $tax_total += $total_price * $tax / 100;
                }
            }
        }

        /**Create Invoice Items*/
        InvoiceItem::insert($invoice_item_data);

        /**Update Invoice User Friendly Label*/
        $model->invoice_number_label = Carbon::now()->format('Y-nj') . ($model->invoice_id + 1000);
        $model->net_value_without_tax = sprintf('%.2f',$no_tax_total);
        $model->tax_amount = sprintf('%.2f',$tax_total);

        $model->amount_without_discount = sprintf('%.2f',$no_tax_total - $model->tax_amount);

        if ($request['discount'] && $request['discount'] > 0) {
            if ($request['discount_type'] == 'percentage') {
                $model->discount_amount = sprintf('%.2f',$model->amount_without_discount * $request['discount'] / 100);
            } else {
                $model->discount_amount = $request['discount'];
            }
        } else {
            $model->discount_amount = 0;
        }

        /**Handle Recurring*/
        if ($model->is_recurring && $model->recurring_type && $model->recurring_num && array_key_exists($model->recurring_type,Constant::GET_INVOICE_RECURRING_PERIODS())) {
            switch ($model->recurring_type) {
                case 'day':
                    $model->next_recurring_date = Carbon::now()->addDays($model->recurring_num)->format('Y-m-d');
                break;
                case 'month':
                    $model->next_recurring_date = Carbon::now()->addMonths($model->recurring_num)->format('Y-m-d');
                break;
                case 'year':
                    $model->next_recurring_date = Carbon::now()->addYears($model->recurring_num)->format('Y-m-d');
                break;
            }
        }
        else{
            $model->next_recurring_date = null;
        }

        $model->total_gross_amount = sprintf('%.2f',$model->amount_without_discount - $model->discount_amount);
        $model->update();

        /**Send Invoice*/
        if ($request['send_invoice']) {
            $invoices = Invoice::with('Client','InvoiceItem')->where('invoice_id',$model->invoice_id)->get();
            $model->due_date_format = $due_date_obj->copy()->format('j M, Y');
            $send_invoice = Helper::sendInvoices($auth_user, $invoices, 'email', Helper::generateSendInvoiceMessage($client, $model, $auth_user), [$model->invoice_id], null);
            if ($send_invoice) {
                return redirect('invoices/'.$model->invoice_id.'/edit')
                    ->with('success', 'Invoice sent successfully');
            }
        }

        return redirect('invoices')
            ->with('success', 'Invoice created successfully');
    }

    public function edit($id)
    {
        $auth_user = Auth::user();
        $invoice = Invoice::with(['Client', 'InvoiceItem' => function ($query) {
            $query->orderBy('order_num', 'asc');
        }])->where('user_id', '=', $auth_user->user_id)->find($id);

        if (!$invoice) {
            return redirect('invoices')
                ->with('error', 'Invoice not found');
        }

        $discount_types = Constant::GET_DISCOUNT_TYPES_LIST();
        $currency_list = Helper::getAvailableCurrenciesList();
        $recurring_periods = Constant::GET_INVOICE_RECURRING_PERIODS();
        $country_id = Helper::getCountryList();
        $current_date_format = Carbon::now()->format('Y-m-d');
        $invoice->issued_date = Carbon::createFromFormat('Y-m-d', $invoice->issued_date)->format('F j, Y');
        $due_date_obj = Carbon::createFromFormat('Y-m-d', $invoice->due_date);
        $invoice->due_date = $due_date_obj->copy()->format('F j, Y');
        $invoice->payment_deadline_days = $due_date_obj->copy()->diffInDays(Carbon::now());
        return view('invoices.edit', compact(
            'auth_user',
            'discount_types',
            'currency_list',
            'recurring_periods',
            'invoice',
            'country_id',
            'current_date_format'
        ));
    }

    public function update(InvoiceRequest $request, $id)
    {
        $auth_user = Auth::user();
        $invoice = Invoice::with(['InvoiceItem' => function ($query) {
            $query->orderBy('order_num', 'asc');
        }])->where('user_id', '=', $auth_user->user_id)->find($id);

        if (!$invoice) {
            return redirect('invoices')
                ->with('error', 'Invoice not found');
        }

        try {
            $due_date_obj = Carbon::createFromFormat('F j, Y', $request['due_date']);
            $request['issued_date'] = Carbon::createFromFormat('F j, Y', $request['issued_date'])->format('Y-m-d');
            $request['due_date'] = $due_date_obj->copy()->format('Y-m-d');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Please double check issues and due dates');
        }

        try {
            $invoice_items = json_decode($request['invoice_items']);
            if (!$invoice_items) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Please add at least one invoice item');
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Please add at least one invoice item');
        }

        $request['is_public_note'] = ($request['is_public_note']) ? $request['is_public_note'] : 0;
        $request['payment_deadline_days'] = Carbon::now()->diffInDays($due_date_obj);
        $invoice->update($request->only([
            'country_id',
            'phone',
            'email',
            'address',
            'city',
            'state',
            'zip',
            'gst_number',
            'currency',
            'discount_type',
            'discount',
            'is_recurring',
            'note',
            'is_public_note',
            'recurring_type',
            'recurring_num',
            'issued_date',
            'due_date',
            'payment_deadline_days'
        ]));

        $invoice_item_ids = [];
        $no_tax_total = 0;
        $tax_total = 0;
        foreach ($invoice_items as $key => $item) {
            if ($item->id) {
                $model = InvoiceItem::where('invoice_id','=',$invoice->invoice_id)->find($item->id);
                if (!$model) {
                    continue;
                }
            }
            else{
                $model = new InvoiceItem();
                $model->invoice_id = $invoice->invoice_id;
            }

            $tax = $item->tax ? $item->tax : 0;
            $model->title = $item->title;
            $model->description = $item->description ?? '';
            $model->unit_price = $item->price;
            $model->tax_rate = $tax;
            $model->qty = $item->qty;
            $model->order_num = $key;
            $model->save();

            if ($item->qty && $item->price) {
                $total_price = $item->qty * $item->price;
                $no_tax_total += $total_price;

                if ($tax) {
                    $tax_total += $total_price * $tax / 100;
                }
            }

            $invoice_item_ids[] = $model->invoice_item_id;
        }

        /**Remove deleted Invoice Items*/
        InvoiceItem::where('invoice_id','=',$invoice->invoice_id)
            ->whereNotIn('invoice_item_id',$invoice_item_ids)
            ->delete();

        /**Update Invoice User Friendly Label*/
        $invoice->net_value_without_tax = sprintf('%.2f',$no_tax_total);
        $invoice->tax_amount = sprintf('%.2f',$tax_total);
        $invoice->amount_without_discount = sprintf('%.2f',$no_tax_total - $invoice->tax_amount);

        if ($request['discount'] && $request['discount'] > 0) {
            if ($request['discount_type'] == 'percentage') {
                $invoice->discount_amount = sprintf('%.2f',$invoice->amount_without_discount * $request['discount'] / 100);
            } else {
                $invoice->discount_amount = $request['discount'];
            }
        } else {
            $invoice->discount_amount = 0;
        }

        $invoice->total_gross_amount = sprintf('%.2f',$invoice->amount_without_discount - $invoice->discount_amount);

        /**Handle Recurring*/
        if ($invoice->is_recurring && $invoice->recurring_type && $invoice->recurring_num && array_key_exists($invoice->recurring_type,Constant::GET_INVOICE_RECURRING_PERIODS())) {
            switch ($invoice->recurring_type) {
                case 'day':
                    $invoice->next_recurring_date = Carbon::now()->addDays($invoice->recurring_num)->format('Y-m-d');
                break;
                case 'month':
                    $invoice->next_recurring_date = Carbon::now()->addMonths($invoice->recurring_num)->format('Y-m-d');
                break;
                case 'year':
                    $invoice->next_recurring_date = Carbon::now()->addYears($invoice->recurring_num)->format('Y-m-d');
                break;
            }
        }
        else{
            $invoice->next_recurring_date = null;
        }

        $invoice->update();

        /**Send Invoice*/
        if ($request['send_invoice']) {
            $invoices = Invoice::with('Client','InvoiceItem')->where('invoice_id',$invoice->invoice_id)->get();
            $invoice->due_date_format = $due_date_obj->copy()->format('j M, Y');
            $send_invoice = Helper::sendInvoices($auth_user, $invoices, 'email', Helper::generateSendInvoiceMessage($invoice->Client, $invoice, $auth_user), [$invoice->invoice_id], null);
            if ($send_invoice) {
                return redirect('invoices/'.$invoice->invoice_id.'/edit')
                    ->with('success', 'Invoice sent successfully');
            }
        }

        return redirect('invoices/'.$invoice->invoice_id.'/edit')
            ->with('success', 'Invoice updated successfully');
    }
}
