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
    $medicine = $_POST['medicine'];
    $dosage = $_POST['dosage'];
    $type = $_POST['type'];
    $duration_days = $_POST['duration_days'];
    $instructions = $_POST['instructions'];
    $manufacturer = $_POST['manufacturer'];
    $price = $_POST['price'];

    // Validate appointment existence
    $checkAppointment = $conn->prepare("SELECT * FROM Appointments WHERE Appointment_ID = ?");
    $checkAppointment->bind_param("i", $appointment_id);
    $checkAppointment->execute();
    $appointmentResult = $checkAppointment->get_result();

    if ($appointmentResult->num_rows == 0) {
        echo "Appointment ID not found.";
    } else {
        // Insert new prescription
        $stmt = $conn->prepare("INSERT INTO Prescriptions (Appointment_ID, Medicine, Dosage, Type, Duration_Days, Instructions, Manufacturer, Price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssisds", $appointment_id, $medicine, $dosage, $type, $duration_days, $instructions, $manufacturer, $price);

        if ($stmt->execute()) {
            // Prescription successfully created, now generate PDF
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);

            // Header
            $pdf->Cell(0, 10, 'Prescription Details', 0, 1, 'C');
            $pdf->Ln(10);

            // Content
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(50, 10, 'Appointment ID:', 0, 0);
            $pdf->Cell(0, 10, $appointment_id, 0, 1);

            $pdf->Cell(50, 10, 'Medicine:', 0, 0);
            $pdf->Cell(0, 10, $medicine, 0, 1);

            $pdf->Cell(50, 10, 'Dosage:', 0, 0);
            $pdf->Cell(0, 10, $dosage, 0, 1);

            $pdf->Cell(50, 10, 'Type:', 0, 0);
            $pdf->Cell(0, 10, $type, 0, 1);

            $pdf->Cell(50, 10, 'Duration (Days):', 0, 0);
            $pdf->Cell(0, 10, $duration_days, 0, 1);

            $pdf->Cell(50, 10, 'Instructions:', 0, 0);
            $pdf->Cell(0, 10, $instructions, 0, 1);

            $pdf->Cell(50, 10, 'Manufacturer:', 0, 0);
            $pdf->Cell(0, 10, $manufacturer, 0, 1);

            $pdf->Cell(50, 10, 'Price:', 0, 0);
            $pdf->Cell(0, 10, '$' . $price, 0, 1);

            $pdf->Output('D', 'Prescription_' . $appointment_id . '.pdf'); // Output the PDF as a download
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

$conn->close();
?>

