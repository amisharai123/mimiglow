<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update User | Mimi Glow</title>

    <style>
        body {
            font-family:'Times New Roman', Times, serif;
            background-color: #FBEFEF;
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 550px;
            margin: 2rem auto;
            padding: 1.5rem;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .form-container h2 {
            text-align: center;
            color: #c2185b;
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.3rem;
            font-weight: bold;
            color: #444;
        }

        input {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
        }

        button {
            width: 100%;
            padding: 0.9rem;
            background-color: #ff69b4;
            color: white;
            font-weight: bold;
            font-size: 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family:'Times New Roman', Times, serif;
        }

        button:hover {
            background-color: #ffb6c1;
        }

        .message {
            text-align: center;
            font-weight: bold;
            margin-bottom: 1rem;
            color: red;
        }
    </style>
</head>
<body>

<div id="header-placeholder"></div>
<script>
    fetch('admin_header.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header-placeholder').innerHTML = data;
        })
        .catch(error => console.error('Header load error:', error));
</script>

<div class="form-container">
    <h2>Update User</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="message"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="name">Full Name:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['full_name']) ?>" required>

        <label for="phone">Phone:</label>
        <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label for="password">Password (leave blank to keep unchanged):</label>
        <input type="password" name="password" id="password">

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password">

        <button type="submit">Update User</button>
    </form>
</div>

</body>
</html>
