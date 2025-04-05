<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result Analysis System</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Header Styles */
        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header-content h1 {
            margin: 0;
            font-size: 2.5em;
        }

        .header-content p {
            margin: 10px 0 0;
            font-size: 1.2em;
        }

        /* Navigation Bar */
        nav {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav .logo {
            font-size: 24px;
            font-weight: bold;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        nav ul li {
            margin-left: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
        }

        nav ul li a:hover {
            color: #28a745;
        }

        /* Hero Section */
        .hero {
            background: url('https://via.placeholder.com/1500x600') no-repeat center center/cover;
            color: #611616;
            padding: 100px 20px;
            text-align: center;
        }

        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 20px;
            margin-bottom: 40px;
        }

        .hero button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
        }

        .hero button:hover {
            background-color: #218838;
        }

        /* Features Section */
        .features {
            display: flex;
            justify-content: space-around;
            padding: 50px 20px;
            background-color: #fff;
        }

        .feature {
            text-align: center;
            width: 30%;
        }

        .feature h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .feature p {
            font-size: 16px;
            color: #666;
        }

        /* Footer */
        footer {
            background-color: #2c3e50;
            color: #fff;
            padding: 40px 20px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .footer-section {
            flex: 1;
            min-width: 250px;
            margin-bottom: 20px;
        }

        .footer-section h3 {
            font-size: 1.4em;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-section h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 2px;
            background-color: #28a745;
        }

        .footer-section p {
            line-height: 1.6;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-section ul li a {
            color: #ddd;
            text-decoration: none;
        }

        .footer-section ul li a:hover {
            color: #28a745;
        }

        .footer-bottom {
            background-color: #1a252f;
            text-align: center;
            padding: 20px;
        }

        .footer-bottom p {
            margin: 0;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-content">
            <h1>Result Analysis System</h1>
            <p>Comprehensive academic performance tracking and analysis</p>
        </div>
    </header>

    <!-- Navigation Bar -->
    <nav>
        <div class="logo">Result Analysis System</div>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="faculty_registration.php">Admin</a></li>
            <li><a href="student_registration.php">Student</a></li>
        </ul>
    </nav>
