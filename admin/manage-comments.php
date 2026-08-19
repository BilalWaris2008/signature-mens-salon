<?php
include 'header.php';
include '../dbconnect.php';

// Restrict access for non-admin users
$user_id = $_SESSION['user_id'];
$stmt_role = $pdo->prepare("SELECT role FROM users WHERE user_id = :user_id");
$stmt_role->execute(['user_id' => $user_id]);
$user = $stmt_role->fetch(PDO::FETCH_ASSOC);

if ($user['role'] !== 'admin') {
    echo "<script>alert('Access denied.'); window.location.href = 'index.php';</script>";
    exit;
}

// Fetch all blog posts for the dropdown
$stmt_blog = $pdo->prepare("SELECT id, title FROM blog ORDER BY post_date DESC");
$stmt_blog->execute();
$blogs = $stmt_blog->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .comments-table-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .comments-table-main {
        width: 100%;
        min-width: 950px;
        margin-bottom: 0;
    }

    .comments-table-main th,
    .comments-table-main td {
        vertical-align: middle;
        white-space: nowrap;
    }

    .comment-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .comment-actions a {
        text-decoration: none;
    }

    .comment-actions .ce5 {
        margin: 0;
    }

    @media (max-width: 768px) {
        .comments-table-main {
            min-width: 0;
        }

        .comments-table-main thead {
            display: none;
        }

        .comments-table-main,
        .comments-table-main tbody,
        .comments-table-main tr,
        .comments-table-main td {
            display: block;
            width: 100%;
        }

        .comments-table-main tr {
            margin-bottom: 15px;
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .comments-table-main td {
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

        .comments-table-main td:last-child {
            border-bottom: none;
            align-items: flex-start;
        }

        .comments-table-main td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #333;
            text-align: left;
            flex: 1;
            padding-right: 12px;
        }

        .comments-table-main td>* {
            flex: 1;
            text-align: right;
        }

        .comment-actions {
            width: 100%;
            justify-content: flex-end;
        }

        .comment-actions a,
        .comment-actions button {
            width: 100%;
        }

        .comment-actions .ce5 {
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
                    <h2 class="page-title">Manage Comments</h2>
                    <ul>
                        <li><a class="active" href="index.php">Home</a></li>
                        <li>Manage Comments</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container col-12 ptb-100">
    <div class="form-group">
        <label for="blogSelector">Select Blog Post:</label>
        <select id="blogSelector" class="form-control">
            <option value="">-- Select Blog Post --</option>
            <?php foreach ($blogs as $blog): ?>
                <option value="<?= $blog['id'] ?>"><?= htmlspecialchars($blog['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="comments-table-wrapper mt-4">
        <table class="table table-bordered comments-table-main" id="commentsTable">
            <thead>
                <tr>
                    <th>Blog Title</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Comment</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" class="text-center" data-label="Message">Select a blog post to load comments.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.getElementById('blogSelector').addEventListener('change', function() {
        var blogId = this.value;

        var tableBody = document.querySelector('#commentsTable tbody');
        tableBody.innerHTML = '<tr><td colspan="6" class="text-center" data-label="Message">Loading comments...</td></tr>';

        if (blogId) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get-comments.php?blog_id=' + blogId, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    var response = JSON.parse(this.responseText);
                    if (response.success) {
                        tableBody.innerHTML = '';
                        response.comments.forEach(function(comment) {
                            var row = `
                            <tr>
                                <td data-label="Blog Title">${comment.title}</td>
                                <td data-label="Name">${comment.author}</td>
                                <td data-label="Email">${comment.email}</td>
                                <td data-label="Comment">${comment.content}</td>
                                <td data-label="Date">${comment.created_at}</td>
                                <td data-label="Actions">
                                    <div class="comment-actions">
                                        <a href="delete-comment.php?id=${comment.id}" class="btn btn-primary ce5 mb-1" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        `;
                            tableBody.innerHTML += row;
                        });
                    } else {
                        tableBody.innerHTML = '<tr><td colspan="6" class="text-center" data-label="Message">No comments found for this blog post.</td></tr>';
                    }
                }
            };
            xhr.send();
        } else {
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center" data-label="Message">Select a blog post to load comments.</td></tr>';
        }
    });
</script>

<?php include 'footer.php'; ?>