<?php 
include 'header.php'; 
include 'dbconnect.php';

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['con_name'];
    $email = $_POST['con_email'];
    $phone = $_POST['con_phone'];
    $subject = $_POST['con_subject'];
    $messageContent = $_POST['con_message'];

    // Prepare SQL insert statement
    $stmt = $pdo->prepare("
        INSERT INTO contact_messages (name, email, phone, subject, message) 
        VALUES (:name, :email, :phone, :subject, :message)
    ");

    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':phone' => $phone,
        ':subject' => $subject,
        ':message' => $messageContent
    ]);

    $message = "Your message has been sent successfully!";
}
?>

<section class="breadcrumbs-area ptb-100 bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="breadcrumbs">
                    <h2 class="page-title">Contact Us</h2>
                    <ul>
                        <li><a class="active" href="index.php">Home</a></li>
                        <li>Contact Us</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="contact-area" class="contact-area mt-0 ptb-100 bg-img-3 bg-gray">
    <div class="container">
        <div class="row">
            <form id="contact-form" method="post" class="col-lg-9">
                <h3 class="contact-title">Send Us a Message:</h3>

                <?php if ($message): ?>
                    <br>
                    <div class="alert alert-success">
                        <?= $message ?>
                    </div>
                <?php endif; ?>

                <div class="row all-contact-text">
                    <div class="col-md-6">
                        <div class="contact-message">
                            <input name="con_name" class="form-control" type="text" required placeholder="Your Name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="contact-message">
                            <input name="con_email" class="form-control" type="email" required placeholder="Your Email">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="contact-message">
                            <input name="con_phone" class="form-control" type="tel" required placeholder="Phone Number">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="contact-message">
                            <input name="con_subject" class="form-control" type="text" required placeholder="Subject">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="contact-textarea">
                            <textarea name="con_message" class="form-control" required placeholder="Your Message"></textarea>
                        </div>
                        <div class="submit mt-10">
                            <button class="hs-btn hs-btn-2" type="submit">Send Message</button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="col-lg-3">
                <div class="single-footer-widget info-style">
                    <h3 class="contact-title">Contact Info:</h3>
                    <div class="hs-footer-address">
                        <div class="ft-single-address">
                            <div class="footer-icon">
                                <a href="#"><i class="zmdi zmdi-pin"></i></a>
                            </div>
                            <div class="footer-address">
                                <p>Address: Aptech Gulshan II, Karachi, Pakistan</p>
                            </div>
                        </div>
                        <div class="ft-single-address">
                            <div class="footer-icon">
                                <a href="mailto:info@signaturemenssalon.com"><i class="zmdi zmdi-email"></i></a>
                            </div>
                            <div class="footer-address">
                                <p><a href="mailto:info@signaturemenssalon.com">signaturemenssalon@dummy.com</a></p>
                                <p><a href="mailto:support@signaturemenssalon.com">support@signaturemenssalon.com</a></p>
                            </div>
                        </div>
                        <div class="ft-single-address">
                            <div class="footer-icon">
                                <a href="tel:+94123456789"><i class="zmdi zmdi-phone"></i></a>
                            </div>
                            <div class="footer-address">
                                <p><a href="tel:+94123456789">+94 111 777 999</a></p>
                                <p><a href="tel:+94123456789">+94 111 555 333</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <p class="form-messege"></p>
        </div>
    </div>
</div>

<div class="map-area">
    <div class="contact-map">
        <div id="hastech">
            <iframe src="https://www.google.com/maps/embed?pb=!1m23!1m12!1m3!1d115775.39836240988!2d67.1015786!3d24.932710399999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m8!3e6!4m0!4m5!1s0x3eb33f5ba1db4061%3A0xad2c7a2c189158d0!2sAptech%20Gulshan%202%2C%20A13%2C%20Block%2016%20Gulshan-e-Iqbal%2C%20Karachi%2C%20Pakistan!3m2!1d24.9052218!2d67.079838!5e0!3m2!1sen!2s!4v1779189583376!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
