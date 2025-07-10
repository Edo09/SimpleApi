<?php
class userModel
{
    public $conexion;
    public function __construct()
    {
        $this->conexion = new mysqli('localhost', 'root', '', 'test');
        mysqli_set_charset($this->conexion, 'utf8');
    }

    public function getUsers($id = null)
    {
        $where = ($id == null) ? "" : " WHERE id='$id'";
        $users = [];
        $sql = "SELECT * FROM users " . $where;
        $registos = mysqli_query($this->conexion, $sql);
        while ($row = mysqli_fetch_assoc($registos)) {
            array_push($users, $row);
        }
        return $users;
    }

    public function saveUser($name, $lastname, $email)
    {
        $valida = $this->validateUsers($name, $lastname, $email);
        $resultado = ['error', 'This user already exists'];
        if (count($valida) == 0) {
            $sql = "INSERT INTO users(name,last_name,email) VALUES('$name','$lastname','$email')";
            mysqli_query($this->conexion, $sql);
            $resultado = ['success', 'User saved'];
        }
        return $resultado;
    }

    public function updateUser($id, $name, $lastname, $email)
    {
        $existe = $this->getUsers($id);
        $resultado = ['error', "There is no user with ID {$id}"];
        if (count($existe) > 0) {
            $valida = $this->validateUsers($name, $lastname, $email);
            $resultado = ['error', 'This user already exists'];
            if (count($valida) == 0) {
                $sql = "UPDATE users SET name='$name',last_name='$lastname',email='$email' WHERE id='$id' ";
                mysqli_query($this->conexion, $sql);
                $resultado = ['success', 'User updated'];
            }
        }
        return $resultado;
    }

    public function deleteUser($id)
    {
        $valida = $this->getUsers($id);
        $resultado = ['error', "User not found {$id}"];
        if (count($valida) > 0) {
            $sql = "DELETE FROM users WHERE id='$id' ";
            mysqli_query($this->conexion, $sql);
            $resultado = ['success', 'User deleted'];
        }
        return $resultado;
    }

    public function validateUsers($name, $lastname, $email)
    {
        $users = [];
        $sql = "SELECT * FROM users WHERE name='$name' AND last_name='$lastname' AND email='$email' ";
        $registros = mysqli_query($this->conexion, $sql);
        while ($row = mysqli_fetch_assoc($registros)) {
            array_push($users, $row);
        }
        return $users;
    }
}
