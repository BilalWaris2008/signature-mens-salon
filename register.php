<?php
include 'header.php';
include 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {

    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telephone  = trim($_POST['telephone']);

    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $message = "Email already exists!";
            $message_type = "danger";
        } else {
            // Insert directly into users table
            $insert = $pdo->prepare("
                INSERT INTO users (first_name, last_name, email, password, telephone)
                VALUES (?, ?, ?, ?, ?)
            ");

            $insert->execute([
                $first_name,
                $last_name,
                $email,
                $password,
                $telephone
            ]);

            $message = "Registration successful! You can now login.";
            $message_type = "success";
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $message_type = "danger";
    }
}
?>

<section class="breadcrumbs-area ptb-50">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="breadcrumbs">
                    <h2 class="page-title">Sign UP</h2>
                    <ul>
                        <li><a class="active" href="index.php">Home</a></li>
                        <li>Sign UP</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="register-area ptb-90 mt-0">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 card pt-5 pb-3">

                <?php if (isset($message)): ?>
                    <div class="alert alert-<?= $message_type ?>">
                        <?= $message ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <input type="text" name="first_name" placeholder="First Name" required class="form-control"><br>
                    <input type="text" name="last_name" placeholder="Last Name" required class="form-control"><br>
                    <input type="email" name="email" placeholder="Email" required class="form-control"><br>
                    <input type="tel" name="telephone" placeholder="Phone" required class="form-control"><br>
                    <input type="password" name="password" placeholder="Password" required class="form-control"><br>
                    

                    <button type="submit" name="register" class="btn btn-primary ce5 btn-large mb-10">Register</button>
                </form>

            </div>
        </div>
    </div>
</section>

<style>
    .form-control {
        padding: 12px;
        font-size: 16px;
        border: 1px solid #000000;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .btn-primary {
        border: none;
        padding: 12px;
        font-size: 20px;
        width: 100%;
        height: 45px;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }
</style>

<?php include 'footer.php'; ?>