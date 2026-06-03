<?php
include("include/config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $mobileRaw   = $_POST['mobile'] ?? '';
    $passwordRaw = $_POST['password'] ?? '';
    
    $mobile   = mysqli_real_escape_string($conn, $mobileRaw);
    $password = mysqli_real_escape_string($conn, $passwordRaw);

   

   /* ============================================
   1️⃣ CHECK IN tblusers TABLE
============================================ */

$query1 = "SELECT * FROM tblusers 
           WHERE mobile = '$mobile'
           AND status = 1";

$result1 = mysqli_query($conn, $query1);

if ($result1 && mysqli_num_rows($result1) == 1) {

    $row = mysqli_fetch_assoc($result1);

    // Enterprise Security: Support legacy plain-text while transitioning to BCRYPT
    $validPassword = false;
    // FIX: Compare against $passwordRaw, not the escaped $password
    if (password_verify($passwordRaw, $row['password'])) {
        $validPassword = true;
    } elseif ($passwordRaw === $row['password']) {
        // Auto-upgrade plain text to hash on first login
        $newHash = password_hash($passwordRaw, PASSWORD_BCRYPT);
        mysqli_query($conn, "UPDATE tblusers SET password = '$newHash' WHERE mobile = '$mobile'");
        $validPassword = true;
    }

    if ($validPassword) {
        // Update login status
        mysqli_query($conn, "UPDATE tblusers 
                             SET is_logged_in = 1 
                             WHERE mobile = '$mobile'");

        $_SESSION['mobile'] = $row['mobile'];
        $_SESSION['role']   = $row['role'];
        $_SESSION['name']   = $row['name'] ?? 'Admin';

        $host=$_SERVER['HTTP_HOST'];
		$uip=$_SERVER['REMOTE_ADDR'];
		$log=mysqli_query($conn,"insert into userlog(username, Role, userip, loginTime, status) values('$mobile', 'Admin' ,'$uip', now(), '1')");

        if ($row['role'] == 'Admin') {
            header("Location: Admin/dashboard.php");
        }
        elseif ($row['role'] == 'Operation') {
            header("Location: operation/dashboard.php");
        }
        exit();
    }
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
        $_SESSION['name']        = $row['client_name'];
        $_SESSION['role']        = "Client";

        $host=$_SERVER['HTTP_HOST'];
		$uip=$_SERVER['REMOTE_ADDR'];
		$log=mysqli_query($conn,"insert into userlog(username, Role, userip, loginTime, status) values('$mobile', 'Client' ,'$uip', now(), '1')");

        header("Location: client/dashboard.php");
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
    <title>SmartBuddy | Secure Industrial Portal</title>
    <!-- Include Global Premium Industrial CSS -->
    <link rel="stylesheet" href="assets/css/premium-industrial.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Login Specific Styles Overriding/Supplementing Global */
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .login-wrapper {
            width: 100%;
            max-width: 1100px;
            display: flex;
            gap: 40px;
            padding: 20px;
            z-index: 10;
        }

        .brand-showcase {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px;
            position: relative;
        }

        .brand-showcase::before {
            content: '';
            position: absolute;
            left: -100px;
            top: 50%;
            transform: translateY(-50%);
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(0,240,255,0.15) 0%, transparent 70%);
            z-index: -1;
            filter: blur(40px);
        }

        .logo-container {
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logo-container img {
            width: 100px;
            filter: drop-shadow(0 0 10px rgba(0,240,255,0.5));
        }

        .brand-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #fff, #8b949e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-subtitle {
            font-size: 1.2rem;
            color: var(--text-muted);
            margin-bottom: 40px;
            max-width: 400px;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--text-main);
            font-size: 0.95rem;
            background: rgba(255,255,255,0.03);
            padding: 15px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.05);
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            border-color: var(--border-highlight);
            transform: translateY(-2px);
            background: rgba(0,240,255,0.05);
        }

        .feature-item i {
            color: var(--primary-color);
            font-size: 1.2rem;
            text-shadow: var(--glow-primary);
        }

        .login-panel {
            width: 450px;
            padding: 50px 40px;
            position: relative;
        }
        
        .login-panel::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.1);
            pointer-events: none;
            background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, transparent 100%);
        }

        .login-header {
            margin-bottom: 35px;
            text-align: center;
        }

        .login-header h2 {
            font-size: 2rem;
            margin-bottom: 10px;
            color: var(--text-main);
        }

        .login-header p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .input-group {
            margin-bottom: 25px;
            position: relative;
        }

        .input-label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .input-field-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-field-container i {
            position: absolute;
            left: 16px;
            color: var(--text-muted);
            transition: color 0.3s ease;
        }

        .premium-input {
            width: 100%;
            padding: 14px 16px 14px 45px !important; /* Make room for icon */
        }

        .premium-input:focus + i {
            color: var(--primary-color);
            text-shadow: var(--glow-primary);
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 0;
        }

        .toggle-password:hover {
            color: var(--primary-color);
        }

        .forgot-password {
            text-align: right;
            margin-top: 10px;
        }

        .forgot-password a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        /* Alert Messages */
        .alert-message {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            background: rgba(22, 27, 34, 0.9);
            backdrop-filter: blur(10px);
            border-left: 4px solid var(--primary-color);
            padding: 15px 25px;
            border-radius: 8px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            animation: slideIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        .alert-danger { border-left-color: var(--danger); }
        .alert-success { border-left-color: var(--success); }
        .alert-warning { border-left-color: var(--warning); }

        @keyframes slideIn {
            from { transform: translateX(120%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @media (max-width: 992px) {
            .login-wrapper {
                flex-direction: column;
                align-items: center;
                gap: 20px;
            }
            .brand-showcase {
                text-align: center;
                padding: 20px;
            }
            .brand-showcase::before {
                left: 50%;
                top: 0;
                transform: translateX(-50%);
            }
            .logo-container {
                justify-content: center;
            }
            .brand-subtitle {
                margin: 0 auto 30px auto;
            }
        }

        @media (max-width: 500px) {
            .login-panel {
                width: 100%;
                padding: 30px 20px;
            }
            .feature-grid {
                grid-template-columns: 1fr;
            }
            .brand-title {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body class="industrial-bg">

    <div class="login-wrapper">
        <!-- Brand Showcase -->
        <div class="brand-showcase">
            <div class="logo-container">
                <img src="smartbuddy.png" alt="SmartBuddy Logo">
            </div>
            <h1 class="brand-title">Enterprise<br><span class="text-glow">Command Center</span></h1>
            <p class="brand-subtitle">Secure, high-performance monitoring and management portal for industrial operations.</p>
            
            <div class="feature-grid">
                <div class="feature-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>Military-Grade Auth</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-microchip"></i>
                    <span>Real-time Telemetry</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-network-wired"></i>
                    <span>Distributed Nodes</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-bolt"></i>
                    <span>High Availability</span>
                </div>
            </div>
        </div>

        <!-- Login Form -->
        <div class="login-panel glass-panel">
            <div class="login-header">
                <h2>System Access</h2>
                <p>Authenticate to initialize connection</p>
            </div>

            <form method="POST" id="loginForm">
                <div class="input-group">
                    <label class="input-label">Terminal ID (Mobile)</label>
                    <div class="input-field-container">
                        <input type="text" name="mobile" maxlength="10" class="premium-input" pattern="[6-9][0-9]{9}" placeholder="Enter 10-digit mobile" required>
                        <i class="fas fa-terminal"></i>
                    </div>
                </div>

                <div class="input-group">
                    <label class="input-label">Access Key (Password)</label>
                    <div class="input-field-container">
                        <input type="password" name="password" id="password" class="premium-input" placeholder="Enter secure key" required>
                        <i class="fas fa-lock"></i>
                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                    <div class="forgot-password">
                        <a href="forget_password.php">Recover Access Key?</a>
                    </div>
                </div>

                <button type="submit" class="btn-premium btn-login" id="loginButton">
                    <span>INITIALIZE <i class="fas fa-chevron-right" style="margin-left: 5px; font-size: 0.8em;"></i></span>
                </button>
            </form>
        </div>
    </div>

    <script>
        // Password Visibility Toggle
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
                icon.style.color = 'var(--primary-color)';
            } else {
                passwordField.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
                icon.style.color = 'var(--text-muted)';
            }
        });

        // Form Submission Handling
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginButton');
            btn.innerHTML = '<span>AUTHENTICATING... <i class="fas fa-circle-notch fa-spin"></i></span>';
            btn.style.opacity = '0.8';
            btn.style.pointerEvents = 'none';
        });

        // Alert Function mapped to PHP output
        function showAlert(message, type = 'info') {
            const existing = document.querySelector('.alert-message');
            if(existing) existing.remove();

            const icons = {
                'danger': 'exclamation-triangle',
                'success': 'check-circle',
                'warning': 'exclamation-circle',
                'info': 'info-circle'
            };

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert-message alert-${type}`;
            alertDiv.innerHTML = `
                <i class="fas fa-${icons[type] || 'info-circle'}" style="font-size: 1.5rem; color: ${type === 'danger' ? 'var(--danger)' : 'var(--primary-color)'}"></i>
                <div>
                    <strong style="display:block; margin-bottom: 2px;">System Notification</strong>
                    <span style="font-size: 0.9rem; color: #ddd;">${message}</span>
                </div>
            `;

            document.body.appendChild(alertDiv);

            setTimeout(() => {
                alertDiv.style.animation = 'slideIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) reverse forwards';
                setTimeout(() => alertDiv.remove(), 400);
            }, 4000);
        }
    </script>
</body>
</html>