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

// Fetch billing reports
$sql = "SELECT 
            Bills.Bill_ID, 
            Appointments.Appointment_ID, 
            Patients.Patient_Name, 
            Bills.Total_Amount, 
            Bills.Payment_Method, 
            Bills.Paid_On, 
            Payment_Status.Payment_Status
        FROM Bills
        LEFT JOIN Appointments ON Bills.Appointment_ID = Appointments.Appointment_ID
        LEFT JOIN Patients ON Appointments.Patient_ID = Patients.Patient_ID
        LEFT JOIN Payment_Status ON Bills.Payment_Status_ID = Payment_Status.Payment_Status_ID
        ORDER BY Bills.Bill_ID";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Reports</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Billing Reports</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="patients.html">Patients</a></li>
                <li><a href="appointment.html">Appointments</a></li>
                <li><a href="reports.html">Reports</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Billing Information</h2>
        <?php if ($result->num_rows > 0): ?>
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>Bill ID</th>
                        <th>Appointment ID</th>
                        <th>Patient Name</th>
                        <th>Total Amount</th>
                        <th>Payment Method</th>
                        <th>Paid On</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['Bill_ID']; ?></td>
                            <td><?php echo $row['Appointment_ID']; ?></td>
                            <td><?php echo $row['Patient_Name']; ?></td>
                            <td><?php echo '$' . number_format($row['Total_Amount'], 2); ?></td>
                            <td><?php echo $row['Payment_Method']; ?></td>
                            <td><?php echo $row['Paid_On'] ? $row['Paid_On'] : 'N/A'; ?></td>
                            <td><?php echo $row['Payment_Status']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No billing records found.</p>
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
