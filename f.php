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
            background: linear-gradient(135deg, #e0c3fc, #8ec5fc);
            font-family: 'Roboto', sans-serif;
            color: #333;
            padding: 30px;
            margin: 0;
        }

        header img {
            width: 100%;
            max-height: 180px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        h3 {
            color: #4b0082;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2rem;
            animation: fadeIn 1s ease-in-out;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            margin-top: 20px;
        }

        thead {
            background: linear-gradient(135deg, #6a1b9a, #8e24aa);
            color: white;
        }

        thead th {
            padding: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 14px;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        td {
            text-align: center;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }

        td input, td select {
            width: 90%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: 0.3s ease;
        }

        td input:focus, td select:focus {
            outline: none;
            border-color: #8e24aa;
            box-shadow: 0 0 10px rgba(142, 36, 170, 0.5);
        }

        button {
            background-color: #8e24aa;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
            margin-top: 20px;
        }

        button:hover {
            background-color: #6a1b9a;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .pagination a {
            padding: 12px 20px;
            margin: 0 5px;
            background-color: #8e24aa;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .pagination a:hover {
            background-color: #6a1b9a;
            transform: scale(1.1);
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 15px;
            display: none;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
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

    <h3>8. Workshops / Events Organized for Students (Max Score: 15 points)</h3>

    <form id="workshop-form" action="" method="POST" novalidate>
        <p>Organized Events</p>
        <table border="1" class="table table-hover" id="workshop-table">
            <thead class="table-Dark">
                <tr>
                    <th>S.No</th>
                    <th>Title of the Program</th>
                    <th>Period (Date/To)</th>
                    <th>Sub-category</th>
                    <th>Self Score</th>
                    <th>HoD's Score</th>
                    <th>HOD Comments</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td><input type="text" name="title1" placeholder="Event Title" required></td>
                    <td><input type="date" name="period1" required></td>
                    <td><input type="text" name="organization1" placeholder="Organization" required></td>
                    <td>
    <select name="national_international2" required>
        <option value="National">National</option>
        <option value="International">International</option>
    </select>
</td>
                    <td><input type="number" value="80" name="sub_category1"></td>
                    <td><input type="number" name="selfscore1" value="5" required></td>
                    <td><input type="number" name="hodscore1"></td>
                    <td><input type="text" name="hodcomments1" placeholder="Comment" required></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td><input type="text" name="title2" placeholder="Event Title" required></td>
                    <td><input type="date" name="period2" required></td>
                    <td><input type="text" name="organization2" placeholder="Organization" required></td>
                    <td><input type="text" name="organization1" placeholder="Organization" required></td>
                    <td><input type="number" value="80" name="sub-category2" ></td>
                    <td><input type="number" name="selfscore2" value="5" required></td>
                    <td><input type="number" name="hodscore2"></td>
                    <td><input type="text" name="hodcomments2" placeholder="Comment" required></td>
                </tr>
            </tbody>
        </table>

        <div class="error-message" id="error-message">Please fill out all required fields correctly!</div>
        <div class="pagination">
                <a href="2a.php" id="prev-page"><i class="fas fa-arrow-left icons"></i> Previous</a>
                <a href="research.php" id="next-page">Next <i class="fas fa-arrow-right icons"></i></a>
            </div>

        <center>
            <button type="button" id="add-row" class="mt-3">+ Add New Row</button>
            <button type="submit" class="mt-3">Submit <i class="fas fa-check-circle"></i></button>
        </center>
    </form>

    <script>
        document.getElementById('add-row').addEventListener('click', function () {
            const tableBody = document.querySelector('#workshop-table tbody');
            const rowCount = tableBody.rows.length + 1; // Increment the row count for the new row number

            // Create a new row
            const newRow = document.createElement('tr');

            newRow.innerHTML = `
                <td>${rowCount}</td>
                <td><input type="text" name="title${rowCount}" placeholder="Event Title" required></td>
                <td><input type="date" name="period${rowCount}" required></td>
                <td><input type="text" name="organization${rowCount}" placeholder="Organization" required></td>
                <td><input type="text" name="national_international${rowCount}" placeholder="National/International" required></td>
                <td><input type="number" value="80" name="sub_category${rowCount}"></td>
                <td><input type="number" name="selfscore${rowCount}" value="5" required></td>
                <td><input type="number" name="hodscore${rowCount}"></td>
                <td><input type="text" name="hodcomments${rowCount}" placeholder="Comment" required></td>
            `;

            // Append the new row to the table body
            tableBody.appendChild(newRow);
        });

        document.getElementById('workshop-form').addEventListener('submit', function (event) {
            const form = event.target;
            const errorMessage = document.getElementById('error-message');

            if (!form.checkValidity()) {
                event.preventDefault();
                errorMessage.style.display = 'block';
                errorMessage.scrollIntoView({ behavior: 'smooth' });
            } else {
                errorMessage.style.display = 'none';
            }
        });
    </script>

<?php
// Database connection details
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

// Insert data into the database after form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare an array to hold the values to be inserted
    $values = [];

    // Loop through the posted data to prepare the SQL
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'title') === 0) {
            $rowNumber = substr($key, 5); // Get the row number
            $title = isset($_POST["title$rowNumber"]) ? $_POST["title$rowNumber"] : '';
            $period = isset($_POST["period$rowNumber"]) ? $_POST["period$rowNumber"] : '';
            $organization = isset($_POST["organization$rowNumber"]) ? $_POST["organization$rowNumber"] : '';
            
            // Ensure that 'national_international' is checked before access
            $national_international = isset($_POST["national_international$rowNumber"]) ? $_POST["national_international$rowNumber"] : '';
            
            $selfScore = isset($_POST["selfscore$rowNumber"]) ? $_POST["selfscore$rowNumber"] : '';
            $hodScore = isset($_POST["hodscore$rowNumber"]) ? $_POST["hodscore$rowNumber"] : '';
            $hodComments = isset($_POST["hodcomments$rowNumber"]) ? $_POST["hodcomments$rowNumber"] : '';

            // Collect the values for SQL insert
            $values[] = "('$title', '$period', '$organization', '$national_international', '$selfScore', '$hodScore', '$hodComments')";
        }
    }

    if (!empty($values)) {
        // Create the SQL insert statement
        $sql = "INSERT INTO workshops (title, period, organization, national_international, selfscore, hodscore, hodcomments) 
                VALUES " . implode(',', $values);
        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success'>Records inserted successfully</div>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

</body>

</html>
