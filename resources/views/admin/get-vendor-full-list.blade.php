
    <div>
        <div class="col-12 d-flex justify-content-center">
 

            <div class="col-lg-12 col-sm-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Vendor</h6>
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
            <select class="form-control toselect-tag" onchange="checkVal(this)" name="product_id" id="product_id">
                <option value="">Select Items</option>
                @foreach ($others as $item)
                    <option value="{{ $item->id }}">{{$item->employee_name }}</option>
                @endforeach
            </select>
        </div> --}}

        <div class="col">
            <select class="form-control toselect-tag" onchange="checkVal(this)" name="vendors" id="vendors" >
                <option value="">Select Vendor</option>
                @foreach ($vendors as $vendor)
                    <option value="{{ $vendor->id }}">{{ $vendor->employee_name }}</option>
                @endforeach
            </select>
        </div>


        <div>
            <input type="button"  value="Reset" class="btn  btn-secondary" onclick="reset()">
            <input type="button"  value="View" class="btn btn-success" id="get_vendor_pdf">
            <input type="button"  value="Vendor Detail View" class="btn btn-primary" id="get_vendor_full_detail">
            {{-- <input type="button"  value="View" class="btn btn-primary" id="get_vendor_full_detail"> --}}
        </div>
</div>
{{-- </form> --}}
    
</div>


<div class="table-responsive">
    <div class="mb-3 d-flex justify-content-end">
        <input type="text" class="form-control w-25" id="search_value" name="search_value" placeholder="Type here to search........">
    </div>
    <table class="table table-bordered datatable_vendor_full_list" id="dataTable" width="100%"
        cellspacing="0">
        <thead>
            <tr>
                {{-- <th>Date</th> --}}
                <th>Date</th>
                <th>Vendors</th>
                <!-- <th>Items</th>
                <th>Weight</th>
                <th>Measurement</th>
                <th>Rate</th> -->
                <th>T_Amount</th>
               
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
       var vendor_table = $('.datatable_vendor_full_list').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            paging: true,
            "info": true,
            "language": {
                "infoFiltered": ""
            },

            ajax: {
                url: "{{ url('get-vendor-list') }}",
                data: function(d) {
                     d.from_date = $("#from_date").val();
                     d.to_date = $("#to_date").val();
                    // d.product_id = $("#product_id").val();
                     d.search_value = $("#search_value").val();
                     d.vendors =  $("#vendors").val();
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
                // {
                //     data: 'product_id',
                //     name: 'product_id'
                // },
                // {
                //     data: 'weight',
                //     name: 'weight'
                // },
                // {
                //     data: 'measurement',
                //     name: 'measurement'
                // },
                // {
                //     data: 'rate',
                //     name: 'rate'
                // },
                {
                    data: 'total_amount',
                    name: 'total_amount'
                },
               
            ],

            success: function(data) {

            }
        });


        $("#search_value").on('keyup', function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                vendor_table.draw();
            }
        });

        function checkVal(e){
       var from_date = $("#from_date")[0].value;
       var to_date = $("#to_date")[0].value;

       if(from_date!=="" && to_date!==""){
        vendor_table.draw();
       }
     }


     function reset(){
        var from_date = $("#from_date").val("");
       var to_date = $("#to_date").val("");
      // var product_id = $("#product_id").val("");
       var search_value = $("#search_value").val("");
       var vendors = $("#vendors").val("");
       vendor_table.draw();
     }





     $("#get_vendor_full_detail").click(function(){

        var url = "{{ url('get-vendor-grand-list-with-full-detail') }}";
        viewModal(url);

     })

     $("#get_vendor_pdf").click(function(){

        var from_date = $("#from_date")[0].value;
        var to_date = $("#to_date")[0].value;
        var vendors = $("#vendors")[0].value;
        var url = "{{ url('get-vendors-grand-pdf-report') }}" + "/" + from_date + "/" + to_date + "/" + vendors;
        viewModal(url);

    })

//  $("#get_vendor_pdf").click(function(){

//     console.log("yes");
//     var from_date = $("#from_date")[0].value;
//     var to_date = $("#to_date")[0].value;
//     //var product_id = $("#product_id")[0].value;
//     var vendors = $("#vendors")[0].value;
//     if(from_date!=="" && to_date!=="")

//     {
// $.ajax({
//     headers: {
//         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//     },
//         url:"{{ url('get-vendors-grand-pdf-report') }}",
//         type:"get",
//         data:{from_date:from_date,to_date:to_date,vendors:vendors},
//         success:function(data){
//             const pdfData = data[0];
//             // Create a blob object from the base64-encoded data
//             const byteCharacters = atob(pdfData);
//             const byteNumbers = new Array(byteCharacters.length);
//             for (let i = 0; i < byteCharacters.length; i++) {
//                 byteNumbers[i] = byteCharacters.charCodeAt(i);
//             }
//             const byteArray = new Uint8Array(byteNumbers);
//             const blob = new Blob([byteArray], {type: 'application/pdf'});

//             // Create a URL for the blob object
//             const url = URL.createObjectURL(blob);

//             // Create a link element with the URL and click on it to download the PDF file
//             const link = document.createElement('a');
//             link.href = url;
//             link.download = 'vendor_detail_list.pdf';
//             document.body.appendChild(link);
//             link.click();
//         }
// })

//  }

// })



$(".toselect-tag").select2();


</script>
