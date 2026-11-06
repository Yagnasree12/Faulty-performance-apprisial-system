<?php
// Database connection settings
$servername = "localhost";
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "partb"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the 'rows' parameter is set and not empty
    if (isset($_POST['rows']) && !empty($_POST['rows'])) {
        $rows = json_decode($_POST['rows'], true); // Decode the JSON data

        // Check if JSON decoding was successful
        if (json_last_error() === JSON_ERROR_NONE) {
            // Prepare the SQL statement
            $stmt = $conn->prepare("INSERT INTO appraisal_results (course_name, class_size, n1, n2, n3, percentage, score) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)
                                    ON DUPLICATE KEY UPDATE class_size = VALUES(class_size), n1 = VALUES(n1), n2 = VALUES(n2), n3 = VALUES(n3), percentage = VALUES(percentage), score = VALUES(score)");

            // Check if the statement was prepared successfully
            if ($stmt === false) {
                die("Error preparing the statement: " . $conn->error);
            }

            // Bind parameters
            $stmt->bind_param("siiiiii", $course_name, $class_size, $n1, $n2, $n3, $percentage, $score);

            // Loop through each row and execute the insert or update
            foreach ($rows as $row) {
                // Validate and sanitize the incoming data before using it
                $course_name = isset($row['course_name']) ? trim($row['course_name']) : null;
                $class_size = isset($row['class_size']) ? (int)$row['class_size'] : 0;
                $n1 = isset($row['n1']) ? (int)$row['n1'] : 0;
                $n2 = isset($row['n2']) ? (int)$row['n2'] : 0;
                $n3 = isset($row['n3']) ? (int)$row['n3'] : 0;
                $percentage = isset($row['percentage']) ? (float)$row['percentage'] : 0;
                $score = isset($row['score']) ? (int)$row['score'] : 0;

                // Execute the prepared statement
                if (!$stmt->execute()) {
                    echo json_encode(["status" => "error", "message" => "Failed to insert or update data"]);
                    exit;
                }
            }

            // Close the statement and connection
            $stmt->close();
            $conn->close();

            // Return success message
            echo json_encode(["status" => "success", "message" => "Data submitted successfully"]);
            exit; // Exit to prevent further script execution
        } else {
            // If JSON decoding fails, return an error message
            echo json_encode(["status" => "error", "message" => "Invalid JSON data received"]);
            exit;
        }
    } else {
        // If 'rows' parameter is not set or empty, return an error message
        echo json_encode(["status" => "error", "message" => "'rows' parameter is missing or empty"]);
        exit;
    }
}
?>

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
            color: #333;
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

        h3 {
            color: #ff0066;
            font-weight: bold;
            text-align: center;
        }

        .table-responsive {
            margin: 20px auto;
            max-width: 1200px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
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
            background-color: #891bbb;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background-color 0.3s, transform 0.3s;
        }

        button:hover {
            background-color: #008CBA;
            transform: translateY(-2px);
        }
        .submit{
            display: block;
            margin: 20px auto;
            background-color: #C70039;
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
            border: none;
            transition: background-color 0.3s;
        }

        .submit:hover {
            background-color: #FF5733;
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

        .icons {
            font-size: 1.2rem;
        }

        .form-validation-message {
            color: red;
            font-size: 0.9rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            table {
                font-size: 0.85rem;
            }
        }
        .add-row{
            padding:10px;
            margin:25px;
        }
    </style>
</head>

<body>
    <header>
        <img src="https://www.pscmr.ac.in/header.jpg" alt="Header Image">
    </header>

    <h3>2. Course Taught - (Two Previous Semesters) Results &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Max.Score=80)</h3>
    <button type="button" id="add-row" class="add-row">
        <i class="fas fa-plus-circle"></i> Add New Row
    </button>

    <form id="appraisal-form">
        <div class="table-responsive">
            <table class="table table-hover" id="course-table">
                <thead class="table-success">
                    <tr>
                        <th>S.No</th>
                        <th>Name of the Course</th>
                        <th>Size of the Class</th>
                        <th>Results - No. of students with marks range (≥75 / ≥60 and >75 / Pass with below <60)</th>
                        <th>Results Percentage</th>
                        <th>Score (Max.Score-80)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>CCIDF - Cyber Crime & Digital Forensics</td>
                        <td><input type="number" id="class-size-1" placeholder="Class Size" /></td>
                        <td>
                            ≥75: <input type="number" id="n1_1" placeholder="n1"> <br>
                            ≥60 & <75: <input type="number" id="n2_1" placeholder="n2" style="margin-top: 5px;"> <br>
                            <60: <input type="number" id="n3_1" placeholder="n3" style="margin-top: 5px;">
                        </td>
                        <td><input type="text" id="percentage1" readonly></td>
                        <td><input type="text" id="score1" readonly></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>CNS - Cryptography & Network Security</td>
                        <td><input type="number" id="class-size-2" placeholder="Class Size" /></td>
                        <td>
                            ≥75: <input type="number" id="n1_2" placeholder="n1"> <br>
                            ≥60 & <75: <input type="number" id="n2_2" placeholder="n2" style="margin-top: 5px;"> <br>
                            <60: <input type="number" id="n3_2" placeholder="n3" style="margin-top: 5px;">
                        </td>
                        <td><input type="text" id="percentage2" readonly></td>
                        <td><input type="text" id="score2" readonly></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align:right;">Total</td>
                        <td><input type="text" id="totalClassSize" readonly></td>
                        <td><input type="text" id="totalResults" readonly></td>
                        <td><input type="text" id="totalPercentage" readonly></td>
                        <td><input type="text" id="totalScore" readonly></td>
                    </tr>
                </tfoot>
            </table>
            <div class="pagination">
                <a href="2a.php" id="prev-page"><i class="fas fa-arrow-left icons"></i> Previous</a>
                <a href="c.php" id="next-page">Next <i class="fas fa-arrow-right icons"></i></a>
            </div>

            <p id="validation-message" class="form-validation-message"></p>

            <center>
                <button type="button" id="submit-form" class="submit">
                    <i class="fas fa-check-circle"></i> Submit
                </button>
            </center>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script>
    function updateCalculations(rowId) {
    const classSize = parseInt(document.getElementById(`class-size-${rowId}`).value) || 0;
    const n1 = parseInt(document.getElementById(`n1_${rowId}`).value) || 0;
    const n2 = parseInt(document.getElementById(`n2_${rowId}`).value) || 0;
    const n3 = parseInt(document.getElementById(`n3_${rowId}`).value) || 0;

    // Calculate the total number of students in the row
    const totalInRow = n1 + n2 + n3;

    // Calculate the percentage based on the class size
    let percentage = classSize > 0 ? (totalInRow / classSize) * 100 : 0;

    // Ensure percentage does not exceed 100%
    if (percentage > 100) {
        percentage = 100;
    }

    // Calculate score (80% of the percentage) but cap it at 80 points
    let score = (percentage * 0.8).toFixed(2);

    // Ensure the score does not exceed 80
    if (parseFloat(score) > 80) {
        score = 80;
    }

    // Update the percentage and score fields
    document.getElementById(`percentage${rowId}`).value = percentage.toFixed(2) + '%';
    document.getElementById(`score${rowId}`).value = score;

    // Recalculate totals for the entire table
    calculateTotals();
}

function calculateTotals() {
    let totalClassSize = 0;
    let totalResults = 0;
    let totalScore = 0;

    const rows = document.querySelectorAll('#course-table tbody tr');
    rows.forEach((row, index) => {
        const rowId = index + 1;
        const classSize = parseInt(document.getElementById(`class-size-${rowId}`).value) || 0;
        const n1 = parseInt(document.getElementById(`n1_${rowId}`).value) || 0;
        const n2 = parseInt(document.getElementById(`n2_${rowId}`).value) || 0;
        const n3 = parseInt(document.getElementById(`n3_${rowId}`).value) || 0;

        // Total students in the current row
        const totalInRow = n1 + n2 + n3;
        totalClassSize += classSize;
        totalResults += totalInRow;

        // Get the individual row score from the `score` field
        totalScore += parseFloat(document.getElementById(`score${rowId}`).value) || 0;
    });

    // Calculate total percentage for all rows
    const totalPercentage = totalClassSize > 0 ? (totalResults / totalClassSize) * 100 : 0;

    // Ensure total percentage is capped at 100%
    const finalTotalPercentage = totalPercentage > 100 ? 100 : totalPercentage;

    // Update the total fields
    document.getElementById('totalClassSize').value = totalClassSize;
    document.getElementById('totalResults').value = totalResults;
    document.getElementById('totalPercentage').value = finalTotalPercentage.toFixed(2) + '%';
    document.getElementById('totalScore').value = totalScore.toFixed(2);
}

// Event listener for input changes to update calculations
document.querySelectorAll('input[type="number"]').forEach(input => {
    input.addEventListener('input', function () {
        const rowId = this.id.split('_')[1] || this.id.split('-')[2];
        updateCalculations(rowId);
    });
});

// Add new row in the table
document.getElementById('add-row').addEventListener('click', function () {
    const table = document.getElementById('course-table').getElementsByTagName('tbody')[0];
    const rowCount = table.rows.length + 1;

    const newRow = `
        <tr>
            <td>${rowCount}</td>
            <td><input type="text" placeholder="Course Name" /></td>
            <td><input type="number" id="class-size-${rowCount}" placeholder="Class Size" /></td>
            <td>
                ≥75: <input type="number" id="n1_${rowCount}" placeholder="n1"> <br>
                ≥60 & <75: <input type="number" id="n2_${rowCount}" placeholder="n2" style="margin-top: 5px;"> <br>
                <60: <input type="number" id="n3_${rowCount}" placeholder="n3" style="margin-top: 5px;">
            </td>
            <td><input type="text" id="percentage${rowCount}" readonly></td>
            <td><input type="text" id="score${rowCount}" readonly></td>
        </tr>
    `;

    table.insertAdjacentHTML('beforeend', newRow);

    // Add event listeners for new row inputs
    document.querySelectorAll(`#n1_${rowCount}, #n2_${rowCount}, #n3_${rowCount}, #class-size-${rowCount}`).forEach(input => {
        input.addEventListener('input', function () {
            updateCalculations(rowCount);
        });
    });
});

// Form submission handler
document.getElementById('submit-form').addEventListener('click', function () {
    const allInputs = document.querySelectorAll('input[type="number"], input[type="text"]');
    let isValid = true;
    const rowsData = [];

    // Validation check
    allInputs.forEach(input => {
        if (!input.value && input.id.startsWith('n')) {
            isValid = false;
        }
    });

    if (!isValid) {
        document.getElementById('validation-message').innerText = "Please fill in all fields before submitting.";
    } else {
        document.getElementById('validation-message').innerText = "";

        // Collect row data for submission
        const tableRows = document.querySelectorAll('#course-table tbody tr');

        tableRows.forEach((row, index) => {
            const courseName = row.querySelector('input[type="text"]').value;
            const classSize = document.getElementById(`class-size-${index + 1}`).value;
            const n1 = document.getElementById(`n1_${index + 1}`).value;
            const n2 = document.getElementById(`n2_${index + 1}`).value;
            const n3 = document.getElementById(`n3_${index + 1}`).value;
            const percentage = document.getElementById(`percentage${index + 1}`).value;
            const score = document.getElementById(`score${index + 1}`).value;

            rowsData.push({
                course_name: courseName,
                class_size: classSize,
                n1: n1,
                n2: n2,
                n3: n3,
                percentage: percentage,
                score: score
            });
        });

        // Send data to the server using Fetch API
        fetch('b.php', {
            method: 'POST',
            body: new URLSearchParams({
                rows: JSON.stringify(rowsData)
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            document.getElementById('appraisal-form').reset(); // Reset the form
            calculateTotals(); // Recalculate totals after reset
        })
        .catch(error => {
            console.error('Error:', error);
            alert("An error occurred. Please try again.");
        });
    }
});
</script>
</body>
</html>
