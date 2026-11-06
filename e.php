<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Performance Appraisal System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            background-color: #f0f4f7;
            font-family: Arial, sans-serif;
            padding: 20px;
            margin: 0;
        }

        header img {
            width: 100%;
            max-height: 150px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h3 {
            color: #ff0066;
            font-weight: bold;
            text-align: center;
            margin-top: 30px;
            margin-bottom: 20px;
            animation: fadeIn 1s ease-in-out;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            text-align: center;
            padding: 15px;
            transition: background-color 0.3s;
        }

        th {
            background-color: #891bbb;
            color: white;
        }

        td {
            background-color: white;
        }

        td:hover {
            background-color: #f9f9f9;
        }

        input[type="number"], input[type="text"] {
            width: 90%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: box-shadow 0.3s;
        }

        input[type="number"]:focus, input[type="text"]:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(137, 27, 187, 0.3);
        }

        button {
            background-color: #891bbb;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
            cursor: pointer;
        }

        button:hover {
            background-color: #008CBA;
            transform: translateY(-2px);
        }

        #add-row-btn {
            margin-top: 15px;
            background-color: #28a745;
        }

        #add-row-btn:hover {
            background-color: #218838;
        }

        .pagination {
            display: flex;
            justify-content: right;
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

        /* Animations */
        .fadeIn {
            animation: fadeInEffect 0.8s ease-in-out;
        }

        @keyframes fadeInEffect {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <header>
        <img src="https://www.pscmr.ac.in/header.jpg" alt="Header Image">
    </header>

    <h3>7. Student Counselling / Effectiveness of the Counseling</h3>

    <?php
// Database connection
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

// Insert or update the record
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure all form fields are set using isset
    $id = isset($_POST['id']) ? $_POST['id'] : ''; // For updates, ID should be checked if present
    $description = isset($_POST['description']) ? $_POST['description'] : ''; // Description check
    $students_alloted = isset($_POST['students_allotted']) ? $_POST['students_allotted'] : 0; // Ensure students_alloted key exists
    $students_successful = isset($_POST['students_successful']) ? $_POST['students_successful'] : 0; // Ensure students_successful key exists
    $percentage = isset($_POST['percentage']) ? $_POST['percentage'] : 0; // Ensure percentage key exists
    $score = isset($_POST['score']) ? $_POST['score'] : 0; // Ensure score key exists
    $self_score = isset($_POST['self_score']) ? $_POST['self_score'] : 0; // Ensure self_score key exists
    $hod_score = isset($_POST['hod_score']) ? $_POST['hod_score'] : 0; // Ensure hod_score key exists

    if ($id) {
        // Update the existing record if ID is provided
        $sql = "UPDATE student_counselling SET 
            description='$description', 
            students_alloted='$students_alloted', 
            students_successful='$students_successful', 
            percentage='$percentage', 
            score='$score', 
            self_score='$self_score', 
            hod_score='$hod_score' 
            WHERE id='$id'";
    } else {
        // Insert a new record if no ID is provided (new record)
        $sql = "INSERT INTO student_counselling
            (description, students_alloted, students_successful, percentage, score, self_score, hod_score)
            VALUES ('$description', '$students_alloted', '$students_successful', '$percentage', '$score', '$self_score', '$hod_score')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Record saved successfully</div>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch the record to edit, if available
$edit_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : ''; // Fetch 'edit_id' safely
$edit_data = [];

if ($edit_id) {
    $result = $conn->query("SELECT * FROM student_counselling WHERE id='$edit_id'");
    $edit_data = $result->fetch_assoc();
}

// Get the total number of records for pagination
$limit = 3; // Number of rows per page
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$total_result = $conn->query("SELECT COUNT(*) AS total FROM student_counselling");
$total_records = $total_result->fetch_assoc()['total'];

?>
    <form id="counseling-form" method="POST">
        <input type="hidden" name="id" value="<?php echo isset($edit_data['id']) ? $edit_data['id'] : ''; ?>">
        <table class="table table-hover fadeIn">
            <thead>
                <tr>
                    <th>S.NO</th>
                    <th>Description</th>
                    <th>Number of Students Allotted (X)</th>
                    <th>Number of Students Successful (Y)</th>
                    <th>(Y/X)*100</th>
                    <th>Score</th>
                    <th>Self Score</th>
                    <th>HoD Score</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <tr>
                    <td><input type="number"></td>
                    <td>Results (Score will be awarded based on number of backlogs)</td>
                    <td><input type="number" name="students_alloted" class="x-value" value="<?php echo isset($edit_data['students_alloted']) ? $edit_data['students_alloted'] : ''; ?>" required></td>
                    <td><input type="number" name="students_successful" class="y-value" value="<?php echo isset($edit_data['students_successful']) ? $edit_data['students_successful'] : ''; ?>" required></td>
                    <td><input type="number" name="percentage" class="percentage-value" value="<?php echo isset($edit_data['percentage']) ? $edit_data['percentage'] : ''; ?>" readonly></td>
                    <td><input type="number" name="score" class="score-value" value="<?php echo isset($edit_data['score']) ? $edit_data['score'] : ''; ?>" readonly></td>
                    <td><input type="number" name="self_score" value="<?php echo isset($edit_data['self_score']) ? $edit_data['self_score'] : ''; ?>" required></td>
                    <td><input type="number" name="hod_score" value="<?php echo isset($edit_data['hod_score']) ? $edit_data['hod_score'] : ''; ?>" required></td>
                </tr>
            </tbody>
            <tr>
                    <td><input type="number"></td>
                    <td>Career Guidance (Score will be awarded based on participation in extra and co-curricular activities)</td>
                    <td><input type="number" name="students_alloted" class="x-value" value="<?php echo isset($edit_data['students_alloted']) ? $edit_data['students_alloted'] : ''; ?>" required></td>
                    <td><input type="number" name="students_successful" class="y-value" value="<?php echo isset($edit_data['students_successful']) ? $edit_data['students_successful'] : ''; ?>" required></td>
                    <td><input type="number" name="percentage" class="percentage-value" value="<?php echo isset($edit_data['percentage']) ? $edit_data['percentage'] : ''; ?>" readonly></td>
                    <td><input type="number" name="score" class="score-value" value="<?php echo isset($edit_data['score']) ? $edit_data['score'] : ''; ?>" readonly></td>
                    <td><input type="number" name="self_score" value="<?php echo isset($edit_data['self_score']) ? $edit_data['self_score'] : ''; ?>" required></td>
                    <td><input type="number" name="hod_score" value="<?php echo isset($edit_data['hod_score']) ? $edit_data['hod_score'] : ''; ?>" required></td>
                </tr>
                <tr>
                    <td><input type="number"></td>
                    <td>Student Regularity (Score will be awarded based on the percentage) </td>
                    <td><input type="number" name="students_alloted" class="x-value" value="<?php echo isset($edit_data['students_alloted']) ? $edit_data['students_alloted'] : ''; ?>" required></td>
                    <td><input type="number" name="students_successful" class="y-value" value="<?php echo isset($edit_data['students_successful']) ? $edit_data['students_successful'] : ''; ?>" required></td>
                    <td><input type="number" name="percentage" class="percentage-value" value="<?php echo isset($edit_data['percentage']) ? $edit_data['percentage'] : ''; ?>" readonly></td>
                    <td><input type="number" name="score" class="score-value" value="<?php echo isset($edit_data['score']) ? $edit_data['score'] : ''; ?>" readonly></td>
                    <td><input type="number" name="self_score" value="<?php echo isset($edit_data['self_score']) ? $edit_data['self_score'] : ''; ?>" required></td>
                    <td><input type="number" name="hod_score" value="<?php echo isset($edit_data['hod_score']) ? $edit_data['hod_score'] : ''; ?>" required></td>
                </tr>
        </table>
        <div class="pagination">
                <a href="d1.php" id="prev-page"><i class="fas fa-arrow-left icons"></i> Previous</a>
                <a href="f.php" id="next-page">Next <i class="fas fa-arrow-right icons"></i></a>
            </div>
        <button type="submit">submit</button>
        <button type="button" id="add-row-btn">Add New Row</button>
    </form>

    <?php
// Database connection
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

// Insert or update the record
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id']) ? $_POST['id'] : ''; // Fixed line
    $description = $_POST['description'];
    $students_alloted = $_POST['students_alloted'];
    $students_successful = $_POST['students_successful'];
    $percentage = $_POST['percentage'];
    $score = $_POST['score'];
    $self_score = $_POST['self_score'];
    $hod_score = $_POST['hod_score'];

    if ($id) {
        // Update the existing record
        $sql = "UPDATE student_counselling SET 
            description='$description', 
            students_alloted='$students_alloted', 
            students_successful='$students_successful', 
            percentage='$percentage', 
            score='$score', 
            self_score='$self_score', 
            hod_score='$hod_score' 
            WHERE id='$id'";
    } else {
        // Insert a new record
        $sql = "INSERT INTO student_counselling
            (description, students_alloted, students_successful, percentage, score, self_score, hod_score)
            VALUES ('$description', '$students_alloted', '$students_successful', '$percentage', '$score', '$self_score', '$hod_score')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Record saved successfully</div>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch the record to edit, if available
$edit_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : ''; // Fixed line
$edit_data = [];

if ($edit_id) {
    $result = $conn->query("SELECT * FROM student_counselling WHERE id='$edit_id'");
    $edit_data = $result->fetch_assoc();
}
?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Calculate percentage and score
            function calculatePercentageAndScore() {
                $('.x-value, .y-value').on('input', function () {
                    let x = parseFloat($(this).closest('tr').find('.x-value').val());
                    let y = parseFloat($(this).closest('tr').find('.y-value').val());

                    if (!isNaN(x) && !isNaN(y) && x !== 0) {
                        let percentage = (y / x) * 100;
                        let score = percentage > 90 ? 10 : percentage > 80 ? 8 : percentage > 70 ? 6 : 4;

                        $(this).closest('tr').find('.percentage-value').val(percentage.toFixed(2));
                        $(this).closest('tr').find('.score-value').val(score);
                    }
                });
            }

            calculatePercentageAndScore();

            // Add new row
            $('#add-row-btn').click(function () {
                let newRow = `<tr>
                    <td><input type="number"></td>
                    <td>Results (Score will be awarded based on number of backlogs)</td>
                    <td><input type="number" class="x-value"></td>
                    <td><input type="number" class="y-value"></td>
                    <td><input type="number" class="percentage-value" readonly></td>
                    <td><input type="number" class="score-value" readonly></td>
                    <td><input type="number" class="self-score"></td>
                    <td><input type="number" class="hod-score"></td>
                </tr>`;
                $('#table-body').append(newRow);
                calculatePercentageAndScore();
            });
        });
    </script>
</body>

</html>
