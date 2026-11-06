<?php
// Database configuration
$host = 'localhost';
$dbname = 'partb'; // Specify your database name
$username = 'root';
$password = ''; // Database password
try {
 // Connect to MySQL with the database specified
 $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
 $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 // Create table if it does not exist
 $createTableQuery = "CREATE TABLE IF NOT EXISTS publications (
 id INT AUTO_INCREMENT PRIMARY KEY,
 serial_no INT NOT NULL,
 book_title VARCHAR(255) NOT NULL,
 authors VARCHAR(255) NOT NULL,
 publishers VARCHAR(255) NOT NULL,
 isbn_issn VARCHAR(50) NOT NULL,
 published_edited VARCHAR(50) NOT NULL,
 self_score FLOAT NOT NULL,
 hod_score FLOAT NOT NULL
 )";
 $conn->exec($createTableQuery);
} catch (PDOException $e) {
 echo "Database error: " . $e->getMessage();
}
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 $book_title = $_POST['book_title'];
 $authors = $_POST['authors'];
 $publishers = $_POST['publishers'];
 $isbn_issn = $_POST['isbn_issn'];
 $published_edited = $_POST['published_edited'];
 $self_score = $_POST['self_score'];
 $hod_score = $_POST['hod_score'];
 // Insert data into the database
 $stmt = $conn->prepare("INSERT INTO publications (serial_no, book_title, authors,
publishers, isbn_issn, published_edited, self_score, hod_score) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
 if ($stmt->execute([1, $book_title, $authors, $publishers, $isbn_issn, $published_edited,
$self_score, $hod_score])) {
 echo "<script>alert('Records inserted successfully!');</script>";
 } else {
 echo "<script>alert('Error inserting records.');</script>";
 } }
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Research Publications Form</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">
 <style>
 /* Overall styling */
 body {
 font-family: Arial, sans-serif;
 background: linear-gradient(to right, #f0f8ff, #ffffff);
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
 .container {
 max-width: 900px;
 margin: 0 auto;
 background-color: white;
 border-radius: 10px;
 padding: 20px;
 box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
 animation: fadeIn 0.7s ease-in-out;
 }
 h3 {
 text-align: center;
 color: #2d5fa8;
 margin-bottom: 20px;
 }
 /* Table styling */
 table {
 width: 100%;
 border-collapse: collapse;
 margin-bottom: 20px;
 }
 th {
 background-color: #2d5fa8;
 color: white;
 padding: 10px;
 }
 th, td {
 text-align: center;
 border: 1px solid #ddd;
 padding: 10px;
 transition: background-color 0.3s;
 }
 td:hover {
 background-color: #f2f2f2;
 }
 /* Input fields styling */
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
 border-color: #2d5fa8;
 box-shadow: 0 0 5px rgba(45, 95, 168, 0.5);
 outline: none;
 }
 /* Button styling */
 .button {
 display: inline-block;
 padding: 10px 20px;
 background-color: #2d5fa8;
 color: white;
 border: none;
 border-radius: 5px;
 cursor: pointer;
 transition: background-color 0.3s, transform 0.2s;
 margin: 10px 5px;
 }
 .button:hover {
 background-color: #1b4a7f;
 transform: scale(1.05);
 }
 .add-row-btn {
 background-color: #4caf50;
 }
 .add-row-btn:hover {
 background-color: #3e8e41;
 }
 /* Animations */
 @keyframes fadeIn {
 from { opacity: 0; }
 to { opacity: 1; }
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
 <h3>2.Research Publications: Text Books/Book Chapters - Edited/Authored (Max Score:
30)</h3>
 <form method="post" onsubmit="return validateForm()">
 <table>
 <thead>
 <tr>
 <th>S.No</th>
 <th>Title of the Book</th>
 <th>Names of all Authors</th>
 <th>Publishers</th>
 <th>ISBN/ISSN</th>
 <th>Published/Edited</th>
 <th>Self Score</th>
 <th>HoD's Score</th>
 </tr>
 </thead>
 <tbody id="publicationsTable">
 <tr>
 <td>1</td>
 <td><input type="text" name="book_title" required></td>
 <td><input type="text" name="authors" required></td>
 <td><input type="text" name="publishers" required></td>
 <td><input type="text" name="isbn_issn" required></td>
 <td><input type="text" name="published_edited" required></td>
 <td><input type="number" name="self_score" oninput="calculateTotal()"
max="30" required></td>
 <td><input type="number" name="hod_score" oninput="calculateTotal()"
max="30" required></td>
 </tr>
 </tbody>
 </table>
 <div class="pagination">
 <a href="research.php" id="prev-page"><i class="fas fa-arrow-left icons"></i>
Previous</a>
 <a href="description.php" id="next-page">Next <i class="fas fa-arrow-right
icons"></i></a>
 </div>
 <div style="text-align: center;">
 <button type="button" class="button add-row-btn" onclick="addRow()">Add New
Row</button>
 <button type="submit" class="button">Submit</button>
 </div>
 <p>Total Self Score: <span id="totalSelfScore">0</span></p>
 <p>Total HoD Score: <span id="totalHodScore">0</span></p>
 </form>
 </div>
 <script>
 // Dynamically add a new row
 function addRow() {
 const table = document.getElementById("publicationsTable");
 const rowCount = table.rows.length;
 const newRow = table.insertRow();
 newRow.innerHTML = `
 <td>${rowCount + 1}</td>
 <td><input type="text" name="book_title" required></td>
 <td><input type="text" name="authors" required></td>
 <td><input type="text" name="publishers" required></td>
 <td><input type="text" name="isbn_issn" required></td>
 <td><input type="text" name="published_edited" required></td>
 <td><input type="number" name="self_score" oninput="calculateTotal()" max="20"
required></td>
 <td><input type="number" name="hod_score" oninput="calculateTotal()" max="20"
required></td>
 `;
 )
 function calculateTotals() {
 let totalSelfScore = 0, totalHodScore = 0;
 const rows = document.querySelectorAll('#publicationsTable tr');
 rows.forEach(row => {
 const selfScoreInput = row.querySelector("input[name='self_score']");
 const hodScoreInput = row.querySelector("input[name='hod_score']");
 const selfScore = parseFloat(selfScoreInput?.value) || 0;
 const hodScore = parseFloat(hodScoreInput?.value) || 0;
 totalSelfScore += selfScore;
 totalHodScore += hodScore;
 });
 // Display the updated totals
 document.getElementById('totalSelfScore').innerText = totalSelfScore;
 document.getElementById('totalHodScore').innerText = totalHodScore;
}
// Attach calculateTotals function to each self_score and hod_score field
function attachCalculateEvents() {
 const inputs = document.querySelectorAll("input[name='self_score'],
input[name='hod_score']");
 inputs.forEach(input => {
 input.removeEventListener('input', calculateTotals); // Remove existing to avoid duplicate
calls
 input.addEventListener('input', calculateTotals);
 });
}
// Initialize totals calculation
document.addEventListener('DOMContentLoaded', () => {
 calculateTotals(); // Initial calculation for any prefilled values
 attachCalculateEvents(); // Attach input event listeners to current inputs
});
 // Validate form inputs
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