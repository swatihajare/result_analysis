<?php
// Database connection
$servername = "127.0.0.1";
$username = "root"; // replace with your database username
$password = ""; // replace with your database password
$dbname = "result_analysis";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to calculate performance stats
function calculatePerformanceStats($conn, $branch, $semester, $academic_year) {
    $stats = [
        'total_students' => 0,
        'above_60' => 0,
        'above_70' => 0,
        'above_75' => 0,
        'above_80' => 0,
        'above_90' => 0
    ];

    // Build the query based on filters
    $sql = "SELECT DISTINCT student_id, percentage FROM student_results WHERE result = 'Pass'";
    
    if (!empty($branch)) {
        $sql .= " AND branch = '" . $conn->real_escape_string($branch) . "'";
    }
    
    if (!empty($semester)) {
        $sql .= " AND semester = '" . $conn->real_escape_string($semester) . "'";
    }

    if (!empty($academic_year)) {
        $sql .= " AND academic_year = '" . $conn->real_escape_string($academic_year) . "'";
    }

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $stats['total_students']++;
            $percentage = $row['percentage'];
            
            if ($percentage > 60) $stats['above_60']++;
            if ($percentage > 70) $stats['above_70']++;
            if ($percentage > 75) $stats['above_75']++;
            if ($percentage > 80) $stats['above_80']++;
            if ($percentage > 90) $stats['above_90']++;
        }
    }

    return $stats;
}

// Get filter values from request
$branch = isset($_GET['branch']) ? $_GET['branch'] : '';
$semester = isset($_GET['semester']) ? $_GET['semester'] : '';
$academic_year = isset($_GET['academic_year']) ? $_GET['academic_year'] : '';

// Calculate statistics
$stats = calculatePerformanceStats($conn, $branch, $semester, $academic_year);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Performance Analysis</title>
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
        
        .header-actions {
            display: flex;
            align-items: center;
        }
        
        .logout-btn {
            background-color: #e74c3c;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .logout-btn:hover {
            background-color: #c0392b;
        }
        
        /* Main Content Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .container {
            flex: 1;
            max-width: 1000px;
            margin: 30px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        
        .selectors {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .select-box {
            flex: 1;
            min-width: 200px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #34495e;
        }
        
        select, input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f8f9fa;
            font-size: 16px;
        }
        
        .year-input {
            position: relative;
        }
        
        .year-format {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 12px;
        }
        
        .results {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .result-card {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }
        
        .result-card:hover {
            transform: translateY(-5px);
        }
        
        .result-card h3 {
            margin-top: 0;
            color: #2c3e50;
        }
        
        .percentage {
            font-size: 36px;
            font-weight: bold;
            margin: 15px 0;
        }
        
        .above-60 { color: #3498db; }
        .above-70 { color: #2ecc71; }
        .above-75 { color: #f39c12; }
        .above-80 { color: #e74c3c; }
        .above-90 { color: #9b59b6; }
        
        .stats {
            font-size: 14px;
            color: #7f8c8d;
        }
        
        /* Footer Styles */
        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: auto;
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
            
            .header-actions {
                margin-top: 10px;
            }
            
            .selectors {
                flex-direction: column;
                gap: 15px;
            }
            
            .select-box {
                width: 100%;
            }
            
            .container {
                padding: 20px;
                margin: 15px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">Student Result System</div>
            <div class="header-actions">
                <a href="button.html" class="logout-btn">Logout</a>
            </div>
        </div>
    </header>

    <div class="container">
        <h1>Student Performance Analysis</h1>
        
        <form method="GET" action="">
            <div class="selectors">
                <div class="select-box">
                    <label for="branch">Select Branch</label>
                    <select id="branch" name="branch" onchange="this.form.submit()">
                        <option value="">-- All Branches --</option>
                        <option value="CSE" <?= $branch == 'CSE' ? 'selected' : '' ?>>Computer Technology</option>
                        <option value="ECE" <?= $branch == 'ECE' ? 'selected' : '' ?>>Electrical Engineering</option>
                        <option value="ME" <?= $branch == 'ME' ? 'selected' : '' ?>>Mechanical Engineering</option>
                        <option value="EJ" <?= $branch == 'EJ' ? 'selected' : '' ?>>Electronics Engineering</option>
                        <option value="CE" <?= $branch == 'CE' ? 'selected' : '' ?>>Civil Engineering</option>
                    </select>
                </div>
                
                <div class="select-box">
                    <label for="semester">Select Semester</label>
                    <select id="semester" name="semester" onchange="this.form.submit()">
                        <option value="">-- All Semesters --</option>
                        <?php for ($i = 1; $i <= 6; $i++): ?>
                            <option value="<?= $i ?>" <?= $semester == $i ? 'selected' : '' ?>>Semester <?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="select-box year-input">
                    <label for="academic_year">Academic Year</label>
                    <input type="text" id="academic_year" name="academic_year" 
                           placeholder="YYYY-YYYY" pattern="\d{4}-\d{4}" 
                           value="<?= htmlspecialchars($academic_year) ?>">
                    <span class="year-format">YYYY-YYYY</span>
                </div>
            </div>
        </form>
        
        <div class="results" id="results">
            <div class="result-card">
                <h3 class="above-60">Above 60% Marks</h3>
                <div class="percentage above-60" id="percent-60">
                    <?= $stats['total_students'] > 0 ? round(($stats['above_60'] / $stats['total_students']) * 100) : 0 ?>%
                </div>
                <div class="stats" id="stats-60">
                    <?= $stats['above_60'] ?> out of <?= $stats['total_students'] ?> students
                </div>
            </div>
            
            <div class="result-card">
                <h3 class="above-70">Above 70% Marks</h3>
                <div class="percentage above-70" id="percent-70">
                    <?= $stats['total_students'] > 0 ? round(($stats['above_70'] / $stats['total_students']) * 100) : 0 ?>%
                </div>
                <div class="stats" id="stats-70">
                    <?= $stats['above_70'] ?> out of <?= $stats['total_students'] ?> students
                </div>
            </div>
            
            <div class="result-card">
                <h3 class="above-75">Above 75% Marks</h3>
                <div class="percentage above-75" id="percent-75">
                    <?= $stats['total_students'] > 0 ? round(($stats['above_75'] / $stats['total_students']) * 100) : 0 ?>%
                </div>
                <div class="stats" id="stats-75">
                    <?= $stats['above_75'] ?> out of <?= $stats['total_students'] ?> students
                </div>
            </div>
            
            <div class="result-card">
                <h3 class="above-80">Above 80% Marks</h3>
                <div class="percentage above-80" id="percent-80">
                    <?= $stats['total_students'] > 0 ? round(($stats['above_80'] / $stats['total_students']) * 100) : 0 ?>%
                </div>
                <div class="stats" id="stats-80">
                    <?= $stats['above_80'] ?> out of <?= $stats['total_students'] ?> students
                </div>
            </div>

            <div class="result-card">
                <h3 class="above-90">Above 90% Marks</h3>
                <div class="percentage above-90" id="percent-90">
                    <?= $stats['total_students'] > 0 ? round(($stats['above_90'] / $stats['total_students']) * 100) : 0 ?>%
                </div>
                <div class="stats" id="stats-90">
                    <?= $stats['above_90'] ?> out of <?= $stats['total_students'] ?> students
                </div>
            </div>
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
        // Validate academic year format
        document.getElementById('academic_year').addEventListener('input', function(e) {
            const pattern = /^\d{4}-\d{4}$/;
            if (!pattern.test(e.target.value)) {
                e.target.setCustomValidity('Please enter in YYYY-YYYY format');
            } else {
                e.target.setCustomValidity('');
            }
        });
    </script>
</body>
</html>