<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "result_analysis");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle remove action
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    if (isset($_SESSION['results'][$remove_id])) {
        unset($_SESSION['results'][$remove_id]);
    }
}

// Handle search
if (isset($_GET['enrollment'])) {
    $enrollment = $_GET['enrollment'];
    $semester = $_GET['semester'];
    
    $sql = "SELECT * FROM student_results WHERE enrollment = ? AND semester = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $enrollment, $semester);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        if (!isset($_SESSION['results'])) {
            $_SESSION['results'] = array();
        }
        
        while ($row = $result->fetch_assoc()) {
            $result_id = $row['enrollment'] . '-' . $row['semester'];
            $_SESSION['results'][$result_id] = $row;
        }
    } else {
        $noResultsMessage = 'No results found for the provided enrollment number and semester.';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Result Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            position: sticky;
            top: 0;
        }
        .search-container {
            margin-bottom: 20px;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        input, select, button {
            padding: 8px;
            margin: 5px 0;
        }
        .search-btn {
            background-color: #2196F3;
            color: white;
            border: none;
            cursor: pointer;
        }
        .remove-btn {
            background-color: #f44336;
            color: white;
            border: none;
            cursor: pointer;
            padding: 5px 10px;
        }
        .pass { color: green; font-weight: bold; }
        .fail { color: red; font-weight: bold; }
        .no-results { color: #666; font-style: italic; padding: 20px; }
    </style>
</head>
<body>

    <h2>Student Result Data</h2>
    
    <!-- Search Form -->
    <div class="search-container">
        <form method="GET" action="">
            <label>Enrollment Number:</label>
            <input type="number" name="enrollment" placeholder="Enter Enrollment No" required>
            
            <label>Semester:</label>
            <select name="semester" required>
                <option value="">Select Semester</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
            </select>
            
            <button type="submit" class="search-btn">Search</button>
            <li><a href="button.html">Go to back</a></li>
        </form>
    </div>

    <!-- Results Table -->
    <?php if (isset($_SESSION['results']) && count($_SESSION['results']) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Enrollment</th>
                    <th>Semester</th>
                    <th>Branch</th>
                    <th>Subject</th>
                    <th>Theory</th>
                    <th>Practical</th>
                    <th>SLA</th>
                    <th>Total</th>
                    <th>Result</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['results'] as $result_id => $row): 
                    $subject_data = json_decode($row['subject_name'], true);
                    foreach ($subject_data as $subject): 
                        // Convert values to integers before calculation
                        $theory = isset($subject['theory']) ? (int)$subject['theory'] : 0;
                        $practical = isset($subject['practical']) ? (int)$subject['practical'] : 0;
                        $sla = isset($subject['sla']) ? (int)$subject['sla'] : 0;
                        $subject_total = $theory + $practical + $sla;
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['enrollment']) ?></td>
                    <td><?= htmlspecialchars($row['semester']) ?></td>
                    <td><?= htmlspecialchars($row['branch']) ?></td>
                    <td><?= htmlspecialchars($subject['name']) ?></td>
                    <td><?= !empty($subject['theory']) ? htmlspecialchars($subject['theory']) : '-' ?></td>
                    <td><?= !empty($subject['practical']) ? htmlspecialchars($subject['practical']) : '-' ?></td>
                    <td><?= !empty($subject['sla']) ? htmlspecialchars($subject['sla']) : '-' ?></td>
                    <td><?= $subject_total ?></td>
                    <td class="<?= strtolower($row['result']) ?>"><?= htmlspecialchars($row['result']) ?></td>
                    <td><button class="remove-btn" onclick="window.location.href='?remove=<?= $result_id ?>'">Remove</button></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="7"><strong>Grand Total</strong></td>
                    <td><strong><?= htmlspecialchars($row['total_marks']) ?></strong></td>
                    <td colspan="2"><strong><?= htmlspecialchars($row['percentage']) ?>%</strong></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif (isset($noResultsMessage)): ?>
        <div class="no-results"><?= htmlspecialchars($noResultsMessage) ?></div>
    <?php else: ?>
        <div class="no-results">Search for student results using the form above</div>
    <?php endif; ?>

</body>
</html>