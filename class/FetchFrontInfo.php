<?php
include_once __DIR__ . '/../configs/config.php';


class FetchFrontInfo
{

    private $conn;

    public function __construct()
    {
        $db = DatabaseConnection::getInstance();
        $this->conn = $db->getConnection();
    }

    public function fetchFrontInfo()
    {
        try {
            $sql = "SELECT `app_name`, `title`, `front_title`, `favicon`, `app_logo` FROM `system_settings` WHERE 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $systemInfo = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($systemInfo) {
                return $systemInfo;
            } else {
                return "No data found.";
            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
