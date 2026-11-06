<?php
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "login";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = [];
$name = $age = $department = $pay_band = $joining_date = $address = $phone = $email = $designation = $specialization = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);
    $department = filter_input(INPUT_POST, 'department', FILTER_SANITIZE_STRING);
    $pay_band = filter_input(INPUT_POST, 'pay_band', FILTER_SANITIZE_STRING);
    $joining_date = filter_input(INPUT_POST, 'joining_date', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $designation = filter_input(INPUT_POST, 'designation', FILTER_SANITIZE_STRING);
    $specialization = filter_input(INPUT_POST, 'specialization', FILTER_SANITIZE_STRING);

    // Validations
    if (!$name) $errors['name'] = "Please enter a valid name.";
    if (!$age || $age < 19 || $age > 75) $errors['age'] = "Please enter a valid age between 19 and 75.";
    if (!$department) $errors['department'] = "Please enter a valid department.";
    if (!$pay_band) $errors['pay_band'] = "Please enter a valid pay band.";
    if (!$joining_date) $errors['joining_date'] = "Please enter a valid joining date.";
    if (!$address) $errors['address'] = "Please enter a valid address.";
    if (!$phone || !preg_match("/^[987]\d{9}$/", $phone)) $errors['phone'] = "Please enter a valid phone number (10 digits starting with 9, 8, or 7).";
    if (!$email || !preg_match("/^[A-Za-z0-9._%+-]+@gmail\.com$/", $email)) $errors['email'] = "Please enter a valid Gmail address.";
    if (!$designation) $errors['designation'] = "Please enter a valid designation.";
    if (!$specialization) $errors['specialization'] = "Please enter a valid area of specialization.";

    // Insert into database if no errors
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO reg (name, age, department, pay_band, joining_date, address, phone, email, designation, specialization) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissssssss", $name, $age, $department, $pay_band, $joining_date, $address, $phone, $email, $designation, $specialization);

        if ($stmt->execute()) {
            header("Location: 2a.php"); // Redirect to the display page
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* CSS styles */
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f7c6e7, #e0c3fc, #8ec5fc);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            background-size: cover;
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
            background: rgba(255, 255, 255, 0.9);
            width: 100%;
            max-width: 800px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            padding: 30px;
            margin: 20px;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .container:hover {
            transform: translateY(-10px) scale(1.05);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
        }

        h1 {
            background: linear-gradient(135deg, #ff6f61, #ff4c3b);
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 30px;
            font-size: 26px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            margin-bottom: 8px;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.3s, background-color 0.3s;
        }

        .form-group input:focus {
            border-color: #ff6f61;
            outline: none;
            background-color: #f9f3fa;
        }

        button {
            width: 100%;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            background-color: #e74c3c;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin: 20px auto 0;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #c0392b;
            transform: scale(1.05);
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .form-group input {
                font-size: 14px;
            }

            h1 {
                font-size: 22px;
            }

            button {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
<header>
        <img src="https://www.pscmr.ac.in/header.jpg" alt="Header Image">
    </header>

    <div class="container">
        <h1><b>Register to Access Your Performance Portal!</b></h1>

        <form method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" value="<?php echo htmlspecialchars($name); ?>" required>
                <?php if (isset($errors['name'])) echo '<span class="error">'.$errors['name'].'</span>'; ?>
            </div>

            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" placeholder="Enter your age" value="<?php echo htmlspecialchars($age); ?>" required>
                <?php if (isset($errors['age'])) echo '<span class="error">'.$errors['age'].'</span>'; ?>
            </div>

            <div class="form-group">
                <label for="department">Department</label>
                <input type="text" id="department" name="department" placeholder="Enter your department" value="<?php echo htmlspecialchars($department); ?>" required>
                <?php if (isset($errors['department'])) echo '<span class="error">'.$errors['department'].'</span>'; ?>
            </div>

            <div class="form-group">
                <label for="pay_band">Current Pay Band & Gross Pay</label>
                <input type="text" id="pay_band" name="pay_band" placeholder="Enter pay band" value="<?php echo htmlspecialchars($pay_band); ?>" required>
                <?php if (isset($errors['pay_band'])) echo '<span class="error">'.$errors['pay_band'].'</span>'; ?>
            </div>

            <div class="form-group">
                <label for="joining_date">Date of Joining this College</label>
                <input type="date" id="joining_date" name="joining_date" value="<?php echo htmlspecialchars($joining_date); ?>" required>
                <?php if (isset($errors['joining_date'])) echo '<span class="error">'.$errors['joining_date'].'</span>'; ?>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" placeholder="Enter your address" value="<?php echo htmlspecialchars($address); ?>" required>
                <?php if (isset($errors['address'])) echo '<span class="error">'.$errors['address'].'</span>'; ?>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" placeholder="Enter your phone number" value="<?php echo htmlspecialchars($phone); ?>" required>
                <?php if (isset($errors['phone'])) echo '<span class="error">'.$errors['phone'].'</span>'; ?>
            </div>

            <div class="form-group">
                <label for="email">Email Address (Gmail only)</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>" required>
                <?php if (isset($errors['email'])) echo '<span class="error">'.$errors['email'].'</span>'; ?>
            </div>

            <div class="form-group">
                <label for="designation">Designation</label>
                <input type="text" id="designation" name="designation" placeholder="Enter your designation" value="<?php echo htmlspecialchars($designation); ?>" required>
                <?php if (isset($errors['designation'])) echo '<span class="error">'.$errors['designation'].'</span>'; ?>
            </div>

            <div class="form-group">
                <label for="specialization">Area of Specialization</label>
                <input type="text" id="specialization" name="specialization" placeholder="Enter your area of specialization" value="<?php echo htmlspecialchars($specialization); ?>" required>
                <?php if (isset($errors['specialization'])) echo '<span class="error">'.$errors['specialization'].'</span>'; ?>
            </div>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
