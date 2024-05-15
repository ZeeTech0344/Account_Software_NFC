<div>
    {{-- <form method="post" action="{{ url('get-pdf-report-of-easypaisa-amount') }}"> --}}
    <div class="row p-2">
        <div class="col">
            <input type="month" id="salary_month" name="salary_month" onchange="checkValues(this)" class="form-control" >
        </div>
       
        <div class="col">
            <select class="form-control"  name="status" onchange="checkValues(this)"  id="status">
                <option>Paid</option>
                <option>Unpaid</option>
            </select>
        </div>
        <div>
            <input type="button"  value="Reset" class="btn  btn-secondary" onclick="reset()">
            <input type="button"  value="PDF" class="btn btn-danger" id="get_salary_pdf">
        </div>
</div>
{{-- </form> --}}
    
</div>


<div class="table-responsive">
    <table class="table table-bordered salary-report-table" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Employee#</th>
                <th>Name</th>
                <th>Post</th>
                <th>Branch</th>
                <th>Basic_Sallary</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        </tbody>
    </table>
</div>

<script>

var salary_report_table = $('.salary-report-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            // paging: false,
            // "info": false,
            "language": {
                "infoFiltered": ""
            },

            ajax: {
                
                url: "{{ url('get-salary-report') }}",
                data: function(d) {
                    d.salary_month = $("#salary_month").val()
                    d.status = $("#status").val()
                }
            },
            columns: [
                {
                    data: 'employee_no',
                    name: 'employee_no'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'post',
                    name: 'post'
                },
                {
                    data: 'branch',
                    name: 'branch'
                },
                {
                    data: 'sallary',
                    name: 'sallary'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            success: function(data) {
                console.log(data);
            }
        });




        function checkValues(e){
       var month = $("#salary_month")[0].value;
       var status = $("#status")[0].value;
            // console.log("yes");

       if(month!=="" && status!==""){
        salary_report_table.draw();
       }

     }


     function reset(){
        var from_date = $("#month").val("");
       var to_date = $("#status").val("");
       salary_report_table.draw();
     }


     $(document).on("click", "#get_salary_pdf", function() {

        var salary_month = $("#salary_month")[0].value;
        var status = $("#status")[0].value;

       if(salary_month!=="" && status !=="" ){

        $.ajax({
            headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
            url:"{{ url('get-salary-pdf') }}",
            type:"POST",
            data:{salary_month:salary_month, status:status},
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
            link.download = 'easypaisa_paid_detail_list.pdf';
            document.body.appendChild(link);
            link.click();
            }
        })

       }
       

    })

</script>