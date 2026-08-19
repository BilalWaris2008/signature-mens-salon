<?php
include 'header.php';
include 'dbconnect.php';

$selected_service_id = isset($_GET['service_id']) ? (int) $_GET['service_id'] : 0;

// Start session and check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script type='text/javascript'>
        window.location.href = 'login.php';
    </script>";
    exit;
}

// Fetch the logged-in user's data
$user_id = $_SESSION['user_id'];
$stmt_user = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt_user->execute(['user_id' => $user_id]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

$message = '';
$message_type = '';


// Email send

function sendEmail($user_name, $user_email, $appointment_date, $appointment_time, $service_name)
{
    $api_key = 'add your api key';

    $sender_email = 'add email';

    $sender_name = 'sender name';

    $formatted_date = date('d M Y', strtotime($appointment_date));

    $formatted_time = date('h:i A', strtotime($appointment_time));

    // Email subject
    $subject = 'Appointment Confirmation';

    // Email content
    $html_content = "
        <html>
        <body style='font-family: Arial, sans-serif;'>
            
            <h2>Appointment Confirmation</h2>

            <p>
                Thank you <strong>" . htmlspecialchars($user_name) . "</strong>
                for booking your appointment.
            </p>

            <p>
                Your appointment has been successfully scheduled.
            </p>

            <p>
                <strong>Appointment Details:</strong>
            </p>

            <p>
                Date: <strong>" . htmlspecialchars($formatted_date) . "</strong><br>
                Time: <strong>" . htmlspecialchars($formatted_time) . "</strong><br>
                Service: <strong>" . htmlspecialchars($service_name) . "</strong>
            </p>

            <p>
                We look forward to seeing you.
            </p>

            <p>
                Thank you,<br>
                <strong>" . htmlspecialchars($sender_name) . "</strong>
            </p>

        </body>
        </html>
    ";


    // Data that will be sent to Brevo
    $data = [
        'sender' => [
            'name' => $sender_name,
            'email' => $sender_email
        ],

        'to' => [
            [
                'email' => $user_email,
                'name' => $user_name
            ]
        ],

        'subject' => $subject,

        'htmlContent' => $html_content
    ];


    // Convert PHP array into JSON
    $json_data = json_encode($data);


    // Brevo API URL
    $url = 'https://api.brevo.com/v3/smtp/email';


    // Create cURL request
    $ch = curl_init($url);


    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);


    // Headers required by Brevo API
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'accept: application/json',
        'api-key: ' . $api_key,
        'content-type: application/json'
    ]);


    // Send request
    $response = curl_exec($ch);


    // Get HTTP status code
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);


    // Close cURL
    curl_close($ch);


    // Return true if Brevo accepted the email
    if ($http_code == 201) {
        return true;
    }

    return false;
}


// EXISTING STAFF FUNCTIONS

function assignRandomStaff($pdo, $appointment_date, $appointment_time, $service_duration)
{
    $stmt_random_staff = $pdo->prepare("
        SELECT user_id 
        FROM users 
        WHERE role = 'staff'
        AND user_id NOT IN (
            SELECT staff_id FROM appointments 
            WHERE appointment_date = :appointment_date 
            AND (
                :appointment_start_time BETWEEN appointment_time AND ADDTIME(appointment_time, SEC_TO_TIME(:service_duration * 60))
                OR 
                ADDTIME(:appointment_start_time, SEC_TO_TIME(:service_duration * 60)) BETWEEN appointment_time AND ADDTIME(appointment_time, SEC_TO_TIME(:service_duration * 60))
            )
        )
        ORDER BY RAND() LIMIT 1
    ");

    $stmt_random_staff->execute([
        'appointment_date' => $appointment_date,
        'appointment_start_time' => $appointment_time,
        'service_duration' => $service_duration
    ]);

    $staff = $stmt_random_staff->fetch(PDO::FETCH_ASSOC);

    return $staff ? $staff['user_id'] : null;
}


function isStaffAvailable($pdo, $staff_id, $appointment_date, $appointment_time, $service_duration)
{
    $stmt_check = $pdo->prepare("
        SELECT COUNT(*) as appointment_count 
        FROM appointments 
        WHERE staff_id = :staff_id 
        AND appointment_date = :appointment_date 
        AND (
            :appointment_start_time BETWEEN appointment_time AND ADDTIME(appointment_time, SEC_TO_TIME(:service_duration * 60))
            OR 
            ADDTIME(:appointment_start_time, SEC_TO_TIME(:service_duration * 60)) BETWEEN appointment_time AND ADDTIME(appointment_time, SEC_TO_TIME(:service_duration * 60))
        )
    ");

    $stmt_check->execute([
        'staff_id' => $staff_id,
        'appointment_date' => $appointment_date,
        'appointment_start_time' => $appointment_time,
        'service_duration' => $service_duration
    ]);

    $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

    return $result['appointment_count'] == 0;
}


// APPOINTMENT FORM SUBMISSION

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $service_id = $_POST['service'];

    $input_date = $_POST['date'];
    $date_object = DateTime::createFromFormat('d/m/Y', $input_date);

    if ($date_object === false) {

        $message = "Invalid date format. Please use dd/mm/yyyy.";
        $message_type = 'danger';
    } else {

        $appointment_date = $date_object->format('Y-m-d');
        $appointment_time = date('H:i:s', strtotime($_POST['time']));


        // Get service information
        $stmt_service = $pdo->prepare("
            SELECT name, duration 
            FROM services 
            WHERE service_id = :service_id
        ");

        $stmt_service->execute([
            'service_id' => $service_id
        ]);

        $service = $stmt_service->fetch(PDO::FETCH_ASSOC);


        if (!$service) {

            $message = "Invalid service selected.";
            $message_type = 'danger';
        } else {

            $service_duration = $service['duration'];
            $service_name = $service['name'];


            // Select staff
            $staff_id = isset($_POST['staff']) && !empty($_POST['staff'])
                ? $_POST['staff']
                : assignRandomStaff(
                    $pdo,
                    $appointment_date,
                    $appointment_time,
                    $service_duration
                );


            if (!$staff_id) {

                $message = "Sorry, no staff member is available for the selected time.";
                $message_type = 'danger';
            } elseif (!isStaffAvailable(
                $pdo,
                $staff_id,
                $appointment_date,
                $appointment_time,
                $service_duration
            )) {

                $message = "Sorry, the selected staff member is not available for this time slot.";
                $message_type = 'danger';
            } else {


                // INSERT APPOINTMENT

                $stmt = $pdo->prepare("
                    INSERT INTO appointments
                    (
                        user_id,
                        name,
                        email,
                        phone,
                        service_id,
                        staff_id,
                        appointment_date,
                        appointment_time
                    ) 
                    VALUES
                    (
                        :user_id,
                        :name,
                        :email,
                        :phone,
                        :service_id,
                        :staff_id,
                        :appointment_date,
                        :appointment_time
                    )
                ");


                $stmt->execute([
                    'user_id' => $user_id,
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'service_id' => $service_id,
                    'staff_id' => $staff_id,
                    'appointment_date' => $appointment_date,
                    'appointment_time' => $appointment_time
                ]);


                // SEND CONFIRMATION EMAIL

                sendEmail(
                    $name,
                    $email,
                    $appointment_date,
                    $appointment_time,
                    $service_name
                );


                // EXISTING SUCCESS MESSAGE

                $message = "Appointment successfully booked!";
                $message_type = 'success';
            }
        }
    }
}
?>


<section class="breadcrumbs-area ptb-100">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="breadcrumbs">
                    <h2 class="page-title" id="appoinment-page">Appointment</h2>
                    <ul>
                        <li><a class="active" href="index.php">Home</a></li>
                        <li>Appointment</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>


<section id="hs-appoinment-area" class="hs-appoinment-area bg-gray">
    <div class="container-fluid ps-0 pe-0">

        <div class="row g-0 align-items-center">

            <div class="col-lg-6">

                <div class="appoinment-thumb appoinment-thumb-st2">

                    <img
                        src="images/others/appoinment/1.jpg"
                        alt="appointment image">

                </div>

            </div>


            <div class="col-lg-6">

                <div class="appoinment-inner appoinment-inner-st2">

                    <div class="appoinment-title text-center">

                        <h2 class="section-title">
                            Book an Appointment
                        </h2>

                        <p class="section-details appoinment">
                            Schedule your beauty experience with Signature Men's Salon.
                            Fill in the details below and we will take care of the rest.
                        </p>

                    </div>


                    <?php if ($message): ?>

                        <br>

                        <div class="alert alert-<?= $message_type ?> text-center">
                            <?= $message ?>
                        </div>

                    <?php endif; ?>


                    <div class="appoinment-form mt-40">

                        <form action="" method="POST">

                            <div class="input-box">

                                <input
                                    type="text"
                                    name="name"
                                    value="<?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>"
                                    readonly
                                    required>

                                <input
                                    type="email"
                                    name="email"
                                    value="<?= htmlspecialchars($user['email']) ?>"
                                    readonly
                                    required>

                            </div>


                            <div class="input-box">

                                <input
                                    type="tel"
                                    name="phone"
                                    value="<?= htmlspecialchars($user['telephone']) ?>"
                                    readonly
                                    required>


                                <select name="service" required>

                                    <option
                                        disabled
                                        <?= $selected_service_id === 0 ? 'selected' : '' ?>>
                                        Choose Service
                                    </option>

                                    <?php

                                    $stmt_services = $pdo->prepare("
                                        SELECT service_id, name
                                        FROM services
                                    ");

                                    $stmt_services->execute();

                                    $services = $stmt_services->fetchAll(PDO::FETCH_ASSOC);

                                    foreach ($services as $service):

                                    ?>

                                        <option
                                            value="<?= $service['service_id'] ?>"
                                            <?= $selected_service_id === (int)$service['service_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($service['name']) ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>


                            <div class="input-box">

                                <input
                                    type="text"
                                    id="datepicker"
                                    name="date"
                                    placeholder="Preferred Date"
                                    required>


                                <select
                                    name="time"
                                    id="time-select"
                                    required>

                                    <option disabled selected>
                                        Choose Time
                                    </option>

                                    <option value="09:00:00">09:00 AM</option>
                                    <option value="09:30:00">09:30 AM</option>
                                    <option value="10:00:00">10:00 AM</option>
                                    <option value="10:30:00">10:30 AM</option>
                                    <option value="11:00:00">11:00 AM</option>
                                    <option value="11:30:00">11:30 AM</option>
                                    <option value="12:00:00">12:00 PM</option>
                                    <option value="12:30:00">12:30 PM</option>
                                    <option value="13:00:00">01:00 PM</option>
                                    <option value="13:30:00">01:30 PM</option>
                                    <option value="14:00:00">02:00 PM</option>
                                    <option value="14:30:00">02:30 PM</option>
                                    <option value="15:00:00">03:00 PM</option>
                                    <option value="15:30:00">03:30 PM</option>
                                    <option value="16:00:00">04:00 PM</option>
                                    <option value="16:30:00">04:30 PM</option>
                                    <option value="17:00:00">05:00 PM</option>
                                    <option value="17:30:00">05:30 PM</option>
                                    <option value="18:00:00">06:00 PM</option>
                                    <option value="18:30:00">06:30 PM</option>
                                    <option value="19:00:00">07:00 PM</option>
                                    <option value="19:30:00">07:30 PM</option>
                                    <option value="20:00:00">08:00 PM</option>

                                </select>

                            </div>


                            <div
                                style="
                                    border: 1px solid transparent;
                                    color: #000000;
                                    font-family: Lato;
                                    font-size: 14px;
                                    font-weight: 400;
                                    padding-left: 0px;
                                    line-height: 45px;
                                    margin-top: 18px;
                                ">

                                <select
                                    name="staff"
                                    style="
                                        border: 1px solid #000000;
                                        width: 100%;
                                        height: 45px;
                                        padding-left: 20px;
                                    ">

                                    <option value="">
                                        Select Staff Member (Optional)
                                    </option>

                                    <?php

                                    $stmt_staff = $pdo->prepare("
                                        SELECT
                                            user_id,
                                            CONCAT(first_name, ' ', last_name) as staff_name
                                        FROM users
                                        WHERE role = 'staff'
                                    ");

                                    $stmt_staff->execute();

                                    $staff_members = $stmt_staff->fetchAll(PDO::FETCH_ASSOC);

                                    foreach ($staff_members as $staff):

                                    ?>

                                        <option value="<?= $staff['user_id'] ?>">
                                            <?= htmlspecialchars($staff['staff_name']) ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>


                            <div class="book-appoin-btn mt-30">

                                <button
                                    type="submit"
                                    class="hs-btn hs-btn-2">
                                    Book Now
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>


<?php include 'footer.php'; ?>