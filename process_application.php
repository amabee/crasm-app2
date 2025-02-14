<?php
session_start();
include_once "../configs/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($_POST['action'] === 'create_application') {
            $sql = "INSERT INTO applications (
                name_of_applicant,
                provincial_office,
                date_received_by_po_from_so_applicant,
                type_of_application,
                date_transmitted_to_ro,
                date_received_by_po,
                date_released_to_so,
                remarks
            ) VALUES (
                :name_of_applicant,
                :provincial_office,
                :date_received_by_po_from_so_applicant,
                :type_of_application,
                :date_transmitted_to_ro,
                :date_received_by_po,
                :date_released_to_so,
                :remarks
            )";

            $stmt = $conn->prepare($sql);
            
            $stmt->bindParam(':name_of_applicant', $_POST['name_of_applicant']);
            $stmt->bindParam(':provincial_office', $_POST['provincial_office']);
            $stmt->bindParam(':date_received_by_po_from_so_applicant', $_POST['date_received_by_po_from_so_applicant']);
            $stmt->bindParam(':type_of_application', $_POST['type_of_application']);
            $stmt->bindParam(':date_transmitted_to_ro', $_POST['date_transmitted_to_ro']);
            $stmt->bindParam(':date_received_by_po', $_POST['date_received_by_po']);
            $stmt->bindParam(':date_released_to_so', $_POST['date_released_to_so']);
            $stmt->bindParam(':remarks', $_POST['remarks']);

            if ($stmt->execute()) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Application added successfully'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to add application'
                ]);
            }
        }
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
}
?>
