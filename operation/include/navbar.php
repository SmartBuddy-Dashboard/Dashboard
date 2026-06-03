
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php" style="height: 100px;">
                <div class="sidebar-brand-text mx-3"> 
                    <img src="../smartbuddy.png" style="max-height: 80px; max-width: 100%; border-radius: 8px;">
                </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <li class="nav-item active">
                <a class="nav-link" href="home.php"> <i class="fas fa-fw fa-home-alt"></i><span>Home</span></a>
            </li>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php"> <i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
            </li>

            <?php
            if( $_SESSION['role']=='1'){?>
            <!-- Divider -->
            <hr class="sidebar-divider">
          
            <!-- Nav Item - Charts -->
            <li class="nav-item"><a class="nav-link" href="list_client.php"><i class="fas fa-fw fa-folder"></i><span>Clients</span></a>
            </li>
       
            <li class="nav-item"><a class="nav-link" href="list_project.php"><i class="fas fa-fw fa-folder"></i><span>Project</span></a>
            </li>

            <li class="nav-item"><a class="nav-link" href="list_machinedetails.php"><i class="fas fa-fw fa-folder"></i><span>Machines</span></a>
            </li>

            <li class="nav-item"><a class="nav-link" href="update_machineid.php"><i class="fas fa-fw fa-folder"></i><span>Update Machine ID</span></a>
            </li>

              <!-- Divider -->
            <hr class="sidebar-divider">
          
            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="view.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Manage Clients</span></a>
            </li>
         <?php }?>
                         
               <!-- Divider -->
            <hr class="sidebar-divider">

                    <!-- Nav Item - Pages Collapse Menu -->
            <!--<li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne"
                    aria-expanded="true" aria-controls="collapseOne"> <i class="fas fa-fw fa-table"></i><span>MASTER</span></a>
                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="list_client.php">Clients</a>
                        <a class="collapse-item" href="list_project.php">Project</a>
                        <a class="collapse-item" href="list_machinedetails.php">Machines</a>
                        <a class="collapse-item" href="list_unassignmachine.php">Assign Machine</a>
                        <a class="collapse-item" href="update_machineid.php">Update Machine ID</a>
                    </div>
                </div>
            </li>
		-->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo"> <i class="fas fa-fw fa-table"></i><span>REPORTS</span></a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="report_details.php">Details</a>
                        <a class="collapse-item" href="report_maintenancelog.php">Maintenance Logs</a>
                        <a class="collapse-item" href="report_alllogs.php">All Machine Logs</a>
                    </div>
                </div>
            </li>
            
           
        </ul>
        <!-- End of Sidebar -->

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
                        <a class="btn btn-primary" href="logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>


   <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                     <!-- Centered Admin Text -->
                    <h3 class="position-absolute w-100 text-center m-0" style="pointer-events: none;">USER</h3>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                            
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                      

                        <!-- Theme Toggle Button -->
                        <li class="nav-item d-flex align-items-center">
                            <button type="button" id="my-new-theme-btn" class="theme-toggle-btn" title="Toggle Theme" onclick="toggleThemeNow()" style="background:transparent;border:none;color:var(--text-muted);font-size:1.2rem;cursor:pointer;padding:0 15px;">
                                <i id="my-new-theme-icon" class="fas fa-sun"></i>
                            </button>
                        </li>
                        <script>
                            function toggleThemeNow() {
                                let currentTheme = localStorage.getItem('theme') || 'dark';
                                let newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                                document.documentElement.setAttribute('data-theme', newTheme);
                                localStorage.setItem('theme', newTheme);
                                let icon = document.getElementById('my-new-theme-icon');
                                if (icon) {
                                    icon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
                                }
                            }
                            window.addEventListener('DOMContentLoaded', function() {
                                let savedTheme = localStorage.getItem('theme') || 'dark';
                                let icon = document.getElementById('my-new-theme-icon');
                                if (icon) {
                                    icon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
                                }
                            });
                        </script>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small" style="font-weight: 600;">
                                    <?= htmlspecialchars($_SESSION['name'] ?? 'User') ?>
                                </span>
                                <img class="img-profile rounded-circle shadow-sm"
                                    src="img/undraw_profile.svg" style="border: 2px solid var(--primary);">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in p-0" aria-labelledby="userDropdown" style="min-width: 260px; border-radius: 12px; overflow: hidden; border: 1px solid rgba(0,0,0,0.1);">
                                
                                <!-- Profile Header -->
                                <div class="dropdown-header text-center text-white py-4" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark));">
                                    <img class="img-profile rounded-circle shadow mb-2" src="img/undraw_profile.svg" style="width: 65px; height: 65px; border: 3px solid rgba(255,255,255,0.4);">
                                    <h6 class="m-0 font-weight-bold" style="font-size: 1.1rem; letter-spacing: 0.5px;"><?= htmlspecialchars($_SESSION['name'] ?? 'User') ?></h6>
                                    <span class="badge badge-light mt-1 text-primary" style="font-size: 0.75rem; padding: 4px 8px; border-radius: 20px;"><?= htmlspecialchars($_SESSION['role'] ?? 'Operation') ?></span>
                                </div>
                                
                                <div class="px-3 py-3 dropdown-body-custom">
                                    <small class="text-muted text-uppercase font-weight-bold mb-2 d-block" style="font-size: 0.7rem; letter-spacing: 1px;">Account Details</small>
                                    
                                    <div class="d-flex align-items-center mt-2 p-2 rounded" style="background-color: rgba(67, 97, 238, 0.05);">
                                        <div class="mr-3 text-center" style="width: 30px; height: 30px; line-height: 30px; background: rgba(67, 97, 238, 0.1); border-radius: 50%;">
                                            <i class="fas fa-id-badge text-primary" style="font-size: 0.8rem;"></i>
                                        </div>
                                        <div>
                                            <span class="d-block font-weight-bold" style="font-size: 0.9rem; color: var(--text-main);"><?= htmlspecialchars($_SESSION['mobile'] ?? 'N/A') ?></span>
                                            <span class="d-block" style="font-size: 0.7rem; color: var(--text-muted);">User ID / Mobile</span>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex align-items-center mt-2 p-2 rounded" style="background-color: rgba(46, 204, 113, 0.05);">
                                        <div class="mr-3 text-center" style="width: 30px; height: 30px; line-height: 30px; background: rgba(46, 204, 113, 0.1); border-radius: 50%;">
                                            <i class="fas fa-shield-alt text-success" style="font-size: 0.8rem;"></i>
                                        </div>
                                        <div>
                                            <span class="d-block font-weight-bold" style="font-size: 0.9rem; color: var(--text-main);"><?= htmlspecialchars($_SESSION['role'] ?? 'Operation') ?> Access</span>
                                            <span class="d-block" style="font-size: 0.7rem; color: var(--text-muted);">Security Level</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown-divider m-0 border-0" style="height: 1px; background: rgba(0,0,0,0.05);"></div>
                                
                                <a class="dropdown-item py-3 text-danger font-weight-bold text-center dropdown-item-logout" href="logout.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i>
                                    Secure Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->
        