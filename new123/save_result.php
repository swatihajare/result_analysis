<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "result_analysis");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get all form data
    $year = (int)$_POST["year"];
    $branch = $_POST["branch"];
    $semester = (int)$_POST["semester"];
    $students = (int)$_POST["students"];
    $num_subjects = (int)$_POST["num_subjects"];

    // Prepare the SQL statement
    $sql = "INSERT INTO results (year, branch, semester, subject_name, subject_type, max_marks, students_enrolled) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    // Process each subject
    for ($i = 1; $i <= $num_subjects; $i++) {
        if (!isset($_POST["subject$i"])) continue;

        $subject_name = $_POST["subject$i"];
        $max_marks = (int)$_POST["subject{$i}_maxMarks"];
        
        // Build subject_type string with marks
        $subject_type_parts = [];
        $types = isset($_POST["subject{$i}_type"]) ? $_POST["subject{$i}_type"] : [];
        
        foreach ($types as $type) {
            $marks = 0;
            switch ($type) {
                case 'Theory':
                    $marks = isset($_POST["subject{$i}_theory_marks"]) ? (int)$_POST["subject{$i}_theory_marks"] : 0;
                    break;
                case 'Practical':
                    $marks = isset($_POST["subject{$i}_practical_marks"]) ? (int)$_POST["subject{$i}_practical_marks"] : 0;
                    break;
                case 'SLA':
                    $marks = isset($_POST["subject{$i}_sla_marks"]) ? (int)$_POST["subject{$i}_sla_marks"] : 0;
                    break;
            }
            $subject_type_parts[] = "$type($marks)";
        }
        
        $subject_type = implode(", ", $subject_type_parts);

        // Bind parameters
        $stmt->bind_param("isissii", 
            $year, 
            $branch, 
            $semester, 
            $subject_name, 
            $subject_type, 
            $max_marks, 
            $students
        );
        
        $stmt->execute();
    }

    echo "<script>
            alert('Data Added Successfully!'); 
            window.location.href='enter_data.html';
          </script>";

    $stmt->close();
    $conn->close();
}
?>