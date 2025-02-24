<?php
require('fpdf184/fpdf.php'); // Include the FPDF library

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital_management";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $appointment_id = $_POST['appointment_id'];
    $total_amount = $_POST['total_amount'];
    $payment_method = $_POST['payment_method'];
    $paid_on = $_POST['paid_on'];
    $payment_status_id = $_POST['payment_status_id'];

    // Validate appointment existence
    $checkAppointment = $conn->prepare("SELECT * FROM Appointments WHERE Appointment_ID = ?");
    $checkAppointment->bind_param("i", $appointment_id);
    $checkAppointment->execute();
    $appointmentResult = $checkAppointment->get_result();

    if ($appointmentResult->num_rows == 0) {
        die("Error: Appointment ID not found.");
    }

    // Validate payment status existence
    $checkPaymentStatus = $conn->prepare("SELECT * FROM Payment_Status WHERE Payment_Status_ID = ?");
    $checkPaymentStatus->bind_param("i", $payment_status_id);
    $checkPaymentStatus->execute();
    $paymentStatusResult = $checkPaymentStatus->get_result();

    if ($paymentStatusResult->num_rows == 0) {
        die("Error: Invalid Payment Status ID.");
    }

    // Insert new bill
    $stmt = $conn->prepare("INSERT INTO Bills (Appointment_ID, Total_Amount, Payment_Method, Paid_On, Payment_Status_ID) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("idssi", $appointment_id, $total_amount, $payment_method, $paid_on, $payment_status_id);

    if ($stmt->execute()) {
        $bill_id = $conn->insert_id; // Get the generated Bill ID

        // Generate PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Header
        $pdf->Cell(0, 10, 'Billing Receipt', 0, 1, 'C');
        $pdf->Ln(10);

        // Billing Details
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'Bill ID:', 0, 0);
        $pdf->Cell(0, 10, $bill_id, 0, 1);

        $pdf->Cell(50, 10, 'Appointment ID:', 0, 0);
        $pdf->Cell(0, 10, $appointment_id, 0, 1);

        $pdf->Cell(50, 10, 'Total Amount:', 0, 0);
        $pdf->Cell(0, 10, '$' . number_format($total_amount, 2), 0, 1);

        $pdf->Cell(50, 10, 'Payment Method:', 0, 0);
        $pdf->Cell(0, 10, $payment_method, 0, 1);

        $pdf->Cell(50, 10, 'Paid On:', 0, 0);
        $pdf->Cell(0, 10, $paid_on, 0, 1);

        $pdf->Cell(50, 10, 'Payment Status:', 0, 0);
        $pdf->Cell(0, 10, $payment_status_id == 1 ? 'Paid' : 'Pending', 0, 1);

        // Output PDF
        $pdf->Output('D', 'Bill_' . $bill_id . '.pdf'); // Prompt download with file name
    } else {
        echo "Error: " . $stmt->error;
    }
}

$conn->close();
?>
