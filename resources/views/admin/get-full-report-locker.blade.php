

<div class="row p-2">
    <div class="col">
        @csrf
        <input type="date" id="from_date_locker" name="from_date_locker" class="form-control" onchange="checkLockerVal(this)">
    </div>
    <div class="col">
        <input type="date" id="to_date_locker" name="to_date_locker" class="form-control" onchange="checkLockerVal(this)">
    </div>
    <div>
        <input type="button"  value="Reset" class="btn  btn-secondary" onclick="resetLocker()">
        <input type="button"  value="PDF" class="btn btn-danger" id="get_locker_amount_pdf">
    </div>
</div>
<div class="table-responsive">
    <table class="table table-bordered locker_grand_report_table" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Date</th>
                <th>Amount</th>
                <th>Operator</th>
                <th>Remarks</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        </tbody>
    </table>
</div>

<script>
    
var locker_grand_report_table = $('.locker_grand_report_table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            // paging: false,
            // "info": false,
            "language": {
                "infoFiltered": ""
            },
            ajax: {
                url: "{{ url('locker-amount-list') }}",
                data: function(d) {
                    d.from_date_locker = $("#from_date_locker").val();
                    d.to_date_locker = $("#to_date_locker").val();
                }
            },
            columns: [
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                // {
                //     data: 'deducted_amount',
                //     name: 'deducted_amount'
                // },
                {
                    data: 'operator',
                    name: 'operator'
                },
                {
                    data: 'remarks',
                    name: 'remarks'
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


        $(document).on("click", ".edit_locker_amount", function() {
            
            $("#close-list-view")[0].click();

            var id = $(this).data("id");

            $.ajax({
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                url:"{{ url('edit-locker-amount') }}",
                type:"POST",
                data:{id:id},
                success:function(data){
                   $("#locker_amount").val(data["amount"]);
                   $("#locker_remarks").val(data["remarks"]);
                   $("#locker_hidden_id").val(data["id"]);
                }


            })

         })




function checkLockerVal(){

    var from_date_locker = $("#from_date_locker")[0].value;
    var to_date_locker = $("#to_date_locker")[0].value;

    if(from_date_locker !=="" && to_date_locker !=="" ){

        locker_grand_report_table.draw();
    }
}






$(document).on("click", "#get_locker_amount_pdf", function() {

var from_date = $("#from_date_locker")[0].value;
var to_date = $("#to_date_locker")[0].value;

if(from_date!=="" && to_date!=="")

{
$.ajax({
headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
},
    url:"{{ url('get-pdf-locker-amount') }}",
    type:"get",
    data:{from_date:from_date,to_date:to_date},
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