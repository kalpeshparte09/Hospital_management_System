<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $blood_group = $_POST['blood_group'];
    $phone_number = $_POST['phone_number'];

    // Check if the patient already exists
    $sql = "SELECT * FROM Patients WHERE Contact = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $contact);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Patient already exists.";
    } else {
        // Insert new patient
        $sql = "INSERT INTO Patients (Patient_Name, DOB, Contact, Address, Gender, Age, Blood_Group, Phone_Number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisssis", $name, $dob, $contact, $address, $gender, $age, $blood_group, $phone_number);
        if ($stmt->execute()) {
            echo "Patient registered successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>
