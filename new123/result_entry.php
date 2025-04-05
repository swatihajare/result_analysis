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

    $sql = "SELECT id, subject_name, subject_type, max_marks FROM results WHERE branch = ? AND semester = ?";
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
    $branch = $_POST['branch'];
    $semester = $_POST['semester'];
    $totalMarks = $_POST['totalMarks'];
    $result = $_POST['result'];
    $percentage = $_POST['percentage'];
    
    // Prepare subject data
    $subject_data = [];
    foreach ($_POST['subject_id'] as $index => $subject_id) {
        $subject_data[] = [
            'id' => $subject_id,
            'name' => $_POST['subject_name'][$index],
            'theory' => $_POST['theory'][$index] ?? null,
            'practical' => $_POST['practical'][$index] ?? null,
            'sla' => $_POST['sla'][$index] ?? null
        ];
    }
    
    // Convert subject data to JSON
    $subject_json = json_encode($subject_data);

    // Insert into database
    $sql = "INSERT INTO student_results 
            (student_id, branch, semester, subject_name, total_marks, result, percentage) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssisd", $student_id, $branch, $semester, $subject_json, $totalMarks, $result, $percentage);
    
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
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Fill Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .subject-row {
            margin-bottom: 15px;
            text-align: left;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 5px;
        }
        .subject-label {
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
            color: #333;
        }
        .marks-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 10px;
        }
        .marks-input-container {
            flex: 1;
            min-width: 100px;
        }
        .marks-input {
            width: 100%;
            text-align: center;
        }
        .radio-group {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 15px 0;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            font-size: 16px;
        }
        button:hover {
            background: #45a049;
        }
        @media (max-width: 480px) {
            .marks-group {
                flex-direction: column;
            }
            .marks-input-container {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Fill Your Result Details</h2>
        <form id="detailsForm" method="POST">
            <input type="hidden" id="student_id" name="student_id" value="<?php echo $_SESSION['student_id']; ?>">
            
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
            
            <label>Result:</label>
            <div class="radio-group">
                <label><input type="radio" name="result" value="Pass" required> Pass</label>
                <label><input type="radio" name="result" value="Fail"> Fail</label>
            </div>
            
            <label>Percentage:</label>
            <input type="text" id="percentage" name="percentage" readonly>
            
            <button type="submit">Submit Results</button>
        </form>
    </div>

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
                                                   placeholder="Theory" min="0" max="70" oninput="calculateTotal()">
                                        </div>
                                    ` : `<input type="hidden" name="theory[]" value="">`}
                                    
                                    ${subject.subject_type.includes("Practical") ? `
                                        <div class="marks-input-container">
                                            <label>Practical Marks (Max 50):</label>
                                            <input type="number" name="practical[]" class="marks-input practical" 
                                                   placeholder="Practical" min="0" max="50" oninput="calculateTotal()">
                                        </div>
                                    ` : `<input type="hidden" name="practical[]" value="">`}
                                    
                                    ${subject.subject_type.includes("SLA") ? `
                                        <div class="marks-input-container">
                                            <label>SLA Marks (Max 30):</label>
                                            <input type="number" name="sla[]" class="marks-input sla" 
                                                   placeholder="SLA" min="0" max="30" oninput="calculateTotal()">
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
            
            // Auto-set pass/fail based on percentage (assuming 35% is passing)
            if (percentage > 0) {
                const passRadio = document.querySelector("input[value='Pass']");
                const failRadio = document.querySelector("input[value='Fail']");
                if (percentage >= 35) {
                    passRadio.checked = true;
                } else {
                    failRadio.checked = true;
                }
            }
        }
    </script>
</body>
</html> -->