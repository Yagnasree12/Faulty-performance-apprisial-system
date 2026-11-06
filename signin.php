<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSCMRCET College Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: url('pscmr.jpeg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            font-family: 'Arial', sans-serif;
            position: relative;
        }

        header {
            width: 100%;
            position: relative;
            z-index: 10;
        }

        header img {
            width: 100%;
            max-height: 150px;
            object-fit: cover;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 150px); /* Subtract the height of the header */
            width: 100%;
            z-index: 2;
            flex-direction: column;
            padding-top: 15px;
        }

        .login-box {
            max-width: 400px;
            width: 100%;
            background: rgba(255, 255, 255, 0.15);
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(5px);
            text-align: left;
            transition: transform 0.3s ease-in-out;
            animation: fadeInUp 1.5s ease-in-out;
            margin-top: 50px;
            margin-bottom: 60px;
        }

        .login-box:hover {
            transform: scale(1.05);
            background: rgba(255, 255, 255, 0.2);
        }

        .login-box h2 {
            color: #fff;
            margin-bottom: 20px;
            font-size: 1.8rem;
            animation: fadeInDown 1.5s ease;
        }

        .form-group label {
            color: #fff;
            font-weight: bold;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.8);
            border: none;
            padding: 12px;
            border-radius: 8px;
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            background: #fff;
            box-shadow: 0 0 10px #e8114f;
            outline: none;
        }

        .btn-primary {
            width: 100%;
            font-weight: bold;
            background-color: #e8114f;
            border: none;
            padding: 10px;
            border-radius: 8px;
            transition: background-color 0.3s, transform 0.3s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #eb4724;
            transform: scale(1.05);
        }

        .error {
            color: red;
            font-size: 0.9em;
        }

        .show-password {
            position: absolute;
            right: 10px;
            top: 38px;
            cursor: pointer;
        }

        .form-group {
            position: relative;
        }

        .password-strength {
            margin-top: 10px;
            height: 10px;
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
        }

        .password-strength .strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s;
        }

        .weak { background-color: red; }
        .medium { background-color: orange; }
        .strong { background-color: green; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
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

    <div class="login-container">
        <div class="login-box">
            <h2>Login as Faculty or Admin</h2>
            <!-- PHP Validation and Login Logic -->
            <?php
// Start output buffering to prevent header errors
ob_start();
// Database connection variables
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "login"; // Ensure this matches your database name

// Establish connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$roleError = $usernameError = $passwordError = "";
$username = $role = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Validate role
    if (empty($role)) {
        $roleError = "Role is required.";
    }

    // Validate username
    if (empty($username)) {
        $usernameError = "Username is required.";
    }

    // Validate password
    if (empty($password)) {
        $passwordError = "Password is required.";
    }

    // If no validation errors, proceed
    if (empty($roleError) && empty($usernameError) && empty($passwordError)) {
        // Check if user exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
        $stmt->bind_param("ss", $username, $role);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch user data
            $user = $result->fetch_assoc();
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Redirect based on role
                if ($role == "admin") {
                    header("Location: 2a.php");
                } elseif ($role == "faculty") {
                    header("Location: 2a.php");
                }
                exit();
            } else {
                $passwordError = "Incorrect password.";
            }
        } else {
            // Redirect to register page for new faculty
            if ($role == "faculty") {
                header("Location: register.php");
                exit();
            }
            $usernameError = "User not found.";
        }
        $stmt->close();
    }
}
$conn->close();
ob_end_flush();
?>

            <!-- Login Form -->
            <form id="loginForm" method="post" action="register.php">
                <div class="form-group">
                    <label for="role"><i class="fas fa-user-tag"></i> Role:</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="admin" <?php if (isset($role) && $role == "admin") echo "selected"; ?>>Admin</option>
                        <option value="faculty" <?php if (isset($role) && $role == "faculty") echo "selected"; ?>>Faculty</option>
                    </select>
                    <span class="error"><?php echo $roleError; ?></span>
                </div>
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username:</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" value="<?php echo isset($username) ? $username : ''; ?>" required>
                    <span class="error"><?php echo $usernameError; ?></span>
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password:</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                    <span class="error"><?php echo $passwordError; ?></span>
                    <span class="show-password"><i class="fas fa-eye"></i></span>

                    <!-- Password Strength Meter -->
                    <div class="password-strength">
                        <div class="strength-bar"></div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Submit</button><br>
                <a href="register.php">Register</a>
            </form>
        </div>
    </div>

    <script>
        // Show/Hide password functionality
        document.querySelector('.show-password').addEventListener('click', function () {
            let passwordField = document.getElementById('password');
            let passwordIcon = this.querySelector('i');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        });

        // Password Strength Meter
        document.getElementById('password').addEventListener('input', function () {
            const strengthBar = document.querySelector('.strength-bar');
            const password = this.value;
            let strength = 0;

            // Criteria for password strength
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[@$!%*?&]/.test(password)) strength++;

            // Update strength bar based on the strength value
            strengthBar.style.width = (strength * 20) + '%';
            if (strength <= 2) {
                strengthBar.className = 'strength-bar weak';
            } else if (strength <= 4) {
                strengthBar.className = 'strength-bar medium';
            } else {
                strengthBar.className = 'strength-bar strong';
            }
        });
    </script>
</body>
</html>