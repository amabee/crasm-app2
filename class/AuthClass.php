<?php
include_once __DIR__ . '/../configs/config.php';
class Auth
{
    public function login($username, $password)
    {

        $db = DatabaseConnection::getInstance();
        $conn = $db->getConnection();

        // Prepare SQL query
        $query = "SELECT `id`, `username`, `password`, `email`, `first_name`, `middle_name`, `last_name`, `role_id`, `status`, `created_at`, `updated_at` FROM `users` WHERE `username` = :username";

        // Execute the query
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['middle_name'] = $user['middle_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['role_id'] = $user['role_id'];
                $_SESSION['status'] = $user['status'];
                $_SESSION['created_at'] = $user['created_at'];

                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    // Check if the user is logged in
    public function checkSession()
    {
        if (isset($_SESSION['user_id'])) {
            return true;
        }

        return false;
    }

    // Logout method
    public function logout()
    {
        // Destroy all session data
        session_unset();
        session_destroy();
    }

    // Check if the user has a specific role
    public function checkRole($required_role)
    {
        if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == $required_role) {
            return true;
        }
        return false;
    }

    // Redirect user based on role
    public function redirectToRolePage()
    {
        $role_id = $_SESSION['role_id'] ?? null;

        switch ($role_id) {
            case 1: // Super Admin
                header("Location: /crasm-app/super_admin/dashboard.php");
                break;
            case 2: // Admin
                header("Location: /crasm-app/admin/dashboard.php");
                break;
            case 3: // RD
                header("Location: /crasm-app/rd/dashboard.php");
                break;
            case 4: // OIC
                header("Location: /crasm-app/oic/dashboard.php");
                break;
            case 5: // Cashier
                header("Location: /crasm-app/cashier/dashboard.php");
                break;
            case 6: // Province Worker
                header("Location: /crasm-app/province_worker/dashboard.php");
                break;
            default:
                header("Location: /login.php");
                break;
        }

        exit;
    }
}
