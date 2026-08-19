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

// Fetch all services
$stmt = $pdo->prepare("SELECT * FROM services");
$stmt->execute();
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .services-table-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .services-table-main {
        width: 100%;
        min-width: 1000px;
        margin-bottom: 0;
    }

    .services-table-main th,
    .services-table-main td {
        vertical-align: middle;
        white-space: nowrap;
    }

    .service-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .service-actions a {
        text-decoration: none;
    }

    .service-actions .ce5 {
        margin: 0;
    }

    @media (max-width: 768px) {
        .services-table-main {
            min-width: 0;
        }

        .services-table-main thead {
            display: none;
        }

        .services-table-main,
        .services-table-main tbody,
        .services-table-main tr,
        .services-table-main td {
            display: block;
            width: 100%;
        }

        .services-table-main tr {
            margin-bottom: 15px;
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .services-table-main td {
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

        .services-table-main td:last-child {
            border-bottom: none;
            align-items: flex-start;
        }

        .services-table-main td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #333;
            text-align: left;
            flex: 1;
            padding-right: 12px;
        }

        .services-table-main td>* {
            flex: 1;
            text-align: right;
        }

        .service-actions {
            width: 100%;
        }

        .service-actions a,
        .service-actions button {
            width: 100%;
        }

        .service-actions .ce5 {
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
                    <h2 class="page-title">Manage Services</h2>
                    <ul>
                        <li><a class="active" href="index.php">Home</a></li>
                        <li>Manage Services</li>
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

    <!-- Button to trigger Add Service Modal -->
    <button class="btn btn-primary ce5 mb-1" data-bs-toggle="modal" data-bs-target="#addServiceModal">Add Service</button>

    <div class="services-table-wrapper">
        <table class="table table-bordered mt-4 services-table-main">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Member Price</th>
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $service): ?>
                    <tr>
                        <td data-label="Image">
                            <?php if (!empty($service['image'])): ?>
                                <img src="../images/services/<?= htmlspecialchars($service['image']) ?>" alt="<?= htmlspecialchars($service['name']) ?>" width="70" height="50" style="object-fit: cover; border-radius: 8px;">
                            <?php else: ?><span>No Image</span><?php endif; ?>
                        </td>
                        <td data-label="Name"><?= htmlspecialchars($service['name']) ?></td>
                        <td data-label="Category"><?= htmlspecialchars($service['category']) ?></td>
                        <td data-label="Description"><?= htmlspecialchars($service['description']) ?></td>
                        <td data-label="Price"><?= htmlspecialchars($service['price']) ?></td>
                        <td data-label="Member Price"><?= htmlspecialchars($service['member_price']) ?></td>
                        <td data-label="Duration"><?= htmlspecialchars($service['duration']) ?> mins</td>
                        <td data-label="Actions">
                            <div class="service-actions">
                                <button class="btn btn-primary ce5 mb-1" data-bs-toggle="modal" data-bs-target="#editServiceModal<?= $service['service_id'] ?>">Edit</button>
                                <a href="delete-service.php?service_id=<?= $service['service_id'] ?>" class="btn btn-primary ce5 mb-1 del-btn" onclick="return confirm('Are you sure you want to delete this service?')">Delete</a>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Service Modal -->
                    <div class="modal fade" id="editServiceModal<?= $service['service_id'] ?>" tabindex="-1" aria-labelledby="editServiceModalLabel<?= $service['service_id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="post" action="edit-service.php" enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editServiceModalLabel<?= $service['service_id'] ?>">Edit Service</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="service_id" value="<?= $service['service_id'] ?>">
                                        <div class="form-group">
                                            <label for="name">Service Name</label>
                                            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($service['name']) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="category">Category</label>
                                            <input type="text" class="form-control" name="category" value="<?= htmlspecialchars($service['category']) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" name="description" required><?= htmlspecialchars($service['description']) ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="price">Price</label>
                                            <input type="number" class="form-control" name="price" value="<?= htmlspecialchars($service['price']) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="member_price">Member Price</label>
                                            <input type="number" class="form-control" name="member_price" value="<?= htmlspecialchars($service['member_price']) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="duration">Duration (mins)</label>
                                            <input type="number" class="form-control" name="duration" value="<?= htmlspecialchars($service['duration']) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Service Image</label>
                                            <?php if (!empty($service['image'])): ?>
                                                <div class="mb-2">
                                                    <img src="../images/services/<?php echo htmlspecialchars($service['image']); ?>" alt="<?php echo htmlspecialchars($service['name']); ?>" width="120" height="80" style="object-fit: cover; border-radius: 8px;">
                                                    <p class="small text-muted mt-1">Current Image: <?php echo htmlspecialchars($service['image']); ?></p>
                                                </div>
                                            <?php endif; ?>

                                            <input type="file" class="form-control" name="image" id="image" accept=".jpg,.jpeg,.png,.webp">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary ce5" data-bs-dismiss="modal">Close</button>
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

    <!-- Add Service Modal -->
    <div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="add-service.php" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addServiceModalLabel">Add New Service</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Service Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <input type="text" class="form-control" name="category" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" class="form-control" name="price" required>
                        </div>
                        <div class="form-group">
                            <label for="member_price">Member Price</label>
                            <input type="number" class="form-control" name="member_price" required>
                        </div>
                        <div class="form-group">
                            <label for="duration">Duration (mins)</label>
                            <input type="number" class="form-control" name="duration" required>
                        </div>
                        <div class="form-group">
                            <label for="image">Service Image</label>
                            <input type="file" class="form-control" name="image" id="image" accept=".jpg,.jpeg,.png,.webp">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary ce5" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_service" class="btn btn-primary ce5 mb-1">Add Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<?php include 'footer.php'; ?>