<?php

$conn = mysqli_connect("localhost", "root", "", "expense_tracker");

if (!$conn) {
    die("Database connection failed");
}

$id = $_GET['id'];

$sql = "DELETE FROM expenses WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    header("Location: index.php");
    exit;
} else {
    echo "Error deleting expense";
}

?>