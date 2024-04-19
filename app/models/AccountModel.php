<?php
class AccountModel
{
    private $conn;
    private $table_name = "account";

    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function debug_to_console($data)
    {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);

        echo "<script>console.log('" . $output . "' );</script>";
    }

    public function login($username, $plainTextPassword)
    {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $isSuccess = password_verify($plainTextPassword, $row['password'] ?? ' ');
       return $row['role'] ?? null;
        
    }
    public function checkAccountByUsername($username)
    {
        $sql = "SELECT email FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_OBJ);
    
        return $row;
    }
    public function saveRegister($username, $name, $encryptedPassword, $role)
    {
        $sql = "INSERT INTO " . $this->table_name . "(email, name,password, role) VALUES (?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $name);
        $stmt->bindParam(3, $encryptedPassword);
        $stmt->bindParam(4, $role);
        $stmt->execute();

        return $stmt->rowCount();
    }
    public function logout(){
        session_destroy();
    }

    function getIdUser($username)
    {

        $stmt = $this->conn->prepare('SELECT id FROM account WHERE email = ?');
        $stmt->execute([$username]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $row['id'];
        } else {
            return null;
        }
    }
    function readAll()
    {
        $query = "SELECT id, email, name, role FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    function get_user_info($user_id)
    {
        $stmt = $this->conn->prepare('SELECT * FROM ' . $this->table_name.' WHERE id = :id');
        $stmt->execute(array('id' => $user_id));
        $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

        return $userInfo;
    
    }

    function updateUser($id, $name, $email, $role)
    {
     
            $query = "UPDATE " . $this->table_name . " SET role=:role, email=:email, name=:name WHERE id=:id";
      
        $stmt = $this->conn->prepare($query);
        // Làm sạch dữ liệu
        $role = htmlspecialchars(strip_tags($role));
        $email = htmlspecialchars(strip_tags($email));
        $name = htmlspecialchars(strip_tags($name));
      
        // Gán dữ liệu vào câu lệnh
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':name', $name);
    
        // Thực thi câu lệnh
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteUserById($id)
    {
        $query = "DELETE FROM " . $this->table_name . " where id = $id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }
}
