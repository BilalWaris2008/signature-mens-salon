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

// Fetch all blog posts
$stmt = $pdo->prepare("SELECT * FROM blog ORDER BY post_date DESC");
$stmt->execute();
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<style>
    .blogs-table-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .blogs-table-main {
        width: 100%;
        min-width: 900px;
        margin-bottom: 0;
    }

    .blogs-table-main th,
    .blogs-table-main td {
        vertical-align: middle;
        white-space: nowrap;
    }

    .blog-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .blog-actions a {
        text-decoration: none;
    }

    .blog-actions .ce5 {
        margin: 0;
    }

    .blog-thumb {
        width: 100px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        display: block;
    }

    @media (max-width: 768px) {
        .blogs-table-main {
            min-width: 0;
        }

        .blogs-table-main thead {
            display: none;
        }

        .blogs-table-main,
        .blogs-table-main tbody,
        .blogs-table-main tr,
        .blogs-table-main td {
            display: block;
            width: 100%;
        }

        .blogs-table-main tr {
            margin-bottom: 15px;
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .blogs-table-main td {
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

        .blogs-table-main td:last-child {
            border-bottom: none;
            align-items: flex-start;
        }

        .blogs-table-main td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #333;
            text-align: left;
            flex: 1;
            padding-right: 12px;
        }

        .blogs-table-main td>* {
            flex: 1;
            text-align: right;
        }

        .blog-thumb {
            margin-left: auto;
        }

        .blog-actions {
            width: 100%;
            justify-content: flex-end;
        }

        .blog-actions a,
        .blog-actions button {
            width: 100%;
        }

        .blog-actions .ce5 {
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
                    <h2 class="page-title">Manage Blog Posts</h2>
                    <ul>
                        <li><a class="active" href="index.php">Home</a></li>
                        <li>Manage Blog Posts</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container col-12 ptb-100">
    <button class="btn btn-primary ce5 mb-1" data-bs-toggle="modal" data-bs-target="#addBlogModal">Add Blog Post</button>

    <div class="blogs-table-wrapper">
        <table class="table table-bordered mt-4 blogs-table-main">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Tags</th>
                    <th>Post Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($blogs as $blog): ?>
                    <tr>
                        <td data-label="Image">
                            <img src="../<?= htmlspecialchars($blog['image']) ?>" alt="Blog Image" class="blog-thumb">
                        </td>
                        <td data-label="Title"><?= htmlspecialchars($blog['title']) ?></td>
                        <td data-label="Category"><?= htmlspecialchars($blog['category']) ?></td>
                        <td data-label="Tags"><?= htmlspecialchars($blog['tags']) ?></td>
                        <td data-label="Post Date"><?= date('F d, Y', strtotime($blog['post_date'])) ?></td>
                        <td data-label="Actions">
                            <div class="blog-actions">
                                <button class="btn btn-primary ce5 mb-1" data-bs-toggle="modal" data-bs-target="#editBlogModal<?= $blog['id'] ?>">Edit</button>
                                <a href="delete-blog.php?id=<?= $blog['id'] ?>" class="btn btn-primary ce5 mb-1" onclick="return confirm('Are you sure you want to delete this blog post?')">Delete</a>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="editBlogModal<?= $blog['id'] ?>" tabindex="-1" aria-labelledby="editBlogModalLabel<?= $blog['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="post" action="edit-blog.php" enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editBlogModalLabel<?= $blog['id'] ?>">Edit Blog Post</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?= $blog['id'] ?>">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($blog['title']) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="category">Category</label>
                                            <input type="text" class="form-control" name="category" value="<?= htmlspecialchars($blog['category']) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="tags">Tags</label>
                                            <input type="text" class="form-control" name="tags" value="<?= htmlspecialchars($blog['tags']) ?>" placeholder="Comma-separated tags">
                                        </div>
                                        <div class="form-group">
                                            <label for="content">Content</label>
                                            <textarea class="form-control" name="content" required><?= htmlspecialchars($blog['content']) ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Image</label>
                                            <input type="file" class="form-control" name="image">
                                            <img src="../<?= htmlspecialchars($blog['image']) ?>" alt="Current Blog Image" class="blog-thumb" style="margin-top:10px;">
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
</div>

<div class="modal fade" id="addBlogModal" tabindex="-1" aria-labelledby="addBlogModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="add-blog.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBlogModalLabel">Add New Blog Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <input type="text" class="form-control" name="category" required>
                    </div>
                    <div class="form-group">
                        <label for="tags">Tags</label>
                        <input type="text" class="form-control" name="tags" placeholder="Comma-separated tags" required>
                    </div>
                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea class="form-control" name="content" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" class="form-control" name="image" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary ce5 mb-1" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_blog" class="btn btn-primary ce5 mb-1">Add Blog Post</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has('added') && urlParams.get('added') === 'success') {
        alert('Blog post added successfully!');
    }

    if (urlParams.has('updated') && urlParams.get('updated') === 'success') {
        alert('Blog post updated successfully!');
    }

    if (urlParams.has('deleted') && urlParams.get('deleted') === 'success') {
        alert('Blog post deleted successfully!');
    }

    if (urlParams.has('mail') && urlParams.get('mail') === 'failed') {
        alert('Blog post added, but failed to send notification email.');
    }
</script>


<?php include 'footer.php'; ?>