
<?php
// Database configuration
$host = 'localhost'; // Change if your database server is different
$dbname = 'partb'; // Name of the database 
$username = 'root'; // Database username
$password = ''; // Database password

// Create database and table if not exists
try {
    // Create connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create table if it doesn't exist
    $conn->exec("CREATE TABLE IF NOT EXISTS memberships (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        membership_name VARCHAR(255) NOT NULL,
        membership_id VARCHAR(255) NOT NULL,
        since_when VARCHAR(255) NOT NULL,
        valid_till VARCHAR(255) NOT NULL,
        self_score FLOAT NOT NULL,
        hod_score FLOAT NOT NULL
    )");

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $membership_name = $_POST['membership_name'];
    $membership_id = $_POST['membership_id'];
    $since_when = $_POST['since_when'];
    $valid_till = $_POST['valid_till'];
    $self_score = $_POST['self_score'];
    $hod_score = $_POST['hod_score'];

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO memberships (membership_name, membership_id, since_when, valid_till, self_score, hod_score) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$membership_name, $membership_id, $since_when, $valid_till, $self_score, $hod_score])) {
        echo "<script>alert('Records inserted successfully!');</script>";
    } else {
        echo "<script>alert('Error inserting records.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Society Membership</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #f0f0f0, #ffffff);
            color: #333;
            padding: 20px;
            margin: 0;
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
            margin-bottom: 20px;
        }

        h2 {
            color: #e82beb ;
            text-align: center;
            margin-bottom: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto; /* Centering the container */
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #eb5d2b;
            color: white;
            padding: 12px;
        }

        th, td {
            text-align: center;
            border: 1px solid #ddd;
            padding: 10px;
            transition: background-color 0.3s;
        }

        td:hover {
            background-color: #f9f9f9;
        }

        input[type="text"],
        input[type="number"] {
            width: 90%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="number"]:focus {
            border-color: #eb5d2b;
            box-shadow: 0 0 5px rgba(235, 93, 43, 0.5);
            outline: none;
        }

        .button {
            display: inline-block; /* Changed to inline-block for better alignment */
            width: auto; /* Automatic width */
            padding: 10px 20px; /* Padding for button */
            background-color:#2b7feb; /* Match header color */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            margin: 10px 5px; /* Margin for spacing between buttons */
        }

        .button:hover {
            background-color: #d45d1f; /* Darker shade on hover */
            transform: scale(1.05);
        }

        .add-row-btn {
            background-color: #5cb85c; /* Different color for Add Row button */
        }

        .add-row-btn:hover {
            background-color: #4cae4c;
        }

        /* Animation effects */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .animated {
            animation: fadeIn 0.5s ease-in-out;
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
    </style>
</head>
<body>
<header>
    <img src="https://www.pscmr.ac.in/header.jpg" alt="Header Image">
</header>
    <div class="container animated">
        <form method="post" onsubmit="return validateForm()">
            <h2>5. Membership of Professional Societies &nbsp;&nbsp;(Max. Score: 20)</h2>
            <table>
                <thead>
                    <tr>
                        <th>S.NO</th>
                        <th>Membership</th>
                        <th>Membership Id</th>
                        <th>Since When</th>
                        <th>Valid Till</th>
                        <th>Self Score</th>
                        <th>HoD Score</th>
                    </tr>
                </thead>
                <tbody id="membershipTable">
                    <tr>
                        <td><input type="text" name="serial_no"></td>
                        <td><input type="text" name="membership_name" required></td>
                        <td><input type="text" name="membership_id" required></td>
                        <td><input type="text" name="since_when" required></td>
                        <td><input type="text" name="valid_till" required></td>
                        <td><input type="number" name="self_score" oninput="calculateTotal()" max="20" required></td>
                        <td><input type="number" name="hod_score" oninput="calculateTotal()" max="20" required></td>
                    </tr>
                </tbody>
            </table>
            <div class="pagination">
            <a href="3.php" id="prev-page"><i class="fas fa-arrow-left icons"></i> Previous</a>
            <a href="5.php" id="next-page">Next <i class="fas fa-arrow-right icons"></i></a>
        </div>
            <div style="text-align: center;">
                <button type="button" class="button add-row-btn" onclick="addRow()">Add New Row</button>
                <button type="submit" class="button">Submit</button>
            </div>
        </form>
    </div>

    <script>
       // Handle Add New Row button
       function addRow() {
            const table = document.getElementById("membershipTable");
            const newRow = table.insertRow();
            for (let i = 0; i < 7; i++) {
                const newCell = newRow.insertCell(i);
                newCell.innerHTML = '<input type="text" ' + (i >= 5 ? 'oninput="calculateTotal()" required' : 'required') + '>';
            }
        }

        function validateForm() {
            const inputs = document.querySelectorAll("input[type='text'], input[type='number']");
            for (const input of inputs) {
                if (input.value.trim() === "") {
                    alert("All fields are required.");
                    return false;
                }
            }
            return true;
        }
    </script>
</body>
</html>