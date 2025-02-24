<?php
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
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $reason = $_POST['reason'];
    $time_slot = $_POST['time_slot'];

    // Validate patient and doctor existence
    $checkPatient = $conn->prepare("SELECT * FROM Patients WHERE Patient_ID = ?");
    $checkPatient->bind_param("i", $patient_id);
    $checkPatient->execute();
    $patientResult = $checkPatient->get_result();

    $checkDoctor = $conn->prepare("SELECT * FROM Doctors WHERE Doctor_ID = ?");
    $checkDoctor->bind_param("i", $doctor_id);
    $checkDoctor->execute();
    $doctorResult = $checkDoctor->get_result();

    if ($patientResult->num_rows == 0) {
        echo "Patient ID not found.";
    } elseif ($doctorResult->num_rows == 0) {
        echo "Doctor ID not found.";
    } else {
        // Insert appointment data
        $stmt = $conn->prepare("INSERT INTO Appointments (Patient_ID, Doctor_ID, Appointment_Date, Reason, Time_Slot) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $patient_id, $doctor_id, $appointment_date, $reason, $time_slot);

        if ($stmt->execute()) {
            echo "Appointment booked successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

$conn->close();
?>
