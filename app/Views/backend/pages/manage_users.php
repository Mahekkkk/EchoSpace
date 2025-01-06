<?= $this->extend('backend/layout/pages-layout.php') ?>
<?= $this->section('content') ?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $pageTitle ?></title>
    <style>
        /* Basic Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            margin-top: 20px;
            animation: fadeIn 1s ease-in-out;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        /* Row hover effect */
        tr:hover {
            background-color: #f1f1f1;
            transform: scale(1.01);
        }

        /* Alternate row colors */
        tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        /* Table Header Styling */
        th {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        /* Action Links Styling */
        a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #ff5722;
        }

        /* Table Title Styling */
        h1 {
            text-align: center;
            color: #333;
            font-family: 'Arial Black', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 20px;
            animation: slideDown 0.8s ease-in-out;
        }

        /* Search Bar Styling */
        .search-container {
            text-align: center;
            margin: 20px 0;
        }

        .search-container input[type="text"] {
            width: 40%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-container button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .search-container button:hover {
            background-color: #45a049;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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

    <!-- Search Bar -->
    <div class="search-container">
        <form method="get" action="<?= site_url('admin/manage-users') ?>">
            <input type="text" name="search" value="<?= isset($_GET['search']) ? esc($_GET['search']) : '' ?>" placeholder="Search users by name or email">
            <button type="submit">Search</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Posts</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= $user['name'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['post_count'] ?></td>
                        <td><?= $user['created_at'] ?></td>
                        <td>
                            <a href="<?= site_url('admin/edit-user/' . $user['id']) ?>">Edit</a> |
                            <a href="<?= site_url('admin/delete-user/' . $user['id']) ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; font-style: italic;">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?= $this->endSection() ?>
