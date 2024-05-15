<!DOCTYPE html>
<html lang="en">

<head>
{{-- 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="{{ url('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <!-- <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet"> -->

    <!-- Custom styles for this template-->
    <link href="{{ url('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <link href="{{ url('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}




    {{-- from role and permissin header --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>NFC</title>
    <link href="{{ url('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    {{-- <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet"> --}}

    

    <!-- Custom styles for this template-->
    <link href="{{ url('css/sb-admin-2.min.css') }}" rel="stylesheet">

    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.bootstrap5.min.css"> --}}

    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css"> --}}
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css"> --}}

    <link href="{{ url('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    {{-- end --}}




    <style>


        .select2-container .select2-selection--single{
            height: 36px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b{

            margin-top: 3px !important;
        }

        .check_riders{
            width:20px;
            height:20px;
        }

        /* #convert_to_number{
            font-size: 13px;
        } */

        .nav-item a{
            font-weight: bolder;
        }
       

    </style>

</head>

<body id="page-top">

    

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
      
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <nav class="navbar navbar-expand-lg navbar-light bg-light p-3">
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="check_amount">Check&nbsp;Amount</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                      <span class="navbar-toggler-icon"></span>
                    </button>
                  
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                      <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-users"></i> Employees/Others
                              </a>
                              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" id="add_employee_id"  href="{{ url('/') }}">Add</a>
                                <a class="dropdown-item"  href="{{ url('/employee-report') }}">Report</a>
                              </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-fw fa-cog"></i> Daily Closing
                              </a>
                              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ url('daily-closing') }}" >Add</a>
                                <a class="dropdown-item"  href="{{ url('daily-closing-grand-report') }}">Report</a>
                                <a class="dropdown-item" href="{{ url('pay-sadqa-form') }}"  >Pay Sadqa</a>
                              </div>
                        </li>

                        @if(Auth::User()->user_branch == "Head Office")
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-money" aria-hidden="true"></i> Easypaisa
                              </a>
                              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item"  href="{{ url('easypaisa-form') }}" >Pay</a>
                                <a class="dropdown-item"  href="{{ url('get-full-report-of-easypaisa-amount') }}" >Report</a>
                              </div>
                        </li>
                      
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class='fa fa-bank'></i> HBL
                              </a>
                              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item"  href="{{ url('hbl-form') }}" >Pay</a>
                                <a class="dropdown-item"  href="{{ url('get-full-report-of-hbl-amount') }}" >Report</a>
                              </div>
                        </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-lock"></i> Locker
                              </a>
                              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ url('locker') }}">Pay</a>
                                <a class="dropdown-item"  href="{{ url('locker-amount') }}">Add Amount To Locker</a>
                                <a class="dropdown-item"  href="{{ url('get-full-report-locker-amount') }}"  >Report</a>
                              </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-clock-o" aria-hidden="true"></i> Employee Pending
                              </a>
                              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ url('pending-form') }}">Add</a>
                                <a class="dropdown-item"  href="{{ url('generate-full-pending-report') }}" >Report</a>
                              </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-bitcoin"></i>  Salary
                              </a>
                              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ url('salary-form') }}">Generate Salary</a>
                              </div>
                        </li>


                        @if(Auth::User()->user_branch == "Head Office")
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-industry" aria-hidden="true"></i> Vendor
                              </a>
                              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ url('vendor-form') }}" >Add Detail</a>
                                <a class="dropdown-item" href="{{ url('pay-vendor-amount') }}" >Pay Amount</a>
                                <a class="dropdown-item" href="{{ url('pay-amount-report-vendor') }}" >Pay Amount Report</a>
                                <a class="dropdown-item"  href="{{ url('get-vendor-full-list') }}"  >Grand Report</a>
                              </div>
                        </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-motorcycle"></i> Rides
                              </a>
                              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ url('ride-form') }}"  >Add</a>
                                <a class="dropdown-item"href="{{ url('get-rides-full-list') }}" >Report</a>
                              </div>
                        </li>
                       
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Others
                              </a>
                              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                           
                                <a class="dropdown-item"  href="{{ url('foodpanda-to-hbl') }}"><i class="fas fa-biking"></i> Foodpanda</a>
                                <a class="dropdown-item" id="installment" href="{{ url('/pay-installment') }}"  > <i class="fas fa-id-card-alt"></i> Pay From Installment</a>
                                <a class="dropdown-item"  href="{{ url('owner-pending') }}"  > <i class="fas fa-id-card-alt"></i> Owner/Other Pending</a>
                                @if(Auth::User()->user_type !== "User")
                                 <a class="dropdown-item" href="{{ url('register') }}"  > <i class="fas fa-user-friends"></i> Registeration</a>
                                @endif
                                 <a class="dropdown-item" href="{{ url('view-chart') }}"  > <i class="fa fa-eye" aria-hidden="true"></i> View Chart</a>
                                 <a class="dropdown-item" href="{{ url('view-chart-branchwise') }}"  > <i class="fas fa-building" aria-hidden="true"></i> View Chart Branchwise</a>
                                 <a class="dropdown-item" href="{{ url('view-chart-profit-loss') }}"  > <i class="fa fa-solid fa-percent"></i> View Chart Profit & Loss</a>
                                
                            </div>
                        </li>
                        
                      </ul>

                      <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-primary mr-5" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user-circle"></i>  {{ isset(Auth::User()->name) ? Auth::User()->name : ""}}
                             </a>
                             <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                               <a class="dropdown-item" id="logout" href="{{ url('logout') }}"  >  <i class="fas fa-power-off"></i> Logout</a>
                             </div>
                      </div>
                    </div>
                  </nav>

                <!-- Begin Page Content -->
                <div class="container-fluid p-0">

                 <!-- Page Heading -->
                 {{-- <div class="d-sm-flex align-items-center justify-content-end mb-4"> --}}
                    {{-- <h1 class="h3 mb-0 text-gray-800">Dashboard</h1> --}}
                    {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                </div> --}}

                 <!-- Content Row -->



                   {{-- end this area is hide due to speed issues 

                 {{-- <div class="row head-line"  > --}}


                   


                    <!-- Earnings (Monthly) Card Example -->
                    {{-- <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                           Easypaisa Last Closing</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800 easypaisa_amount_last"></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <!-- Earnings (Monthly) Card Example -->
                    {{-- <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                           HBL Last Closing</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800 hbl-last-closing-amount"></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <!-- Earnings (Monthly) Card Example -->
                    {{-- <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Locker
                                        </div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">215,000</div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                     {{-- end this area is hide due to speed issues  --}}
                

                    <!-- Pending Requests Card Example -->
                   {{-- <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Pending Requests</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                 {{-- </div>  --}}

