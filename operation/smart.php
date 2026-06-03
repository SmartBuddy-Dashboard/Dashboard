<?php
include("include/config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $mobile   = mysqli_real_escape_string($conn, $_POST['mobile']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

   

   /* ============================================
   1️⃣ CHECK IN tblusers TABLE
============================================ */

$query1 = "SELECT * FROM tblusers 
           WHERE mobile = '$mobile'
           AND password = '$password'
           AND status = 1";

$result1 = mysqli_query($conn, $query1);

if ($result1 && mysqli_num_rows($result1) == 1) {

    $row = mysqli_fetch_assoc($result1);

    // Update login status
    mysqli_query($conn, "UPDATE tblusers 
                         SET is_logged_in = 1 
                         WHERE mobile = '$mobile'");

    $_SESSION['mobile'] = $row['mobile'];
    $_SESSION['role']   = $row['role'];

    // Direct redirect (NO ROLE CHECK)
    header("Location: dashboard.php");
    exit();
}

    /* ============================================
       2️⃣ IF NOT FOUND → CHECK IN clients TABLE
    ============================================ */

    $query2 = "SELECT * FROM clients
               WHERE contact_mobile = '$mobile'
               AND password = '$password'";

    $result2 = mysqli_query($conn, $query2);

    if ($result2 && mysqli_num_rows($result2) == 1) {

        $row = mysqli_fetch_assoc($result2);

        $_SESSION['mobile']      = $row['contact_mobile'];
        $_SESSION['client_name'] = $row['client_name'];
        $_SESSION['role']        = "Client";

        header("Location: ../smart6Dec/dashboard.php");
        exit();
    }

    /* ============================================
       3️⃣ NOT FOUND IN BOTH TABLES
    ============================================ */

    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showAlert('❌ Invalid credentials', 'danger');
            });
          </script>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartQR Login | Secure Access Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8fafc;
            background-image: radial-gradient(circle at 10% 20%, rgba(233, 240, 253, 0.7) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(243, 232, 255, 0.7) 0%, transparent 20%);
            padding: 20px;
            position: relative;
        }

        /* Alert Message Styles */
        .alert-message {
            position: fixed;
            top: 20px;
            right: 20px;
            max-width: 350px;
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-left: 4px solid #f44336;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-left: 4px solid #4CAF50;
        }

        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
            border-left: 4px solid #2196F3;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            border-left: 4px solid #FF9800;
        }

        .alert-message i {
            font-size: 18px;
        }

        .alert-close {
            margin-left: auto;
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            font-size: 16px;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .alert-close:hover {
            opacity: 1;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes slideOut {
            from { transform: translateX(100%); opacity: 1; }
            to { transform: translateX(0); opacity: 0; }
        }

        .login-container {
            display: flex;
            max-width: 1000px;
            width: 100%;
            min-height: 600px;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .login-container:hover {
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.12);
        }

        .brand-section {
            flex: 1;
            background: linear-gradient(145deg, #4ff568 0%, #3a56d4 100%);
            padding: 48px 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .brand-section::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            top: -100px;
            right: -100px;
        }

        .brand-section::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.03);
            bottom: -80px;
            left: -80px;
        }

        .brand-header {
            z-index: 2;
            position: relative;
            margin-bottom: 40px;
        }

        .company-logo {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 40px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 230px;
            height: 120px;
            border-radius: 16px;
            padding: 5px;
            transition: all 0.3s ease;
        }

        .logo-container img {
            width: 100%;
            height: 100%;
        }

        .logo-text {
            display: flex;
            flex-direction: column;
        }

        .logo-text h1 {
            margin-top: 80px;
            margin-left: -30px;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(180deg, #ffffff 0%, rgba(255, 255, 255, 0.9) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 10px rgba(255, 255, 255, 0.2);
        }

        .logo-text .tagline {
            font-size: 14px;
            font-weight: 400;
            opacity: 0.9;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.9);
        }

        .brand-content {
            z-index: 2;
            position: relative;
        }

        .brand-content h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 16px;
            line-height: 1.2;
        }

        .brand-content p {
            font-size: 16px;
            font-weight: 300;
            opacity: 0.9;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .feature-list {
            list-style: none;
            margin-top: 32px;
        }

        .feature-list li {
            display: flex;
            align-items: center;
            margin-bottom: 18px;
            font-size: 15px;
            font-weight: 400;
        }

        .feature-list i {
            margin-right: 12px;
            background: rgba(255, 255, 255, 0.15);
            padding: 6px;
            border-radius: 6px;
            font-size: 14px;
        }

        .login-section {
            flex: 1;
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            margin-bottom: 32px;
            text-align: center;
        }

        .login-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #64748b;
            font-size: 15px;
        }

        .input-group {
            margin-bottom: 24px;
        }

        .input-label {
            display: flex;
            align-items: center;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
            font-size: 15px;
        }

        .input-label i {
            margin-right: 8px;
            font-size: 16px;
            color: #64748b;
        }

        .input-field {
            width: 100%;
            padding: 16px 20px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 15px;
            color: #1e293b;
            transition: all 0.3s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: #4f6df5;
            box-shadow: 0 0 0 3px rgba(79, 109, 245, 0.1);
        }

        .input-field.error {
            border-color: #f44336;
            box-shadow: 0 0 0 3px rgba(244, 67, 54, 0.1);
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 18px;
        }

        .forgot-password {
            text-align: right;
            margin-top: 8px;
        }

        .forgot-password a {
            color: #4f6df5;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: #3a56d4;
            text-decoration: underline;
        }

        .login-button {
            width: 100%;
            padding: 18px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(90deg, #4f6df5 0%, #3a56d4 100%);
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .login-button:hover {
            background: linear-gradient(90deg, #3a56d4 0%, #2a44c4 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 109, 245, 0.25);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .login-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 450px;
            }

            .brand-section {
                padding: 32px 24px;
            }

            .brand-content h2 {
                font-size: 20px;
            }

            .login-section {
                padding: 32px 24px;
            }

            .company-logo {
                gap: 16px;
                margin-bottom: 30px;
            }

            .logo-container {
                width: 260px;
                height: 190px;
                border-radius: 12px;
            }

            .logo-text h1 {
                font-size: 24px;
            }

            .logo-text .tagline {
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            .company-logo {
                gap: 12px;
            }

            .logo-container {
                width: 200px;
                height: 110px;
                padding: 12px;
            }

            .logo-text h1 {
                font-size: 20px;
            }

            .brand-content h2 {
                font-size: 20px;
            }

            .alert-message {
                max-width: calc(100% - 40px);
                right: 20px;
                left: 20px;
            }
        }
    </style>
</head>

<body>
    <!-- Alert messages will appear here -->
    
    <div class="login-container">
        <div class="brand-section">
            <div>
                <div class="brand-header">
                    <div class="company-logo">
                        <div class="logo-container">
                            <img src="smartbuddy.png" alt="SmartBuddy Logo">
                        </div>
                        <div class="logo-text">
                            <h1>SmartBuddy</h1>
                        </div>
                    </div>
                </div>

                <div class="brand-content">
                    <h2>Secure Access Portal</h2>
                    <p>Enterprise-grade authentication system with advanced security features and QR-based access management.</p>

                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Multi-factor authentication</li>
                        <li><i class="fas fa-check-circle"></i> Encrypted session management</li>
                        <li><i class="fas fa-check-circle"></i> Real-time access monitoring</li>
                        <li><i class="fas fa-check-circle"></i> Role-based permissions</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Login Form Section -->
        <div class="login-section">
            <div class="login-header">
                <h2>Welcome Back</h2>
                <p>Please enter your credentials to continue</p>
            </div>

            <form method="POST" id="loginForm">
                <div class="input-group">
                    <div class="input-label">
                        <i class="fas fa-mobile-alt"></i> Mobile Number
                    </div>
                    <input type="text" 
                           name="mobile" 
                           maxlength="10" 
                           class="input-field" 
                           pattern="[6-9][0-9]{9}"
                           placeholder="Enter 10-digit mobile number"
                           required>
                </div>

                <div class="input-group">
                    <div class="input-label">
                        <i class="fas fa-key"></i> Password
                    </div>
                    <div class="password-container">
                        <input type="password" 
                               name="password" 
                               class="input-field" 
                               placeholder="Enter your password" 
                               id="password"
                               maxlength="10" 
                               required>
                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                    <div class="forgot-password">
                        <a href="client/forget_password.php"><i class="fas fa-lock"></i> Forgot Password?</a>
                    </div>
                </div>

                <button type="submit" class="login-button" id="loginButton">
                    <span>Login to Continue</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Password visibility toggle
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('password');

            if (togglePassword && passwordField) {
                togglePassword.addEventListener('click', function () {
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);

                    // Toggle eye icon
                    const eyeIcon = this.querySelector('i');
                    if (type === 'text') {
                        eyeIcon.classList.remove('fa-eye');
                        eyeIcon.classList.add('fa-eye-slash');
                    } else {
                        eyeIcon.classList.remove('fa-eye-slash');
                        eyeIcon.classList.add('fa-eye');
                    }
                });
            }

            // Form validation and submission
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');

            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form inputs
                const mobileInput = this.querySelector('input[name="mobile"]');
                const passwordInput = this.querySelector('input[name="password"]');
                
                // Reset previous errors
                mobileInput.classList.remove('error');
                passwordInput.classList.remove('error');
                
                // Validate mobile number
                const mobilePattern = /^[6-9][0-9]{9}$/;
                if (!mobilePattern.test(mobileInput.value)) {
                    mobileInput.classList.add('error');
                    showAlert('Please enter a valid Indian mobile number (10 digits, starting with 6-9)', 'danger');
                    mobileInput.focus();
                    return false;
                }
                
                // Validate password
                if (passwordInput.value.length < 1) {
                    passwordInput.classList.add('error');
                    showAlert('Please enter your password', 'danger');
                    passwordInput.focus();
                    return false;
                }
                
                // Show loading state
                const originalText = loginButton.innerHTML;
                loginButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Authenticating...';
                loginButton.disabled = true;
                
                // Submit the form
                this.submit();
                
                // Reset button after 5 seconds if submission fails
                setTimeout(() => {
                    loginButton.innerHTML = originalText;
                    loginButton.disabled = false;
                }, 5000);
                
                return true;
            });

            // Input focus effects
            const inputFields = document.querySelectorAll('.input-field');
            inputFields.forEach(input => {
                input.addEventListener('focus', function () {
                    this.classList.add('focused');
                });

                input.addEventListener('blur', function () {
                    this.classList.remove('focused');
                });
                
                // Remove error class on input
                input.addEventListener('input', function () {
                    this.classList.remove('error');
                });
            });
        });

        // Show alert message function
        function showAlert(message, type = 'info') {
            // Remove existing alerts
            const existingAlert = document.querySelector('.alert-message');
            if (existingAlert) {
                existingAlert.remove();
            }
            
            // Type to icon mapping
            const icons = {
                'danger': 'exclamation-triangle',
                'success': 'check-circle',
                'warning': 'exclamation-circle',
                'info': 'info-circle'
            };
            
            // Create new alert
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert-message alert-${type}`;
            alertDiv.innerHTML = `
                <i class="fas fa-${icons[type] || 'info-circle'}"></i>
                <span>${message}</span>
                <button class="alert-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            document.body.appendChild(alertDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentElement) {
                    alertDiv.style.animation = 'slideOut 0.3s ease-out';
                    setTimeout(() => {
                        if (alertDiv.parentElement) {
                            alertDiv.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }
    </script>
</body>
</html>