<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $enrollment_number = $_POST['enrollment_number'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $conn = new mysqli("localhost", "root", "", "result_analysis");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO students (username, enrollment_number, password) VALUES ('$username', '$enrollment_number', '$password')";
    if ($conn->query($sql)) {
        // Registration successful, redirect to login page
        header("Location: student_login.php");
        exit(); // Ensure no further code is executed after the redirect
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
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
        
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        
        .form-container h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        
        .form-container input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        
        .form-container button {
            width: 100%;
            padding: 14px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s;
        }
        
        .form-container button:hover {
            background: #219653;
        }
        
        .form-links {
            margin-top: 20px;
            font-size: 14px;
        }
        
        .form-links a {
            color: #3498db;
            text-decoration: none;
            display: block;
            margin: 8px 0;
        }
        
        .form-links a:hover {
            text-decoration: underline;
        }
        
        .success-message {
            display: none;
            color: #27ae60;
            margin-top: 15px;
            font-weight: bold;
        }
        
        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
            display: none;
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
            
            .form-container {
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
        <div class="form-container">
            <h2>Student Registration</h2>
            <form id="registrationForm" method="POST">
                <input type="text" id="username" name="username" placeholder="Enter Username" required>
                <input type="text" id="enrollment_number" name="enrollment_number" placeholder="Enter Enrollment Number" required>
                <input type="password" id="password" name="password" placeholder="Enter Password" required>
                <input type="password" id="confirm_password" placeholder="Confirm Password" required>
                <div class="error-message" id="passwordError">Passwords do not match!</div>
                
                <button type="submit">Register</button>
                
                <div class="form-links">
                    <a href="student_login.php">Already have an account? Login now</a>
                    <a href="home.html">Go back to Home</a>
                </div>
                
                <p class="success-message" id="successMessage">Registered Successfully!</p>
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

    <script>
        document.getElementById("registrationForm").addEventListener("submit", function(event) {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            var passwordError = document.getElementById("passwordError");
            
            if (password !== confirmPassword) {
                passwordError.style.display = "block";
                event.preventDefault();
            } else {
                passwordError.style.display = "none";
                document.getElementById("successMessage").style.display = "block";
            }
        });
    </script>
</body>
</html>