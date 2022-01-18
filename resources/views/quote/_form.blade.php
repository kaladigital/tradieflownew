@section('view_css')
<link rel="stylesheet" href="/js/jquery-ui/jquery-ui.css">
<link rel="stylesheet" href="/js/upload_file/uploadfile.css">
@endsection
<div class="row">
    <div class="col-md-6">
        <h4>Client Details</h4>
        <div class="form-group">
            {!! Form::text('client_name',null,['class' => 'form-control', 'id' => 'client_name']) !!}
            {!! Form::label('client_name','Client\'s Name') !!}
        </div>
        <div class="form-group">
            {!! Form::text('client_contact_person',null,['class' => 'form-control', 'id' => 'client_name']) !!}
            {!! Form::label('client_contact_person','Contact Person (optional)') !!}
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::text('client_phone',null,['class' => 'form-control', 'id' => 'client_phone']) !!}
                    {!! Form::label('client_phone','Phone') !!}
                </div>
                <div class="col-md-6">
                    {!! Form::text('client_email',null,['class' => 'form-control', 'id' => 'client_email']) !!}
                    {!! Form::label('client_email','Email') !!}
                </div>
            </div>
        </div>
        <h4>Location</h4>
        <div class="form-group">
            {!! Form::text('client_address',null,['class' => 'form-control', 'id' => 'client_address']) !!}
            {!! Form::label('client_address','Address') !!}
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::text('client_city',null,['class' => 'form-control', 'id' => 'client_city']) !!}
                    {!! Form::label('client_city','City') !!}
                </div>
                <div class="col-md-6">
                    {!! Form::text('client_state',null,['class' => 'form-control', 'id' => 'client_state']) !!}
                    {!! Form::label('client_state','State') !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::text('client_zip',null,['class' => 'form-control', 'id' => 'client_zip']) !!}
                    {!! Form::label('client_zip','Zip') !!}
                </div>
                <div class="col-md-6">
                    {!! Form::select('client_country_id',$country_id,null,['class' => 'form-control', 'id' => 'client_country_id', 'placeholder' => 'Select']) !!}
                    {!! Form::label('client_country_id','Country') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <h4>Business Details</h4>
        <div class="form-group">
            {!! Form::text('company_name',null,['class' => 'form-control', 'id' => 'company_name']) !!}
            {!! Form::label('company_name','Company Name') !!}
        </div>
        <div class="form-group">
            {!! Form::text('company_contact_person',null,['class' => 'form-control', 'id' => 'company_name']) !!}
            {!! Form::label('company_contact_person','Contact Person (optional)') !!}
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::text('company_phone',null,['class' => 'form-control', 'id' => 'company_phone']) !!}
                    {!! Form::label('company_phone','Phone') !!}
                </div>
                <div class="col-md-6">
                    {!! Form::text('company_email',null,['class' => 'form-control', 'id' => 'company_email']) !!}
                    {!! Form::label('company_email','Email') !!}
                </div>
            </div>
        </div>
        <h4>Location</h4>
        <div class="form-group">
            {!! Form::text('company_address',null,['class' => 'form-control', 'id' => 'company_address']) !!}
            {!! Form::label('company_address','Address') !!}
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::text('company_city',null,['class' => 'form-control', 'id' => 'company_city']) !!}
                    {!! Form::label('company_city','City') !!}
                </div>
                <div class="col-md-6">
                    {!! Form::text('company_state',null,['class' => 'form-control', 'id' => 'company_state']) !!}
                    {!! Form::label('company_state','State') !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::text('company_zip',null,['class' => 'form-control', 'id' => 'company_zip']) !!}
                    {!! Form::label('company_zip','Zip') !!}
                </div>
                <div class="col-md-6">
                    {!! Form::select('company_country_id',$country_id,null,['class' => 'form-control', 'id' => 'company_country_id', 'placeholder' => 'Select']) !!}
                    {!! Form::label('company_country_id','Country') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h4>Quotes Deadline</h4>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        {!! Form::text('expiry_days_within',null,['class' => 'form-control', 'id' => 'expiry_days_within']) !!}
                        {!! Form::label('expiry_days_within','Expires within days') !!}
                    </div>
                    <div class="col-md-6">
                        {!! Form::text('expiry_date',null,['class' => 'form-control', 'id' => 'expiry_date']) !!}
                        {!! Form::label('expiry_date','Expiry Date') !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h4>Special Offer</h4>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        {!! Form::text('special_discount_percentage',null,['class' => 'form-control', 'id' => 'special_discount_percentage']) !!}
                        {!! Form::label('special_discount_percentage','Amount of Discount') !!}
                    </div>
                    <div class="col-md-6">
                        {!! Form::text('special_discount_within_days',null,['class' => 'form-control', 'id' => 'special_discount_within_days']) !!}
                        {!! Form::label('special_discount_within_days','If accepted within days') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        {!! Form::textarea('message',null,['class' => 'form-control', 'id' => 'message', 'rows' => '4']) !!}
        {!! Form::label('message','Custom Message') !!}
    </div>
    <div class="col-md-12">
        <h4>Items on Quote</h4>
        <table class="table table-bordered" id="quote_item_table">
            <thead>
                <tr>
                    <th>Delete</th>
                    <th>Title of Item</th>
                    <th>Descr.</th>
                    <th>Unit Price</th>
                    <th>Tax Rate</th>
                    <th>Quantity</th>
                    <th>Re-Order</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <a href="" class="btn btn-primary text-center" id="add_new_item">Add New Item</a>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <h4>Total Items of Invoice</h4>
            <table>
                <tbody>
                    <tr>
                        <td>Next Value (without TAX)</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td id="tax_header_percentage">Tax amount:</td>
                        <td id="tax_header_amount"></td>
                    </tr>
                    <tr>
                        <td>Amount without Discount:</td>
                        <td id="amount_without_discount"></td>
                    </tr>
                    <tr>
                        <td>Discount value:</td>
                        <td id="total_discount_value"></td>
                    </tr>
                    <tr>
                        <td>Total gross amount:</td>
                        <td id="total_gross_amount"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <div id="company_logo_container"></div>
            <div id="upload_logo">Upload</div>
        </div>
    </div>
</div>
@section('view_script')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script type="text/javascript" src="/js/upload_file/jquery.form.min.js"></script>
<script type="text/javascript" src="/js/upload_file/jquery.uploadfile.js"></script>
<script type="text/javascript" src="/js/jquery.inputmask.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        // tinymce.init({
        //     selector: '#message',
        //     plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
        //     toolbar_mode: 'floating',
        // });

        $('#upload_logo').uploadFile({
            url: '/quote/company/logo',
            dragDrop:true,
            fileName: "qqfile",
            multiple : true,
            returnType:"json",
            showStatusAfterSuccess:false,
            showAbort:false,
            showDone:false,
            uploadButtonClass : "btn btn-primary btn-xs",
            onCancel: function(files,pd)
            {
                $('.ajax-file-upload-statusbar').remove();
            },
            onError: function(files,status,errMsg,pd)
            {
                $('.ajax-file-upload-statusbar').remove();
            },
            onSuccess:function(files,data,xhr){
                if (data.status) {
                    $('#company_logo_container').html(_.template($('#company_logo_template').html())({
                        image: data.file_name
                    }));
                }
                else{
                    App.render_message('error',data.error);
                }
            }
        });

        $(document).on('click','#add_new_item',function(){
            $('#quote_item_table tbody').append(_.template($('#quote_item_template').html()));
            set_mask();
            return false;
        });

        $(document).on('click','.delete_quote_item',function(){
            $(this).closest('.quote_item').fadeOut(function(){
                $(this).remove();
            });
            return false;
        });

        $('#quote_item_table tbody').sortable({ handle: '.sortable-handle' });
        $('#quote_item_table tbody').disableSelection();
    });

    $(document).on('keyup','.quote_item_price,.quote_item_qty,.quote_item_price,.quote_item_tax,#special_discount_percentage',function(){
        calculate_totals();
        return false;
    });

    $(document).on('click','#delete_company_logo',function(){
        $('#company_logo_container').empty();
        return false;
    });

    var calculate_totals = function(){
        var discount_percentage = parseFloat($('#special_discount_percentage').val());
            discount_percentage = isNaN(discount_percentage) ? 0 : discount_percentage;

        var total_quotes = 0;
        var quote_item_tax_amount = 0;

        $('.quote_item').each(function(){
            var unit_price = parseInt($(this).find('.quote_item_price').val());
            var unit_qty = parseFloat($(this).find('.quote_item_qty').val());
            var unit_tax = parseFloat($(this).find('.quote_item_tax').val());

            if (!isNaN(unit_price) && !isNaN(unit_qty) && !isNaN(unit_tax)) {
                var total = parseFloat(unit_price * unit_qty);
                quote_item_tax_amount += total > 0 ? total * unit_tax / 100 : 0;
                total_quotes += total;
            }
        });

        // $('#total_discount_value').text(isNaN(discount_amount) ? '' : App.convertNumberWithComma(discount_amount) + '%');
        $('#amount_without_discount').text('$' + isNaN(total_quotes) ? '' : App.convertNumberWithComma(total_quotes));
        var total_quote_percentage = total_quotes > 0 ? (quote_item_tax_amount / total_quotes * 100).toFixed(2) : 0;
        $('#tax_header_percentage').text(total_quote_percentage + '% Tax amount:');
        $('#tax_header_amount').text('$' + quote_item_tax_amount.toFixed(2));

        var total_with_tax = total_quotes - quote_item_tax_amount;
        var discount_value = 0;
        if (discount_percentage && discount_percentage > 0 && total_with_tax) {
            discount_value = total_with_tax * discount_percentage / 100;
        }

        $('#total_discount_value').text(discount_value);
        $('#total_gross_amount').text('$' + total_with_tax ? App.convertNumberWithComma(total_with_tax - discount_value) : 0);
    }

    var set_mask = function(){
        $('.quote_item_price').inputmask('decimal',{
            placeholder: ''
        });
        $('.quote_item_tax').inputmask('decimal',{
            placeholder: ''
        });
        $('.quote_item_qty').inputmask('integer',{
            placeholder: ''
        });

        $('#special_discount_percentage').inputmask('integer',{
            placeholder: ''
        });
    }
</script>
@endsection
<script type="text/template" id="quote_item_template">
    <tr class="quote_item" data-id="">
        <td>
            <a href="" class="delete_quote_item">
                Delete
            </a>
        </td>
        <td>
            <div class="form-group">
                <input type="text" class="form-control" class="quote_item_title" placeholder="Title of Item">
            </div>
        </td>
        <td>
            <a href="">Add/Edit</a>
        </td>
        <td>
            <div class="form-group">
                <input type="text" class="form-control quote_item_price" placeholder="Unit Price">
            </div>
        </td>
        <td>
            <div class="form-group">
                <input type="text" class="form-control quote_item_tax" placeholder="Tax Rate">
            </div>
        </td>
        <td>
            <div class="form-group">
                <input type="text" class="form-control quote_item_qty" placeholder="Quantity">
            </div>
        </td>
        <td>
            <a href="" class="sortable-handle">
                sort icon
            </a>
        </td>
    </tr>
</script>
<script type="text/template" id="company_logo_template">
    <div class="form-group">
        <img src="/quote_temp/<%= image %>" width="64" id="company_logo" data-image="<%= image %>">
        <a href="" id="delete_company_logo">Delete</a>
    </div>
</script>
