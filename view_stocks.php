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

        /* Search Form Styles */
        .search-form {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
        }

        .search-form input[type="text"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            width: 300px;
        }

        .search-form input[type="submit"],
        .search-form input[type="reset"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-left: 10px;
        }

        .search-form input[type="submit"]:hover,
        .search-form input[type="reset"]:hover {
            background-color: #0056b3;
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

            .search-form input[type="text"] {
                width: 200px;
            }

            .search-form input[type="submit"],
            .search-form input[type="reset"] {
                padding: 8px 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Stocks List</h1>

        <!-- Link to go back to the home page -->
        <a href="index.php" class="nav-link">Home</a>

        <!-- Search Form -->
        <form class="search-form" action="view_stocks.php" method="get">
            <input type="text" name="search" placeholder="Search by stock name..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <input type="submit" value="Search">
            <input type="reset" value="Reset" onclick="window.location.href='view_stocks.php';">
        </form>

        <?php
        // Include the connection file
        include 'connection.php';

        // Establish the database connection
        $conn = getDbConnection();

        // Retrieve search term
        $search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

        // Prepare and execute the query
        if ($search) {
            $sql = "SELECT * FROM stocks WHERE stock_name LIKE ?";
            $stmt = $conn->prepare($sql);
            $searchTerm = '%' . $search . '%';
            $stmt->bind_param('s', $searchTerm);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $sql = "SELECT * FROM stocks";
            $result = $conn->query($sql);
        }

        // Check if there are results
        if ($result->num_rows > 0) {
            echo "<table><thead><tr><th>Stock Name</th><th>Quantity</th><th>Rack Number</th></tr></thead><tbody>";
            // Fetch and display each row
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($row['stock_name']) . "</td><td>" . htmlspecialchars($row['quantity']) . "</td><td>" . htmlspecialchars($row['rack_number']) . "</td></tr>";
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
