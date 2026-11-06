<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Appraisal System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            margin: 20px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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
            transition: transform 0.5s ease;
        }

        header img:hover {
            transform: scale(1.05);
        }

        h2 {
            color: #bf00ff;
            font-size: 18px;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            transition: transform 0.3s ease-in-out;
        }

        th, td {
            text-align: center;
            padding: 15px;
            border: 1px solid #dee2e6;
        }

        th {
            background-color: #b8daff; /* Your specified color */
            color: white;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            transition: background-color 0.3s ease-in-out;
        }

        th:hover {
            background-color: #a0c4d8;
        }

        td input[type="textbox"] {
            width: 80px;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        td input[type="textbox"]:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        td input[type="textbox"].error {
            border-color: red;
        }

        td input[type="textbox"].valid {
            border-color: #28a745;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            margin: 10px;
            font-size: 14px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-submit {
            background-color: #4CAF50;
            color: white;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }

        .btn-add-row, .btn-prev, .btn-next {
            background-color: #007bff;
            color: white;
        }

        .btn-add-row:hover, .btn-prev:hover, .btn-next:hover {
            background-color: #0056b3;
        }

        .btn-prev:hover {
            transform: translateX(-5px);
        }

        .btn-next:hover {
            transform: translateX(5px);
        }

        .hover-effect {
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .hover-effect:hover {
            background-color: #ffdd99;
            transform: scale(1.02);
        }

        .error-msg {
            color: red;
            font-size: 12px;
            display: none;
            text-align: center;
        }

        .success-msg {
            color: green;
            font-size: 14px;
            display: none;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <img src="https://www.pscmr.ac.in/header.jpg" alt="Header Image">
    </header>

    <h2>5. Academic projects guided -- (points will be awarded based on Quality/outcome/societal cause/environmental concern/quality publication) (Max.Score=30)</h2>

    <!-- Add Row Button -->
    <div class="text-justify">
        <button class="btn btn-add-row" onclick="addRow()">Add New Row</button>
    </div>

    <!-- Table -->
    <table class="table table-hover hover-effect">
        <thead>
            <tr>
                <th>Type of Project</th>
                <th>Number of Batches</th>
                <th>Score</th>
                <th>Self Score</th>
                <th>HoD's Score</th>
            </tr>
        </thead>
        <tbody id="projectTable">
            <tr>
                <td>Community Service Projects</td>
                <td><input type="number" class="project-input" placeholder="Enter no. of batches" required></td>
                <td>Score @ 10 for each batch</td>
                <td><input type="textbox" class="project-input" placeholder="Self Score" required></td>
                <td><input type="textbox" class="project-input" placeholder="HoD's Score" required></td>
            </tr>
            <tr>
                <td>Guided Major Projects</td>
                <td><input type="textbox" class="project-input" placeholder="Enter no. of batches" required></td>
                <td>Score @ 10 for each batch</td>
                <td><input type="textbox" class="project-input" placeholder="Self Score" required></td>
                <td><input type="textbox" class="project-input" placeholder="HoD's Score" required></td>
            </tr>
        </tbody>
    </table>

    <!-- Submit Button -->
    <div class="text-center">
        <button class="btn btn-submit" onclick="submitData()">Submit</button>
    </div>

    <!-- Error and Success Messages -->
    <center>
        <div class="error-msg" id="errorMsg">Please fill in all the required fields.</div>
        <div class="success-msg" id="successMsg">Form submitted successfully!</div>
    </center>

    <!-- Previous and Next Buttons -->
    <center>
        <button class="btn btn-prev" onclick="previousPage()">Previous</button>
        <button class="btn btn-next" onclick="nextPage()">Next</button>
    </center>

    <script>
        // Function to add a new row to the table
        function addRow() {
            const table = document.getElementById('projectTable');
            const newRow = `
                <tr>
                    <td><input type="textbox" class="project-input" placeholder="Enter Project Type" required></td>
                    <td><input type="textbox" class="project-input" placeholder="Enter no. of batches" required></td>
                    <td>Score @ 10 for each batch</td>
                    <td><input type="textbox" class="project-input" placeholder="Enter Self Score" required></td>
                    <td><input type="textbox" class="project-input" placeholder="Enter HoD's Score" required></td>
                </tr>`;
            table.insertAdjacentHTML('beforeend', newRow);
        }

        // Form validation and submission logic
        function validateInputs() {
            let isValid = true;
            const inputs = document.querySelectorAll('.project-input');
            inputs.forEach(input => {
                if (input.value.trim() === "") {
                    input.classList.add('error');
                    isValid = false;
                } else {
                    input.classList.remove('error');
                    input.classList.add('valid');
                }
            });
            return isValid;
        }

        function submitData() {
            const errorMsg = document.getElementById('errorMsg');
            const successMsg = document.getElementById('successMsg');
            errorMsg.style.display = 'none';
            successMsg.style.display = 'none';

            if (validateInputs()) {
                successMsg.style.display = 'block';
                setTimeout(() => {
                    alert('Data has been submitted successfully!');
                    // You would typically send data to the server here
                }, 1000);
            } else {
                errorMsg.style.display = 'block';
            }
        }

        function previousPage() {
            window.location.href = 'c.php'; // Replace with actual previous page
        }

        function nextPage() {
            window.location.href = 'e.php'; // Replace with actual next page
        }
    </script>
</body>
</html>