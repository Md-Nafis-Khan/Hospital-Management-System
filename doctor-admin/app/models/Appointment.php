<?php

require_once __DIR__ . "/../core/Model.php";

class Appointment extends Model
{
    public function create($patientId, $doctorId, $date, $time, $notes)
    {
        $query = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, notes) VALUES (?, ?, ?, ?, ?)";
        $statement = $this->db->prepare($query);
        return $statement->execute(array($patientId, $doctorId, $date, $time, $notes));
    }

    public function getForPatient($patientId)
    {
        $query = "SELECT appointments.*, users.name AS doctor_name FROM appointments JOIN users ON appointments.doctor_id = users.id WHERE appointments.patient_id = ? ORDER BY appointment_date DESC, appointment_time DESC";
        $statement = $this->db->prepare($query);
        $statement->execute(array($patientId));
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getForDoctor($doctorId)
    {
        $query = "SELECT appointments.*, users.name AS patient_name, users.email AS patient_email FROM appointments JOIN users ON appointments.patient_id = users.id WHERE appointments.doctor_id = ? ORDER BY appointment_date DESC, appointment_time DESC";
        $statement = $this->db->prepare($query);
        $statement->execute(array($doctorId));
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $doctorId, $status)
    {
        $query = "UPDATE appointments SET status = ? WHERE id = ? AND doctor_id = ?";
        $statement = $this->db->prepare($query);
        return $statement->execute(array($status, $id, $doctorId));
    }

    public function countAll()
    {
        return $this->db->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
    }

    public function updateByPatient($id, $patientId, $date, $time, $notes)
    {
        $query = "UPDATE appointments SET appointment_date = ?, appointment_time = ?, notes = ? WHERE id = ? AND patient_id = ? AND status = 'pending'";
        $statement = $this->db->prepare($query);
        return $statement->execute(array($date, $time, $notes, $id, $patientId));
    }

    public function deleteByPatient($id, $patientId)
    {
        $query = "DELETE FROM appointments WHERE id = ? AND patient_id = ? AND status = 'pending'";
        $statement = $this->db->prepare($query);
        return $statement->execute(array($id, $patientId));
    }
}