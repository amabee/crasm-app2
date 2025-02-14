<?php
session_start();
include_once "../configs/config.php";

// Set headers for JSON response
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'create_application':
            try {
                // Get provincial office from session
                $provincial_office = $_SESSION['provincial_office'] ?? null;
                
                if (!$provincial_office) {
                    throw new Exception("Provincial office not found in session");
                }

                $sql = "INSERT INTO applications (
                    name_of_applicant,
                    provincial_office,
                    date_received_by_po_from_so_applicant,
                    type_of_application,
                    date_of_payment,
                    or_number,
                    date_transmitted_to_ro,
                    date_received_by_ro,
                    ro_screener,
                    date_forwarded_to_the_office_of_oic,
                    date_reviewed_by_oic_crasd,
                    feedbacks,
                    date_forwarded_to_ord,
                    date_application_approved_by_rd,
                    for_issuance_of_crasm,
                    for_transmittal_of_crasm,
                    date_crasm_generated,
                    date_forwarded_back_to_the_office_of_oic_cao,
                    date_reviewed_and_initialed_by_oic_crasd,
                    date_forwarded_back_to_ord,
                    date_crasm_approved_by_rd,
                    date_transmitted_back_to_po,
                    date_received_by_po,
                    date_released_to_so,
                    remarks,
                    date_created
                ) VALUES (
                    :name_of_applicant,
                    :provincial_office,
                    :date_received_by_po_from_so_applicant,
                    :type_of_application,
                    :date_of_payment,
                    :or_number,
                    :date_transmitted_to_ro,
                    :date_received_by_ro,
                    :ro_screener,
                    :date_forwarded_to_the_office_of_oic,
                    :date_reviewed_by_oic_crasd,
                    :feedbacks,
                    :date_forwarded_to_ord,
                    :date_application_approved_by_rd,
                    :for_issuance_of_crasm,
                    :for_transmittal_of_crasm,
                    :date_crasm_generated,
                    :date_forwarded_back_to_the_office_of_oic_cao,
                    :date_reviewed_and_initialed_by_oic_crasd,
                    :date_forwarded_back_to_ord,
                    :date_crasm_approved_by_rd,
                    :date_transmitted_back_to_po,
                    :date_received_by_po,
                    :date_released_to_so,
                    :remarks,
                    NOW()
                )";

                $stmt = $conn->prepare($sql);
                
                // Bind parameters
                $stmt->bindParam(':name_of_applicant', $_POST['name_of_applicant']);
                $stmt->bindParam(':provincial_office', $provincial_office);
                $stmt->bindParam(':date_received_by_po_from_so_applicant', $_POST['date_received_by_po_from_so_applicant']);
                $stmt->bindParam(':type_of_application', $_POST['type_of_application']);
                $stmt->bindParam(':date_of_payment', $_POST['date_of_payment']);
                $stmt->bindParam(':or_number', $_POST['or_number']);
                $stmt->bindParam(':date_transmitted_to_ro', $_POST['date_transmitted_to_ro']);
                $stmt->bindParam(':date_received_by_ro', $_POST['date_received_by_ro']);
                $stmt->bindParam(':ro_screener', $_POST['ro_screener']);
                $stmt->bindParam(':date_forwarded_to_the_office_of_oic', $_POST['date_forwarded_to_the_office_of_oic']);
                $stmt->bindParam(':date_reviewed_by_oic_crasd', $_POST['date_reviewed_by_oic_crasd']);
                $stmt->bindParam(':feedbacks', $_POST['feedbacks']);
                $stmt->bindParam(':date_forwarded_to_ord', $_POST['date_forwarded_to_ord']);
                $stmt->bindParam(':date_application_approved_by_rd', $_POST['date_application_approved_by_rd']);
                $stmt->bindParam(':for_issuance_of_crasm', $_POST['for_issuance_of_crasm']);
                $stmt->bindParam(':for_transmittal_of_crasm', $_POST['for_transmittal_of_crasm']);
                $stmt->bindParam(':date_crasm_generated', $_POST['date_crasm_generated']);
                $stmt->bindParam(':date_forwarded_back_to_the_office_of_oic_cao', $_POST['date_forwarded_back_to_the_office_of_oic_cao']);
                $stmt->bindParam(':date_reviewed_and_initialed_by_oic_crasd', $_POST['date_reviewed_and_initialed_by_oic_crasd']);
                $stmt->bindParam(':date_forwarded_back_to_ord', $_POST['date_forwarded_back_to_ord']);
                $stmt->bindParam(':date_crasm_approved_by_rd', $_POST['date_crasm_approved_by_rd']);
                $stmt->bindParam(':date_transmitted_back_to_po', $_POST['date_transmitted_back_to_po']);
                $stmt->bindParam(':date_received_by_po', $_POST['date_received_by_po']);
                $stmt->bindParam(':date_released_to_so', $_POST['date_released_to_so']);
                $stmt->bindParam(':remarks', $_POST['remarks']);

                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Application created successfully']);
                } else {
                    throw new Exception("Error executing SQL statement");
                }
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error creating application: ' . $e->getMessage()
                ]);
            }
            break;

        case 'update_application':
            try {
                $sql = "UPDATE applications SET 
                    name_of_applicant = :name_of_applicant,
                    date_received_by_po_from_so_applicant = :date_received_by_po_from_so_applicant,
                    type_of_application = :type_of_application,
                    date_transmitted_to_ro = :date_transmitted_to_ro,
                    date_received_by_ro = :date_received_by_ro,
                    date_received_by_po = :date_received_by_po,
                    date_released_to_so = :date_released_to_so,
                    remarks = :remarks 
                    WHERE application_id = :application_id";

                $stmt = $conn->prepare($sql);
                
                // Bind parameters
                $stmt->bindParam(':application_id', $_POST['application_id']);
                $stmt->bindParam(':name_of_applicant', $_POST['name_of_applicant']);
                $stmt->bindParam(':date_received_by_po_from_so_applicant', $_POST['date_received_by_po_from_so_applicant']);
                $stmt->bindParam(':type_of_application', $_POST['type_of_application']);
                $stmt->bindParam(':date_transmitted_to_ro', $_POST['date_transmitted_to_ro']);
                $stmt->bindParam(':date_received_by_ro', $_POST['date_received_by_ro']);
                $stmt->bindParam(':date_received_by_po', $_POST['date_received_by_po']);
                $stmt->bindParam(':date_released_to_so', $_POST['date_released_to_so']);
                $stmt->bindParam(':remarks', $_POST['remarks']);

                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Application updated successfully']);
                } else {
                    throw new Exception("Error executing SQL statement");
                }
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error updating application: ' . $e->getMessage()
                ]);
            }
            break;

        case 'get_application':
            try {
                $sql = "SELECT * FROM applications WHERE application_id = :application_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':application_id', $_POST['application_id']);
                $stmt->execute();
                
                $application = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($application) {
                    echo json_encode(['status' => 'success', 'data' => $application]);
                } else {
                    throw new Exception("Application not found");
                }
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error fetching application: ' . $e->getMessage()
                ]);
            }
            break;

        default:
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid action specified'
            ]);
            break;
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
?>