<?php

$conn = mysqli_connect("localhost", "root", "", "expense_tracker");

if (!$conn) {
    die("Database connection failed");
}

$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM expenses WHERE id = $id");
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die("Expense not found");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST["title"];
    $amount = $_POST["amount"];
    $category = $_POST["category"];
    $expense_date = $_POST["expense_date"];
    $description = $_POST["description"];

    $sql = "UPDATE expenses SET
            title='$title',
            amount='$amount',
            category='$category',
            expense_date='$expense_date',
            description='$description'
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error updating expense: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Expense</title>
</head>

<body>

<h1>Edit Expense</h1>

<form method="POST">

    <label>Expense Name:</label>
    <input type="text" name="title"
           value="<?php echo htmlspecialchars($row['title']); ?>" required>

    <br><br>

    <label>Amount:</label>
    <input type="number" name="amount" step="0.01"
           value="<?php echo htmlspecialchars($row['amount']); ?>" required>

    <br><br>

    <label>Category:</label>
    <select name="category">
        <option <?php if($row['category']=="Food") echo "selected"; ?>>Food</option>
        <option <?php if($row['category']=="Travel") echo "selected"; ?>>Travel</option>
        <option <?php if($row['category']=="Shopping") echo "selected"; ?>>Shopping</option>
        <option <?php if($row['category']=="Education") echo "selected"; ?>>Education</option>
        <option <?php if($row['category']=="Other") echo "selected"; ?>>Other</option>
    </select>

    <br><br>

    <label>Date:</label>
    <input type="date" name="expense_date"
           value="<?php echo htmlspecialchars($row['expense_date']); ?>" required>

    <br><br>

    <label>Description:</label>
    <textarea name="description"><?php echo htmlspecialchars($row['description']); ?></textarea>

    <br><br>

    <button type="submit">Update Expense</button>

</form>

</body>
</html>