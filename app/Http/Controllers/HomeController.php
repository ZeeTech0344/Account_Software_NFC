<?php

namespace App\Http\Controllers;
use App\Models\BankAccount;
use App\Models\Closing;
use App\Models\easypaisa_amount_paid_detail;
use App\Models\EasypaisaAmount;
use App\Models\EasypaisaPaidAmount;
use App\Models\Employee;
use App\Models\foodpandaAmount;
use App\Models\HblAmounts;
use App\Models\Head;
use App\Models\HeadLocation;
use App\Models\installment;
use App\Models\Locker;
use App\Models\LockerAmountOutSource;
use App\Models\lockerDetail;
use App\Models\OtherSalary;
use App\Models\ownerPending;
use App\Models\PayVendorAmount;
use App\Models\Pending;
use App\Models\ReturnAmount;
use App\Models\Ride;
use App\Models\Sadqa;
use App\Models\salary;
use App\Models\StoreAmmount;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use PDF;
use Rap2hpoutre\FastExcel\FastExcel;
use Termwind\Components\Raw;

class HomeController extends Controller
{
    function editPaySadqa(Request $req){
        $sadqa = Sadqa::find($req->id);
        return response()->json($sadqa, 200);
    }

    function getVendorData(Request $req){

        $vendor_id = $req->vendor_id;

        $vendor_old_amount = Vendor::where("employee_id", $vendor_id)->sum("total_amount");

        $vendor_pay_amount = payVendorAmount::where("employee_id", $vendor_id)->sum("paid_amount");


        $remaining = $vendor_old_amount - $vendor_pay_amount;

        return response()->json($remaining,200);

    }

    function viewChart(){

        //sale
        //  $data = Closing::selectRaw('year(created_at) year, monthname(created_at) month, sum(amount) as sum')
        // ->where("head",2)
        // ->groupBy('year', 'month')
        // ->orderBy('id', 'asc')
        // ->get()->toArray();

        $data = Closing::selectRaw("CONCAT(day(created_at),' ', MONTHNAME(created_at), ' ', YEAR(created_at)) as month, SUM(amount) as sum")
        ->where("head", 2)
        ->groupBy('month')
         ->orderBy('id', 'asc')
        ->get()
        ->toArray();
        

        $create_data_by_month_sale = json_decode(json_encode($data), true);


         //demand
         $data_demand = Closing::selectRaw("CONCAT(day(created_at),' ', MONTHNAME(created_at), ' ', YEAR(created_at)) as month, SUM(amount) as sum")
        ->where("head", 1)
        ->groupBy('month')
         ->orderBy('id', 'asc')
        ->get()
        ->toArray();

        $create_data_by_month_demand = json_decode(json_encode($data_demand), true);

        return view("admin.view-chart", compact("create_data_by_month_sale", "create_data_by_month_demand"));
    }

    function viewChartBrachWise(){



        $data = DB::table('closings')
        ->join('head_locations', 'head_locations.id', '=', 'closings.location')
        ->selectRaw('head_locations.location AS head_location , sum(closings.amount) as sale')
        ->where("closings.head",2)
        ->where("head_locations.location","!=","Pindi")
        ->groupBy('head_location')
        ->orderBy('head_location')
        ->get();


        $create_data_by_month_sale = json_decode(json_encode($data), true);


        $data_demand = DB::table('closings')
        ->join('head_locations', 'head_locations.id', '=', 'closings.location')
        ->selectRaw('head_locations.location AS head_location , sum(closings.amount) as demand')
        ->where("closings.head",1)
        ->where("head_locations.location","!=","Pindi")
        ->groupBy('head_location')
        ->orderBy('head_location')
        ->get();

        $create_data_by_month_demand = json_decode(json_encode($data_demand), true);

        return view("admin.view-chart-branchwise", compact("create_data_by_month_sale", "create_data_by_month_demand"));

    }


    function viewChartProfitLoss(){

        $sale = Closing::selectRaw("CONCAT( MONTHNAME(created_at), ' ', YEAR(created_at)) as month, SUM(amount) as sale")
        ->where("head", 2)
        ->groupBy('month')
         ->orderBy('id', 'asc')
        ->get()
        ->toArray();

        $create_data_by_sale = json_decode(json_encode($sale), true);

        $demand = Closing::selectRaw("CONCAT( MONTHNAME(created_at), ' ', YEAR(created_at)) as month, SUM(amount) as demand")
        ->where("head", 1)
        ->groupBy('month')
         ->orderBy('id', 'asc')
        ->get()
        ->toArray();

        $create_data_by_demand = json_decode(json_encode($demand), true);


        $easypaisa_out = EasypaisaPaidAmount::selectRaw("CONCAT( MONTHNAME(created_at), ' ', YEAR(created_at)) as month, SUM(amount) as easypaisa")
        ->where("amount_status", "Out")
        ->groupBy('month')
         ->orderBy('id', 'asc')
        ->get()
        ->toArray();

        $create_data_by_easypaisa_out = json_decode(json_encode($easypaisa_out), true);


        $locker_out = lockerDetail::selectRaw("CONCAT( MONTHNAME(created_at), ' ', YEAR(created_at)) as month, SUM(amount) as locker")
        ->where("amount_status", "Out")
        ->groupBy('month')
         ->orderBy('id', 'asc')
        ->get()
        ->toArray();

        $create_data_by_locker_out = json_decode(json_encode($locker_out), true);


        $hbl_out = HblAmounts::selectRaw("CONCAT( MONTHNAME(created_at), ' ', YEAR(created_at)) as month, SUM(amount) as hbl")
        ->where("amount_status", "Out")
        ->groupBy('month')
         ->orderBy('id', 'asc')
        ->get()
        ->toArray();

        $create_data_by_hbl_out = json_decode(json_encode($hbl_out), true);

        $locker_in = lockerDetail::selectRaw("CONCAT( MONTHNAME(created_at), ' ', YEAR(created_at)) as month, SUM(amount) as locker_in")
        ->where("amount_status", "In")
        ->groupBy('month')
         ->orderBy('id', 'asc')
        ->get()
        ->toArray();

        $create_data_by_locker_in = json_decode(json_encode($locker_in), true);

        return view("admin.view-chart-profit-list", compact("create_data_by_sale", "create_data_by_demand", "create_data_by_easypaisa_out",
        "create_data_by_hbl_out","create_data_by_locker_out","create_data_by_locker_in"));


       
    }

    function getVendorListWithFullDetail(Request $req){

         $vendors = Employee::withSum('getVendorsReserveAmount', 'total_amount')->withSum('getVendorsPayAmount', 'paid_amount')->where("employee_type","Vendors")
         ->get();


         $html = [];
         $html["title"] = "Vendor Detail View";
         $html["view"] = view("admin.vendor-detail", compact('vendors'))->render();
         return response()->json($html, 200);


         //return view("admin.vendor-detail", compact("vendors"));
    }

    function hblFullReportSecondViewPdf(Request $req){

        ini_set('memory_limit', '-1');

        $from_date = $req->from_date;
        $to_date = $req->to_date;
        
        $foodpanda_old_amounts_easypaisa = DB::table('foodpanda_amounts')
        ->whereDate("created_at", "<", $from_date)
        ->where("account", "HBL")
        ->sum("amount");

        
        $closing_old_amount_easypaisa = DB::table('closings')
        ->where("closings.head",4)
        ->where("closings.location", "!=" ,6)
        ->whereDate("closings.created_at", "<", $from_date)
        ->sum("amount");


        $get_data_old_sum = HblAmounts::whereDate("created_at", "<", $from_date)
        ->sum("amount");

        $hbl_reserve_amount = hblReserveAmount();
       
        $grand_final_old_amount = ($hbl_reserve_amount + $foodpanda_old_amounts_easypaisa + $closing_old_amount_easypaisa) -  $get_data_old_sum ;

        //foodpanda amount get
        $foodpanda_get = foodpandaAmount::
         where("account", "HBL")
        ->whereDate("created_at", ">=", $from_date)
        ->whereDate("created_at", "<=", $to_date)
        ->selectRaw('foodpanda_amounts.is_this_foodpanda as head, foodpanda_amounts.amount as amount, foodpanda_amounts.amount_status, foodpanda_amounts.created_at, foodpanda_amounts.remarks')
        ->get();

        $foodpanda = json_decode(json_encode($foodpanda_get), true);

    

        //sum of closing (new city basti taxila)
        $sum_of_easypaisa_get = DB::table('closings')
        ->join('heads', 'heads.id', '=', 'closings.head')
        ->join('head_locations', 'head_locations.id', '=', 'closings.location')
        ->selectRaw('CONCAT( COALESCE(heads.head,""), " " , COALESCE(head_locations.location , "") ) AS head , sum(closings.amount) as amount , closings.amount_status, closings.created_at')
        ->where("closings.head", 4)
        ->where("closings.location", "!=" ,6)
        ->whereDate("closings.created_at", ">=", $from_date)
        ->whereDate("closings.created_at", "<=", $to_date)
        ->groupBy('closings.created_at','head','closings.amount', 'closings.amount_status')
        ->get();

        $sum_of_easypaisa_datewise = json_decode(json_encode($sum_of_easypaisa_get), true);


        //locker detail
        $get_data = DB::table('hbl_amounts')
                ->join('employees', 'employees.id', '=', 'hbl_amounts.employee_id')
                ->selectRaw(' CONCAT(COALESCE(employees.employee_name, ""), " " , COALESCE(employees.employee_post, "") ," - ",COALESCE(hbl_amounts.purpose)) AS head , hbl_amounts.amount, hbl_amounts.amount_status, hbl_amounts.remarks ,hbl_amounts.created_at')
                ->whereDate("hbl_amounts.created_at", ">=", $from_date)
                ->whereDate("hbl_amounts.created_at", "<=", $to_date)
                ->get();

         $data = json_decode(json_encode($get_data), true);

        
        
         $pdf = PDF::loadView("admin.hbl-full-report-second-view-pdf", compact('grand_final_old_amount','foodpanda','data','sum_of_easypaisa_datewise'));
         $file = $pdf->download('locker_pdf.pdf');
         return response()->json([base64_encode($file)], 200);
       
 
         ini_set('memory_limit', '128M');
       
    }


    function hblFullReportSecondView(Request $req, $from_date=null, $to_date=null, $type=null, $employee_others=null){


        
        $foodpanda_old_amounts_easypaisa = DB::table('foodpanda_amounts')
        ->whereDate("created_at", "<", $from_date)
        ->where("account", "HBL")
        ->sum("amount");

        
        $closing_old_amount_easypaisa = DB::table('closings')
        ->where("closings.head",4)
        ->where("closings.location","!=",6)
        ->whereDate("closings.created_at", "<", $from_date)
        ->sum("amount");


        $get_data_old_sum = HblAmounts::whereDate("created_at", "<", $from_date)
        ->sum("amount");

        $hbl_reserve_amount = hblReserveAmount();
        $grand_final_old_amount = ($hbl_reserve_amount + $foodpanda_old_amounts_easypaisa + $closing_old_amount_easypaisa) -  $get_data_old_sum;



        //foodpanda amount get
        $foodpanda_get = foodpandaAmount::
         where("account", "HBL")
        ->whereDate("created_at", ">=", $from_date)
        ->whereDate("created_at", "<=", $to_date)
        ->selectRaw('foodpanda_amounts.is_this_foodpanda as head, foodpanda_amounts.amount as amount, foodpanda_amounts.amount_status, foodpanda_amounts.created_at, foodpanda_amounts.remarks')
        ->get();

        $foodpanda = json_decode(json_encode($foodpanda_get), true);

    

        //sum of closing (new city basti taxila)
        $sum_of_easypaisa_get = DB::table('closings')
        ->join('heads', 'heads.id', '=', 'closings.head')
        ->join('head_locations', 'head_locations.id', '=', 'closings.location')
        ->selectRaw('CONCAT( COALESCE(heads.head,""), " " , COALESCE(head_locations.location , "") ) AS head , sum(closings.amount) as amount , closings.amount_status, closings.created_at')
        ->where("closings.head",4)
        ->where("closings.location","!=",6)
        ->whereDate("closings.created_at", ">=", $from_date)
        ->whereDate("closings.created_at", "<=", $to_date)
        ->groupBy('closings.created_at','head','closings.amount', 'closings.amount_status')
        ->get();

        $sum_of_easypaisa_datewise = json_decode(json_encode($sum_of_easypaisa_get), true);


        //locker detail
        $get_data = DB::table('hbl_amounts')
                ->join('employees', 'employees.id', '=', 'hbl_amounts.employee_id')
                ->selectRaw(' CONCAT(COALESCE(employees.employee_name, ""), " " , COALESCE(employees.employee_post, "") ," - ",COALESCE(hbl_amounts.purpose)) AS head , hbl_amounts.amount, hbl_amounts.amount_status, hbl_amounts.remarks ,hbl_amounts.created_at')
                ->whereDate("hbl_amounts.created_at", ">=", $from_date)
                ->whereDate("hbl_amounts.created_at", "<=", $to_date)
                ->get();

         $data = json_decode(json_encode($get_data), true);

        

        $html = [];
        $html["title"] = "HBL Grand Report View";
        $html["view"] = view("admin.hbl-full-report-second-view", compact('grand_final_old_amount','foodpanda','data','sum_of_easypaisa_datewise'))->render();
        return response()->json($html, 200);


    }


    function getViewHblAmountNewCreatedSecond(Request $req, $from_date=null, $to_date=null, $type=null, $employee_others=null){

        if ($from_date && $to_date && $type && $employee_others) {
            

            $data = HblAmounts::with("getEmployee:id,employee_name,employee_post")
                ->where("purpose", $type)
                ->where("employee_id", $employee_others)
                ->whereDate("created_at", ">=", $from_date)
                ->whereDate("created_at", "<=", $to_date)
                ->get();

        }elseif ($req->from_date && $req->to_date && $req->type) {
            
            $data = HblAmounts::with("getEmployee:id,employee_name,employee_post")
                ->where("purpose", $type)
                ->whereDate("created_at", ">=", $from_date)
                ->whereDate("created_at", "<=", $to_date)
                ->get();

        }

        $html = [];
        $html["title"] = "Locker Grand Report View";
        $html["view"] = view("admin.get-view-hbl-amount-second", compact('data'))->render();
        return response()->json($html, 200);
    }

    function getViewEasypaisaAmountNewCreatedSecond(Request $req, $from_date=null, $to_date=null, $type=null, $employee_others=null){

        if ($from_date && $to_date && $type && $employee_others) {
            

            $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                ->where("purpose", $type)
                ->where("employee_id", $employee_others)
                ->whereDate("created_at", ">=", $from_date)
                ->whereDate("created_at", "<=", $to_date)
                ->get();

        }elseif ($req->from_date && $req->to_date && $req->type) {
            
            $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                ->where("purpose", $type)
                ->whereDate("created_at", ">=", $from_date)
                ->whereDate("created_at", "<=", $to_date)
                ->get();

        }

        $html = [];
        $html["title"] = "Locker Grand Report View";
        $html["view"] = view("admin.get-view-easypaisa-amount-second", compact('data'))->render();
        return response()->json($html, 200);
        
    }

    function getViewLockerAmountNewCreated(Request $req, $from_date, $to_date, $type = null, $employee_others = null){

        // $from_date = "2023-07-26";
        // $to_date = "2023-07-28";
        
        // $from = $from_date;
        // $to = $to_date;
         
        //create_old sum of all tables to create last amount close

         $foodpanda_old_amounts = DB::table('foodpanda_amounts')
         ->where("account","Locker")
        ->whereDate("created_at", "<", $from_date)
        ->sum("amount");

        $locker_outsource_old = DB::table('locker_amount_out_sources')
        ->whereDate("created_at", "<", $from_date)
        ->sum("amount");

        // $installment_old = DB::table('installments')
        // ->whereDate("created_at", "<", $from_date)
        // ->sum("pay_installment");

        //when we pay sadqa to someone
        // $sadqa = DB::table('sadqas')
        // ->whereDate("created_at", "<", $from_date)
        // ->sum("pay_sadqa_amount");

        $closing_old_amount = DB::table('closings')
        ->where("closings.head",9)
        ->whereIn('closings.location', [1,2,3])
        ->whereDate("closings.created_at", "<", $from_date)
        ->sum("amount");


         $sadqa_calculate_from_sale_old = DB::table('closings')
        ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as formatted_date , sum(closings.amount) as sum'))
        ->where("closings.head",2)
        ->where("location", "!=" ,6)
        ->whereDate("closings.created_at", "<", $from_date)
        ->groupBy("formatted_date")
        ->get();

        
        
        $sadqa_caculate_total = 0;

        foreach($sadqa_calculate_from_sale_old as $sale_sum){
            $sadqa_caculate_total = $sadqa_caculate_total + ($sale_sum->sum > 0 ? ceil((($sale_sum->sum  / 100) * 2) / 10) * 10 : 0);
        }

        

       
        $get_data_old = DB::table('locker_details')
                    ->whereDate("locker_details.created_at", "<", $from_date)
                    ->sum("amount");

        
                    
        $locker_reserve_amount = LockerReserveAmount();

        $grand_final_old_amount = ($locker_reserve_amount + $foodpanda_old_amounts + $locker_outsource_old + $closing_old_amount  - $get_data_old ) -  $sadqa_caculate_total;



        //foodpanda amount get
        $foodpanda_get = foodpandaAmount::
         where("account", "Locker")
        ->whereDate("created_at", ">=", $from_date)
        ->whereDate("created_at", "<=", $to_date)
        ->selectRaw('foodpanda_amounts.is_this_foodpanda as head, foodpanda_amounts.amount as amount, foodpanda_amounts.amount_status, foodpanda_amounts.created_at, foodpanda_amounts.remarks')
        ->get();

        $foodpanda = json_decode(json_encode($foodpanda_get), true);


        //locker outsource amount get
        $outsource_get = LockerAmountOutSource::
        whereDate("created_at", ">=", $from_date)
        ->whereDate("created_at", "<=", $to_date)
        ->selectRaw('locker_amount_out_sources.is_this_outsource_locker as head, locker_amount_out_sources.amount as amount, locker_amount_out_sources.amount_status, locker_amount_out_sources.remarks ,locker_amount_out_sources.created_at')
        ->get()->toArray();
        
        $outsource = json_decode(json_encode($outsource_get), true);


        //installment get
        // $installment_get = installment::
        // whereDate("created_at", ">=", $from_date)
        // ->whereDate("created_at", "<=", $to_date)
        // ->selectRaw('installments.is_this_installment as head, installments.pay_installment as amount, installments.amount_status, installments.purpose as remarks ,installments.created_at')
        // ->get();

        // $installment = json_decode(json_encode($installment_get), true);


        //pay Sadqa Get
        // $sadqa_get = Sadqa::
        // whereDate("created_at", ">=", $from_date)
        // ->whereDate("created_at", "<=", $to_date)
        // ->selectRaw('sadqas.is_this_sadqa as head, sadqas.pay_sadqa_amount as amount, sadqas.amount_status, sadqas.pay_to as remarks ,sadqas.created_at')
        // ->get();

        // $sadqa = json_decode(json_encode($sadqa_get), true);


        //sum of closing (new city basti taxila)
        $sum_of_sale_datewise_get = DB::table('closings')
        ->join('heads', 'heads.id', '=', 'closings.head')
        ->join('head_locations', 'head_locations.id', '=', 'closings.location')
        ->selectRaw(' CONCAT(heads.head, " ",  head_locations.location) AS head , closings.amount, closings.amount_status, closings.created_at')
        ->where("closings.head",9)
        ->whereIn('closings.location', [1,2,3])
        ->whereDate("closings.created_at", ">=", $from_date)
        ->whereDate("closings.created_at", "<=", $to_date)
        ->groupBy('closings.created_at','closings.location','closings.head','head','closings.amount', 'closings.amount_status')
        ->get();

        $sum_of_sale_datewise = json_decode(json_encode($sum_of_sale_datewise_get), true);




        //daily sadqa deducation from sale is created
        $sadqa_calculate_from_sale = DB::table('closings')
        ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as formatted_date , sum(closings.amount) as sum'))
        ->where("closings.head",2)
        ->where("location", "!=" ,6)
        ->whereDate("closings.created_at", ">=", $from_date)
        ->whereDate("closings.created_at", "<=", $to_date)
        ->groupBy("formatted_date")
        ->get();

        
        $array_created_for_sadqa_deduction = [];
        $sadqa_caculate = [];

        foreach( $sadqa_calculate_from_sale  as $sale_sum){

            
            $sadqa_caculate["head"] = "Daily Sadqa";
            $sadqa_caculate["amount"] = ($sale_sum->sum > 0 ? ceil((($sale_sum->sum  / 100) * 2) / 10) * 10 : 0);
            $sadqa_caculate["amount_status"] = "Out";
            $sadqa_caculate["created_at"] = $sale_sum->formatted_date;

            array_push($array_created_for_sadqa_deduction, $sadqa_caculate);
            
        }

        //locker detail
        $get_data = DB::table('locker_details')
                ->join('employees', 'employees.id', '=', 'locker_details.employee_id')
                ->selectRaw(' CONCAT(COALESCE(employees.employee_name, ""), " " , COALESCE(employees.employee_post, "") ," - ",COALESCE(locker_details.purpose)) AS head , locker_details.amount, locker_details.amount_status, locker_details.remarks ,locker_details.created_at')
                ->whereDate("locker_details.created_at", ">=", $from_date)
                ->whereDate("locker_details.created_at", "<=", $to_date)
                ->get();

        $data = json_decode(json_encode($get_data), true);


        $html = [];
        $html["title"] = "Locker Grand Report View";
        $html["view"] = view("admin.test-locker-amount", compact('grand_final_old_amount','array_created_for_sadqa_deduction','sum_of_sale_datewise','outsource','foodpanda','data', 'sum_of_sale_datewise'))->render();
        return response()->json($html, 200);


        // $total_count = count($data->get());
     
        //return    view("admin.test-locker-amount", compact('grand_final_old_amount','array_created_for_sadqa_deduction','sum_of_sale_datewise','sadqa','installment','outsource','foodpanda','data', 'from', 'to', 'sum_of_sale_datewise'));

    }



    function getViewLockerAmountNewCreatedPdf(Request $req){


        ini_set('memory_limit', '-1');
       
        $from_date = $req->from_date;
        $to_date = $req->to_date;
        $type = $req->type;
        $employee_others = $req->employee_others;

        
        $foodpanda_old_amounts = DB::table('foodpanda_amounts')
        ->where("account","Locker")
       ->whereDate("created_at", "<", $from_date)
       ->sum("amount");

       $locker_outsource_old = DB::table('locker_amount_out_sources')
       ->whereDate("created_at", "<", $from_date)
       ->sum("amount");

       // $installment_old = DB::table('installments')
       // ->whereDate("created_at", "<", $from_date)
       // ->sum("pay_installment");

       //when we pay sadqa to someone
       // $sadqa = DB::table('sadqas')
       // ->whereDate("created_at", "<", $from_date)
       // ->sum("pay_sadqa_amount");

       $closing_old_amount = DB::table('closings')
       ->where("closings.head",9)
       ->whereIn('closings.location', [1,2,3])
       ->whereDate("closings.created_at", "<", $from_date)
       ->sum("amount");


        $sadqa_calculate_from_sale_old = DB::table('closings')
       ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as formatted_date , sum(closings.amount) as sum'))
       ->where("closings.head",2)
       ->where("location", "!=" ,6)
       ->whereDate("closings.created_at", "<", $from_date)
       ->groupBy("formatted_date")
       ->get();

       
       
       $sadqa_caculate_total = 0;

       foreach($sadqa_calculate_from_sale_old as $sale_sum){
           $sadqa_caculate_total = $sadqa_caculate_total + ($sale_sum->sum > 0 ? ceil((($sale_sum->sum  / 100) * 2) / 10) * 10 : 0);
       }

       

      
       $get_data_old = DB::table('locker_details')
                   ->whereDate("locker_details.created_at", "<", $from_date)
                   ->sum("amount");

       
                   
       $locker_reserve_amount = LockerReserveAmount();

       $grand_final_old_amount = ($locker_reserve_amount + $foodpanda_old_amounts + $locker_outsource_old + $closing_old_amount  - $get_data_old ) -  $sadqa_caculate_total;



       //foodpanda amount get
       $foodpanda_get = foodpandaAmount::
        where("account", "Locker")
       ->whereDate("created_at", ">=", $from_date)
       ->whereDate("created_at", "<=", $to_date)
       ->selectRaw('foodpanda_amounts.is_this_foodpanda as head, foodpanda_amounts.amount as amount, foodpanda_amounts.amount_status, foodpanda_amounts.created_at, foodpanda_amounts.remarks')
       ->get();

       $foodpanda = json_decode(json_encode($foodpanda_get), true);


       //locker outsource amount get
       $outsource_get = LockerAmountOutSource::
       whereDate("created_at", ">=", $from_date)
       ->whereDate("created_at", "<=", $to_date)
       ->selectRaw('locker_amount_out_sources.is_this_outsource_locker as head, locker_amount_out_sources.amount as amount, locker_amount_out_sources.amount_status, locker_amount_out_sources.remarks ,locker_amount_out_sources.created_at')
       ->get()->toArray();
       
       $outsource = json_decode(json_encode($outsource_get), true);


       //installment get
       // $installment_get = installment::
       // whereDate("created_at", ">=", $from_date)
       // ->whereDate("created_at", "<=", $to_date)
       // ->selectRaw('installments.is_this_installment as head, installments.pay_installment as amount, installments.amount_status, installments.purpose as remarks ,installments.created_at')
       // ->get();

       // $installment = json_decode(json_encode($installment_get), true);


       //pay Sadqa Get
       // $sadqa_get = Sadqa::
       // whereDate("created_at", ">=", $from_date)
       // ->whereDate("created_at", "<=", $to_date)
       // ->selectRaw('sadqas.is_this_sadqa as head, sadqas.pay_sadqa_amount as amount, sadqas.amount_status, sadqas.pay_to as remarks ,sadqas.created_at')
       // ->get();

       // $sadqa = json_decode(json_encode($sadqa_get), true);


       //sum of closing (new city basti taxila)
       $sum_of_sale_datewise_get = DB::table('closings')
       ->join('heads', 'heads.id', '=', 'closings.head')
       ->join('head_locations', 'head_locations.id', '=', 'closings.location')
       ->selectRaw(' CONCAT(heads.head, " ",  head_locations.location) AS head , closings.amount, closings.amount_status, closings.created_at')
       ->where("closings.head",9)
       ->whereIn('closings.location', [1,2,3])
       ->whereDate("closings.created_at", ">=", $from_date)
       ->whereDate("closings.created_at", "<=", $to_date)
       ->groupBy('closings.created_at','closings.location','closings.head','head','closings.amount', 'closings.amount_status')
       ->get();

       $sum_of_sale_datewise = json_decode(json_encode($sum_of_sale_datewise_get), true);




       //daily sadqa deducation from sale is created
       $sadqa_calculate_from_sale = DB::table('closings')
       ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as formatted_date , sum(closings.amount) as sum'))
       ->where("closings.head",2)
       ->where("location", "!=" ,6)
       ->whereDate("closings.created_at", ">=", $from_date)
       ->whereDate("closings.created_at", "<=", $to_date)
       ->groupBy("formatted_date")
       ->get();

       
       $array_created_for_sadqa_deduction = [];
       $sadqa_caculate = [];

       foreach( $sadqa_calculate_from_sale  as $sale_sum){

           
           $sadqa_caculate["head"] = "Daily Sadqa";
           $sadqa_caculate["amount"] = ($sale_sum->sum > 0 ? ceil((($sale_sum->sum  / 100) * 2) / 10) * 10 : 0);
           $sadqa_caculate["amount_status"] = "Out";
           $sadqa_caculate["created_at"] = $sale_sum->formatted_date;

           array_push($array_created_for_sadqa_deduction, $sadqa_caculate);
           
       }

       //locker detail
       $get_data = DB::table('locker_details')
               ->join('employees', 'employees.id', '=', 'locker_details.employee_id')
               ->selectRaw(' CONCAT(COALESCE(employees.employee_name, ""), " " , COALESCE(employees.employee_post, "") ," - ",COALESCE(locker_details.purpose)) AS head , locker_details.amount, locker_details.amount_status, locker_details.remarks ,locker_details.created_at')
               ->whereDate("locker_details.created_at", ">=", $from_date)
               ->whereDate("locker_details.created_at", "<=", $to_date)
               ->get();

       $data = json_decode(json_encode($get_data), true);




        $pdf = PDF::loadView("admin.test-locker-pdf", compact('grand_final_old_amount','array_created_for_sadqa_deduction','sum_of_sale_datewise','outsource','foodpanda','data', 'sum_of_sale_datewise'));
        $file = $pdf->download('locker_pdf.pdf');
        return response()->json([base64_encode($file)], 200);
      

        ini_set('memory_limit', '128M');



    }


    function easypaisaFullReportSecondViewPdf(Request $req){

        ini_set('memory_limit', '-1');
       
        $from_date = $req->from_date;
        $to_date = $req->to_date;
    
        $foodpanda_old_amounts_easypaisa = DB::table('foodpanda_amounts')
        ->whereDate("created_at", "<", $from_date)
        ->where("account", "Easypaisa")
        ->sum("amount");

        
        $closing_old_amount_easypaisa = DB::table('closings')
        ->where("closings.head",5)
        ->where("closings.location", "!=" , 6)
        ->whereDate("closings.created_at", "<", $from_date)
        ->sum("amount");


        $get_data_old_sum = EasypaisaPaidAmount::whereDate("created_at", "<", $from_date)
        ->sum("amount");


     
        $easypaisa_reserve_amount = EasypaisaReserveAmount();
        
        $grand_final_old_amount = ($easypaisa_reserve_amount + $foodpanda_old_amounts_easypaisa + $closing_old_amount_easypaisa) -  $get_data_old_sum ;

        //foodpanda amount get
        $foodpanda_get = foodpandaAmount::
         where("account", "Easypaisa")
        ->whereDate("created_at", ">=", $from_date)
        ->whereDate("created_at", "<=", $to_date)
        ->selectRaw('foodpanda_amounts.is_this_foodpanda as head, foodpanda_amounts.amount as amount, foodpanda_amounts.amount_status, foodpanda_amounts.created_at, foodpanda_amounts.remarks')
        ->get();

        $foodpanda = json_decode(json_encode($foodpanda_get), true);

    

        //sum of closing (new city basti taxila)
        $sum_of_easypaisa_get = DB::table('closings')
        ->join('heads', 'heads.id', '=', 'closings.head')
        ->join('head_locations', 'head_locations.id', '=', 'closings.location')
        ->selectRaw('CONCAT( COALESCE(heads.head,""), " " , COALESCE(head_locations.location , "") ) AS head , sum(closings.amount) as amount , closings.amount_status, closings.created_at')
        ->where("closings.head",5)
        ->where("closings.location", "!=" , 6)
        ->whereDate("closings.created_at", ">=", $from_date)
        ->whereDate("closings.created_at", "<=", $to_date)
        ->groupBy('closings.created_at','head','closings.amount', 'closings.amount_status')
        ->get();

        $sum_of_easypaisa_datewise = json_decode(json_encode($sum_of_easypaisa_get), true);


        //locker detail
        $get_data = DB::table('easypaisa_paid_amounts')
                ->join('employees', 'employees.id', '=', 'easypaisa_paid_amounts.employee_id')
                ->selectRaw(' CONCAT(COALESCE(employees.employee_name, ""), " " , COALESCE(employees.employee_post, "") ," - ",COALESCE(easypaisa_paid_amounts.purpose)) AS head , easypaisa_paid_amounts.amount, easypaisa_paid_amounts.amount_status, easypaisa_paid_amounts.remarks ,easypaisa_paid_amounts.created_at')
                ->whereDate("easypaisa_paid_amounts.created_at", ">=", $from_date)
                ->whereDate("easypaisa_paid_amounts.created_at", "<=", $to_date)
                ->get();

         $data = json_decode(json_encode($get_data), true);

        
        $pdf = PDF::loadView("admin.easypaisa-full-report-second-view-pdf", compact('grand_final_old_amount','foodpanda','data','sum_of_easypaisa_datewise'));
        $file = $pdf->download('easypaisa_closing.pdf');
        return response()->json([base64_encode($file)], 200);

        ini_set('memory_limit', '128M');
    }



    function easypaisaFullReportSecondView(Request $req, $from_date=null, $to_date=null, $type=null, $employee_others=null){

      
        $foodpanda_old_amounts_easypaisa = DB::table('foodpanda_amounts')
        ->whereDate("created_at", "<", $from_date)
        ->where("account", "Easypaisa")
        ->sum("amount");

        
        $closing_old_amount_easypaisa = DB::table('closings')
        ->where("closings.head",5)
        ->where("closings.location", "!=" , 6)
        ->whereDate("closings.created_at", "<", $from_date)
        ->sum("amount");


        $get_data_old_sum = EasypaisaPaidAmount::whereDate("created_at", "<", $from_date)
        ->sum("amount");

       
        $easypaisa_reserve_amount = EasypaisaReserveAmount();
        
        $grand_final_old_amount = ($easypaisa_reserve_amount + $foodpanda_old_amounts_easypaisa + $closing_old_amount_easypaisa) -  $get_data_old_sum ;




        //foodpanda amount get
        $foodpanda_get = foodpandaAmount::
         where("account", "Easypaisa")
        ->whereDate("created_at", ">=", $from_date)
        ->whereDate("created_at", "<=", $to_date)
        ->selectRaw('foodpanda_amounts.is_this_foodpanda as head, foodpanda_amounts.amount as amount, foodpanda_amounts.amount_status, foodpanda_amounts.created_at, foodpanda_amounts.remarks')
        ->get();

        $foodpanda = json_decode(json_encode($foodpanda_get), true);

    

        //sum of closing (new city basti taxila)
        $sum_of_easypaisa_get = DB::table('closings')
        ->join('heads', 'heads.id', '=', 'closings.head')
        ->join('head_locations', 'head_locations.id', '=', 'closings.location')
        ->selectRaw('CONCAT( COALESCE(heads.head,""), " " , COALESCE(head_locations.location , "") ) AS head , sum(closings.amount) as amount , closings.amount_status, closings.created_at')
        ->where("closings.head",5)
        ->where("closings.location", "!=" , 6)
        ->whereDate("closings.created_at", ">=", $from_date)
        ->whereDate("closings.created_at", "<=", $to_date)
        ->groupBy('closings.created_at','head','closings.amount', 'closings.amount_status')
        ->get();

        $sum_of_easypaisa_datewise = json_decode(json_encode($sum_of_easypaisa_get), true);


        //locker detail
        $get_data = DB::table('easypaisa_paid_amounts')
                ->join('employees', 'employees.id', '=', 'easypaisa_paid_amounts.employee_id')
                ->selectRaw(' CONCAT(COALESCE(employees.employee_name, ""), " " , COALESCE(employees.employee_post, "") ," - ",COALESCE(easypaisa_paid_amounts.purpose)) AS head , easypaisa_paid_amounts.amount, easypaisa_paid_amounts.amount_status, easypaisa_paid_amounts.remarks, easypaisa_paid_amounts.created_at')
                ->whereDate("easypaisa_paid_amounts.created_at", ">=", $from_date)
                ->whereDate("easypaisa_paid_amounts.created_at", "<=", $to_date)
                ->get();

         $data = json_decode(json_encode($get_data), true);

        

        $html = [];
        $html["title"] = "Easypaisa Grand Report View";
        $html["view"] = view("admin.easypaisa-full-report-second-view", compact('grand_final_old_amount','foodpanda','data','sum_of_easypaisa_datewise'))->render();
        return response()->json($html, 200);

    }

    //this is for single head calculation
    function getViewLockerAmountNewCreatedSecond(Request $req, $from_date=null, $to_date=null, $type=null, $employee_others=null){

        
        
        if ($from_date && $to_date && $type && $employee_others) {
            

             $data = lockerDetail::with("getEmployee:id,employee_name,employee_post")
                 ->where("purpose", $type)
                 ->where("employee_id", $employee_others)
                 ->whereDate("created_at", ">=", $from_date)
                 ->whereDate("created_at", "<=", $to_date)
                 ->get();

         }elseif ($req->from_date && $req->to_date && $req->type) {
             
             $data = lockerDetail::with("getEmployee:id,employee_name,employee_post")
                 ->where("purpose", $type)
                 ->whereDate("created_at", ">=", $from_date)
                 ->whereDate("created_at", "<=", $to_date)
                 ->get();

         }

         $html = [];
         $html["title"] = "Locker Grand Report View";
         $html["view"] = view("admin.get-view-locker-amount-second", compact('data'))->render();
         return response()->json($html, 200);
    }


    function getSalaryDetail(Request $req, $month_get){


        // $salary_detail = Employee::all();


        if($req->ajax()){

            $month = $month_get."-01";
            $last_date = date("Y-m-t" ,strtotime($month));

            $salary_detail = Employee::with("getEmployeeBranch:id,location")
            ->with(['easypaisa' => function ($query) use($month){
               $query->where('paid_for_month_date' ,$month)
               ->where("purpose","Advance");
           }])
           ->with(['hbl' => function ($query) use($month){
               $query->where('paid_for_month_date',$month)
               ->where("purpose","Advance");
           }])
           ->with(['locker' => function ($query) use($month){
               $query->where('paid_for_month_date' ,$month)
               ->where("purpose","Advance");
           }])
           ->with(['salary' => function ($query) use($month){
            $query->where('salary_month' ,$month);
            }])
            ->whereDate('joining', '<=', $last_date)
           ->where("employee_type", "Employee")
           ->where("employee_status", "On")
           ->get();

                
           $html = [];
           $html["title"] = "Salary Grand Detail (". date_format(date_create($month_get),"d-M-Y").")";
           $html["view"] = view("admin.get-salary-detail", compact("salary_detail", "month_get"))->render();
           return response()->json($html, 200);


        }

      


        //return view("admin.get-salary-detail", compact("salary_detail"));
    }

    function foodpandaToHbl(){

       
    
        return view("admin.foodpanda-to-hbl");
    }





    function insertFoodpandaToHblList(Request $req){


        if ($req->ajax()) {


                if($req->from_date && $req->to_date){
                  

                    $total_count = foodpandaAmount::
                    whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->count();
    
                    $data = foodpandaAmount::whereDate("created_at", ">=", $req->from_date)
                        ->whereDate("created_at", "<=", $req->to_date)
                        ->offset($req->start)
                        ->limit(10)
                        ->orderBy("id", "desc");


                }else{

                    $total_count = foodpandaAmount::count();
                    $data = foodpandaAmount::offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");
                }
              
        
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('foodpanda_date', function ($row) {
                    return  date_format(date_create($row->date),"d-m-Y");
                })
                ->addColumn('hbl_date', function ($row) {
                    return  date_format(date_create($row->created_at),"d-m-Y");
                })
                ->addColumn('account', function ($row) {
                    return $row->account;
                })
                ->addColumn('amount', function ($row) {
                    return number_format($row->amount);
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks;
                })
                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group btn-sm">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    </button>
                    <div class="dropdown-menu">
                    <a href="javascript:void(0)" class="dropdown-item edit-foodpanda-amount"  data-id="' . $row->id . '">Edit</a>
                    </div>
                    </div>';

                    return $btn;
                })
                ->setFilteredRecords($data->count())
                ->setTotalRecords($total_count)
                ->rawColumns(['action'])
                ->make(true);

                // <a  href="javascript:void(0)" class="dropdown-item delete-foodpanda-amount" data-id="' . $row->id . '">Delete</a>
        }
    }


    function editFoodpandaAmount(Request $req){

        $foodpanda = foodpandaAmount::find($req->id);
        return response()->json($foodpanda, 200);
    }


    function deleteFoodpandaAmount(Request $req){
        $foodpanda = foodpandaAmount::find($req->id);
        $foodpanda->delete();
        return response()->json("deleted",200);
       
    }

    function getFoodpandaAmountUsingDate(Request $req){

         $foodpanda = DB::table('closings')
        ->where('closings.head', 3)
        ->sum('amount');

        $foodpanda_recieved = DB::table('foodpanda_amounts')
        ->sum('amount');

        $remaining =  $foodpanda - $foodpanda_recieved;

        return response()->json($remaining, 200);

    }


    function viewFoodpandaAmounts(Request $req, $from_date, $to_date){



        if($from_date && $to_date){

            $foodpanda_grand_total = DB::table('closings')
            ->where('closings.head', 3)
            ->sum('amount');


            $foodpanda_closing = DB::table('closings')
            ->where('closings.head', 3)
            ->whereDate('closings.date', $from_date)
            ->whereDate('closings.date', $to_date)
            ->sum('amount');
    
            $foodpanda = DB::table('foodpanda_amounts')
            ->whereDate('created_at', $from_date)
            ->whereDate('created_at', $to_date)
            ->get();

            
        $html = [];
        $html["title"] = "Food Panda Report";
        $html["view"] = view("admin.view-foodpanda-report", compact("foodpanda","foodpanda_closing","foodpanda_grand_total"))->render();
        return response()->json($html, 200);
            
        }
        


       

        //return view("admin.view-foodpanda-report", compact("foodpanda"));

    }




    function insertFoodpandaToHbl(Request $req){


       
        if ($req->hidden_id) {
            // $validation["date"] = [
            //     'required',
            //     Rule::unique('foodpanda_amounts')->ignore($req->hidden_id)
            // ];
        } else {

            // $validation["date"] = [
            //     'required',
            //     Rule::unique('foodpanda_amounts')
            // ];
        }

        // $validator = Validator::make($req->all(), $validation);


        // if ($validator->fails()) {
        //     return response()->json(['error' => $validator->errors()->all()], 400);
        // }


        if($req->hidden_id){
            $foodpanda =  foodpandaAmount::find($req->hidden_id);
        }else{
            $foodpanda = new foodpandaAmount();
        }
        $foodpanda->date = date("Y-m-d");
        $foodpanda->account = $req->account;
        $foodpanda->amount = $req->amount;
        $foodpanda->remarks = $req->remarks;
        $foodpanda->save();
        return response()->json("saved",200);

    }

    function addEmployeeOtherSecondForm()
    {
        $branches = branches();
        return view("admin.add-employee-other-second-form", compact("branches"));
    }


    function getEmployeeOthersReports()
    {
    }


    function easypaisaLastClosingAmount()
    {

        $date = date("Y-m-d");
        $closing = date('Y-m-d', strtotime($date . ' -1 day'));
        // $closing_to = date('Y-m-d', strtotime($date.' -1 day'));

        $easypaisa_amount = DB::table('closings')
            ->select(DB::raw('sum(amount) as sum'))
            ->where("head", "5")
            ->where("date", $closing)
            ->groupBy('date')
            ->get();

        $paid_amount  = DB::table('easypaisa_paid_amounts')
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as formatted_date"), DB::raw('sum(amount) as sum'))
            // ->select(DB::raw('sum(amount) as sum'))
            // ->where("paid_date", date("Y-m-d"))
            ->whereDate("created_at", "=", date("Y-m-d"))
            ->groupBy("formatted_date")
            ->get();


        return response()->json([(isset($easypaisa_amount[0]->sum) ? $easypaisa_amount[0]->sum : 0)  - (isset($paid_amount[0]->sum) ? $paid_amount[0]->sum : 0)], 200);
    }


    function hblLastClosingAmount()
    {
        $date = date("Y-m-d");
        $closing = date('Y-m-d', strtotime($date . ' -1 day'));
        // $closing_to = date('Y-m-d', strtotime( .' -1 day'));

        $hbl_amount = DB::table('closings')
            ->select(DB::raw('sum(amount) as sum'))
            ->where("head", "4")
            ->where("date", $closing)
            ->groupBy('date')
            ->get();

        $paid_amount  = DB::table('hbl_amounts')
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as formatted_date"), DB::raw('sum(amount) as sum'))
            // ->select(DB::raw('sum(amount) as sum'))
            // ->where("paid_date", date("Y-m-d"))
            ->whereDate("created_at", "=", date("Y-m-d"))
            ->groupBy("formatted_date")
            ->get();


        return response()->json([(isset($hbl_amount[0]->sum) ? $hbl_amount[0]->sum : 0)  - (isset($paid_amount[0]->sum) ? $paid_amount[0]->sum : 0)], 200);
    }





    function dailyClosing()
    {
        $branches = branches();
        return view("admin.daily-closing", compact("branches"));
    }


    function getFullReportofClosing(Request $req)
    {

        if ($req->ajax()) {

            if ($req->from_closing_date && $req->to_closing_date) {
                $data = DB::table('closings')
                    ->select(DB::raw('date'))
                    ->whereDate("date", ">=", $req->from_closing_date)
                    ->whereDate("date", "<=", $req->to_closing_date)
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc")
                    ->groupBy('date')
                    ->get();
            } else {
                $data = DB::table('closings')
                    ->select(DB::raw('date'))
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc")
                    ->groupBy('date')
                    ->get();
            }



            // $data = Closing::where("data", $req->date)->offset($req->start)->limit(10)->orderBy("id", "desc");
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return date_format(date_create($row->date), "d-m-Y");
                })

                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" class="edit btn btn-primary single-view-closing"  data-id="' . $row->date . '">View</a>';

                    return $btn;
                })
                ->setFilteredRecords($data->count())
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    function getFullReportofClosingView(Request $req)
    {

        if ($req->ajax()) {
            $html = [];
            $html["title"] = "All Closing List";
            $html["view"] = view("admin.daily-closing-report")->render();
            return response()->json($html, 200);
        }
    }


    function getCurrentAmount()
    {
        // EasypaisaAmount::all();
    }


    function index()
    {

        $branches = branches();
        //return view("admin.all-form", compact("branches"));
    }


    function insertPaidAmount(Request $req)
    {

        // $validation = [
        //     'easypasia_amount_detail_date'=>"required",
        //     'employee_type',
        //     'easypaisa_detail_locations',
        //     'employee_others',
        //     // 'advance_payment_month',
        //     'purpose',
        //     'paid_amount',
        //     'remarks'
        // ];

        // $validator = Validator::make($req->all(), $validation);


        // if ($validator->fails()) {
        //     return response()->json(['error' => $validator->errors()->all()], 400);
        // }

        $paid_amount = new easypaisa_amount_paid_detail();
        $paid_amount->easypasia_amount_date = $req->easypasia_amount_detail_date;
        $paid_amount->employee_type = $req->employee_type;
        $paid_amount->locations_id = $req->easypaisa_detail_locations;
        if (isset($req->advance_payment_month)) {
            $paid_amount->advance_payment_month = $req->advance_payment_month . "-01";
        }
        $paid_amount->employee_others = $req->employee_others;
        $paid_amount->purpose = $req->purpose;
        $paid_amount->paid_amount = $req->paid_amount;
        $paid_amount->remarks = $req->remarks;
        $paid_amount->operator = userName();

        // $easypaisa_amount =new  EasypaisaAmount();

        $easypaisa_last_record =  EasypaisaAmount::where('branch', $req->easypaisa_detail_locations)->latest()->get()->first();
        $remaining_amount = $easypaisa_last_record->remaining_amount;

        $easypaisa_amount = new  EasypaisaAmount();
        $easypaisa_amount->invoice_no = $easypaisa_last_record->invoice_no;
        $easypaisa_amount->current_amount = $easypaisa_last_record->current_amount;
        $easypaisa_amount->deducted_amount = $paid_amount->paid_amount;
        $easypaisa_amount->remaining_amount = $remaining_amount - $paid_amount->paid_amount;
        $easypaisa_amount->branch = $req->easypaisa_detail_locations;
        $easypaisa_amount->remarks = "Easypaisa Paid Table";
        $easypaisa_amount->operator = userName();
        $easypaisa_amount->save();

        $paid_amount->easypaisa_amount_id = $easypaisa_amount->id;

        $paid_amount->save();
        return response()->json(["saved"], 200);
    }


    function getEmployees(Request $req)
    {

        if ($req->employee_type && $req->branch) {
            $data = Employee::where("employee_type", $req->employee_type)->where("employee_branch", $req->branch)->where("employee_status", "On")->get();
        } else {
            $data = Employee::where("employee_type", "Employee")->where("employee_branch", $req->branch)->where("employee_status", "On")->get();
        }


        // $easypaisa_amount = EasypaisaAmount::where('branch',  $req->branch)->latest()->get()->first();
        return response()->json([$data]);
    }


    function getHeadLocations()
    {
        $head_location = HeadLocation::where("status","On")->get();
        return response()->json($head_location);
    }

    function getHeads()
    {
        $heads = Head::all();
        return response()->json($heads);
    }

    function insertClosing(Request $req)
    {

        $location = $req->location;
        $head = $req->head;
        $date = $req->date;

        $validation = [
            'date' =>  'required',
            'location' => 'required',
            'amount' => 'required'
        ];

        if ($req->hidden_id) {
            $validation["head"] = [
                'required',
                Rule::unique('closings')->where(function ($query) use ($location, $head, $date) {
                    return $query->where('head', $head)->where("location", $location)->where("date", $date);
                })->ignore($req->hidden_id)
            ];
        } else {

            $validation["head"] = [
                'required',
                Rule::unique('closings')->where(function ($query) use ($location, $head, $date) {
                    return $query->where('head', $head)->where("location", $location)->where("date", $date);
                })
            ];
        }



        $validator = Validator::make($req->all(), $validation);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        if ($req->hidden_id) {
            $closing = Closing::find($req->hidden_id);
        } else {
            $closing = new Closing();
        }

        $closing->date =  $req->date;
        $closing->location = $req->location;
        $closing->head = $req->head;
        $closing->amount = $req->amount;
        $closing->amount_status = "In";
        $closing->remarks = $req->remarks;
        $closing->save();
        return response()->json(['saved'], 200);
    }

    function getClosingList(Request $req)
    {
        if ($req->ajax()) {
            if ($req->date) {
                $data = Closing::with("heads:id,head")->with('locations:id,location')->where("date", $req->date)->orderBy("id", "desc");
            } else {
                $data = Closing::where("id", 0);
            }

            // $data = Closing::where("data", $req->date)->offset($req->start)->limit(10)->orderBy("id", "desc");
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('head', function ($row) {
                    return $row->heads->head;
                })
                ->addColumn('location', function ($row) {
                    return $row->locations->location;
                })
                ->addColumn('amount', function ($row) {
                    return $row->amount;
                })

                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group btn-sm">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    </button>
                    <div class="dropdown-menu">
                    <a href="javascript:void(0)" class="dropdown-item closing-head-edit"  data-id="' . $row->id . '">Edit</a>
                    <a  href="javascript:void(0)" class="dropdown-item closing-head-delete" data-id="' . $row->id . '">Delete</a>
                    </div>
                    </div>';

                    // $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';

                    return $btn;
                })
                ->setFilteredRecords($data->count())
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    function editClosing(Request $req)
    {

        if ($req->ajax()) {

            if ($req->id) {
                $closing = closing::find($req->id);
                return response()->json([$closing], 200);
            }
        }
    }

    function deleteClosing(Request $req)
    {

        if ($req->ajax()) {
            $closing = closing::find($req->id);
            $closing->delete();
            return response()->json(["deleted"], 200);
        }
    }


    function getClosingPdf(Request $req)
    {

        // $closing = closing::where("date", "2023-06-07")->get();

        $heads_name = Head::all();

        $head_locations = HeadLocation::all();

        $date = $req->date;

        // $closing = Closing::with("heads:id,head")->with('locations:id,location')->where("date", $req->date)->orderBy("location", "asc")->get();


        $closing = Closing::with("heads:id,head")->with('locations:id,location')->where("date", $date)->orderBy("location", "asc")->get();

        // return view("admin.get-closing-pdf", compact('closing', "heads_name", "head_locations", "date"));


        $pdf = PDF::loadView("admin.get-closing-pdf", compact('closing', "heads_name", "head_locations", "date"));
        $file = $pdf->download('nfc_closing.pdf');
        return response()->json([base64_encode($file)], 200);




        // return view("admin.get-closing-pdf", compact("closing","heads_name","head_locations"));

    }


    function getClosingView(Request $req, $id)
    {

        if ($req->ajax()) {
            $heads_name = Head::all();

            $head_locations = HeadLocation::all();

            $closing = Closing::with("heads:id,head")->with('locations:id,location')->where("date", $id)->orderBy("location", "asc")->get();

            $html = [];
            $html["title"] = "Closing";
            $branches = branches();
            $html["view"] = view("admin.get-closing-pdf", compact('closing', "heads_name", "head_locations", "id"))->render();
            return response()->json($html, 200);
        }
    }


    function addEasypaisaForm(Request $req)
    {

        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Easy Paisa Form";
            $branches = branches();
            $html["view"] = view("admin.add-easypaisa-form", compact("branches"))->render();
            return response()->json($html, 200);
        }
    }




    function easypaisaAmountList(Request $req)
    {

        if ($req->ajax()) {

            $total = count(DB::table('easypaisa_amounts')->get());

            $data = EasypaisaAmount::with("locations:id,location")->where("status", "Add")->offset($req->start)->limit(10)->orderBy("id", "desc");

            // $data = Closing::where("data", $req->date)->offset($req->start)->limit(10)->orderBy("id", "desc");
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('current_amount', function ($row) {
                    return $row->current_amount;
                })
                // ->addColumn('add_amount', function ($row) {
                //     return $row->add_amount;
                // })
                ->addColumn('remaining_amount', function ($row) {
                    return $row->remaining_amount;
                })

                ->addColumn('branch', function ($row) {
                    return $row->locations->location;
                })

                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group btn-sm">
                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Action
                </button>
                <div class="dropdown-menu">
                </div>
                </div>';

                    // <a href="javascript:void(0)" class="dropdown-item closing-head-edit"  data-id="'.$row->id.'">Edit</a>
                    // <a  href="javascript:void(0)" class="dropdown-item closing-head-delete" data-id="'.$row->id.'">Delete</a>

                    // $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';

                    return $btn;
                })
                ->setFilteredRecords($data->count())
                ->setTotalRecords($total)
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    function addEmployeeOthersForm(Request $req)
    {

        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Employee/Others Form";
            $branches = branches();
            $html["view"] = view("admin.add-employee-others-form", compact("branches"))->render();
            return response()->json($html, 200);
        }
    }


    function insertEmployeeOthers(Request $req)
    {

        $validation = [
            'employee_name' =>  'required',
            // 'cnic' =>  'unique:employees,cnic',
            // 'employee_branch' =>  'required',
            'employee_status' =>  'required',
        ];


        if ($req->employee_hidden_id) {
            $validation["cnic"] = [
                'cnic' =>  'unique:employees,cnic,' . $req->employee_hidden_id
            ];
        } else {
            $validation["cnic"] = [
                'cnic' =>  'unique:employees,cnic'
            ];
        }

        $validator = Validator::make($req->all(), $validation);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        $Employee_last_id = Employee::latest()->get()->first();



        if ($req->employee_hidden_id) {
            $employee = Employee::find($req->employee_hidden_id);
        } else {


            $employee = new Employee();

            if (isset($Employee_last_id->employee_no)) {
                if ($req->employee_type == "Employee") {
                    $employee->employee_no = $Employee_last_id->employee_no + 1;
                }
            } else {

                if ($req->employee_type == "Employee") {
                    $employee->employee_no = 1000;
                }
            }
        }



        $employee->employee_name = $req->employee_name;
        // if (isset($req->employee_post)) {
            $employee->employee_post = $req->employee_post;
        // }
        // if (isset($req->cnic)) {
            $employee->cnic = $req->cnic;
        // }
        // if (isset($req->basic_sallary)) {
            $employee->basic_sallary = $req->basic_sallary;
            $employee->cnic =  $req->cnic;
            $employee->phone_no =  $req->phone_no;
            $employee->father_cnic =  $req->father_cnic;
            $employee->father_phone_no =  $req->father_phone_no;
        // }

        // if (isset($req->employee_branch)) {
            $employee->employee_branch = $req->employee_branch;
        // }
        $employee->employee_type = $req->employee_type;
        $employee->employee_status = $req->employee_status;
        $employee->joining = $req->joining;
        $employee->leaving = $req->leaving;
        $employee->operator = userName();
        $employee->save();
    }




   

    function listEmployeeOthers(Request $req)
    {
        if ($req->ajax()) {

            $data = Employee::offset($req->start)->limit(10)->orderBy("id", "desc");;

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->employee_name;
                })
                ->addColumn('post', function ($row) {
                    return $row->employee_post;
                })
                ->addColumn('type', function ($row) {
                    return $row->employee_type;
                })

                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group btn-sm">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    </button>
                    <div class="dropdown-menu">
                    <a href="javascript:void(0)" class="dropdown-item  edit-easypaisa-amount"  data-id="' . $row->id . '">Edit</a>';

                    $btn .= '<a  href="javascript:void(0)" class="dropdown-item return-easypaisa-amount" data-id="' . $row->id . '">Return</a>';

                    $btn .= '</div>
                    </div>';

                    // $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';

                    return $btn;
                })
                ->setFilteredRecords($data->count())
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }


 

    function addSadqah(Request $req)
    {


        if ($req->ajax()) {
            $easypaisa_amount = easypaisa_amount_paid_detail::with("EasypaisaDetail:id")->with("Employees:id,employee_name,employee_post")->with("branches:id,location")->where("id", $req->id)->get();
            $html = [];
            $html["title"] = "Sadqah Form";
            $html["view"] =  view("admin.add-sadqah-form", compact("easypaisa_amount"))->render();
            return response()->json($html, 200);
        }
    }

    function easypaisaForm()
    {
        $branches = branches();
        return view("admin.easypaisa-form", compact("branches"));
    }


    function insertEasypaisaAmount(Request $req)
    {
        if ($req->ajax()) {

            $validation = [
                // 'paid_date' =>  'required',
                'employee_id' =>  'required',
                // 'paid_for_month' =>  'required',
                'purpose' =>  'required',
                // 'status' =>  'required',
                'amount' =>  'required',
                // 'remarks' =>  'required',
            ];

            $validator = Validator::make($req->all(), $validation);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 400);
            }

            if($req->hidden_id){
                $easypaisa_paid =  EasypaisaPaidAmount::find($req->hidden_id);
            }else{
                $easypaisa_paid = new EasypaisaPaidAmount();
            }
            
            $easypaisa_paid->employee_id = $req->employee_id;
            $easypaisa_paid->purpose = $req->purpose;
            if (isset($req->advance_payment_month)) {
                $easypaisa_paid->paid_for_month_date = $req->advance_payment_month . "-01";
            }

            $easypaisa_paid->status = "Paid";
            $easypaisa_paid->amount = $req->amount;
            $easypaisa_paid->remarks = $req->remarks;
            // $easypaisa_paid->paid_date = $req->paid_date;
            $easypaisa_paid->save();
        }
    }


    function getReportofEasypaisaAmount(Request $req)
    {

        if ($req->ajax()) {



            if($req->search_value){
                $search_value = $req->search_value;

                $total_count = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->count();

                $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
                

            }elseif($req->search_value &&  $req->from_date && $req->to_date){

                $search_value = $req->search_value;

                $total_count = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();

                $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
            
            
            
              }elseif ($req->from_date && $req->to_date && $req->type && $req->employee_others) {


                $total_count = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                ->where("employee_id", $req->employee_others)
                ->where("purpose", $req->type)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();


            
                $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                ->where("employee_id", $req->employee_others)
                ->where("purpose", $req->type)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
                 
            
            }elseif ($req->from_date && $req->to_date && $req->employee_others) {


                $total_count = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                ->where("employee_id", $req->employee_others)
               ->whereDate("created_at", ">=", $req->from_date)
               ->whereDate("created_at", "<=", $req->to_date)
               ->count();

                $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                     ->where("employee_id", $req->employee_others)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");
                

            }elseif ($req->from_date && $req->to_date && $req->type) {

                $total_count = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                ->where("purpose", $req->type)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();

                $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                    ->where("purpose", $req->type)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");
              

            } elseif ($req->from_date && $req->to_date){

                $total_count = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();
        
                $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");
                
        
            }else{

                $total_count = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")->count();
                $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")->offset($req->start)->limit(10)->orderBy("id", "desc");

            }


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('paid_date', function ($row) {
                    return  date_format(date_create($row->created_at), "d-m-Y");
                })
                ->addColumn('employee', function ($row) {
                    if ($row->getEmployee->employee_post !== null) {
                        $advance_date = date_format(date_create($row->paid_for_month_date), "d-M-Y");
                        return $row->getEmployee->employee_name . " (" . $row->getEmployee->employee_post . ")-" . $advance_date;
                    } else {
                        return $row->getEmployee->employee_name;
                    }
                })
                ->addColumn('purpose', function ($row) {
                    return $row->purpose;
                })
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->addColumn('amount', function ($row) {
                    return number_format($row->amount);
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks;
                })

                ->addColumn('action', function ($row) {


                     //dont remove this code


                    // if($row->status == "Paid"){

                    //     $btn = '<div class="btn-group btn-sm">
                    //     <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                    //     Action
                    //     </button>
                    //     <div class="dropdown-menu">
                    //     <a href="javascript:void(0)" class="dropdown-item  edit-easypaisa-amount"  data-id="' . $row->id . '">Edit</a>';
                        
                    // }else{

                    //     $btn = '<div class="btn-group btn-sm">
                    //     <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    //     Action
                    //     </button>
                    //     <div class="dropdown-menu">
                    //     <a href="javascript:void(0)" class="dropdown-item  edit-easypaisa-amount"  data-id="' . $row->id . '">Edit</a>';

                    // }


                    //dont remove this code

                    if($row->purpose == "Others" || $row->purpose == "Patty" ||  $row->purpose == "Advance"){

                        $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle"'.(Auth::User()->user_type == "User" ? "disabled" : "").'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">';
                        $btn .= '<a href="javascript:void(0)" class="dropdown-item  edit-easypaisa-amount"  data-id="' . $row->id . '">Edit</a>';
                        $btn .= '</div></div>';
                        
                    }else{

                        $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item  edit-easypaisa-amount"  data-id="' . $row->id . '">Edit</a>';
                        $btn .= '</div></div>';

                    }





                   

                    // $btn .= '<a  href="javascript:void(0)" class="dropdown-item return-easypaisa-amount" data-id="' . $row->id . '">Return</a>';

                    // $btn .= '</div>
                    // </div>';

                 
                    return $btn;
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    function getFullReportofEasypaisaAmount(Request $req)
    {

        
        $employees = DB::table('employees')
        ->select(DB::raw('id,employee_name'))
        ->get();

        return  view("admin.get-full-report-easypaisa-amount", compact("employees"));
    }

    function editEasypaisaPaidAmount(Request $req){

       $easypaisa_paid_amount = EasypaisaPaidAmount::find($req->id);

       $get_detail_employee = Employee::find($easypaisa_paid_amount->employee_id);

       return response()->json([$easypaisa_paid_amount,$get_detail_employee],200);

    }

    function getPdfReportOfEasypaisaAmount(Request $req)
    {
        
        ini_set('memory_limit', '-1');
      
        if($req->search_value){
            $search_value = $req->search_value;
            $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
            ->whereHas('getEmployee', function ($query)use($search_value){
                $query->where("employee_name", "like", '%' . $search_value . '%');
            })
            ->orderBy("id", "desc")
            ->get();
          
        } elseif ($req->from_date && $req->to_date && $req->type && $req->employee_others) {
        
            $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
            ->where("employee_id", $req->employee_others)
            ->where("purpose", $req->type)
            ->whereDate("created_at", ">=", $req->from_date)
            ->whereDate("created_at", "<=", $req->to_date)
            ->orderBy("id", "desc")
            ->get();
             
        
        }elseif ($req->from_date && $req->to_date && $req->employee_others) {
        
            $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                 ->where("employee_id", $req->employee_others)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->orderBy("id", "desc")
                ->get();
            
        }elseif ($req->from_date && $req->to_date && $req->type) {
        
            $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                ->where("purpose", $req->type)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->orderBy("id", "desc")
                ->get();
        } elseif ($req->from_date && $req->to_date){
    
            $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
            ->whereDate("created_at", ">=", $req->from_date)
            ->whereDate("created_at", "<=", $req->to_date)
            ->orderBy("id", "desc")
            ->get();
    
        }else{

            $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")->offset($req->start)
            ->orderBy("id", "desc")
            ->get();
        }

       if ($req->from_date && $req->to_date){
        $easypaisa_old_amount_paid_sum = DB::table('easypaisa_paid_amounts')
        ->select(DB::raw('sum(amount) as sum'))
        ->whereDate("created_at", "<" , $req->from_date)
        ->get();
       }
       


        $easypaisa_amount = DB::table('closings')
            ->select(DB::raw('sum(amount) as sum'))
            ->where("head", "5")
            ->get();
        
        $from_foodpanda_amount  = DB::table('foodpanda_amounts')
            ->select(DB::raw('sum(amount) as sum'))
            ->where("account", "Easypaisa")
            ->get();

        $from = $req->from_date;
        $to = $req->to_date;
        $employee_others = $req->employee_others;
        $type =  $req->type;

        $pdf = PDF::loadView("admin.get-pdf-report-easypaisa", compact('from_foodpanda_amount','data', 'from', 'to','employee_others','type', 'easypaisa_amount','easypaisa_old_amount_paid_sum'));
        $file = $pdf->download('nfc_easypaisa_detail.pdf');

        return response()->json([base64_encode($file)], 200);
        ini_set('memory_limit', '128M');

    }



    function viewReportEasypaisaAmount(Request $req, $from_date, $to_date, $type=null, $employee_others=null){

     
      
      if ($from_date && $to_date && $type && $employee_others) {
        
            $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
            ->where("employee_id", $employee_others)
            ->where("purpose", $type)
            ->whereDate("created_at", ">=", $from_date)
            ->whereDate("created_at", "<=", $to_date)
            ->orderBy("id", "desc")
            ->get();
             
        
        }elseif ($from_date && $to_date && $employee_others) {
        
            $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                 ->where("employee_id", $employee_others)
                ->whereDate("created_at", ">=", $from_date)
                ->whereDate("created_at", "<=", $to_date)
                ->orderBy("id", "desc")
                ->get();
            
        }elseif ($from_date && $to_date && $type) {
        
            $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
                ->where("purpose", $type)
                ->whereDate("created_at", ">=", $from_date)
                ->whereDate("created_at", "<=", $to_date)
                ->orderBy("id", "desc")
                ->get();
        
        } elseif ($from_date && $to_date){
    
            $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")
            ->whereDate("created_at", ">=", $from_date)
            ->whereDate("created_at", "<=", $to_date)
            ->orderBy("id", "desc")
            ->get();
    
        }else{

            $data = EasypaisaPaidAmount::with("getEmployee:id,employee_name,employee_post")->orderBy("id", "desc")
            ->get();
        }

       if ($from_date && $to_date){
        $easypaisa_old_amount_paid_sum = DB::table('easypaisa_paid_amounts')
        ->select(DB::raw('sum(amount) as sum'))
        ->whereDate("created_at", "<" , $from_date)
        ->get();
       }
       


        $easypaisa_amount = DB::table('closings')
            ->select(DB::raw('sum(amount) as sum'))
            ->where("head", "5")
            ->get();
        
        $from_foodpanda_amount  = DB::table('foodpanda_amounts')
            ->select(DB::raw('sum(amount) as sum'))
            ->where("account", "Easypaisa")
            ->get();

        $from = $from_date;
        $to = $to_date;
        $employee_others = $employee_others;
        $type =  $type;



        
        $html = [];
        $html["title"] = "Easypaisa Grand Report View";
        $html["view"] = view("admin.view-easypaisa-report",compact('from_foodpanda_amount','data', 'from', 'to','employee_others','type', 'easypaisa_amount','easypaisa_old_amount_paid_sum'))->render();
        return response()->json($html, 200);

        // $pdf = PDF::loadView("admin.get-pdf-report-easypaisa", compact('from_foodpanda_amount','data', 'from', 'to','employee_others','type', 'easypaisa_amount','easypaisa_old_amount_paid_sum'));
        // $file = $pdf->download('nfc_easypaisa_detail.pdf');

        // $html = [];
        // $html["title"] = "Easypaisa Amount View";
        // $html["view"] =  view("admin.view-easypaisa-report", compact('from_foodpanda_amount','data', 'from', 'to','employee_others','type', 'easypaisa_amount','easypaisa_old_amount_paid_sum'));

        // return response()->json($html, 200);
        

    }


    





    function getPdfReportofHblAmount(Request $req)
    {


        ini_set('memory_limit', '-1');
    

        if($req->search_value){

        $search_value = $req->search_value;

        $data = HblAmounts::with("getEmployee:id,employee_name,employee_post")
        ->whereHas('getEmployee', function ($query)use($search_value){
            $query->where("employee_name", "like", '%' . $search_value . '%');
        })->orderBy("id", "desc")->get();

        
        }elseif ($req->from_date && $req->to_date && $req->type && $req->type && $req->employee_others) {
        
            $data = HblAmounts::with("getEmployee:id,employee_name,employee_post")
                ->where("purpose", $req->type)
                ->where("employee_id", $req->employee_others)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->orderBy("id", "desc")
                ->get();
               
        }elseif ($req->from_date && $req->to_date && $req->type) {
        
            $data = HblAmounts::with("getEmployee:id,employee_name,employee_post")
                ->where("purpose", $req->type)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->orderBy("id", "desc")
                ->get();

        } elseif ($req->from_date && $req->to_date){

            $data = HblAmounts::with("getEmployee:id,employee_name,employee_post")
            ->whereDate("created_at", ">=", $req->from_date)
            ->whereDate("created_at", "<=", $req->to_date)
            ->orderBy("id", "desc")
            ->get();

        }else{
          
            $data = HblAmounts::with("getEmployee:id,employee_name,employee_post")
            ->offset($req->start)->limit(10)->orderBy("id", "desc")->get();
            
        }


        if ($req->from_date && $req->to_date){
            $easypaisa_old_amount_paid_sum = DB::table('easypaisa_paid_amounts')
            ->select(DB::raw('sum(amount) as sum'))
            ->whereDate("created_at", "<" , $req->from_date)
            ->get();
        }


        $hbl_old_amount_paid_sum = DB::table('hbl_amounts')
        ->select(DB::raw('sum(amount) as sum'))
        ->whereDate("created_at","<",$req->from_date )
        ->get();


        $easypaisa_amount = DB::table('closings')
            ->select(DB::raw('sum(amount) as sum'))
            ->where("head", "4")
            ->get();

        $from = $req->from_date;
        $to = $req->to_date;

        $type = $req->type;
        $employee_others = $req->employee_others;


        $from_foodpanda_amount_hbl  = DB::table('foodpanda_amounts')
        ->select(DB::raw('sum(amount) as sum'))
        ->where("account", "HBL")
        ->get();

        $pdf = PDF::loadView("admin.get-pdf-report-hbl", compact('from_foodpanda_amount_hbl','data', 'from', 'to','type', 'employee_others' ,'easypaisa_amount','hbl_old_amount_paid_sum'));
        $file = $pdf->download('nfc_hbl_detail.pdf');

        return response()->json([base64_encode($file)], 200);
        ini_set('memory_limit', '128M');
    }


    function viewReportHblAmount(Request $req, $from_date, $to_date){

        if($from_date && $to_date){

            $data = HblAmounts::with("getEmployee:id,employee_name,employee_post")
            ->whereDate("created_at", ">=", $from_date)
            ->whereDate("created_at", "<=", $to_date)
            ->orderBy("id", "desc")
            ->get();

        }


        $hbl_old_amount_paid_sum = DB::table('hbl_amounts')
        ->select(DB::raw('sum(amount) as sum'))
        ->whereDate("created_at","<",$req->from_date )
        ->get();


        $easypaisa_amount = DB::table('closings')
            ->select(DB::raw('sum(amount) as sum'))
            ->where("head", "4")
            ->get();

        $from_foodpanda_amount_hbl  = DB::table('foodpanda_amounts')
        ->select(DB::raw('sum(amount) as sum'))
        ->where("account", "HBL")
        ->get();

        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Easypaisa Amount";
    
            $html["view"] = view("admin.get-hbl-view", compact("easypaisa_amount","hbl_old_amount_paid_sum","from_foodpanda_amount_hbl","from_date", "to_date","data"))->render();
            return response()->json($html, 200);
        }

    }



    function ownerPending(){

        return view("admin.owner-pending");
    }

    function insertOwnerPending(Request $req){

        if($req->hidden_id){
            $owner_pending = ownerPending::find($req->hidden_id);
        }else{
            $owner_pending = new ownerPending();
        }   
        $owner_pending->type = $req->type;
        $owner_pending->date = $req->date;
        $owner_pending->amount =  $req->amount;
        $owner_pending->remarks =  $req->remarks;
        $owner_pending->save();
    }   


    function getOwnerPendingList(Request $req){

        if ($req->ajax()) {


            if($req->from_date && $req->to_date && $req->pending_type){

                $total = count(DB::table('owner_pendings')
                ->whereDate("date",">=", $req->from_date)
                ->whereDate("date","<=", $req->to_date)
                ->where("type", $req->pending_type)
                ->get());

                $data = ownerPending::whereDate("date",">=", $req->from_date)
                ->whereDate("date","<=", $req->to_date)
                ->where("type", $req->pending_type)
                ->offset($req->start)
                ->limit(10)->orderBy("id", "desc");

            }else{

                $total = count(DB::table('owner_pendings')->get());
                $data = ownerPending::offset($req->start)->limit(10)->orderBy("id", "desc");
    
            }

            
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return date_format(date_create($row->date),"d-m-Y");
                })
                ->addColumn('pending', function ($row) {
                    return $row->type;
                })
                ->addColumn('amount', function ($row) {
                    return number_format($row->amount);
                })

                ->addColumn('remarks', function ($row) {
                    return $row->remarks;
                })

                ->addColumn('action', function ($row) {

                $btn = '<div class="btn-group btn-sm">
                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Action
                </button>
                <div class="dropdown-menu">
                <a href="javascript:void(0)" class="dropdown-item edit-owner-pending"  data-id="'.$row->id.'">Edit</a>
                
                </div>
                </div>';
                    return $btn;
                })
                //<a  href="javascript:void(0)" class="dropdown-item delete-owner-pending" data-id="'.$row->id.'">Delete</a>
                ->setFilteredRecords($data->count())
                ->setTotalRecords($total)
                ->rawColumns(['action'])
                ->make(true);
        }

    }


    function viewOwnerPendingReport(Request $req, $from=null, $to=null, $pending_type=null){

        if($from && $to && $pending_type){
            $pendings =  ownerPending::whereDate("date",">=", $from)
            ->whereDate("date","<=", $to)
            ->where("type", $pending_type)
            ->get();
        }else{
            $pendings =  ownerPending::whereDate("date",">=", $from)
            ->whereDate("date","<=", $to)
            ->get();
        }

       

         if ($req->ajax()) {
            $html = [];
            $html["title"] = $pending_type ." Pendings";
            $branches = branches();
            $html["view"] = view("admin.view-owner-pending-report", compact("pendings"))->render();
            return response()->json($html, 200);
        }


        //return view("admin.view-owner-pending-report", compact("pendings"));
    }


    function editOwnerPending(Request $req){
        if($req->ajax()){

           $owner_pending = ownerPending::find($req->id);
           return response()->json( $owner_pending, 200);
        }

    }


    function pendingForm()
    {

        $branches = branches();
        return view("admin.pending-form", compact("branches"));
    }

    function insertPending(Request $req)
    {

        if ($req->ajax()) {

            $validation = [
                'date' =>  'required',
                'employee_id' =>  'required',
                'amount' =>  'required',
                
                'remarks'=>  'required',
            ];

            $validator = Validator::make($req->all(), $validation);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 400);
            }

            $employee_detail = explode(",",$req->employee_id);

            if ($req->hidden_id) {
                $pending = Pending::find($req->hidden_id);
            } else {
                $pending = new Pending();
            }
           
            $pending->date = $req->date;
            $pending->employee_id = $employee_detail[0];
            $pending->branch_id =  $employee_detail[1];
            $pending->amount = $req->amount;
            $pending->status = "Pending";
            $pending->remarks = $req->remarks;
            $pending->save();
            return response()->json(['saved'], 200);
        }
    }


    function getListOfPending(Request $req)
    {


        if ($req->ajax()) {

            if($req->search_value && $req->from_date_pending && $req->to_date_pending ){

                $search_value = $req->search_value;

                $count_data =  Pending::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location") ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->whereDate("date", ">=", $req->from_date_pending)
                ->whereDate("date", "<=", $req->to_date_pending)
                ->count();

                $data = Pending::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location") ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->whereDate("date", ">=", $req->from_date_pending)
                ->whereDate("date", "<=", $req->to_date_pending)
                ->offset($req->start)->limit(10)->orderBy("id", "desc");



            }elseif($req->search_value){

                $search_value = $req->search_value;

                $count_data =  Pending::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location") ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })->count();

                $data = Pending::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location") ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->offset($req->start)->limit(10)->orderBy("id", "desc");

            }elseif ($req->from_date_pending && $req->to_date_pending && $req->pending_status && $req->pending_employee_id) {


                $count_data = Pending::where("employee_id",$req->pending_employee_id)
                ->where("status", $req->pending_status)
                ->whereDate("date", ">=", $req->from_date_pending)
                ->whereDate("date", "<=", $req->to_date_pending)
                ->count();

                $data = Pending::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->where("employee_id",$req->pending_employee_id)
                ->where("status", $req->pending_status)
                ->whereDate("date", ">=", $req->from_date_pending)
                ->whereDate("date", "<=", $req->to_date_pending)
                ->offset($req->start)->limit(10)->orderBy("id", "desc");


            }elseif ($req->from_date_pending && $req->to_date_pending && $req->pending_employee_id) {


                $count_data = Pending::where("employee_id",$req->pending_employee_id)
                // ->where("status", $req->pending_status)
                ->whereDate("date", ">=", $req->from_date_pending)
                ->whereDate("date", "<=", $req->to_date_pending)
                ->count();

                $data = Pending::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->where("employee_id",$req->pending_employee_id)
                // ->where("status", $req->pending_status)
                ->whereDate("date", ">=", $req->from_date_pending)
                ->whereDate("date", "<=", $req->to_date_pending)
                ->offset($req->start)->limit(10)->orderBy("id", "desc");

                
            } elseif ($req->from_date_pending && $req->to_date_pending && $req->pending_status) {


                $count_data = Pending::where("status", $req->pending_status)
                    ->whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->count();

               
                $data = Pending::with("getEmployee:id,employee_name,employee_post")
                    ->with("getBranch:id,location")
                    // ->where("employee_id",$req->pending_employee_id)
                    ->where("status", $req->pending_status)
                    ->whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");

                 
                
            }elseif ($req->from_date_pending && $req->to_date_pending) {

                $count_data = Pending::whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->count();

                $data = Pending::with("getEmployee:id,employee_name,employee_post")
                    ->whereDate("date", ">=", $req->from_date_pending)
                    ->whereDate("date", "<=", $req->to_date_pending)
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");
            } else {

               

                $count_data = Pending::count();
                $data = Pending::with("getEmployee:id,employee_name,employee_post")->with("getBranch:id,location")->offset($req->start)->limit(10)->orderBy("id", "desc");
                
            }


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return date_format(date_create($row->date),"d-m-Y");
                })
                ->addColumn('employee_id', function ($row) {
                    return $row->getEmployee->employee_name . "-" . $row->getEmployee->employee_post;
                })

                ->addColumn('location', function ($row) {
                    return $row->getBranch->location;
                })

                ->addColumn('amount', function ($row) {
                    return number_format($row->amount);
                })
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                // ->addColumn('paid_date', function ($row) {
                //     return $row->paid_date;
                // })
                ->addColumn('account_name', function ($row) {
                    return $row->account_name;
                })

                ->addColumn('remarks', function ($row) {
                    return $row->remarks;
                })
                
            
                ->addColumn('action', function ($row) {

                    if ($row->status == "Pending") {
                        $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" ' .(Auth::User()->user_type == "User" ? "disabled" : ""). ' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">';
                        $btn .=  '<a href="javascript:void(0)" class="dropdown-item  edit-pending-amount"  data-id="' . $row->id . '">Edit</a>';
                        $btn .= '<a  href="javascript:void(0)" class="dropdown-item delete-pending-amount" data-id="' . $row->id . '">Delete</a>';
                        $btn .= '</div>
                        </div>';
                    }else{
                        $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item  edit-pending-amount"  data-id="' . $row->id . '">Edit</a>';
                        $btn .= '<a  href="javascript:void(0)" class="dropdown-item delete-pending-amount" data-id="' . $row->id . '">Delete</a>';
                        $btn .= '</div>
                        </div>';
                    }

                    return $btn;
                })
                ->setFilteredRecords($data->count())
                ->setTotalRecords( $count_data)
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    function getPendingPdf(Request $req){

    

        $pending_sum  = DB::table('pendings')
        ->select(DB::raw('sum(amount) as sum'))
        ->whereDate("date", ">=", $req->from_date_pending)
        ->whereDate("date", "<=", $req->to_date_pending)
        ->get();


        if($req->search_value){

            $search_value = $req->search_value;

            $data = Pending::with("getEmployee:id,employee_name,employee_post")
            ->with("getBranch:id,location") ->whereHas('getEmployee', function ($query)use($search_value){
                $query->where("employee_name", "like", '%' . $search_value . '%');
            })
            ->orderBy("id", "desc")->get();

        }elseif ($req->from_date_pending && $req->to_date_pending && $req->pending_status && $req->pending_employee_id) {

            $data = Pending::with("getEmployee:id,employee_name,employee_post")
            ->with("getBranch:id,location")
            ->where("employee_id",$req->pending_employee_id)
            ->where("status", $req->pending_status)
            ->whereDate("date", ">=", $req->from_date_pending)
            ->whereDate("date", "<=", $req->to_date_pending)
            ->orderBy("id", "desc")->get();


        }elseif ($req->from_date_pending && $req->to_date_pending && $req->pending_employee_id) {


            $data = Pending::with("getEmployee:id,employee_name,employee_post")
            ->with("getBranch:id,location")
            ->where("employee_id",$req->pending_employee_id)
            // ->where("status", $req->pending_status)
            ->whereDate("date", ">=", $req->from_date_pending)
            ->whereDate("date", "<=", $req->to_date_pending)
            ->orderBy("id", "desc")->get();

            
        } elseif ($req->from_date_pending && $req->to_date_pending && $req->pending_status) {

            $data = Pending::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                // ->where("employee_id",$req->pending_employee_id)
                ->where("status", $req->pending_status)
                ->whereDate("date", ">=", $req->from_date_pending)
                ->whereDate("date", "<=", $req->to_date_pending)
                ->orderBy("id", "desc")->get();
            
        }elseif ($req->from_date_pending && $req->to_date_pending) {

           

            $data = Pending::with("getEmployee:id,employee_name,employee_post")
                ->whereDate("date", ">=", $req->from_date_pending)
                ->whereDate("date", "<=", $req->to_date_pending)
                ->orderBy("id", "desc")->get();
        } else {

        
            $data = Pending::with("getEmployee:id,employee_name,employee_post")->with("getBranch:id,location")
            ->orderBy("id", "desc")->get();
            
        }

        $pdf = PDF::loadView("admin.get-pending-pdf", compact("data","pending_sum"));
        $file = $pdf->download('nfc_pending.pdf');
        return response()->json([base64_encode($file)], 200);

        //return view("admin.get-pending-pdf", compact("data"));

    }



    function getPendingListView(Request $req, $from_date=null, $to_date=null, $employee=null, $status=null){


        $pending_sum  = DB::table('pendings')
        ->select(DB::raw('sum(amount) as sum'))
        ->whereDate("date", ">=", $from_date)
        ->whereDate("date", "<=", $to_date)
        ->get();

        

        if ($from_date && $to_date &&  $employee  && $status) {
           
            $data = Pending::with("getEmployee:id,employee_name,employee_post")
                ->where("employee_id", $employee)
                ->where("status", $status)
                ->whereDate("date", ">=", $from_date)
                ->whereDate("date", "<=", $to_date)
                ->orderBy("id", "desc")->get();
                
        }elseif ($from_date && $to_date &&  $employee) {
           
            $data = Pending::with("getEmployee:id,employee_name,employee_post")
                ->where("employee_id", $employee)
                ->whereDate("date", ">=", $from_date)
                ->whereDate("date", "<=", $to_date)
                ->orderBy("id", "desc")->get();

        }elseif($from_date && $to_date) {
           
            $data = Pending::with("getEmployee:id,employee_name,employee_post")
                ->whereDate("date", ">=", $from_date)
                ->whereDate("date", "<=", $to_date)
                ->orderBy("id", "desc")->get();
        } 

        
        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Pending List";
            $html["view"] = view("admin.get-pending-view",compact("data","pending_sum"))->render();
            return response()->json($html, 200);
        }

       

    }


    function editPendingAmount(Request  $req)
    {

        if ($req->ajax()) {

            $pending = Pending::find($req->id);
            return response()->json($pending, 200);
        }
    }

    function deletePending(Request $req)
    {

        $pending = Pending::find($req->id);
        $pending->delete();
        return response()->json(["deleted"], 200);
    }

    function generateFullPendingReport(Request $req)
    {

        $branches = branches();
        return view("admin.get-report-pending", compact("branches"));
    }


    function payNow(Request $req, $id, $amount, $pending_date, $paynow_id)
    {

        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Pay Now";
            // $branches = branches();
            $html["view"] = view("admin.pay-now-form", compact('id', 'amount', 'pending_date', 'paynow_id'))->render();
            return response()->json($html, 200);
        }
    }

    function insertPayNow(Request $req)
    {

        // return $req->all();
        if ($req->pay_through == "Easypaisa") {
            $paid_amount = new EasypaisaPaidAmount();
            // $paid_amount->paid_date = date("Y-m-d");
            $paid_amount->employee_id = $req->employee_id;
            $paid_amount->purpose = "Pending";
            $paid_amount->paid_for_month_date = $req->pending_date;
            $paid_amount->status = "Paid";
            $paid_amount->amount = $req->amount;
            $paid_amount->save();
            $pending = Pending::find($req->pay_now_id);
            $pending->status = "Paid";
            // $pending->paid_date = date("Y-m-d");
            $pending->account_id = $paid_amount->id;
            $pending->account_name =  $req->pay_through;
            $pending->save();
        } elseif ($req->pay_through == "HBL") {
            $paid_amount = new HblAmounts();
            // $paid_amount->paid_date = date("Y-m-d");
            $paid_amount->employee_id = $req->employee_id;
            $paid_amount->purpose = "Pending";
            $paid_amount->paid_for_month_date = $req->pending_date;
            $paid_amount->status = "Paid";
            $paid_amount->amount = $req->amount;
            $paid_amount->save();
            $pending = Pending::find($req->pay_now_id);
            $pending->status = "Paid";
            // $pending->paid_date = date("Y-m-d");
            $pending->account_id = $paid_amount->id;
            $pending->account_name =  $req->pay_through;
            $pending->save();
        } elseif ($req->pay_through == "Locker") {
            $paid_amount = new lockerDetail();
            // $paid_amount->paid_date = date("Y-m-d");
            $paid_amount->employee_id = $req->employee_id;
            $paid_amount->purpose = "Pending";
            $paid_amount->paid_for_month_date = $req->pending_date;
            $paid_amount->status = "Paid";
            $paid_amount->amount = $req->amount;
            $paid_amount->save();
            $pending = Pending::find($req->pay_now_id);
            $pending->status = "Paid";
            // $pending->paid_date = date("Y-m-d");
            $pending->account_id = $paid_amount->id;
            $pending->account_name =  $req->pay_through;
            $pending->save();
        }
    }


    function getEmployeeForPending()
    {

        $data = Employee::where("employee_type", "Employee")->get();
        return response()->json([$data], 200);
    }


    function chooseOptionEmployeeOther(Request $req)
    {
        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Choose Option";
            $html["view"] = view("admin.choose-option-employee-other")->render();
            return response()->json($html, 200);
        }
    }


    function employeeReport(Request $req)
    {
       
        
        $branches = branches();
        return view("admin.employee-other-report", compact('branches'));
    }


    function getPdfOfEmployeeOthers(Request $req)
    {

        if($req->search_value){   
            $data = Employee::with('getEmployeeBranch:id,location')
               ->where("employee_name", "like", '%' . $req->search_value . '%')
               ->get();

       } elseif ($req->type_of_employee && $req->branch_of_employee && $req->employee_post) {


           $data = Employee::with('getEmployeeBranch:id,location')->where("employee_type", $req->type_of_employee)
               ->where("employee_branch", $req->branch_of_employee)
               ->where("employee_post", $req->employee_post)
               ->where("employee_status", "On")
               ->get();
           

       }elseif ($req->type_of_employee && $req->employee_post) {


        $data = Employee::with('getEmployeeBranch:id,location')
            ->where("employee_type", $req->type_of_employee)
            ->where("employee_post", $req->employee_post)
            ->where("employee_status", "On")
            ->get();
        

        } elseif ($req->type_of_employee && $req->branch_of_employee) {

          

           $data = Employee::with('getEmployeeBranch:id,location')->where("employee_type", $req->type_of_employee)
               ->where("employee_branch", $req->branch_of_employee)
               ->where("employee_status", "On")
               ->get();
          

       } elseif ($req->type_of_employee) {

           $data = Employee::with('getEmployeeBranch:id,location')
               ->where("employee_type", $req->type_of_employee)
               ->where("employee_status", "On")
               ->get();

       } elseif ($req->branch_of_employee) {

           $data = Employee::with('getEmployeeBranch:id,location')
           ->where("employee_branch", $req->branch_of_employee)
               ->where("employee_status", "On")
               ->orderBy("employee_post","asc")
               ->get();

       }  else {

           $data = Employee::with('getEmployeeBranch:id,location')
           ->where("employee_status", "On")
           ->get();
           
       }

        $pdf = PDF::loadView("admin.get-pdf-employee-others", compact("data"));
        $file = $pdf->download('nfc_closing.pdf');
        return response()->json([base64_encode($file)], 200);
    }



    function ViewEmployeeReport(Request $req){

        
        if($req->search_value){   
            $data = Employee::with('getEmployeeBranch:id,location')
               ->where("employee_name", "like", '%' . $req->search_value . '%')
               ->get();

       } elseif ($req->type_of_employee && $req->branch_of_employee && $req->employee_post) {


           $data = Employee::with('getEmployeeBranch:id,location')->where("employee_type", $req->type_of_employee)
               ->where("employee_branch", $req->branch_of_employee)
               ->where("employee_post", $req->employee_post)
               ->where("employee_status", "On")
               ->get();
           

       }elseif ($req->type_of_employee && $req->employee_post) {


        $data = Employee::with('getEmployeeBranch:id,location')
            ->where("employee_type", $req->type_of_employee)
            ->where("employee_post", $req->employee_post)
            ->where("employee_status", "On")
            ->get();
        

    }elseif ($req->type_of_employee && $req->branch_of_employee) {

           $data = Employee::with('getEmployeeBranch:id,location')->where("employee_type", $req->type_of_employee)
               ->where("employee_branch", $req->branch_of_employee)
               ->where("employee_status", "On")
               ->get();
          

       } elseif ($req->type_of_employee) {

           $data = Employee::with('getEmployeeBranch:id,location')
               ->where("employee_type", $req->type_of_employee)
               ->where("employee_status", "On")
               ->get();

       } elseif ($req->branch_of_employee) {

           $data = Employee::with('getEmployeeBranch:id,location')
           ->where("employee_branch", $req->branch_of_employee)
               ->where("employee_status", "On")
               ->orderBy("employee_post","asc")
               ->get();

       }  else {

           $data = Employee::with('getEmployeeBranch:id,location')
           ->where("employee_status", "On")
           ->get();
           
       }


        if ($req->ajax()) {
            $html = [];
            $html["title"] = "View Employee/Others";
            $branches = branches();
            $html["view"] = view("admin.view-employee-other", compact("data"))->render();
            return response()->json($html, 200);
        }

        // $pdf = PDF::loadView("admin.get-pdf-employee-others", compact("data"));
        // $file = $pdf->download('nfc_closing.pdf');
        // return response()->json([base64_encode($file)], 200);

    }



    function getDataofEmployee(Request $req)
    {

        if ($req->ajax()) {

            if($req->search_value){   

                 $count_employee = Employee::with('getEmployeeBranch:id,location')
                 ->where("employee_name", "like", '%' . $req->search_value . '%')->count();


                 $data = Employee::with('getEmployeeBranch:id,location')
                    ->where("employee_name", "like", '%' . $req->search_value . '%')
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");
             

            } elseif ($req->type_of_employee && $req->branch_of_employee && $req->employee_post) {

                $count_employee =Employee::with('getEmployeeBranch:id,location')->where("employee_type", $req->type_of_employee)
                ->where("employee_branch", $req->branch_of_employee)
                ->where("employee_post", $req->employee_post)
                ->where("employee_status", "On")->count();

                $data = Employee::with('getEmployeeBranch:id,location')->where("employee_type", $req->type_of_employee)
                    ->where("employee_branch", $req->branch_of_employee)
                    ->where("employee_post", $req->employee_post)
                    ->where("employee_status", "On")
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");
                

            }elseif ($req->type_of_employee && $req->employee_post) {

                $count_employee =Employee::with('getEmployeeBranch:id,location')
                ->where("employee_type", $req->type_of_employee)
                ->where("employee_post", $req->employee_post)
                ->where("employee_status", "On")->count();

                $data = Employee::with('getEmployeeBranch:id,location')
                    ->where("employee_type", $req->type_of_employee)
                    ->where("employee_post", $req->employee_post)
                    ->where("employee_status", "On")
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");
                

            }  elseif ($req->type_of_employee && $req->branch_of_employee) {

                $count_employee = Employee::with('getEmployeeBranch:id,location')->where("employee_type", $req->type_of_employee)
                    ->where("employee_branch", $req->branch_of_employee)
                    ->where("employee_status", "On")
                    ->count();

                $data = Employee::with('getEmployeeBranch:id,location')->where("employee_type", $req->type_of_employee)
                    ->where("employee_branch", $req->branch_of_employee)
                    ->where("employee_status", "On")
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");
               

            } elseif ($req->type_of_employee) {

                $count_employee = Employee::with('getEmployeeBranch:id,location')
                    ->where("employee_type", $req->type_of_employee)
                    ->where("employee_status", "On")
                    ->count();

                $data = Employee::with('getEmployeeBranch:id,location')
                    ->where("employee_type", $req->type_of_employee)
                    ->where("employee_status", "On")
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");

            } elseif ($req->branch_of_employee) {

                $count_employee = Employee::with('getEmployeeBranch:id,location')
                ->where("employee_branch", $req->branch_of_employee)
                    ->where("employee_status", "On")
                    ->count();

                $data = Employee::with('getEmployeeBranch:id,location')
                ->where("employee_branch", $req->branch_of_employee)
                    ->where("employee_status", "On")
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");

            }  elseif ($req->employee_post) {

                $count_employee = Employee::with('getEmployeeBranch:id,location')
                ->where("employee_post", $req->employee_post)
                    ->where("employee_status", "On")
                    ->count();

                $data = Employee::with('getEmployeeBranch:id,location')
                ->where("employee_post", $req->employee_post)
                    ->where("employee_status", "On")
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");

            }else {

                $count_employee = Employee::with('getEmployeeBranch:id,location')
                ->where("employee_status", "On")
                ->count();
                

                $data = Employee::with('getEmployeeBranch:id,location')
                ->where("employee_status", "On")
                ->offset($req->start)->limit(10)->orderBy("id", "desc");
                
            }


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->employee_no ? "(" . $row->employee_no . ") " . $row->employee_name . "-" . $row->employee_post : $row->employee_name;
                })
                ->addColumn('type', function ($row) {
                    return $row->employee_type;
                })
                ->addColumn('branch', function ($row) {
                    return $row->getEmployeeBranch->location;
                })
                ->addColumn('basic_salary', function ($row) {
                    return $row->basic_sallary ?  number_format($row->basic_sallary) : "-";
                })
                
                ->addColumn('cnic', function ($row) {
                    return $row->cnic ? $row->cnic : "-";
                })

                ->addColumn('phone_no', function ($row) {
                    return $row->phone_no ?  $row->phone_no : "-";
                })

                ->addColumn('father_cnic', function ($row) {
                    return $row->father_cnic ?  $row->father_cnic : "-";
                })
                ->addColumn('father_phone_no', function ($row) {
                    return $row->father_phone_no ?  $row->father_phone_no : "-";
                })
                ->addColumn('doj', function ($row) {
                    return $row->joining ? date_format(date_create($row->joining),"d-m-Y") : "-";
                })
                ->addColumn('dol', function ($row) {
                    return $row->leaving ?  date_format(date_create($row->leaving),"d-m-Y") : "-";
                })
                ->addColumn('status', function ($row) {
                    return $row->employee_status;
                })

                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group btn-sm">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    </button>
                    <div class="dropdown-menu">
                    <a href="javascript:void(0)" class="dropdown-item  edit_employee_others"  data-id="' . $row->id . '">Edit</a>';
                    $btn .= '</div>
                    </div>';
                    return $btn;
                })
                ->setFilteredRecords($count_employee)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    function editEmployeeOthers(Request $req)
    {

        $employee = Employee::find($req->id);

        return response()->json($employee, 200);
    }


    function HblForm()
    {
        $branches = branches();
        return view("admin.hbl-form", compact('branches'));
    }


    function insertHblAmount(Request $req)
    {
        if ($req->ajax()) {

            $validation = [
                // 'paid_date' =>  'required',
                'employee_id' =>  'required',
                // 'paid_for_month' =>  'required',
                'purpose' =>  'required',
                // 'status' =>  'required',
                'amount' =>  'required',
                // 'remarks' =>  'required',
            ];

            $validator = Validator::make($req->all(), $validation);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 400);
            }

            if($req->hidden_id){
                $easypaisa_paid = HblAmounts::find($req->hidden_id);
            }else{
                $easypaisa_paid = new HblAmounts();
            }
            
            // $easypaisa_paid->paid_date = $req->paid_date;
            $easypaisa_paid->employee_id = $req->employee_id;
            $easypaisa_paid->purpose = $req->purpose;
            if (isset($req->advance_payment_month)) {
                $easypaisa_paid->paid_for_month_date = $req->advance_payment_month . "-01";
            }

            $easypaisa_paid->status = "Paid";
            $easypaisa_paid->amount = $req->amount;
            $easypaisa_paid->remarks = $req->remarks;
            // $easypaisa_paid->paid_date = $req->paid_date;
            $easypaisa_paid->save();
        }
    }


    function getReportofHblAmount(Request $req)
    {

        if ($req->ajax()) {



            if($req->from_date && $req->to_date && $req->search_value){

                $search_value = $req->search_value;

                $total_count = HblAmounts::with("getEmployee:id,employee_name,employee_post")
                ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();
    
                $data = HblAmounts::with("getEmployee:id,employee_name,employee_post")
                ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->orderBy("id", "desc");


              


            }elseif($req->search_value){

            $search_value = $req->search_value;

            $total_count = HblAmounts::with("getEmployee:id,employee_name,employee_post")
            ->whereHas('getEmployee', function ($query)use($search_value){
                $query->where("employee_name", "like", '%' . $search_value . '%');
            })->count();

            $data = HblAmounts::with("getEmployee:id,employee_name,employee_post")
            ->whereHas('getEmployee', function ($query)use($search_value){
                $query->where("employee_name", "like", '%' . $search_value . '%');
            })->orderBy("id", "desc");

           // $total_count = count($data->get());

            }elseif ($req->from_date && $req->to_date && $req->type && $req->type && $req->employee_others) {
            
                $data = HblAmounts::with("getEmployee:id,employee_name,employee_post")
                    ->where("purpose", $req->type)
                    ->where("employee_id", $req->employee_others)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");

                $total_count = DB::table('hbl_amounts')->where("purpose", $req->type)
                ->where("employee_id", $req->employee_others)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)->count();
                   
            }elseif ($req->from_date && $req->to_date && $req->type) {
            
                $data = HblAmounts::with("getEmployee:id,employee_name,employee_post")
                    ->where("purpose", $req->type)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");

                $total_count = DB::table('hbl_amounts')
                ->where("purpose", $req->type)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)->count();
                   
            } elseif ($req->from_date && $req->to_date){

                $data = HblAmounts::with("getEmployee:id,employee_name,employee_post")
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");

            $total_count = DB::table('hbl_amounts')->where("purpose", $req->type)
            ->whereDate("created_at", ">=", $req->from_date)
            ->whereDate("created_at", "<=", $req->to_date)->count();
            }else{
                $total_count = DB::table('hbl_amounts')->count();
                $data = HblAmounts::with("getEmployee:id,employee_name,employee_post")
                ->offset($req->start)->limit(10)->orderBy("id", "desc");
            }


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('paid_date', function ($row) {
                    return date_format(date_create($row->created_at), "d-m-Y");
                })
                ->addColumn('employee', function ($row) {
                    if ($row->getEmployee->employee_post !== null) {
                        $advance_date = date_format(date_create($row->paid_for_month_date), "d-M-Y");
                        return $row->getEmployee->employee_name . " ( " . $row->getEmployee->employee_post . " )- " . $advance_date;
                    } else {
                        return $row->getEmployee->employee_name;
                    }
                })
                ->addColumn('purpose', function ($row) {
                    return $row->purpose;
                })
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->addColumn('amount', function ($row) {
                    return number_format($row->amount);
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group btn-sm">
               <button type="button" class="btn btn-sm btn-info dropdown-toggle" ' .(Auth::User()->user_type == "User" ? "disabled" : ""). ' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               Action
               </button>
               <div class="dropdown-menu">
               <a href="javascript:void(0)" class="dropdown-item  edit-hbl-amount"  data-id="' . $row->id . '">Edit</a>';

                   // $btn .= '<a  href="javascript:void(0)" class="dropdown-item return-easypaisa-amount" data-id="' . $row->id . '">Return</a>';

                    $btn .= '</div>
               </div>';

                    // $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';

                    return $btn;
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    function editHBLAmount(Request $req){

        $hbl_detail = HblAmounts::find($req->id);

        $find_employee_type = Employee::find($hbl_detail->employee_id);
 
        return response()->json([$find_employee_type, $hbl_detail], 200);

    }


    function getFullReportofHblAmount(Request $req)
    {

        $branches = branches();
        $employees = DB::table('employees')
        ->select(DB::raw('id,employee_name'))
        ->get();

        return view("admin.get-full-report-of-hbl-account", compact("branches","employees"));

        // if ($req->ajax()) {
        //     $html = [];
        //     $html["title"] = "HBL Paid Detail";
            // $branches = branches();
            // $html["view"] = view("admin.get-full-report-of-hbl-account", compact("branches"))->render();
        //     return response()->json($html, 200);
        // }
    }

    function salaryForm()
    {


        //      $month  = date("Y-m")."-"."01";
        //     return $data = Employee::with('salary:id,salary_month')->whereHas('salary', function ($query)use($month){
        //        $query->where('salary_month',$month);
        //    })->where("employee_type","Employee")->orderBy("id", "asc")->get();

        return view("admin.salary");
    }

    function getDataofEmployeeSalary(Request $req)
    {
        // ->whereDoesntHave('salary', function ($query) use ($month) {
        //     $query->where('salary_month', $month);
        // })
        if ($req->ajax()) {
            if ($req->month) {
                $count_employee = count(DB::table('employees')->where("employee_type", "Employee")->get());
                $month  = $req->month . "-" . "01";
                $last_date = date("Y-m-t" ,strtotime($month));
                $data = Employee::whereDoesntHave('salary', function ($query) use ($month) {
                        $query->where('salary_month', $month);
                    })->where("employee_status","On")
                ->whereDate('joining', '<=', $last_date)
                ->where("employee_type", "Employee")->orderBy("id", "asc");
            } else {
                $count_employee = count(DB::table('employees')->where("employee_type", "Employee")->get());
                $month  = date("Y-m") . "-" . "01";
                $last_date = date("Y-m-t" ,strtotime($month));
                $data = Employee::whereDoesntHave('salary', function ($query) use ($month) {
                        $query->where('salary_month', $month);
                    })
                ->where("employee_status","On")
                ->whereDate('joining', '<=', $last_date)
                ->where("employee_type", "Employee")->orderBy("id", "asc");
            }
            // })->where("plot_area", $req->block)->where("status", "On")->offset($req->start)->limit(10)->orderBy("id", "DESC");
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('employee_no', function ($row) {
                    return $row->employee_no;
                })
                ->addColumn('name', function ($row) {
                    return $row->employee_name;
                })
                ->addColumn('post', function ($row) {
                    return $row->employee_post;
                })
                ->addColumn('branch', function ($row) {
                    return $row->getEmployeeBranch->location;
                })
                ->addColumn('salary', function ($row) {
                    return number_format($row->basic_sallary);
                })
               
                ->addColumn('action', function ($row) use ($month) {
                    $btn = '<div class="btn-group btn-sm">
                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Action
                </button>
                <div class="dropdown-menu">
                <a href="javascript:void(0)" class="dropdown-item  pay_now_salary"  data-id="' . $row->id . "," . $month . "," . $row->basic_sallary . "," . $row->employee_branch.",".$row->employee_name.",".$row->getEmployeeBranch->location.",".$row->joining.",".$row->employee_post.'">View Salary</a>';
                    $btn .= '</div>
                </div>';
                    return $btn;
                })
                ->setFilteredRecords($count_employee)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    function payNowSalary(Request $req, $id, $date, $salary, $branch, $name, $employee_branch, $joining,$employee_post)
    {
       
        // in variables the date is current month
        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Pay Salary (".date_format(date_create($date),"M-Y").")";
            $html["view"] = view("admin.pay-now-salary", compact("id", "date", "salary", "branch","name","employee_branch","joining","employee_post"))->render();
            return response()->json($html, 200);
        }




        // return view("admin.pay-now-salary");
    }

    function checkAdvanceSalary(Request $req)
    {


        $current_month = $req->date;
        $date = new DateTime($current_month);
        // $date->modify('first day of last month');
        $firstDayOfPreviousMonth = $date->format('Y-m-01');
        $lastDayOfPreviousMonth = $date->format('Y-m-t');



        $check_advance_easypaisa = DB::table('easypaisa_paid_amounts')
            ->select(DB::raw('sum(amount) as sum'))
            ->where("employee_id", $req->id)
            ->where("purpose", "Advance")
            ->whereDate("paid_for_month_date", ">=", $firstDayOfPreviousMonth)
            ->whereDate("paid_for_month_date", "<=", $lastDayOfPreviousMonth)
            ->get();

        // return  response()->json($check_advance_easypaisa);

        $check_advance_hbl = DB::table('hbl_amounts')
            ->select(DB::raw('sum(amount) as sum'))
            ->where("employee_id", $req->id)
            ->where("purpose", "Advance")
            ->whereDate("paid_for_month_date", ">=", $firstDayOfPreviousMonth)
            ->whereDate("paid_for_month_date", "<=", $lastDayOfPreviousMonth)
            ->get();

        $check_advance_locker = DB::table('locker_details')
            ->select(DB::raw('sum(amount) as sum'))
            ->where("employee_id", $req->id)
            ->where("purpose", "Advance")
            ->whereDate("paid_for_month_date", ">=", $firstDayOfPreviousMonth)
            ->whereDate("paid_for_month_date", "<=", $lastDayOfPreviousMonth)
            ->get();


        //locker advance check 

        return response()->json([ (isset($check_advance_locker[0]) ? $check_advance_locker[0]->sum : 0) +  (isset($check_advance_easypaisa[0]) ? $check_advance_easypaisa[0]->sum : 0) +  (isset($check_advance_hbl[0]) ? $check_advance_hbl[0]->sum : 0)], 200);
    
    
    }



    function checkPendings(Request $req){

       

        $current_month = $req->date;
        $date = new DateTime($current_month);
        // $date->modify('first day of last month');
         $firstDayOfPreviousMonth = $date->format('Y-m-01');
        $lastDayOfPreviousMonth = $date->format('Y-m-t');
        

        $check_pendings = DB::table('pendings')
        ->select(DB::raw('sum(amount) as sum'))
        ->where("employee_id", $req->id)
        ->where("status", "Pending")
        ->whereDate("created_at", ">=", $firstDayOfPreviousMonth)
        ->whereDate("created_at", "<=", $lastDayOfPreviousMonth)
        ->get();

        return response()->json([ (isset($check_pendings[0]) ? $check_pendings[0]->sum : 0)] , 200);
    

    }

    function checkRiderAmounts(Request $req){

        $current_month = $req->date;
        $date = new DateTime($current_month);
        // $date->modify('first day of last month');
        $firstDayOfPreviousMonth = $date->format('Y-m-01');
        $lastDayOfPreviousMonth = $date->format('Y-m-t');

        $check_rides_amount = DB::table('rides')
        ->select(DB::raw('sum(amount) as sum'))
        ->where("employee_id", $req->id)
        ->where("status", "Unpaid")
        ->whereDate("created_at", ">=", $firstDayOfPreviousMonth)
        ->whereDate("created_at", "<=", $lastDayOfPreviousMonth)
        ->get();

        return response()->json([ (isset($check_rides_amount[0]) ? $check_rides_amount[0]->sum : 0)] , 200);
    }


    function finalSalaryInsert(Request $req)
    {

        if ($req->ajax()) {

            $validation = [
                'pay_through' =>  'required',

            ];

            $validator = Validator::make($req->all(), $validation);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 400);
            }

            
            $firstDayOfMonth = $req->paid_for_month;;
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfMonth));


            if ($req->pay_through == "Easypaisa") {
                $paid_amount = new EasypaisaPaidAmount();
                // $paid_amount->paid_date = date("Y-m-d");
                $paid_amount->employee_id = $req->employee_id;
                $paid_amount->purpose = "Salary";
                $paid_amount->paid_for_month_date = $req->paid_for_month;
                $paid_amount->status = "Paid";
                $paid_amount->amount = $req->salary;
                $paid_amount->save();

                $salary = new salary();
                $salary->employee_id = $req->employee_id;
                $salary->branch_id = $req->branch;
                $salary->basic_salary = $req->basic_salary;
                $salary->advance = $req->get_advance;
                $salary->pendings = $req->pendings;
                // $salary->fuel_amount = $req->fuel_amount;
                $salary->day_of_work_deduction = $req->day_of_work_deduction;
                $salary->addition = $req->addition;
                $salary->remarks = $req->remarks;
                $salary->day_of_work = $req->day_of_work;
                
                $salary->amount = $req->salary;
                $salary->salary_month = $req->paid_for_month;
                $salary->status = "Paid";
                $salary->account_id  = $paid_amount->id;
                $salary->account_name  = $req->pay_through;
                $salary->save();

                

                DB::table('pendings')
                ->where("employee_id", $req->employee_id)
                ->where("status", "Pending")
                ->whereDate("created_at", ">=" ,$firstDayOfMonth)
                ->whereDate("created_at", "<=" ,$lastDayOfMonth)
                ->update(['status' => 'Paid','account_id'=>$paid_amount->id,'account_name'=>"Easypaisa"]);

            } elseif ($req->pay_through == "HBL") {
                $paid_amount = new HblAmounts();
                // $paid_amount->paid_date = date("Y-m-d");
                $paid_amount->employee_id = $req->employee_id;
                $paid_amount->purpose = "Salary";
                $paid_amount->paid_for_month_date = $req->paid_for_month;
                $paid_amount->status = "Paid";
                $paid_amount->amount = $req->salary;
                $paid_amount->save();

                $salary = new salary();
                $salary->employee_id = $req->employee_id;
                $salary->branch_id = $req->branch;
                $salary->basic_salary = $req->basic_salary;
                $salary->advance = $req->get_advance;
                $salary->pendings = $req->pendings;
                // $salary->fuel_amount = $req->fuel_amount;
                $salary->day_of_work_deduction = $req->day_of_work_deduction;
                $salary->addition = $req->addition;
                $salary->remarks = $req->remarks;
                $salary->day_of_work = $req->day_of_work;
                //this salary is after deduction
                $salary->amount = $req->salary;
                $salary->salary_month = $req->paid_for_month;
                $salary->status = "Paid";
                $salary->account_id  = $paid_amount->id;
                $salary->account_name  = $req->pay_through;
                $salary->save();

                DB::table('pendings')
                ->where("employee_id", $req->employee_id)
                ->where("status", "Pending")
                ->whereDate("created_at", ">=" ,$firstDayOfMonth)
                ->whereDate("created_at", "<=" ,$lastDayOfMonth)
                ->update(['status' => 'Paid','account_id'=>$paid_amount->id,'account_name'=>"HBL"]);

            }
            elseif ($req->pay_through == "Others") {
                $paid_amount = new OtherSalary();
                // $paid_amount->paid_date = date("Y-m-d");
                $paid_amount->employee_id = $req->employee_id;
                $paid_amount->purpose = "Salary";
                $paid_amount->paid_for_month_date = $req->paid_for_month;
                $paid_amount->status = "Paid";
                $paid_amount->amount = $req->salary;
                $paid_amount->save();

                $salary = new salary();
                $salary->employee_id = $req->employee_id;
                $salary->branch_id = $req->branch;
                $salary->basic_salary = $req->basic_salary;
                $salary->advance = $req->get_advance;
                $salary->pendings = $req->pendings;
                // $salary->fuel_amount = $req->fuel_amount;
                $salary->day_of_work_deduction = $req->day_of_work_deduction;
                $salary->addition = $req->addition;
                $salary->remarks = $req->remarks;
                $salary->day_of_work = $req->day_of_work;
                //this salary is after deduction
                $salary->amount = $req->salary;
                $salary->salary_month = $req->paid_for_month;
                $salary->status = "Paid";
                $salary->account_id  = $paid_amount->id;
                $salary->account_name  = $req->pay_through;
                $salary->save();

                DB::table('pendings')
                ->where("employee_id", $req->employee_id)
                ->where("status", "Pending")
                ->whereDate("created_at", ">=" ,$firstDayOfMonth)
                ->whereDate("created_at", "<=" ,$lastDayOfMonth)
                ->update(['status' => 'Paid','account_id'=>$paid_amount->id,'account_name'=>"Others"]);

            } elseif ($req->pay_through == "Locker") {
                $paid_amount = new lockerDetail();
                // $paid_amount->paid_date = date("Y-m-d");
                $paid_amount->employee_id = $req->employee_id;
                $paid_amount->purpose = "Salary";
                $paid_amount->paid_for_month_date = $req->paid_for_month;
                $paid_amount->status = "Paid";
                $paid_amount->amount = $req->salary;
                $paid_amount->save();

                $salary = new salary();
                $salary->employee_id = $req->employee_id;
                $salary->branch_id = $req->branch;
                $salary->basic_salary = $req->basic_salary;
                $salary->advance = $req->get_advance;
                $salary->pendings = $req->pendings;
                //$salary->fuel_amount = $req->fuel_amount;
                $salary->day_of_work_deduction = $req->day_of_work_deduction;
                $salary->addition = $req->addition;
                $salary->remarks = $req->remarks;
                $salary->day_of_work = $req->day_of_work;
                //this salary is after deduction
                $salary->amount = $req->salary;
                $salary->salary_month = $req->paid_for_month;
                $salary->status = "Paid";
                $salary->account_id  = $paid_amount->id;
                $salary->account_name  = $req->pay_through;
                $salary->save();

                DB::table('pendings')
                ->where("employee_id", $req->employee_id)
                ->where("status", "Pending")
                ->whereDate("created_at", ">=" ,$firstDayOfMonth)
                ->whereDate("created_at", "<=" ,$lastDayOfMonth)
                ->update(['status' => 'Paid','account_id'=>$paid_amount->id,'account_name'=>"Locker"]);

            }
        }
    }


    function deleteSalaryRecord(Request $req){

        //in this code we delete all records related to any particular salary

       
        if($req->data[1] == "Easypaisa"){

            

           $easypaisa_paid_amount = EasypaisaPaidAmount::find($req->data[0]);
           $easypaisa_paid_amount->delete();

           DB::table('pendings')
                ->where("account_id", $req->data[0])
                ->where("account_name", $req->data[1])
                ->update(['status' => 'Pending','account_id'=>null,'account_name'=>null]);

         DB::table('salaries')
                ->where("account_id", $req->data[0])
                ->where("account_name", $req->data[1])
                ->delete();


        }elseif($req->data[1] == "HBL"){

            $easypaisa_paid_amount = HblAmounts::find($req->data[0]);
            $easypaisa_paid_amount->delete();
 
            DB::table('pendings')
                 ->where("account_id", $req->data[0])
                 ->where("account_name", $req->data[1])
                 ->update(['status' => 'Pending','account_id'=>null,'account_name'=>null]);
 
            DB::table('salaries')
                 ->where("account_id", $req->data[0])
                 ->where("account_name", $req->data[1])
                 ->delete();

         }

         elseif($req->data[1] == "Others"){

            $easypaisa_paid_amount = OtherSalary::find($req->data[0]);
            $easypaisa_paid_amount->delete();
 
            DB::table('pendings')
                 ->where("account_id", $req->data[0])
                 ->where("account_name", $req->data[1])
                 ->update(['status' => 'Pending','account_id'=>null,'account_name'=>null]);
 
            DB::table('salaries')
                 ->where("account_id", $req->data[0])
                 ->where("account_name", $req->data[1])
                 ->delete();

         }  elseif($req->data[1] == "Locker"){
            $easypaisa_paid_amount = lockerDetail::find($req->data[0]);
            $easypaisa_paid_amount->delete();
 
            DB::table('pendings')
                 ->where("account_id", $req->data[0])
                 ->where("account_name", $req->data[1])
                 ->update(['status' => 'Pending','account_id'=>null,'account_name'=>null]);
 
          DB::table('salaries')
                 ->where("account_id", $req->data[0])
                 ->where("account_name", $req->data[1])
                 ->delete();
         }


    }


    function getSalaryReport(Request $req)
    {

        if ($req->ajax()) {
            if ($req->salary_month && $req->status == "Unpaid") {
                $count_employee = count(DB::table('employees')->get());
                $month  = $req->salary_month . "-" . "01";
                $data = Employee::with('salary:id,salary_month')->whereDoesntHave('salary', function ($query) use ($month) {
                    $query->where('salary_month', $month);
                })->where("employee_type", "Employee")
                ->whereDate('joining', '>=', $month)
                ->where("employee_status", "On")
                ->offset($req->start)->limit(10)->orderBy("id", "asc");

            } elseif ($req->salary_month && $req->status == "Paid") {

                $month = $req->salary_month;
                $count_employee = count(DB::table('employees')->get());
                $month  = $req->salary_month . "-" . "01";
                $data = Employee::with('salary:id,salary_month')->whereHas('salary', function ($query) use ($month) {
                    $query->where('salary_month', $month);
                })->where("employee_type", "Employee")
                ->where("employee_status", "On")
                ->whereDate('joining', '>=', $month)
                ->offset($req->start)->limit(10)->orderBy("id", "asc");

            } else {

                $count_employee = count(DB::table('employees')->get());
                $month  = date("Y-m") . "-" . "01";
                $data = Employee::with('salary:id,salary_month')->whereHas('salary', function ($query) use ($month) {
                    $query->where('salary_month', $month);
                })->where("employee_type", "Employee")
                ->where("employee_status", "On")
                ->whereDate('joining', '>=', $month)
                ->offset($req->start)->limit(10)->orderBy("id", "asc");

            }


            // })->where("plot_area", $req->block)->where("status", "On")->offset($req->start)->limit(10)->orderBy("id", "DESC");


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('employee_no', function ($row) {
                    return $row->employee_no;
                })
                ->addColumn('name', function ($row) {
                    return $row->employee_name;
                })
                ->addColumn('post', function ($row) {
                    return $row->employee_post;
                })
                ->addColumn('branch', function ($row) {
                    return $row->getEmployeeBranch->location;
                })
                ->addColumn('sallary', function ($row) {
                    return $row->basic_sallary;
                    // return $row->salary;
                })
                ->addColumn('action', function ($row) use ($month) {

                    $btn = '<div class="btn-group btn-sm">
                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Action
                </button>
                <div class="dropdown-menu">
                <a href="javascript:void(0)" class="dropdown-item  pay_now_salary"  data-id="' . $row->id . "," . $month . "," . $row->basic_sallary . "," . $row->employee_branch . '">View Salary</a>';
                    $btn .= '</div>
                </div>';
                    return $btn;
                })
                ->setFilteredRecords($count_employee)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    function getSalaryReportView(Request $req)
    {

        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Salary Report";
            $html["view"] = view("admin.get-salary-report")->render();
            return response()->json($html, 200);
        }
    }

    function getSalaryPdf(Request $req)
    {
        if ($req->ajax()) {
            $month  = $req->month . "-" . "01";
            $data = salary::with("employee:id, name, post, branch, basic_sallary")->with('employee.getEmployeeBranch')->where("salary_month", $month)->orderBy("id", "asc")->get();
            $pdf = PDF::loadView("admin.get-salary-pdf", compact("data", "month"));
            $file = $pdf->download('nfc_closing.pdf');
            return response()->json([base64_encode($file)], 200);
            // return view("admin.salary-pdf-view", compact("data","month"));    

        }
    }


    function getPaidSalary(Request $req, $get_month)
    {
        if ($req->ajax()) {
            $month  = $get_month . "-" . "01";
            $data = salary::with("employee:id, name, post, branch, basic_sallary")
            ->with('employee.getEmployeeBranch')
            ->where("salary_month", $month)
            ->orderBy("id", "asc")->get();
            $html = [];
            $html["title"] = "Salary Report";
            $html["view"] = view("admin.salary-paid-view", compact("data", "month"))->render();
            return response()->json($html, 200);
        }
    }



    function getSalaryUnpaidDetail(Request $req, $get_month)
    {
        if ($req->ajax()) {
            // $month  = $get_month . "-" . "01";
            // $last_date = date("Y-m-t" ,strtotime($month));

            // $data = Employee::whereDoesntHave('salary', function ($query) use ($month) {
            //             $query->where('salary_month', $month);
            // })->where("employee_status","On")
            // ->whereDate('joining', '<=', $last_date)
            // ->where("employee_type", "Employee")->orderBy("id", "asc")->get();

            $month = $get_month."-01";
            $last_date = date("Y-m-t" ,strtotime($month));

            $salary_detail = Employee::with("getEmployeeBranch:id,location")
            ->with(['easypaisa' => function ($query) use($month){
               $query->where('paid_for_month_date' ,$month)
               ->where("purpose","Advance");
           }])
           ->with(['hbl' => function ($query) use($month){
               $query->where('paid_for_month_date',$month)
               ->where("purpose","Advance");
           }])
           ->with(['locker' => function ($query) use($month){
               $query->where('paid_for_month_date' ,$month)
               ->where("purpose","Advance");
           }])
           ->with(['pendings' => function ($query) use($month, $last_date){
            $query->whereDate('created_at' , ">=" ,$month)
            ->whereDate('created_at' , "<=" ,$last_date)
            ->where("status","Pending");
            }])
           ->whereDoesntHave('salary', function ($query) use ($month) {
            $query->where('salary_month', $month);
            })
            ->whereDate('joining', '<=', $last_date)
           ->where("employee_type", "Employee")
           ->where("employee_status", "On")
           ->get();

            $html = [];
            $html["title"] = "Salary Report (".date_format(date_create($month),"M-Y") . ")" ;
            $html["view"] = view("admin.salary-unpaid-view", compact("salary_detail", "month"))->render();
            return response()->json($html, 200);
        }
    }




    function payInstallment(){

         $installment = DB::table('locker_details')
            ->select(DB::raw('sum(amount) as sum'))
            ->where("employee_id",137)
            ->get();

          $pay_installment = DB::table('installments')
            ->select(DB::raw('sum(pay_installment) as sum'))
            ->get();


        return view("admin.installment-deduction", compact("installment","pay_installment"));
    }


    function payInstallmentInsert(Request $req){



        if($req->hidden_id){
            $installment = installment::find($req->hidden_id);
        }else{
            $installment = new installment();
        }
        
        $installment->pay_installment = $req->pay_installment_amount;
        $installment->purpose = $req->purpose;
        $installment->save();
        return response()->json("saved", 200);

    }
    

    function getInstallmentList(Request $req){

        
        if ($req->ajax()) {

          
                $count_employee = count(DB::table('installments')->get());
               
                $data = installment::orderBy("id", "desc");
                 

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return date_format(date_create($row->created_at),"d-m-Y");
                })
                ->addColumn('pay_installment', function ($row) {
                    return $row->pay_installment;
                })
                ->addColumn('purpose', function ($row) {
                    return $row->purpose;
                })
                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group btn-sm">
                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Action
                </button>
                <div class="dropdown-menu">
                    <a href="javascript:void(0)" class="dropdown-item edit_installment_amount"  data-id="' . $row->id . '">Edit</a>
                    <a  href="javascript:void(0)" class="dropdown-item delete_installment_amount" data-id="' . $row->id . '">Delete</a>
                    </div>
                </div>';
                return $btn;

                })
                ->setFilteredRecords($count_employee)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }

    }


    function editInstallment(Request $req){

        $installment = installment::find($req->id);
        return response()->json($installment, 200);
    }


    function deleteInstallment(Request $req){

        $installment = installment::find($req->id);
        $installment->delete();

    }


    function getInstallmentReport(Request $req, $from_date, $to_date){



        $pay_installment_old = installment::orderBy("id", "desc")
        ->whereDate("created_at","<",$from_date)
        ->sum("pay_installment");


        $pay_installment = installment::orderBy("id", "desc")
        ->whereDate("created_at",">=",$from_date)
        ->whereDate("created_at","<=",$to_date)
        ->get();

        $installment = DB::table('locker_details')
        ->select(DB::raw('sum(amount) as sum'))
        ->where("employee_id",137)
        ->get();


        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Installment Pay Report";
            $html["view"] =view("admin.get-installment-report", compact("pay_installment","installment","pay_installment_old"))->render();
            return response()->json($html, 200);
        }

        //return view("admin.get-installment-report", compact("pay_installment","installment"));
    }


    function viewInstallGrandReport(Request $req, $from_date, $to_date){


        $pay_installment_old = installment::orderBy("id", "desc")
        ->whereDate("created_at","<",$from_date)
        ->sum("pay_installment");


        $installment_old = lockerDetail::where("employee_id",137)
        ->whereDate("created_at","<",$from_date)
        ->sum("amount");

        $calculate_old_install = $installment_old - $pay_installment_old;

        
        $pay_installment = installment::orderBy("id", "desc")
        ->whereDate("created_at",">=",$from_date)
        ->whereDate("created_at","<=",$to_date)
        ->sum("pay_installment");

        $installment = lockerDetail::where("employee_id",137)
        ->whereDate("created_at",">=",$from_date)
        ->whereDate("created_at","<=",$to_date)
        ->get();


       

        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Installment Grand Report";
            $html["view"] =view("admin.view-install-grand-report", compact("pay_installment","installment","pay_installment_old","installment_old","calculate_old_install"))->render();
            return response()->json($html, 200);
        }

        //return view("admin.view-install-grand-report", compact("pay_installment","installment"));

    }

    function getSadqaReport(Request $req)
    {

        $demand = DB::table('closings')
            ->select(DB::raw('date,sum(amount) as sum'))
            ->where("head", "2")
            ->where("location", "!=" ,6)
            ->groupBy('date')
            ->get();

        
        if ($req->ajax()) {
                $html = [];
                $html["title"] = "View Sadqa";
                $html["view"] =view("admin.get-sadqa-view", compact('demand'))->render();
                return response()->json($html, 200);
            }
    

        // $pdf = PDF::loadView("admin.get-sadqa-view", compact('demand'));
        // $file = $pdf->download('nfc_closing.pdf');
        // return response()->json([base64_encode($file)], 200);

        // return view("admin.get-sadqa-view",compact('demand'));
    }

    function locker()
    {
        $branches = branches();
        return view("admin.locker", compact('branches'));
    }

    function addLockerAmountForm(Request $req)
    {

        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Locker Amount Form";
            $branches = branches();
            $html["view"] = view("admin.add-locker-amount", compact("branches"))->render();
            return response()->json($html, 200);
        }
    }

    function insertLockerAmount(Request $req)
    {


        $validation = [

            'locker_amount' => 'required'
        ];

        $validator = Validator::make($req->all(), $validation);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        if ($req->locker_hidden_id) {
            $locker = Locker::find($req->locker_hidden_id);
        } else {
            $locker = new Locker();
        }
        $locker->amount = $req->locker_amount;
        $locker->remarks = $req->locker_remarks;
        $locker->operator = userName();
        $locker->save();
        return response()->json("saved", 200);
    }


    function lockerAmountList(Request $req)
    {

        if ($req->ajax()) {

            if ($req->from_date_locker && $req->to_date_locker) {

                // $count_locker = count(DB::table('lockers')
                // ->whereDate("created_at", ">=" , $req->from_date_locker)
                // ->whereDate("created_at", "<=" , $req->to_date_locker)
                // ->get());

                $data = Locker::whereDate("created_at", ">=", $req->from_date_locker)
                    ->whereDate("created_at", "<=", $req->to_date_locker)
                    ->offset($req->start)
                    ->limit(10)->orderBy("id", "desc");

                $count_locker =  count($data->get());
            } else {
                // $count_locker = count(DB::table('lockers')->get());
                $data = Locker::offset($req->start)->limit(10)->orderBy("id", "desc");
                $count_locker =  count($data->get());
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return date_format(date_create($row->created_at), "d-m-Y");
                })
                ->addColumn('amount', function ($row) {
                    return $row->amount;
                })
                ->addColumn('operator', function ($row) {
                    return $row->operator;
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks ? $row->remarks : "-";
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group btn-sm">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    </button>
                    <div class="dropdown-menu">
                    <a href="javascript:void(0)" class="dropdown-item edit_locker_amount"  data-id="' . $row->id . '">Edit</a>
                    <a  href="javascript:void(0)" class="dropdown-item delete_locker_amount" data-id="' . $row->id . '">Delete</a>
                    </div>
                    </div>';
                    return $btn;
                })
                ->setFilteredRecords($count_locker)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    function editLockerAmount(Request $req)
    {

        $locker = Locker::find($req->id);
        return response()->json($locker, 200);
    }


    function getReportLockerAmount(Request $req)
    {

        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Locker Report";
            $html["view"] = view("admin.get-full-report-locker")->render();
            return response()->json($html, 200);
        }
    }


    function getPdfLockerAmount(Request $req)
    {

        $from = $req->from_date;
        $to = $req->to_date;

        if ($req->from_date && $req->to_date) {
            $data = Locker::whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)->get();
        } else {
            $data = Locker::orderBy("id", "desc")->get();
        }


        $pdf = PDF::loadView("admin.locker-amount-pdf", compact("data", "from", "to"));
        $file = $pdf->download('nfc_locker_amount.pdf');
        return response()->json([base64_encode($file)], 200);

        // return view("admin.locker-amount-pdf", compact("data"));

    }

   

    function insertLockerPaidAmount(Request $req)
    {

        if ($req->ajax()) {

            $validation = [
                // 'paid_date' =>  'required',
                'employee_id' =>  'required',
                // 'paid_for_month' =>  'required',
                'purpose' =>  'required',
                // 'status' =>  'required',
                'amount' =>  'required',
                // 'remarks' =>  'required',
            ];

            $validator = Validator::make($req->all(), $validation);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 400);
            }

            if($req->hidden_id){
                $easypaisa_paid =  lockerDetail::find($req->hidden_id);
            }else{
                $easypaisa_paid = new lockerDetail();
            }
            // $easypaisa_paid->paid_date = $req->paid_date;
            $easypaisa_paid->employee_id = $req->employee_id;
            $easypaisa_paid->purpose = $req->purpose;
            if (isset($req->advance_payment_month)) {
                $easypaisa_paid->paid_for_month_date = $req->advance_payment_month . "-01";
            }
            $easypaisa_paid->status = "Paid";
            $easypaisa_paid->amount = $req->amount;
            $easypaisa_paid->remarks = $req->remarks;
            // $easypaisa_paid->paid_date = $req->paid_date;
            $easypaisa_paid->save();
        }
    }


    function getLockerPaidAmountList(Request $req)
    {

        if ($req->ajax()) {


            if ($req->from_date && $req->to_date && $req->search_value) {

                $search_value = $req->search_value;
            
                $total_count = lockerDetail::with("getEmployee:id,employee_name,employee_post")
                ->with("getEmployee:id,employee_name,employee_post")
                ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();
    

                $data = lockerDetail::with("getEmployee:id,employee_name,employee_post")
                ->with("getEmployee:id,employee_name,employee_post")
                ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc");

            }elseif($req->search_value){

            $search_value = $req->search_value;
            
            $total_count = lockerDetail::with("getEmployee:id,employee_name,employee_post")
            ->with("getEmployee:id,employee_name,employee_post")
            ->whereHas('getEmployee', function ($query)use($search_value){
                $query->where("employee_name", "like", '%' . $search_value . '%');
            })->count();


            $data = lockerDetail::with("getEmployee:id,employee_name,employee_post")
            ->with("getEmployee:id,employee_name,employee_post")
            ->whereHas('getEmployee', function ($query)use($search_value){
                $query->where("employee_name", "like", '%' . $search_value . '%');
            })
            ->offset($req->start)
            ->limit(10)
            ->orderBy("id", "desc");

            }elseif ($req->from_date && $req->to_date && $req->type && $req->employee_others) {
               
                $total_count = DB::table('locker_details')
                    ->where("purpose", $req->type)
                    ->where("employee_id", $req->employee_others)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->count();

                $data = lockerDetail::with("getEmployee:id,employee_name,employee_post")
                    ->where("purpose", $req->type)
                    ->where("employee_id", $req->employee_others)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");

            }elseif ($req->from_date && $req->to_date && $req->type) {
                
                $total_count = DB::table('locker_details')
                    ->where("purpose", $req->type)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->count();

                $data = lockerDetail::with("getEmployee:id,employee_name,employee_post")
                    ->where("purpose", $req->type)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");

                // $total_count = count($data->get());
            }elseif($req->from_date && $req->to_date) {

                
                $total_count = DB::table('locker_details')
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->count();

                $data = lockerDetail::with("getEmployee:id,employee_name,employee_post")
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)
                    ->limit(10)
                    ->orderBy("id", "desc");
            } else {

               
                $data = lockerDetail::with("getEmployee:id,employee_name,employee_post")->offset($req->start)->limit(10)->orderBy("id", "desc");
                $total_count = DB::table('locker_details')->count();
            }


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('paid_date', function ($row) {
                    return  date_format(date_create($row->created_at), "d-m-Y");
                })
                ->addColumn('employee', function ($row) {
                    if ($row->getEmployee->employee_post !== null) {

                        $advance_date = date_format(date_create($row->paid_for_month_date),"d-M-Y");
                        return $row->getEmployee->employee_name . " ( " . $row->getEmployee->employee_post . " ) - " . $advance_date ;
                        
                    } else {
                        return $row->getEmployee->employee_name;
                    }
                })
                ->addColumn('purpose', function ($row) {
                    return $row->purpose;
                })
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->addColumn('amount', function ($row) {
                    return number_format($row->amount);
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks;
                })

                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group btn-sm">
                    <button type="button" class="btn btn-sm btn-info  dropdown-toggle"' .(Auth::User()->user_type == "User" ? "disabled" : ""). 'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    </button>
                    <div class="dropdown-menu">
                    <a href="javascript:void(0)" class="dropdown-item  edit-locker-amount"  data-id="' . $row->id . '">Edit</a>';
                    $btn .= '</div>
                    </div>';

                    // $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';

                    return $btn;
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    function editLockerAmountDetail(Request $req){

       $locker_detail = lockerDetail::find($req->id);

       $find_employee_type = Employee::find($locker_detail->employee_id);

    //    return  $locker_detail;
    return response()->json([$find_employee_type, $locker_detail], 200);


    }


    function getFullReportLockerAmount(Request $req)
    {

        // if ($req->ajax()) {
        //     $html = [];
        //     $html["title"] = "Locker Paid Report";
            //$html["view"] = view("admin.get-full-report-locker-amount")->render();
            // return response()->json($html, 200);
        // }
        
       
        $employees = DB::table('employees')
        ->select(DB::raw('id,employee_name'))
        ->get();
       return view("admin.get-full-report-locker-amount", compact("employees"));
    }

    function getPdfReportLocker(Request $req)
    {

        ini_set('memory_limit', '-1');
       


           $from = $req->from_date;
           $to = $req->to_date;
            

           $locker = DB::table('closings')
           ->select(DB::raw('sum(amount) as sum'))
           ->where("head", "9")
           ->whereIn("location",[1,2,3])
           ->get();
   
   
           $sum_of_sale_datewise = DB::table('closings')
               ->select(DB::raw('sum(closings.amount) as sum'))
               ->where("closings.head",2)
               ->groupBy("date")
               ->orderBy("closings.head","desc")->get();
   
           $sadqa_caculate = 0;
   
           foreach( $sum_of_sale_datewise  as $sale_sum){
               $sadqa_caculate = $sadqa_caculate + ($sale_sum->sum > 0 ? ceil((($sale_sum->sum  / 100) * 2) / 10) * 10 : 0);
           }

        

           if($req->search_value){

            $search_value = $req->search_value;
            
            $data = lockerDetail::with("getEmployee:id,employee_name,employee_post")
            ->with("getEmployee:id,employee_name,employee_post")
            ->whereHas('getEmployee', function ($query)use($search_value){
                $query->where("employee_name", "like", '%' . $search_value . '%');
            })
            ->get();

            }elseif ($req->from_date && $req->to_date && $req->type && $req->employee_others) {
               

                $data = lockerDetail::with("getEmployee:id,employee_name,employee_post")
                    ->where("purpose", $req->type)
                    ->where("employee_id", $req->employee_others)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->get();

            }elseif ($req->from_date && $req->to_date && $req->type) {
                

                $data = lockerDetail::with("getEmployee:id,employee_name,employee_post")
                    ->where("purpose", $req->type)
                    // ->whereDate("paid_date", ">=", $req->from_date)
                    // ->whereDate("paid_date", "<=", $req->to_date)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->get();

                // $total_count = count($data->get());
            }elseif($req->from_date && $req->to_date) {

            
                $data = lockerDetail::with("getEmployee:id,employee_name,employee_post")
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    // ->offset($req->start)
                    // ->limit(10)
                    ->orderBy("id", "desc")
                    ->get();

                // $total_count = count($data->get());
            } else {
               
                $data = lockerDetail::with("getEmployee:id,employee_name,employee_post")->get();
                
            }

        
            $from = $req->from_date;
            $to = $req->to_date;
            $employee_others = $req->employee_others;
            $type = $req->type;

           

        $sum_of_paid_locker = DB::table('locker_details')
            ->select(DB::raw('sum(amount) as sum'))
            ->whereDate("created_at","<",$from)
            ->get();


        $sum_locker_out_source  = DB::table('locker_amount_out_sources')
            ->select(DB::raw('sum(amount) as sum'))
            ->get();

        $from_foodpanda_amount_locker  = DB::table('foodpanda_amounts')
            ->select(DB::raw('sum(amount) as sum'))
            ->where("account", "Locker")
            ->get();
        

        $pdf = PDF::loadView("admin.get-pdf-report-locker",  compact('from_foodpanda_amount_locker','sum_locker_out_source','data', 'from', 'to','employee_others','type','sadqa_caculate','sum_of_sale_datewise', 'locker','sum_of_paid_locker'));
        $file = $pdf->download('nfc_locker_detail.pdf');

        return response()->json([base64_encode($file)], 200);
        ini_set('memory_limit', '128M');
    }


    
    function getViewLockerAmount(Request $req, $from_date, $to_date, $type=null, $employee_others=null)
    {

      
           $from = $from_date;
           $to = $to_date;
            

           $locker = DB::table('closings')
           ->select(DB::raw('sum(amount) as sum'))
           ->where("head", "9")
           ->whereIn("location",[1,2,3])
           ->get();
   
   
           $sum_of_sale_datewise = DB::table('closings')
               ->select(DB::raw('sum(closings.amount) as sum'))
               ->where("closings.head",2)
               ->groupBy("date")
               ->orderBy("closings.head","desc")->get();
   
           $sadqa_caculate = 0;
   
           foreach( $sum_of_sale_datewise  as $sale_sum){
               $sadqa_caculate = $sadqa_caculate + ($sale_sum->sum > 0 ? ceil((($sale_sum->sum  / 100) * 2) / 10) * 10 : 0);
           }


            if ($from_date && $to_date && $type && $employee_others) {
               

                $data = lockerDetail::with("getEmployee:id,employee_name,employee_post")
                    ->where("purpose", $type)
                    ->where("employee_id", $employee_others)
                    ->whereDate("created_at", ">=", $from_date)
                    ->whereDate("created_at", "<=", $to_date)
                    ->get();

            }elseif ($from_date && $to_date && $type) {
                

                $data = lockerDetail::with("getEmployee:id,employee_name,employee_post")
                    ->where("purpose", $req->type)
                    ->whereDate("created_at", ">=", $from_date)
                    ->whereDate("created_at", "<=", $to_date)
                    ->get();

                // $total_count = count($data->get());
            }elseif($from_date && $to_date) {

            
                $data = lockerDetail::with("testClosingAmount")->with("getEmployee:id,employee_name,employee_post")
                    ->whereDate("created_at", ">=", $from_date)
                    ->whereDate("created_at", "<=", $to_date)
                    ->orderBy("id", "desc")
                    ->get();
                // $total_count = count($data->get());
            } else {
               
                $data = lockerDetail::with("testClosingAmount")->with("getEmployee:id,employee_name,employee_post")->get();
                
            }

        
            $from = $from_date;
            $to = $to_date;
            $employee_others = $employee_others;
            $type = $type;

           

        $sum_of_paid_locker = DB::table('locker_details')
            ->select(DB::raw('sum(amount) as sum'))
            ->whereDate("created_at","<",$from)
            ->get();


        $sum_locker_out_source  = DB::table('locker_amount_out_sources')
            ->select(DB::raw('sum(amount) as sum'))
            ->get();

        $check_out_source_amt = LockerAmountOutSource::whereDate("created_at", ">=", $from)
        ->whereDate("created_at", "<=", $to)
        ->get();

        $from_foodpanda_amount_locker  = DB::table('foodpanda_amounts')
            ->select(DB::raw('sum(amount) as sum'))
            ->where("account", "Locker")
            ->get();

        
        
        $html = [];
        $html["title"] = "Locker Grand Report View";
        $html["view"] = view("admin.get-view-locker-amount", compact('check_out_source_amt','from_foodpanda_amount_locker','sum_locker_out_source','data', 'from', 'to','employee_others','type','sadqa_caculate','sum_of_sale_datewise', 'locker','sum_of_paid_locker'))->render();
        return response()->json($html, 200);

        

        // $pdf = PDF::loadView("admin.get-pdf-report-locker",  compact('from_foodpanda_amount_locker','sum_locker_out_source','data', 'from', 'to','employee_others','type','sadqa_caculate','sum_of_sale_datewise', 'locker','sum_of_paid_locker'));
        // $file = $pdf->download('nfc_locker_detail.pdf');

        // return response()->json([base64_encode($file)], 200);
     
    }



    // function getViewLockerAmount(Request $req){



    //     $from = $req->from_date;
    //     $to = $req->to_date;
         

    //     $locker = DB::table('closings')
    //     ->select(DB::raw('sum(amount) as sum'))
    //     ->where("head", "9")
    //     ->whereIn("location",[1,2,3])
    //     ->get();


    //     $sum_of_sale_datewise = DB::table('closings')
    //         ->select(DB::raw('sum(closings.amount) as sum'))
    //         ->where("closings.head",2)
    //         ->groupBy("date")
    //         ->orderBy("closings.head","desc")->get();

    //     $sadqa_caculate = 0;

    //     foreach( $sum_of_sale_datewise  as $sale_sum){
    //         $sadqa_caculate = $sadqa_caculate + ($sale_sum->sum > 0 ? ceil((($sale_sum->sum  / 100) * 2) / 10) * 10 : 0);
    //     }

     

    //     if($req->search_value){

    //      $search_value = $req->search_value;
         
    //      $data = lockerDetail::with("getEmployee:id,employee_name,employee_post")
    //      ->with("getEmployee:id,employee_name,employee_post")
    //      ->whereHas('getEmployee', function ($query)use($search_value){
    //          $query->where("employee_name", "like", '%' . $search_value . '%');
    //      })
    //      ->get();

    //      }elseif ($req->from_date && $req->to_date && $req->type && $req->employee_others) {
            

    //          $data = lockerDetail::with("getEmployee:id,employee_name,employee_post")
    //              ->where("purpose", $req->type)
    //              ->where("employee_id", $req->employee_others)
    //              ->whereDate("created_at", ">=", $req->from_date)
    //              ->whereDate("created_at", "<=", $req->to_date)
    //              ->get();

    //      }elseif ($req->from_date && $req->to_date && $req->type) {
             

    //          $data = lockerDetail::with("getEmployee:id,employee_name,employee_post")
    //              ->where("purpose", $req->type)
    //              // ->whereDate("paid_date", ">=", $req->from_date)
    //              // ->whereDate("paid_date", "<=", $req->to_date)
    //              ->whereDate("created_at", ">=", $req->from_date)
    //              ->whereDate("created_at", "<=", $req->to_date)
    //              ->get();

    //          // $total_count = count($data->get());
    //      }elseif($req->from_date && $req->to_date) {

         
    //          $data = lockerDetail::with("getEmployee:id,employee_name,employee_post")
    //              ->whereDate("created_at", ">=", $req->from_date)
    //              ->whereDate("created_at", "<=", $req->to_date)
    //              // ->offset($req->start)
    //              // ->limit(10)
    //              ->orderBy("id", "desc")
    //              ->get();

    //          // $total_count = count($data->get());
    //      } else {
            
    //          $data = lockerDetail::with("getEmployee:id,employee_name,employee_post")->get();
             
    //      }

     
    //      $from = $req->from_date;
    //      $to = $req->to_date;
    //      $employee_others = $req->employee_others;
    //      $type = $req->type;

        

    //  $sum_of_paid_locker = DB::table('locker_details')
    //      ->select(DB::raw('sum(amount) as sum'))
    //      ->whereDate("created_at","<",$from)
    //      ->get();


    //  $sum_locker_out_source  = DB::table('locker_amount_out_sources')
    //      ->select(DB::raw('sum(amount) as sum'))
    //      ->get();

    //  $from_foodpanda_amount_locker  = DB::table('foodpanda_amounts')
    //      ->select(DB::raw('sum(amount) as sum'))
    //      ->where("account", "Locker")
    //      ->get();
     


        
    
            
    //     //    $locker = DB::table('closings')
    //     //    ->select(DB::raw('sum(amount) as sum'))
    //     //    ->where("head", "9")
    //     //    ->whereIn("location",[1,2,3])
    //     //    ->get();
   
   
    //     //    $sum_of_sale_datewise = DB::table('closings')
    //     //        ->select(DB::raw('sum(closings.amount) as sum'))
    //     //        ->where("closings.head",2)
    //     //        ->groupBy("date")
    //     //        ->orderBy("closings.head","desc")->get();
   
    //     //    $sadqa_caculate = 0;
   
    //     //    foreach( $sum_of_sale_datewise  as $sale_sum){
    //     //        $sadqa_caculate = $sadqa_caculate + ($sale_sum->sum > 0 ? ceil((($sale_sum->sum  / 100) * 2) / 10) * 10 : 0);
    //     //    }

        

    //     //  if ($from_date && $to_date) {
        
    //     //         $data = lockerDetail::with("getEmployee:id,employee_name,employee_post")
            
    //     //             ->whereDate("created_at", ">=",$from_date)
    //     //             ->whereDate("created_at", "<=", $to_date)
    //     //             ->get();
               
    //     //     }

        
    //     //     $from = $from_date;
    //     //     $to = $to_date;
    //     //     // $employee_others = $req->employee_others;
    //     //     // $type = $req->type;

           

    //     // $sum_of_paid_locker = DB::table('locker_details')
    //     //     ->select(DB::raw('sum(amount) as sum'))
    //     //     ->whereDate("created_at","<",$from)
    //     //     ->get();


    //     // $sum_locker_out_source  = DB::table('locker_amount_out_sources')
    //     //     ->select(DB::raw('sum(amount) as sum'))
    //     //     ->get();

    //     // $from_foodpanda_amount_locker  = DB::table('foodpanda_amounts')
    //     //     ->select(DB::raw('sum(amount) as sum'))
    //     //     ->where("account", "Locker")
    //     //     ->get();
        
    
           
    //     //         $html = [];
    //     //         $html["title"] = "Locker Grand Report";
    //     //         $html["view"] = view("admin.get-view-locker-amount", compact('from_foodpanda_amount_locker','sum_locker_out_source','data', 'from', 'to','sadqa_caculate','sum_of_sale_datewise', 'locker','sum_of_paid_locker'))->render();
    //     //         return response()->json($html, 200);
            

    //     // $pdf = PDF::loadView("admin.get-view-locker-amount",  compact('from_foodpanda_amount_locker','sum_locker_out_source','data', 'from', 'to','sadqa_caculate','sum_of_sale_datewise', 'locker','sum_of_paid_locker'));
    //     // $file = $pdf->download('nfc_locker_detail.pdf');
        
        
       
    // }



    function lockerAmount(Request $req){
      return view("admin.locker-amount-form");
    }


    function insertLockAmount(Request $req){

        if($req->ajax()){

            if($req->hidden_id){
                $amount = LockerAmountOutSource::find($req->hidden_id);
            }else{
                $amount = new  LockerAmountOutSource();
            }
           
           $amount->amount = $req->amount;
           $amount->remarks = $req->remarks;
           $amount->save();
        }

    }


    function getLockerAddAmount(Request $req){


        if ($req->ajax()) {


                if($req->from_date && $req->to_date){

                    $data = LockerAmountOutSource::whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");

                    $count_locker = DB::table('locker_amount_out_sources')
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)    
                    ->count();

                }else{

                    $data = LockerAmountOutSource::offset($req->start)->limit(10)->orderBy("id", "desc");
                    $count_locker = DB::table('locker_amount_out_sources')->count();

                }
            
               
           

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return date_format(date_create($row->created_at), "d-m-Y");
                })
                ->addColumn('amount', function ($row) {
                    return $row->amount;
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks ? $row->remarks : "-";
                })

                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group btn-sm">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    </button>
                    <div class="dropdown-menu">
                    <a href="javascript:void(0)" class="dropdown-item edit_locker_amount"  data-id="' . $row->id . '">Edit</a>
                  
                    </div>
                    </div>';
                    return $btn;
                })
                ->setFilteredRecords($count_locker)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }

    }


    function editLockerAmountNew(Request $req){
            
        $lockerAmountOutsource =  LockerAmountOutSource::find($req->id);
        return response()->json([$lockerAmountOutsource],200);


    }


    function getVendors(Request $req)
    {

        $data = Employee::where("employee_type", "Others")->where("employee_branch", $req->branch)->get();
        return response()->json([$data]);
    }

    function vendorForm()
    {

        $vendors = Employee::where("employee_type", "Vendors")->get();
        $branches = branches();
        return view("admin.vendor-form", compact("branches","vendors"));
    }


    function insertVendor(Request $req)
    {

        if ($req->ajax()) {

            $validation = [
                // "product_id" => "required",
                "employee_id" => "required",
                // "location" => "required",
                // "weight" => "required",
                // "measurement" => "required",
                // "rate" => "required",
                "amount" => "required",
            ];

            $validator = Validator::make($req->all(), $validation);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 400);
            }

            if($req->hidden_id){
                $vendor = Vendor::find($req->hidden_id);
            }else{
                $vendor = new Vendor();
            }
           
            $vendor->employee_id = $req->employee_id;
            $vendor->product_id = $req->product_id;
            $vendor->branch_id = 1;
            $vendor->weight = $req->weight;
            $vendor->measurement = $req->measurement;
            $vendor->rate = $req->rate;
            $vendor->total_amount = $req->amount;
            $vendor->save();
            return response()->json("saved", 200);
            
        }
    }


    function getVendorList(Request $req)
    {


        if ($req->ajax()) {




            if($req->search_value && $req->from_date && $req->to_date){

                $search_value = $req->search_value;

                $count_data = Vendor::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();

                $data = Vendor::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)->limit(10)->orderBy("id", "desc");


            }elseif($req->search_value){

                $search_value = $req->search_value;

                $count_data = Vendor::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->count();

                $data = Vendor::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                
                ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->offset($req->start)->limit(10)->orderBy("id", "desc");



            }elseif ($req->from_date && $req->to_date  && $req->vendors && $req->product_id) {

                $count_data = DB::table('vendors')
                    ->where("employee_id", $req->vendors)
                    ->where("product_id", $req->product_id)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->count();

                $data = Vendor::with("getEmployee:id,employee_name,employee_post")
                    ->with("getBranch:id,location")
                    ->where("employee_id", $req->vendors)
                    ->where("product_id", $req->product_id)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");
               

            }elseif ($req->from_date && $req->to_date && $req->product_id) {

                $count_data = DB::table('vendors')
                    ->where("product_id", $req->product_id)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->count();

                $data = Vendor::with("getEmployee:id,employee_name,employee_post")
                    ->with("getBranch:id,location")
                    
                    ->where("product_id", $req->product_id)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");
            }elseif ($req->from_date && $req->to_date  && $req->vendors) {

                $count_data = DB::table('vendors')
                    ->where("employee_id", $req->vendors)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->count();

                $data = Vendor::with("getEmployee:id,employee_name,employee_post")
                    ->with("getBranch:id,location")
                    
                    ->where("employee_id", $req->vendors)
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");
               

            }elseif($req->from_date && $req->to_date){

                $count_data = DB::table('vendors')
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->count();

                $data = Vendor::with("getEmployee:id,employee_name,employee_post")
                   
                    ->with("getBranch:id,location")
                    ->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->to_date)
                    ->offset($req->start)->limit(10)->orderBy("id", "desc");

            }else {
            
               
                $count_data = Vendor::count();
                $data = Vendor::with("getEmployee:id,employee_name,employee_post")->with("getBranch:id,location")->offset($req->start)->limit(10)->orderBy("id", "desc");

            }


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return date_format(date_create($row->created_at), "d-m-Y");
                })
                ->addColumn('employee_id', function ($row) {
                    return $row->getEmployee->employee_name;
                })

            //     ->addColumn('product_id', function ($row) {
            //         return $row->getVendors->employee_name;
            //    })


            //     ->addColumn('weight', function ($row) {
            //         return $row->weight;
            //     })

            //     ->addColumn('measurement', function ($row) {
            //         return $row->measurement;
            //     })

            //     ->addColumn('rate', function ($row) {
            //         return number_format($row->rate);
            //     })

                ->addColumn('total_amount', function ($row) {
                    return number_format($row->total_amount);
                })

                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" ' .(Auth::User()->user_type == "User" ? "disabled" : ""). ' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item  edit-vendor-amount"  data-id="' . $row->id . '">Edit</a>';
                        // $btn .= '<a  href="javascript:void(0)" class="dropdown-item delete-vendor-amount" data-id="' . $row->id . '">Delete</a>';
                        $btn .= '</div>
                        </div>';



                    return $btn;
                })
                ->setFilteredRecords($count_data)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }



    function getVendorsGrandPdfReport(Request $req, $from_date=null, $to_date=null, $vendor=null){


            if ($from_date && $to_date  && $vendor) {
            
                $data = Vendor::with("getEmployee:id,employee_name,employee_post")
                    ->with("getBranch:id,location")
                    ->where("employee_id", $vendor)
                    ->whereDate("created_at", ">=", $from_date)
                    ->whereDate("created_at", "<=", $to_date)
                    ->orderBy("id", "asc")->get();


                $vendors_paid_amounts = DB::table('pay_vendor_amounts')
                    ->select(DB::raw('sum(paid_amount) as vendor_amount'))
                    ->where("employee_id", $vendor)
                    ->get();

            }elseif($from_date && $to_date){

                $data = Vendor::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->whereDate("created_at", ">=", $from_date)
                ->whereDate("created_at", "<=", $to_date)
                ->orderBy("id", "asc")->get();


                $vendors_paid_amounts = DB::table('pay_vendor_amounts')
                    ->select(DB::raw('sum(paid_amount) as vendor_amount'))
                    ->get();
            }

            $html = [];
            $html["title"] = "Vendors Grand Report Datewise";
            $html["view"] = view("admin.get-vendors-grand-pdf-report",  compact("data","vendors_paid_amounts"))->render();
            return response()->json($html, 200);

            // $pdf = PDF::loadView("admin.get-vendors-grand-pdf-report", compact("data","vendors_paid_amounts"));
            // $file = $pdf->download('nfc_vendor_paid_detail.pdf');
            // return response()->json([base64_encode($file)], 200);

    
           // return view("admin.get-vendors-grand-pdf-report", compact("data","pay_vendor_amounts"));
    }



    function editVendorDetail(Request $req){

        $vendor = Vendor::with("getEmployee:id,employee_name")->find($req->id);
        return response()->json($vendor, 200);

    }

    function deleteVendorDetail(Request $req){

        $vendor = Vendor::find($req->id);
        $vendor->delete();
        return response()->json("deleted", 200);

    }

    function payVendorAmount(){

       // $branches = branches();
        $vendors = DB::table('employees')
        ->select(DB::raw('id,employee_name'))
        ->where("employee_type", "Vendors")
        ->get();
        return view("admin.pay-vendor-amount", compact("vendors"));
    }


    function getVendorTotalAmount(Request $req){
       
        $vendors = DB::table('vendors')
        ->select(DB::raw('sum(total_amount) as vendor_amount'))
        ->where("employee_id", $req->vendor_id)
        ->groupBy("employee_id")
        ->get();

        $paid_vendor_amount = DB::table('pay_vendor_amounts')
        ->select(DB::raw('sum(paid_amount) as amount'))
        ->where("employee_id", $req->vendor_id)
        ->groupBy("employee_id")
        ->get();

        

        return response()->json( [ (isset($vendors) ? $vendors : 0 )  ,  (isset($paid_vendor_amount) ? $paid_vendor_amount : 0)]   , 200);
      
    }


    function insertVendorPaidAmount(Request $req){



        if ($req->pay_through == "Easypaisa") {

            if($req->hidden_id){
                $pay_vendor_amount  =  PayVendorAmount::find($req->hidden_id);
                if($pay_vendor_amount->account_name !== $req->pay_through){
                    if($pay_vendor_amount->account_name == "HBL"){

                        $hbl_amount = HblAmounts::find($pay_vendor_amount->account_id);
                        $hbl_amount ->delete();
                        $paid_amount = new EasypaisaPaidAmount();

                    }elseif($pay_vendor_amount->account_name == "Locker"){

                        $locker_amount = lockerDetail::find($pay_vendor_amount->account_id);
                        $locker_amount->delete();
                        $paid_amount = new EasypaisaPaidAmount();
                    }
                }else{
                    $paid_amount = EasypaisaPaidAmount::find($pay_vendor_amount->account_id);
                }
            }else{
                $paid_amount = new EasypaisaPaidAmount();
            }

            if($req->hidden_id){
                $pay_vendor_amount =  PayVendorAmount::find($req->hidden_id);
            }else{
                $pay_vendor_amount = new PayVendorAmount();
            }
            
            $paid_amount->employee_id = $req->employee_id;
            $paid_amount->purpose = "Vendors";
            $paid_amount->paid_for_month_date = date("Y-m-d");
            $paid_amount->status = "Paid";
            $paid_amount->amount = $req->paid_amount;
            $paid_amount->save();
            
            $pay_vendor_amount->employee_id = $req->employee_id;
            $pay_vendor_amount->paid_amount = $req->paid_amount;
            $pay_vendor_amount->account_id =  $paid_amount->id;
            $pay_vendor_amount->account_name = $req->pay_through;
            $pay_vendor_amount->remarks = $req->remarks;
            $pay_vendor_amount->save();

        } elseif ($req->pay_through == "HBL") {

            if($req->hidden_id){

                $pay_vendor_amount  =  PayVendorAmount::find($req->hidden_id);
                if($pay_vendor_amount->account_name !== $req->pay_through){
                    if($pay_vendor_amount->account_name == "Easypaisa"){

                        $hbl_amount = EasypaisaPaidAmount::find($pay_vendor_amount->account_id);
                        $hbl_amount ->delete();
                        $paid_amount = new HblAmounts();

                    }elseif($pay_vendor_amount->account_name == "Locker"){

                        $locker_amount = lockerDetail::find($pay_vendor_amount->account_id);
                        $locker_amount->delete();
                        $paid_amount = new HblAmounts();
                    }
                }else{
                    $paid_amount = HblAmounts::find($pay_vendor_amount->account_id);
                }
            }else{
                $paid_amount = new HblAmounts();
            }

            if($req->hidden_id){
                $pay_vendor_amount =  PayVendorAmount::find($req->hidden_id);
            }else{
                $pay_vendor_amount = new PayVendorAmount();
            }


            //$paid_amount = new HblAmounts();
            $paid_amount->employee_id = $req->employee_id;
            $paid_amount->purpose = "Vendors";
            $paid_amount->paid_for_month_date =  date("Y-m-d");
            $paid_amount->status = "Paid";
            $paid_amount->amount = $req->paid_amount;
            $paid_amount->save();

          // $pay_vendor_amount = new PayVendorAmount();
            $pay_vendor_amount->employee_id = $req->employee_id;
            $pay_vendor_amount->paid_amount = $req->paid_amount;
            $pay_vendor_amount->account_id =  $paid_amount->id;
            $pay_vendor_amount->account_name = $req->pay_through;
            $pay_vendor_amount->remarks = $req->remarks;
            $pay_vendor_amount->save();

        } elseif ($req->pay_through == "Locker") {

            if($req->hidden_id){

                $pay_vendor_amount  =  PayVendorAmount::find($req->hidden_id);
                if($pay_vendor_amount->account_name !== $req->pay_through){
                    if($pay_vendor_amount->account_name == "Easypaisa"){

                        $hbl_amount = EasypaisaPaidAmount::find($pay_vendor_amount->account_id);
                        $hbl_amount ->delete();
                        $paid_amount = new lockerDetail();

                    }elseif($pay_vendor_amount->account_name == "HBL"){

                        $locker_amount = HblAmounts::find($pay_vendor_amount->account_id);
                        $locker_amount->delete();
                        $paid_amount = new lockerDetail();
                        
                    }
                }else{
                    $paid_amount = lockerDetail::find($pay_vendor_amount->account_id);
                }
            }else{
                $paid_amount = new lockerDetail();
            }

            if($req->hidden_id){
                $pay_vendor_amount =  PayVendorAmount::find($req->hidden_id);
            }else{
                $pay_vendor_amount = new PayVendorAmount();
            }

           

            //$paid_amount = new lockerDetail();
            $paid_amount->employee_id = $req->employee_id;
            $paid_amount->purpose = "Vendors";
            $paid_amount->paid_for_month_date =  date("Y-m-d");
            $paid_amount->status = "Paid";
            $paid_amount->amount = $req->paid_amount;
            $paid_amount->save();


            //$pay_vendor_amount = new PayVendorAmount();
            $pay_vendor_amount->employee_id = $req->employee_id;
            $pay_vendor_amount->paid_amount = $req->paid_amount;
            $pay_vendor_amount->account_id =  $paid_amount->id;
            $pay_vendor_amount->account_name = $req->pay_through;
            $pay_vendor_amount->remarks = $req->remarks;
            $pay_vendor_amount->save();
        }

    }


    function payAmountReportVendor(){

        $vendors = Employee::where("employee_type", "Vendors")->get();

        return view("admin.pay-amount-report", compact("vendors"));
    }


    function getVendorPaidAmountList(Request $req){

    

        if ($req->ajax()) {

            if($req->from_date && $req->to_date && $req->search_value){
                $search_value = $req->search_value;

                $count_data =  PayVendorAmount::with("getEmployee:id,employee_name")
                ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();

                $data = PayVendorAmount::with("getEmployee:id,employee_name")
                ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)->limit(10)->orderBy("id", "desc");

            }elseif($req->from_date && $req->to_date && $req->vendors){
                $search_value = $req->search_value;

                $count_data =  PayVendorAmount::with("getEmployee:id,employee_name")
                ->where("employee_id", $req->vendors)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();

                $data = PayVendorAmount::with("getEmployee:id,employee_name")
                ->where("employee_id", $req->vendors)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)->limit(10)->orderBy("id", "desc");

            }elseif($req->from_date && $req->to_date){

                $search_value = $req->search_value;

                $count_data =  PayVendorAmount::with("getEmployee:id,employee_name")
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();

                $data = PayVendorAmount::with("getEmployee:id,employee_name")
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)->limit(10)->orderBy("id", "desc");


            }elseif($req->vendors){

                $count_data = PayVendorAmount::with("getEmployee:id,employee_name")
                ->where("employee_id", $req->vendors)
                ->count();

                $data = PayVendorAmount::with("getEmployee:id,employee_name")
                ->where("employee_id", $req->vendors)
                ->offset($req->start)->limit(10)->orderBy("id", "desc");
    
            } elseif($req->search_value){

                $search_value = $req->search_value;

                $count_data =  PayVendorAmount::with("getEmployee:id,employee_name")
                ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })->count();

                $data = PayVendorAmount::with("getEmployee:id,employee_name")
                ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })->offset($req->start)->limit(10)->orderBy("id", "desc");

            }else{

                $count_data = PayVendorAmount::count();
                $data = PayVendorAmount::with("getEmployee:id,employee_name,employee_post")->offset($req->start)->limit(10)->orderBy("id", "desc");
    
            }
            
            

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return date_format(date_create($row->created_at), "d-m-Y");
                })
                ->addColumn('employee_id', function ($row) {
                    return $row->getEmployee->employee_name;
                })

                ->addColumn('paid_amount', function ($row) {
                    return number_format($row->paid_amount);
               })

                ->addColumn('account_name', function ($row) {
                     return $row->account_name;
                })

                ->addColumn('Remarks', function ($row) {
                    return $row->remarks;
               })

                
    
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle"  ' .(Auth::User()->user_type == "User" ? "disabled" : ""). ' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">';
                        $btn .='<a href="javascript:void(0)" class="dropdown-item  edit-vendor-pay-amount"  data-id="' . $row->id . '">Edit</a>';
                       // $btn .= '<a  href="javascript:void(0)" class="dropdown-item delete-vendor-amount" data-id="' . $row->id . '">Delete</a>';

                        $btn .= '</div>
                        </div>';
                    return $btn;
                })
                ->setFilteredRecords($count_data)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    function editPayVendorAmount(request $req){

        $amount = PayVendorAmount::find($req->id);

        $get_vendor_name = employee::find($amount->employee_id);

        return response()->json([$amount,$get_vendor_name], 200);
    }


    function payAmountReportVendorPdf(Request $req){

        if($req->from_date && $req->to_date && $req->search_value){
            $search_value = $req->search_value;

            $data = PayVendorAmount::with("getEmployee:id,employee_name")
            ->whereHas('getEmployee', function ($query)use($search_value){
                $query->where("employee_name", "like", '%' . $search_value . '%');
            })
            ->whereDate("created_at", ">=", $req->from_date)
            ->whereDate("created_at", "<=", $req->to_date)
            ->orderBy("id", "asc")->get();

        }elseif($req->from_date && $req->to_date && $req->vendors){
            $search_value = $req->search_value;

            $data = PayVendorAmount::with("getEmployee:id,employee_name")
            ->where("employee_id", $req->vendors)
            ->whereDate("created_at", ">=", $req->from_date)
            ->whereDate("created_at", "<=", $req->to_date)
            ->orderBy("id", "asc")->get();

        }elseif($req->from_date && $req->to_date){

            $search_value = $req->search_value;

            $data = PayVendorAmount::with("getEmployee:id,employee_name")
            ->whereDate("created_at", ">=", $req->from_date)
            ->whereDate("created_at", "<=", $req->to_date)
            ->orderBy("id", "asc")->get();

        }elseif($req->vendors){
            $data = PayVendorAmount::with("getEmployee:id,employee_name")
            ->where("employee_id", $req->vendors)
            ->orderBy("id", "asc")->get();

        }elseif($req->search_value){

            $search_value = $req->search_value;

            $data = PayVendorAmount::with("getEmployee:id,employee_name")
            ->whereHas('getEmployee', function ($query)use($search_value){
                $query->where("employee_name", "like", '%' . $search_value . '%');
            })->orderBy("id", "asc")->get();

        }else{

            $data = PayVendorAmount::with("getEmployee:id,employee_name,employee_post")->get();
        }
        


        $pdf = PDF::loadView("admin.pay-amount-report-vendor-pdf", compact("data"));
        $file = $pdf->download('nfc_vendor_paid_detail.pdf');
        return response()->json([base64_encode($file)], 200);

        //return view("admin.pay-amount-report-vendor-pdf", compact("data"));
    }



    function ViewPayAmountVendorGrandTotalReport(Request $req){

        
        


            $pay_vendor_amounts = DB::table('pay_vendor_amounts')
            ->select(DB::raw('sum(pay_vendor_amounts.paid_amount) as sum, employees.employee_name'))
            ->join('employees', 'employees.id', '=', 'pay_vendor_amounts.employee_id')
            ->groupBy("employees.employee_name")
            ->get();

            $vendor_detail_sum = DB::table('vendors')
            ->select(DB::raw('sum(vendors.total_amount) as sum, employees.employee_name'))
            ->join('employees', 'employees.id', '=', 'vendors.employee_id')
            ->groupBy("employees.employee_name")
            ->get();

       
        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Vendor Paid Grand Report";
            $html["view"] = view("admin.view-grand-report-vendor-paid-amount", compact("pay_vendor_amounts","vendor_detail_sum"))->render();
            return response()->json($html, 200);
        }



    

       // return view("admin.view-grand-report-vendor-paid-amount", compact("pay_vendor_amounts","vendor_detail_sum"));

    }


    



    function getVendorFullList(Request $req)
    {
    
        $vendors = DB::table('employees')
        ->select(DB::raw('id,employee_name'))
        ->where("employee_type", "Vendors")
        ->get();

        $others = DB::table('employees')
        ->select(DB::raw('id,employee_name'))
        ->where("employee_type", "others")
        ->get();

        return view("admin.get-vendor-full-list", compact("vendors", "others"));
    }


    function payNowVendor(Request $req, $id, $amount, $pending_date, $paynow_id)
    {

        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Pay Now";
            $html["view"] = view("admin.pay-now-vendor-form", compact('id', 'amount', 'pending_date', 'paynow_id'))->render();
            return response()->json($html, 200);
        }
    }


    function insertPayNowVendor(Request $req)
    {

        if ($req->pay_through == "Easypaisa") {
            $paid_amount = new EasypaisaPaidAmount();
            // $paid_amount->paid_date = date("Y-m-d");
            $paid_amount->employee_id = $req->employee_id;
            $paid_amount->purpose = "Vendors";
            $paid_amount->paid_for_month_date = $req->pending_date;
            $paid_amount->status = "Paid";
            $paid_amount->amount = $req->amount;
            $paid_amount->save();
            $pending = Vendor::find($req->pay_now_id);
            $pending->status = "Paid";
            $pending->paid_date = date("Y-m-d");
            $pending->account_id = $paid_amount->id;
            $pending->account_name =  $req->pay_through;
            $pending->save();
        } elseif ($req->pay_through == "HBL") {
            $paid_amount = new HblAmounts();
            // $paid_amount->paid_date = date("Y-m-d");
            $paid_amount->employee_id = $req->employee_id;
            $paid_amount->purpose = "Vendors";
            $paid_amount->paid_for_month_date = $req->pending_date;
            $paid_amount->status = "Paid";
            $paid_amount->amount = $req->amount;
            $paid_amount->save();
            $pending = Vendor::find($req->pay_now_id);
            $pending->status = "Paid";
            $pending->paid_date = date("Y-m-d");
            $pending->account_id = $paid_amount->id;
            $pending->account_name =  $req->pay_through;
            $pending->save();
        } elseif ($req->pay_through == "Locker") {
            $paid_amount = new lockerDetail();
            // $paid_amount->paid_date = date("Y-m-d");
            $paid_amount->employee_id = $req->employee_id;
            $paid_amount->purpose = "Vendors";
            $paid_amount->paid_for_month_date = $req->pending_date;
            $paid_amount->status = "Paid";
            $paid_amount->amount = $req->amount;
            $paid_amount->save();
            $pending = Vendor::find($req->pay_now_id);
            $pending->status = "Paid";
            $pending->paid_date = date("Y-m-d");
            $pending->account_id = $paid_amount->id;
            $pending->account_name =  $req->pay_through;
            $pending->save();
        }
    }


    function getReportVendorPdf(Request $req)
    {

    
        if($req->search_value && $req->from_date && $req->to_date){


            $search_value = $req->search_value;

            $data = Vendor::with("getEmployee:id,employee_name,employee_post")
            ->with("getBranch:id,location")
            ->whereHas('getEmployee', function ($query)use($search_value){
                $query->where("employee_name", "like", '%' . $search_value . '%');
            })
            ->whereDate("created_at", ">=", $req->from_date)
            ->whereDate("created_at", "<=", $req->to_date)
            ->orderBy("id", "desc")
            ->get();


        }elseif($req->search_value){

            $search_value = $req->search_value;

           

            $data = Vendor::with("getEmployee:id,employee_name,employee_post")
            ->with("getBranch:id,location")
            ->whereHas('getEmployee', function ($query)use($search_value){
                $query->where("employee_name", "like", '%' . $search_value . '%');
            })
            ->orderBy("id", "desc")
            ->get();



        }elseif ($req->from_date && $req->to_date && $req->type && $req->vendors) {

         

            $data = Vendor::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                // ->where("employee_id",$req->pending_employee_id)
                ->where("status", $req->type)
                ->where("employee_id", $req->vendors)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->orderBy("id", "desc")
                ->get();
           

        }elseif ($req->from_date && $req->to_date  && $req->vendors) {

          

            $data = Vendor::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->where("employee_id", $req->vendors)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->orderBy("id", "desc")
                ->get();
           

        }elseif ($req->from_date && $req->to_date && $req->type) {

            $data = Vendor::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                // ->where("employee_id",$req->pending_employee_id)
                ->where("status", $req->type)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->orderBy("id", "desc")
                ->get();
           

        }elseif($req->from_date && $req->to_date){

           
            $data = Vendor::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->orderBy("id", "desc")
                ->get();

        }else {

            $data = Vendor::with("getEmployee:id,employee_name,employee_post")->with("getBranch:id,location")->orderBy("id", "desc")->get();

            
        }

        

        $from = $req->from_date;
        $to = $req->to_date;

        $pdf = PDF::loadView("admin.get-report-vendor-pdf", compact('data', 'from', 'to'));
        $file = $pdf->download('nfc_vendor.pdf');
        return response()->json([base64_encode($file)], 200);


        //return view("admin.get-report-vendor-pdf", compact('data','from','to'));
    }

    function rideForm()
    {

        $branches = branches();
        $riders = Employee::where("employee_type", "Employee")->where("employee_post", "Rider")->get();   
        $fuel_type = Employee::where("employee_type", "Fuel")->get();  
        return view("admin.ride-form", compact("riders", "branches", "fuel_type"));
    }



    function insertRides(Request $req)
    {

        if ($req->ajax()) {

            $validation = [
                // "location" => "required",
                "employee_id" => "required",
                "rides" => "required",
                "amount" => "required",
                "fuel_type" => "required",
               
            ];

            $validator = Validator::make($req->all(), $validation);


            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()], 400);
            }  

            $employee_detail = explode(",",$req->employee_id);
            $today_date = date("Y-m-d");

            if($req->hidden_id){
            
                $rides = Ride::find($req->hidden_id);

                $rider_old_amount = $rides->amount;
                $fuel_type_id = $rides->fuel_type;
                $past_account_id = $rides->account_id;


                $find_account_id_in_locker = lockerDetail::find($past_account_id);
                $locker_amount_check_already = $find_account_id_in_locker->amount;
                $find_account_id_in_locker->amount = $locker_amount_check_already - $rider_old_amount;   
                $find_account_id_in_locker->save();
                
            
                //find id if exist in between these dates
                //find account_id already exist on this fuel type in between days

                if($fuel_type_id !== $req->fuel_type){

                $exist = DB::table('locker_details')
                ->whereDate("created_at", ">=" , $today_date)->whereDate("created_at", "<=" , $today_date)
                ->where("employee_id", $req->fuel_type)
                ->latest('id')->first();

                if($exist){

                   $finding_id = lockerDetail::find($exist->id);
                   $finding_id->amount =   $finding_id->amount + $req->amount;
                   $finding_id->save();

                   $rides->employee_id =  $employee_detail[0]; 
                   $rides->branch_id = $employee_detail[1]; 
                   $rides->shift = $req->shift;
                   $rides->rides = $req->rides;
                   $rides->amount = $req->amount;
                   $rides->account_id = $finding_id->id;
                   $rides->account_name = "Locker";
                   $rides->fuel_type = $req->fuel_type;
                   $rides->status = "Paid";
                   $rides->save();



                }else{

                    $insert_to_locker = new lockerDetail();
                    $insert_to_locker ->employee_id =  $req->fuel_type;
                    $insert_to_locker ->purpose = "Fuel";
                    $insert_to_locker ->status = "Paid";
                    $insert_to_locker ->amount = $req->amount;
                    $insert_to_locker ->save();

                    $rides->employee_id =  $employee_detail[0]; 
                    $rides->branch_id = $employee_detail[1]; 
                    $rides->shift = $req->shift;
                    $rides->rides = $req->rides;
                    $rides->amount = $req->amount;
                    $rides->account_id = $insert_to_locker->id;
                    $rides->fuel_type = $req->fuel_type;
                    $rides->account_name = "Locker";
                    $rides->status = "Paid";
                    $rides->save();

                }

            }else{

                 //find to remove past amount
               
                 $find_account_id_in_locker_new = lockerDetail::find($find_account_id_in_locker->id);
                 $locker_amount_check_already = $find_account_id_in_locker->amount;
                 $find_account_id_in_locker_new->amount = $locker_amount_check_already + $req->amount;   
                 $find_account_id_in_locker_new->save();


                 $rides->employee_id =  $employee_detail[0]; 
                 $rides->branch_id = $employee_detail[1]; 
                 $rides->shift = $req->shift;
                 $rides->rides = $req->rides;
                 $rides->amount = $req->amount;
                 $rides->account_id = $find_account_id_in_locker_new->id;
                 $rides->fuel_type = $req->fuel_type;
                 $rides->account_name = "Locker";
                 $rides->status = "Paid";
                 $rides->save();



            }
                



            }else{
            $rides = new Ride();
                
            // $rides->employee_id = $req->employee_id;

           
            $check_fuel_type = lockerDetail::where("employee_id", $req->fuel_type)
            ->whereDate("created_at",">=",$today_date)
            ->whereDate("created_at","<=",$today_date)->get();

            $insert_to_locker  = "" ;
            if(count($check_fuel_type)<=0){
                $insert_to_locker = new lockerDetail();
                $insert_to_locker ->employee_id =  $req->fuel_type;
                $insert_to_locker ->purpose = "Fuel";
                $insert_to_locker ->status = "Paid";
                $insert_to_locker ->amount = $req->amount;
                $insert_to_locker ->save();
            }else{
                
                DB::table('locker_details')
                ->where("employee_id", $req->fuel_type)
                ->whereDate("created_at",">=",$today_date)
                ->whereDate("created_at","<=",$today_date)
                ->update(array('amount' => $check_fuel_type[0]->amount  + $req->amount)); 
            }

            //we send employee_location along with employee_id
            
            $rides->employee_id =  $employee_detail[0]; 
            $rides->branch_id = $employee_detail[1]; 
            $rides->shift = $req->shift;
            $rides->rides = $req->rides;
            $rides->amount = $req->amount;
            $rides->fuel_type = $req->fuel_type;

            if($insert_to_locker  !== ""){
               
                $rides->account_id = $insert_to_locker->id;
            }else{
                $rides->account_id =  $check_fuel_type[0]->id;
            }
           

            $rides->account_name = "Locker";
            $rides->status = "Paid";
            $rides->save();

            }


            return response()->json("saved", 200);
        }
    }




    function getRiders(Request $req)
    {

        // $data = Employee::where("employee_type", "Employee")->where("employee_branch", $req->branch)->get();
        $data = Employee::with("getEmployeeBranch:id,location")->where("employee_type", "Employee")->where("employee_status", "On")->get();
        return response()->json([$data], 200);

    }






    function getListRiders(Request $req)
    {
        if ($req->ajax()) {

            if($req->from_date == "" && $req->shift == "" ){
                $checked = "";
            }else{
                $checked = "checked_all";
            }
           

            if($req->rider && $req->shift && $req->from_date && $req->to_date){

                $count_data =  Ride::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->where("employee_id", $req->rider)
                ->where("shift", $req->shift)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();

                $data = Ride::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->where("employee_id", $req->rider)
                ->where("shift", $req->shift)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)->limit(10)->orderBy("id", "desc");
               

            }elseif($req->shift && $req->from_date && $req->to_date){

                $count_data =  Ride::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->where("shift", $req->shift)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();

                $data = Ride::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->where("shift", $req->shift)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)->limit(10)->orderBy("id", "desc");

            }elseif($req->search_value && $req->from_date && $req->to_date){

                $search_value = $req->search_value;

                $count_data = Ride::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();


                $data = Ride::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)->limit(10)->orderBy("id", "desc");


            }elseif($req->from_date && $req->shift){

                $count_data =  Ride::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->where("shift", $req->shift)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->from_date)
                ->count();

                $data = Ride::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->where("shift", $req->shift)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->from_date)
                ->offset($req->start)->limit(10)->orderBy("id", "desc");

            }elseif($req->search_value){

                $search_value = $req->search_value;

                $count_data = Ride::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->count();


                $data = Ride::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->whereHas('getEmployee', function ($query)use($search_value){
                    $query->where("employee_name", "like", '%' . $search_value . '%');
                })
                ->offset($req->start)->limit(10)->orderBy("id", "desc");

            }elseif($req->rider && $req->from_date && $req->to_date){
                $count_data =  Ride::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->where("employee_id", $req->rider)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();

                $data = Ride::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->where("employee_id", $req->rider)
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)->limit(10)->orderBy("id", "desc");
               

            }elseif($req->rider && $req->shift){

                $count_data =  Ride::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->where("employee_id", $req->rider)
                ->where("shift", $req->shift)
                ->count();

                $data = Ride::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->where("employee_id", $req->rider)
                ->where("shift", $req->shift)
                ->offset($req->start)->limit(10)->orderBy("id", "desc");
               

            }elseif($req->rider){

                $count_data =  Ride::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->where("employee_id", $req->rider)
                ->count();

                $data = Ride::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->where("employee_id", $req->rider)
                ->offset($req->start)->limit(10)->orderBy("id", "desc");
               

            }elseif($req->shift){
                $count_data =  Ride::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->where("shift", $req->shift)
                ->count();

                $data = Ride::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location")
                ->where("shift", $req->shift)
                ->offset($req->start)->limit(10)->orderBy("id", "desc");
               

            }elseif($req->from_date && $req->to_date) {

                $count_data = Ride::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location") 
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)->limit(10)->orderBy("id", "desc")
                ->count();

                $data = Ride::with("getEmployee:id,employee_name,employee_post")
                ->with("getBranch:id,location") 
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)->limit(10)->orderBy("id", "desc");
                
            }else{

                $count_data = Ride::count();

                $data = Ride::with("getEmployee:id,employee_name,employee_post")->with("getBranch:id,location")->offset($req->start)->limit(10)->orderBy("id", "desc");
              
            }


            return Datatables::of($data)
                ->addIndexColumn()

                ->addColumn('checkbox', function ($row) use ($checked) {

                    // if($row->status == "Unpaid"){

                        if($checked == "checked_all"){
                            return  "<input type='checkbox' class='check_riders' name='rider_id' disabled  checked value=".$row->amount.','.date_format(date_create($row->created_at),'Y-m-d').','.$row->employee_id.','.$row->id.">"; 
                        }else{
                            return  "<input type='checkbox' class='check_riders' name='rider_id' disabled  value=".$row->amount.','.date_format(date_create($row->created_at),'Y-m-d').','.$row->employee_id.','.$row->id.">"; 
                        }
                       
                        
                        // }
                    
                    // else{
                    //     return  "<input type='checkbox' class='check_riders' name='rider_id' value=".$row->id." disabled>"; 
                    // }
                    
                })

                ->addColumn('date', function ($row) {
                    return date_format(date_create($row->created_at), "d-m-Y");
                })
                ->addColumn('employee_id', function ($row) {
                    return $row->getEmployee->employee_name."-".$row->getEmployee->employee_post;
                })

                ->addColumn('location', function ($row) {
                    return $row->getBranch->location;
                })

                ->addColumn('shift', function ($row) {
                    return $row->getShift->location;
                })

                ->addColumn('rides', function ($row) {
                    return $row->rides;
                })

                ->addColumn('amount', function ($row) {
                    return number_format($row->amount);
                })

                ->addColumn('status', function ($row) {
                    return $row->status;
                })

               
                ->addColumn('action', function ($row) {

                    // if ($row->status == "Unpaid") {
                        $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" ' .(Auth::User()->user_type == "User" ? "disabled" : ""). ' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">
                         <a href="javascript:void(0)" class="dropdown-item  edit-rider-amount"  data-id="' . $row->id . '">Edit</a>';
                       // $btn .= '<a href="javascript:void(0)" class="dropdown-item  pay-rider-amount"  data-id="'.$row->amount.",".date_format(date_create($row->created_at),"Y-m-d").",".$row->employee_id.",".$row->id.'">Pay Now</a>';
                        $btn .= '</div>
                        </div>';
                    
                    // }
                    
                    // else{
                    //     $btn = '<div class="btn-group btn-sm">
                    //     <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                    //     Action
                    //     </button>
                    //     <div class="dropdown-menu">
                    //     <a href="javascript:void(0)" class="dropdown-item  edit-rider-amount"  data-id="' . $row->id . '">Edit</a>';
                    //    // $btn .= '<a href="javascript:void(0)" class="dropdown-item  pay-rider-amount"  data-id="'.$row->amount.",".date_format(date_create($row->created_at),"Y-m-d").",".$row->employee_id.",".$row->id.'">Pay Now</a>';
                    //     $btn .= '</div>
                    //     </div>';
                    // }



                    return $btn;
                })
                ->setFilteredRecords($count_data)
                ->setTotalRecords($data->count())
                ->rawColumns(['action','checkbox'])
                ->make(true);
        }
    }


    function payNowBulkRides(Request $req, $bulk_array){

                // Split the string by commas
                $parts = explode(',', $bulk_array);

                // Initialize an empty array to store the grouped values
                $result = [];

                // Loop through the parts array and group them after every fourth comma
                $group = [];
                foreach ($parts as $index => $value) {
                    // Add the current value to the group
                    $group[] = $value;

                    // Check if the current index is divisible by 4 or if it's the last element
                    if (($index + 1) % 4 === 0 || $index === count($parts) - 1) {
                        // Add the group to the result array
                        $result[] = $group;

                        // Reset the group array
                        $group = [];
                    }
                }

        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Bulk Pay Now";
            $html["view"] = view("admin.pay-now-bulk-rides", compact('result'))->render();
            return response()->json($html, 200);
        }
    }


    function insertBulkPayNowRides(Request $req){

        $array = $req->get_data;

        // return  $array;


        if ($req->pay_through == "Easypaisa") {

            foreach($array as $get_data){
                $paid_amount = new EasypaisaPaidAmount();
                $paid_amount->employee_id = $get_data[2];
                $paid_amount->purpose = "Rides";
                $paid_amount->paid_for_month_date = $get_data[1];
                $paid_amount->status = "Paid";
                $paid_amount->amount = $get_data[0];
                $paid_amount->save();
                $pending = Ride::find($get_data[3]);
                $pending->status = "Paid";
                $pending->paid_date = date("Y-m-d");
                $pending->account_id = $paid_amount->id;
                $pending->account_name =  $req->pay_through;
                $pending->save();

            }

        
        } elseif ($req->pay_through == "HBL") {


            foreach($array as $get_data){
                $paid_amount = new HblAmounts();
                $paid_amount->employee_id = $get_data[2];
                $paid_amount->purpose = "Rides";
                $paid_amount->paid_for_month_date = $get_data[1];
                $paid_amount->status = "Paid";
                $paid_amount->amount = $get_data[0];
                $paid_amount->save();
                $pending = Ride::find($get_data[3]);
                $pending->status = "Paid";
                $pending->paid_date = date("Y-m-d");
                $pending->account_id = $paid_amount->id;
                $pending->account_name =  $req->pay_through;
                $pending->save();

            }

           

        } elseif ($req->pay_through == "Locker") {


            foreach($array as $get_data){
                $paid_amount = new lockerDetail();
                $paid_amount->employee_id = $get_data[2];
                $paid_amount->purpose = "Rides";
                $paid_amount->paid_for_month_date = $get_data[1];
                $paid_amount->status = "Paid";
                $paid_amount->amount = $get_data[0];
                $paid_amount->save();
                $pending = Ride::find($get_data[3]);
                $pending->status = "Paid";
                $pending->paid_date = date("Y-m-d");
                $pending->account_id = $paid_amount->id;
                $pending->account_name =  $req->pay_through;
                $pending->save();

            }


        }



    }

    


    function editRiderDetail(Request $req){

        $ride = Ride::find($req->id);
        return response()->json($ride,200);
    }


    function getRidesPdf(Request $req){


        // if($req->search_value && $req->from_date && $req->to_date){

        //     $search_value = $req->search_value;

        //     $data = Ride::with("getEmployee:id,employee_name,employee_post")
        //     ->with("getBranch:id,location")
        //     ->whereHas('getEmployee', function ($query)use($search_value){
        //         $query->where("employee_name", "like", '%' . $search_value . '%');
        //     })
        //     ->whereDate("created_at", ">=", $req->from_date)
        //     ->whereDate("created_at", "<=", $req->to_date)
        //     ->orderBy("shift", "asc")
        //     ->get();


        // }elseif($req->search_value){

        //     $search_value = $req->search_value;

        //     $data = Ride::with("getEmployee:id,employee_name,employee_post")
        //     ->with("getBranch:id,location")
        //     ->whereHas('getEmployee', function ($query)use($search_value){
        //         $query->where("employee_name", "like", '%' . $search_value . '%');
        //     })
        //     ->orderBy("shift", "asc")
        //     ->get();



        // }elseif($req->from_date && $req->to_date && $req->status &&  $req->rider && $req->shift){



          

        //     $data = Ride::with("getEmployee:id,employee_name,employee_post")
        //     ->with("getBranch:id,location")
        //     ->where("employee_id", $req->rider)
        //     ->where("status", $req->status)
        //     ->where("shift", $req->shift)
        //     ->whereDate("created_at", ">=", $req->from_date)
        //     ->whereDate("created_at", "<=", $req->to_date)
        //     ->orderBy("shift", "asc")
        //     ->get();
           

        // }

        // elseif($req->from_date && $req->to_date && $req->status &&  $req->shift){



          

        //     $data = Ride::with("getEmployee:id,employee_name,employee_post")
        //     ->with("getBranch:id,location")
        //     ->where("status", $req->status)
        //     ->where("shift", $req->shift)
        //     ->whereDate("created_at", ">=", $req->from_date)
        //     ->whereDate("created_at", "<=", $req->to_date)
        //     ->orderBy("shift", "asc")
        //     ->get();
           

        // }elseif($req->from_date && $req->to_date &&  $req->shift){


        //     $data = Ride::with("getEmployee:id,employee_name,employee_post")
        //     ->with("getBranch:id,location")
        //     ->where("shift", $req->shift)
        //     ->whereDate("created_at", ">=", $req->from_date)
        //     ->whereDate("created_at", "<=", $req->to_date)
        //     ->orderBy("shift", "asc")
        //     ->get();
           

        // } elseif($req->rider && $req->shift){



           

        //     $data = Ride::with("getEmployee:id,employee_name,employee_post")
        //     ->with("getBranch:id,location")
        //     ->where("employee_id", $req->rider)
        //     ->where("shift", $req->shift)
        //     ->orderBy("shift", "asc")
        //     ->get();
           

        // }elseif($req->shift){
           

        //     $data = Ride::with("getEmployee:id,employee_name,employee_post")
        //     ->with("getBranch:id,location")
        //     ->where("shift", $req->shift)
        //     ->orderBy("shift", "asc")
        //     ->get();
           

        // }elseif($req->from_date && $req->to_date && $req->status &&  $req->rider){

        //     $data = Ride::with("getEmployee:id,employee_name,employee_post")
        //     ->with("getBranch:id,location")
        //     ->where("employee_id", $req->rider)
        //     ->where("status", $req->status)
        //     ->whereDate("created_at", ">=", $req->from_date)
        //     ->whereDate("created_at", "<=", $req->to_date)
        //     ->orderBy("shift", "asc")
        //     ->get();
           

        // }elseif($req->from_date && $req->to_date && $req->rider){

            
        //     $data = Ride::with("getEmployee:id,employee_name,employee_post")
        //     ->with("getBranch:id,location")
        //     ->where("employee_id", $req->rider)
        //     ->whereDate("created_at", ">=", $req->from_date)
        //     ->whereDate("created_at", "<=", $req->to_date)
        //     ->orderBy("shift", "asc")
        //     ->get();
           
            
        // }elseif ($req->from_date && $req->to_date && $req->status) {

        //     $data = Ride::with("getEmployee:id,employee_name,employee_post")
        //         ->with("getBranch:id,location")
        //         ->where("status", $req->status)
        //         ->whereDate("created_at", ">=", $req->from_date)
        //         ->whereDate("created_at", "<=", $req->to_date)
        //         ->orderBy("shift", "asc")
        //          ->get();
                


        // } elseif($req->from_date && $req->to_date) {

        //     $data = Ride::with("getEmployee:id,employee_name,employee_post")
        //     ->with("getBranch:id,location") 
        //     ->whereDate("created_at", ">=", $req->from_date)
        //     ->whereDate("created_at", "<=", $req->to_date)
        //     ->orderBy("shift", "asc")
        //     ->get();

        // }else{

            
        //     $data = Ride::with("getEmployee:id,employee_name,employee_post")->with("getBranch:id,location")
        //     ->orderBy("shift", "asc")
        //     ->get();
          
        // }
        

        
        if($req->rider && $req->shift && $req->from_date && $req->to_date){

            $data = Ride::with("getEmployee:id,employee_name,employee_post")
            ->with("getBranch:id,location")
            ->where("employee_id", $req->rider)
            ->where("shift", $req->shift)
            ->whereDate("created_at", ">=", $req->from_date)
            ->whereDate("created_at", "<=", $req->to_date)
             ->orderBy("id", "desc")->get();
           

        }elseif($req->shift && $req->from_date && $req->to_date){

           

            $data = Ride::with("getEmployee:id,employee_name,employee_post")
            ->with("getBranch:id,location")
            ->where("shift", $req->shift)
            ->whereDate("created_at", ">=", $req->from_date)
            ->whereDate("created_at", "<=", $req->to_date)
            ->orderBy("id", "desc")->get();


        }elseif($req->search_value && $req->from_date && $req->to_date){

            $search_value = $req->search_value;

            $data = Ride::with("getEmployee:id,employee_name,employee_post")
            ->with("getBranch:id,location")
            ->whereHas('getEmployee', function ($query)use($search_value){
                $query->where("employee_name", "like", '%' . $search_value . '%');
            })
            ->whereDate("created_at", ">=", $req->from_date)
            ->whereDate("created_at", "<=", $req->to_date)
            ->orderBy("id", "desc")->get();


        }elseif($req->search_value){

            $search_value = $req->search_value;

           

            $data = Ride::with("getEmployee:id,employee_name,employee_post")
            ->with("getBranch:id,location")
            ->whereHas('getEmployee', function ($query)use($search_value){
                $query->where("employee_name", "like", '%' . $search_value . '%');
            })
            ->orderBy("id", "desc")->get();

        }elseif($req->rider && $req->from_date && $req->to_date){

            $data = Ride::with("getEmployee:id,employee_name,employee_post")
            ->with("getBranch:id,location")
            ->where("employee_id", $req->rider)
            ->whereDate("created_at", ">=", $req->from_date)
            ->whereDate("created_at", "<=", $req->to_date)
            ->orderBy("id", "desc")->get();
           

        }elseif($req->rider && $req->shift){

           
            $data = Ride::with("getEmployee:id,employee_name,employee_post")
            ->with("getBranch:id,location")
            ->where("employee_id", $req->rider)
            ->where("shift", $req->shift)
            ->orderBy("id", "desc")->get();
           

        }elseif($req->rider){

           
            $data = Ride::with("getEmployee:id,employee_name,employee_post")
            ->with("getBranch:id,location")
            ->where("employee_id", $req->rider)
            ->orderBy("id", "desc")->get();
           

        }elseif($req->shift){
        
            $data = Ride::with("getEmployee:id,employee_name,employee_post")
            ->with("getBranch:id,location")
            ->where("shift", $req->shift)
            ->orderBy("id", "desc")->get();
           
        }elseif($req->from_date && $req->to_date) {

            $data = Ride::with("getEmployee:id,employee_name,employee_post")
            ->with("getBranch:id,location") 
            ->whereDate("created_at", ">=", $req->from_date)
            ->whereDate("created_at", "<=", $req->to_date)
            ->orderBy("id", "desc")->get();
            
        }else{

            $data = Ride::with("getEmployee:id,employee_name,employee_post")->with("getBranch:id,location")
            ->orderBy("id", "desc")->get();
          
        }



        
        $pdf = PDF::loadView("admin.get-rides-pdf", compact("data"));
        $file = $pdf->download('nfc_vendor.pdf');
        return response()->json([base64_encode($file)], 200);

        //return view("admin.get-rides-pdf", compact("data"));
    }


    function getRidersListView(Request $req, $from_date, $to_date, $shift=null){

      
        if($shift && $from_date && $to_date){

          $data = Ride::with("getEmployee:id,employee_name,employee_post")
            ->with("getBranch:id,location")
            ->where("shift", $shift)
            ->whereDate("created_at", ">=", $from_date)
            ->whereDate("created_at", "<=", $to_date)
            ->orderBy("id", "desc")->get();
           
        }elseif($from_date && $to_date) {

            $data = Ride::with("getEmployee:id,employee_name,employee_post")
            ->with("getBranch:id,location") 
            ->whereDate("created_at", ">=", $from_date)
            ->whereDate("created_at", "<=", $to_date)
            ->orderBy("id", "desc")->get();
            
        }



        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Rider List";
            $html["view"] = view("admin.get-rides-list-view", compact('data'))->render();
            return response()->json($html, 200);

        }



    }




    function payNowRides(Request $req, $amount, $ride_date, $employee_id , $paynow_id)
    {

        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Pay Now";
            $html["view"] = view("admin.pay-now-rides", compact('employee_id', 'amount', 'ride_date', 'paynow_id'))->render();
            return response()->json($html, 200);
        }
    }


    function insertPayNowRides(Request $req)
    {
        if ($req->pay_through == "Easypaisa") {
            $paid_amount = new EasypaisaPaidAmount();
            // $paid_amount->paid_date = date("Y-m-d");
            $paid_amount->employee_id = $req->employee_id;
            $paid_amount->purpose = "Rides";
            $paid_amount->paid_for_month_date = $req->pending_date;
            $paid_amount->status = "Paid";
            $paid_amount->amount = $req->amount;
            $paid_amount->save();
            $pending = Ride::find($req->pay_now_id);
            $pending->status = "Paid";
            $pending->paid_date = date("Y-m-d");
            $pending->account_id = $paid_amount->id;
            $pending->account_name =  $req->pay_through;
            $pending->save();
        } elseif ($req->pay_through == "HBL") {
            $paid_amount = new HblAmounts();
            // $paid_amount->paid_date = date("Y-m-d");
            $paid_amount->employee_id = $req->employee_id;
            $paid_amount->purpose = "Rides";
            $paid_amount->paid_for_month_date = $req->pending_date;
            $paid_amount->status = "Paid";
            $paid_amount->amount = $req->amount;
            $paid_amount->save();
            $pending = Ride::find($req->pay_now_id);
            $pending->status = "Paid";
            $pending->paid_date = date("Y-m-d");
            $pending->account_id = $paid_amount->id;
            $pending->account_name =  $req->pay_through;
            $pending->save();
        } elseif ($req->pay_through == "Locker") {
            $paid_amount = new lockerDetail();
            // $paid_amount->paid_date = date("Y-m-d");
            $paid_amount->employee_id = $req->employee_id;
            $paid_amount->purpose = "Rides";
            $paid_amount->paid_for_month_date = $req->pending_date;
            $paid_amount->status = "Paid";
            $paid_amount->amount = $req->amount;
            $paid_amount->save();
            $pending = Ride::find($req->pay_now_id);
            $pending->status = "Paid";
            $pending->paid_date = date("Y-m-d");
            $pending->account_id = $paid_amount->id;
            $pending->account_name =  $req->pay_through;
            $pending->save();
        }
    }

    function getRidesFullList(Request $req){


        $riders = Employee::where("employee_type", "Employee")->where("employee_post","Rider")->get();
        $shift = branches();
        return view("admin.get-rides-full-list",compact("riders", "shift"));

        // if ($req->ajax()) {
        //     $html = [];
        //     $html["title"] = "Riders List";
        //     $riders = Employee::where("employee_type", "Employee")->where("employee_post","Rider")->get();
        //     $html["view"] = view("admin.get-rides-full-list",compact("riders"))->render();
        //     return response()->json($html, 200);
        // }


    }

    function dailyClosingGrandReport(Request $req){


       

        // $closing  = DB::table('closings')
        //     ->select(DB::raw('closings.date,closings.amount as sum, heads.head, head_locations.location'))
        //     ->join('heads', 'heads.id', '=', 'closings.head')
        //     ->join('head_locations', 'head_locations.id', '=', 'closings.location')
        //     ->whereDate("closings.date", "=", "2023-06-15")
        //     // ->groupBy("closings.date","heads.head","head_locations.location")
        //     ->orderBy("head_locations.location","desc")
        //     ->get();

        //$closing = Closing::with("heads:id,head")->with('locations:id,location')->where("date", $req->date)->orderBy("id", "desc");

        $branches  = branches();
        $heads  = Head::all();
        return view("admin.daily-closing-grand-report", compact("branches","heads"));

    }


    function getFullDataofDailyClosing(Request $req){

        if ($req->ajax()) { 


            if($req->from_date && $req->to_date && $req->heads && $req->locations){


                $count_data = DB::table('closings')
                ->where("closings.head", $req->heads)
                ->where("closings.location", $req->locations)
                ->whereDate("closings.date", ">=", $req->from_date)
                ->whereDate("closings.date", "<=", $req->to_date)
                ->count();


                $data  = DB::table('closings')
                ->select(DB::raw('closings.date,closings.amount as sum, heads.head, head_locations.location,closings.remarks'))
                ->join('heads', 'heads.id', '=', 'closings.head')
                ->join('head_locations', 'head_locations.id', '=', 'closings.location')
                ->where("closings.head", $req->heads)
                ->where("closings.location", $req->locations)
                ->whereDate("closings.date", ">=", $req->from_date)
                ->whereDate("closings.date", "<=", $req->to_date)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("head_locations.location","desc");

                

            }elseif($req->from_date && $req->to_date && $req->heads){


                $count_data = DB::table('closings')
                ->where("closings.head", $req->heads)
                ->whereDate("closings.date", ">=", $req->from_date)
                ->whereDate("closings.date", "<=", $req->to_date)
                ->count();


                $data  = DB::table('closings')
                ->select(DB::raw('closings.date,closings.amount as sum, heads.head, head_locations.location,closings.remarks'))
                ->join('heads', 'heads.id', '=', 'closings.head')
                ->join('head_locations', 'head_locations.id', '=', 'closings.location')
                ->where("closings.head", $req->heads)
                ->whereDate("closings.date", ">=", $req->from_date)
                ->whereDate("closings.date", "<=", $req->to_date)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("head_locations.location","desc");
                

            }elseif($req->from_date && $req->to_date){

                $count_data = DB::table('closings')
                ->whereDate("closings.date", ">=", $req->from_date)
                ->whereDate("closings.date", "<=", $req->to_date)
                ->count();

                $data  = DB::table('closings')
                ->select(DB::raw('closings.date,closings.amount as sum, heads.head, head_locations.location,closings.remarks'))
                ->join('heads', 'heads.id', '=', 'closings.head')
                ->join('head_locations', 'head_locations.id', '=', 'closings.location')
                ->whereDate("closings.date", ">=", $req->from_date)
                ->whereDate("closings.date", "<=", $req->to_date)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("head_locations.location","desc");
               

            }else{


                $count_data = DB::table('closings')->count();

                $data  = DB::table('closings')
                ->select(DB::raw('closings.date,closings.amount as sum, heads.head, head_locations.location,closings.remarks'))
                ->join('heads', 'heads.id', '=', 'closings.head')
                ->join('head_locations', 'head_locations.id', '=', 'closings.location')
                ->offset($req->start)
                ->limit(10)
                ->orderBy("head_locations.location","desc");
               
            }
           
            


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return date_format(date_create($row->date), "d-m-Y");
                })
              
                ->addColumn('head', function ($row) {
                    return $row->head;
                })

                ->addColumn('location', function ($row) {
                    return $row->location;
                })

                ->addColumn('amount', function ($row) {
                    return $row->sum;
                })

                ->addColumn('remarks_get', function ($row) {
                    return $row->remarks;
                })


                ->setFilteredRecords($count_data)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    function getPdfDataofDailyClosing(Request $req, $from_date=null, $to_date=null,  $locations=null, $heads=null){


        if($from_date && $to_date && $heads && $locations){

            $sum_of_sale_datewise = DB::table('closings')
            ->select(DB::raw('sum(closings.amount) as sum'))
            ->where("closings.head",2)
            ->whereDate("closings.date", ">=", $from_date)
            ->whereDate("closings.date", "<=", $to_date)
            ->groupBy("date")
            ->orderBy("closings.head","desc")->get();


            $data  = DB::table('closings')
            ->select(DB::raw('closings.date,closings.amount as sum, heads.head, head_locations.location'))
            ->join('heads', 'heads.id', '=', 'closings.head')
            ->join('head_locations', 'head_locations.id', '=', 'closings.location')
            ->where("closings.head", $heads)
            ->where("closings.location", $locations)
            ->whereDate("closings.date", ">=", $from_date)
            ->whereDate("closings.date", "<=", $to_date)
            ->orderBy("closings.head","desc")->get();
           

        }elseif($from_date && $to_date  && $locations){

            $sum_of_sale_datewise = DB::table('closings')
            ->select(DB::raw('sum(closings.amount) as sum'))
            ->where("closings.head",2)
            ->whereDate("closings.date", ">=", $from_date)
            ->whereDate("closings.date", "<=", $to_date)
            ->groupBy("date")
            ->orderBy("closings.head","desc")->get();


            $data  = DB::table('closings')
            ->select(DB::raw('closings.date,closings.amount as sum, heads.head, head_locations.location'))
            ->join('heads', 'heads.id', '=', 'closings.head')
            ->join('head_locations', 'head_locations.id', '=', 'closings.location')
            ->where("closings.location", $locations)
            ->whereDate("closings.date", ">=", $from_date)
            ->whereDate("closings.date", "<=", $to_date)
            ->orderBy("closings.head","desc")->get();
           
        }
        
        elseif($from_date && $to_date && $heads){

            $sum_of_sale_datewise = DB::table('closings')
            ->select(DB::raw('sum(closings.amount) as sum'))
            ->where("closings.head",2)
            ->whereDate("closings.date", ">=", $from_date)
            ->whereDate("closings.date", "<=", $to_date)
            ->groupBy("date")
            ->orderBy("closings.head","desc")->get();


            $data  = DB::table('closings')
            ->select(DB::raw('closings.date,closings.amount as sum, heads.head, head_locations.location'))
            ->join('heads', 'heads.id', '=', 'closings.head')
            ->join('head_locations', 'head_locations.id', '=', 'closings.location')
            ->where("closings.head", $heads)
            ->whereDate("closings.date", ">=", $from_date)
            ->whereDate("closings.date", "<=", $to_date)
            ->orderBy("closings.head","desc")->get();
           

        }elseif($from_date && $to_date){
           
            $sum_of_sale_datewise = DB::table('closings')
            ->select(DB::raw('sum(closings.amount) as sum'))
            ->where("closings.head",2)
            ->whereDate("closings.date", ">=", $from_date)
            ->whereDate("closings.date", "<=", $to_date)
            ->groupBy("date")
            ->orderBy("closings.head","desc")->get();

            $data  = DB::table('closings')
            ->select(DB::raw('closings.date,closings.amount as sum, heads.head, head_locations.location'))
            ->join('heads', 'heads.id', '=', 'closings.head')
            ->join('head_locations', 'head_locations.id', '=', 'closings.location')
            ->whereDate("closings.date", ">=", $from_date)
            ->whereDate("closings.date", "<=", $to_date)
            ->orderBy("closings.head","desc")->get();
            

        }else{
            $data  = DB::table('closings')
            ->select(DB::raw('closings.date,closings.amount as sum, heads.head, head_locations.location'))
            ->join('heads', 'heads.id', '=', 'closings.head')
            ->join('head_locations', 'head_locations.id', '=', 'closings.location')
            ->orderBy("closings.head","desc")->get();

            $sum_of_sale_datewise = DB::table('closings')
            ->select(DB::raw('sum(closings.amount) as sum'))
            ->where("closings.head",2)
            // ->whereDate("closings.date", ">=", "2023-06-15")
            // ->whereDate("closings.date", "<=", "2023-06-17")
            ->groupBy("date")
            ->orderBy("closings.head","desc")->get();
           
        }

        $from = $from_date;
        $to = $to_date;

        $html = [];
        $html["title"] = "Closing Record";
        $html["view"] = view("admin.get-pdf-of-closing", compact("data","sum_of_sale_datewise","from","to"))->render();
        return response()->json($html, 200);



       
        // $pdf = PDF::loadView("admin.get-pdf-of-closing", compact("data","sum_of_sale_datewise","from","to"));
        // $file = $pdf->download('nfc_closing_report.pdf');
        // return response()->json([base64_encode($file)], 200);

        //return view("admin.get-pdf-of-closing", compact("data","sum_of_sale_datewise"));
    }


    function getViewofDailyClosingGrandData(Request $req){

        
        if($req->from_date && $req->to_date && $req->heads && $req->locations){


            $sum_of_sale_datewise = DB::table('closings')
            ->select(DB::raw('sum(closings.amount) as sum'))
            ->where("closings.head",2)
            ->whereDate("closings.date", ">=", $req->from_date)
            ->whereDate("closings.date", "<=", $req->to_date)
            ->groupBy("date")
            ->orderBy("closings.head","desc")->get();


            $data  = DB::table('closings')
            ->select(DB::raw('closings.date,closings.amount as sum, heads.head, head_locations.location'))
            ->join('heads', 'heads.id', '=', 'closings.head')
            ->join('head_locations', 'head_locations.id', '=', 'closings.location')
            ->where("closings.head", $req->heads)
            ->where("closings.location", $req->locations)
            ->whereDate("closings.date", ">=", $req->from_date)
            ->whereDate("closings.date", "<=", $req->to_date)
            ->orderBy("closings.head","desc")->get();
           

        }elseif($req->from_date && $req->to_date && $req->heads){

            $sum_of_sale_datewise = DB::table('closings')
            ->select(DB::raw('sum(closings.amount) as sum'))
            ->where("closings.head",2)
            ->whereDate("closings.date", ">=", $req->from_date)
            ->whereDate("closings.date", "<=", $req->to_date)
            ->groupBy("date")
            ->orderBy("closings.head","desc")->get();


            $data  = DB::table('closings')
            ->select(DB::raw('closings.date,closings.amount as sum, heads.head, head_locations.location'))
            ->join('heads', 'heads.id', '=', 'closings.head')
            ->join('head_locations', 'head_locations.id', '=', 'closings.location')
            ->where("closings.head", $req->heads)
            ->whereDate("closings.date", ">=", $req->from_date)
            ->whereDate("closings.date", "<=", $req->to_date)
            ->orderBy("closings.head","desc")->get();
           

        }elseif($req->from_date && $req->to_date){
           
            $sum_of_sale_datewise = DB::table('closings')
            ->select(DB::raw('sum(closings.amount) as sum'))
            ->where("closings.head",2)
            ->whereDate("closings.date", ">=", $req->from_date)
            ->whereDate("closings.date", "<=", $req->to_date)
            ->groupBy("date")
            ->orderBy("closings.head","desc")->get();

            $data  = DB::table('closings')
            ->select(DB::raw('closings.date,closings.amount as sum, heads.head, head_locations.location'))
            ->join('heads', 'heads.id', '=', 'closings.head')
            ->join('head_locations', 'head_locations.id', '=', 'closings.location')
            ->whereDate("closings.date", ">=", $req->from_date)
            ->whereDate("closings.date", "<=", $req->to_date)
            ->orderBy("closings.head","desc")->get();
            

        }else{
            $data  = DB::table('closings')
            ->select(DB::raw('closings.date,closings.amount as sum, heads.head, head_locations.location'))
            ->join('heads', 'heads.id', '=', 'closings.head')
            ->join('head_locations', 'head_locations.id', '=', 'closings.location')
            ->orderBy("closings.head","desc")->get();

            $sum_of_sale_datewise = DB::table('closings')
            ->select(DB::raw('sum(closings.amount) as sum'))
            ->where("closings.head",2)
            // ->whereDate("closings.date", ">=", "2023-06-15")
            // ->whereDate("closings.date", "<=", "2023-06-17")
            ->groupBy("date")
            ->orderBy("closings.head","desc")->get();
           
        }

        $from = $req->from_date;
        $to = $req->to_date;

        $html = [];
        $html["title"] = "Closing Record";
        $html["view"] = view("admin.get-view-of-data-closing", compact("data","sum_of_sale_datewise","from","to"))->render();
        return response()->json($html, 200);
    
    }
    

    function checkBalance(Request $req){


        //easypaisa detail
        $easypaisa_amount = DB::table('closings')
        ->select(DB::raw('sum(amount) as sum'))
        ->where("head", "5")
        ->where("location","!=","6")
        ->get();


        $paid_amount  = DB::table('easypaisa_paid_amounts')
        ->select(DB::raw('sum(amount) as sum'))
        ->get();


        $from_foodpanda_amount  = DB::table('foodpanda_amounts')
        ->select(DB::raw('sum(amount) as sum'))
        ->where("account", "Easypaisa")
        ->get();


        //hbl detail
        $hbl_amount = DB::table('closings')
        ->select(DB::raw('sum(amount) as sum'))
        ->where("head", "4")
        ->where("location", "!=" ,"6")
        ->get();

        $hbl_paid_amount  = DB::table('hbl_amounts')
        ->select(DB::raw('sum(amount) as sum'))
        ->get();

        $from_foodpanda_amount_hbl  = DB::table('foodpanda_amounts')
        ->select(DB::raw('sum(amount) as sum'))
        ->where("account", "HBL")
        ->get();



        //123 is the id of location
        //locker detail
        $locker = DB::table('closings')
        ->select(DB::raw('sum(amount) as sum'))
        ->where("head", "9")
        ->whereIn("location",[1,2,3])
        ->get();

        //this is for sadqa deduction
        $sum_of_sale_datewise = DB::table('closings')
            ->select(DB::raw('sum(closings.amount) as sum'))
            ->where("closings.head",2)
            ->where("location", "!=" ,6)
            ->groupBy("date")
            ->orderBy("closings.head","desc")->get();
        
        
        $from_foodpanda_amount_locker  = DB::table('foodpanda_amounts')
            ->select(DB::raw('sum(amount) as sum'))
            ->where("account", "Locker")
            ->get();
    

        $sadqa_caculate = 0;

        foreach( $sum_of_sale_datewise  as $sale_sum){
            $sadqa_caculate = $sadqa_caculate + ($sale_sum->sum > 0 ? ceil((($sale_sum->sum  / 100) * 2) / 10) * 10 : 0);
        }

      
        //this is the total sum of paid amount through locker
        $sum_of_paid_locker = DB::table('locker_details')
            ->select(DB::raw('sum(amount) as sum'))
            ->get();

        
        $sum_locker_out_source  = DB::table('locker_amount_out_sources')
        ->select(DB::raw('sum(amount) as sum'))
        ->get();


        // $installment_get  = DB::table('installments')
        // ->sum("pay_installment");

        // $sadqa_get  = DB::table('sadqas')
        // ->sum("pay_sadqa_amount");


        $hbl_reserve_amount = hblReserveAmount();
        $easypaisa_reserve_amount = EasypaisaReserveAmount();
        $locker_reserve_amount = LockerReserveAmount();


        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Total Amount";
            $html["view"] = view("admin.check-amount", compact("locker_reserve_amount","easypaisa_reserve_amount","hbl_reserve_amount","from_foodpanda_amount","from_foodpanda_amount_hbl","from_foodpanda_amount_locker","sum_locker_out_source","sum_of_paid_locker","easypaisa_amount","paid_amount", "hbl_amount", "hbl_paid_amount","locker","sadqa_caculate"))->render();
            return response()->json($html, 200);
        }


    }


    function paySadqaForm(){


        $sale = DB::table('closings')
        ->select(DB::raw('date,sum(amount) as sum'))
        ->where("head", "2")
        ->where("location", "!=" ,6)
        ->groupBy('date')
        ->get();

        $total = 0;
        foreach ($sale as $get_data){
            ceil(( $get_data->sum /100 * 2)/10) * 10 ;
            $total = $total + (ceil(( $get_data->sum /100 * 2)/10) * 10);
        }

        $sum_paid_sadqa = DB::table('sadqas')
        ->select(DB::raw('sum(pay_sadqa_amount) as sum'))
        ->get();

        $calculate_total = isset($sum_paid_sadqa) ? $total - $sum_paid_sadqa[0]->sum : $total;
           
        return view("admin.pay-sadqa-form", compact("sale", "calculate_total"));
    }


    function paySadqaInsert(Request $req){

        $validation = [
            'pay_sadqa_amount'=>"required",
            'pay_to'=>"required",
           
        ];

        $validator = Validator::make($req->all(), $validation);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        if($req->hidden_id){
            $sadqa = Sadqa::find($req->hidden_id);
        }else{
            $sadqa = new Sadqa();
        }
        $sadqa->pay_sadqa_amount = $req->pay_sadqa_amount;
        $sadqa->pay_to = $req->pay_to;
        $sadqa->save();

        return response()->json("saved", 200);

    
    }

    function getSadqaList(Request $req){

        
        if ($req->ajax()) { 


            if($req->from_date && $req->to_date){
                
                $count_data = DB::table('sadqas')
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->count();

                $data  = DB::table('sadqas')
                ->whereDate("created_at", ">=", $req->from_date)
                ->whereDate("created_at", "<=", $req->to_date)
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id","desc");

            }else{
                $count_data = DB::table('sadqas')->count();
                
                $data  = DB::table('sadqas')
                ->offset($req->start)
                ->limit(10)
                ->orderBy("id","desc");

            }


            
               
        
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return date_format(date_create($row->created_at), "d-m-Y");
                })
              
                ->addColumn('pay_sadqa_amount', function ($row) {
                    return $row->pay_sadqa_amount;
                })

                ->addColumn('pay_to', function ($row) {
                    return $row->pay_to;
                })

                ->addColumn('action', function ($row) {

                    $btn = '<div class="btn-group btn-sm">
                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" '.(Auth::User()->user_type == "User" ? "disabled" : "").' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        </button>
                        <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item  edit-sadqa-amount"  data-id="' . $row->id . '">Edit</a>';
                        // $btn .= '<a  href="javascript:void(0)" class="dropdown-item delete-vendor-amount" data-id="' . $row->id . '">Delete</a>';

                        $btn .= '</div>
                        </div>';



                    return $btn;
                })

                ->setFilteredRecords($count_data)
                ->setTotalRecords($data->count())
                ->rawColumns(['action'])
                ->make(true);
        }
    }



    function viewSadqaReport(Request $req){


        $sale = DB::table('closings')
        ->select(DB::raw('date,sum(amount) as sum'))
        ->where("head", "2")
        ->groupBy('date')
        ->get();

        $total = 0;
        foreach ($sale as $get_data){
            ceil(( $get_data->sum /100 * 2)/10) * 10 ;
            $total = $total + (ceil(( $get_data->sum /100 * 2)/10) * 10);
        }


        $sadqa = Sadqa::all();

        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Grand Sadqa Pay Report";
            $html["view"] = view("admin.view-sadqa-report", compact("sadqa","total"))->render();
            return response()->json($html, 200);
        }

        //return view("admin.view-sadqa-report", compact("sadqa","total"));
    }


    function register()
    {
        $branches = branches();
        return view('auth.register', compact("branches"));
    }


    function registerUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users,name|max:20',
            'password' => 'required|max:20|confirmed',
            'password_confirmation' => 'required',
            'user_type' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $register = new User();
        $register->name = trim($request->name);
        $register->password = Hash::make(trim($request->password));
        $register->user_type = trim($request->user_type);
        $register->user_branch = $request->user_branch;
        $register->save();
        return json_encode("saved");
    }



    function loginForm(){

        if (!Auth::check()) {
            return view('auth.login');
            // return Redirect::route('login');
        }
        // return view('auth.login-form');
        return redirect('/');
    }


    function postLogin(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);



        $credentials = $request->only('name', 'password');
        if (Auth::attempt($credentials)) {

            return redirect('/');
        }

        return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
    }




    function logout()
    {
        session()->flush();
        Auth::logout();

        return Redirect('login');
    }

    

}
