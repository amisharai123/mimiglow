<?php
session_start();
include('../db_connection.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");

    if (mysqli_num_rows($result)) {
        $delete = mysqli_query($conn, "DELETE FROM users WHERE id = $id");

        if ($delete) {
            $_SESSION['success'] = "User deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete user.";
        }
    } else {
        $_SESSION['error'] = "User not found.";
    }
} else {
    $_SESSION['error'] = "No user ID provided.";
}

header("Location: manage_users.php");
exit;
?>
