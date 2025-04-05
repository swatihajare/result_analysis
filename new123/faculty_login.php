<?php
session_start();
$error_message = ""; // Initialize error message variable

// Handle logout if requested
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $conn = new mysqli("localhost", "root", "", "result_analysis");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepared statement to prevent SQL injection
    $sql = "SELECT id, password FROM faculty WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['faculty_id'] = $row['id'];
            header("Location: button.html");
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - College Result Analysis System</title>
    <style>
        /* Header Styles */
        header {
            background-color: #2c3e50;
            color: white;
            padding: 15px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        
        /* Main Content Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .main-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        
        .login-container h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 24px;
        }
        
        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: #34495e;
            font-weight: bold;
        }
        
        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        
        .login-btn {
            width: 100%;
            padding: 14px;
            background-color: #27ae60;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin: 15px 0;
            transition: background-color 0.3s;
        }
        
        .login-btn:hover {
            background-color: #219653;
        }
        
        .logout-btn {
            width: 95%;
            padding: 14px;
            background-color: #e74c3c;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin: 15px 0;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }
        
        .logout-btn:hover {
            background-color: #c0392b;
        }
        
        .error-message {
            color: #e74c3c;
            margin: 15px 0;
            font-size: 14px;
            font-weight: bold;
        }
        
        .auth-links {
            margin-top: 20px;
            font-size: 14px;
        }
        
        .auth-links a {
            color: #3498db;
            text-decoration: none;
        }
        
        .auth-links a:hover {
            text-decoration: underline;
        }
        
        /* Footer Styles */
        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px 0;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .footer-links a {
            color: white;
            text-decoration: none;
        }
        
        .footer-links a:hover {
            text-decoration: underline;
        }
        
        .copyright {
            font-size: 14px;
            color: #bdc3c7;
        }
        
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            
            .login-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">Student Result System</div>
        </div>
    </header>

    <div class="main-container">
        <div class="login-container">
            <h2>Admin Login</h2>
            <form id="loginForm" method="POST">
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                
                <button type="submit" class="login-btn">Login</button>
                
                <?php if (isset($_SESSION['faculty_id'])): ?>
                   
                <?php endif; ?>
                
                <?php if (!empty($error_message)): ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                <?php endif; ?>
                
                <div class="auth-links">
                    Don't have an account? <a href="faculty_registration.php">Register now</a><br>
                    <a href="home.html">Back to Home</a>
                </div>
            </form>
        </div>
    </div>

    <footer>
        <div class="footer-container">
            <div class="footer-links">
                <a href="about.php">About Us</a>
                <a href="contact.php">Contact</a>
                <a href="privacy.php">Privacy Policy</a>
                <a href="terms.php">Terms of Service</a>
            </div>
            <div class="copyright">
                &copy; <?php echo date("Y"); ?> Student Result System. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>