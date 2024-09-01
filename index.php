<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management Application</title>
    <style>
        /* Global Styles */
        *{
            margin:0;
            padding:0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #f4f4f4; /* Light background for better contrast */
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

        /* Container for centering content */
        .container {
            max-width: 800px;
            width: 90%; /* Full width for responsiveness */
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff; /* White background for the content area */
            border-radius: 8px; /* Rounded corners for a modern look */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Subtle shadow for depth */
        }

        /* Navigation Styles */
        nav {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        nav a {
            display: block;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            padding: 12px 20px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center;
            width: 100%;
            max-width: 300px;
            font-size: 16px;
        }

        nav a:hover {
            background-color: #0056b3;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            nav {
                padding: 10px;
            }

            nav a {
                width: 90%;
                padding: 12px;
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Stock Management App</h1>
    </header>

    <div class="container">
        <nav>
            <a href="buy_stock.php">Buy Stock</a>
            <a href="sell_stock.php">Sell Stock</a>
            <a href="view_stocks.php">View Stocks</a>
            <a href="view_transactions.php">View Transactions</a>
        </nav>
    </div>
</body>
</html>
