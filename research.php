
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Publications Data Entry</title>
    <style>
        /* Your CSS styling here */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #f8fafc, #dbe4f3);
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
        h1 {
            color: #b72d67;
            text-align: center;
            margin-bottom: 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
        }
        h3 {
            color: #4A90E2;
            font-weight: bold;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #b72d67;
            color: white;
            padding: 12px;
        }

        th, td {
            text-align: center;
            padding: 8px;
            border: 1px solid #ddd;
        }

        input[type="text"], input[type="number"] {
            width: 90%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus, input[type="number"]:focus {
            border-color: #4A90E2;
            box-shadow: 0 0 5px rgba(74, 144, 226, 0.5);
            outline: none;
        }


        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #45a049;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .animated {
            animation: fadeIn 0.8s ease-in-out;
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

<div class="container">
    <h1>2. RESEARCH & DEVELOPMENT</h1>
    <h3>Research Publications (Max score: 60)</h3>
    <form method="post" onsubmit="return validateForm()">
        <table>
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Title of the Paper</th>
                    <th>First Author (Y/N)</th>
                    <th>Journal Name</th>
                    <th>ISBN/ISSN No./DOI</th>
                    <th>IF</th>
                    <th>SC1/Scopus Indexed/Web of Science/IC1</th>
                    <th>Self Score</th>
                    <th>Hod Score</th>
                </tr>
            </thead>
            <tbody id="dataTable">
                <tr>
                    <td>1</td>
                    <td><input type="text" name="title[]" required></td>
                    <td><input type="checkbox" name="first_author[]" value="Y"></td>
                    <td><input type="text" name="journal[]" required></td>
                    <td><input type="text" name="isbn[]" required></td>
                    <td><input type="text" name="impact_factor[]" required></td>
                    <td><input type="text" name="index[]" required></td>
                    <td><input type="number" name="self_score[]" oninput="calculateTotal()" required></td>
                    <td><input type="number" name="hod_score[]" oninput="calculateTotal()" required></td>
                </tr>
            </tbody>
        </table>
        <div class="pagination">
            <a href="f.php" id="prev-page"><i class="fas fa-arrow-left icons"></i> Previous</a>
            <a href="poblications.php" id="next-page">Next <i class="fas fa-arrow-right icons"></i></a>
        </div>
        <button type="button" class="button" onclick="addRow()">Add New Row</button>
        <button type="submit" class="button" name="submit">Submit</button>
        <p id="totalScore" style="font-weight: bold; margin-top: 10px;">Self Score Total: 0 | Hod Score Total: 0</p>
    </form>
</div>

<script>
    function addRow() {
        const table = document.getElementById("dataTable");
        const newRow = table.insertRow();
        const rowCount = table.rows.length;
        newRow.innerHTML = `
            <td>${rowCount}</td>
            <td><input type="text" name="title[]" required></td>
            <td><input type="checkbox" name="first_author[]" value="Y"></td>
            <td><input type="text" name="journal[]" required></td>
            <td><input type="text" name="isbn[]" required></td>
            <td><input type="text" name="impact_factor[]" required></td>
            <td><input type="text" name="index[]" required></td>
            <td><input type="number" name="self_score[]" oninput="calculateTotal()" required></td>
            <td><input type="number" name="hod_score[]" oninput="calculateTotal()" required></td>
        `;
    }

    function calculateTotal() {
        let selfTotal = 0;
        let hodTotal = 0;
        document.querySelectorAll("input[name='self_score[]']").forEach(input => {
            selfTotal += parseFloat(input.value) || 0;
        });
        document.querySelectorAll("input[name='hod_score[]']").forEach(input => {
            hodTotal += parseFloat(input.value) || 0;
        });
        document.getElementById("totalScore").innerText = `Self Score Total: ${selfTotal} | Hod Score Total: ${hodTotal}`;
    }
</script>

<?php
if (isset($_POST['submit'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "partb";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $title = $_POST['title'];
    $first_author = isset($_POST['first_author']) ? $_POST['first_author'] : [];
    $journal = $_POST['journal'];
    $isbn = $_POST['isbn'];
    $impact_factor = $_POST['impact_factor'];
    $index = $_POST['index'];
    $self_score = $_POST['self_score'];
    $hod_score = $_POST['hod_score'];

    for ($i = 0; $i < count($title); $i++) {
        $stmt = $conn->prepare("INSERT INTO research_publications (title, first_author, journal, isbn, impact_factor, index_type, self_score, hod_score) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $firstAuthorVal = isset($first_author[$i]) ? $first_author[$i] : 'N';
        $stmt->bind_param("ssssssii", $title[$i], $firstAuthorVal, $journal[$i], $isbn[$i], $impact_factor[$i], $index[$i], $self_score[$i], $hod_score[$i]);
        $stmt->execute();
    }

    echo "<script>alert('Records inserted successfully');</script>";
    $stmt->close();
    $conn->close();
}
?>

</body>
</html>