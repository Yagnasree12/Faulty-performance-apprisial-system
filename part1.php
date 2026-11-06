 <?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "login";

// Create connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define variables and set them to empty values
$name = $age = $department = $payband = $joiningdate = $address = $phonenumber = $emailid = $designation = $specialization = "";
$nameError = $ageError = $departmentError = $paybandError = $joiningdateError = $addressError = $phonenumberError = $emailidError = $designationError = $specializationError = "";
$update_mode = false;
$id = null; // Add this to handle updates

// Function to sanitize input
function test_input($data) {
  return htmlspecialchars(stripslashes(trim($data)));
}

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = isset($_POST['id']) ? $_POST['id'] : '';
  
  // Validate Name
  if (empty($_POST["name"])) {
    $nameError = "Name is required";
  } else {
    $name = test_input($_POST["name"]);
  }

  // Validate Age
  if (empty($_POST["age"]) || !is_numeric($_POST["age"])) {
    $ageError = "Valid age is required";
  } else {
    $age = test_input($_POST["age"]);
  }

  // Validate Department
  if (empty($_POST["department"])) {
    $departmentError = "Department is required";
  } else {
    $department = test_input($_POST["department"]);
  }

  // Validate Pay Band
  if (empty($_POST["pay_band"])) {
    $pay_bandError = "Pay band is required";
  } else {
    $pay_band = test_input($_POST["pay_band"]);
  }

  // Validate Date of Joining
  if (empty($_POST["date_of_joining"])) {
    $date_of_joiningError = "Date of joining is required";
  } else {
    $date_of_joining = test_input($_POST["date_of_joining"]);
  }

  // Validate Address
  if (empty($_POST["address"])) {
    $addressError = "Address is required";
  } else {
    $address = test_input($_POST["address"]);
  }

  // Validate Phone Number
  if (empty($_POST["phone_number"]) || !preg_match('/^[0-9]{10}$/', $_POST["phone_number"])) {
    $phoneError = "Valid phone number is required";
  } else {
    $phone_number = test_input($_POST["phone_number"]);
  }

  // Validate Email
  if (empty($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $emailError = "Valid email is required";
  } else {
    $email = test_input($_POST["email"]);
  }

  // Validate Designation
  if (empty($_POST["designation"])) {
    $designationError = "Designation is required";
  } else {
    $designation = test_input($_POST["designation"]);
  }

  // Validate Specialization
  if (empty($_POST["specialization"])) {
    $specializationError = "Specialization is required";
  } else {
    $specialization = test_input($_POST["specialization"]);
  }

  // If no errors, insert or update data in the database
  if (empty($nameError) && empty($ageError) && empty($departmentError) && empty($pay_bandError) && empty($date_of_joiningError) && empty($addressError) && empty($phoneError) && empty($emailError) && empty($designationError) && empty($specializationError)) {
    if (empty($id)) {
      // Insert new record
      $stmt = $conn->prepare("INSERT INTO details (name, age, department, pay_band, date_of_joining, address, phone_number, email, designation, specialization) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("sissssssss", $name, $age, $department, $pay_band, $date_of_joining, $address, $phone_number, $email, $designation, $specialization);
    } else {
      // Update existing record
      $stmt = $conn->prepare("UPDATE details SET name=?, age=?, department=?, pay_band=?, date_of_joining=?, address=?, phone_number=?, email=?, designation=?, specialization=? WHERE id=?");
      $stmt->bind_param("sissssssssi", $name, $age, $department, $pay_band, $date_of_joining, $address, $phone_number, $email, $designation, $specialization, $id);
    }

    if ($stmt->execute()) {
      $_SESSION['message'] = "Data inserted successfully!";
      header("Location: " . $_SERVER['PHP_SELF']); // Reload the page
      exit();
    } else {
      echo "Error: " . $stmt->error;
    }
    $stmt->close();
  }
}

// Handle update request
if (isset($_GET['edit'])) {
  $update_mode = true;
  $id = $_GET['edit'];
  $result = $conn->query("SELECT * FROM details WHERE id=$id");

  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $age = $row['age'];
    $department = $row['department'];
    $payband = $row['payband'];
    $joiningdate = $row['joiningdate'];
    $address = $row['address'];
    $phonenumber = $row['phonenumber'];
    $emailid = $row['emailid'];
    $designation = $row['designation'];
    $specialization = $row['specialization'];
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Form Design</title>
  <style>
    /* Include your CSS styles here */
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content:center;
      align-items: center;
      min-height: 100vh;
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

    .container {
      background-color: #fff;
      width: 100%;
      max-width: 1200px;
      border-radius: 8px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin: 20px;
    }

    h1, h2 {
      background-color: hwb(30 93% 5%);
      border: 3px solid #9b59b6;
      padding: 10px;
      text-align: center;
      border-radius: 5px;
    }

    h1 {
      color: #27ae60;
    }

    h2 {
      color: #e67e22;
      margin-top: 40px;
    }

    table {
      width: 100%;
      margin: 20px 0;
      border-collapse: collapse;
    }

    th, td {
      padding: 12px;
      text-align: left;
      border: 1px solid #ddd;
      font-size: 16px;
    }

    th {
      background-color: #f7f7f7;
      color: #333;
    }

    td input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 15px;
      box-sizing: border-box;
    }

    button {
      padding: 12px 24px;
      border: none;
      border-radius: 5px;
      background-color: #e74c3c;
      color: white;
      cursor: pointer;
      font-size: 16px;
      margin: 15px;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #3498db;
    }

    .form-container {
      text-align: center;
    }

    @media (max-width: 768px) {
      table {
        width: 100%;
      }

      h1, h2 {
        font-size: 20px;
      }

      td input {
        font-size: 14px;
      }

      button {
        font-size: 14px;
      }
    }

    /* Flexbox for responsive buttons */
    .btn-group {
      display: flex;
      justify-content: center;
      gap: 10px;
    }

    /* Hover effect for table rows */
    tr:hover {
      background-color: #ecf0f1;
      cursor: pointer;
    }
  </style>
  <script>
    function validateForm() {
      // Email validation
      var email = document.forms["facultyForm"]["emailid"].value;
      var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z]+\.[a-z]{2,3}$/;
      if (!emailPattern.test(email)) {
        alert("Invalid email");
        return false;
      }

      // Phone number validation
      var phone = document.forms["facultyForm"]["phonenumber"].value;
      var phonePattern = /^[7-9][0-9]{10}$/;
      if (!phonePattern.test(phone)) {
        alert("Invalid phone number");
        return false;
      }
      var age = document.forms["facultyForm"]["age"].value;
      if (isNaN(age) || age < 20 || age > 90) {
        alert("Age must be  between 20 and 90");
        return false;
      }

      // Address validation
      var address = document.forms["facultyForm"]["address"].value;
      if (address.length < 10) { // You can adjust the length as per your requirements
        alert("Address must be at least 10 characters long");
        return false;
      }

      return true;
    }
  </script>
</head>
<body>
<header>
        <img src="3.jpg" alt="Header Image">
    </header>

  <div class="container">
  <h1><b>PART-A</b></h1>
    <h2><b><i>GENERAL INFORMATION & ACADEMIC BACKGROUND</i></b></h2>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <table>
        <tr><th>S.NO</th><th>Details</th><th>Input</th></tr>
        <tr><td>1</td><th>Name</th><td><input type="text" name="name" value="<?php echo $name; ?>"><span><?php echo $nameError; ?></span></td></tr>
        <tr><td>2</td><th>Age</th><td><input type="text" name="age" value="<?php echo $age; ?>"><span><?php echo $ageError; ?></span></td></tr>
        <tr><td>3</td><th>Department</th><td><input type="text" name="department" value="<?php echo $department; ?>"><span><?php echo $departmentError; ?></span></td></tr>
        <tr><td>4</td><th>Current Pay Band & Gross Pay</th><td><input type="text" name="payband" value="<?php echo $payband; ?>"><span><?php echo $paybandError; ?></span></td></tr>
        <tr><td>5</td><th>Date of Joining</th><td><input type="text" name="joiningdate" value="<?php echo $joiningdate; ?>"><span><?php echo $joiningdateError; ?></span></td></tr>
        <tr><td>6</td><th>Address</th><td><input type="text" name="address" value="<?php echo $address; ?>"><span><?php echo $addressError; ?></span></td></tr>
        <tr><td>7</td><th>Phone Number</th><td><input type="text" name="phonenumber" value="<?php echo $phonenumber; ?>"><span><?php echo $phonenumberError; ?></span></td></tr>
        <tr><td>8</td><th>Email</th><td><input type="email" name="emailid" value="<?php echo @emailid; ?>"><span><?php echo $emailidError; ?></span></td></tr>
        <tr><td>9</td><th>Designation</th><td><input type="text" name="designation" value="<?php echo $designation; ?>"><span><?php echo $designationError; ?></span></td></tr>
        <tr><td>10</td><th>Area of Specialization</th><td><input type="text" name="specialization" value="<?php echo $specialization; ?>"><span><?php echo $specializationError; ?></span></td></tr>
      </table>
    <h2>Posts Held After Appointment to this Institution</h2>
    <table>
      <tr>
        <th>Designation</th>
        <th>Department</th>
        <th>From</th>
        <th>To</th>
        <th>Gross Pay</th>
        <th>Achievements</th>
      </tr>
      <tr>
        <td><input type="text" placeholder="Designation"></td>
        <td><input type="text" placeholder="Department"></td>
        <td><input type="text" placeholder="From"></td>
        <td><input type="text" placeholder="To"></td>
        <td><input type="text" placeholder="Gross Pay"></td>
        <td><input type="text" placeholder="Achievements"></td>
      </tr>
      <tr>
        <td><input type="text" placeholder="Designation"></td>
        <td><input type="text" placeholder="Department"></td>
        <td><input type="text" placeholder="From"></td>
        <td><input type="text" placeholder="To"></td>
        <td><input type="text" placeholder="Gross Pay"></td>
        <td><input type="text" placeholder="Achievements"></td>
      </tr>
      <tr>
        <td><input type="text" placeholder="Designation"></td>
        <td><input type="text" placeholder="Department"></td>
        <td><input type="text" placeholder="From"></td>
        <td><input type="text" placeholder="To"></td>
        <td><input type="text" placeholder="Gross Pay"></td>
        <td><input type="text" placeholder="Achievements"></td>
      </tr>
    </table>
    <?php
      // Fetch data from the database
      $sql = "SELECT * FROM register";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['age']}</td><td>{$row['department']}</td><td>{$row['payband']}</td><td>{$row['joiningdate']}</td><td>{$row['address']}</td><td>{$row['phonenumber']}</td><td>{$row['emailid']}</td><td>{$row['designation']}</td><td>{$row['specialization']}</td></tr>";
          }
      } else {
          echo "<tr><td colspan='11'>No records found</td></tr>";
      }
      ?>
      <div class="btn-group">
        <button type="submit" class="btn btn-primary"><?php echo $update_mode ? 'Update' : 'Submit'; ?></button>
        <?php if ($update_mode): ?>
          <input type="hidden" name="id" value="<?php echo $id; ?>">
        <?php endif; ?>
      </div>
    </form>
</body>
</html>
<?php $conn->close(); ?>
