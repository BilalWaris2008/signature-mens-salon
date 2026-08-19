<?php
include 'header.php';
include 'dbconnect.php';

// Function to sanitize the category name for filtering
function sanitize_category($category)
{
    return strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $category));
}

// Query to fetch unique categories from the services table
$stmt = $pdo->prepare("SELECT DISTINCT category FROM services");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Query to fetch all services from the database
$stmt_services = $pdo->prepare("SELECT * FROM services");
$stmt_services->execute();
$services = $stmt_services->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .services-filter-area {
        padding-left: 15px;
        padding-right: 15px;
    }

    .port-filter-nav {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .port-filter-nav li {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 18px;
        border-radius: 999px;
        cursor: pointer;
        transition: all 0.25s ease;
        white-space: nowrap;
    }

    .port-filter-nav li.is-checked {
        font-weight: 600;
    }

    .single-service-area {
        height: 100%;
        padding: 28px 24px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        border: 3px solid #999;
    }

    .service-image {
        width: 80%;
        height: 150px;
        margin: 0 auto 20px;
        overflow: hidden;
        border-radius: 8px;
    }

    .service-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    @media (max-width: 991px) {
        .service-image {
            width: 75%;
            height: 140px;
        }
    }

    @media (max-width: 767px) {
        .service-image {
            width: 70%;
            height: 160px;
        }
    }

    .single-service-area:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
        border: 3px solid var(--gold);
    }

    .ser-vice-tit {
        margin-bottom: 12px;
        line-height: 1.3;
        word-break: break-word;
    }

    .ser-pra {
        margin-bottom: 14px;
        line-height: 1.7;
        word-break: break-word;
    }

    .service-price {
        margin-bottom: 0;
        line-height: 1.7;
        font-weight: 500;
        word-break: break-word;
    }

    @media (max-width: 991px) {
        .single-service-area {
            padding: 24px 20px;
        }
    }

    @media (max-width: 767px) {
        .breadcrumbs-area {
            padding-top: 70px;
            padding-bottom: 70px;
        }

        .port-filter-nav {
            gap: 8px;
        }

        .port-filter-nav li {
            width: auto;
            padding: 9px 14px;
            font-size: 14px;
        }

        .single-service-area {
            padding: 22px 18px;
        }

        .ser-vice-tit {
            font-size: 18px;
        }

        .ser-pra,
        .service-price {
            font-size: 14px;
        }
    }

    @media (max-width: 575px) {
        .services-filter-area {
            padding-left: 10px;
            padding-right: 10px;
        }

        .port-filter-nav {
            gap: 8px 6px;
        }

        .port-filter-nav li {
            padding: 8px 12px;
            font-size: 13px;
        }

        .single-service-area {
            padding: 20px 16px;
        }
    }
</style>

<section class="breadcrumbs-area ptb-100">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="breadcrumbs">
                    <h2 class="page-title">Services</h2>
                    <ul>
                        <li>
                            <a class="active" href="index.php">Home</a>
                        </li>
                        <li>Services</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="services-filter-area ptb-50">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <ul id="service-filters" class="port-filter-nav">
                    <li data-filter="*" class="is-checked">All</li>
                    <?php foreach ($categories as $category): ?>
                        <li data-filter=".<?= sanitize_category($category['category']) ?>">
                            <?= htmlspecialchars(ucfirst($category['category'])) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="hs-service-area" class="hs-service area ptb-90 bg-gray">
    <div class="container">
        <div class="row mb-n6 grid">
            <?php foreach ($services as $service): ?>
                <div class="col-lg-4 col-md-6 mb-6 pro-item <?= sanitize_category($service['category']) ?>">
                    <a href="appointment.php?service_id=<?= (int)$service['service_id'] ?>#appoinment-page" class="service-card-link">
                        <div class="single-service-area">
                            <?php if (!empty($service['image'])): ?>
                                <div class="service-image">
                                    <img src="images/services/<?= htmlspecialchars($service['image']) ?>" alt="<?= htmlspecialchars($service['name']) ?>">
                                </div>
                            <?php endif; ?>
                            <h4 class="ser-vice-tit"><?= htmlspecialchars($service['name']) ?></h4>
                            <p class="ser-pra"><?= htmlspecialchars($service['description']) ?></p>
                            <p class="service-price">
                                <strong> Standard Price: PKR <?= number_format((float)$service['price'], 2) ?></strong><br>
                                <strong> Member Price: PKR <?= number_format((float)$service['member_price'], 2) ?></strong>
                            </p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>