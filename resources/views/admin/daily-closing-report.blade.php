


<div class="d-flex justify-content-center">
    <div class="row p-2 w-50">
        <div class="col">
            <input type="date" id="from_closing_date" name="from_closing_date" class="form-control" onchange="checkVal(this)">
        </div>
        <div class="col">
            <input type="date" id="to_closing_date" name="to_closing_date" class="form-control" onchange="checkVal(this)">
        </div>
        <div>
            <input type="button"  value="Reset" class="btn  btn-secondary" onclick="reset()">
            {{-- <input type="button"  value="PDF" class="btn btn-danger" id="get_easypaisa_pdf"> --}}
        </div>
</div>

    
</div>
 <div class="table-responsive">
                <table class="table table-bordered datatable_closing_list" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
 </div>
    
    



<script>
    var closing_table = $('.datatable_closing_list').DataTable({
         processing: true,
         serverSide: true,
         searching: false,
         "language": {
             "infoFiltered": ""
         },

         ajax: {
             url: "{{ url('get-full-report-of-closing') }}",
             data: function(d) {
                 d.from_closing_date = $("#from_closing_date").val()
                 d.to_closing_date = $("#to_closing_date").val()
             }
         },
         columns: [

             {
                 data: 'date',
                 name: 'date'
             },
             {
                 data: 'action',
                 name: 'action',
                 orderable: false,
                 searchable: false
             },

         ],

         success: function(data) {
           
         }
     });


     function checkVal(e){
       var from_date = $("#from_closing_date")[0].value;
       var to_date = $("#to_closing_date")[0].value;
    

       if(from_date!=="" && to_date!==""){
        closing_table.draw();
       }
     }


     function reset(){
        var from_date = $("#from_date").val("");
       var to_date = $("#to_date").val("");
       easypaisa_full_report_table.draw();
     }







 $(document).on("click", "#get_easypaisa_pdf", function() {

    var from_date = $("#from_date")[0].value;
    var to_date = $("#to_date")[0].value;
    if(from_date!=="" && to_date!=="")

    {
$.ajax({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
        url:"{{ url('get-pdf-report-of-easypaisa-amount') }}",
        type:"GET",
        data:{from_date:from_date,to_date:to_date,type:type},
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





$(document).on("click", ".single-view-closing", function() {

    var date = $(this).data("id");
    var url = "{{ url('get-closing-view') }}" + "/" +date;
    viewModal(url);

})









// var date = $(this).data("date");

//             $.ajax({
//                 headers: {
//                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                 },
//                     url:"{{ url('get-closing-pdf') }}",
//                     type:"GET",
//                     data:{date:date},
//                     success:function(data){
//                         const pdfData = data[0];
//                         // Create a blob object from the base64-encoded data
//                         const byteCharacters = atob(pdfData);
//                         const byteNumbers = new Array(byteCharacters.length);
//                         for (let i = 0; i < byteCharacters.length; i++) {
//                             byteNumbers[i] = byteCharacters.charCodeAt(i);
//                         }
//                         const byteArray = new Uint8Array(byteNumbers);
//                         const blob = new Blob([byteArray], {type: 'application/pdf'});


//                         // Create a URL for the blob object
//                         const url = URL.createObjectURL(blob);

//                         // Create a link element with the URL and click on it to download the PDF file
//                         const link = document.createElement('a');
//                         link.href = url;
//                         link.download = 'test.pdf';
//                         document.body.appendChild(link);
//                         link.click();
//                     }
//             })


</script>


