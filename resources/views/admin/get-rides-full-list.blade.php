
    <div>
        <div class="col-12 d-flex justify-content-center">

            <div class="col-lg-12 col-sm-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Rides</h6>
                        <div>
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                id="add-employee-others-forms">Employee/Others</a> --}}
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
       
        {{-- <div class="col">
            <select class="form-control" onchange="checkVal(this)" name="status" id="status">
                <option value="">Select Type</option>
                <option>Paid</option>
                <option>Unpaid</option>
            </select>
        </div> --}}
       

        <div class="col">
            <select class="form-control toselect-tag-employee" onchange="checkVal(this)" name="rider" id="rider">
                <option value="">Select Riders</option>
                @foreach ($riders as $rides)
                    <option value="{{ $rides->id }}">{{ $rides->employee_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col">
            <select class="form-control" onchange="checkVal(this)" name="shift" id="shift">
                <option value="">Select Shift</option>
                @foreach ($shift as $get_shift)
                    <option value="{{ $get_shift->id }}">{{ $get_shift->location }}</option>
                @endforeach
            </select>
        </div>


        <div>
            <input type="button"  value="Reset" class="btn  btn-secondary" onclick="reset()">
            <input type="button"  value="PDF" class="btn btn-danger" id="get_rider_pdf_list">
            <input type="button"  value="View" class="btn btn-primary" id="get_rider_view_list">
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
                {{-- <th>Date</th> --}}
                <th>Date</th>
                <th>Employee</th>
                <th>Branch</th>
                <th>Shift</th>
                <th>Rides</th>
                <th>Amount</th>
                {{-- <th>Status</th> --}}
                {{-- <th>Paid_Date</th> --}}
                {{-- <th>Acc_Name</th> --}}
                {{-- <th>Action</th> --}}
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



$(".toselect-tag-employee").select2();

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
                url: "{{ url('get-list-riders') }}",
                data: function(d) {
                    d.to_date = $("#to_date").val();
                    d.from_date = $("#from_date").val();
                    // d.status = $("#status").val();
                    d.rider = $("#rider").val();
                    d.search_value = $("#search_value").val();
                    d.shift = $("#shift").val();
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
                    data: 'location',
                    name: 'location'
                },
                {
                    data: 'shift',
                    name: 'shift'
                },
                {
                    data: 'rides',
                    name: 'rides'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                // {
                //     data: 'status',
                //     name: 'status'
                // },
                // {
                //     data: 'paid_date',
                //     name: 'paid_date'
                // },
                // {
                //     data: 'account_name',
                //     name: 'account_name'
                // },
                // {
                //     data: 'action',
                //     name: 'action',
                //     orderable: false,
                //     searchable: false
                // },
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







 $("#get_rider_pdf_list").click(function(){

    var from_date = $("#from_date")[0].value;
    var to_date = $("#to_date")[0].value;
    // var status = $("#status")[0].value;
    var rider =  $("#rider")[0].value;
    var shift =  $("#shift")[0].value;
    var search_value = $("#search_value")[0].value;
$.ajax({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
        url:"{{ url('get-rides-pdf') }}",
        type:"get",
        data:{from_date:from_date,
            to_date:to_date,
            // status:status,
             shift:shift,
            rider:rider,
            search_value:search_value
        
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
            link.download = 'vendor_detail_list.pdf';
            document.body.appendChild(link);
            link.click();
        }
})

 

})





$("#get_rider_view_list").click(function(){

var from_date = $("#from_date")[0].value;
var to_date = $("#to_date")[0].value;
var shift =  $("#shift")[0].value;

if(from_date !=="" && to_date !==""  ){
    var url = "{{ url('get-riders-list-view') }}" + "/" + from_date + "/" + to_date + "/" + shift;
    viewModal(url);
}
 
})


//get_rider_view_list



</script>

