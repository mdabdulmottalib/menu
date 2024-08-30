<?php
class Menu {
    private $db;

    public function __construct() {
        $this->db = connectDB();
    }

    public function saveMenu($week, $content, $imagePath, $pdfPath) {
        $week = sanitizeInput($week);
        $content = sanitizeInput($content);
        $stmt = $this->db->prepare("INSERT INTO weekly_menus (week, content, created_date, image_path, pdf_path) VALUES (?, ?, NOW(), ?, ?)");
        $stmt->bind_param('ssss', $week, $content, $imagePath, $pdfPath);
        return $stmt->execute();
    }

    public function getAllMenus() {
        $result = $this->db->query("SELECT * FROM weekly_menus WHERE deleted = 0 ORDER BY created_date DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
