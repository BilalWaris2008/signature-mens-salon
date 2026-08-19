<?php

include '../dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $content = trim($_POST['content']);
    $tags = trim($_POST['tags']);

    $target_dir = "../images/blog/";
    $imagePath = "";

    // Upload image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

        // Create folder if it doesn't exist
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $target_dir . $fileName;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is an image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check === false) {
            echo "<script>
                    alert('Selected file is not an image.');
                    window.location.href='manage-blog.php';
                  </script>";
            exit;
        }

        // Maximum file size (5 MB)
        if ($_FILES['image']['size'] > 5000000) {
            echo "<script>
                    alert('Image size must be less than 5MB.');
                    window.location.href='manage-blog.php';
                  </script>";
            exit;
        }

        // Allowed extensions
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($imageFileType, $allowed)) {
            echo "<script>
                    alert('Only JPG, JPEG, PNG, GIF and WEBP images are allowed.');
                    window.location.href='manage-blog.php';
                  </script>";
            exit;
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $imagePath = "images/blog/" . $fileName;
        } else {
            echo "<script>
                    alert('Failed to upload image.');
                    window.location.href='manage-blog.php';
                  </script>";
            exit;
        }
    }

    try {

        $stmt = $pdo->prepare("
            INSERT INTO blog
            (title, content, image, post_date, category, tags)
            VALUES
            (:title, :content, :image, CURDATE(), :category, :tags)
        ");

        $stmt->execute([
            ':title'    => $title,
            ':content'  => $content,
            ':image'    => $imagePath,
            ':category' => $category,
            ':tags'     => $tags
        ]);

        echo "<script>
                alert('Blog added successfully!');
                window.location.href='manage-blog.php';
              </script>";
    } catch (PDOException $e) {

        echo "<script>
                alert('Failed to add blog: " . addslashes($e->getMessage()) . "');
                window.location.href='manage-blog.php';
              </script>";
    }
} else {

    echo "<script>
            alert('Invalid request!');
            window.location.href='manage-blog.php';
          </script>";
}
