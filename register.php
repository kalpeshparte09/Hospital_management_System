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
    $doctor_name = $_POST['doctor_name'];
    $specialization_id = $_POST['specialization_id'];
    $doctor_contact = $_POST['doctor_contact'];
    $experience = $_POST['experience'];
    $department_id = $_POST['department_id'];

    // Validate specialization and department existence
    $checkSpecialization = $conn->prepare("SELECT * FROM Specializations WHERE Specialization_ID = ?");
    $checkSpecialization->bind_param("i", $specialization_id);
    $checkSpecialization->execute();
    $specializationResult = $checkSpecialization->get_result();

    $checkDepartment = $conn->prepare("SELECT * FROM Departments WHERE Department_ID = ?");
    $checkDepartment->bind_param("i", $department_id);
    $checkDepartment->execute();
    $departmentResult = $checkDepartment->get_result();

    if ($specializationResult->num_rows == 0) {
        echo "Specialization ID not found.";
    } elseif ($departmentResult->num_rows == 0) {
        echo "Department ID not found.";
    } else {
        // Insert new doctor
        $stmt = $conn->prepare("INSERT INTO Doctors (Doctor_Name, Specialization_ID, Doctor_Contact, Experience, Department_ID) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sissi", $doctor_name, $specialization_id, $doctor_contact, $experience, $department_id);

        if ($stmt->execute()) {
            echo "Doctor registered successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

$conn->close();
?>
