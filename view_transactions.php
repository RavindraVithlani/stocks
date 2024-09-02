<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Transactions</title>
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
            width: 100%;
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
            padding: 10px;
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

        /* Filter Styles */
        .filters {
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filters form {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filters label {
            margin: 0;
        }

        .filters select, .filters input {
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .filters button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .filters button:hover {
            background-color: #0056b3;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .table-wrapper {
                overflow-x: auto;
            }

            table {
                font-size: 14px;
                min-width: 600px; /* Ensures table is wide enough to be scrolled */
                border: 0;
            }

            .filters {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Transactions List</h1>

        <!-- Link to go back to the home page -->
        <a href="index.php" class="nav-link">Home</a>

        <!-- Filters -->
        <div class="filters">
            <form method="GET" action="">
                <!-- Type Filter -->
                <label for="type">Type:</label>
                <select id="type" name="type">
                    <option value="">All Types</option>
                    <?php
                    // Include the connection file
                    include 'connection.php';

                    // Establish the database connection
                    $conn = getDbConnection();

                    // Fetch distinct types
                    $typesResult = $conn->query("SELECT DISTINCT type FROM transactions");
                    while ($row = $typesResult->fetch_assoc()) {
                        $selected = isset($_GET['type']) && $_GET['type'] == $row['type'] ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($row['type']) . "' $selected>" . htmlspecialchars($row['type']) . "</option>";
                    }

                    // Fetch distinct stock names
                    $stocksResult = $conn->query("SELECT DISTINCT stock_name FROM transactions");
                    ?>
                </select>

                <!-- Stock Name Filter -->
                <label for="stock_name">Stock Name:</label>
                <select id="stock_name" name="stock_name">
                    <option value="">All Stocks</option>
                    <?php
                    while ($row = $stocksResult->fetch_assoc()) {
                        $selected = isset($_GET['stock_name']) && $_GET['stock_name'] == $row['stock_name'] ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($row['stock_name']) . "' $selected>" . htmlspecialchars($row['stock_name']) . "</option>";
                    }
                    ?>

                </select>

                <!-- Date Filter -->
                <label for="date">Date:</label>
                <select id="date" name="date">
                    <option value="">All Dates</option>
                    <?php
                    // Fetch distinct dates
                    $datesResult = $conn->query("SELECT DISTINCT DATE(time) as date FROM transactions ORDER BY date DESC");
                    while ($row = $datesResult->fetch_assoc()) {
                        $selected = isset($_GET['date']) && $_GET['date'] == $row['date'] ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($row['date']) . "' $selected>" . htmlspecialchars($row['date']) . "</option>";
                    }
                    ?>
                </select>

                <button type="submit">Apply Filters</button>
            </form>
        </div>

        <div class="table-wrapper">
            <?php
            // Establish the database connection
            $conn = getDbConnection();

            // Get filter values from the form
            $type = isset($_GET['type']) ? $_GET['type'] : '';
            $stock_name = isset($_GET['stock_name']) ? $_GET['stock_name'] : '';
            $date = isset($_GET['date']) ? $_GET['date'] : '';

            // Prepare the SQL query with filters
            $sql = "SELECT * FROM transactions WHERE 1=1";

            if (!empty($type)) {
                $sql .= " AND type = '" . $conn->real_escape_string($type) . "'";
            }
            if (!empty($stock_name)) {
                $sql .= " AND stock_name = '" . $conn->real_escape_string($stock_name) . "'";
            }
            if (!empty($date)) {
                $sql .= " AND DATE(time) = '" . $conn->real_escape_string($date) . "'";
            }

            $result = $conn->query($sql);

            // Check if there are results
            if ($result->num_rows > 0) {
                echo "<table><thead><tr><th>ID</th><th>Type</th><th>Stock Name</th><th>Quantity</th><th>Time</th></tr></thead><tbody>";
                // Fetch and display each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . htmlspecialchars($row['id']) . "</td><td>" . htmlspecialchars($row['type']) . "</td><td>" . htmlspecialchars($row['stock_name']) . "</td><td>" . htmlspecialchars($row['quantity']) . "</td><td>" . htmlspecialchars($row['time']) . "</td></tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No transactions available.</p>";
            }

            // Close the database connection
            closeDbConnection($conn);
            ?>
        </div>
    </div>
</body>
</html>
