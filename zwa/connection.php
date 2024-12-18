<?php
/**
 * @var string $host The hostname of the database server.
 */
$host = 'localhost';

/**
 * @var string $dbname The name of the database.
 */
$dbname = 'biriuolg';

/**
 * @var string $username The username for the database connection.
 */
$username = 'biriuolg';

/**
 * @var string $password The password for the database connection.
 */
$password = 'webove aplikace';

/**
 * Create a new MySQLi connection.
 *
 * @var mysqli $conn The MySQLi database connection object.
 */
$conn = new mysqli($host, $username, $password, $dbname);

/**
 * Check the connection.
 */
if ($conn->connect_error) {
    /**
     * Exit the script if the database connection fails.
     */
    die("Connect database error: " . $conn->connect_error);
}