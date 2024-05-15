

    <div class="col-lg-12 col-sm-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Employee/Others List</h6>
            </div>
            <div class="card-body">
                <div>
                    {{-- <form method="post" action="{{ url('get-pdf-report-of-easypaisa-amount') }}"> --}}
                    <div class="row p-2">
                   <div class="col">
           
                        <input type="date" id="from_date" name="from_date" class="form-control" onchange="whenChageVal(this)">
                        </div>
                        <div class="col">
                            <input type="date" id="to_date" name="to_date" class="form-control" onchange="whenChageVal(this)">
                        </div>
        
                    
                        <div class="col">
                            <select class="form-control" onchange="whenChageVal(this)" name="heads" id="heads">
                                <option value="">Select Head</option>
                                @foreach ($heads as $head)
                                    <option value="{{ $head->id }}">{{ $head->head }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col">
                            <select class="form-control" onchange="whenChageVal(this)" name="locations"
                                id="locations">
                                <option value="">Select Branch</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->location }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div>
                            <input type="button" value="Reset" class="btn  btn-secondary" onclick="reset()">
                            <input type="button" value="View" class="btn btn-success" id="get_all_record_pdf">
                            <input type="button" value="Grand View" class="btn btn-primary" id="view_record">
                        </div>
                    </div>
                    {{-- </form> --}}



    <div class="table-responsive">
      
        <table class="table table-bordered table_employee_other" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <tr>
                        <th>Date</th>
                        <th>Head</th>
                        <th>Location</th>
                        <th>Amount</th>
                        <th>Remarks</th>
                    </tr>
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


    <script>
        var employee_table_record = $('.table_employee_other').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            // paging: false,
            // "info": false,
            "language": {
                "infoFiltered": ""
            },

            ajax: {
                url: "{{ url('get-full-data-of-daily-closing') }}",
                data: function(d) {
                    d.from_date = $("#from_date").val();
                    d.to_date = $("#to_date").val();
                    d.heads = $("#heads").val();
                    d.locations = $("#locations").val();
                }
            },
            columns: [{
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'head',
                    name: 'head'
                },
                {
                    data: 'location',
                    name: 'location'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'remarks_get',
                    name: 'remarks_get'
                }
            ],

            success: function(data) {
                console.log(data);
            }
        });


          function whenChageVal(e) {
            var from_date = $("#from_date")[0].value;
            var to_date = $("#to_date")[0].value;

            if (from_date !== "" && to_date !== "") {
                employee_table_record.draw();
            }
        }


        

      $(document).on("click", "#view_record", function() {
        var from_date = $("#from_date")[0].value;
          var to_date = $("#to_date")[0].value;
          var heads = $("#heads")[0].value;
          var locations = $("#locations")[0].value;
        var url = "{{ url('get-view-of-daily-closing-grand-data') }}" + "/" + from_date + "/" + to_date + "/" + heads + "/" + locations;
        forListModalView(url);

       })


        // $(document).on("click", ".edit_employee_others", function() {

        //     var id = $(this).data("id");
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         url: "{{ url('edit-employee-others') }}",
        //         type: "GET",
        //         data: {
        //             id: id
        //         },
        //         success: function(data) {


        //             $("#employee_name").val(data["employee_name"]);
        //             $("#employee_type").val(data["employee_type"]);
        //             $("#employee_post").val(data["employee_post"]);
        //             $("#cnic").val(data["cnic"]);
        //             $("#basic_sallary").val(data["basic_sallary"]);
        //             $("#employee_branch").val(data["employee_branch"])
        //             $("#employee_status").val(data["employee_status"])
        //             $("#employee_hidden_id").val(data["id"])

        //             $("#close-view")[0].click();

        //             // $("#date").val(data[0]["date"]);
        //             // getHeads(data[0]["head"]);
        //             // getHeadLocation(data[0]["location"]);
        //             // $("#amount").val(data[0]["amount"]);
        //             // $("#hidden_id").val(data[0]["id"]);
        //         }
        //     })

        // })


        // // $(document).on("click", ".edit_employee_others", function() {

        // //     var id = $(this).data("id");




        // // })


        // function whenChageVal(e) {
        //     var branch_of_employee = $("#branch_of_employee")[0].value;
        //     var type_of_employee = $("#type_of_employee")[0].value;

        //     if (branch_of_employee !== "" && type_of_employee !== "") {

        //         employee_table_record.draw();
        //     }
        // }

        // function resetEmployeeRecord() {
        //     $("#branch_of_employee").val("");
        //     $("#type_of_employee").val("");
        //     employee_table_record.draw();
        // }



        $(document).on("click", "#get_all_record_pdf", function() {

           
          var from_date = $("#from_date")[0].value;
          var to_date = $("#to_date")[0].value;
          var heads = $("#heads")[0].value;
          var locations = $("#locations")[0].value;

          if(from_date !=="" && to_date !== ""){

            var url = "{{url('get-pdf-data-of-daily-closing')}}" + "/" + from_date + "/" + to_date + "/" + locations + "/" + heads;
            viewModal(url);

          }

          
            // $.ajax({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     },
            //     url: "{{ url('get-pdf-data-of-daily-closing') }}",
            //     type: "GET",
            //     data: {
            //         from_date: from_date,
            //         to_date: to_date,
            //         heads: heads,
            //         locations:locations
            //     },
            //     success: function(data) {
            //         const pdfData = data[0];
            //         // Create a blob object from the base64-encoded data
            //         const byteCharacters = atob(pdfData);
            //         const byteNumbers = new Array(byteCharacters.length);
            //         for (let i = 0; i < byteCharacters.length; i++) {
            //             byteNumbers[i] = byteCharacters.charCodeAt(i);
            //         }
            //         const byteArray = new Uint8Array(byteNumbers);
            //         const blob = new Blob([byteArray], {
            //             type: 'application/pdf'
            //         });


            //         // Create a URL for the blob object
            //         const url = URL.createObjectURL(blob);

            //         // Create a link element with the URL and click on it to download the PDF file
            //         const link = document.createElement('a');
            //         link.href = url;
            //         link.download = 'test.pdf';
            //         document.body.appendChild(link);
            //         link.click();
            //     }
            // })
        })


        function reset(){
          var from_date = $("#from_date").val("");
          var to_date = $("#to_date").val("");
          var heads = $("#heads").val("");
          var locations = $("#locations").val("");
          employee_table_record.draw();
        }

    </script>















{{-- {{ $closing }} --}}

{{-- {{ $heads_name }} --}}

{{-- <style>

    table{
        width:100%;
        border-collapse: collapse;
        
    }

    td,th {
        border: 1px solid black;
        text-align: left;
        padding: 5px;
    }
    h4{
        text-align: center;
    }
    
</style>
@php
    $sr=0;
@endphp
<table>
    <thead>
      <tr>
        <th>Sr</th>
        <th>Date</th>
        <th>Head</th>
        <th>Location</th>
        <th>Amount</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($closing as $get_data)
        <tr>
            <td>{{ $sr++ }}</td>
            <td>{{ $get_data->date }}</td>
            <td>{{ $get_data->head }}</td>
            <td>{{ $get_data->location }}</td>
            <td>{{ $get_data->sum }}</td>
        </tr>
        @endforeach
    </tbody>

</table> --}}