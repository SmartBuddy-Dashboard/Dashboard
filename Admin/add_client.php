<?php 
require_once('include/header.php');
require_once('include/navbar.php');
require_once('include/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data and sanitize
    $client_name     = mysqli_real_escape_string($conn, $_POST['client_name']);
    $client_phone    = mysqli_real_escape_string($conn, $_POST['client_phone']);
    $client_address  = mysqli_real_escape_string($conn, $_POST['client_address']);
    $client_website  = mysqli_real_escape_string($conn, $_POST['client_website']);
    $state           = mysqli_real_escape_string($conn, $_POST['state']);
    $district        = mysqli_real_escape_string($conn, $_POST['district']);
    $city            = mysqli_real_escape_string($conn, $_POST['city']);
    $client_type     = mysqli_real_escape_string($conn, $_POST['client_type']);
    $contact_name    = mysqli_real_escape_string($conn, $_POST['contact_name']);
    $contact_mobile  = mysqli_real_escape_string($conn, $_POST['contact_mobile']);
    $contact_email   = mysqli_real_escape_string($conn, $_POST['contact_email']);
    $password        = mysqli_real_escape_string($conn, $_POST['password']);

    /* ------------------------------
       CLIENT LOGO UPLOAD
    --------------------------------*/
    $client_logo = "";

    if (!empty($_FILES['client_logo']['name'])) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        /* ===== GET LAST ID + 1 ===== */
        $res = mysqli_query($conn, "SELECT MAX(id) AS last_id FROM clients");
        $row = mysqli_fetch_assoc($res);
        $next_id = ($row['last_id'] ?? 0) + 1;

        $allowedTypes = ['jpg', 'jpeg', 'png'];
        $fileExt = strtolower(pathinfo($_FILES['client_logo']['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowedTypes)) {
            echo "<script>alert('Only JPG, JPEG, PNG files are allowed');</script>";
            exit;
        }

        /* ===== CLEAN CLIENT NAME ===== */
        $safeClientName = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($client_name));

        /* ===== FINAL FILE NAME ===== */
        $fileName = $next_id . '_' . $safeClientName . '.' . $fileExt;
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['client_logo']['tmp_name'], $targetPath)) {
            $client_logo = mysqli_real_escape_string($conn, $fileName);
        } else {
            echo "<script>alert('Logo upload failed');</script>";
            exit;
        }
    }

    /* ------------------------------
       INSERT QUERY
    --------------------------------*/
    $sql = "INSERT INTO clients 
            (client_name, client_phone, client_address, client_website, client_state, clinet_district, client_city, client_type, contact_person, contact_mobile, password, contact_email, client_logo)
            VALUES 
            ('$client_name', '$client_phone', '$client_address', '$client_website', '$state', '$district', '$city', '$client_type', '$contact_name', '$contact_mobile', '$password', '$contact_email', '$client_logo')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Client details inserted successfully'); window.location='list_client.php';</script>";
        exit;
    } else {
        echo "<pre>Error inserting client: " . mysqli_error($conn) . "</pre>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .page-title { 
            font-size: 26px; 
            color: var(--text-main) !important; 
            font-weight: 700; 
            display: flex; 
            align-items: center; 
            gap: 12px; 
        }
        .page-title i { 
            color: #00d9a5; 
            font-size: 28px; 
        }
        
        .form-card {
            background: var(--bg-card); 
            border-radius: 24px; 
            padding: 40px; 
            box-shadow: var(--card-shadow);
            position: relative; 
            overflow: hidden;
        }
        .form-card::before {
            content: ''; 
            position: absolute; 
            top: 0; 
            left: 0; 
            right: 0; 
            height: 5px;
            background: linear-gradient(90deg, #00d9a5, #00b894, #3498db);
        }
        
        .form-header { 
            display: flex; 
            align-items: center; 
            gap: 15px; 
            margin-bottom: 35px; 
            padding-bottom: 20px; 
            border-bottom: 2px solid #f0f0f0; 
        }
        .form-icon { 
            width: 55px; 
            height: 55px; 
            background: linear-gradient(135deg, #00d9a5, #00b894); 
            border-radius: 14px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            box-shadow: 0 8px 25px rgba(0,217,165,0.3); 
        }
        .form-icon i { 
            color: #fff; 
            font-size: 24px; 
        }
        .form-header h2 { 
            font-size: 22px; 
            color: var(--text-main) !important; 
            margin: 0; 
        }
        .form-header p { 
            color: #888; 
            font-size: 13px; 
            margin: 5px 0 0; 
        }
        
        .form-grid { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 25px; 
        }
        .form-group { 
            position: relative; 
        }
        .form-group.full { 
            grid-column: span 2; 
        }
        
        .form-group label {
            display: block; 
            color: var(--text-main) !important; 
            font-weight: 600; 
            margin-bottom: 10px; 
            font-size: 13px;
            display: flex; 
            align-items: center; 
            gap: 8px;
        }
        .form-group label i { 
            color: #00d9a5; 
            font-size: 14px; 
        }
        .form-group label .required { 
            color: #e74c3c; 
        }
        
        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid var(--input-border);
            border-radius: 4px;
            font-size: 14px;
            background: var(--input-bg);
            color: var(--text-main);
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #86b7fe;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .form-group input.error-field,
        .form-group select.error-field,
        .form-group textarea.error-field {
            border-color: #dc3545;
            background-color: #fff8f8;
        }
        
        .form-group input.error-field:focus,
        .form-group select.error-field:focus,
        .form-group textarea.error-field:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }
        
        .form-group .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
        
        .form-group .hint { 
            color: #888; 
            font-size: 12px; 
            margin-top: 8px; 
            display: flex; 
            align-items: center; 
            gap: 5px; 
        }
        .form-group .hint i { 
            color: #f39c12; 
        }
        
        .section-divider {
            grid-column: span 2; 
            display: flex; 
            align-items: center; 
            gap: 15px;
            margin: 15px 0; 
            color: #888; 
            font-size: 12px; 
            text-transform: uppercase; 
            letter-spacing: 1px;
        }
        .section-divider::before, 
        .section-divider::after {
            content: ''; 
            flex: 1; 
            height: 2px; 
            background: linear-gradient(90deg, transparent, #e8e8e8, transparent);
        }
        .section-divider i { 
            color: #00d9a5; 
        }
        
        .form-actions {
            display: flex; 
            justify-content: flex-end; 
            gap: 15px; 
            margin-top: 40px;
            padding-top: 30px; 
            border-top: 2px solid #f0f0f0;
        }
        .btn {
            padding: 14px 35px; 
            border: none; 
            border-radius: 12px; 
            cursor: pointer;
            font-size: 14px; 
            font-weight: 600; 
            transition: all 0.3s; 
            display: flex; 
            align-items: center; 
            gap: 10px;
        }
        .btn-submit {
            background: linear-gradient(135deg, #00d9a5, #00b894); 
            color: #fff;
            box-shadow: 0 8px 25px rgba(0,217,165,0.3);
        }
        .btn-submit:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 12px 35px rgba(0,217,165,0.4); 
        }
        .btn-submit:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        .btn-reset {
            background: linear-gradient(135deg, #f39c12, #e67e22); 
            color: #fff;
            box-shadow: 0 8px 25px rgba(243,156,18,0.3);
        }
        .btn-reset:hover { 
            transform: translateY(-3px); 
        }
        .btn-back {
            background: linear-gradient(135deg, #6c757d, #5a6268); 
            color: #fff;
            box-shadow: 0 8px 25px rgba(108,117,125,0.3);
        }
        .btn-back:hover { 
            transform: translateY(-3px); 
        }
        
        @media (max-width: 992px) {
            .form-card {
                padding: 30px 25px;
            }
        }

        @media (max-width: 768px) {
            .form-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .form-header h2 {
                font-size: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .form-group.full,
            .section-divider {
                grid-column: span 1;
            }

            .form-group input,
            .form-group select,
            .form-group textarea {
                font-size: 15px;
                padding: 10px;
            }

            .section-divider {
                margin: 25px 0 10px;
                font-size: 11px;
            }

            .form-actions {
                flex-direction: column;
                gap: 12px;
            }

            .form-actions .btn {
                width: 100%;
                justify-content: center;
                padding: 14px;
            }
        }

        @media (max-width: 480px) {
            .form-card {
                padding: 20px 15px;
                border-radius: 18px;
            }

            .form-icon {
                width: 45px;
                height: 45px;
            }

            .form-icon i {
                font-size: 20px;
            }

            .form-header h2 {
                font-size: 18px;
            }

            .form-header p {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <form id="clientForm" method="POST" enctype="multipart/form-data" novalidate>
                            <div class="form-card">
                                <div class="form-header">
                                    <div class="form-icon"><i class="fas fa-building"></i></div>
                                    <div>
                                        <h2>Client Information</h2>
                                        <p>Fill in the details below to add a new client</p>
                                    </div>
                                </div>
                                
                                <div class="form-grid">
                                    <!-- Basic Info -->
                                    <div class="form-group">
                                        <label><i class="fas fa-user"></i> Client Name <span class="required">*</span></label>
                                        <input type="text" name="client_name" id="client_name" placeholder="Enter Client name" maxlength="150" class="form-control">
                                        <div class="error-message" id="client_name_error">Client name is required</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label><i class="fas fa-phone"></i> Client Phone <span class="required">*</span></label>
                                        <input type="text" name="client_phone" id="client_phone" placeholder="Enter Client Phone No." maxlength="10" class="form-control">
                                        <div class="error-message" id="client_phone_error">Valid 10-digit phone number starting with 6-9 is required</div>
                                    </div>
                                    
                                    <div class="form-group full">
                                        <label><i class="fas fa-map-marker-alt"></i> Client Address<span class="required">*</span></label>
                                        <textarea name="client_address" id="client_address" placeholder="Enter Address" maxlength="200" class="form-control" rows="1"></textarea>
                                        <div class="error-message" id="client_address_error">Client address is required</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label><i class="fas fa-globe"></i> Client Website </label>
                                        <input type="text" name="client_website" id="client_website" placeholder="https://www.example.com" maxlength="200" class="form-control">
                                        <div class="error-message" id="client_website_error">Please enter a valid website URL</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label><i class="fas fa-tags"></i> Client Type <span class="required">*</span></label>
                                        <select class="form-control" name="client_type" id="client_type">
                                            <option value="">-- Select Type --</option>
                                            <option value="Government">Government</option>
                                            <option value="Private">Private</option>
                                            <option value="Individual">Individual</option>
                                            <option value="NGO">NGO</option>
                                        </select>
                                        <div class="error-message" id="client_type_error">Please select a client type</div>
                                    </div>
                                    
                                    <div class="section-divider"><i class="fas fa-map"></i> Location Details</div>
                                    
                                    <div class="form-group">
                                        <label><i class="fas fa-flag"></i> State <span class="required">*</span></label>
                                        <select class="form-control" name="state" id="state" onchange="fetchDistricts(this.value)">
                                            <option value="">-- Select State --</option>
                                            <?php
                                            $stateQuery = "SELECT DISTINCT state FROM cities ORDER BY state ASC";
                                            $stateResult = mysqli_query($conn, $stateQuery);
                                            while ($row = mysqli_fetch_assoc($stateResult)) {
                                                echo '<option value="' . htmlspecialchars($row['state']) . '">' . htmlspecialchars($row['state']) . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <div class="error-message" id="state_error">Please select a state</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label><i class="fas fa-landmark"></i> District <span class="required">*</span></label>
                                        <select class="form-control" name="district" id="district" onchange="fetchCities(this.value)">
                                            <option value="">-- Select District --</option>
                                        </select>
                                        <div class="error-message" id="district_error">Please select a district</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label><i class="fas fa-city"></i> City <span class="required">*</span></label>
                                        <select class="form-control" name="city" id="city">
                                            <option value="">-- Select City --</option>
                                        </select>
                                        <div class="error-message" id="city_error">Please select a city</div>
                                    </div>
                                    
                                    <div class="section-divider"><i class="fas fa-user-tie"></i> Contact Person Details</div>
                                    
                                    <div class="form-group">
                                        <label><i class="fas fa-id-card"></i> Contact Name <span class="required">*</span></label>
                                        <input type="text" name="contact_name" id="contact_name" placeholder="Enter Contact Name" maxlength="50" class="form-control">
                                        <div class="error-message" id="contact_name_error">Contact name is required</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label><i class="fas fa-mobile-alt"></i> Contact Mobile <span class="required">*</span></label>
                                        <input type="text" name="contact_mobile" id="contact_mobile" placeholder="Enter Contact Mobile" maxlength="10" class="form-control">
                                        <div class="error-message" id="contact_mobile_error">Valid 10-digit mobile number starting with 6-9 is required</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label><i class="fas fa-envelope"></i> Contact Email</label>
                                        <input type="email" name="contact_email" id="contact_email" placeholder="Enter Contact Email" maxlength="200" class="form-control">
                                        <div class="error-message" id="contact_email_error">Please enter a valid email address</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label><i class="fas fa-lock"></i> Password <span class="required">*</span></label>
                                        <input type="text" name="password" id="password" placeholder="Enter Password" minlength="8" maxlength="10" class="form-control">
                                        <p class="hint"><i class="fas fa-info-circle"></i> Password must be between 8-10 characters</p>
                                        <div class="error-message" id="password_error">Password must be 8-10 characters long</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label><i class="fas fa-image"></i> Client Logo</label>
                                        <input type="file" name="client_logo" id="client_logo" class="form-control" accept="image/png, image/jpeg, image/jpg" onchange="previewLogo(this)">
                                        <small class="text-muted">Allowed formats: JPG, JPEG, PNG (Max 2MB)</small>
                                        <div class="error-message" id="client_logo_error">Only JPG, JPEG, PNG files are allowed (Max 2MB)</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div style="margin-top:10px;">
                                            <img id="logoPreview" src="" alt="Logo Preview" style="display:none; max-height:100px; border:1px solid #ddd; padding:5px; border-radius:5px;">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-actions">
                                    <a href="list_client.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Back</a>
                                    <button type="reset" class="btn btn-reset" id="resetBtn">
                                        <i class="fas fa-redo"></i> Reset
                                    </button>
                                    <button type="submit" id="submitBtn" class="btn btn-submit">
                                        <i class="fas fa-check"></i> Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('clientForm');
            const submitBtn = document.getElementById('submitBtn');
            const resetBtn = document.getElementById('resetBtn');
            
            // Logo preview function
            function previewLogo(input) {
                const preview = document.getElementById('logoPreview');
                const file = input.files[0];
                
                if (file) {
                    // Validate file size (2MB max)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('File size must be less than 2MB');
                        input.value = '';
                        preview.style.display = 'none';
                        return;
                    }
                    
                    // Validate file type
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (!validTypes.includes(file.type)) {
                        alert('Only JPG, JPEG, PNG files are allowed');
                        input.value = '';
                        preview.style.display = 'none';
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                    clearError(input);
                }
            }
            
            // Clear error styling and message
            function clearError(element) {
                element.classList.remove('error-field');
                const errorElement = document.getElementById(element.id + '_error');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            }
            
            // Show error styling and message
            function showError(element, message) {
                element.classList.add('error-field');
                const errorElement = document.getElementById(element.id + '_error');
                if (errorElement) {
                    errorElement.textContent = message;
                    errorElement.style.display = 'block';
                }
            }
            
            // Validation functions
            function validateRequired(element) {
                const value = element.value.trim();
                if (value === '') {
                    showError(element, 'This field is required');
                    return false;
                }
                clearError(element);
                return true;
            }
            
            function validatePhone(element) {
                const value = element.value.trim();
                if (value === '') {
                    showError(element, 'Phone number is required');
                    return false;
                }
                
                const phoneRegex = /^[6-9][0-9]{9}$/;
                if (!phoneRegex.test(value)) {
                    showError(element, 'Must be 10 digits and start with 6, 7, 8, or 9');
                    return false;
                }
                
                clearError(element);
                return true;
            }
            
            function validateEmail(element) {
                const value = element.value.trim();
                if (value === '') {
                    clearError(element);
                    return true; // Email is optional
                }
                
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    showError(element, 'Please enter a valid email address');
                    return false;
                }
                
                clearError(element);
                return true;
            }
            
            function validateWebsite(element) {
                const value = element.value.trim();
                if (value === '') {
                    clearError(element);
                    return true; // Website is optional
                }
                
                const urlRegex = /^(https?:\/\/)?([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/;
                if (!urlRegex.test(value)) {
                    showError(element, 'Please enter a valid website URL');
                    return false;
                }
                
                clearError(element);
                return true;
            }
            
            function validatePassword(element) {
                const value = element.value.trim();
                if (value === '') {
                    showError(element, 'Password is required');
                    return false;
                }
                
                if (value.length < 8 || value.length > 10) {
                    showError(element, 'Password must be 8-10 characters long');
                    return false;
                }
                
                clearError(element);
                return true;
            }
            
            function validateSelect(element) {
                const value = element.value;
                if (value === '') {
                    showError(element, 'Please select an option');
                    return false;
                }
                clearError(element);
                return true;
            }
            
            // Individual field validation on blur
            document.getElementById('client_name').addEventListener('blur', function() {
                validateRequired(this);
            });
            
            document.getElementById('client_phone').addEventListener('blur', function() {
                validatePhone(this);
            });
            
            document.getElementById('client_address').addEventListener('blur', function() {
                validateRequired(this);
            });
            
            document.getElementById('client_website').addEventListener('blur', function() {
                validateWebsite(this);
            });
            
            document.getElementById('client_type').addEventListener('blur', function() {
                validateSelect(this);
            });
            
            document.getElementById('state').addEventListener('blur', function() {
                validateSelect(this);
            });
            
            document.getElementById('district').addEventListener('blur', function() {
                validateSelect(this);
            });
            
            document.getElementById('city').addEventListener('blur', function() {
                validateSelect(this);
            });
            
            document.getElementById('contact_name').addEventListener('blur', function() {
                validateRequired(this);
            });
            
            document.getElementById('contact_mobile').addEventListener('blur', function() {
                validatePhone(this);
            });
            
            document.getElementById('contact_email').addEventListener('blur', function() {
                validateEmail(this);
            });
            
            document.getElementById('password').addEventListener('blur', function() {
                validatePassword(this);
            });
            
            // Real-time input formatting
            document.getElementById('client_phone').addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
            
            document.getElementById('contact_mobile').addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
            
            // Form submission handler
form.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Reset all errors
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(error => error.style.display = 'none');
    
    const errorFields = document.querySelectorAll('.error-field');
    errorFields.forEach(field => field.classList.remove('error-field'));
    
    // Validate all fields
    const validations = [
        validateRequired(document.getElementById('client_name')),
        validatePhone(document.getElementById('client_phone')),
        validateRequired(document.getElementById('client_address')),
        validateWebsite(document.getElementById('client_website')),
        validateSelect(document.getElementById('client_type')),
        validateSelect(document.getElementById('state')),
        validateSelect(document.getElementById('district')),
        validateSelect(document.getElementById('city')),
        validateRequired(document.getElementById('contact_name')),
        validatePhone(document.getElementById('contact_mobile')),
        validateEmail(document.getElementById('contact_email')),
        validatePassword(document.getElementById('password'))
    ];
    
    // Check if all validations passed
    const allValid = validations.every(valid => valid === true);
    
    if (allValid) {
        // Disable submit button to prevent double submission
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        
        // Submit the form
        form.submit();
    } else {
        // Focus first invalid field ONLY (no alert)
        const invalidFields = [
            'client_name', 'client_phone', 'client_address', 'client_website',
            'client_type', 'state', 'district', 'city', 'contact_name',
            'contact_mobile', 'contact_email', 'password'
        ];
        
        for (const fieldId of invalidFields) {
            const field = document.getElementById(fieldId);
            if (field.classList.contains('error-field')) {
                field.focus();
                break;
            }
        }
    }
});

            // Reset button handler
            resetBtn.addEventListener('click', function() {
                // Clear all error messages
                const errorMessages = document.querySelectorAll('.error-message');
                errorMessages.forEach(error => error.style.display = 'none');
                
                // Remove error styling
                const errorFields = document.querySelectorAll('.error-field');
                errorFields.forEach(field => field.classList.remove('error-field'));
                
                // Clear logo preview
                document.getElementById('logoPreview').style.display = 'none';
                
                // Reset form
                form.reset();
                
                // Reset district and city dropdowns
                document.getElementById('district').innerHTML = '<option value="">-- Select District --</option>';
                document.getElementById('city').innerHTML = '<option value="">-- Select City --</option>';
            });
        });
        
        // AJAX functions for location dropdowns
        function fetchDistricts(state) {
            if (state) {
                $.ajax({
                    type: 'POST',
                    url: 'fetch_districts.php',
                    data: { state: state },
                    success: function(response) {
                        $('#district').html(response);
                        $('#city').html('<option value="">-- Select City --</option>');
                    },
                    error: function() {
                        alert('Error fetching districts');
                    }
                });
            } else {
                $('#district').html('<option value="">-- Select District --</option>');
                $('#city').html('<option value="">-- Select City --</option>');
            }
        }
        
        function fetchCities(district) {
            if (district) {
                $.ajax({
                    type: 'POST',
                    url: 'fetch_cities.php',
                    data: { district: district },
                    success: function(response) {
                        $('#city').html(response);
                    },
                    error: function() {
                        alert('Error fetching cities');
                    }
                });
            } else {
                $('#city').html('<option value="">-- Select City --</option>');
            }
        }
    </script>
</body>
</html>

<?php
include('include/scripts.php');
include('include/footer.php');
?>