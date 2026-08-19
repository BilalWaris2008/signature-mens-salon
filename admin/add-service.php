<?php
include '../dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $member_price = $_POST['member_price'];
    $duration = $_POST['duration'];

    $imageName = null;

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
    }

    try {

        $stmt = $pdo->prepare("
            INSERT INTO services 
            (name, category, description, price, member_price, duration, image) 
            VALUES 
            (:name, :category, :description, :price, :member_price, :duration, :image)
        ");

        $stmt->execute([
            ':name' => $name,
            ':category' => $category,
            ':description' => $description,
            ':price' => $price,
            ':member_price' => $member_price,
            ':duration' => $duration,
            ':image' => $imageName
        ]);
        echo "<script>
                alert('Service added successfully!');
                window.location.href = 'manage-services.php';
              </script>";
    } catch (Exception $e) {

        if ($imageName && file_exists('../images/services/' . $imageName)) {
            unlink('../images/services/' . $imageName);
        }
        echo "<script>
                alert('Failed to add service: " . $e->getMessage() . "');
                window.location.href = 'manage-services.php';
              </script>";
    }
} else {

    echo "<script>
            alert('Invalid request!');
            window.location.href = 'manage-services.php';
          </script>";
}
