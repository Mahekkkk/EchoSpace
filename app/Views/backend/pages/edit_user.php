<?= $this->extend('backend/layout/pages-layout.php') ?>
<?= $this->section('content') ?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $pageTitle ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 20px;
            animation: slideDown 0.8s ease-in-out;
        }

        form {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button, .back-button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            text-transform: uppercase;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        button:hover, .back-button:hover {
            background-color: #45a049;
        }

        .back-button {
            display: inline-block;
            margin-top: 10px;
            background-color: #f44336;
            text-decoration: none;
            text-align: center;
        }

        .back-button:hover {
            background-color: #e53935;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <h1><?= $pageTitle ?></h1>

    <form method="post" action="<?= site_url('admin/update-user/' . $user['id']) ?>">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= $user['name'] ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= $user['email'] ?>" required>

        <button type="submit">Update User</button>
    </form>

    <!-- Back Button -->
    <div style="text-align: center; margin-top: 20px;">
        <a href="<?= site_url('admin/manage-users') ?>" class="back-button">Back to Manage Users</a>
    </div>
</body>
</html>
<?= $this->endSection() ?>
