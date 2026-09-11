CREATE DATABASE IF NOT EXISTS hs;
USE hs;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'doctor', 'patient') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status ENUM('pending', 'approved', 'completed', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES users(id),
    FOREIGN KEY (doctor_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS leaves (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (doctor_id) REFERENCES users(id)
);

INSERT INTO users (username, name, email, password, role) VALUES
('admin', 'Admin User', 'admin@admin.com', '$2y$10$g7DSaBhmJKZssAPkyg/3N.wiNB/w2QtABSkcJFtmi2iG7F3GKuvhK', 'admin'),
('doctor', 'Doctor User', 'doctor@doctor.com', '$2y$10$pcglj5X48LWW7acqaas6E.NruRknzSUcV5BbqEErUCCLeS/6Z8IIC', 'doctor'),
('patient', 'Patient User', 'patient@patient.com', '$2y$10$yoMHfFkwSQNbkuCEMNJOL.iTr.GE8NApDz6nHqdaqzlgDeTou5sl.', 'patient')
ON DUPLICATE KEY UPDATE
name = VALUES(name), password = VALUES(password), role = VALUES(role);

-- admin123, doctor123, patient123
