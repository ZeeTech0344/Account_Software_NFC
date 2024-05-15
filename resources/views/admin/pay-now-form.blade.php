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
        <input type="submit" value="Save" class="btn btn-primary">
    </div>
    <input type="hidden" name="pay_now_id" value="{{ $paynow_id }}">
    <input type="hidden" name="employee_id" value="{{ $id }}">
    <input type="hidden" name="pending_date" value="{{ $pending_date }}">
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
                var formData = new FormData(form);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('insert-pay-now') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        $('#pay-now-form')[0].reset();
                        $(".paynow-close")[0].click();
                        pending_table.draw();
                        // pending_list_full_table.draw();
                        // pending_table.draw();
                    },
                    error: function(data) {
                        
                        // if (data.responseJSON.error.length >= 1) {

                        // $(".alert-success")[0].classList.add("d-none");
                        // $(".alert-danger")[0].innerText = "Invalid fields";
                        // $(".alert-danger")[0].classList.remove("d-none");

                        //this code is for select2 fields for backend validation error
                        // for (var a = 0; a < $(".select2").length; a++) {
                        //     if ($(".select2")[a].previousSibling.value == "") {
                        //         $(".select2-selection")[a].style.border = "1px solid red";
                        //     }
                        // }

                        //this code is for without select2 fields for backend validation error
                        // var count_errors = data.responseJSON.error.length;
                        // for (var a = 0; a < count_errors; a++) {
                        //     var error_text = data.responseJSON.error[a];
                        //     var find_last_word = error_text.indexOf("field");
                        //     var name = error_text.substr(4, find_last_word - 5);
                        //     var create_name = "." + name.replace(" ", "_");
                        //     var check = $(create_name);
                        //     check[0].style.cssText = "border:1px solid red";
                        // }


                        // }

                    }

                })
            }
        });

</script>