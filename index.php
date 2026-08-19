<?php
include 'header.php';
include 'dbconnect.php';
?>

<div class="slider-area bg-color-1">
    <div class="slide-active slider-shape-1 slider-activation slider-nav-style-1">
        <div class="single-slider-wrap sbg-1">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="slider-content">
                            <div class="slider-inner text-center">
                                <h1>Signature Men's Salon</h1>
                                <p>Your style, our expertise. Premium grooming crafted for the modern gentleman.</p>
                                <div class="slider-button">
                                    <a class="hs-btn btn-light btn-large" href="contact.php">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="single-slider-wrap sbg-1">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="slider-content">
                            <div class="slider-inner text-center">
                                <h1>Upgrade Your Style</h1>
                                <p>From sharp fades to classic cuts, we deliver precision with every appointment.</p>
                                <div class="slider-button">
                                    <a class="hs-btn btn-light btn-large" href="contact.php">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="single-slider-wrap sbg-1">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="slider-content">
                            <div class="slider-inner text-center">
                                <h1>Experience Premium Grooming</h1>
                                <p>Relax with luxury grooming services designed to keep you confident and refreshed.</p>
                                <div class="slider-button">
                                    <a class="hs-btn btn-light btn-large" href="contact.php">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section id="hs-story-area" class="hs-story-area ptb-90">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="hs-story-thumb">
                    <div class="hs-story-img">
                        <img src="images/story-img/1.png" alt="Our Story Image">
                    </div>
                    <div class="hs-story-text">
                        <div class="hs-story-text-rot">
                            <h3>We Love What We Do, Every Day</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hs-story-wrap">
                    <h2 class="section-title">Our Story</h2>
                    <p class="theme-color pra-1">Where Style Meets Confidence</p>
                    <div class="story-details">
                        <p>At Signature Men's Salon, we believe great grooming is the foundation of confidence. Built with a passion for precision and modern style, we've created a premium space where every gentleman can relax, refresh, and enjoy a first-class grooming experience.</p>
                        <p>Our skilled barbers and grooming specialists are committed to delivering personalized services that suit your lifestyle and personality. From precision haircuts and sharp fades to expert beard styling and premium hair care, every service is designed to help you look and feel your best.</p>
                        <p>With years of experience, exceptional craftsmanship, and a dedication to customer satisfaction, we continue to set the standard for modern men's grooming, combining timeless techniques with the latest trends.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="hs-service-area" class="hs-service-area ptb-90">
    <div class="container">
        <div class="row mb-n6 align-items-stretch">
            <?php
            // Fetch 6 random services from the services table
            $service_query = "SELECT * FROM services ORDER BY RAND() LIMIT 6";
            $services = $pdo->query($service_query)->fetchAll(PDO::FETCH_ASSOC);

            // Loop through each service and display it
            foreach ($services as $service) {
            ?>
                <div class="col-md-6 col-lg-4 mb-6 d-flex">
                    <div class="single-service-area w-100">
                        <a href="services.php">
                            <?php if (!empty($service['image'])): ?>
                                <div class="service-image">
                                    <img src="images/services/<?= htmlspecialchars($service['image']) ?>" alt="<?= htmlspecialchars($service['name']) ?>">
                                </div>
                            <?php endif; ?>
                            <h4 class="ser-vice-tit"><?= $service['name'] ?> (<?= $service['category'] ?>)</h4>
                            <p class="ser-pra"><?= $service['description'] ?></p>
                            <p><strong>Price:<span class="price"> <?= number_format($service['price'], 2) ?> PKR</span></strong> </p>
                            <p><strong>Member Price:<span class="member-price"> <?= number_format($service['member_price'], 2) ?> PKR</span></strong> </p>
                            <p><strong>Duration:</strong> <?= $service['duration'] ?> minutes</p>
                        </a>
                    </div>

                </div>
            <?php } ?>
        </div>
    </div>
</section>

<section id="hs-membership-benefits" class="hs-membership-benefits ptb-90">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="hs-pricing-title text-center">
                    <h2 class="section-title fw-semibold">Membership Benefits</h2>
                    <p class="section-details">As a registered member of Signature Men's Salon, you unlock access to exclusive member prices and enjoy a range of premium services. Sign up today to take advantage of these fantastic benefits!</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-40 mb-n6">
            <div class="col-md-8 col-lg-6 mb-6">
                <div class="hs-priceing-box text-center">
                    <div class="hs-price-head">
                        <h4>Exclusive Member Benefits</h4>
                        <div class="hs-price-box">
                            <p>FREE</p>
                        </div>
                    </div>
                    <div class="hs-price-body">
                        <ul>
                            <li>Access to Member Prices on All Services</li>
                            <li>Discounted Rates on Haircuts</li>
                            <li>Exclusive Discounts on Hair Treatments & Styling</li>
                            <li>Special Member Offers on Products</li>
                            <li>Priority Booking & Scheduling</li>
                            <li>Exclusive Invitations to Salon Events & Promotions</li>
                        </ul>
                        <div class="pricing-btn">
                            <a class="hs-btn" href="register.php">SIGN UP</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="hs-team-area" class="hs-team-area ptb-90">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="hs-pricing-title text-center">
                    <h2 class="section-title bg-color-bark">Our Special Team</h2>
                    <p class="section-details text-white">Meet our skilled barbers and grooming experts. Dedicated to helping every gentleman look sharp, feel confident, and leave with a fresh, stylish look.</p>
                </div>
            </div>
        </div>
        <div class="row mt-40 mb-n7">
            <div class="col-md-6 col-lg-4 mb-7">
                <div class="hs-single-team">
                    <div class="single-team-inner">
                        <div class="single-team-content">
                            <div class="team-thumb staff-img-1">
                                <img src="images/team/1.png" alt="team images">
                            </div>
                            <ul class="team-social-icon">
                                <li><a href="https://www.facebook.com/" target="_blank"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a href="https://www.twitter.com/" target="_blank"><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a href="https://www.skype.com/" target="_blank"><i class="zmdi zmdi-skype"></i></a></li>
                                <li><a href="https://www.linkedin.com/" target="_blank"><i class="zmdi zmdi-linkedin"></i></a></li>
                                <li><a href="https://www.pinterest.com/" target="_blank"><i class="zmdi zmdi-pinterest"></i></a></li>
                            </ul>
                        </div>
                        <div class="team-info">
                            <h4>Azan Khan</h4>
                            <p>Staff</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-7">
                <div class="hs-single-team">
                    <div class="single-team-inner">
                        <div class="single-team-content">
                            <div class="team-thumb staff-img-2">
                                <img src="images/team/2.png" alt="team images">
                            </div>
                            <ul class="team-social-icon">
                                <li><a href="https://www.facebook.com/" target="_blank"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a href="https://www.twitter.com/" target="_blank"><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a href="https://www.skype.com/" target="_blank"><i class="zmdi zmdi-skype"></i></a></li>
                                <li><a href="https://www.linkedin.com/" target="_blank"><i class="zmdi zmdi-linkedin"></i></a></li>
                                <li><a href="https://www.pinterest.com/" target="_blank"><i class="zmdi zmdi-pinterest"></i></a></li>
                            </ul>
                        </div>
                        <div class="team-info">
                            <h4>Hamdan Khan</h4>
                            <p>Staff</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-7">
                <div class="hs-single-team">
                    <div class="single-team-inner">
                        <div class="single-team-content">
                            <div class="team-thumb staff-img-3">
                                <img src="images/team/3.png" alt="team images">
                            </div>
                            <ul class="team-social-icon">
                                <li><a href="https://www.facebook.com/" target="_blank"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a href="https://www.twitter.com/" target="_blank"><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a href="https://www.skype.com/" target="_blank"><i class="zmdi zmdi-skype"></i></a></li>
                                <li><a href="https://www.linkedin.com/" target="_blank"><i class="zmdi zmdi-linkedin"></i></a></li>
                                <li><a href="https://www.pinterest.com/" target="_blank"><i class="zmdi zmdi-pinterest"></i></a></li>
                            </ul>
                        </div>
                        <div class="team-info">
                            <h4>Bilal Waris</h4>
                            <p>Staff</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="hs-portfolio-area" class="hs-portfolio-area ptb-90">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="hs-pricing-title text-center">
                    <h2 class="section-title fw-semibold">Our Works</h2>
                    <p class="section-details">At Signature Men's Salon, style meets precision. Our expert barbers provide tailored haircuts, beard trims, and grooming services designed to match your personality and lifestyle.</p>
                </div>
            </div>
        </div>

        <div class="row our-portfolio-page grid mt-30">
            <?php
            // Fetch 3 random gallery items from the database
            $stmt = $pdo->prepare("SELECT * FROM gallery ORDER BY RAND() LIMIT 3");
            $stmt->execute();
            $gallery_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($gallery_items as $item) {
            ?>
                <div class="pro-item col-lg-4 col-md-6">
                    <div class="our-portfolio" data-title-position="left, top">
                        <div class="our-port-thumb">
                            <img src="<?= $item['file_path'] ?>" alt="<?= $item['title'] ?>">
                        </div>
                        <div class="our-hover-information">
                            <div class="our-hover-action">
                                <a href="<?= $item['file_path'] ?>" data-lightbox="hsportimg" data-title="<?= $item['title'] ?>">
                                    <i class="zmdi zmdi-zoom-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<section id="hs-shop-area" class="hs-shop-area pb-90 pt-60">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="hs-pricing-title text-center">
                    <h2 class="section-title text-dark fw-semibold">Our Shop Items</h2>
                </div>
            </div>
        </div>
        <div class="row mt-40 mb-n6">
            <?php
            // Fetch 4 random products from the database
            $stmt_products = $pdo->prepare("SELECT * FROM products ORDER BY RAND() LIMIT 4");
            $stmt_products->execute();
            $products = $stmt_products->fetchAll(PDO::FETCH_ASSOC);

            foreach ($products as $product) {
            ?>
                <div class="col-md-6 col-lg-3 mb-6">
                    <div class="hs-single-shop-area">
                        <div class="single-shop-thumd">
                            <a href="products.php">
                                <img src="<?= $product['image_url'] ?>" alt="<?= $product['product_name'] ?>">
                            </a>
                        </div>
                        <div class="hs-shop-details">
                            <h4 class="shop-title"><a class="product-name" href="products.php"><?= $product['product_name'] ?></a></h4>
                            <ul class="product-price">
                                <li class="new-price"><?= $product['price'] ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<section id="our-blog-area" class="our-blog-area pt-90 mb-30">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="hs-pricing-title text-center">
                    <h2 class="section-title fw-semibold">Our Latest Blogs</h2>
                    <p class="section-details">Discover the latest men's grooming trends, expert tips, and style inspiration from our blog.</p>
                </div>
            </div>
        </div>
        <div class="row mt-40 mb-n6">
            <?php
            // Fetch 3 random blog posts from the database along with their comment counts
            $stmt_blogs = $pdo->prepare("
                        SELECT b.*, COUNT(c.id) as comments_count 
                        FROM blog b 
                        LEFT JOIN comments c ON b.id = c.blog_id 
                        GROUP BY b.id 
                        ORDER BY RAND() 
                        LIMIT 3
                    ");
            $stmt_blogs->execute();
            $blogs = $stmt_blogs->fetchAll(PDO::FETCH_ASSOC);

            foreach ($blogs as $blog) {
            ?>
                <div class="col-lg-4 col-md-6 mb-6">
                    <div class="single-blog-wrap">
                        <div class="blog-front">
                            <div class="blog-thumb">
                                <img src="<?= htmlspecialchars($blog['image']) ?>" alt="<?= htmlspecialchars($blog['title']) ?>">
                            </div>
                            <div class="blog-hover-info">
                                <div class="blog-hover-action">
                                    <a href="blog-details.php?id=<?= $blog['id'] ?>"><i class="zmdi zmdi-link"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="blog-details">
                            <h4 class="blog-title"><a href="blog-details.php?id=<?= $blog['id'] ?>"><?= htmlspecialchars($blog['title']) ?></a></h4>
                            <ul class="blog-post-date">
                                <li class="post-time"><i class="zmdi zmdi-time"></i><span><?= date('M d, Y', strtotime($blog['post_date'])) ?></span></li>
                                <li class="post-cmt"><i class="zmdi zmdi-comment-alt-text"></i><span>(<?= $blog['comments_count'] ?>)</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<?php include 'footer.php' ?>