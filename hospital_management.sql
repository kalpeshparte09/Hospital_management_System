create database hospital_management;
use hospital_management;

-- Create Patients Table
CREATE TABLE Patients (
    Patient_ID INT PRIMARY KEY,
    Patient_Name VARCHAR(100),
    DOB DATE,
    Contact BIGINT,
    Address VARCHAR(255),
    Gender VARCHAR(10),
    Age INT,
    Blood_Group VARCHAR(5),
    Phone_Number BIGINT
);

-- Create Doctors Table
CREATE TABLE Doctors (
    Doctor_ID INT PRIMARY KEY,
    Doctor_Name VARCHAR(100),
    Specialization_ID INT,
    Doctor_Contact BIGINT,
    Experience VARCHAR(50),
    Department_ID INT,
    FOREIGN KEY (Specialization_ID) REFERENCES Specializations(Specialization_ID),
    FOREIGN KEY (Department_ID) REFERENCES Departments(Department_ID)
);

-- Create Specializations Table
CREATE TABLE Specializations (
    Specialization_ID INT PRIMARY KEY,
    Specialization_Name VARCHAR(100),
    Department_ID INT,
    FOREIGN KEY (Department_ID) REFERENCES Departments(Department_ID)
);

-- Create Departments Table
CREATE TABLE Departments (
    Department_ID INT PRIMARY KEY,
    Department_Name VARCHAR(100)
);

-- Create Appointments Table
CREATE TABLE Appointments (
    Appointment_ID INT PRIMARY KEY,
    Patient_ID INT,
    Doctor_ID INT,
    Appointment_Date DATE,
    Reason VARCHAR(255),
    Time_Slot VARCHAR(50),
    FOREIGN KEY (Patient_ID) REFERENCES Patients(Patient_ID),
    FOREIGN KEY (Doctor_ID) REFERENCES Doctors(Doctor_ID)
);

-- Create Prescriptions Table
CREATE TABLE Prescriptions (
    Medication_ID INT PRIMARY KEY auto_increment,
    Appointment_ID INT,
    Medicine VARCHAR(100),
    Dosage VARCHAR(50),
    Type VARCHAR(50),
    Duration_Days INT,
    Instructions VARCHAR(255),
    Manufacturer VARCHAR(100),
    Price DECIMAL(10, 2),
    FOREIGN KEY (Appointment_ID) REFERENCES Appointments(Appointment_ID)
);

-- Create Bills Table
CREATE TABLE Bills (
    Bill_ID INT PRIMARY KEY,
    Appointment_ID INT,
    Total_Amount DECIMAL(10, 2),
    Payment_Method VARCHAR(50),
    Paid_On DATE,
    Payment_Status_ID INT,
    FOREIGN KEY (Appointment_ID) REFERENCES Appointments(Appointment_ID),
    FOREIGN KEY (Payment_Status_ID) REFERENCES Payment_Status(Payment_Status_ID)
);

-- Create Payment Status Table
CREATE TABLE Payment_Status (
    Payment_Status_ID INT PRIMARY KEY,
    Payment_Status VARCHAR(50)
);

-- Create Medical Record Table
CREATE TABLE Medical_Record (
    Record_ID INT PRIMARY KEY,
    Patient_ID INT,
    Visit_Date DATE,
    Diagnosis VARCHAR(255),
    Prescribed_Medications VARCHAR(255),
    Notes VARCHAR(255),
    FOREIGN KEY (Patient_ID) REFERENCES Patients(Patient_ID)
);
select * from patients;

ALTER TABLE Patients MODIFY Patient_ID INT AUTO_INCREMENT;
ALTER TABLE appointments MODIFY Appointment_ID INT AUTO_INCREMENT;
ALTER TABLE departments MODIFY Department_ID INT AUTO_INCREMENT;
ALTER TABLE bills MODIFY Bill_ID INT AUTO_INCREMENT;
ALTER TABLE medical_record MODIFY Record_ID INT AUTO_INCREMENT;
ALTER TABLE specializations MODIFY Specialization_ID INT AUTO_INCREMENT;
ALTER TABLE doctors MODIFY Doctor_ID INT AUTO_INCREMENT;
INSERT INTO Departments (Department_Name)
VALUES
('Cardiology'),
('Neurology'),
('Pediatrics'),
('Oncology');
INSERT INTO Specializations (Specialization_Name, Department_ID)
VALUES
('Cardiologist', 1),
('Neurologist', 2),
('Pediatrician', 3),
('Oncologist', 4);

INSERT INTO Doctors (Doctor_Name, Specialization_ID, Doctor_Contact, Experience, Department_ID)
VALUES
('Dr. John Smith', 1, 1234567890, '10 years', 1),
('Dr. Emily Brown', 2, 9876543210, '8 years', 2),
('Dr. Sarah Taylor', 3, 4567891230, '5 years', 3),
('Dr. Michael White', 4, 7891234560, '12 years', 4);

INSERT INTO Appointments (Patient_ID, Doctor_ID, Appointment_Date, Reason, Time_Slot)
VALUES
(1, 1, '2024-12-15', 'Routine check-up', '10:00 AM - 10:30 AM'),
(2, 2, '2024-12-16', 'Migraine consultation', '11:00 AM - 11:30 AM'),
(3, 3, '2024-12-17', 'Pediatric care', '2:00 PM - 2:30 PM');

INSERT INTO Patients (Patient_Name, DOB, Contact, Address, Gender, Age, Blood_Group, Phone_Number)
VALUES
('Alice Johnson', '1980-05-15', 1112223334, '123 Main St', 'Female', 43, 'A+', 9876543210),
('Bob Davis', '1992-11-22', 5556667778, '456 Elm St', 'Male', 31, 'B-', 1234567890),
('Charlie Brown', '2005-02-10', 8889990001, '789 Pine St', 'Male', 19, 'O+', 6543210987);

select * from appointments;

CREATE TABLE Payment_Status (
    Payment_Status_ID INT PRIMARY KEY AUTO_INCREMENT,
    Payment_Status VARCHAR(50) NOT NULL
);

-- Insert initial data
INSERT INTO Payment_Status (Payment_Status_ID, Payment_Status)
VALUES
(1, 'Paid'),
(2, 'Pending');
select * from appointments;

