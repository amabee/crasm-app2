<?php
session_start();
include_once "../configs/config.php";

function sendJsonResponse($status, $message, $data = null)
{
    header('Content-Type: application/json');
    $response = ['status' => $status, 'message' => $message];
    if ($data) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit;
}

function generateUsername($firstName, $lastName)
{
    try {
        $base = strtolower(substr($firstName, 0, 1) . $lastName);
        $randomNum = rand(100, 999);
        return $base . $randomNum;
    } catch (Exception $e) {
        sendJsonResponse('error', 'Error generating username');
    }
}

function getRoleId($conn, $roleName)
{
    try {
        $stmt = $conn->prepare("SELECT id FROM roles WHERE role_name = ?");
        $stmt->execute([$roleName]);
        $roleId = $stmt->fetchColumn();

        if (!$roleId) {
            throw new Exception("Invalid role selected");
        }

        return $roleId;
    } catch (Exception $e) {
        sendJsonResponse('error', $e->getMessage());
    }
}

function generateUserPassword()
{
    try {
        $tempPassword = bin2hex(random_bytes(8));
        return [
            'plain' => $tempPassword,
            'hashed' => password_hash($tempPassword, PASSWORD_DEFAULT)
        ];
    } catch (Exception $e) {
        sendJsonResponse('error', 'Error generating password');
    }
}

function createUser($conn, $userData, $username, $hashedPassword, $roleId)
{
    try {
        $sql = 'INSERT INTO users (username, first_name, middle_name, last_name, email, password, role_id, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, "active")';

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $username,
            $userData['firstname'],
            $userData['middlename'],
            $userData['lastname'],
            $userData['email'],
            $hashedPassword,
            $roleId
        ]);

        return true;
    } catch (PDOException $e) {
        throw new Exception('Error creating user: ' . $e->getMessage());
    }
}

function getUserById($conn, $userId)
{
    try {
        $sql = "SELECT u.*, r.role_name 
                FROM users u 
                LEFT JOIN roles r ON u.role_id = r.id 
                WHERE u.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception('Error fetching user: ' . $e->getMessage());
    }
}

function updateUser($conn, $userData)
{
    try {
        $roleId = getRoleId($conn, $userData['role']);

        $sql = "UPDATE users SET 
                first_name = ?, 
                middle_name = ?, 
                last_name = ?, 
                email = ?, 
                role_id = ?,
                status = ?
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $userData['firstname'],
            $userData['middlename'],
            $userData['lastname'],
            $userData['email'],
            $roleId,
            $userData['status'],
            $userData['user_id']
        ]);

        return true;
    } catch (PDOException $e) {
        throw new Exception('Error updating user: ' . $e->getMessage());
    }
}

function deleteUser($conn, $userId)
{
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!$user) {
            throw new Exception("User not found");
        }

        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);

        return true;
    } catch (PDOException $e) {
        throw new Exception('Error deleting user: ' . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'get_user':
                    if (empty($_POST['userId'])) {
                        throw new Exception("User ID is required");
                    }
                    $user = getUserById($conn, $_POST['userId']);
                    if ($user) {
                        sendJsonResponse('success', 'User fetched successfully', ['user' => $user]);
                    } else {

                        throw new Exception("User not found");
                    }
                    break;

                case 'update':
                    if (empty($_POST['user_id'])) {
                        throw new Exception("User ID is required");
                    }
                    if (updateUser($conn, $_POST)) {
                        sendJsonResponse('success', 'User updated successfully');
                    }
                    break;

                case 'delete':
                    if (empty($_POST['userId'])) {
                        throw new Exception("User ID is required");
                    }
                    if (deleteUser($conn, $_POST['userId'])) {
                        sendJsonResponse('success', 'User deleted successfully');
                    }
                    break;

                default:
                    throw new Exception("Invalid action specified");
            }
        } else {
            $requiredFields = ['firstname', 'lastname', 'email', 'role'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("$field is required");
                }
            }

            $roleId = getRoleId($conn, $_POST['role']);
            $username = generateUsername($_POST['firstname'], $_POST['lastname']);
            $password = generateUserPassword();

            if (createUser($conn, $_POST, $username, $password['hashed'], $roleId)) {
                sendJsonResponse('success', 'User created successfully', [
                    'username' => $username,
                    'password' => $password['plain']
                ]);
            }
        }
    } catch (Exception $e) {
        sendJsonResponse('error', $e->getMessage());
    }
}
