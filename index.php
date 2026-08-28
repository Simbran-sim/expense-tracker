<?php
$conn = mysqli_connect("localhost", "root", "", "expense_tracker");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST["title"];
    $amount = $_POST["amount"];
    $category = $_POST["category"];
    $expense_date = $_POST["expense_date"];
    $description = $_POST["description"];

    $sql = "INSERT INTO expenses
            (title, amount, category, expense_date, description)
            VALUES
            ('$title', '$amount', '$category', '$expense_date', '$description')";

    if (mysqli_query($conn, $sql)) {
        $message = "Expense added successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Expense Tracker</title>
    <style>
    body {
    font-family: Arial, sans-serif;
    background: #f4f6f8;
    margin: 0;
    padding: 30px;
    color: #333;
}

h1 {
    text-align: center;
    margin-bottom: 25px;
}

h2 {
    margin-top: 30px;
}

form {
    background: white;
    max-width: 500px;
    margin: auto;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

input, select, textarea {
    width: 100%;
    box-sizing: border-box;
    padding: 10px;
    margin: 7px 0 15px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

button {
    width: 100%;
    padding: 12px;
    background: #333;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

button:hover {
    background: #555;
}

table {
    width: 100%;
    margin-top: 15px;
    background: white;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background: #333;
    color: white;
}

a {
    text-decoration: none;
    margin: 5px;
}

a:hover {
    text-decoration: underline;
}
</style>
</head>

<body>

    <h1>My Expense Tracker</h1>
    <h2>Category-wise Summary</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>Category</th>
        <th>Total Amount</th>
    </tr>

<?php
$category_result = mysqli_query(
    $conn,
    "SELECT category, SUM(amount) AS total
     FROM expenses
     GROUP BY category
     ORDER BY total DESC"
);

while ($category = mysqli_fetch_assoc($category_result)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($category['category']) . "</td>";
    echo "<td>" . number_format($category['total'], 2) . "</td>";
    echo "</tr>";
}
?>

</table>



    <?php
$total_result = mysqli_query($conn, "SELECT SUM(amount) AS total FROM expenses");
$total_row = mysqli_fetch_assoc($total_result);
$total = $total_row['total'] ?? 0;
?>

<h2>Total Expenses: ₹<?php echo number_format($total, 2); ?></h2>

    <?php
    if ($message != "") {
        echo "<p><strong>$message</strong></p>";
    }
    ?>

    <form method="POST">

        <label>Expense Name:</label>
        <input type="text" name="title" required>

        <br><br>

        <label>Amount:</label>
        <input type="number" name="amount" step="0.01" required>

        <br><br>

        <label>Category:</label>
        <select name="category">
            <option>Food</option>
            <option>Travel</option>
            <option>Shopping</option>
            <option>Education</option>
            <option>Other</option>
        </select>

        <br><br>

        <label>Date:</label>
        <input type="date" name="expense_date" required>

        <br><br>

        <label>Description:</label>
        <textarea name="description"></textarea>

        <br><br>

        <button type="submit">Add Expense</button>

    </form>
<h2>My Expenses</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>Expense Name</th>
        <th>Amount</th>
        <th>Category</th>
        <th>Date</th>
        <th>Description</th>
        <th>Action</th>
    </tr>

<?php
$result = mysqli_query($conn, "SELECT * FROM expenses ORDER BY id DESC");

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
    echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
    echo "<td>" . htmlspecialchars($row['category']) . "</td>";
    echo "<td>" . htmlspecialchars($row['expense_date']) . "</td>";
    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
    echo "<td><a href='edit.php?id=".$row['id']."'>Edit</a></td>";
    echo "<td><a href='delete.php?id=".$row['id']."'>Delete</a></td>";
    echo "</tr>";
}
?>

</table>
</body>
</html>