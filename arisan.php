<?php

// Connect to the database
$conn = mysqli_connect("host", "username", "password", "database");

// Add member
if (isset($_POST['add_member'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $sql = "INSERT INTO members (name, phone, email) VALUES ('$name', '$phone', '$email')";
    mysqli_query($conn, $sql);
}

// Edit member
if (isset($_POST['edit_member'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $sql = "UPDATE members SET name='$name', phone='$phone', email='$email' WHERE id='$id'";
    mysqli_query($conn, $sql);
}

// Delete member
if (isset($_GET['delete_member'])) {
    $id = $_GET['delete_member'];
    $sql = "DELETE FROM members WHERE id='$id'";
    mysqli_query($conn, $sql);
}

// Display member list
$sql = "SELECT * FROM members";
$result = mysqli_query($conn, $sql);

echo "<table>";
echo "<tr><th>Name</th><th>Phone</th><th>Email</th><th>Actions</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['name'] . "</td>";
    echo "<td>" . $row['phone'] . "</td>";
    echo "<td>" . $row['email'] . "</td>";
    echo "<td><a href='?edit_member=" . $row['id'] . "'>Edit</a> | <a href='?delete_member=" . $row['id'] . "'>Delete</a></td>";
    echo "</tr>";
}
echo "</table>";

// Add member form
echo "<form method='post'>";
echo "<input type='text' name='name' placeholder='Name' required>";
echo "<input type='text' name='phone' placeholder='Phone' required>";
echo "<input type='email' name='email' placeholder='Email' required>";
echo "<input type='submit' name='add_member' value='Add Member'>";
echo "</form>";

// Edit member form
if (isset($_GET['edit_member'])) {
    $id = $_GET['edit_member'];
    $sql = "SELECT * FROM members WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    echo "<form method='post'>";
    echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
    echo "<input type='text' name='name' value='" . $row['name'] . "' required>";
    echo "<input type='text' name='phone' value='" . $row['phone'] . "' required>";
echo "<input type='email' name='email' value='" . $row['email'] . "' required>";
echo "<input type='submit' name='edit_member' value='Save Changes'>";
echo "</form>";
}

// Cash payment system
if (isset($_POST['pay_cash'])) {
$id = $_POST['id'];
$sql = "UPDATE members SET paid='1' WHERE id='$id'";
mysqli_query($conn, $sql);
}

// Display cash payment button for unpaid members
$sql = "SELECT * FROM members WHERE paid='0'";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
echo "<form method='post'>";
echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
echo "<input type='submit' name='pay_cash' value='Pay Cash for " . $row['name'] . "'>";
echo "</form>";
}

// Lottery mechanism
if (isset($_POST['draw'])) {
$sql = "SELECT * FROM members WHERE paid='1' ORDER BY RAND() LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
echo "The winner of the monthly lottery is: " . $row['name'];
// reset paid status
$sql = "UPDATE members SET paid='0' WHERE paid='1'";
mysqli_query($conn, $sql);
}

echo "<form method='post'>";
echo "<input type='submit' name='draw' value='Draw'>";
echo "</form>";

mysqli_close($conn);

?>
