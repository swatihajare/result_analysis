<?php
// Database Connection
$conn = new mysqli("localhost", "root", "", "result_analysis");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Pass and Fail Percentages Based on Branch
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['branch'])) {
    $branch = $_GET['branch'];

    // Query to calculate pass and fail percentages for each semester
    $sql = "SELECT semester, 
                   SUM(CASE WHEN result = 'Pass' THEN 1 ELSE 0 END) * 100.0 / COUNT(*) AS pass_percentage,
                   SUM(CASE WHEN result = 'Fail' THEN 1 ELSE 0 END) * 100.0 / COUNT(*) AS fail_percentage
            FROM student_results
            WHERE branch = ?
            GROUP BY semester";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $branch);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[$row['semester']] = [
            'pass_percentage' => round($row['pass_percentage'], 2), // Round to 2 decimal places
            'fail_percentage' => round($row['fail_percentage'], 2)  // Round to 2 decimal places
        ];
    }

    echo json_encode($data);
    exit();
}

$conn->close();
?>