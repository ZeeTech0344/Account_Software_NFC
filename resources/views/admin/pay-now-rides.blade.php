<form id="pay-now-form" class="data-form">

    <div class="form-group">
        <label for="exampleFormControlSelect1"></label>
        <select class="form-control" name="pay_through">
            <option value="">Pay Through</option>
            <option>Easypaisa</option>
            <option>HBL</option>
            <option>Locker</option>
        </select>
    </div>

    <div class="form-group">
        <label for="exampleFormControlInput1" name="pending_amount">Amount</label>
        <input type="input" class="form-control" name="amount" readonly value="{{ $amount }}">
    </div>

    <div class="form-group d-flex justify-content-end">
        <input type="submit" value="Save" disabled class="btn btn-primary">
    </div>
    <input type="hidden" name="pay_now_id" value="{{ $paynow_id }}">
    <input type="hidden" name="employee_id" value="{{ $employee_id }}">
    <input type="hidden" name="pending_date" value="{{ $ride_date }}">
</form>



<script>
    
        $('#pay-now-form').validate({
            errorPlacement: function(error, element) {
                    // element[0].style.border = "1px solid red";
            },
            rules: {
                pay_through: "required",
                pending_amount: "required",
                pay_now_id: "required",
                pending_date: "required",
            },

            submitHandler: function(form) {

                if (confirm('Paid Rides! Are you sure')) {
                var formData = new FormData(form);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('insert-pay-now-rides') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                    //   -n  $('#payow-form')[0].reset();
                        $(".paynow-close")[0].click();


                        //both are vendor table but due to copy paste there i cannot change names
                        pending_table.draw();
                       // vendor_table.draw();



                        // pending_list_full_table.draw();
                        // pending_table.draw();
                    },
                    error: function(data) {
                        
                        

                    }

                })
            }
            }
        });

</script>