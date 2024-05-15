<div class="card-header py-3 d-flex justify-content-center">
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" style="margin-left: 5px;" id="employee_list"><i
            class="fas fa-download fa-sm text-white-50"></i> Employee</a>

            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm ml-5" id="others_list"><i
                class="fas fa-download fa-sm text-white-50"></i> Others</a>
    
</div>










<script>
     $(document).on("click", "#employee_list", function() {

    var url = "{{ url('employee-report') }}";
    viewModal(url);

    })

</script>