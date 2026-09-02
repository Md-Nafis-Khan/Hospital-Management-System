<?php

require_once __DIR__ . "/../core/Model.php";

class User extends Model
{
    public function findByEmail($email)
    {
        $query = "SELECT * FROM users WHERE email = ?";
        $statement = $this->db->prepare($query);
        $statement->execute(array($email));
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $email, $password, $role)
    {
        $query = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $statement = $this->db->prepare($query);
        return $statement->execute(array($name, $email, password_hash($password, PASSWORD_DEFAULT), $role));
    }

    public function getDoctors()
    {
        $statement = $this->db->query("SELECT id, name, email FROM users WHERE role = 'doctor' ORDER BY name");
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $statement = $this->db->query("SELECT id, name, email, role, created_at FROM users ORDER BY id DESC");
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $name, $email, $role)
    {
        $query = "UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?";
        $statement = $this->db->prepare($query);
        return $statement->execute(array($name, $email, $role, $id));
    }

    public function delete($id)
    {
        $statement = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $statement->execute(array($id));
    }
}