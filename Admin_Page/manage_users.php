<?php
session_start();
include('../db_connection.php'); // use your actual connection file

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['userRole'] !== 'Admin') {
    header("Location: ../Login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users | Mimi Glow</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #F8F4E3;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1rem;
            background: white;
            border-radius: 10px;
            background-color: #FFF7F2;
        }

        h1 {
            text-align: center;
            color: #c2185b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: rgb(249, 172, 211)
        }

        .actions a {
            text-decoration: none;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            margin-right: 5px;
        }

        .edit {
            background-color:rgb(252, 93, 189);
        }

        .delete {
            background-color:rgb(247, 84, 84);
        }

        .actions a:hover {
            opacity: 0.8;
        }

        .search-bar {
            text-align: center;
            margin-bottom: 1rem;
        }

        .search-bar input {
            padding: 0.5rem;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-bar button {
            padding: 0.5rem 1rem;
            background-color: #ff69b4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #ffb6c1;
        }
    </style>
</head>
<body>

<div id="header-placeholder"></div>
<script>
    fetch('admin_header.php')
        .then(res => res.text())
        .then(data => {
            document.getElementById('header-placeholder').innerHTML = data;
        })
        .catch(err => console.error("Header load error:", err));
</script>

<div class="container">
    <h1>Manage Users</h1>

    <div class="search-bar">
        <form method="GET" action="manage_users.php">
            <input type="text" name="search" placeholder="Search by name or email" 
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <?php
    $searchQuery = '';
    if (isset($_GET['search'])) {
        $search = mysqli_real_escape_string($conn, $_GET['search']);
        $searchQuery = "WHERE email LIKE '%$search%' OR full_name LIKE '%$search%'";
    }

    $sql = "SELECT id, full_name, email FROM users $searchQuery";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo '<table>';
        echo '<tr><th>ID</th><th>Name</th><th>Email</th><th>Actions</th></tr>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . htmlspecialchars($row['full_name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
            echo '<td class="actions">';
            echo '<a href="update_users.php?id=' . $row['id'] . '" class="edit">Update</a>';
            echo '<a href="delete_users.php?id=' . $row['id'] . '" class="delete" onclick="return confirm(\'Are you sure you want to delete this user?\')">Delete</a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p style="text-align:center;">No users found.</p>';
    }
    ?>
</div>
</body>
</html>
