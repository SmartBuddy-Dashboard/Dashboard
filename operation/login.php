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
        $_SESSION['mobile']  = $user['mobile'];
        $_SESSION['role']    = $user['role'];
        $_SESSION['user_id'] = $user['id'];
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
    <title>Admin Login | Secure Access Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
<style>
    :root {
        --primary: #4361ee;
        --primary-dark: #3a56d4;
        --primary-light: #4cc9f0;
        --gradient-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --gradient-card: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        --text-dark: #2c3e50;
        --text-light: #7f8c8d;
        --success: #2ecc71;
        --danger: #e74c3c;
        --shadow-light: 0 4px 6px rgba(0, 0, 0, 0.07);
        --shadow-medium: 0 10px 25px rgba(0, 0, 0, 0.1);
        --shadow-heavy: 0 20px 50px rgba(0, 0, 0, 0.15);
        --border-radius: 20px;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--gradient-bg);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        color: var(--text-dark);
    }

    /* Floating Animation */
    @keyframes float {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
        }
        33% {
            transform: translateY(-10px) rotate(5deg);
        }
        66% {
            transform: translateY(-5px) rotate(-5deg);
        }
    }

    /* Background Elements */
    .floating-shapes {
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
        z-index: -1;
        overflow: hidden;
    }

    .shape {
        position: absolute;
        border-radius: 50%;
        opacity: 0.1;
        animation: float 15s infinite ease-in-out;
    }

    .shape-1 {
        width: 300px;
        height: 300px;
        background: var(--primary);
        top: -150px;
        right: -150px;
        animation-delay: 0s;
    }

    .shape-2 {
        width: 200px;
        height: 200px;
        background: #f72585;
        bottom: -100px;
        left: -100px;
        animation-delay: 5s;
    }

    .shape-3 {
        width: 150px;
        height: 150px;
        background: #4cc9f0;
        top: 50%;
        left: 10%;
        animation-delay: 10s;
    }

    /* Login Container */
    .login-container {
        width: 100%;
        max-width: 520px;
        animation: slideInUp 0.8s ease-out;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Login Card */
    .login-card {
        background: var(--gradient-card);
        border-radius: var(--border-radius);
        padding: 50px 40px;
        box-shadow: var(--shadow-heavy);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }

    .login-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--primary), var(--primary-light), var(--primary));
        background-size: 200% 100%;
        animation: gradientShift 3s infinite alternate;
    }

    @keyframes gradientShift {
        0% {
            background-position: 0% 50%;
        }
        100% {
            background-position: 100% 50%;
        }
    }

    .login-card::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        z-index: -1;
    }

    /* Header */
    .card-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .header-icon {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        font-size: 2.8rem;
        color: white;
        box-shadow: var(--shadow-medium);
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .header-icon:hover {
        transform: scale(1.05) rotate(10deg);
    }

    .header-icon::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.2), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }

    .header-icon:hover::before {
        transform: translateX(100%);
    }

    .card-title {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 10px;
        background: linear-gradient(135deg, var(--text-dark), var(--primary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -0.5px;
    }

    .card-subtitle {
        color: var(--text-light);
        font-size: 1rem;
        line-height: 1.6;
        opacity: 0.9;
        max-width: 320px;
        margin: 0 auto;
    }

    /* Form Styling - FIXED FOR ICONS */
    .form-group {
        margin-bottom: 28px;
        position: relative;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.95rem;
        letter-spacing: -0.2px;
    }

    .form-label i {
        color: var(--primary);
        font-size: 1.1rem;
        width: 24px;
    }

    .input-wrapper {
        position: relative;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 16px 50px 16px 50px; /* Equal padding on both sides for icons */
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
        height: 56px;
        font-weight: 500;
        color: var(--text-dark);
        width: 100%;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
        transform: translateY(-2px);
    }

    /* Left Icons (Lock & Phone) */
    .left-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
        font-size: 1.2rem;
        transition: color 0.3s ease;
        pointer-events: none;
        z-index: 2;
    }

    .form-control:focus ~ .left-icon {
        color: var(--primary);
    }

    /* Right Icons (Eye toggle) */
    .right-icon {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
        cursor: pointer;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        background: none;
        border: none;
        padding: 0;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
    }

    .right-icon:hover {
        color: var(--primary);
        transform: translateY(-50%) scale(1.1);
    }

    /* Special styling for password field */
    .password-field .form-control {
        padding-right: 50px; /* Extra space for eye icon */
    }

    /* Validation */
    .validation-error {
        color: var(--danger);
        font-size: 0.85rem;
        margin-top: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        animation: shake 0.5s ease;
        padding: 8px 12px;
        background: rgba(231, 76, 60, 0.08);
        border-radius: 8px;
        border-left: 3px solid var(--danger);
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    .validation-success {
        color: var(--success);
        font-size: 0.85rem;
        margin-top: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        background: rgba(46, 204, 113, 0.08);
        border-radius: 8px;
        border-left: 3px solid var(--success);
    }

    /* Submit Button */
    .submit-btn {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border: none;
        border-radius: 12px;
        padding: 18px;
        font-size: 1.1rem;
        font-weight: 700;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 10px;
        box-shadow: var(--shadow-medium);
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
    }

    .submit-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.6s ease;
    }

    .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-heavy);
    }

    .submit-btn:hover::before {
        left: 100%;
    }

    .submit-btn:active {
        transform: translateY(0);
    }

    .submit-btn i {
        font-size: 1.2rem;
        transition: transform 0.3s ease;
    }

    .submit-btn:hover i {
        transform: translateX(5px);
    }

    /* Security Badge */
    .security-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-top: 30px;
        padding: 15px;
        background: rgba(67, 97, 238, 0.08);
        border-radius: 12px;
        font-size: 0.9rem;
        color: var(--text-light);
        border: 1px solid rgba(67, 97, 238, 0.1);
        backdrop-filter: blur(5px);
    }

    .security-badge i {
        color: var(--primary);
        font-size: 1.1rem;
    }

    /* Copyright */
    .copyright {
        text-align: center;
        margin-top: 30px;
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    /* Loading Animation */
    .loading {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .login-card {
            padding: 40px 30px;
        }
        
        .card-title {
            font-size: 1.8rem;
        }
        
        .header-icon {
            width: 80px;
            height: 80px;
            font-size: 2.5rem;
        }
        
        .form-control {
            padding: 14px 45px 14px 45px;
            height: 52px;
        }
        
        .left-icon,
        .right-icon {
            font-size: 1.1rem;
        }
        
        .left-icon {
            left: 15px;
        }
        
        .right-icon {
            right: 15px;
        }
    }

    @media (max-width: 576px) {
        body {
            padding: 15px;
        }
        
        .login-card {
            padding: 35px 25px;
            border-radius: 16px;
        }
        
        .card-title {
            font-size: 1.6rem;
        }
        
        .card-subtitle {
            font-size: 0.95rem;
        }
        
        .header-icon {
            width: 70px;
            height: 70px;
            font-size: 2rem;
        }
        
        .form-control {
            padding: 12px 40px 12px 40px;
            height: 48px;
        }
        
        .left-icon {
            left: 12px;
            font-size: 1rem;
        }
        
        .right-icon {
            right: 12px;
            font-size: 1rem;
        }
        
        .submit-btn {
            padding: 16px;
            font-size: 1rem;
        }
        
        .shape-1,
        .shape-2,
        .shape-3 {
            display: none;
        }
    }
</style>
</head>
<body>
    <!-- Background Elements -->
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

   <!-- Login Container -->
<div class="login-container">
    <div class="login-card">
        <div class="card-header">
            <div class="header-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <h2 class="card-title">Administrator Login</h2>
        </div>

        <form method="POST" action="" id="loginForm">
            <!-- Mobile Number -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-mobile-alt"></i>
                    Mobile Number
                </label>
                <div class="input-wrapper">
                    <i class="fas fa-phone left-icon"></i>
                    <input type="tel" 
                           name="mobile" 
                           id="mobile" 
                           maxlength="10" 
                           class="form-control"
                           pattern="[6-9][0-9]{9}"
                           placeholder="Enter 10-digit mobile number"
                           required
                           oninput="validateMobile()">
                </div>
                <div id="mobileError" class="validation-error d-none">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Mobile must start with 6-9 and be 10 digits</span>
                </div>
                <div id="mobileSuccess" class="validation-success d-none">
                    <i class="fas fa-check-circle"></i>
                    <span>Valid mobile number format</span>
                </div>
            </div>

            <!-- Password -->
            <div class="form-group password-field">
                <label class="form-label">
                    <i class="fas fa-key"></i>
                    Password
                </label>
                <div class="input-wrapper">
                    <i class="fas fa-lock left-icon"></i>
                    <input type="password" 
                           name="password" 
                           maxlength="8" 
                           class="form-control" 
                           id="password" 
                           placeholder="Enter your password" 
                           required>
                    <button type="button" class="right-icon" onclick="togglePassword()">
                        <i id="eyeIcon" class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Hidden Role -->
            <input type="hidden" name="role" value="Admin">

            <!-- Submit Button -->
            <button type="submit" class="submit-btn" id="submitBtn">
                <i class="fas fa-sign-in-alt"></i>
                <span>Sign In to Dashboard</span>
            </button>
        </form>
    </div>
    
    <p class="copyright">© 2024 Secure Access Portal. All rights reserved.</p>
</div>

    <script>
        // Mobile Validation
        const mobileInput = document.getElementById("mobile");
        const mobileError = document.getElementById("mobileError");
        const mobileSuccess = document.getElementById("mobileSuccess");
        const mobileRegex = /^[6-9][0-9]{9}$/;

        function validateMobile() {
            const value = mobileInput.value.trim();
            
            // Allow only numbers
            mobileInput.value = value.replace(/[^0-9]/g, "");
            
            if (!mobileRegex.test(value)) {
                mobileError.classList.remove("d-none");
                mobileSuccess.classList.add("d-none");
                mobileInput.classList.add("is-invalid");
                mobileInput.classList.remove("is-valid");
                return false;
            } else {
                mobileError.classList.add("d-none");
                mobileSuccess.classList.remove("d-none");
                mobileInput.classList.remove("is-invalid");
                mobileInput.classList.add("is-valid");
                return true;
            }
        }

        // Toggle Password Visibility
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const eyeIcon = document.getElementById("eyeIcon");
            
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }

        // Form Submission
        const loginForm = document.getElementById("loginForm");
        const submitBtn = document.getElementById("submitBtn");

        loginForm.addEventListener("submit", function(e) {
            if (!validateMobile()) {
                e.preventDefault();
                mobileInput.focus();
                mobileInput.style.animation = "shake 0.5s ease";
                setTimeout(() => {
                    mobileInput.style.animation = "";
                }, 500);
            } else {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<div class="loading"></div><span>Authenticating...</span>';
                
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i><span>Sign In to Dashboard</span>';
                    }
                }, 2000);
            }
        });

        // Add event listeners
        mobileInput.addEventListener("blur", validateMobile);
        mobileInput.addEventListener("input", validateMobile);

        // Enter key support
        document.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                e.preventDefault();
                loginForm.dispatchEvent(new Event("submit"));
            }
        });

        // Focus on mobile input
        window.addEventListener("load", function() {
            mobileInput.focus();
        });

        // Form validation on page load
        validateMobile();
    </script>
</body>
</html>