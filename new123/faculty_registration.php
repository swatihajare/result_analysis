<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $department = trim($_POST['department']);

    $conn = new mysqli("localhost", "root", "", "result_analysis");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepared statement to prevent SQL injection
    $sql = $conn->prepare("INSERT INTO faculty (full_name, email, username, password, department) VALUES (?, ?, ?, ?, ?)");
    $sql->bind_param("sssss", $fullName, $email, $username, $password, $department);

    if ($sql->execute()) {
        // Redirect to faculty login page after successful registration
        echo "<script>
                alert('Registration successful! Redirecting to login page.');
                window.location.href = 'faculty_login.php';
              </script>";
        exit();
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }

    $sql->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration - College Result Analysis System</title>
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
        
        .registration-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }
        
        .registration-container h2 {
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
        
        .input-group input,
        .input-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        
        .register-btn {
            width: 100%;
            padding: 14px;
            background-color: #27ae60;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin: 20px 0 15px;
            transition: background-color 0.3s;
        }
        
        .register-btn:hover {
            background-color: #219653;
        }
        
        .auth-links {
            margin-top: 20px;
            font-size: 14px;
            color: #7f8c8d;
        }
        
        .auth-links a {
            color: #3498db;
            text-decoration: none;
        }
        
        .auth-links a:hover {
            text-decoration: underline;
        }
        
        .error-message {
            color: #e74c3c;
            margin: 10px 0;
            font-size: 14px;
            font-weight: bold;
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
            
            .registration-container {
                padding: 20px;
            }
        }
    </style>
    <script>
        function validateForm() {
            let fullName = document.getElementById("fullName").value.trim();
            let email = document.getElementById("email").value.trim();
            let username = document.getElementById("username").value.trim();
            let password = document.getElementById("password").value.trim();
            let department = document.getElementById("department").value.trim();

            if (fullName === "" || email === "" || username === "" || password === "" || department === "") {
                alert("All fields are required!");
                return false;
            }
            
            // Validate email format
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert("Please enter a valid email address!");
                return false;
            }
            
            return true;
        }
    </script>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">Student Result System</div>
        </div>
    </header>

    <div class="main-container">
        <div class="registration-container">
            <h2>Admin Registration</h2>
            <form id="registrationForm" method="POST" onsubmit="return validateForm()">
                <div class="input-group">
                    <label for="fullName">Full Name:</label>
                    <input type="text" id="fullName" name="fullName" placeholder="Enter your full name" required>
                </div>
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Choose a username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter a password" required>
                </div>
                <div class="input-group">
                    <label for="department">Department:</label>
                    <input type="text" id="department" name="department" placeholder="Enter department" required>
                </div>
                
                <button type="submit" class="register-btn">Register</button>
                
                <div class="auth-links">
                    Already have an account? <a href="faculty_login.php">Login now</a><br>
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