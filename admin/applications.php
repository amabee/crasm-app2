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
            break;
        case 3:
            header("Location: ../regional-director/dashboard.php");
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
    <link
        rel="apple-touch-icon"
        sizes="180x180"
        href="../vendors/images/apple-touch-icon.png" />
    <link
        rel="icon"
        type="image/png"
        sizes="32x32"
        href="../vendors/images/favicon-32x32.png" />
    <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="../vendors/images/favicon-16x16.png" />

    <!-- Mobile Specific Metas -->
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../vendors/styles/core.css" />
    <link
        rel="stylesheet"
        type="text/css"
        href="../vendors/styles/icon-font.min.css" />
    <link
        rel="stylesheet"
        type="text/css"
        href="../src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
    <link
        rel="stylesheet"
        type="text/css"
        href="../src/plugins/datatables/css/responsive.bootstrap4.min.css" />
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
            <div
                class="search-toggle-icon bi bi-search"
                data-toggle="header_search"></div>
        </div>
        <div class="header-right">
            <div class="dashboard-setting user-notification">
                <div class="dropdown">
                    <a
                        class="dropdown-toggle no-arrow"
                        href="javascript:;"
                        data-toggle="right-sidebar">
                        <i class="dw dw-settings2"></i>
                    </a>
                </div>
            </div>
            <div class="user-info-dropdown">
                <div class="dropdown">
                    <a
                        class="dropdown-toggle"
                        href="#"
                        role="button"
                        data-toggle="dropdown">
                        <span class="user-icon">
                            <img src=<?php echo $_SESSION['image'] ?? "https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg"; ?> alt="Profile Image" />
                        </span>
                        <span class="user-name"><?php echo ($_SESSION['firstname'] . " " . (isset($_SESSION['middlename']) ? $_SESSION['middlename'] : "") . " " . $_SESSION['lastname']); ?></h3></span>
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
            <div class="github-link">
                <a href="https://github.com/dropways/deskapp" target="_blank"><img src="vendors/images/github.svg" alt="" /></a>
            </div>
        </div>
    </div>

    <div class="right-sidebar">
        <div class="sidebar-title">
            <h3 class="weight-600 font-16 text-blue">
                Layout Settings
                <span class="btn-block font-weight-400 font-12">User Interface Settings</span>
            </h3>
            <div class="close-sidebar" data-toggle="right-sidebar-close">
                <i class="icon-copy ion-close-round"></i>
            </div>
        </div>
        <div class="right-sidebar-body customscroll">
            <div class="right-sidebar-body-content">
                <h4 class="weight-600 font-18 pb-10">Header Background</h4>
                <div class="sidebar-btn-group pb-30 mb-10">
                    <a
                        href="javascript:void(0);"
                        class="btn btn-outline-primary header-white active">White</a>
                    <a
                        href="javascript:void(0);"
                        class="btn btn-outline-primary header-dark">Dark</a>
                </div>

                <h4 class="weight-600 font-18 pb-10">Sidebar Background</h4>
                <div class="sidebar-btn-group pb-30 mb-10">
                    <a
                        href="javascript:void(0);"
                        class="btn btn-outline-primary sidebar-light">White</a>
                    <a
                        href="javascript:void(0);"
                        class="btn btn-outline-primary sidebar-dark active">Dark</a>
                </div>

                <h4 class="weight-600 font-18 pb-10">Menu Dropdown Icon</h4>
                <div class="sidebar-radio-group pb-10 mb-10">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input
                            type="radio"
                            id="sidebaricon-1"
                            name="menu-dropdown-icon"
                            class="custom-control-input"
                            value="icon-style-1"
                            checked="" />
                        <label class="custom-control-label" for="sidebaricon-1"><i class="fa fa-angle-down"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input
                            type="radio"
                            id="sidebaricon-2"
                            name="menu-dropdown-icon"
                            class="custom-control-input"
                            value="icon-style-2" />
                        <label class="custom-control-label" for="sidebaricon-2"><i class="ion-plus-round"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input
                            type="radio"
                            id="sidebaricon-3"
                            name="menu-dropdown-icon"
                            class="custom-control-input"
                            value="icon-style-3" />
                        <label class="custom-control-label" for="sidebaricon-3"><i class="fa fa-angle-double-right"></i></label>
                    </div>
                </div>

                <h4 class="weight-600 font-18 pb-10">Menu List Icon</h4>
                <div class="sidebar-radio-group pb-30 mb-10">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input
                            type="radio"
                            id="sidebariconlist-1"
                            name="menu-list-icon"
                            class="custom-control-input"
                            value="icon-list-style-1"
                            checked="" />
                        <label class="custom-control-label" for="sidebariconlist-1"><i class="ion-minus-round"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input
                            type="radio"
                            id="sidebariconlist-2"
                            name="menu-list-icon"
                            class="custom-control-input"
                            value="icon-list-style-2" />
                        <label class="custom-control-label" for="sidebariconlist-2"><i class="fa fa-circle-o" aria-hidden="true"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input
                            type="radio"
                            id="sidebariconlist-3"
                            name="menu-list-icon"
                            class="custom-control-input"
                            value="icon-list-style-3" />
                        <label class="custom-control-label" for="sidebariconlist-3"><i class="dw dw-check"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input
                            type="radio"
                            id="sidebariconlist-4"
                            name="menu-list-icon"
                            class="custom-control-input"
                            value="icon-list-style-4"
                            checked="" />
                        <label class="custom-control-label" for="sidebariconlist-4"><i class="icon-copy dw dw-next-2"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input
                            type="radio"
                            id="sidebariconlist-5"
                            name="menu-list-icon"
                            class="custom-control-input"
                            value="icon-list-style-5" />
                        <label class="custom-control-label" for="sidebariconlist-5"><i class="dw dw-fast-forward-1"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input
                            type="radio"
                            id="sidebariconlist-6"
                            name="menu-list-icon"
                            class="custom-control-input"
                            value="icon-list-style-6" />
                        <label class="custom-control-label" for="sidebariconlist-6"><i class="dw dw-next"></i></label>
                    </div>
                </div>

                <div class="reset-options pt-30 text-center">
                    <button class="btn btn-danger" id="reset-settings">
                        Reset Settings
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="left-side-bar">
        <div class="brand-logo">
            <a href="index.html">
                <img src=<?php echo $systemInfo['app_logo'] ?> alt="" class="dark-logo" />
                <img
                    src="../vendors/images/deskapp-logo-white.svg"
                    alt=""
                    class="light-logo" />
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
                        <a href="user-control.php" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-person-lines-fill"></span><span class="mtext">User Control</span>
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
                                        <!-- <a href="#" data-color="#e95959" onclick="deleteUser(this)">
                                            <i class="icon-copy dw dw-delete-3"></i>
                                        </a> -->
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>


    <!-- EDIT MODAL -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
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

                        <!-- Payment Information -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editDateOfPayment" class="form-label">Date of Payment</label>
                                <input type="date" class="form-control" id="editDateOfPayment" name="date_of_payment">
                            </div>
                            <div class="col-md-6">
                                <label for="editORNumber" class="form-label">O.R. Number</label>
                                <input type="text" class="form-control" id="editORNumber" name="or_number" readonly>
                                <input type="hidden" name="or_number_hidden" id="editORNumberHidden">
                            </div>
                        </div>

                        <!-- RO Processing -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editDateTransmittedRO" class="form-label">Date Transmitted to RO</label>
                                <input type="date" class="form-control" id="editDateTransmittedRO" name="date_transmitted_to_ro">
                            </div>
                            <div class="col-md-6">
                                <label for="editDateReceivedRO" class="form-label">Date Received by RO</label>
                                <input type="date" class="form-control" id="editDateReceivedRO" name="date_received_by_ro">
                            </div>
                        </div>

                        <!-- Review Process -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editDateReviewedRO" class="form-label">Date Reviewed by RO Screener/SOIS Focal</label>
                                <input type="date" class="form-control" id="editDateReviewedRO" name="ro_screener">
                            </div>
                            <div class="col-md-6">
                                <label for="editDateForwardedOIC" class="form-label">Date Forwarded to OIC/CAO</label>
                                <input type="date" class="form-control" id="editDateForwardedOIC" name="date_forwarded_to_the_office_of_oic">
                            </div>
                        </div>

                        <!-- OIC Review -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editDateReviewedOIC" class="form-label">Date Reviewed by OIC CRASD</label>
                                <input type="date" class="form-control" id="editDateReviewedOIC" name="date_reviewed_by_oic_crasd">
                            </div>
                            <div class="col-md-6">
                                <label for="editFeedbacks" class="form-label">Feedbacks</label>
                                <textarea class="form-control" id="editFeedbacks" name="feedbacks"></textarea>
                            </div>
                        </div>

                        <!-- ORD Processing -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editDateForwardedORD" class="form-label">Date Forwarded to ORD</label>
                                <input type="date" class="form-control" id="editDateForwardedORD" name="date_forwarded_to_ord">
                            </div>
                            <div class="col-md-6">
                                <label for="editDateApprovedRD" class="form-label">Date Approved by Regional Director</label>
                                <input type="date" class="form-control" id="editDateApprovedRD" name="date_application_approved_by_rd">
                            </div>
                        </div>

                        <!-- CRASM Processing -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editDateCrasmIssuance" class="form-label">Date Returned for CRASM Issuance</label>
                                <input type="date" class="form-control" id="editDateCrasmIssuance" name="for_issuance_of_crasm">
                            </div>
                            <div class="col-md-6">
                                <label for="editDateTransmittalCRASM" class="form-label">Date for CRASM Transmittal</label>
                                <input type="date" class="form-control" id="editDateTransmittalCRASM" name="for_transmittal_of_crasm">
                            </div>
                        </div>

                        <!-- Final Processing -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editDateGeneratedCRASM" class="form-label">Date CRASM Generated</label>
                                <input type="date" class="form-control" id="editDateGeneratedCRASM" name="date_crasm_generated">
                            </div>
                            <div class="col-md-6">
                                <label for="editDateForwardedBackOIC" class="form-label">Date Forwarded Back to OIC/CAO</label>
                                <input type="date" class="form-control" id="editDateForwardedBackOIC" name="date_forwarded_back_to_the_office_of_oic_cao">
                            </div>
                        </div>

                        <!-- Final Review -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editDateReviewedInitialedOIC" class="form-label">Date Reviewed & Initialed by OIC-CRASD</label>
                                <input type="date" class="form-control" id="editDateReviewedInitialedOIC" name="date_reviewed_and_initialed_by_oic_crasd">
                            </div>
                            <div class="col-md-6">
                                <label for="editDateForwardedBackORD" class="form-label">Date Forwarded Back to ORD</label>
                                <input type="date" class="form-control" id="editDateForwardedBackORD" name="date_forwarded_back_to_ord">
                            </div>
                        </div>

                        <!-- Final Approval -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editDateApprovedCRASM" class="form-label">Date CRASM Approved by RD</label>
                                <input type="date" class="form-control" id="editDateApprovedCRASM" name="date_crasm_approved_by_rd">
                            </div>
                            <div class="col-md-6">
                                <label for="editDateTransmittedPO" class="form-label">Date Transmitted back to PO</label>
                                <input type="date" class="form-control" id="editDateTransmittedPO" name="date_transmitted_back_to_po">
                            </div>
                        </div>

                        <!-- Final Status -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editDateReceivedPO" class="form-label">Date Received by PO</label>
                                <input type="date" class="form-control" id="editDateReceivedPO" name="date_received_by_po">
                            </div>
                            <div class="col-md-6">
                                <label for="editDateReleasedSO" class="form-label">Date Released to SO</label>
                                <input type="date" class="form-control" id="editDateReleasedSO" name="date_released_to_so">
                            </div>
                        </div>

                        <!-- Remarks -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="editRemarks" class="form-label">Remarks</label>
                                <textarea class="form-control" id="editRemarks" name="remarks" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- modal -->

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
            document.getElementById('editUserForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const orNumber = document.getElementById('editORNumber').value;
                document.getElementById('editORNumberHidden').value = orNumber;

                if (validateForm(this)) {
                    saveApplicationData(this);
                }
            });

            document.querySelectorAll('.edit-application').forEach(button => {
                button.addEventListener('click', function() {
                    const applicationId = this.dataset.id;
                    loadApplicationData(applicationId);
                });
            });

            loadProvincialOffices();
        });
    </script>

</body>

</html>