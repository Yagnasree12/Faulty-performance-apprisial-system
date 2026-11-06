<?php
// Database connection parameters
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
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 $programs = $_POST['programs'];
 // Prepare and bind
 $stmt = $conn->prepare("INSERT INTO seminars (title, period, from_date, sponsor, category,
self_score, hod_score) VALUES (?, ?, ?, ?, ?, ?, ?)");
 $stmt->bind_param("ssssssi", $title, $period, $from_date, $sponsor, $category, $self_score,
$hod_score);
 foreach ($programs as $program) {
 $title = $program['title'];
 $period = $program['period'];
 $from_date = $program['from_date'];
 $sponsor = $program['sponsor'];
 $category = $program['category'];
 $self_score = $program['self_score'];
 $hod_score = $program['hod_score'];
 // Execute the statement
 $stmt->execute();
 }
 $stmt->close();
 echo "<script>alert('Data submitted successfully!');</script>";
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Seminar/Conference Form</title>
 <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
rel="stylesheet">
 <style>
 /* General Page Styling */
 body {
 font-family: 'Arial', sans-serif;
 background-color: #f8f9fa;
 margin: 0;
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
 margin-bottom: 20px;
 }
 h2 {
 text-align: center;
 color: #343a40;
 margin-bottom: 30px;
 font-size: 24px;
 }
 .container {
 max-width: 900px;
 margin: auto;
 background: #ffffff;
 padding: 30px;
 border-radius: 10px;
 box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
 transition: transform 0.3s, box-shadow 0.3s;
 }
 .container:hover {
 transform: translateY(-2px);
 box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
 }
 table {
 width: 100%;
 border-collapse: collapse;
 margin: 20px 0;
 }
 th, td {
 padding: 15px;
 text-align: left;
 border: 1px solid #dee2e6;
 transition: background-color 0.3s;
 }
 th {
 background-color: #28a745;
 color: white;
 font-weight: bold;
 }
 tr:hover {
 background-color: #e9ecef;
 }
 input, select {
 width: 100%;
 padding: 12px;
 margin: 8px 0;
 border: 1px solid #ced4da;
 border-radius: 5px;
 transition: border-color 0.3s;
 }
 input:focus, select:focus {
 border-color: #28a745;
 outline: none;
 }
 button {
 background-color: #28a745;
 color: white;
 border: none;
 padding: 12px 20px;
 border-radius: 5px;
 cursor: pointer;
 transition: background-color 0.3s, transform 0.2s;
 font-size: 16px;
 }
 button:hover {
 background-color: #218838;
 transform: translateY(-2px);
 }
 .add-row {
 margin: 20px 0;
 display: flex;
 justify-content: center;
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
 .submit, .add-row button {
 background-color: #28a745;
 color: white;
 margin-left: 10px;
 }
 .submit:hover, .add-row button:hover {
 background-color: #0069d9;
 }
 </style>
</head>
<body>
<header>
 <img src="https://www.pscmr.ac.in/header.jpg" alt="Header Image">
</header>
<div class="container">
 <h2>2. For Organizing Seminars/Conferences/Workshops/Training Programmes (Max
Score=30)</h2>
 <form method="post" id="seminarForm">
 <table>
 <thead>
 <tr>
 <th>S.NO</th>
 <th>Title of the Program</th>
 <th>Period (in weeks)</th>
 <th>From Date</th>
 <th>Sponsor</th>
 <th>Category</th>
 <th>Self Score</th>
 <th>HOD Score</th>
 </tr>
 </thead>
 <tbody id="tableBody">
 <tr>
 <td>1</td>
 <td><input type="text" name="programs[0][title]" required></td>
 <td><input type="text" name="programs[0][period]" required min="0"
max="10"></td>
 <td><input type="date" name="programs[0][from_date]" required></td>
 <td><input type="text" name="programs[0][sponsor]" required></td>
 <td>
 <select name="programs[0][category]" required>
 <option value="">Select</option>
 <option value="National">National</option>
 <option value="International">International</option>
 <option value="College Level">College Level</option>
 </select>
 </td>
 <td><input type="number" name="programs[0][self_score]" required min="0"
max="30"></td>
 <td><input type="number" name="programs[0][hod_score]" required min="0"
max="30"></td>
 </tr>
 </tbody>
 </table>
 <div class="pagination">
 <a href="3.fac.php" id="prev-page"><i class="fas fa-arrow-left icons"></i> Previous</a>
 <a href="3.php" id="next-page">Next <i class="fas fa-arrow-right icons"></i></a>
 </div>
 <div class="add-row">
 <button type="button" onclick="addRow()">Add New Row</button>
 <button type="submit" class="submit">Submit</button>
 </div>
 </form>
</div>
<script>
 let rowCount = 1;
 function addRow() {
 const tableBody = document.getElementById('tableBody');
 const newRow = document.createElement('tr');
 newRow.innerHTML = `
 <td>${rowCount + 1}</td>
 <td><input type="text" name="programs[${rowCount}][title]" required></td>
 <td><input type="text" name="programs[${rowCount}][period]" required min="0"
max="10" oninput="calculateScore(this)"></td>
 <td><input type="date" name="programs[${rowCount}][from_date]" required></td>
 <td><input type="text" name="programs[${rowCount}][sponsor]" required></td>
 <td>
 <select name="programs[${rowCount}][category]" required>
 <option value="">Select</option>
 <option value="National">National</option>
 <option value="International">International</option>
 <option value="College Level">College Level</option>
 </select>
 </td>
 <td><input type="number" name="programs[${rowCount}][self_score]" required
min="0" max="30" readonly></td>
 <td><input type="number" name="programs[${rowCount}][hod_score]" required
min="0" max="30" readonly></td>
 `;
 rowCount++;
 tableBody.appendChild(newRow);
 }
 function calculateScore(periodInput) {
 const row = periodInput.closest('tr');
 const selfScoreInput = row.querySelector('input[name*="[self_score]"]');
 const period = parseInt(periodInput.value);
 if (period >= 2) {
 selfScoreInput.value = 20;
 } else if (period === 1) {
 selfScoreInput.value = 10;
 } else if (period < 1) {
 selfScoreInput.value = Math.min(5 * period * 7, 5); // Up to 5 points max
 } else {
 selfScoreInput.value = 0;
 }
 }
 document.getElementById('seminarForm').addEventListener('submit', function(event) {
 const inputs = this.querySelectorAll('input, select');
 for (let input of inputs) {
 if (!input.checkValidity()) {
 alert('Please fill out all fields correctly.');
 event.preventDefault();
 return;
 }
 }
 });
</script>
</body>
</html>
