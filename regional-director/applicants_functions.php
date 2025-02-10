<?php
session_start();
include_once "../configs/config.php";

// Response handler function
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

function getProvincialOffices($conn)
{
    try {
        $sql = "SELECT province_id, provincial_office FROM provincial_office ORDER BY provincial_office";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception('Error fetching provincial offices: ' . $e->getMessage());
    }
}

function createApplication($conn, $data)
{
    try {
        $sql = "INSERT INTO applications (name_of_applicant, provincial_office, date_created) 
                VALUES (?, ?, NOW())";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $data['name_of_applicant'],
            $data['provincial_office']
        ]);

        return $conn->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception('Error creating application: ' . $e->getMessage());
    }
}

function getApplication($conn, $applicationId)
{
    try {
        $sql = "SELECT * FROM applications WHERE application_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$applicationId]);
        $application = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$application) {
            throw new Exception('Application not found: ' . $applicationId);
        }

        return $application;
    } catch (PDOException $e) {
        throw new Exception('Error fetching application: ' . $e->getMessage());
    }
}

function updateApplication($conn, $data)
{
    try {
        $sql = "UPDATE applications SET 
            date_application_approved_by_rd = ?,
            for_issuance_of_crasm = ?,
            for_transmittal_of_crasm = ?,
            date_crasm_approved_by_rd = ?,
            last_updated = NOW()
            WHERE application_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $data['name_of_applicant'],
            $data['provincial_office'],
            !empty($data['date_application_approved_by_rd']) ? $data['date_application_approved_by_rd'] : null,
            !empty($data['for_issuance_of_crasm']) ? $data['for_issuance_of_crasm'] : null,
            !empty($data['for_transmittal_of_crasm']) ? $data['for_transmittal_of_crasm'] : null,
            !empty($data['date_crasm_approved_by_rd']) ? $data['date_crasm_approved_by_rd'] : null,
            $data['application_id']
        ]);

        return true;
    } catch (PDOException $e) {
        throw new Exception('Error updating application: ' . $e->getMessage());
    }
}


// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'create_application':
                    if (empty($_POST['name_of_applicant'])) {
                        throw new Exception("Applicant name is required");
                    }
                    $applicationId = createApplication($conn, $_POST);
                    sendJsonResponse('success', 'Application created successfully', ['id' => $applicationId]);
                    break;

                case 'get_provincial_offices':
                    $offices = getProvincialOffices($conn);
                    sendJsonResponse('success', 'Provincial offices fetched successfully', ['offices' => $offices]);
                    break;

                case 'get_application':
                    if (empty($_POST['application_id'])) {
                        throw new Exception("Application ID is required");
                    }
                    $application = getApplication($conn, $_POST['application_id']);
                    sendJsonResponse('success', 'Application fetched successfully', $application);
                    break;

                case 'update_application':
                    if (empty($_POST['application_id'])) {
                        throw new Exception("Application ID is required");
                    }
                    updateApplication($conn, $_POST);
                    sendJsonResponse('success', 'Application updated successfully');
                    break;

                default:
                    throw new Exception("Invalid action specified");
            }
        } else {
            throw new Exception("No action specified");
        }
    } catch (Exception $e) {
        sendJsonResponse('error', $e->getMessage());
    }
}
