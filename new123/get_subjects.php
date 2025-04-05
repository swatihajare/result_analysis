<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "result_analysis");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$branch = $_GET['branch'];
$semester = $_GET['semester'];

$sql = "SELECT subject_name, subject_type FROM results WHERE branch = ? AND semester = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $branch, $semester);
$stmt->execute();
$result = $stmt->get_result();

$subjects = [];
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row;
}

echo json_encode($subjects);

$stmt->close();
$conn->close();
?>