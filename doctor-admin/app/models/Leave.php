<?php

require_once __DIR__ . "/../core/Model.php";

class Leave extends Model
{
    public function create($doctorId, $startDate, $endDate, $reason)
    {
        $statement = $this->db->prepare("INSERT INTO leaves (doctor_id, start_date, end_date, reason) VALUES (?, ?, ?, ?)");
        return $statement->execute(array($doctorId, $startDate, $endDate, $reason));
    }

    public function getForDoctor($doctorId)
    {
        $statement = $this->db->prepare("SELECT * FROM leaves WHERE doctor_id = ? ORDER BY id DESC");
        $statement->execute(array($doctorId));
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $query = "SELECT leaves.*, users.name AS doctor_name FROM leaves JOIN users ON leaves.doctor_id = users.id ORDER BY leaves.id DESC";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function review($id, $status)
    {
        $statement = $this->db->prepare("UPDATE leaves SET status = ? WHERE id = ?");
        return $statement->execute(array($status, $id));
    }

    public function countPending()
    {
        return $this->db->query("SELECT COUNT(*) FROM leaves WHERE status = 'pending'")->fetchColumn();
    }

    public function update($id, $doctorId, $startDate, $endDate, $reason)
    {
        $query = "UPDATE leaves SET start_date = ?, end_date = ?, reason = ? WHERE id = ? AND doctor_id = ? AND status = 'pending'";
        $statement = $this->db->prepare($query);
        return $statement->execute(array($startDate, $endDate, $reason, $id, $doctorId));
    }

    public function delete($id, $doctorId)
    {
        $query = "DELETE FROM leaves WHERE id = ? AND doctor_id = ? AND status = 'pending'";
        $statement = $this->db->prepare($query);
        return $statement->execute(array($id, $doctorId));
    }
}