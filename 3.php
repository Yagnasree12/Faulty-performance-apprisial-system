
<?php
// Database connection
$host = 'localhost';
$db = 'partb'; // Corrected database name
$user = 'root';
$pass = ''; // Database password, add your MySQL password if set

// Create database connection
$conn = new mysqli($host, $user, $pass, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create table if it doesn't exist
$tableSql = "CREATE TABLE IF NOT EXISTS invited_talks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    talk VARCHAR(255) NOT NULL,
    conference VARCHAR(255) NOT NULL,
    place VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    self_score INT NOT NULL,
    hod_score INT NOT NULL
)";

if ($conn->query($tableSql) === FALSE) {
    echo "Error creating table: " . $conn->error;
}

// Insert data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $talks = $_POST['talks'];
    $conferences = $_POST['conferences'];
    $places = $_POST['places'];
    $dates = $_POST['dates'];
    $selfScores = $_POST['selfscores'];
    $hodScores = $_POST['hodscores'];

    foreach ($talks as $index => $talk) {
        if (!empty($talk) && !empty($conferences[$index]) && !empty($places[$index]) && !empty($dates[$index])) {
            $stmt = $conn->prepare("INSERT INTO invited_talks (talk, conference, place, date, self_score, hod_score) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssii", $talk, $conferences[$index], $places[$index], $dates[$index], $selfScores[$index], $hodScores[$index]);
            $stmt->execute();
        }
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    echo "<script>alert('Records inserted successfully');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invited Talks & Awards</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9faff;
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
            color: #4b0082;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        thead {
            background-color: #4b0082;
            color: white;
            animation: slideDown 0.5s ease;
        }

        th, td {
            text-align: center;
            padding: 15px;
            transition: background-color 0.3s;
        }

        td:hover {
            background-color: #f0f0f0;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"] {
            width: 90%;
            padding: 8px;
            border: 2px solid #ddd; /* Set border color */
            border-radius: 5px;
            transition: box-shadow 0.3s, border-color 0.3s; /* Smooth transition */
        }

        input:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(75, 0, 130, 0.3);
            background-color: #f0f4ff;
            border-color: #4b0082; /* Focus border color */
            animation: shake 0.5s ease;
        }

        .btn {
            background-color: #4b0082;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            align-items:center;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn:hover {
            background-color: #8a2be2;
            transform: translateY(-2px);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
            100% { transform: translateX(0); }
        }

        .pagination {
            display: flex;
            justify-content: right;
            margin-top: 20px;
        }

        .pagination a {
            padding: 10px 20px;
            background-color:#eb5d2b;
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
    <div class="container">
        <h2>4. Invited Talks / Awards (Max Score: 20)</h2>
        <form id="talks-form" method="POST">
            <table border="1" class="table table-hover text-center">
                <thead>
                    <tr>
                        <th>S.NO</th>
                        <th>Invited Talks/Conference</th>
                        <th>Name of the Conference/Seminar</th>
                        <th>Place</th>
                        <th>Date</th>
                        <th>Self Score</th>
                        <th>HoD's Score</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <tr>
                        <td>1</td>
                        <td><input type="text" name="talks[]" required></td>
                        <td><input type="text" name="conferences[]" required></td>
                        <td><input type="text" name="places[]" required></td>
                        <td><input type="date" name="dates[]" required></td>
                        <td><input type="number" name="selfscores[]" class="score-input" min="0" max="20" required></td>
                        <td><input type="number" name="hodscores[]" class="score-input" min="0" max="20" required></td>
                    </tr>
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center">
                <div><strong>Total Self Score:</strong> <span id="total-self-score">0</span>/20</div>
                <div><strong>Total HoD Score:</strong> <span id="total-hod-score">0</span>/20</div>
                <div class="pagination">
            <a href="seminars.php" id="prev-page"><i class="fas fa-arrow-left icons"></i> Previous</a>
            <a href="4.php" id="next-page">Next <i class="fas fa-arrow-right icons"></i></a>
        </div>
                <button type="button" class="btn" id="add-row">+ Add New Row</button>
            </div>
            <center><button type="submit" class="btn mt-3">Submit</button></center>
        </form>
    </div>

    <script>
        // Handle Add New Row button
        document.getElementById('add-row').addEventListener('click', function () {
            const tableBody = document.getElementById('table-body');
            const rowCount = tableBody.rows.length + 1;

            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${rowCount}</td>
                <td><input type="text" name="talks[]" required></td>
                <td><input type="text" name="conferences[]" required></td>
                <td><input type="text" name="places[]" required></td>
                <td><input type="date" name="dates[]" required></td>
                <td><input type="number" name="selfscores[]" class="score-input" min="0" max="20" required></td>
                <td><input type="number" name="hodscores[]" class="score-input" min="0" max="20" required></td>
            `;
            tableBody.appendChild(newRow);
        });

        // Calculate and update total self score and HoD score
        document.addEventListener('input', function (event) {
            if (event.target.classList.contains('score-input')) {
                updateTotalScores();
            }
        });

        function updateTotalScores() {
            const selfScores = document.querySelectorAll('input[name="selfscores[]"]');
            const hodScores = document.querySelectorAll('input[name="hodscores[]"]');
            let totalSelf = 0;
            let totalHod = 0;

            selfScores.forEach(input => {
                totalSelf += parseInt(input.value) || 0;
            });

            hodScores.forEach(input => {
                totalHod += parseInt(input.value) || 0;
            });

            document.getElementById('total-self-score').innerText = Math.min(totalSelf, 20);
            document.getElementById('total-hod-score').innerText = Math.min(totalHod, 20);
        }

        // Form validation
        document.getElementById('talks-form').addEventListener('submit', function (event) {
            if (!this.checkValidity()) {
                event.preventDefault();
                alert('Please fill out all required fields correctly.');
            }
        });
    </script>
</body>
</html>
