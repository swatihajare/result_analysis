<?php
// Start session to store form data temporarily
session_start();

// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'result_management';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $branch = isset($_POST['branch']) ? htmlspecialchars(trim($_POST['branch'])) : '';
    $semester = isset($_POST['semester']) ? intval($_POST['semester']) : 0;
    $num_subjects = isset($_POST['num_subjects']) ? intval($_POST['num_subjects']) : 0;
    $students = isset($_POST['students']) ? intval($_POST['students'])) : 0;
    
    // Basic validation
    $errors = [];
    
    if (empty($branch)) {
        $errors[] = "Branch is required";
    }
    
    if ($semester < 1 || $semester > 6) {
        $errors[] = "Invalid semester selected";
    }
    
    if ($num_subjects < 1) {
        $errors[] = "Number of subjects must be at least 1";
    }
    
    if ($students < 1) {
        $errors[] = "Number of students must be at least 1";
    }
    
    // Collect subject data
    $subjects = [];
    for ($i = 1; $i <= $num_subjects; $i++) {
        $subject_name = isset($_POST["subject{$i}_name"]) ? htmlspecialchars(trim($_POST["subject{$i}_name"])) : '';
        $subject_types = isset($_POST["subject{$i}_type"]) ? $_POST["subject{$i}_type"] : [];
        $max_marks = isset($_POST["subject{$i}_maxMarks"]) ? intval($_POST["subject{$i}_maxMarks"])) : 0;
        
        if (empty($subject_name)) {
            $errors[] = "Subject {$i} name is required";
        }
        
        if (empty($subject_types)) {
            $errors[] = "Subject {$i} must have at least one type selected";
        }
        
        if ($max_marks < 1) {
            $errors[] = "Subject {$i} must have valid maximum marks";
        }
        
        $subjects[] = [
            'name' => $subject_name,
            'types' => $subject_types,
            'max_marks' => $max_marks
        ];
    }
    
    // If validation errors, redirect back with errors
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: result.html');
        exit;
    }
    
    // Store data in session for confirmation
    $_SESSION['form_data'] = [
        'branch' => $branch,
        'semester' => $semester,
        'num_subjects' => $num_subjects,
        'students' => $students,
        'subjects' => $subjects
    ];
}

// Retrieve data from session
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : null;
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];

// Clear session data after retrieval
unset($_SESSION['form_data']);
unset($_SESSION['errors']);

if (!$form_data) {
    header('Location: result.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Result Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .data-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .subject-item {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn-back {
            background: #6c757d;
        }
        .btn-back:hover {
            background: #5a6268;
        }
        .error {
            color: red;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Review Result Data</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <h3>Please correct the following errors:</h3>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="data-section">
            <h3>Basic Information</h3>
            <p><strong>Branch:</strong> <?php echo htmlspecialchars($form_data['branch']); ?></p>
            <p><strong>Semester:</strong> <?php echo htmlspecialchars($form_data['semester']); ?></p>
            <p><strong>Number of Subjects:</strong> <?php echo htmlspecialchars($form_data['num_subjects']); ?></p>
            <p><strong>Enrolled Students:</strong> <?php echo htmlspecialchars($form_data['students']); ?></p>
        </div>
        
        <div class="data-section">
            <h3>Subjects Information</h3>
            <?php foreach ($form_data['subjects'] as $index => $subject): ?>
                <div class="subject-item">
                    <p><strong>Subject <?php echo $index + 1; ?>:</strong> <?php echo htmlspecialchars($subject['name']); ?></p>
                    <p><strong>Types:</strong> <?php echo implode(', ', $subject['types']); ?></p>
                    <p><strong>Maximum Marks:</strong> <?php echo htmlspecialchars($subject['max_marks']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="actions">
            <a href="save_result.php" class="btn">Confirm and Save</a>
            <a href="result.html" class="btn btn-back">Go Back and Edit</a>
        </div>
    </div>
</body>
</html>