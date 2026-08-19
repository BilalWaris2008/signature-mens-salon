<?php

include '../dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock_status = $_POST['stock_status'];
    $image_url = $_POST['current_image_url'];

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {

        $target_dir = "../images/shop/";
        $target_file = $target_dir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image is valid
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check === false) {
            echo "<script>
                    alert('File is not an image.');
                    window.location.href='manage-products.php';
                  </script>";
            exit;
        }

        // Check file size (5MB max)
        if ($_FILES['image']['size'] > 5000000) {
            echo "<script>
                    alert('Sorry, your file is too large.');
                    window.location.href='manage-products.php';
                  </script>";
            exit;
        }

        // Allow only certain file types
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "<script>
                    alert('Only JPG, JPEG, PNG & GIF files are allowed.');
                    window.location.href='manage-products.php';
                  </script>";
            exit;
        }

        // Upload image
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_url = "images/shop/" . basename($_FILES['image']['name']);
        } else {
            echo "<script>
                    alert('Error uploading image.');
                    window.location.href='manage-products.php';
                  </script>";
            exit;
        }
    }

    try {

        // Update product
        $stmt = $pdo->prepare("
            UPDATE products
            SET
                product_name = :product_name,
                description = :description,
                category = :category,
                price = :price,
                stock_status = :stock_status,
                image_url = :image_url
            WHERE product_id = :product_id
        ");

        $stmt->execute([
            ':product_name' => $product_name,
            ':description'  => $description,
            ':category'     => $category,
            ':price'        => $price,
            ':stock_status' => $stock_status,
            ':image_url'    => $image_url,
            ':product_id'   => $product_id
        ]);

        echo "<script>
                alert('Product updated successfully!');
                window.location.href='manage-products.php';
              </script>";
    } catch (PDOException $e) {

        echo "<script>
                alert('Failed to update product: " . addslashes($e->getMessage()) . "');
                window.location.href='manage-products.php';
              </script>";
    }
} else {

    echo "<script>
            alert('Invalid request!');
            window.location.href='manage-products.php';
          </script>";
}
