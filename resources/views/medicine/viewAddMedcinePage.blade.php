<!DOCTYPE html>
<html lang="en">

<head>
<style>
  .star{
color:red;
}
.container1 {
 display: grid;
 align-items: center; 
 grid-template-columns: 1fr 2fr 1fr;
 column-gap: 1px;
}

.img {
  width: 50px;
  height:50px;
}

.text1 {
    font-size: 25px;
   font-family: url(sansation_light.woff);
   src: url(sansation_light.woff);

    color: #EF3B2D;
}
</style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Ghazaband Scouts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

   
<!-- Custom fonts -->
<link href="{{URL('dashboard/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles-->
    <link href="{{URL('dashboard/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{URL('dashboard/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route ('home') }}">
                
                <div class="sidebar-brand-text mx-3">Ghazaband Scouts</div>
            </a>

            <br>


            <!-- Heading -->
            <div class="sidebar-heading">
                Components
            </div>
            @if($User->role=='Doctor')
            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                  
                    <span>Patient</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Patient Components:</h6>
                        

                        <a class="collapse-item" href="{{ route ('patient.view') }}">Add</a>
                        <a class="collapse-item" href="{{ route ('home') }}">Reports</a>
                        <a class="collapse-item" href="{{ route ('patient.check') }}">Check</a>

                        <a class="collapse-item" href="{{ route ('fromDoctorPatients') }}">On Store</a>
                        <a class="collapse-item" href="{{ route ('downloadingPatientExcel') }}">Download Backup</a>
                        
                    </div>
                </div>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            @endif
            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse"
                    aria-expanded="true" aria-controls="collapseTwo">
                  
                    <span>Medicine</span>
                </a>
                <div id="collapse" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Medicine Components:</h6>
                        
                        
                        <a class="collapse-item" href="{{ route ('medicines.show') }}">Reports</a>
                        
                        <a class="collapse-item active" href="{{ route ('medicine.view') }}">Add</a>
                    </div>
                </div>
            </li>

            <hr class="sidebar-divider">
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse4"
                    aria-expanded="true" aria-controls="collapseTwo">
                  
                    <span>Voucher</span>
                </a>
                <div id="collapse4" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">vouchers Components:</h6>
                        <a class="collapse-item" href="{{ route ('allVoucher') }}">Reports</a>
                        <a class="collapse-item" href="{{ route ('voucher.view') }}">Add</a>
                    </div>
                </div>
            </li>
            <hr class="sidebar-divider">
             
            @if($User->role=='Doctor')
                        
            <!-- Divider -->

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse1"
                    aria-expanded="true" aria-controls="collapseTwo">
                  
                    <span>Users</span>
                </a>
                <div id="collapse1" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">users Components:</h6>
                        <a class="collapse-item" href="{{ route ('users.show') }}">Reports</a>
                        <a class="collapse-item" href="{{ route ('user.view') }}">Add</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">
            @endif
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                <img class="img-profile rounded-circle"
                                    src="{{URL('dashboard/img/undraw_profile.svg')}}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->
                <div class="container1">
                    <div class="image">
                        <img src="assets/1.png">
                    </div>
                    <div class="text1">
                        <h1>Ghazaband Scouts</h1>
                    </div>
                    <div class="image">
                        <img src="assets/2.png">
                    </div>
                    </div>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">New Medicine</h1>
                    
                    <div class="card shadow mb-4">
                        
                        <div class="card-body">
                           <form action="{{ route ('medicine.add') }}"  method="post">
                                    @csrf
                                
                                    <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Medicine Name<sup class="star">*</sup></label>
                                <div class="col-sm-8">
                                <input placeholder="Enter the Medicine Name" class="form-control"  id="name" name="name"  type="text" maxlength="40" required>
                                    </div>
                              </div>

                            
                              <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Medicine Type<sup class="star">*</sup></label>
                                <div class="col-sm-8">
                                            
                            <select class="form-select" aria-label="Default select example" name="medicineType" id="medicineType">
                                <option value="Cap">Cap</option>
                                <option value="Tab">Tab</option>
                                <option value="Syp">Syp</option>
                                <option value="Drops">Drops</option>
                                <option value="Inj">Inj</option>
                                <option value="Ointment">Ointment</option>
                                <option value="Gel">Gel</option>
                                <option value="Dressing">Dressing</option>
                            </select>
                             </div>
                             </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Purchase Type<sup class="star">*</sup></label>
                                <div class="col-sm-8">
                                            
                            <select class="form-select" aria-label="Default select example" name="purchaseType" id="purchaseType">
                                <option value="Bulk">Bulk</option>
                                <option value="LP">LP</option>
                            </select>
                             </div>
                             </div>
                             <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Quantity<sup class="star">*</sup></label>
                                <div class="col-sm-8">
                                <input placeholder="Enter the Medicine Quantity" class="form-control" maxlength="30" id="quantity" name="quantity"  type="text" required>
                                    </div>
                              </div>
                            <br><br>
                                  <center>
                            <button class="btn btn-primary" type="submit">
                            Add
                            </button>

                                  </center>  
                                    <br>
                                    
                                </form> 
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Ghazaband Scouts 2023</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    
                    <a class="btn btn-primary" href="{{ route('logout') }}"
                      onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                      </a>
                    <form id="logout-form"  action="{{ route('logout') }}" method="POST" class="d-none">
                      @csrf
                    </form>

                  </div>
            </div>
        </div>
    </div>
    
    @include('sweetalert::alert')
    <!-- Bootstrap core JavaScript-->

    <script src="{{URL('dashboard/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{URL('dashboard/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{URL('dashboard/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{URL('dashboard/js/sb-admin-2.min.js')}}"></script>

    <!-- Page level plugins -->
    <script src="{{URL('dashboard/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL('dashboard/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <!-- Page level custom scripts -->
    <script src="{{URL('dashboard/js/demo/datatables-demo.js')}}"></script>

</body>

</html>