
{{-- {{ print_r($result) }} --}}



@php
$total_amount = 0;
    foreach ($result as $key => $get_date) {

        $total_amount = $total_amount + $get_date[0];
    }

    $convert_array_javascript = json_encode($result,JSON_PRETTY_PRINT );
  
@endphp

<form id="pay-now-rides-bulk-form" class="data-form">
{{-- 
    <div class="form-group">
        <label for="exampleFormControlSelect1"></label>
        <select class="form-control" name="pay_through" id="pay_through">
            <option value="">Pay Through</option>
            <option>Easypaisa</option>
            <option>HBL</option>
            <option>Locker</option>
        </select>
    </div> --}}

    <div class="form-group">
        <label for="exampleFormControlInput1" >Checked Ride Amounts</label>
        <input type="input" class="form-control" name="amount" readonly value="{{ $total_amount }}" >
    </div>

    <div class="form-group d-flex justify-content-end">
        <input type="submit" value="Save" disabled class="btn btn-primary">
    </div>
    {{-- <input type="hidden" name="pay_now_id" value="{{ $paynow_id }}">
    <input type="hidden" name="employee_id" value="{{ $id }}">
    <input type="hidden" name="pending_date" value="{{ $pending_date }}"> --}}
</form>



<script>

    var get_data = <?php echo  $convert_array_javascript;  ?>;

    
    
        $('#pay-now-rides-bulk-form').validate({
            errorPlacement: function(error, element) {
                    // element[0].style.border = "1px solid red";
            },
            rules: {
                pay_through: "required",
            },

            submitHandler: function(form) {

                var pay_through = $("#pay_through")[0].value;
                var formData = new FormData(form);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('insert-bulk-pay-now-rides') }}",
                    type: "POST",
                    data:{get_data:get_data,pay_through:pay_through},
                    success: function(data) {
                        $('#pay-now-rides-bulk-form')[0].reset();
                        pending_table.draw();
                        $(".paynow-close")[0].click();

                        
                    },
                    error: function(data) {
                        
                    

                    }

                })
            }
        });

</script>