<?php
class User {
    private $db;

    public function __construct() {
        $this->db = connectDB();
    }

    public function login($email, $password) {
        $email = sanitizeInput($email);
        $password = md5(sanitizeInput($password));
        $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password' LIMIT 1";
        $result = $this->db->query($query);
        if ($result->num_rows === 1) {
            $_SESSION['user_id'] = $result->fetch_assoc()['id'];
            return true;
        }
        return false;
    }

    public function logout() {
        session_destroy();
    }
}
?>
