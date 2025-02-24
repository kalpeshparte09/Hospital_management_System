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

// Fetch patient reports
$sql = "SELECT 
            Patients.Patient_ID, 
            Patients.Patient_Name, 
            Patients.Age, 
            Patients.Gender, 
            Medical_Record.Visit_Date, 
            Medical_Record.Diagnosis, 
            Medical_Record.Prescribed_Medications, 
            Medical_Record.Notes
        FROM Patients
        LEFT JOIN Medical_Record ON Patients.Patient_ID = Medical_Record.Patient_ID
        ORDER BY Patients.Patient_ID, Medical_Record.Visit_Date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Reports</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Patient Reports</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="patients.html">Patients</a></li>
                <li><a href="appointments.html">Appointments</a></li>
                <li><a href="reports.html">Reports</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Patient Medical Records</h2>
        <?php if ($result->num_rows > 0): ?>
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>Patient Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Visit Date</th>
                        <th>Diagnosis</th>
                        <th>Prescribed Medications</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['Patient_ID']; ?></td>
                            <td><?php echo $row['Patient_Name']; ?></td>
                            <td><?php echo $row['Age']; ?></td>
                            <td><?php echo $row['Gender']; ?></td>
                            <td><?php echo $row['Visit_Date'] ? $row['Visit_Date'] : 'N/A'; ?></td>
                            <td><?php echo $row['Diagnosis'] ? $row['Diagnosis'] : 'N/A'; ?></td>
                            <td><?php echo $row['Prescribed_Medications'] ? $row['Prescribed_Medications'] : 'N/A'; ?></td>
                            <td><?php echo $row['Notes'] ? $row['Notes'] : 'N/A'; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No patient reports found.</p>
        <?php endif; ?>
    </main>
    <footer>
        <p>&copy; 2024 Hope Hospital Management. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
