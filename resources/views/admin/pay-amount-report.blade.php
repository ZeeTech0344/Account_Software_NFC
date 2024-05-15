

    <div>
        <div class="col-12 d-flex justify-content-center">

            <div class="col-lg-12 col-sm-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Vendors Paid Detail</h6>
                       
                        <div>
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                            id="view-remaining-report">View Remaining Total</a> --}}
                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="view-grand-report">View Grand Total</a>
                           
                        </div>
                    </div>
                    <div class="card-body">


<div>
    {{-- <form method="post" action="{{ url('get-pdf-report-of-easypaisa-amount') }}"> --}}
    <div class="row p-2">
        <div class="col">
            @csrf
            <input type="date" id="from_date" name="from_date" class="form-control" onchange="checkVal(this)">
        </div>
        <div class="col">
            <input type="date" id="to_date" name="to_date" class="form-control" onchange="checkVal(this)">
        </div>
       
        <div class="col">
            <select class="form-control" onchange="checkVal(this)" name="vendors" id="vendors">
                <option value="">Select Vendor</option>
                @foreach ($vendors as $vendor)
                    <option value="{{ $vendor->id }}">{{ $vendor->employee_name }}</option>
                @endforeach
            </select>
        </div>


        <div>
            <input type="button"  value="Reset" class="btn  btn-secondary" onclick="reset()">
            <input type="button"  value="PDF" class="btn btn-danger" id="get_rider_pdf">
        </div>
</div>
{{-- </form> --}}
</div>

<div class="table-responsive">
    <div class="mb-3 d-flex justify-content-end">
        <input type="text" class="form-control w-25" id="search_value" name="search_value" placeholder="Type here to search........">
    </div>
    <table class="table table-bordered datatable_riders" id="dataTable" width="100%"
        cellspacing="0">

        <thead>
            <tr>
              
                <th>Date</th>
                <th>Vendor</th>
                <th>Paid Amount</th>
                <th>Account Name</th>
                <th>Remarks</th>
              
            </tr>
        </thead>

        <tbody>



        </tbody>
    </table>
</div>

                    </div>
                </div>
            </div>
        </div>
    </div>

<script>





var pending_table = $('.datatable_riders').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            // paging: false,
            // "info": false,
            "language": {
                "infoFiltered": ""
            },

            ajax: {
                url: "{{ url('get-vendor-paid-amount-list') }}",
                data: function(d) {
                    d.to_date = $("#to_date").val();
                    d.from_date = $("#from_date").val();
                    d.search_value = $("#search_value").val();
                    d.vendors = $("#vendors").val();
                }
            },
            columns: [

            {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'employee_id',
                    name: 'employee_id'
                },
                {
                    data: 'paid_amount',
                    name: 'paid_amount'
                },
                {
                    data: 'account_name',
                    name: 'account_name'
                },
                {
                    data: 'remarks',
                    name: 'remarks'
                },
            
            ],

            success: function(data) {

            }
        });





        $("#search_value").on('keyup', function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                pending_table.draw();
            }
        });


        function checkVal(e){
       var from_date = $("#from_date")[0].value;
       var to_date = $("#to_date")[0].value;
     
    //    if(from_date!=="" && to_date!==""){
        pending_table.draw();
    //    }
     }


     function reset(){
        var from_date = $("#from_date").val("");
       var to_date = $("#to_date").val("");
       var status = $("#status").val("");
       pending_table.draw();
     }







 $("#get_rider_pdf").click(function(){

    var from_date = $("#from_date")[0].value;
    var to_date = $("#to_date")[0].value;
    var search_value = $("#search_value")[0].value;
    var vendors =  $("#vendors")[0].value;

    
$.ajax({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
        url:"{{ url('pay-amount-report-vendor-pdf') }}",
        type:"get",
        data:{
            from_date:from_date,
            to_date:to_date,
            search_value:search_value,
            vendors:vendors
            
        },
        success:function(data){
            const pdfData = data[0];
            // Create a blob object from the base64-encoded data
            const byteCharacters = atob(pdfData);
            const byteNumbers = new Array(byteCharacters.length);
            for (let i = 0; i < byteCharacters.length; i++) {
                byteNumbers[i] = byteCharacters.charCodeAt(i);
            }
            const byteArray = new Uint8Array(byteNumbers);
            const blob = new Blob([byteArray], {type: 'application/pdf'});

            // Create a URL for the blob object
            const url = URL.createObjectURL(blob);

            // Create a link element with the URL and click on it to download the PDF file
            const link = document.createElement('a');
            link.href = url;
            link.download = 'vendor_paid_detail_list.pdf';
            document.body.appendChild(link);
            link.click();
        }
})


})


$("#view-grand-report").click(function(){

    var from_date = $("#from_date")[0].value;
    var to_date = $("#to_date")[0].value;

    var url = "{{ url('view-pay-amount-vendor-grand-total-report') }}";

    viewModal(url);

})








</script>
