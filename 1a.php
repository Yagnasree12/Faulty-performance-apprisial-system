<?php
// Database connection (Assuming your database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "partb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert data into the database
if (isset($_POST['submit'])) {
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO lectures (course_lab_name, year_semester, teaching_periods_planned, teaching_periods_conducted, percentage_classes_engaged, strategy_method_used, expected_outcome, no_of_students, year_class, outcome_achieved, score_achieved, api_score, self_score) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("ssiiiiissssiii", $course_lab_name, $year_semester, $teaching_periods_planned, $teaching_periods_conducted, $percentage_classes_engaged, $strategy_method_used, $expected_outcome, $no_of_students, $year_class, $outcome_achieved, $score_achieved, $api_score, $self_score);

    // Get form data
    $course_lab_name = $_POST['course_lab_name'] ?? '';
    $year_semester = $_POST['year_semester'] ?? '';
    $teaching_periods_planned = $_POST['teaching_periods_planned'] ?? 0;
    $teaching_periods_conducted = $_POST['teaching_periods_conducted'] ?? 0;
    $percentage_classes_engaged = $_POST['percentage_classes_engaged'] ?? 0;
    $strategy_method_used = $_POST['strategy_method_used'] ?? '';
    $expected_outcome = $_POST['expected_outcome'] ?? '';
    $no_of_students = $_POST['no_of_students'] ?? 0;
    $year_class = $_POST['year_class'] ?? '';
    $outcome_achieved = $_POST['outcome_achieved'] ?? '';
    $score_achieved = $_POST['score_achieved'] ?? 0;
    $api_score = $_POST['api_score'] ?? 0;
    $self_score = $_POST['self_score'] ?? 0;

    // Execute statement
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Update data
if (isset($_POST['update'])) {
    $stmt = $conn->prepare("UPDATE lectures SET course_lab_name = ?, year_semester = ?, teaching_periods_planned = ?, teaching_periods_conducted = ?, percentage_classes_engaged = ?, strategy_method_used = ?, expected_outcome = ?, no_of_students = ?, year_class = ?, outcome_achieved = ?, score_achieved = ?, api_score = ?, self_score = ? WHERE id = ?");
    
    $stmt->bind_param("ssiiiiissssiii", $course_lab_name, $year_semester, $teaching_periods_planned, $teaching_periods_conducted, $percentage_classes_engaged, $strategy_method_used, $expected_outcome, $no_of_students, $year_class, $outcome_achieved, $score_achieved, $api_score, $self_score, $id);

    // Get form data
    $id = $_POST['id'] ?? 0; // Assuming you have a hidden field for row ID in your form

    // Get other fields similarly...
    
    // Execute statement
    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Performance Appraisal System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f0f4f7;
            font-family: Arial, sans-serif;
            color: #333;
            padding: 20px;
        }
        header {
            width: 100%;
            text-align: center;
            margin-bottom: 20px;
        }

        header img {
            width: 100%;
            max-height: 150px;
            object-fit: cover;
        }

        h1, h2 {
            text-align: center;
            margin-top: 20px;
        }

        h1 {
            color: #1021d4;
        }

        h2 {
            color: #2c3dd0;
            font-style: italic;
        }

        .table-responsive {
            margin: 20px auto;
            max-width: 1200px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        th,
        td {
            text-align: center;
            padding: 12px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #891bbb;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        td input {
            width: 90%;
            border: none;
            background-color: #f7f7f7;
            padding: 6px;
            border-radius: 5px;
        }

        td input:focus {
            outline: 2px solid #008CBA;
        }

        button {
            background-color: #00796b;
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
            border: none;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #004d40 ;
        }
        .submit {
            background-color: #891bbb;
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
            border: none;
            transition: background-color 0.3s;
            align-items:center;
        }
        .submit:hover {
            background-color: #008CBA;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 10px 20px;
            background-color: #891bbb;
            border-radius: 5px;
            color: white;
            margin: 0 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: #008CBA;
        }

        .add-btn {
            background-color: #28a745;
            padding: 10px 15px;
            border-radius: 5px;
            color: white;
            margin-bottom: 20px;
            display: block;
            margin-left: auto;
        }

        .add-btn:hover {
            background-color: #218838;
        }

        .icons {
            font-size: 1.5rem;
            color: #891bbb;
        }

        .icons:hover {
            color: #008CBA;
            transition: color 0.3s;
        }

        @media (max-width: 768px) {
            table {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <img src="https://www.pscmr.ac.in/header.jpg" alt="Header Image">
    </header>
    <div class="container mt-5">
        <h1 class="text-center">Faculty Performance Appraisal System</h1>
        <a href="#" class="add-btn" id="add-row-btn">+ Add New Row</a>
        <form method="POST" action="">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Name of the Course/Lab</th>
                        <th>B.Tech./MBA Year / Semester</th>
                        <th>No. of Teaching Periods Planned</th>
                        <th>No. of Teaching Periods Conducted</th>
                        <th>% of Classes Engaged</th>
                        <th>Strategy/Method Used</th>
                        <th>Expected Outcome</th>
                        <th>No. of Students</th>
                        <th>Year/Class</th>
                        <th>Outcome Achieved</th>
                        <th>Score Achieved</th>
                        <th>API Score</th>
                        <th>Self Score</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <!-- Sample row data (should be generated dynamically from the database) -->
                    <tr>
                        <td>1</td>
                        <td>Cyber Crime & Digital Forensics</td>
                        <td>IV-I</td>
                        <td>62</td>
                        <td>66</td>
                        <td>75</td>
                        <td>Lecture, Group Discussion</td>
                        <td>Knowledge on Cyber Crime</td>
                        <td>60</td>
                        <td>IV</td>
                        <td>75%</td>
                        <td>4</td>
                        <td>5</td>
                        <td>4</td>
                        <td>
                            <button type="submit" name="update" class="submit">Update</button>
                            <button type="button" class="delete-row-btn">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="pagination">
                <a href="main.php">Previous</a>
                <a href="b.php">Next</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById("add-row-btn").addEventListener("click", function (e) {
            e.preventDefault();
            const tableBody = document.getElementById("table-body");
            const newRow = document.createElement("tr");

            newRow.innerHTML = `
                <td></td>
                <td><input type="text" name="course_lab_name[]" required></td>
                <td><input type="text" name="year_semester[]" required></td>
                <td><input type="number" name="teaching_periods_planned[]" required></td>
                <td><input type="number" name="teaching_periods_conducted[]" required></td>
                <td><input type="number" name="percentage_classes_engaged[]" required></td>
                <td><input type="text" name="strategy_method_used[]" required></td>
                <td><input type="text" name="expected_outcome[]" required></td>
                <td><input type="number" name="no_of_students[]" required></td>
                <td><input type="text" name="year_class[]" required></td>
                <td><input type="text" name="outcome_achieved[]" required></td>
                <td><input type="number" name="score_achieved[]" required></td>
                <td><input type="number" name="api_score[]" required></td>
                <td><input type="number" name="self_score[]" required></td>
                <td><button type="button" class="delete-row-btn">Delete</button></td>
            `;

            tableBody.appendChild(newRow);

            // Add event listener for the delete button in the new row
            newRow.querySelector(".delete-row-btn").addEventListener("click", function () {
                tableBody.removeChild(newRow);
            });

            // Update serial numbers
            updateSerialNumbers();
        });

        // Update serial numbers
        function updateSerialNumbers() {
            const rows = document.querySelectorAll("#table-body tr");
            rows.forEach((row, index) => {
                row.cells[0].innerText = index + 1; // Update serial number
            });
        }

        // Initial event listener for delete buttons in existing rows
        document.querySelectorAll(".delete-row-btn").forEach(button => {
            button.addEventListener("click", function () {
                const row = button.closest("tr");
                row.parentNode.removeChild(row);
                updateSerialNumbers();
            });
        });
    </script>
</body>
</html>
