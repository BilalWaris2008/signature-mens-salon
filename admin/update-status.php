<?php
include '../dbconnect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = isset($_POST['appointment_id']) ? (int) $_POST['appointment_id'] : 0;
    $status = trim($_POST['status'] ?? '');

    if ($appointment_id <= 0 || $status === '') {
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        exit;
    }

    try {
        // Update appointment status
        $stmt = $pdo->prepare("
            UPDATE appointments 
            SET status = :status 
            WHERE appointment_id = :appointment_id
        ");
        $stmt->execute([
            'appointment_id' => $appointment_id,
            'status' => $status
        ]);

        // Check whether appointment exists and fetch details
        $stmt_appointment = $pdo->prepare("
            SELECT a.appointment_id, a.appointment_date, a.appointment_time, s.name AS service_name, 
                   CONCAT(u.first_name, ' ', u.last_name) AS customer_name, u.email
            FROM appointments a
            JOIN services s ON a.service_id = s.service_id
            JOIN users u ON a.user_id = u.user_id
            WHERE a.appointment_id = :appointment_id
            LIMIT 1
        ");
        $stmt_appointment->execute(['appointment_id' => $appointment_id]);
        $appointment = $stmt_appointment->fetch(PDO::FETCH_ASSOC);

        if (!$appointment) {
            echo json_encode(['success' => false, 'message' => 'Appointment not found.']);
            exit;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Appointment status updated successfully.'
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Unable to update appointment status.'
        ]);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
exit;
