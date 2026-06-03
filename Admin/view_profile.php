<?php 
require_once('include/header.php');
require_once('include/navbar.php');
require_once('include/config.php');



$query = "SELECT * FROM tblusers WHERE mobile = '$mobile'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <style>
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .profile-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        
        .profile-avatar {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            backdrop-filter: blur(10px);
        }
        
        .profile-avatar i {
            font-size: 50px;
            color: white;
        }
        
        .profile-header h3 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .profile-header p {
            margin: 5px 0 0;
            opacity: 0.9;
        }
        
        .profile-body {
            padding: 30px;
        }
        
        .info-row {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }
        
        .info-row:hover {
            background: #f8f9fa;
            transform: translateX(5px);
        }
        
        .info-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
        }
        
        .info-icon i {
            font-size: 24px;
            color: white;
        }
        
        .info-content {
            flex: 1;
        }
        
        .info-label {
            font-size: 12px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }
        
        .role-badge {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .profile-container {
                padding: 10px;
            }
            
            .info-icon {
                width: 40px;
                height: 40px;
                margin-right: 15px;
            }
            
            .info-icon i {
                font-size: 18px;
            }
            
            .info-value {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        
                
                <div class="card-body">
                    <div class="table-responsive">
                        
                        <div class="profile-container">
                            <div class="profile-card">
                                <div class="profile-header">
                                    <div class="profile-avatar">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <h3><?= htmlspecialchars($user['name'] ?? 'N/A'); ?></h3>
                                    
                                </div>
                                
                                <div class="profile-body">
                                    <div class="info-row">
                                        <div class="info-icon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="info-content">
                                            <div class="info-label">Email Address</div>
                                            <div class="info-value"><?= htmlspecialchars($user['email'] ?? 'N/A'); ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-row">
                                        <div class="info-icon">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                        <div class="info-content">
                                            <div class="info-label">Mobile Number</div>
                                            <div class="info-value"><?= htmlspecialchars($user['mobile'] ?? 'N/A'); ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-row">
                                        <div class="info-icon">
                                            <i class="fas fa-user-shield"></i>
                                        </div>
                                        <div class="info-content">
                                            <div class="info-label">Role</div>
                                            <div class="info-value">
                                                <span class="role-badge"><?= htmlspecialchars($user['role'] ?? 'N/A'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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