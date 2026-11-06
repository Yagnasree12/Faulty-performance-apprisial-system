<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "partb";
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
 die("Connection failed: " . $conn->connect_error);
}
// Insert data into the database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 $numRows = count($_POST['project_title']);
 for ($i = 0; $i < $numRows; $i++) {
 $project_title = $conn->real_escape_string($_POST['project_title'][$i]);
 $funding_agency = $conn->real_escape_string($_POST['funding_agency'][$i]);
 $amount = $_POST['amount'][$i];
 $investigator = $_POST['investigator'][$i];
 $status = $_POST['status'][$i];
 $self_score = $_POST['self_score'][$i];
 $hod_score = $_POST['hod_score'][$i];
 // SQL query to insert data
 $sql = "INSERT INTO projects (project_title, funding_agency, amount, investigator,
status, self_score, hod_score)
 VALUES ('$project_title', '$funding_agency', '$amount', '$investigator', '$status',
'$self_score', '$hod_score')";
 if (!$conn->query($sql)) {
 echo "Error: " . $sql . "<br>" . $conn->error;
 }
 }
 echo "<script>alert('Records inserted successfully');</script>";
}
// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Project Details</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">
 <style>
 body {
 font-family: Arial, sans-serif;
 background: linear-gradient(to right, #e8f4f8, #ffffff);
 color: #333;
 padding: 20px;
 }
 header {
 text-align: center;
 margin-bottom: 20px;
 }
 header img {
 max-height: 150px;
 object-fit: cover;
 margin-bottom: 20px;
 }
 .container {
 max-width: 900px;
 margin: 0 auto;
 background-color: #ffffff;
 border-radius: 10px;
 padding: 20px;
 box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
 }
 h2 {
 color: #3c9d9b;
 text-align: center;
 margin-bottom: 20px;
 font-weight: bold;
 }
 table {
 width: 100%;
 border-collapse: collapse;
 margin-bottom: 20px;
 transition: box-shadow 0.3s;
 }
 table:hover {
 box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
 }
 th {
 background-color: #4caf50;
 color: white;
 padding: 10px;
 font-size: 1.1em;
 }
 th, td {
 text-align: center;
 border: 1px solid #ddd;
 padding: 10px;
 }
 tr:hover {
 background-color: #f2f2f2; /* Light gray row on hover */
}
 input[type="text"], input[type="number"], select {
 width: 100%;
 padding: 8px;
 border: 1px solid #ccc;
 border-radius: 5px;
 transition: border-color 0.3s, box-shadow 0.3s;
 }
 input[type="text"]:focus, input[type="number"]:focus, select:focus {
 border-color: #3c9d9b;
 box-shadow: 0 0 5px rgba(60, 157, 155, 0.5);
 }
 .btn {
 display: inline-block;
 padding: 10px 20px;
 color: white;
 background-color: #3c9d9b;
 border: none;
 border-radius: 5px;
 cursor: pointer;
 font-size: 1.1em;
 transition: background-color 0.3s, transform 0.3s;
 margin-top: 10px;
 }
 .btn:hover {
 background-color: #2d7d7a;
 transform: scale(1.05);
 }
 .btn-add {
 background-color: #4caf50;
 }
 .btn-add:hover {
 background-color: #45a049;
 }
 .score {
 font-weight: bold;
 text-align: center;
 margin-top: 20px;
 font-size: 1.2em;
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
 <h2>Project Details (Max Total Score: 30)</h2>
 <form method="post" onsubmit="return validateForm()">
 <table id="projectTable">
 <thead>
 <tr>
 <th>S.No</th>
 <th>Project Title</th>
 <th>Funding Agency</th>
 <th>Amount (in Lakhs)</th>
 <th>Role</th>
 <th>Status</th>
 <th>Self Score</th>
 <th>HoD's Score</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td>1</td>
 <td><input type="text" name="project_title[]" required></td>
 <td><input type="text" name="funding_agency[]" required></td>
 <td><input type="number" min="0" name="amount[]"
onchange="calculateScores(this)" required></td>
 <td>
 <select name="investigator[]" onchange="calculateScores(this)" required>
 <option value="Principal Investigator">Principal Investigator</option>
 <option value="Co-investigator">Co-investigator</option>
 </select>
 </td>
 <td>
 <select name="status[]" onchange="calculateScores(this)" required>
 <option value="ongoing">Ongoing</option>
 <option value="completed">Completed</option>
 <option value="applied">Applied</option>
 </select>
 </td>
 <td><input type="number" name="self_score[]" class="selfScore" readonly></td>
 <td><input type="number" name="hod_score[]" class="hodScore" readonly></td>
 </tr>
 </tbody>
 </table>
 <div class="pagination">
 <a href="description.php" id="prev-page"><i class="fas fa-arrow-left icons"></i>
Previous</a>
 <a href="3.fac.php" id="next-page">Next <i class="fas fa-arrow-right icons"></i></a>
 </div>
 <button type="button" class="btn btn-add" onclick="addRow()">Add New Row</button>
 <button type="submit" class="btn">Submit</button>
 <p class="score" id="totalScore">Total Self Score: 0 | Total HoD's Score: 0</p>
 </form>
</div>
<script>
 function addRow() {
 const table =
document.getElementById('projectTable').getElementsByTagName('tbody')[0];
 const rowCount = table.rows.length;
 const row = table.insertRow(rowCount);
 row.innerHTML = `
 <td>${rowCount + 1}</td>
 <td><input type="text" name="project_title[]" required></td>
 <td><input type="text" name="funding_agency[]" required></td>
 <td><input type="number" min="0" name="amount[]"
onchange="calculateScores(this)" required></td>
 <td>
 <select name="investigator[]" onchange="calculateScores(this)" required>
 <option value="Principal Investigator">Principal Investigator</option>
 <option value="Co-investigator">Co-investigator</option>
 </select>
 </td>
 <td>
 <select name="status[]" onchange="calculateScores(this)" required>
 <option value="ongoing">Ongoing</option>
 <option value="completed">Completed</option>
 <option value="applied">Applied</option>
 </select>
 </td>
 <td><input type="number" name="self_score[]" class="selfScore" readonly></td>
 <td><input type="number" name="hod_score[]" class="hodScore" readonly></td>
 }
 function calculateScores(element) {
 const row = element.closest("tr");
 const amount = parseFloat(row.cells[3].querySelector("input").value) || 0;
 const investigator = row.cells[4].querySelector("select").value;
 const status = row.cells[5].querySelector("select").value;
 let selfScore = 0;
 if (amount >10) {
 selfScore = 30;
 } else if (amount >= 8) {
 selfScore = 20;
 } else if (amount >= 5) {
 selfScore = 10;
 } else {
 selfScore = 5;
 }
 if (investigator === "Co-investigator") {
 selfScore /= 2;
 }
 if (status === "applied") {
 selfScore = 10;
 }
 row.cells[6].querySelector("input").value = selfScore;
 row.cells[7].querySelector("input").value = selfScore;
 calculateTotalScores();
 }
 function calculateTotalScores() {
 let selfTotal = 0, hodTotal = 0;
 document.querySelectorAll('.selfScore').forEach(input => selfTotal +=
parseFloat(input.value) || 0);
 document.querySelectorAll('.hodScore').forEach(input => hodTotal +=
parseFloat(input.value) || 0);
 document.getElementById('totalScore').innerText = `Total Self Score: ${selfTotal} | Total
HoD's Score: ${hodTotal}`;
 }
 function validateForm() {
 const inputs = document.querySelectorAll("input[type='text'], select,
input[type='number']");
 for (const input of inputs) {
 if (!input.value.trim()) {
 alert("All fields are required.");
 return false;
 } }
 alert("Records inserted successfully!");
 return true;
 }
</script>
</body>
</html>
