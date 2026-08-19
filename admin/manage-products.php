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

// Fetch all products
$stmt = $pdo->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .products-table-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .products-table-main {
        width: 100%;
        min-width: 1100px;
        margin-bottom: 0;
    }

    .products-table-main th,
    .products-table-main td {
        vertical-align: middle;
        white-space: nowrap;
    }

    .product-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .product-actions a {
        text-decoration: none;
    }

    .product-actions .ce5 {
        margin: 0;
    }

    .product-thumb {
        display: block;
        width: 100px;
        height: auto;
        max-width: 100px;
        object-fit: cover;
        border-radius: 6px;
    }

    @media (max-width: 768px) {
        .products-table-main {
            min-width: 0;
        }

        .products-table-main thead {
            display: none;
        }

        .products-table-main,
        .products-table-main tbody,
        .products-table-main tr,
        .products-table-main td {
            display: block;
            width: 100%;
        }

        .products-table-main tr {
            margin-bottom: 15px;
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .products-table-main td {
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

        .products-table-main td:last-child {
            border-bottom: none;
            align-items: flex-start;
        }

        .products-table-main td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #333;
            text-align: left;
            flex: 1;
            padding-right: 12px;
        }

        .products-table-main td>* {
            flex: 1;
            text-align: right;
        }

        .product-thumb {
            margin-left: auto;
        }

        .product-actions {
            width: 100%;
            justify-content: flex-end;
        }

        .product-actions a,
        .product-actions button {
            width: 100%;
        }

        .product-actions .ce5 {
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
                    <h2 class="page-title">Manage Products</h2>
                    <ul>
                        <li><a class="active" href="index.php">Home</a></li>
                        <li>Manage Products</li>
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

    <!-- Button to trigger Add Product Modal -->
    <button class="btn btn-primary ce5 mb-1" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</button>

    <div class="products-table-wrapper">
        <table class="table table-bordered mt-4 products-table-main">
            <thead>
                <tr>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td data-label="Product Image">
                            <?php if (!empty($product['image_url'])): ?>
                                <img src="../<?= $product['image_url'] ?>" alt="<?= $product['product_name'] ?>" class="img-thumbnail product-thumb">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td data-label="Product Name"><?= $product['product_name'] ?></td>
                        <td data-label="Description"><?= $product['description'] ?></td>
                        <td data-label="Category"><?= $product['category'] ?></td>
                        <td data-label="Price"><?= $product['price'] ?></td>
                        <td data-label="Stock Status"><?= ucfirst($product['stock_status']) ?></td>
                        <td data-label="Actions">
                            <div class="product-actions">
                                <button class="btn btn-primary ce5 mb-1" data-bs-toggle="modal" data-bs-target="#editProductModal<?= $product['product_id'] ?>">Edit</button>
                                <a href="delete-product.php?product_id=<?= $product['product_id'] ?>" class="btn btn-primary ce5 mb-1" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Product Modal -->
                    <div class="modal fade" id="editProductModal<?= $product['product_id'] ?>" tabindex="-1" aria-labelledby="editProductModalLabel<?= $product['product_id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="post" action="edit-product.php" enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editProductModalLabel<?= $product['product_id'] ?>">Edit Product</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                        <div class="form-group">
                                            <label for="product_name">Product Name</label>
                                            <input type="text" class="form-control" name="product_name" value="<?= $product['product_name'] ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" name="description"><?= $product['description'] ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="category">Category</label>
                                            <input type="text" class="form-control" name="category" value="<?= $product['category'] ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="price">Price</label>
                                            <input type="number" class="form-control" name="price" value="<?= $product['price'] ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="stock_status">Stock Status</label>
                                            <select class="form-control" name="stock_status">
                                                <option value="in_stock" <?= ($product['stock_status'] == 'in_stock') ? 'selected' : '' ?>>In Stock</option>
                                                <option value="out_of_stock" <?= ($product['stock_status'] == 'out_of_stock') ? 'selected' : '' ?>>Out of Stock</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Product Image</label>
                                            <input type="file" class="form-control" name="image">
                                            <?php if (!empty($product['image_url'])): ?>
                                                <img src="../<?= $product['image_url'] ?>" class="img-thumbnail mt-2" width="100">
                                                <input type="hidden" name="current_image_url" value="<?= $product['image_url'] ?>">
                                            <?php endif; ?>
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

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="add-product.php" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="product_name">Product Name</label>
                            <input type="text" class="form-control" name="product_name" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <input type="text" class="form-control" name="category">
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" class="form-control" name="price" required>
                        </div>
                        <div class="form-group">
                            <label for="stock_status">Stock Status</label>
                            <select class="form-control" name="stock_status">
                                <option value="in_stock">In Stock</option>
                                <option value="out_of_stock">Out of Stock</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="image">Product Image</label>
                            <input type="file" class="form-control" name="image">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary ce5 mb-1" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_product" class="btn btn-primary ce5 mb-1">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<?php include 'footer.php'; ?>