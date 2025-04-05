<?php
header('Content-Type: application/json');

// Database Connection
$conn = new mysqli("localhost", "root", "", "result_analysis");
if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

// Get input parameters
$branch = $_GET['branch'] ?? '';
$semester = $_GET['semester'] ?? '';
$enrollment = $_GET['enrollment'] ?? '';

if (empty($branch) || empty($semester) || empty($enrollment)) {
    die(json_encode(['error' => 'All fields are required']));
}

// Fetch student ID from enrollment number
$stmt = $conn->prepare("SELECT id FROM students WHERE enrollment_number = ?");
$stmt->bind_param("s", $enrollment);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die(json_encode(['error' => 'Student not found']));
}

$student = $result->fetch_assoc();
$student_id = $student['id'];

// Fetch student result
$stmt = $conn->prepare("SELECT * FROM student_results WHERE student_id = ? AND branch = ? AND semester = ?");
$stmt->bind_param("iss", $student_id, $branch, $semester);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die(json_encode(['error' => 'Result not found for this combination']));
}

$result_data = $result->fetch_assoc();

// Prepare response
$response = [
    'student_id' => $student_id,
    'branch' => $result_data['branch'],
    'semester' => $result_data['semester'],
    'subject_name' => $result_data['subject_name'],
    'total_marks' => $result_data['total_marks'],
    'result' => $result_data['result'],
    'percentage' => $result_data['percentage']
];

echo json_encode($response);
$conn->close();
?>