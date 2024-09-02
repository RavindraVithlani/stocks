<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Stock</title>
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
        header {
            text-align: center;
            padding: 20px;
            width: 100%;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        /* Container Styles */
        .container {
            max-width: 800px;
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Form Styles */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 16px;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Message Styles */
        .message {
            color: #28a745;
            font-size: 16px;
            margin-bottom: 15px;
        }

        /* Error Message Styles */
        .error-message {
            color: #dc3545;
            font-size: 16px;
            margin-bottom: 15px;
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
            .container {
                padding: 10px;
            }

            input[type="submit"] {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Create Stock</h1>
    </header>

    <div class="container">
        <!-- Link to go back to the home page -->
        <a href="index.php" class="nav-link">Home</a>

        <?php
        // Include the connection file
        include 'connection.php';

        // Initialize variables
        $stock_name = '';
        $quantity = '';
        $message = '';
        $error_message = '';

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve and sanitize form data
            $stock_name = htmlspecialchars($_POST['stock_name']);
            $quantity = (int)$_POST['quantity'];

            // Validate input
            if (empty($stock_name) || $quantity < 0) {
                $error_message = 'Please provide a valid stock name and a non-negative quantity.';
            } else {
                // Establish database connection
                $conn = getDbConnection();

                // Check if the stock already exists
                $stmt = $conn->prepare("SELECT stock_name FROM stocks WHERE stock_name = ?");
                $stmt->bind_param("s", $stock_name);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $error_message = 'Stock already exists.';
                } else {
                    // Insert the new stock
                    $stmt = $conn->prepare("INSERT INTO stocks (stock_name, quantity) VALUES (?, ?)");
                    $stmt->bind_param("si", $stock_name, $quantity);
                    if ($stmt->execute()) {
                        $message = 'Stock created successfully!';
                    } else {
                        $error_message = 'Error creating stock.';
                    }
                }

                // Close the database connection
                closeDbConnection($conn);
            }
        }
        ?>

        <!-- Display the message -->
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Display the error message -->
        <?php if ($error_message): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <!-- Form to create a new stock -->
        <form action="create_stock.php" method="post">
            <label for="stock_name">Stock Name:</label>
            <input type="text" id="stock_name" name="stock_name" value="<?php echo htmlspecialchars($stock_name); ?>" required>
            <br>
            <label for="quantity">Initial Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>" required>
            <br>
            <input type="submit" value="Create Stock">
        </form>
    </div>
</body>
</html>
