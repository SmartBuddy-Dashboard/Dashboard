<?php
include("include/config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $mobile   = mysqli_real_escape_string($conn, $_POST['mobile']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role     = mysqli_real_escape_string($conn, $_POST['role']);

    $query = "SELECT * FROM tblusers 
              WHERE mobile='$mobile' 
              AND password='$password' 
              AND role='$role' 
              AND status=1";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        // ✅ Set session
        $_SESSION['mobile']  = $user['mobile'];
        $_SESSION['role']    = $user['role'];
        $_SESSION['user_id'] = $user['id'];

        // ✅ Redirect to dashboard
        header("Location: dashboard.php");
        exit();

    } else {
        echo "<script>alert('❌ Invalid Mobile, Password, Role, or Inactive User');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | Secure Access</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #4cc9f0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4bb543;
            --error-color: #ff3333;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
        }
        
        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            transition: all 0.3s ease;
        }
        
        .login-card:hover {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h3 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .login-header p {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .custom-line {
            border-top: 2px solid var(--primary-color);
            opacity: 0.3;
            margin: 1.5rem 0;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #dee2e6;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        }
        
        .input-group-text {
            background-color: white;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .input-group-text:hover {
            background-color: #f8f9fa;
        }
        
        .btn-custom {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.3s;
            margin-top: 1rem;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
        }
        
        .admin-badge {
            display: inline-flex;
            align-items: center;
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 1rem;
        }
        
        .admin-badge i {
            margin-right: 0.5rem;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 2rem;
            color: #6c757d;
            font-size: 0.85rem;
        }
        
        .is-invalid {
            border-color: var(--error-color) !important;
        }
        
        .text-danger {
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
        
        @media (max-width: 576px) {
            .login-card {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h3>Admin Login</h3>
                           </div>
            
            <hr class="custom-line">
            
            <form method="POST" action="">
                <!-- Mobile Number -->
                <div class="mb-4 text-start">
                    <label class="form-label">Mobile Number</label>
                    <input type="text" name="mobile" id="mobile" maxlength="10" class="form-control"
                           pattern="[6-9][0-9]{9}"
                           title="Mobile number must start with 6, 7, 8, or 9 and be 10 digits long"
                           placeholder="Enter 10-digit mobile number"
                           required>
                    <small id="mobileError" class="text-danger d-none">❌ Invalid mobile number format</small>
                </div>

                <!-- Password -->
                <div class="mb-4 text-start">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" maxlength="8" class="form-control" 
                               id="password" placeholder="Enter your password" required>
                        <span class="input-group-text" onclick="togglePassword()">
                            <i id="eyeIcon" class="bi bi-eye-slash"></i>
                        </span>
                    </div>
                </div>

                <!-- Hidden Role -->
                <input type="hidden" name="role" value="Admin">
              

                <!-- Submit Button -->
                <button type="submit" class="btn btn-custom w-100">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Login
                </button>
            </form>
            
            <div class="login-footer">
                <p>For authorized personnel only</p>
            </div>
        </div>
    </div>

    <!-- JavaScript for validation and toggle -->
    <script>
        // Mobile Validation
        let mobileInput = document.getElementById("mobile");
        let errorMsg = document.getElementById("mobileError");
        let regex = /^[6-9][0-9]{9}$/;

        function validateMobile() {
            if (!regex.test(mobileInput.value.trim())) {
                errorMsg.classList.remove("d-none");
                mobileInput.classList.add("is-invalid");
            } else {
                errorMsg.classList.add("d-none");
                mobileInput.classList.remove("is-invalid");
            }
        }

        mobileInput.addEventListener("blur", validateMobile);
        mobileInput.addEventListener("input", function () {
            this.value = this.value.replace(/[^0-9]/g, "");
            validateMobile();
        });

        // Toggle Password Visibility
        function togglePassword() {
            let pwd = document.getElementById("password");
            let eyeIcon = document.getElementById("eyeIcon");
            if (pwd.type === "password") {
                pwd.type = "text";
                eyeIcon.classList.replace("bi-eye-slash", "bi-eye");
            } else {
                pwd.type = "password";
                eyeIcon.classList.replace("bi-eye", "bi-eye-slash");
            }
        }
        
        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!regex.test(mobileInput.value.trim())) {
                e.preventDefault();
                errorMsg.classList.remove("d-none");
                mobileInput.classList.add("is-invalid");
                mobileInput.focus();
            }
        });
    </script>
</body>
</html>