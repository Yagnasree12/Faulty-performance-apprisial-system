
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summary Sheet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* General Page Styles */
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f0f8ff; /* Light background color */
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* Top Background Image and Header */
        header {
            width: 100%;
            text-align: center;
            margin-bottom: 20px; /* Space between image and heading */
        }

        header img {
            width: 100%;
           /* max-height: 150px;
            object-fit: cover; /* Ensures the image scales without distortion */
        }

        /* Heading Style */
        h1 {
            background-color: #673AB7; /* Deep purple */
            color: #fff;
            padding: 20px;
            font-size: 2.8em;
            font-family: 'Poppins', sans-serif;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-align: center;
            border-radius: 15px;
            margin-top: 20px; /* Space between image and heading */
            width: 60%; /* Set the width of the heading */
            z-index: 1;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Main Container Styling */
        .container {
            width: 60%;
            margin: 30px auto; /* Add space between heading and table */
            padding: 20px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        /* Table Styling */
        table {
            width: 100%;
            margin: 30px auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: left;
            font-size: 1.1em;
        }

        th {
            background-color: #00796B; /* Dark teal */
            color: white;
        }

        td {
            background-color: #E3F2FD; /* Pale blue */
        }

        /* Table Hover Effect */
        tbody tr:hover {
            background-color: #B2DFDB; /* Light teal on hover */
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        /* Form Input Styles */
        input[type="number"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 2px solid #007bff;
            border-radius: 5px;
            background-color: #fafafa;
        }

        /* Button Styles */
        button {
            width: 20%;
            padding: 14px 28px;
            border: none;
            border-radius: 8px;
            background-color: #0591b6;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #08659e;
            transform: scale(1.05);
        }

        /* Responsive Styling */
        @media screen and (max-width: 768px) {
            .container {
                width: 90%;
            }

            h1 {
                font-size: 2em;
                padding: 15px;
            }

            button {
                width: 40%;
            }

            th, td {
                font-size: 0.9em;
            }
        }
    </style>
    <script>
        function handleSubmit(event) {
            event.preventDefault(); 
            alert("Submitted Successfully");
        }
    </script>
</head>
<body>

    <!-- Header with Background Image -->
    <header>
        <img src="https://www.pscmr.ac.in/header.jpg" alt="Header Image">
    </header>

    <!-- Heading below the image -->
    <h1><i>SUMMARY SHEET</i></h1>

    <!-- Table Container in the Center -->
    <div class="container">
        <form onsubmit="handleSubmit(event)">
            <table class="table table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>S.NO.</th>
                        <th style="text-align: left;">Evaluation Parameter</th>
                        <th>Max Score</th>
                        <th>HOD Score</th>
                        <th>Assessment Screening Principal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td style="text-align: left;">Teaching, Learning & Evaluation</td>
                        <td>375</td>
                        <td><input type="number" name="hod_score_1"></td>
                        <td><input type="number" name="principal_score_1"></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td style="text-align: left;">Research & Development</td>
                        <td>180</td>
                        <td><input type="number" name="hod_score_2"></td>
                        <td><input type="number" name="principal_score_2"></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td style="text-align: left;">Faculty Development</td>
                        <td>120</td>
                        <td><input type="number" name="hod_score_3"></td>
                        <td><input type="number" name="principal_score_3"></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td style="text-align: left;">Profession Related Contribution</td>
                        <td>75</td>
                        <td><input type="number" name="hod_score_4"></td>
                        <td><input type="number" name="principal_score_4"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <th style="text-align:center;">Total</th>
                        <td>750</td>
                        <td><input type="number" name="hod_total_score"></td>
                        <td><input type="number" name="principal_total_score"></td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>

</body>
</html>
