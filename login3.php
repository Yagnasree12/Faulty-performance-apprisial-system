<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSCMRCET College Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Style omitted for brevity - remains unchanged */
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
                // Start output buffering
                ob_start();

                // Database connection variables
                $servername = "localhost";
                $username_db = "root";
                $password_db = "";
                $dbname = "login";  // Ensure this is the correct database name

                // Establish connection
                $conn = new mysqli($servername, $username_db, $password_db, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Initialize error messages
                $roleError = $usernameError = $passwordError = "";

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Sanitize inputs
                    $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
                    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

                    // Role validation
                    if (empty($role)) {
                        $roleError = "Please select a role.";
                    }

                    // Username validation: must be alphanumeric
                    if (empty($username)) {
                        $usernameError = "Please enter your username.";
                    } elseif (!preg_match('/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]+$/', $username)) {
                        $usernameError = "Username must be a combination of letters and numbers.";
                    }

                    // Password validation: at least 1 uppercase, 1 lowercase, 1 special character, 1 number, length 8-16
                    if (empty($password)) {
                        $passwordError = "Please enter your password.";
                    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,16}$/', $password)) {
                        $passwordError = "Password should contain uppercase, lowercase, number, and special character.";
                    }

                    if (empty($roleError) && empty($usernameError) && empty($passwordError)) {
                        // Check if username already exists
                        $checkUserStmt = $conn->prepare("SELECT * FROM faculty WHERE username = ?");
                        $checkUserStmt->bind_param("s", $username);
                        $checkUserStmt->execute();
                        $result = $checkUserStmt->get_result();

                        if ($result->num_rows > 0) {
                            $usernameError = "This username is already taken. Please choose another one.";
                        } else {
                            // Hash the password for security
                            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                            // Insert the validated data into the database
                            $stmt = $conn->prepare("INSERT INTO faculty (username, password, role) VALUES (?, ?, ?)");
                            $stmt->bind_param("sss", $username, $hashed_password, $role);

                            if ($stmt->execute()) {
                                // Successful insertion, redirect to menu.php
                                header("Location: menu.php");
                                exit();
                            } else {
                                echo "<div class='error'>Error: " . $stmt->error . "</div>";
                            }

                            $stmt->close();
                        }

                        $checkUserStmt->close();
                    }
                }

                // Close connection
                $conn->close();

                ob_end_flush();
            ?>
            <!-- Login Form -->
            <form id="loginForm" method="post" action="">
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
