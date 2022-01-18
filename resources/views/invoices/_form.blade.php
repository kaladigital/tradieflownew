@section('view_css')
<link rel="stylesheet" href="/js/jquery-ui/jquery-ui.css">
<link rel="stylesheet" href="/js/jquery-datetimepicker/jquery.datetimepicker.min.css">
<link rel="stylesheet" href="/js/select2/css/select2.min.css">
@endsection
<div class="row">
    <div class="col-12 col-lg-6">
        <div class="widget-box">
            <h6>Client Details</h6>
            <span class="field-label">Client Data</span>
            @if($invoice->invoice_id)
                @if($invoice->Client)
                    <h5>
                        <a href="/client/{{ $invoice->client_id }}" class="text-dark">
                            {{ $invoice->Client->name }}
                        </a>
                    </h5>
                @endif
            @else
                <div class="form-group">
                    {!! Form::text('client_search',null,['class' => 'form-control', 'id' => 'client_search', 'placeholder' => 'Company or Client Name', 'required' => 'required']) !!}
                    {!! Form::label('client_search','Company or Client Name') !!}
                </div>
            @endif
            <div class="form-row">
                <div class="col-12 col-sm-6 col-lg-12 col-xl-6">
                    <div class="form-group">
                        {!! Form::text('phone',null,['class' => 'form-control', 'placeholder' => 'Phone Number']) !!}
                        {!! Form::label('phone','Phone Number') !!}
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-12 col-xl-6">
                    <div class="form-group">
                        {!! Form::email('email',null,['class' => 'form-control', 'placeholder' => 'Email']) !!}
                        {!! Form::label('email','Email') !!}
                    </div>
                </div>
            </div>
            <span class="field-label">Location</span>
            <div class="form-group">
                {!! Form::text('address',null,['class' => 'form-control', 'placeholder' => 'Address Line', 'required' => 'required']) !!}
                {!! Form::label('address','Address Line') !!}
            </div>
            <div class="form-row">
                <div class="col-12 col-sm-6 col-lg-12 col-xl-6">
                    <div class="form-group">
                        {!! Form::text('city',null,['class' => 'form-control', 'placeholder' => 'City', 'required' => 'required']) !!}
                        {!! Form::label('city','City') !!}
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-12 col-xl-6">
                    <div class="form-group">
                        {!! Form::text('state',null,['class' => 'form-control', 'placeholder' => 'State', 'required' => 'required']) !!}
                        {!! Form::label('state','State') !!}
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-12 col-sm-6 col-lg-12 col-xl-6">
                    <div class="form-group">
                        {!! Form::text('zip',null,['class' => 'form-control', 'placeholder' => 'ZIP', 'required' => 'required']) !!}
                        {!! Form::label('zip','ZIP') !!}
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-12 col-xl-6">
                    <div class="form-group select-group select-country">
                        {!! Form::select('country_id',$country_id, null,['class' => 'form-control', 'placeholder' => 'Select', 'id' => 'country_id', 'required' => 'required']) !!}
                        {!! Form::label('country_id','Country') !!}
                    </div>
                </div>
            </div>
            <span class="field-label">GST Number</span>
            <div class="form-group">
                {!! Form::text('gst_number',null,['class' => 'form-control', 'placeholder' => 'GST Number']) !!}
                {!! Form::label('gst_number','GST Number') !!}
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6 mt-4 mt-lg-0">
        <div class="widget-box">
            <h6>Invoice Details</h6>
            <span class="field-label">Dates</span>
            <div class="form-group date-calendar">
                {!! Form::text('issued_date',null,['class' => 'form-control', 'placeholder' => 'Issued Date', 'id' => 'issued_date', 'required' => 'required']) !!}
                {!! Form::label('issued_date','Issued Date') !!}
            </div>
            <div class="form-row">
                <div class="col-12 col-sm-6 col-lg-12 col-xl-6">
                    <div class="form-group date-calendar">
                        {!! Form::text('due_date',null,['class' => 'form-control', 'placeholder' => 'Due Date', 'id' => 'due_date', 'required' => 'required']) !!}
                        {!! Form::label('due_date','Due Date') !!}
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-12 col-xl-6">
                    <div class="form-group">
                        {!! Form::text('payment_deadline_days',null,['class' => 'form-control', 'placeholder' => 'Due Date', 'id' => 'payment_deadline_days']) !!}
                        {!! Form::label('payment_deadline_days','Due Date') !!}
                        <div class="days"><span>days</span></div>
                    </div>
                </div>
            </div>
            <span class="field-label">Payment Type</span>
            <div class="form-group select-group select-currency">
                {!! Form::label('currency','Currency') !!}
                {!! Form::select('currency',$currency_list,null,['class' => 'form-control', 'required' => 'required']) !!}
            </div>
            <span class="field-label">Discount</span>
            <div class="form-row">
                <div class="col-12 col-sm-6 col-lg-12 col-xl-6">
                    <div class="form-group select-group">
                        {!! Form::label('discount_type','Type of Discount') !!}
                        {!! Form::select('discount_type',$discount_types,null,['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-12 col-xl-6">
                    <div class="form-group">
                        {!! Form::text('discount',null,['class' => 'form-control', 'placeholder' => 'Amount of Discount', 'id' => 'discount']) !!}
                        {!! Form::label('discount','Amount of Discount') !!}
                    </div>
                </div>
            </div>
            <span class="field-label">Recurring Invoice?</span>
            <div class="form-group recurring-invoice">
                <ul class="nav nav-tabs action-triger">
                    <li class="nav-item">
                        <a class="nav-link {{ $invoice->is_recurring ? 'active' : '' }}" data-toggle="pill" href="#frequency-yes" id="recurring_yes_btn">Yes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $invoice->is_recurring ? '' : 'active' }}" data-toggle="pill" href="#frequency-no">No</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div id="frequency-yes" class="tab-pane fade {{ $invoice->is_recurring ? 'active show' : '' }}">
                    <span class="field-label">Frequency of Recurrance</span>
                    <div class="row recurrance-frequency-row">
                        <div class="col-12 col-xl-auto label-col">
                            <h6>Raise Every:</h6>
                        </div>
                        <div class="col-12 mt-3 mt-sm-0 col-sm-6 col-xl-auto counter-col">
                            <div class="form-group">
                                <button type="button" class="btn btn-secondary btn-decrease recurring_count" data-type="decrease">-</button>
                                {!! Form::text('recurring_num',null,['class' => 'form-control counting', 'id' => 'recurring_num']) !!}
                                <button type="button" class="btn btn-primary btn-increase recurring_count" data-type="increase">+</button>
                            </div>
                        </div>
                        <div class="col-12 mt-3 mt-sm-0 col-sm-6 col-xl-auto type-col">
                            <div class="form-group select-group select-raise-type">
                                {!! Form::select('recurring_type',$recurring_periods,null,['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div id="frequency-no" class="tab-pane fade in active"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="widget-box">
            <div class="heading-row d-flex align-items-center">
                <h6>What do you invoice?</h6>
                <a href="#" class="btn btn-outline btn--round ml-auto">Import from Quotes</a>
            </div>
            <div class="contents-body">
                <div class="contents-wrap">
                    <table id="invoice_table" class="table w-100" border-spacing="0">
                        <thead>
                            <tr>
                                <th class="delete-item">Delete</th>
                                <th class="item-title">Title of Item</th>
                                <th class="item-description">Descr.</th>
                                <th class="item-price">Unit Price</th>
                                <th class="item-tax-rate">Tax Rate</th>
                                <th class="item-quantity">Quantity</th>
                                <th class="item-re-order">Re-order</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($invoice->invoice_id)
                                @foreach($invoice->InvoiceItem as $key => $item)
                                    <tr class="invoice_item" data-id="{{ $item->invoice_item_id }}">
                                        <td class="delete-item delete_invoice_item">
                                            <button type="button" class="btn">
                                                <img src="/images/delete-red.svg" alt="Delete icon">
                                            </button>
                                        </td>
                                        <td class="item-title">
                                            <div class="form-group">
                                                {!! Form::text('item_title',$item->title,['class' => 'form-control item_title', 'placeholder' => 'Title of Item', 'id' => 'title_'.$key]) !!}
                                                {!! Form::label('title_'.$key,'Title of Item') !!}
                                            </div>
                                        </td>
                                        <td class="item-description">
                                            <button type="button" class="btn add_line_description">
                                                <img src="/images/invoice/add-description.svg" alt="Add description">
                                            </button>
                                            {!! Form::hidden('item_description',$item->description,['class' => 'item_desc']) !!}
                                        </td>
                                        <td class="item-price">
                                            <div class="form-group">
                                                {!! Form::text('item_price',$item->unit_price,['class' => 'form-control item_price', 'placeholder' => 'Title of Item', 'id' => 'item_price_'.$key]) !!}
                                                {!! Form::label('item_price_'.$key,'Unit Price') !!}
                                                <div class="currency">
                                                    <span class="currency_text"></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="item-tax-rate">
                                            <div class="form-group">
                                                {!! Form::text('item_tax',$item->tax_rate,['class' => 'form-control item_tax', 'placeholder' => 'Tax Rate', 'id' => 'item_tax_'.$key]) !!}
                                                {!! Form::label('item_tax_'.$key,'Tax Rate') !!}
                                                <div class="type">
                                                    <span>%</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="item-quantity">
                                            <div class="form-group">
                                                {!! Form::text('item_qty',$item->qty,['class' => 'form-control item_qty', 'placeholder' => 'Quantity', 'id' => 'item_qty_'.$key]) !!}
                                                {!! Form::label('item_qty_'.$key,'Quantity') !!}
                                                <div class="type">
                                                    <span>unit</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="item-re-order">
                                            <button type="button" class="btn">
                                                <img src="/images/invoice/re-order-icon.svg" alt="Re order icon">
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <button type="button" id="add_invoice_item" class="btn btn--sqr btn-primary d-flex align-items-center mx-auto">
                    <img src="/images/plus-Icon-white.svg" alt="Plus icon" class="icon">
                    <span>Add New Item</span>
                </button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-lg-6">
        <div class="widget-box items-info-list-box">
            <h6>Total Items of Invoice</h6>
            <ul class="total-items-info-list">
                <li class="list-item d-flex align-items-center">
                    <span class="label">Net Value (without Tax)</span>
                    <span class="value ml-auto" id="net_without_tax_label"></span>
                </li>
                <li class="list-item d-flex align-items-center">
                    <span class="label" id="tax_amount_percentage_label"></span>
                    <span class="value ml-auto" id="tax_amount_label"></span>
                </li>
                <li class="list-item d-flex align-items-center">
                    <span class="label">Amount without Discount:</span>
                    <span class="value ml-auto" id="amount_without_discount_label"></span>
                </li>
                <li class="list-item d-flex align-items-center">
                    <span class="label">Discount value:</span>
                    <span class="value ml-auto" id="discount_value_amount_label"></span>
                </li>
                <li class="list-item total-amount d-flex align-items-center">
                    <span class="label">Total gross amount:</span>
                    <span class="value ml-auto" id="total_gross_amount_label"></span>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-12 col-lg-6 mt-4 mt-lg-0">
        <div class="widget-box comments-box">
            <h6>Comments</h6>
            <div class="form-group comments">
                {!! Form::textarea('note',null,['class' => 'form-control', 'maxlength' => '1024', 'placeholder' => 'Start typing...']) !!}
            </div>
            <div class="custom-control custom-checkbox">
                {!! Form::checkbox('is_public_note','1',$invoice->is_public_note ? true : false,['class' => 'custom-control-input', 'id' => 'is_public_note']) !!}
                {!! Form::label('is_public_note','Client can view the comments',['class' => 'custom-control-label']) !!}
            </div>
        </div>
    </div>
</div>
<div class="row form-action-row">
    <div class="col-12 col-sm-3 col-xl-6 text-center">
        <a href="/invoices" class="btn btn-secondary btn--sqr">Cancel</a>
    </div>
    <div class="col-12 col-sm-9 col-xl-6 text-center">
        <button type="submit" id="save_invoice_btn" class="btn btn-outline btn--sqr">Save Invoice</button>
        @if($invoice->status == 'pending')
            <button type="button" id="save_send_invoice_btn" href="#" class="btn btn-primary btn--sqr">Save Invoice & Send</button>
        @endif
    </div>
</div>
{!! Form::hidden('client_id',null,['id' => 'client_id']) !!}
{!! Form::hidden('invoice_items',null,['id' => 'invoice_items']) !!}
{!! Form::hidden('send_invoice',null,['id' => 'send_invoice']) !!}
{!! Form::hidden('is_recurring',null,['id' => 'is_recurring']) !!}
<div class="modal fade add-new-invoice-modal add-description-popup" id="add_description_modal" tabindex="-1"
     role="dialog" aria-labelledby="add_description_modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-12 header-col d-flex align-items-center">
                    <span class="modal-title" id="addDescriptionPopupModalLabel">Add Description to Item</span>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
                        <img src="/images/calendar-modal-close.svg" alt="Close icon black">
                    </button>
                </div>
            </div>
            <div class="modal-container">
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::textarea('line_description',null,['class' => 'form-control', 'placeholder' => 'Description', 'maxlength' => '1024', 'id' => 'line_description']) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn--sqr btn-secondary" data-dismiss="modal" aria-label="Close">Cancel</a>
                    <a href="#" class="btn btn--sqr btn-primary" id="save_line_description">Save</a>
                </div>
            </div>
        </div>
    </div>
</div>
@section('view_script')
<script type="text/javascript" src="/js/underscore-min.js"></script>
<script type="text/javascript" src="/js/jquery-datetimepicker/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript" src="/js/jquery.inputmask.min.js"></script>
<script type="text/javascript" src="/js/select2/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function(){
        window.current_date_time_obj = new Date('{{ $current_date_format }}');
        window.months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        $('#discount').inputmask('decimal',{
            rightAlign: false,
            placeholder: ''
        });

        $('#invoice_table tbody').sortable({
            handle: '.item-re-order',
            cancel: ''
        }).disableSelection();

        $('#issued_date').datetimepicker({
            timepicker:false,
            format: 'F j, Y',
            lang:'en',
            validateOnBlur: false,
            closeOnTimeSelect: true,
            scrollMonth : false,
            scrollInput : false
        });

        $('#due_date').datetimepicker({
            timepicker:false,
            format: 'F j, Y',
            lang:'en',
            validateOnBlur: false,
            closeOnTimeSelect: true,
            scrollMonth : false,
            scrollInput : false
        });

        $('#payment_deadline_days').inputmask('integer',{
            rightAlign: false,
            placeholder: ''
        });

        $('#recurring_num').inputmask('integer',{
            rightAlign: false,
            placeholder: ''
        });

        $('#country_id').select2({
            width: '100%'
        });

        $(document).on('click','.recurring_count',function(){
            var recurring_num = parseInt($('#recurring_num').val());
            var recurring_type = $(this).attr('data-type');
            recurring_num += (recurring_type == 'increase') ? 1 : -1;
            recurring_num = (recurring_num <= 0) ? 1 : recurring_num;
            $('#recurring_num').val(recurring_num);
            return false;
        });

        $(document).on('change','#due_date',function(){
            var due_date = $(this).val();
            if (due_date.length) {
                var days_left = Math.ceil((new Date(due_date).getTime() - current_date_time_obj.getTime()) / (60 * 60 * 24 * 1000));
                $('#payment_deadline_days').val(days_left);
            }
            else{
                $('#payment_deadline_days').val('');
            }
            return false;
        });

        $(document).on('keyup','#payment_deadline_days',function(){
            var total_days = $(this).val();
            if (total_days.length) {
                var day_num = parseInt(total_days) * 60 * 60 * 24 * 1000;
                var date_obj = new Date(current_date_time_obj.getTime() + day_num);
                var month = months[date_obj.getMonth()];
                $('#due_date').val(month + ' ' + date_obj.getDate() + ', ' + date_obj.getFullYear());
            }
            else{
                $('#due_date').val('');
            }
            return false;
        });

        /**Invoice Create*/
        $(document).on('click','#save_invoice_btn',function(){
            $('#send_invoice').val('0');
            $('#invoice_form').trigger('submit');
            return false;
        });

        $(document).on('click','#save_send_invoice_btn',function(){
            $('#send_invoice').val('1');
            $('#invoice_form').trigger('submit');
            return false;
        });

        $(document).on('submit','#invoice_form',function(){
            @if(!$invoice->invoice_id)
                var client_id = $('#client_id').val();
                if (!client_id || !$('#client_search').val()) {
                    App.render_message('info','Please select client from list');
                    $('#client_search').focus();
                    return false;
                }
            @endif
            var invoice_line_item = $('.invoice_item:first');
            if (!invoice_line_item.length) {
                App.render_message('error','Please specify invoice items');
                return false;
            }

            var title_obj = invoice_line_item.find('.item_title');
            var title = $.trim(title_obj.val());
            if (!title.length) {
                title_obj.focus();
                return false;
            }

            var price_obj = invoice_line_item.find('.item_price');
            var price = $.trim(price_obj.val());
            if (!price.length) {
                price_obj.focus();
                return false;
            }

            var qty_obj = invoice_line_item.find('.item_qty');
            var qty = $.trim(qty_obj.val());
            if (!qty.length) {
                qty_obj.focus();
                return false;
            }

            var invoice_items = [];
            var allow_submit = true;
            $('.invoice_item').each(function(){
                var title_obj = $(this).find('.item_title');
                var title = $.trim(title_obj.val());
                var price_obj = $(this).find('.item_price');
                var price = $.trim(price_obj.val());
                var qty_obj = $(this).find('.item_qty');
                var qty = $.trim(qty_obj.val());

                if (allow_submit && !title.length) {
                    title_obj.focus();
                    allow_submit = false;
                }

                if (allow_submit && !price.length) {
                    price_obj.focus();
                    allow_submit = false;
                }

                if (allow_submit && !qty.length) {
                    qty_obj.focus();
                    allow_submit = false;
                }

                if (allow_submit) {
                    invoice_items.push({
                        id: $(this).attr('data-id'),
                        title: title,
                        price: price,
                        tax: $(this).find('.item_tax').val(),
                        description: $(this).find('.item_desc').val(),
                        qty: qty
                    });
                }
            });

            if (allow_submit) {
                $('#invoice_items').val(JSON.stringify(invoice_items));
                $('#is_recurring').val($('#recurring_yes_btn').hasClass('active') ? '1' : '0');
                return true;
            }
            else{
                return false;
            }
        });

        /**Invoice Item*/
        $(document).on('click','#add_invoice_item',function(){
            $('#invoice_table tbody').append(_.template($('#add_invoice_item_template').html())({
                num: (new Date()).getTime() * 1000
            }));
            $('.invoice_item:last').fadeIn();
            set_inline_items_masks();
            return false;
        });

        $(document).on('click','.delete_invoice_item',function(){
            $(this).closest('.invoice_item').fadeOut(function(){
                $(this).remove();
                calculate_totals();
            });
            return false;
        });

        $(document).on('click','.add_line_description',function(){
            $('.invoice_item').removeClass('active_invoice_item');
            var closest_obj = $(this).closest('.invoice_item');
            closest_obj.addClass('active_invoice_item');
            $('#line_description').val(closest_obj.find('.item_desc').val());
            $('#add_description_modal').modal('show');
            return false;
        });

        $(document).on('click','#save_line_description',function(){
            $('#add_description_modal').modal('hide');
            var description = $.trim($('#line_description').val());
            $('.active_invoice_item').find('.item_desc').val(description);
            $('.invoice_item').removeClass('active_invoice_item');
            return false;
        });

        $(document).on('keyup','.item_title,.item_price,.item_tax,.item_qty,#discount',function(){
            calculate_totals();
            return false;
        });

        $(document).on('change','#discount_type,#currency',function(){
            calculate_totals();
            return false;
        });

        @if($invoice->invoice_id)
            set_inline_items_masks();
        @else
            $('#add_invoice_item').trigger('click');
        @endif

        $('#client_search').autocomplete({
            autoFill: true,
            select: function (event, ui) {
                $('#client_id').val(ui.item.client_id);
            },
            source: function( request, response ) {
                $.ajax( {
                    url: '/invoices/client/search',
                    type: 'POST',
                    dataType: 'json',
                    async: false,
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        var clients = [];
                        if (data.clients.length) {
                            $.each(data.clients,function(key,value){
                                clients.push({
                                    label : value.name + (value.company ? ' (' + value.company + ')' : ''),
                                    client_id : value.client_id,
                                    phone: value.phone
                                });
                            });
                        }
                        return response(clients);
                    }
                });
            },
            minLength: 0
        }).bind('focus',function(){ $(this).autocomplete("search"); } );

        /**Calculate Totals*/
        calculate_totals();

        @if($invoice->invoice_id)
            $('#due_date').trigger('change');
        @endif
    });

    var set_inline_items_masks = function(){
        $('.item_price').inputmask('decimal',{
            rightAlign: false,
            placeholder: ''
        });

        $('.item_tax').inputmask('decimal',{
            rightAlign: false,
            placeholder: ''
        });

        $('.item_qty').inputmask('integer',{
            rightAlign: false,
            placeholder: ''
        });
    };

    var calculate_totals = function(){
        var no_tax_total = 0;
        var taxed_total = 0;

        $('.invoice_item').each(function(){
            var unit_price = $(this).find('.item_price').val();
                unit_price = unit_price ? parseFloat(unit_price) : 0;

            var tax_rate = $(this).find('.item_tax').val();
                tax_rate = tax_rate ? parseFloat(tax_rate) : 0;

            var qty = $(this).find('.item_qty').val();
                qty = qty ? parseInt(qty) : 0;

            var total_price = unit_price * qty;
            no_tax_total += total_price;
            taxed_total += (total_price && tax_rate) ? total_price * tax_rate / 100 : 0;
        });

        var currency_label = $('#currency option:selected').text();
        $('#net_without_tax_label').text(convert_number_format(no_tax_total) + ' ' + currency_label);
        var total_percentage = (taxed_total && no_tax_total) ? Math.ceil(taxed_total * 100 / no_tax_total) : 0;
        var total_with_tax = no_tax_total - taxed_total;
        $('#tax_amount_percentage_label').text(total_percentage + '% Tax amount:');
        $('#tax_amount_label').text(convert_number_format(taxed_total.toFixed(2)) + ' ' + currency_label);
        $('#amount_without_discount_label').text(convert_number_format(total_with_tax.toFixed(2)) + ' ' + currency_label);

        var discount_amount = $('#discount').val();
        var discount_type = $('#discount_type').val();
        var total_discount_value = 0;
        if (discount_amount.length && parseFloat(discount_amount) > 0) {
            if (discount_type == 'percentage') {
                total_discount_value = total_with_tax * discount_amount / 100;
            }
            else{
                total_discount_value = discount_amount;
            }
        }
        $('#discount_value_amount_label').text(convert_number_format(total_discount_value.toFixed(2)) + ' ' + currency_label);
        $('#total_gross_amount_label').text(convert_number_format((total_with_tax - total_discount_value.toFixed(2))) + ' ' + currency_label);
        $('.currency_text').text(currency_label);
    };

    function convert_number_format(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>
<script type="text/template" id="add_invoice_item_template">
    <tr class="invoice_item" data-id="" style="display:none;">
        <td class="delete-item delete_invoice_item">
            <button type="button" class="btn">
                <img src="/images/delete-red.svg" alt="Delete icon">
            </button>
        </td>
        <td class="item-title">
            <div class="form-group">
                <input type="text" class="form-control item_title" placeholder="Title of Item" id="title_<%= num %>">
                <label for="title_<%= num %>">Title of Item</label>
            </div>
        </td>
        <td class="item-description">
            <button type="button" class="btn add_line_description">
                <img src="/images/invoice/add-description.svg" alt="Add description">
            </button>
            {!! Form::hidden('item_description',null,['class' => 'item_desc']) !!}
        </td>
        <td class="item-price">
            <div class="form-group">
                <input type="text" class="form-control item_price" placeholder="Unit Price" id="price_<%= num %>">
                <label for="price_<%= num %>">Unit Price</label>
                <div class="currency">
                    <span class="currency_text"></span>
                </div>
            </div>
        </td>
        <td class="item-tax-rate">
            <div class="form-group">
                <input type="text" class="form-control item_tax" placeholder="Tax Rate" id="tax_<%= num %>">
                <label for="tax_<%= num %>">Tax Rate</label>
                <div class="type">
                    <span>%</span>
                </div>
            </div>
        </td>
        <td class="item-quantity">
            <div class="form-group">
                <input type="text" class="form-control item_qty" placeholder="Quantity" id="qty_<%= num %>">
                <label for="qty_<%= num %>">Quantity</label>
                <div class="type">
                    <span>unit</span>
                </div>
            </div>
        </td>
        <td class="item-re-order">
            <button type="button" class="btn">
                <img src="/images/invoice/re-order-icon.svg" alt="Re order icon">
            </button>
        </td>
    </tr>
</script>
@endsection
