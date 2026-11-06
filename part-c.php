<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Core Values Data Entry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: 'Quicksand', sans-serif;
            background: linear-gradient(135deg, #f06, #b24070, #891bbb, #008CBA);
            background-size: 300% 300%;
            animation: gradient 15s ease infinite;
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

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        h1, h2 {
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            font-style: italic;
        }

        .container {
            max-width: 800px;
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            color: white;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            color: #fff;
        }

        th {
            background-color: rgba(255, 255, 255, 0.2);
        }

        td {
            background-color: rgba(0, 0, 0, 0.1);
        }

        input[type="number"] {
            width: 80px;
            text-align: center;
            padding: 8px;
            border-radius: 5px;
            border: none;
        }

        input[type="number"]:focus {
            outline: 2px solid #891bbb;
            box-shadow: 0 0 8px rgba(255, 255, 255, 0.8);
        }

        button {
            border-radius: 30px;
            background-color: #ff5733;
            color: white;
            padding: 12px 25px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
            display: block;
            margin: 0 auto;
        }

        button:hover {
            background-color: #f06;
        }

        #totalScore {
            font-size: 1.2em;
            font-weight: bold;
            color: #fff;
            text-align: center;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            input[type="number"] {
                width: 60px;
            }
        }

    </style>
    <script>
        function calculateTotal() {
            var total = 0;
            var inputs = document.querySelectorAll("input[type=number]");
            inputs.forEach(input => {
                total += parseInt(input.value) || 0;
            });
            document.getElementById("totalScore").textContent = "Total Score: " + total;
        }
    </script>
</head>
<body>
    <header>
        <img src="https://www.pscmr.ac.in/header.jpg" alt="Header Image">
    </header>

    <div class="container">
        <h1>PART-C</h1>
        <h2>Demonstration of Core Values Data Entry</h2>
        
        <!-- Form for Data Entry -->
        <form action="" method="POST">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Parameter</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">1</td>
                        <td>Punctuality, Attitude And Motivation</td>
                        <td><input type="number" name="score1" min="0" max="10" required oninput="calculateTotal()"></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">2</td>
                        <td>Professionalism</td>
                        <td><input type="number" name="score2" min="0" max="10" required oninput="calculateTotal()"></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">3</td>
                        <td>Willingness To Learn And Work</td>
                        <td><input type="number" name="score3" min="0" max="10" required oninput="calculateTotal()"></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">4</td>
                        <td>Cordiality And Team Spirit</td>
                        <td><input type="number" name="score4" min="0" max="10" required oninput="calculateTotal()"></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">5</td>
                        <td>Novel Pedagogical Methods And Instructional Materials Used</td>
                        <td><input type="number" name="score5" min="0" max="10" required oninput="calculateTotal()"></td>
                    </tr>
                </tbody>
            </table>

            <!-- Display Total Score -->
            <div id="totalScore">Total Score: 0</div>

            <!-- Submit Button -->
            <button type="submit">Submit</button>
        </form>
    </div>

    <?php
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "partc";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create table if not exists
    $createTableQuery = "CREATE TABLE IF NOT EXISTS core_values (
        id INT AUTO_INCREMENT PRIMARY KEY,
        score1 INT NOT NULL,
        score2 INT NOT NULL,
        score3 INT NOT NULL,
        score4 INT NOT NULL,
        score5 INT NOT NULL,
        total_score INT NOT NULL
    )";

    $conn->query($createTableQuery);

    // Insert data after form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $score1 = $_POST['score1'];
        $score2 = $_POST['score2'];
        $score3 = $_POST['score3'];
        $score4 = $_POST['score4'];
        $score5 = $_POST['score5'];
        $totalScore = $score1 + $score2 + $score3 + $score4 + $score5;

        // Insert query
        $sql = "INSERT INTO core_values (score1, score2, score3, score4, score5, total_score) 
                VALUES ('$score1', '$score2', '$score3', '$score4', '$score5', '$totalScore')";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success mt-3'>submitted successfully!</div>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Close the connection
    $conn->close();
    ?>
</body>
</html>
