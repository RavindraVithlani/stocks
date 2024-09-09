<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell Stock</title>
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
            position: relative; /* For positioning the button */
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

        select,
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

        /* Error Message Styles */
        #error_message {
            color: #dc3545;
            font-size: 16px;
        }

        /* Message Styles */
        .message {
            color: #28a745;
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
        <h1>Sell Stock</h1>
    </header>

    <div class="container">
        <!-- Link to go back to the home page -->
        <a href="index.php" class="nav-link">Home</a>

        <?php
        // Include the connection file
        include 'connection.php';

        // Initialize variables
        $message = '';
        $stocks = [];
        $error_message = '';

        // Fetch stocks for the dropdown
        $conn = getDbConnection();
        $sql = "SELECT stock_name, quantity, rack_number FROM stocks";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $stocks[] = $row;
            }
        }
        closeDbConnection($conn);

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve and sanitize form data
            $stock_name = htmlspecialchars($_POST['stock_name']);
            $quantity = (int)$_POST['quantity'];

            // Establish database connection
            $conn = getDbConnection();

            // Check stock availability
            $stmt = $conn->prepare("SELECT quantity, rack_number FROM stocks WHERE stock_name = ?");
            $stmt->bind_param("s", $stock_name);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $available_quantity = $row['quantity'];
                $rack_number = $row['rack_number'];

                if ($quantity <= $available_quantity) {
                    // Update stock quantity
                    $new_quantity = $available_quantity - $quantity;
                    
                    if ($new_quantity === 0) {
                        $rack_number = 0; // Set rack number to 0 if quantity is 0
                    }

                    $stmt = $conn->prepare("UPDATE stocks SET quantity = ?, rack_number = ? WHERE stock_name = ?");
                    $stmt->bind_param("iis", $new_quantity, $rack_number, $stock_name);
                    $stmt->execute();

                    // Record the transaction
                    $stmt = $conn->prepare("INSERT INTO transactions (type, stock_name, quantity) VALUES (?, ?, ?)");
                    $type = 'Sell';
                    $stmt->bind_param("ssi", $type, $stock_name, $quantity);
                    $stmt->execute();

                    $message = 'Stock sold successfully!';
                } else {
                    $error_message = 'Insufficient quantity available.';
                }
            } else {
                $error_message = 'Stock not found.';
            }

            // Close the database connection
            closeDbConnection($conn);
        }
        ?>

        <!-- Display the message -->
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Form to submit stock sale -->
        <form action="sell_stock.php" method="post" onsubmit="return validateQuantity()">
            <label for="stock_name">Select Stock:</label>
            <select id="stock_name" name="stock_name" required>
                <option value="" disabled selected>Select a stock</option>
                <?php foreach ($stocks as $stock): ?>
                    <option value="<?php echo htmlspecialchars($stock['stock_name']); ?>"
                            data-quantity="<?php echo htmlspecialchars($stock['quantity']); ?>">
                        <?php echo htmlspecialchars($stock['stock_name']); ?> (Available: <?php echo htmlspecialchars($stock['quantity']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required>
            <br>
            <?php if ($error_message): ?>
                <span id="error_message"><?php echo $error_message; ?></span>
            <?php endif;?>
            <br>
            <input type="submit" value="Sell Stock">
        </form>
    </div>
</body>
</html>
