<?php
// Start output buffering
ob_start();

// Database connection setup
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

// Create table if it doesn't exist
$tableCreationQuery = "CREATE TABLE IF NOT EXISTS appraisals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sno INT NOT NULL,
    convener_member VARCHAR(255) NOT NULL,
    dept_level VARCHAR(255) NOT NULL,
    membership_details VARCHAR(255) NOT NULL,
    organization_name VARCHAR(255) NOT NULL,
    sub_category VARCHAR(255) NOT NULL,
    period VARCHAR(255) NOT NULL,
    self_score INT NOT NULL,
    hod_score INT NOT NULL,
    total_score INT NOT NULL
)";

$conn->query($tableCreationQuery);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Loop through each row in the form
    foreach ($_POST['sno'] as $index => $sno) {
        $convener = $conn->real_escape_string($_POST['convener'][$index]);
        $dept_level = $conn->real_escape_string($_POST['dept_level'][$index]);
        $membership = $conn->real_escape_string($_POST['membership'][$index]);
        $organization = $conn->real_escape_string($_POST['organization'][$index]);
        $sub_category = $conn->real_escape_string($_POST['sub_category'][$index]);
        $period = $conn->real_escape_string($_POST['period'][$index]);
        $self_score = intval($_POST['self_score'][$index]);
        $hod_score = intval($_POST['hod_score'][$index]);
        $total_score = $self_score + $hod_score;

        // Prepare statement to insert data
        $stmt = $conn->prepare("INSERT INTO appraisals (sno, convener_member, dept_level, membership_details, organization_name, sub_category, period, self_score, hod_score, total_score) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssiiii", $sno, $convener, $dept_level, $membership, $organization, $sub_category, $period, $self_score, $hod_score, $total_score);
        $stmt->execute();
    }
    echo "<script>alert('Submitted successfully');</script>";
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Performance Appraisal</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
          /* Global styling */
          body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f7f9fc;
            color: #333;
        }
        header {
            position: relative;
            text-align: center;
            z-index: 10;
        }
        header img {
            width: 100%;
            max-height: 150px;
            object-fit: cover;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        /* Styling for headings */
        h1, h3 {
            text-align: center;
            color: #333;
            font-weight: bold;
            margin-top: 20px;
            padding: 5px;
        }
        h1 {
            color: #2c3e50;
            font-size: 1.8rem;
        }
        h3 {
            color: #3498db;
            font-size: 1.2rem;
        }
        /* Table styling */
        table {
            margin: 20px auto;
            width: 90%;
            max-width: 1000px;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 1s ease;
        }
        @keyframes fadeInUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        thead {
            background-color: #3498db;
            color: #fff;
        }
        thead th {
            padding: 12px;
        }
        tbody tr:hover {
            background-color: #ecf3fa;
            transition: background-color 0.3s;
        }
        tbody td {
            padding: 10px;
            text-align: center;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 2px solid #ddd;
            border-radius: 5px;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:hover,
        input[type="text"]:focus,
        input[type="number"]:hover,
        input[type="number"]:focus {
            border-color: #3498db;
            outline: none;
        }
        /* Button styling */
        input[type="button"] {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            display: block;
            margin: 20px auto;
        }
        input[type="button"]:hover {
            background-color: #2c3e50;
            transform: scale(1.05);
        }
        /* Form validation messages */
        .error {
            color: red;
            font-size: 0.9rem;
            display: none;
            margin-top: 5px;
        }
        .pagination {
            display: flex;
            justify-content: right;
            margin-top: 20px;
        }
        .pagination a {
            padding: 10px 20px;
            background-color: #ea4ca7;
            border-radius: 5px;
            color: white;
            margin: 0 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .pagination a:hover {
            background-color: #dd0e63;
        }
    </style>
</head>
<body>
<header>
    <img src="https://www.pscmr.ac.in/header.jpg" alt="Header Image">
</header>

<form id="appraisalForm" method="post">
    <h1>4. Profession Related Contribution</h1>
    <h3>Involvement in Institutional/Departmental Activities, Membership of Professional Associations Committees (Total Score: 75)</h3>

    <table id="appraisalTable" class="table table-bordered text-center">
        <thead>
            <tr>
                <th>S.NO</th>
                <th>Convener/Member</th>
                <th>Department Level</th>
                <th>Membership Details</th>
                <th>Organization Name</th>
                <th>Sub Category</th>
                <th>Period</th>
                <th>Self Score (Max 75)</th>
                <th>HOD Score</th>
                <th>Total Score</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="number" class="form-control" name="sno[]" required></td>
                <td><input type="text" class="form-control" name="convener[]" required></td>
                <td><input type="text" class="form-control" name="dept_level[]" required></td>
                <td><input type="text" class="form-control" name="membership[]" required></td>
                <td><input type="text" class="form-control" name="organization[]" required></td>
                <td><input type="text" class="form-control" name="sub_category[]" required></td>
                <td><input type="text" class="form-control" name="period[]" required></td>
                <td><input type="number" class="form-control self-score" name="self_score[]" max="75" required></td>
                <td><input type="number" class="form-control hod-score" name="hod_score[]" required></td>
                <td><input type="number" class="form-control total-score" name="total_score[]" readonly></td>
            </tr>
        </tbody>
    </table>
    <div class="pagination">
                <a href="5.php" id="prev-page"><i class="fas fa-arrow-left icons"></i> Previous</a>
                <a href="part-c.php" id="next-page">Next <i class="fas fa-arrow-right icons"></i></a>
            </div>
    <div class="text-center">
        <button type="button" onclick="addRow()" class="btn btn-info">Add New Row</button>
        <input type="submit" class="btn btn-primary" value="Submit">
    </div>
</form>

<script>
// Adds a new row to the table dynamically
function addRow() {
    const table = document.getElementById('appraisalTable').getElementsByTagName('tbody')[0];
    const newRow = table.rows[0].cloneNode(true);
    newRow.querySelectorAll('input').forEach(input => input.value = '');
    table.appendChild(newRow);
}

// Calculate total score automatically
document.getElementById('appraisalTable').addEventListener('input', function (e) {
    if (e.target.classList.contains('self-score') || e.target.classList.contains('hod-score')) {
        const row = e.target.closest('tr');
        const selfScore = parseInt(row.querySelector('.self-score').value) || 0;
        const hodScore = parseInt(row.querySelector('.hod-score').value) || 0;
        row.querySelector('.total-score').value = selfScore + hodScore;
    }
});

// Validate self-score input
document.querySelectorAll('.self-score').forEach(input => {
    input.addEventListener('input', () => {
        if (input.value > 75) {
            input.style.borderColor = 'red';
            alert('Self Score must not exceed 75');
        } else {
            input.style.borderColor = '';
        }
    });
});
</script>

</body>
</html>