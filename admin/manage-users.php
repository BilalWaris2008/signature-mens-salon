<?php
include 'header.php';
include '../dbconnect.php';

// Fetch current logged-in user's role to restrict access
$user_id = $_SESSION['user_id'];
$stmt_role = $pdo->prepare("SELECT role FROM users WHERE user_id = :user_id");
$stmt_role->execute(['user_id' => $user_id]);
$user = $stmt_role->fetch(PDO::FETCH_ASSOC);

// Restrict access for non-admin users
if ($user['role'] !== 'admin') {
    echo "<script>alert('Access denied.'); window.location.href = 'index.php';</script>";
    exit;
}

$message = "";

// Fetch all users
$stmt = $pdo->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .users-table-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .users-table-main {
        width: 100%;
        min-width: 900px;
        margin-bottom: 0;
    }

    .users-table-main th,
    .users-table-main td {
        vertical-align: middle;
        white-space: nowrap;
    }

    .user-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .user-actions a {
        text-decoration: none;
    }

    .user-actions .ce5 {
        margin: 0;
    }

    @media (max-width: 768px) {
        .users-table-main {
            min-width: 0;
        }

        .users-table-main thead {
            display: none;
        }

        .users-table-main,
        .users-table-main tbody,
        .users-table-main tr,
        .users-table-main td {
            display: block;
            width: 100%;
        }

        .users-table-main tr {
            margin-bottom: 15px;
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .users-table-main td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border: none;
            border-bottom: 1px solid #f0f0f0;
            white-space: normal;
            text-align: right;
            font-size: 14px;
            word-break: break-word;
        }

        .users-table-main td:last-child {
            border-bottom: none;
            align-items: flex-start;
        }

        .users-table-main td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #333;
            text-align: left;
            flex: 1;
            padding-right: 12px;
        }

        .users-table-main td>* {
            flex: 1;
            text-align: right;
        }

        .user-actions {
            width: 100%;
            justify-content: flex-end;
        }

        .user-actions a,
        .user-actions button {
            width: 100%;
        }

        .user-actions .ce5 {
            width: 100%;
            display: block;
        }

        .modal-dialog {
            margin: 1rem;
        }
    }
</style>

<section class="breadcrumbs-area ptb-100 bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="breadcrumbs">
                    <h2 class="page-title">Manage Users</h2>
                    <ul>
                        <li><a class="active" href="index.php">Home</a></li>
                        <li>Manage Users</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container col-12 ptb-100">

    <!-- Display Success or Error Messages -->
    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <!-- Button to trigger Add User Modal -->
    <button class="btn btn-primary ce5 mb-1" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>

    <div class="users-table-wrapper">
        <table class="table table-bordered mt-4 users-table-main">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Telephone</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td data-label="First Name"><?= htmlspecialchars($user['first_name']) ?></td>
                        <td data-label="Last Name"><?= htmlspecialchars($user['last_name']) ?></td>
                        <td data-label="Email"><?= htmlspecialchars($user['email']) ?></td>
                        <td data-label="Telephone"><?= htmlspecialchars($user['telephone']) ?></td>
                        <td data-label="Role"><?= htmlspecialchars(ucfirst($user['role'])) ?></td>
                        <td data-label="Actions">
                            <div class="user-actions">
                                <button class="btn btn-primary ce5 mb-1" data-bs-toggle="modal" data-bs-target="#editUserModal<?= $user['user_id'] ?>">Edit</button>
                                <a href="delete-user.php?user_id=<?= $user['user_id'] ?>" class="btn btn-primary ce5 mb-1" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit User Modal -->
                    <div class="modal fade" id="editUserModal<?= $user['user_id'] ?>" tabindex="-1" aria-labelledby="editUserModalLabel<?= $user['user_id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="post" action="edit-user.php">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editUserModalLabel<?= $user['user_id'] ?>">Edit User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                        <div class="form-group">
                                            <label for="first_name">First Name</label>
                                            <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="last_name">Last Name</label>
                                            <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="telephone">Telephone</label>
                                            <input type="text" class="form-control" name="telephone" value="<?= htmlspecialchars($user['telephone']) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="role">Role</label>
                                            <select class="form-control" name="role">
                                                <option value="user" <?= ($user['role'] == 'user') ? 'selected' : '' ?>>User</option>
                                                <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                                                <option value="staff" <?= ($user['role'] == 'staff') ? 'selected' : '' ?>>Staff</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary ce5 mb-1" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary ce5 mb-1">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="add-user.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" name="last_name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="telephone">Telephone</label>
                            <input type="text" class="form-control" name="telephone" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" name="role">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary ce5 mb-1" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_user" class="btn btn-primary ce5 mb-1">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<?php include 'footer.php'; ?>