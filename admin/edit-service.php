<?php
include '../dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_id = $_POST['service_id'];

    $stmt = $pdo->prepare("SELECT image FROM services WHERE service_id = :service_id");
    $stmt->execute([':service_id' => $service_id]);
    $currentService = $stmt->fetch(PDO::FETCH_ASSOC);
    $currentImage = $currentService['image'] ?? null;

    $imageName = $currentImage;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../images/services/';
        $extension = strtolower(
            pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION)
        );
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($extension, $allowedExtensions)) {
            echo "<script>
                alert('Invalid image format. Only JPG, JPEG, PNG and WEBP are allowed.');
                window.location.href = 'manage-services.php';
              </script>";
            exit;
        }
        $imageName = basename($_FILES['image']['name']);

        if (!move_uploaded_file(
            $_FILES['image']['tmp_name'],
            $uploadDir . $imageName
        )) {
            echo "<script>
                alert('Failed to upload image.');
                window.location.href = 'manage-services.php';
              </script>";
            exit;
        }

        if (!empty($currentImage) && $currentImage !== $imageName) {
            $oldImagePath = $uploadDir . $currentImage;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
    }

    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $member_price = $_POST['member_price'];
    $duration = $_POST['duration'];

    try {

        $stmt = $pdo->prepare("UPDATE services SET name = :name, category = :category, description = :description, price = :price, member_price = :member_price, duration = :duration, image = :image
        WHERE service_id = :service_id");

        $stmt->execute([
            ':name' => $name,
            ':category' => $category,
            ':description' => $description,
            ':price' => $price,
            ':member_price' => $member_price,
            ':duration' => $duration,
            ':image' => $imageName,
            ':service_id' => $service_id
        ]);
        echo "<script>alert('Service updated successfully!'); window.location.href = 'manage-services.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Failed to update service: " . $e->getMessage() . "'); window.location.href = 'manage-services.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request!'); window.location.href = 'manage-services.php';</script>";
}
