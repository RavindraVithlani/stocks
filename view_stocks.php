<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Stocks</title>
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #f4f4f4;
        }

        /* Header Styles */
        h1 {
            text-align: center;
            padding: 20px;
            margin: 0;
            font-size: 24px;
        }

        /* Container Styles */
        .container {
            max-width: 1000px;
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        td {
            background-color: #ffffff;
        }

        /* Home Button Styles */
        .nav-link {
            display: block;
            text-decoration: none;
            color: #007bff;
            margin: 20px 0;
            font-size: 16px;
            text-align: center;
            padding: 10px 15px;
            border-radius: 5px;
            background-color: #ffffff;
            border: 1px solid #007bff;
            transition: background-color 0.3s, color 0.3s;
        }

        .nav-link:hover {
            background-color: #007bff;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            table {
                font-size: 14px;
                border: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Stocks List</h1>

        <!-- Link to go back to the home page -->
        <a href="index.php" class="nav-link">Home</a>

        <?php
        // Include the connection file
        include 'connection.php';

        // Establish the database connection
        $conn = getDbConnection();

        // Prepare and execute the query
        $sql = "SELECT * FROM stocks";
        $result = $conn->query($sql);

        // Check if there are results
        if ($result->num_rows > 0) {
            echo "<table><thead><tr><th>Stock Name</th><th>Quantity</th></tr></thead><tbody>";
            // Fetch and display each row
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($row['stock_name']) . "</td><td>" . htmlspecialchars($row['quantity']) . "</td></tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No stocks available.</p>";
        }

        // Close the database connection
        closeDbConnection($conn);
        ?>
    </div>
</body>
</html>
