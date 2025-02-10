<?php
session_start();
include_once "../configs/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
if (isset($_SESSION['role_id'])) {
    switch ($_SESSION['role_id']) {
        case 1:
            header("Location: ../super_admin/dashboard.php");
            break;
        case 2:
            header("Location: ../regional-director/dashboard.php");
            break;
        case 3:
            
            break;
        case 4:
            header("Location: ../cao/dashboard.php");
            break;
        case 5:
            header("Location: ../collecting-officer/dashboard.php");
            break;
        case 6:
            header("Location: ../provincial/dashboard.php");
            break;
        default:
            break;
    }
}

$systemInfo = [];
$sql = "SELECT `app_name`, `title`, `front_title`, `favicon`, `app_logo` FROM `system_settings`";
$stmt = $conn->prepare($sql);
$stmt->execute();
$systemInfo = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

$sql = "SELECT
    a.application_id,
    a.name_of_applicant, 
    po.provincial_office,
    CASE 
        WHEN    
            a.date_received_by_po_from_so_applicant IS NULL OR
            a.type_of_application IS NULL OR
            a.date_of_payment IS NULL OR
            a.or_number IS NULL OR
            a.date_transmitted_to_ro IS NULL OR
            a.date_received_by_ro IS NULL OR
            a.ro_screener IS NULL OR
            a.date_forwarded_to_the_office_of_oic IS NULL OR
            a.date_reviewed_by_oic_crasd IS NULL OR
            a.feedbacks IS NULL OR
            a.date_forwarded_to_ord IS NULL OR
            a.date_application_approved_by_rd IS NULL OR
            a.for_issuance_of_crasm IS NULL OR
            a.for_transmittal_of_crasm IS NULL OR
            a.date_crasm_generated IS NULL OR
            a.date_forwarded_back_to_the_office_of_oic_cao IS NULL OR
            a.date_reviewed_and_initialed_by_oic_crasd IS NULL OR
            a.date_forwarded_back_to_ord IS NULL OR
            a.date_crasm_approved_by_rd IS NULL OR
            a.date_transmitted_back_to_po IS NULL OR
            a.date_received_by_po IS NULL OR
            a.date_released_to_so IS NULL OR
            a.remarks IS NULL
        THEN 'Pending' 
        ELSE 'Complete' 
    END AS status
FROM applications a
LEFT JOIN provincial_office po ON a.provincial_office = po.province_id
ORDER BY a.date_created DESC;
";
$stmt = $conn->prepare($sql);
$stmt->execute();
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title><?php echo $systemInfo['app_name'] ?></title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="../vendors/images/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="../vendors/images/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="../vendors/images/favicon-16x16.png" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="../vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="../src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="../src/plugins/datatables/css/responsive.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="../vendors/styles/style.css" />

    <script src="process.js" defer></script>
</head>

<body>
    <div class="pre-loader">
        <div class="pre-loader-box">
            <div class="loader-logo">
                <img src=<?php echo $systemInfo['app_logo'] ?> alt="" class="dark-logo" />
            </div>
            <div class="loader-progress" id="progress_div">
                <div class="bar" id="bar1"></div>
            </div>
            <div class="percent" id="percent1">0%</div>
            <div class="loading-text">Loading...</div>
        </div>
    </div>

    <div class="header">
        <div class="header-left">
            <div class="menu-icon bi bi-list"></div>
            <div class="search-toggle-icon bi bi-search" data-toggle="header_search"></div>
        </div>
        <div class="header-right">
            <div class="dashboard-setting user-notification">
                <div class="dropdown">
                    <a class="dropdown-toggle no-arrow" href="javascript:;" data-toggle="right-sidebar">
                        <i class="dw dw-settings2"></i>
                    </a>
                </div>
            </div>
            <div class="user-info-dropdown">
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                        <span class="user-icon">
                            <img src=<?php echo $_SESSION['image'] ?? "https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg"; ?> alt="Profile Image" />
                        </span>
                        <span class="user-name"><?php echo ($_SESSION['firstname'] . " " . (isset($_SESSION['middlename']) ? $_SESSION['middlename'] : "") . " " . $_SESSION['lastname']); ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                        <form method="POST" action="../logout.php">
                            <button type="submit" name="logout" class="dropdown-item">
                                <i class="dw dw-logout"></i> Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="right-sidebar">
        <!-- Your existing right sidebar code -->
    </div>

    <div class="left-side-bar">
        <div class="brand-logo">
            <a href="index.html">
                <img src=<?php echo $systemInfo['app_logo'] ?> alt="" class="dark-logo" />
                <img src="../vendors/images/deskapp-logo-white.svg" alt="" class="light-logo" />
            </a>
            <div class="close-sidebar" data-toggle="left-sidebar-close">
                <i class="ion-close-round"></i>
            </div>
        </div>
        <div class="menu-block customscroll">
            <div class="sidebar-menu">
                <ul id="accordion-menu">
                    <li>
                        <a href="dashboard.php" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-house-door"></span><span class="mtext">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="applications.php" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-person-lines-fill"></span><span class="mtext">Applications</span>
                        </a>
                    </li>
                    <li>
                        <a href="account_settings.php" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-gear"></span><span class="mtext">Account Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="xs-pd-20-10 pd-ltr-20">
            <div class="card-box pb-10">
                <div class="h5 pd-20 mb-0 d-flex justify-content-between align-items-center">
                    <span>Applications</span>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addApplicationModal">
                        <i class="bi bi-plus"></i> Add Application
                    </button>
                </div>

                <table class="data-table table nowrap">
                    <thead>
                        <tr>
                            <th class="d-none">Application ID</th>
                            <th class="table-plus">Applicant Name</th>
                            <th>Provincial Office</th>
                            <th>Status</th>
                            <th class="datatable-nosort">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $application): ?>
                            <tr>
                                <td class="table-plus d-none">
                                    <div class="txt">
                                        <div class="weight-600" id="application_id" name="application_id"><?php echo htmlspecialchars($application['application_id']); ?></div>
                                    </div>
                                </td>
                                <td class="table-plus">
                                    <div class="name-avatar d-flex align-items-center">
                                        <div class="txt">
                                            <div class="weight-600"><?php echo htmlspecialchars($application['name_of_applicant']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="txt">
                                        <div class="weight-600"><?php echo htmlspecialchars($application['provincial_office']); ?></div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-pill"
                                        data-bgcolor="<?php echo ($application['status'] == 'Complete') ? '#28a745' : '#ffc107'; ?>"
                                        data-color="#ffffff">
                                        <?php echo htmlspecialchars($application['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="#"
                                            data-color="#265ed7"
                                            style="margin-right: 10px;"
                                            class="edit-application"
                                            data-id='<?php echo htmlspecialchars($application['application_id']); ?>'>
                                            <i class="icon-copy dw dw-edit2"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Application Modal -->
    <div class="modal fade" id="addApplicationModal" tabindex="-1" aria-labelledby="addApplicationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addApplicationModalLabel">Add New Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addApplicationForm">
                        <input type="hidden" name="action" value="add_application">
                        
                        <!-- Basic Information -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="add_name_of_applicant" class="form-label">Applicant Name</label>
                                <input type="text" class="form-control" id="add_name_of_applicant" name="name_of_applicant" required>
                            </div>
                            <div class="col-md-6">
                                <label for="add_provincial_office" class="form-label">Provincial Office</label>
                                <select class="form-control" id="add_provincial_office" name="provincial_office" required>
                                    <option value="">Select Provincial Office</option>
                                </select>
                            </div>
                        </div>

                        <!-- Initial Processing -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="add_date_received_po" class="form-label">Date Received by PO from SO Applicant</label>
                                <input type="date" class="form-control" id="add_date_received_po" name="date_received_by_po_from_so_applicant">
                            </div>
                            <div class="col-md-6">
                                <label for="add_type_of_application" class="form-label">Type of Application</label>
                                <input type="text" class="form-control" id="add_type_of_application" name="type_of_application">
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="add_date_of_payment" class="form-label">Date of Payment</label>
                                <input type="date" class="form-control" id="add_date_of_payment" name="date_of_payment">
                            </div>
                            <div class="col-md-6">
                                <label for="add_or_number" class="form-label">O.R. Number</label>
                                <input type="text" class="form-control" id="add_or_number" name="or_number">
                            </div>
                        </div>

                        <!-- Remarks -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="add_remarks" class="form-label">Remarks</label>
                                <textarea class="form-control" id="add_remarks" name="remarks" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Application Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <input type="hidden" name="action" value="update_application">
                        <input type="hidden" name="application_id" id="editApplicationId">

                        <!-- Basic Information -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name_of_applicant" class="form-label">Applicant Name</label>
                                <input type="text" class="form-control" id="name_of_applicant" name="name_of_applicant" required>
                            </div>
                            <div class="col-md-6">
                                <label for="provincial_office" class="form-label">Provincial Office</label>
                                <select class="form-control" id="provincial_office" name="provincial_office" required>
                                    <option value="">Select Provincial Office</option>
                                </select>
                            </div>
                        </div>

                        <!-- All your existing edit form fields here -->
                        <!-- Initial Processing -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editDateReceivedPO" class="form-label">Date Received by PO from SO Applicant</label>
                                <input type="date" class="form-control" id="editDateReceivedPO" name="date_received_by_po_from_so_applicant">
                            </div>
                            <div class="col-md-6">
                                <label for="editTypeOfApplication" class="form-label">Type of Application</label>
                                <input type="text" class="form-control" id="editTypeOfApplication" name="type_of_application">
                            </div>
                        </div>

                        <!-- Keep all your existing edit form fields -->

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- js -->
    <script src="../vendors/scripts/core.js"></script>
    <script src="../vendors/scripts/script.min.js"></script>
    <script src="../vendors/scripts/process.js"></script>
    <script src="../vendors/scripts/layout-settings.js"></script>
    <script src="../src/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="../src/plugins/datatables/js/dataTables.responsive.min.js"></script>
    <script src="../src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
    <script src="../vendors/scripts/dashboard3.js"></script>
    <script src="../vendors/scripts/datatable-setting.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Edit form submission handler
            document.getElementById('editUserForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const orNumber = document.getElementById('editORNumber').value;
                document.getElementById('editORNumberHidden').value = orNumber;

                if (validateForm(this)) {
                    saveApplicationData(this);
                }
            });

            // Add form submission handler
            document.getElementById('addApplicationForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (validateForm(this)) {
                    const formData = new FormData(this);
                    
                    fetch('process_application.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Application added successfully!'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Failed to add application'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while adding the application'
                        });
                    });
                }
            });

            // Edit button click handlers
            document.querySelectorAll('.edit-application').forEach(button => {
                button.addEventListener('click', function() {
                    const applicationId = this.dataset.id;
                    loadApplicationData(applicationId);
                });
            });

            // Load provincial offices for both forms
            loadProvincialOffices();
            populateAddFormProvincialOffices();
        });

        // Function to populate provincial offices in add form
        function populateAddFormProvincialOffices() {
            const select = document.getElementById('add_provincial_office');
            fetch('get_provincial_offices.php')
                .then(response => response.json())
                .then(data => {
                    data.forEach(office => {
                        const option = document.createElement('option');
                        option.value = office.province_id;
                        option.textContent = office.provincial_office;
                        select.appendChild(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        // Your existing form validation function
        function validateForm(form) {
            // Add your validation logic here
            return true;
        }
    </script>
</body>
</html>