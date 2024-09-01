<?php
// connection.php

/**
 * Establish a connection to the MySQL database.
 * 
 * @return mysqli The database connection object.
 */
function getDbConnection() {
    // Database configuration
    $host = 'localhost';  // Your database host
    $username = 'root';  // Your database username
    $password = '';  // Your database password
    $database = 'stocks';  // Your database name

    // Create a new mysqli object and connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

/**
 * Close the given MySQL database connection.
 * 
 * @param mysqli $conn The database connection object.
 */
function closeDbConnection($conn) {
    if ($conn instanceof mysqli) {
        $conn->close();
    }
}
?>
