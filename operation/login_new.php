<?php
include("include/config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $mobile   = mysqli_real_escape_string($conn, $_POST['mobile']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role     = mysqli_real_escape_string($conn, $_POST['role']);

    /* ===================== ADMIN LOGIN ===================== */
    if ($role === "Admin") {

        $query = "SELECT * FROM tblusers 
                  WHERE mobile = '$mobile'
                  AND password = '$password'
                  AND role = 'Admin'
                  AND status = 1";

        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) == 1) {

            $_SESSION['mobile'] = $mobile;
            $_SESSION['role']   = "Admin";

            header("Location: https://smartbuddy.co.in/smartqr/Admin/dashboard.php");
            exit();

        } else {
            echo "<script>alert('❌ Invalid Admin credentials or inactive account');</script>";
        }
    }

    /* ===================== CLIENT LOGIN ===================== */
    elseif ($role === "Client") {

        // ✅ Client table login
        $query = "SELECT * FROM clients 
                  WHERE contact_mobile = '$mobile'
                  AND password = '$password'";

        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) == 1) {

            $row = mysqli_fetch_assoc($result);

            // ✅ Store session values
            $_SESSION['mobile']      = $row['contact_mobile'];
            $_SESSION['client_name'] = $row['client_name'];
            $_SESSION['role']        = "Client";

            header("Location: https://smartbuddy.co.in/smartqr/client/dashboard.php");
            exit();

        } else {
            echo "<script>alert('❌ Invalid Client credentials');</script>";
        }
    }

    else {
        echo "<script>alert('❌ Please select a valid role');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Secure Access</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a0ca3;
            --secondary: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #4cc9f0;
            --border-radius: 12px;
            --shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
        }
        
        .login-container {
            max-width: 440px;
            width: 100%;
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
        }
        
        .brand-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .brand-logo i {
            font-size: 2.5rem;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }
        
        .brand-logo h2 {
            color: var(--dark);
            font-weight: 700;
            font-size: 1.8rem;
            margin: 0;
        }
        
        .brand-logo .subtitle {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 400;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        
        .form-control, .form-select {
            border-radius: var(--border-radius);
            padding: 0.75rem 1rem;
            border: 1.5px solid #e0e0e0;
            font-size: 0.95rem;
            transition: var(--transition);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
            transform: translateY(-1px);
        }
        
        .input-group {
            border-radius: var(--border-radius);
            overflow: hidden;
        }
        
        .input-group-text {
            background: white;
            border: 1.5px solid #e0e0e0;
            border-left: none;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .input-group-text:hover {
            background: var(--light);
            color: var(--primary);
        }
        
        .input-group:focus-within .input-group-text {
            border-color: var(--primary);
        }
        
        .btn-login {
            background: linear-gradient(to right, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: var(--border-radius);
            padding: 0.85rem;
            font-weight: 600;
            font-size: 1rem;
            transition: var(--transition);
            letter-spacing: 0.5px;
            margin-top: 0.5rem;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
            color: white;
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .btn-login i {
            transition: var(--transition);
        }
        
        .btn-login:hover i {
            transform: translateX(3px);
        }
        
        .role-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 1.5rem;
        }
        
        .role-option {
            flex: 1;
            text-align: center;
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            background: white;
        }
        
        .role-option:hover {
            border-color: var(--primary);
            background: rgba(67, 97, 238, 0.03);
        }
        
        .role-option.active {
            border-color: var(--primary);
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }
        
        .role-option i {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e0e0e0;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .form-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .form-footer a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .alert-message {
            position: fixed;
            top: 20px;
            right: 20px;
            max-width: 350px;
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
        }
        
        .shape-1 {
            width: 300px;
            height: 300px;
            background: var(--primary);
            top: -150px;
            right: -150px;
        }
        
        .shape-2 {
            width: 200px;
            height: 200px;
            background: var(--secondary);
            bottom: -100px;
            left: -100px;
        }
        
        /* Responsive */
        @media (max-width: 576px) {
            .login-card {
                padding: 2rem 1.5rem;
            }
            
            .brand-logo h2 {
                font-size: 1.5rem;
            }
            
            .role-selector {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <!-- Floating Background Shapes -->
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <!-- Brand Logo -->
            <div class="brand-logo">
                <i class="bi bi-shield-lock"></i>
                <h2>SmartQR Login</h2>
                <p class="subtitle">Secure Access Portal</p>
            </div>
            
            <!-- Role Selector -->
            <div class="role-selector">
                <div class="role-option active" data-role="Admin">
                    <i class="bi bi-person-badge"></i>
                    <span>Admin</span>
                </div>
                <div class="role-option" data-role="Client">
                    <i class="bi bi-person-circle"></i>
                    <span>Client</span>
                </div>
            </div>

            <!-- Login Form -->
            <form method="POST" id="loginForm">
                <input type="hidden" name="role" id="selectedRole" value="Admin">
                
                <!-- Mobile Input -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="bi bi-phone me-2"></i>Mobile Number
                    </label>
                    <input type="text" 
                           name="mobile" 
                           maxlength="10" 
                           class="form-control"
                           pattern="[6-9][0-9]{9}"
                           placeholder="Enter 10-digit mobile number"
                           required>
                    <div class="form-text">Enter a valid Indian mobile number</div>
                </div>

                <!-- Password Input -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="bi bi-key me-2"></i>Password
                    </label>
                    <div class="input-group">
                        <input type="password" 
                               name="password" 
                               id="password"
                               class="form-control" 
                               placeholder="Enter your password" 
                               required>
                        <span class="input-group-text" onclick="togglePassword()">
                            <i id="eyeIcon" class="bi bi-eye-slash"></i>
                        </span>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-login w-100">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Login to Continue
                </button>
            </form>

           
        </div>
    </div>

    <script>
        // Role selector functionality
        document.querySelectorAll('.role-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove active class from all options
                document.querySelectorAll('.role-option').forEach(opt => {
                    opt.classList.remove('active');
                });
                
                // Add active class to clicked option
                this.classList.add('active');
                
                // Update hidden input value
                const role = this.getAttribute('data-role');
                document.getElementById('selectedRole').value = role;
            });
        });

        // Password toggle
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('bi-eye-slash');
                eyeIcon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('bi-eye');
                eyeIcon.classList.add('bi-eye-slash');
            }
        }

        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const mobileInput = document.querySelector('input[name="mobile"]');
            const mobilePattern = /^[6-9][0-9]{9}$/;
            
            if (!mobilePattern.test(mobileInput.value)) {
                e.preventDefault();
                showAlert('Please enter a valid Indian mobile number', 'danger');
                mobileInput.focus();
                return;
            }
            
            const passwordInput = document.getElementById('password');
            if (passwordInput.value.length < 1) {
                e.preventDefault();
                showAlert('Please enter your password', 'danger');
                passwordInput.focus();
                return;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="bi bi-arrow-clockwise spin me-2"></i> Authenticating...';
            submitBtn.disabled = true;
        });

        // Show alert message
        function showAlert(message, type) {
            // Remove existing alerts
            const existingAlert = document.querySelector('.alert-message');
            if (existingAlert) {
                existingAlert.remove();
            }
            
            // Create new alert
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show alert-message`;
            alertDiv.innerHTML = `
                <i class="bi ${type === 'danger' ? 'bi-exclamation-triangle' : 'bi-check-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alertDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        // Add spin animation
        const style = document.createElement('style');
        style.textContent = `
            .spin {
                animation: spin 1s linear infinite;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>