<?php
// Start a PHP session to maintain user state across pages
session_start();

// Check if student_id is set in session, if not redirect to login page
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

// Database Connection
// Create connection to MySQL database "result_analysis" with root user (no password)
$conn = new mysqli("localhost", "root", "", "result_analysis");
// Check if connection failed and display error if it did
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Subjects Based on Branch and Semester (AJAX endpoint)
// Check if request is GET and has branch/semester parameters
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['branch']) && isset($_GET['semester'])) {
    // Sanitize input (though prepared statements will handle security)
    $branch = $_GET['branch'];
    $semester = $_GET['semester'];

    // SQL query to get subjects for given branch and semester
    $sql = "SELECT id, subject_name, subject_type, max_marks FROM results WHERE branch = ? AND semester = ?";
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare($sql);
    // Bind parameters (branch as string, semester as integer)
    $stmt->bind_param("si", $branch, $semester);
    $stmt->execute();
    $result = $stmt->get_result();

    // Build array of subjects
    $subjects = [];
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }

    // Return subjects as JSON for AJAX response
    echo json_encode($subjects);
    exit();
}

// Handling Form Submission (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get student ID from session
    $student_id = $_SESSION['student_id'];
    // Get form data from POST
    $enrollment = $_POST['enrollment'];
    $academic_year = $_POST['academic_year'];
    $branch = $_POST['branch'];
    $semester = $_POST['semester'];
    $totalMarks = $_POST['totalMarks'];
    $percentage = $_POST['percentage'];

    // Extract just the first year from academic_year (format: YYYY-YYYY)
    $academic_year_start = substr($academic_year, 0, 4);
    
    // Determine pass/fail result based on percentage (40% threshold)
    $result = ($percentage >= 40) ? "Pass" : "Fail";
    
    // Prepare subject data from form arrays
    $subject_data = [];
    // Loop through all submitted subjects
    foreach ($_POST['subject_id'] as $index => $subject_id) {
        $subject_data[] = [
            'id' => $subject_id,
            'name' => $_POST['subject_name'][$index],
            // Use null coalescing operator for optional fields
            'theory' => $_POST['theory'][$index] ?? null,
            'practical' => $_POST['practical'][$index] ?? null,
            'sla' => $_POST['sla'][$index] ?? null
        ];
    }

    // Convert subject data to JSON for storage
    $subject_json = json_encode($subject_data);

    // SQL to insert result data into student_results table
    $sql = "INSERT INTO student_results 
            (student_id, enrollment, academic_year, branch, semester, subject_name, total_marks, result, percentage) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    
    // Bind parameters with correct data types:
    // i=integer, s=string, d=double
    $bind_result = $stmt->bind_param("isisssdsd", 
        $student_id,       // i (integer)
        $enrollment,       // s (string)
        $academic_year_start, // i (integer - first year only)
        $branch,           // s (string)
        $semester,         // s (string)
        $subject_json,     // s (string - JSON)
        $totalMarks,       // d (double)
        $result,           // s (string)
        $percentage        // d (double)
    );
    
    if ($bind_result === false) {
        die("Error binding parameters: " . $stmt->error);
    }
    
    // Execute the statement and show success/error message
    if ($stmt->execute()) {
        echo "<script>alert('Data Added Successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fill Result Details</title>
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
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logout-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        
        .logout-btn:hover {
            background-color: #c0392b;
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
        
        .container {
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
            max-width: 600px;
            text-align: center;
            position: relative;
        }
        
        /* Existing form styles */
        input, select, textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        
        .subject-row {
            margin-bottom: 20px;
            text-align: left;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        
        .subject-label {
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
            color: #2c3e50;
            font-size: 16px;
        }
        
        .marks-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 10px;
        }
        
        .marks-input-container {
            flex: 1;
            min-width: 120px;
        }
        
        .marks-input {
            width: 100%;
            text-align: center;
        }
        
        button[type="submit"] {
            width: 100%;
            padding: 14px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s;
        }
        
        button[type="submit"]:hover {
            background: #219653;
        }
        
        .result-status {
            padding: 12px;
            margin: 15px 0;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            display: none;
            font-size: 16px;
        }
        
        .pass {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .fail {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
            
            .marks-group {
                flex-direction: column;
            }
            
            .marks-input-container {
                width: 100%;
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
            <div class="user-info">
                <span>Welcome, Student</span>
                <a href="home.html" class="logout-btn">Logout</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="form-container">
            <h2>Fill Your Result Details</h2>
            <form id="detailsForm" method="POST">
                <input type="hidden" id="student_id" name="student_id" value="<?php echo $_SESSION['student_id']; ?>">
                <input type="hidden" id="result" name="result" value="">
                
                <label>Enrollment Number:</label>
                <input type="text" id="enrollment" name="enrollment" placeholder="Enter enrollment number" required>
                
                <label>Academic Year:</label>
                <div class="year-input">
                    <input type="text" id="academic_year" name="academic_year" 
                           placeholder="YYYY-YYYY" pattern="\d{4}-\d{4}" required>
                    <span class="year-format">YYYY-YYYY</span>
                </div>
                
                <label>Select Branch:</label>
                <select id="branch" name="branch" onchange="updateSubjects()" required>
                    <option value="">Select Branch</option>
                    <option value="CSE">Computer Technology</option>
                    <option value="ECE">Electrical</option>
                    <option value="ME">Mechanical</option>
                    <option value="EJ">Electronics</option>
                    <option value="CE">Civil</option>
                </select>
                
                <label>Select Semester:</label>
                <select id="semester" name="semester" onchange="updateSubjects()" required>
                    <option value="">Select Semester</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                </select>
                
                <div id="subjectsContainer"></div>
                
                <label>Total Marks:</label>
                <input type="number" id="totalMarks" name="totalMarks" readonly>
                
                <div id="resultDisplay" class="result-status">
                    Result Status: <span id="statusText"></span>
                </div>
                
                <label>Percentage:</label>
                <input type="text" id="percentage" name="percentage" readonly>
                
                <button type="submit">Submit Results</button>
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
        function updateSubjects() {
            const branch = document.getElementById("branch").value;
            const semester = document.getElementById("semester").value;
            const subjectsContainer = document.getElementById("subjectsContainer");
            subjectsContainer.innerHTML = "";

            if (branch && semester) {
                fetch(`students_detail.php?branch=${branch}&semester=${semester}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(subject => {
                            const subjectDiv = document.createElement("div");
                            subjectDiv.classList.add("subject-row");
                            
                            // Create hidden inputs for subject data
                            subjectDiv.innerHTML = `
                                <input type="hidden" name="subject_id[]" value="${subject.id}">
                                <input type="hidden" name="subject_name[]" value="${subject.subject_name}">
                                
                                <label class="subject-label">${subject.subject_name}</label>
                                <div class="marks-group">
                                    ${subject.subject_type.includes("Theory") ? `
                                        <div class="marks-input-container">
                                            <label>Theory Marks (Max 70):</label>
                                            <input type="number" name="theory[]" class="marks-input theory" 
                                                   placeholder="Enter marks" min="0" max="70" oninput="calculateTotal()">
                                        </div>
                                    ` : `<input type="hidden" name="theory[]" value="">`}
                                    
                                    ${subject.subject_type.includes("Practical") ? `
                                        <div class="marks-input-container">
                                            <label>Practical Marks (Max 50):</label>
                                            <input type="number" name="practical[]" class="marks-input practical" 
                                                   placeholder="Enter marks" min="0" max="50" oninput="calculateTotal()">
                                        </div>
                                    ` : `<input type="hidden" name="practical[]" value="">`}
                                    
                                    ${subject.subject_type.includes("SLA") ? `
                                        <div class="marks-input-container">
                                            <label>SLA Marks (Max 30):</label>
                                            <input type="number" name="sla[]" class="marks-input sla" 
                                                   placeholder="Enter marks" min="0" max="30" oninput="calculateTotal()">
                                        </div>
                                    ` : `<input type="hidden" name="sla[]" value="">`}
                                </div>
                            `;
                            subjectsContainer.appendChild(subjectDiv);
                        });
                    });
            }
        }

        function calculateTotal() {
            let totalMarksObtained = 0;
            let totalMaxMarks = 0;

            document.querySelectorAll(".subject-row").forEach(row => {
                const theoryMarks = parseFloat(row.querySelector(".theory")?.value) || 0;
                const practicalMarks = parseFloat(row.querySelector(".practical")?.value) || 0;
                const slaMarks = parseFloat(row.querySelector(".sla")?.value) || 0;

                totalMarksObtained += theoryMarks + practicalMarks + slaMarks;

                // Add max marks based on what components exist
                if (row.querySelector(".theory")) totalMaxMarks += 70;
                if (row.querySelector(".practical")) totalMaxMarks += 50;
                if (row.querySelector(".sla")) totalMaxMarks += 30;
            });

            document.getElementById("totalMarks").value = totalMarksObtained;
            
            const percentage = totalMaxMarks > 0 
                ? ((totalMarksObtained / totalMaxMarks) * 100).toFixed(2) 
                : 0;
            document.getElementById("percentage").value = percentage + "%";
            
            // Automatically determine and display result (40% threshold)
            const resultDisplay = document.getElementById("resultDisplay");
            const statusText = document.getElementById("statusText");
            const resultInput = document.getElementById("result");
            
            if (percentage >= 40) {
                statusText.textContent = "PASS";
                resultDisplay.className = "result-status pass";
                resultInput.value = "Pass";
            } else {
                statusText.textContent = "FAIL";
                resultDisplay.className = "result-status fail";
                resultInput.value = "Fail";
            }
            
            // Show the result display if we have marks
            resultDisplay.style.display = (totalMarksObtained > 0) ? "block" : "none";
        }

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





<!-- <?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

// Database Connection
$conn = new mysqli("localhost", "root", "", "result_analysis");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Subjects Based on Branch and Semester
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['branch']) && isset($_GET['semester'])) {
    $branch = $_GET['branch'];
    $semester = $_GET['semester'];

    $sql = "SELECT id, subject_name, max_marks FROM results WHERE branch = ? AND semester = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $branch, $semester);
    $stmt->execute();
    $result = $stmt->get_result();

    $subjects = [];
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }

    echo json_encode($subjects);
    exit();
}

// Handling Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_SESSION['student_id'];
    $enrollment = $_POST['enrollment'];
    $academic_year = $_POST['academic_year'];
    $branch = $_POST['branch'];
    $semester = $_POST['semester'];
    $totalMarks = $_POST['totalMarks'];
    $percentage = $_POST['percentage'];

    // Extract just the first year from academic_year (YYYY-YYYY)
    $academic_year_start = substr($academic_year, 0, 4);
    
    $result = ($percentage >= 40) ? "Pass" : "Fail";
    
    // Prepare subject data
    $subject_data = [];
    foreach ($_POST['subject_id'] as $index => $subject_id) {
        $subject_data[] = [
            'id' => $subject_id,
            'name' => $_POST['subject_name'][$index],
            'marks_obtained' => $_POST['marks_obtained'][$index],
            'max_marks' => $_POST['max_marks'][$index]
        ];
    }

    // Convert subject data to JSON
    $subject_json = json_encode($subject_data);

    // Insert into database
    $sql = "INSERT INTO student_results 
            (student_id, enrollment, academic_year, branch, semester, subject_name, total_marks, result, percentage) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    
    $bind_result = $stmt->bind_param("isisssdsd", 
        $student_id,
        $enrollment,
        $academic_year_start,
        $branch,
        $semester,
        $subject_json,
        $totalMarks,
        $result,
        $percentage
    );
    
    if ($bind_result === false) {
        die("Error binding parameters: " . $stmt->error);
    }
    
    if ($stmt->execute()) {
        echo "<script>alert('Data Added Successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fill Result Details</title>
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
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logout-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        
        .logout-btn:hover {
            background-color: #c0392b;
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
        
        .container {
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
            max-width: 600px;
            text-align: center;
            position: relative;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        
        .subject-row {
            margin-bottom: 20px;
            text-align: left;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        
        .subject-label {
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
            color: #2c3e50;
            font-size: 16px;
        }
        
        .marks-input-container {
            margin-bottom: 10px;
        }
        
        .marks-input {
            width: 100%;
            text-align: center;
        }
        
        button[type="submit"] {
            width: 100%;
            padding: 14px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s;
        }
        
        button[type="submit"]:hover {
            background: #219653;
        }
        
        .result-status {
            padding: 12px;
            margin: 15px 0;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            display: none;
            font-size: 16px;
        }
        
        .pass {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .fail {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
            <div class="user-info">
                <span>Welcome, Student</span>
                <a href="home.html" class="logout-btn">Logout</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="form-container">
            <h2>Fill Your Result Details</h2>
            <form id="detailsForm" method="POST">
                <input type="hidden" id="student_id" name="student_id" value="<?php echo $_SESSION['student_id']; ?>">
                <input type="hidden" id="result" name="result" value="">
                
                <label>Enrollment Number:</label>
                <input type="text" id="enrollment" name="enrollment" placeholder="Enter enrollment number" required>
                
                <label>Academic Year:</label>
                <div class="year-input">
                    <input type="text" id="academic_year" name="academic_year" 
                           placeholder="YYYY-YYYY" pattern="\d{4}-\d{4}" required>
                    <span class="year-format">YYYY-YYYY</span>
                </div>
                
                <label>Select Branch:</label>
                <select id="branch" name="branch" onchange="updateSubjects()" required>
                    <option value="">Select Branch</option>
                    <option value="CSE">Computer Technology</option>
                    <option value="ECE">Electrical</option>
                    <option value="ME">Mechanical</option>
                    <option value="EJ">Electronics</option>
                    <option value="CE">Civil</option>
                </select>
                
                <label>Select Semester:</label>
                <select id="semester" name="semester" onchange="updateSubjects()" required>
                    <option value="">Select Semester</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                </select>
                
                <div id="subjectsContainer"></div>
                
                <label>Total Marks:</label>
                <input type="number" id="totalMarks" name="totalMarks" readonly>
                
                <div id="resultDisplay" class="result-status">
                    Result Status: <span id="statusText"></span>
                </div>
                
                <label>Percentage:</label>
                <input type="text" id="percentage" name="percentage" readonly>
                
                <button type="submit">Submit Results</button>
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
        function updateSubjects() {
            const branch = document.getElementById("branch").value;
            const semester = document.getElementById("semester").value;
            const subjectsContainer = document.getElementById("subjectsContainer");
            subjectsContainer.innerHTML = "";

            if (branch && semester) {
                fetch(`students_detail.php?branch=${branch}&semester=${semester}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(subject => {
                            const subjectDiv = document.createElement("div");
                            subjectDiv.classList.add("subject-row");
                            
                            subjectDiv.innerHTML = `
                                <input type="hidden" name="subject_id[]" value="${subject.id}">
                                <input type="hidden" name="subject_name[]" value="${subject.subject_name}">
                                <input type="hidden" name="max_marks[]" value="${subject.max_marks}">
                                
                                <label class="subject-label">${subject.subject_name} (Max: ${subject.max_marks})</label>
                                <div class="marks-input-container">
                                    <input type="number" name="marks_obtained[]" class="marks-input" 
                                           placeholder="Enter marks obtained" min="0" max="${subject.max_marks}" 
                                           oninput="calculateTotal()">
                                </div>
                            `;
                            subjectsContainer.appendChild(subjectDiv);
                        });
                    });
            }
        }

        function calculateTotal() {
            let totalMarksObtained = 0;
            let totalMaxMarks = 0;

            document.querySelectorAll(".subject-row").forEach(row => {
                const marksObtained = parseFloat(row.querySelector("input[name='marks_obtained[]']").value) || 0;
                const maxMarks = parseFloat(row.querySelector("input[name='max_marks[]']").value) || 0;

                totalMarksObtained += marksObtained;
                totalMaxMarks += maxMarks;
            });

            document.getElementById("totalMarks").value = totalMarksObtained;
            
            const percentage = totalMaxMarks > 0 
                ? ((totalMarksObtained / totalMaxMarks) * 100).toFixed(2) 
                : 0;
            document.getElementById("percentage").value = percentage + "%";
            
            // Automatically determine and display result (40% threshold)
            const resultDisplay = document.getElementById("resultDisplay");
            const statusText = document.getElementById("statusText");
            const resultInput = document.getElementById("result");
            
            if (percentage >= 40) {
                statusText.textContent = "PASS";
                resultDisplay.className = "result-status pass";
                resultInput.value = "Pass";
            } else {
                statusText.textContent = "FAIL";
                resultDisplay.className = "result-status fail";
                resultInput.value = "Fail";
            }
            
            resultDisplay.style.display = (totalMarksObtained > 0) ? "block" : "none";
        }

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
</html> -->