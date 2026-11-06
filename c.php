<?php
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

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Function to handle file upload
function handleFileUpload($file, $uploadDir) {
    if (isset($file['error']) && $file['error'] === UPLOAD_ERR_OK) {
        $filename = basename($file['name']);
        $filepath = $uploadDir . $filename;
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $filepath;
        }
    }
    return null; // Return null if no file uploaded or an error occurred
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    // Sanitize inputs
    $semester1_remedial = sanitizeInput($_POST['semester1_remedial'] ?? '');
    $semester2_remedial = sanitizeInput($_POST['semester2_remedial'] ?? '');
    $semester1_career = sanitizeInput($_POST['semester1_career'] ?? '');
    $semester2_career = sanitizeInput($_POST['semester2_career'] ?? '');
    $semester1_content = sanitizeInput($_POST['semester1_content'] ?? '');
    $semester2_content = sanitizeInput($_POST['semester2_content'] ?? '');
    $hod_score_remedial = sanitizeInput($_POST['hod_score_remedial'] ?? '');
    $hod_score_career = sanitizeInput($_POST['hod_score_career'] ?? '');
    $hod_score_content = sanitizeInput($_POST['hod_score_content'] ?? '');

    // File upload directory
    $uploadDir = "uploads/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Handle file uploads
    $self_score_remedial_path = isset($_FILES['self_score_remedial']) ? handleFileUpload($_FILES['self_score_remedial'], $uploadDir) : null;
    $self_score_career_path = isset($_FILES['self_score_career']) ? handleFileUpload($_FILES['self_score_career'], $uploadDir) : null;
    $self_score_content_path = isset($_FILES['self_score_content']) ? handleFileUpload($_FILES['self_score_content'], $uploadDir) : null;

    // Prepare SQL for update or insert
    if ($id > 0) {
        $sql = "UPDATE faculty_scores SET 
                semester1_remedial = ?, semester2_remedial = ?, hod_score_remedial = ?, self_score_remedial_path = ?,
                semester1_career = ?, semester2_career = ?, hod_score_career = ?, self_score_career = ?,
                semester1_content = ?, semester2_content = ?, hod_score_content = ?, self_score_content = ?
                WHERE id = ?";
    } else {
        $sql = "INSERT INTO faculty_scores 
                (semester1_remedial, semester2_remedial, hod_score_remedial, self_score_remedial_path,
                 semester1_career, semester2_career, hod_score_career, self_score_career,
                 semester1_content, semester2_content, hod_score_content, self_score_content)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters
    if ($id > 0) {
        $stmt->bind_param(
            "ssssssssssssi",
            $semester1_remedial, $semester2_remedial, $hod_score_remedial, $self_score_remedial_path,
            $semester1_career, $semester2_career, $hod_score_career, $self_score_career,
            $semester1_content, $semester2_content, $hod_score_content, $self_score_content, $id
        );
    } else {
        $stmt->bind_param(
            "ssssssssssss",
            $semester1_remedial, $semester2_remedial, $hod_score_remedial, $self_score_remedial_path,
            $semester1_career, $semester2_career, $hod_score_career, $self_score_career,
            $semester1_content, $semester2_content, $hod_score_content, $self_score_content
        );
    }

    // Execute the query
    if ($stmt->execute()) {
        $message = "Data submitted successfully!";
    } else {
        $message = "Error executing statement: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch data for display
function getData($conn) {
    $sql = "SELECT * FROM faculty_scores";
    $result = $conn->query($sql);
    $rows = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    return $rows;
}

$data = getData($conn);
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Performance Appraisal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e9ecef;
            font-family: 'Arial', sans-serif;
            margin: 20px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        header {
            text-align: center;
            margin-bottom: 30px;
        }

        header img {
            width: 100%;
            max-height: 150px;
            object-fit: cover;
            border-radius: 10px;
        }

        h2 {
            color: #d9534f;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            margin-bottom: 20px;
            border-collapse: collapse;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
            background-color: white;
        }

        th {
            background-color: #6f42c1;
            color: white;
        }

        td {
            text-align: center;
        }

        input[type="text"] {
            width: 80%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus {
            border-color: #6f42c1;
            outline: none;
        }

        button {
            border-radius: 5px;
            background-color: #6f42c1;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        button:hover {
            background-color: #5a3e8c;
            transform: scale(1.05);
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .page-link {
            padding: 10px 15px;
            margin: 0 5px;
            border: 1px solid #6f42c1;
            color: #6f42c1;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .page-link:hover {
            background-color: #6f42c1;
            color: white;
        }

        .error {
            color: red;
            display: none;
            margin-top: 5px;
            text-align: center;
        }
        .add-row{
            padding:20px;
            margin:15px;
        }
    </style>
    </style>
</head>
<body>
<header>
    <img src="https://www.pscmr.ac.in/header.jpg" alt="Header Image">
</header>
<h2>4. Remedial/Bridge Courses/Job Oriented Certificate Courses Beyond College Hours</h2>
<button type="button" id="add-row" class="add-row">
                    <i class="fas fa-plus-circle"></i> Add New Row
</button>
<?php if (isset($message)) : ?>
    <div class="alert alert-info"><?php echo $message; ?></div>
<?php endif; ?>
<form method="POST" id="faculty-form">
    <table class="table table-hover">
        <thead class="table-danger">      
            <tr>
                <th>S.No</th>
                <th>Item</th>
                <th>Semester I</th>
                <th>Semester II</th>
                <th>Self Score</th>
                <th>HoD's Score</th>
            </tr>
        </thead>
        <tbody id="dynamic-rows">
        <tr>
            <td>1</td>
            <td>Remedial* (40)<br>(For each semester 20 marks)</td>
            <td><input type="text" name="semester1_remedial" class="form-control" required data-max="20"></td>
            <td><input type="text" name="semester2_remedial" class="form-control" required data-max="20"></td>
            <td><input type="text" name="self_score_remedial" class="form-control" required data-max="20"></td>
            <td><input type="text" name="hod_score_remedial" class="form-control" required data-max="20"></td>
        </tr>
            <td>2</td>
            <td>Career Oriented / Value Added* (10)<br>(For each semester 5 Marks)</td>
            <td><input type="text" name="semester1_career" class="form-control" required data-max="5"></td>
            <td><input type="text" name="semester2_career" class="form-control" required data-max="5"></td>
            <td><input type="text" name="self_score_career" class="form-control" required data-max="5"></td>
            <td><input type="text" name="hod_score_career" class="form-control" required data-max="5"></td>
        </tr>
        <tr>
            <td>3</td>
            <td>Content Beyond Syllabus* (10)<br>(For each semester 5 Marks)</td>
            <td><input type="text" name="semester1_content" class="form-control" required data-max="5"></td>
            <td><input type="text" name="semester2_content" class="form-control" required data-max="5"></td>
            <td><input type="text" name="self_score_content" class="form-control" required data-max="5"></td>
            <td><input type="text" name="hod_score_content" class="form-control" required data-max="5"></td>
        </tr>
        </tbody>
    </table>
    <button type="submit" id="submit-btn">Submit</button>
    <div class="error" id="error-message">Please correct the highlighted fields.</div>

<div class="pagination">
    <a href="b.php" class="page-link">Previous</a>
    <a href="d1.php" class="page-link">Next</a>
</div>
</form>

<script>
    document.getElementById('submit-btn').addEventListener('click', function() {
        const inputs = document.querySelectorAll('input[type="text"]');
        let valid = true;
        const errorMessage = document.getElementById('error-message');
        errorMessage.style.display = 'none'; // Reset error message

        const formData = new FormData();

        inputs.forEach(input => {
            const value = input.value;
            const max = parseInt(input.getAttribute('data-max'), 10);

            // Reset border color
            input.style.borderColor = '#ced4da';

            // Validation: Check if input is a number and within the specified range
            if (!value || isNaN(value) || value < 0 || value > max) {
                valid = false;
                input.style.borderColor = 'red'; // Highlight invalid fields
            } else {
                formData.append(input.name, value); // Append valid inputs to FormData
            }
        });
        document.getElementById('add-row').addEventListener('click', function () {
            const table = document.getElementById('course-table').getElementsByTagName('tbody')[0];
            const rowCount = table.rows.length + 1;

            const newRow = 
                <tr>
                    <td>${rowCount}</td>
                    <td><input type="text" placeholder="Course Name" /></td>
                    <td><input type="number" id="class-size-${rowCount}" placeholder="Class Size" /></td>
                    <td>
                        ≥75: <input type="number" id="n1_${rowCount}" placeholder="n1"> <br>
                        ≥60 & <75: <input type="number" id="n2_${rowCount}" placeholder="n2"> <br>
                        <60: <input type="number" id="n3_${rowCount}" placeholder="n3">
                    </td>
                    <td><input type="text" id="percentage${rowCount}" readonly></td>
                    <td><input type="text" id="score${rowCount}" readonly></td>
                </tr>
            ;

            table.insertAdjacentHTML('beforeend', newRow);

            // Add event listener for the new row inputs
            document.querySelectorAll(#n1_${rowCount}, #n2_${rowCount}, #n3_${rowCount}, #class-size-${rowCount}).forEach(input => {
                input.addEventListener('input', function () {
                    updateCalculations(rowCount);
                });
            });
        });
    document.getElementById('add-row').addEventListener('click', function () {
        const table = document.getElementById('dynamic-rows');
        const rowCount = table.rows.length + 1;

        const newRow = `
            <tr>
                <td>${rowCount}</td>
                <td><input type="text" name="course_name_${rowCount}" placeholder="Course Name" required></td>
                <td><input type="number" name="semester1_${rowCount}" placeholder="Semester I" required></td>
                <td><input type="number" name="semester2_${rowCount}" placeholder="Semester II" required></td>
                <td><input type="number" name="self_score_${rowCount}" placeholder="Self Score" required></td>
                <td><input type="number" name="hod_score_${rowCount}" placeholder="HoD's Score" required></td>
            </tr>
        `;

        table.insertAdjacentHTML('beforeend', newRow);
    });
    $(document).ready(function () {
    if ($('input[name="id"]').val()) {
        $('#update-btn').show();
        $('#submit-btn').hide();
    }
});
</script>
</body>
</html>
