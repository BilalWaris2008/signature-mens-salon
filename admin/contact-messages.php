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

// Fetch all contact messages
$stmt = $pdo->prepare("SELECT * FROM contact_messages ORDER BY submitted_at DESC");
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .contact-messages-table-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .contact-messages-table-main {
        width: 100%;
        min-width: 1100px;
        margin-bottom: 0;
    }

    .contact-messages-table-main th,
    .contact-messages-table-main td {
        vertical-align: middle;
        white-space: nowrap;
    }

    .message-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .message-actions a {
        text-decoration: none;
    }

    .message-actions .ce5 {
        margin: 0;
    }

    @media (max-width: 768px) {
        .contact-messages-table-main {
            min-width: 0;
        }

        .contact-messages-table-main thead {
            display: none;
        }

        .contact-messages-table-main,
        .contact-messages-table-main tbody,
        .contact-messages-table-main tr,
        .contact-messages-table-main td {
            display: block;
            width: 100%;
        }

        .contact-messages-table-main tr {
            margin-bottom: 15px;
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .contact-messages-table-main td {
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

        .contact-messages-table-main td:last-child {
            border-bottom: none;
            align-items: flex-start;
        }

        .contact-messages-table-main td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #333;
            text-align: left;
            flex: 1;
            padding-right: 12px;
        }

        .contact-messages-table-main td>* {
            flex: 1;
            text-align: right;
        }

        .message-actions {
            width: 100%;
            justify-content: flex-end;
        }

        .message-actions a,
        .message-actions button {
            width: 100%;
        }

        .message-actions .ce5 {
            width: 100%;
            display: block;
        }
    }
</style>

<section class="breadcrumbs-area ptb-100 bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="breadcrumbs">
                    <h2 class="page-title">Contact Messages</h2>
                    <ul>
                        <li><a class="active" href="index.php">Home</a></li>
                        <li>Contact Messages</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container col-12 ptb-100">
    <div class="contact-messages-table-wrapper">
        <table class="table table-bordered mt-4 contact-messages-table-main">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($messages)): ?>
                    <?php foreach ($messages as $message): ?>
                        <tr>
                            <td data-label="Name"><?= htmlspecialchars($message['name']) ?></td>
                            <td data-label="Email"><?= htmlspecialchars($message['email']) ?></td>
                            <td data-label="Phone"><?= htmlspecialchars($message['phone']) ?></td>
                            <td data-label="Subject"><?= htmlspecialchars($message['subject']) ?></td>
                            <td data-label="Message"><?= htmlspecialchars($message['message']) ?></td>
                            <td data-label="Submitted At"><?= htmlspecialchars($message['submitted_at']) ?></td>
                            <td data-label="Actions">
                                <div class="message-actions">
                                    <a href="delete-contact-message.php?id=<?= $message['id'] ?>" class="btn btn-primary ce5 mb-1" onclick="return confirm('Are you sure you want to delete this message?')">Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center" data-label="Message">No contact messages found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>