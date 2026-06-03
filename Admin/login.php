<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
ob_start();
session_start();
require_once("include/config.php");
// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
// Check if email is empty
if(empty(trim($_POST["email"]))){
$email_err = "<p>Please enter email.</p>";
} else{
$email = trim($_POST["email"]);
}

// Check if password is empty
if(empty(trim($_POST["password"]))){
$password_err = "<p>Please enter your Password.</p>";
} else{
$password = trim($_POST["password"]);
}
$email = trim($_POST['email']);
$password = trim($_POST['password']);

$sql = "SELECT * FROM tbl_users WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$rs = mysqli_stmt_get_result($stmt);
$numRows = mysqli_num_rows($rs);

if($numRows == 1){
$row = mysqli_fetch_assoc($rs);
if(password_verify($password,$row['password'])){

$_SESSION['id'] = $row['id'];
$_SESSION['email'] = $row['email'];
$_SESSION['name'] = $row['name'];
$_SESSION['role'] = $row['role'];
header("location: index.php");
ob_flush();
}
else{
$password_err = "<p>The password you entered was not valid.</p>";
}
}
else{
// Display an error message if email doesn't exist
$email_err = "<p>No account found with that email.</p>";
}
}
?>
<html lang="en">
<head>
    <title>SmartBuddy Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    
    <style>
        body {
            background-color: #10141f;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #e2e8f0;
        }
        .login-card {
            background-color: #1e2638;
            border-radius: 12px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            border: 1px solid #2d3748;
        }
        .login-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            color: #ffffff;
        }
        .form-control {
            background-color: #131722;
            border: 1px solid #2d3748;
            color: #e2e8f0;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .form-control:focus {
            background-color: #131722;
            border-color: #20c997;
            color: #ffffff;
            box-shadow: none;
        }
        .btn-login {
            background-color: #20c997;
            border: none;
            color: #ffffff;
            font-weight: bold;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            transition: background-color 0.3s;
        }
        .btn-login:hover {
            background-color: #17a589;
        }
        .forgot-pw {
            text-align: center;
            margin-top: 20px;
        }
        .forgot-pw a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
        }
        .forgot-pw a:hover {
            color: #e2e8f0;
        }
        .help-block {
            color: #e74c3c;
            font-size: 13px;
            margin-top: -15px;
            margin-bottom: 15px;
            display: block;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-title">SmartBuddy Login</div>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <input class="form-control" type="text" name="email" placeholder="Email" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo strip_tags($email_err); ?></span>
            </div>

            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <input class="form-control" type="password" name="password" placeholder="Password">
                <span class="help-block"><?php echo strip_tags($password_err); ?></span>
            </div>

            <button type="submit" class="btn-login">Login</button>
            
            <div class="forgot-pw">
                <a href="forget_pw.php">Forgot Password?</a>
            </div>
        </form>
    </div>

</body>
</html>