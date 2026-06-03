<?php 
require_once('include/header.php');
require_once('include/navbar.php');
require_once('include/config.php');

/* =========================
   INSERT LOGIC
========================= */
if (isset($_POST['submit'])) {

    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile   = mysqli_real_escape_string($conn, $_POST['mobile']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role     = mysqli_real_escape_string($conn, $_POST['role']);

    $query = "INSERT INTO tblusers (name, email, mobile, password, role) 
              VALUES ('$name', '$email', '$mobile', '$password', '$role')";

    if (mysqli_query($conn, $query)) {
         echo "<script>
            alert('User Added Successfully');
            window.location.href='list_user.php';
          </script>";
              } else {
        echo "<script>alert('Error: ".mysqli_error($conn)."');</script>";
    }
}
?>

<head>
    <style>
        .form-card {
            background: var(--bg-card);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }
        
        .form-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 25px 30px;
            margin-bottom: 30px;
        }
        
        .form-header h5 {
            color: white;
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-header h5 i {
            font-size: 24px;
        }
        
        .form-container {
            padding: 0 30px 30px 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        
        .form-group label i {
            color: #667eea;
            font-size: 14px;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--input-border);
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: var(--input-bg);
            color: var(--text-main);
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            background: var(--input-bg);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-group input:hover,
        .form-group select:hover {
            border-color: #cbd5e0;
        }
        
        .btn-save {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 35px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            color: white !important;
        }
        
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white !important;
        }
        
        .btn-save i {
            margin-right: 8px;
            color: white;
        }
        
        @media (max-width: 768px) {
            .form-container {
                padding: 0 20px 20px 20px;
            }
            
            .form-header {
                padding: 20px;
            }
            
            .form-header h5 {
                font-size: 18px;
            }
            
            .btn-save {
                padding: 10px 25px;
                font-size: 14px;
                width: 100%;
            }
        }
        
        /* Animation */
        .form-card {
            animation: fadeInUp 0.5s ease;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Row spacing */
        .row {
            margin-bottom: 10px;
        }
    </style>
</head>

<div id="content-wrapper" class="d-flex flex-column">
<div id="content">

<div class="container-fluid">

    <div class="form-card">
        
        <div class="form-header">
            <h5>
                <i class="fas fa-user-plus"></i> 
                Add New User
            </h5>
        </div>
        
        <div class="form-container">
            <form method="POST">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label><i class="fas fa-user"></i> Full Name</label>
                            <input type="text" name="name" class="form-control" required 
                                  maxlength="30">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label><i class="fas fa-envelope"></i> Email Address</label>
                            <input type="email" name="email" class="form-control" required maxlength="30" 
                                   >
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-group">
    <label><i class="fas fa-phone"></i> Mobile Number</label>
    <input type="text" name="mobile" class="form-control" required maxlength="10"
           oninput="this.value = this.value.replace(/[^0-9]/g, '')">
</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label><i class="fas fa-lock"></i> Password</label>
                            <input type="text" name="password" class="form-control" required 
                                   maxlength="8">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label><i class="fas fa-user-shield"></i> User Role</label>
                            <input type="text" name="role" class="form-control" required 
                                   value="Operation" readonly>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" name="submit" class="btn btn-save">
                        <i class="fas fa-save"></i> Save User
                    </button>
                </div>

            </form>
        </div>
        
    </div>
</div>
</div>
</div>

</div>


<?php
include('include/scripts.php');
include('include/footer.php');
?>