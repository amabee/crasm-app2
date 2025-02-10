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

$sql = "SELECT u.id, u.username, u.email, u.first_name, u.middle_name, u.last_name, u.image, u.status, r.role_name as role_name 
        FROM users u 
        LEFT JOIN roles r ON u.role_id = r.id
        WHERE r.id != 1 AND u.id != :user_id
        ORDER BY u.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":user_id", $_SESSION['user_id']);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        rel="icon"
        type="image/png"
        sizes="32x32"
        href=<?php echo "../uploads/" . $systemInfo['favicon'] ?> />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                    <span>User Control</span>
                    <button class="btn btn-primary" class="btn-block"
                        data-toggle="modal"
                        data-target="#userModal"
                        type="button">
                        <i class="icon-copy dw dw-add"></i> Add User
                    </button>
                </div>
                <table class="data-table table stripe hover nowrap">
                    <thead>
                        <tr>
                            <th class="d-none">ID</th>
                            <th class="table-plus">Firstname</th>
                            <th>Middlename</th>
                            <th>Lastname</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="datatable-nosort">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="d-none"><?php echo htmlspecialchars($user['id'] ?? ''); ?></td>
                                <td class="table-plus">
                                    <div class="name-avatar d-flex align-items-center">
                                        <div class="avatar mr-2 flex-shrink-0">
                                            <img
                                                src="<?php echo htmlspecialchars($user['image'] ?? 'vendors/images/default-avatar.jpg'); ?>"
                                                class="border-radius-100 shadow"
                                                width="40"
                                                height="40"
                                                alt="User avatar" />
                                        </div>
                                        <div class="txt">
                                            <div class="weight-600"><?php echo htmlspecialchars($user['first_name']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($user['middle_name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['role_name']); ?></td>
                                <td>
                                    <span
                                        class="badge badge-pill"
                                        data-bgcolor="<?php echo $user['status'] == strtolower('active') ? '#28a745' : '#dc3545'; ?>"
                                        data-color="#fff">
                                        <?php echo $user['status'] == strtolower('Active') ? strtolower('Active') : strtolower('Inactive'); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php

                                    if ($user['role_name'] !== "Super Admin"):
                                    ?>
                                        <div class='table-actions'>
                                            <a href='javascript:;' data-color='#265ed7' onclick='editUser(<?php echo (int)$user["id"]; ?>)'>
                                                <i class='icon-copy dw dw-edit2'></i>
                                            </a>
                                            <a href='#' type='button' data-color='#e95959' onclick='deleteUser(<?php echo (int)$user["id"]; ?>)'>
                                                <i class='icon-copy dw dw-delete-3'></i>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- create modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="userForm" method="POST" class="add_user">
                        <div class="mb-3">
                            <label for="firstname" class="form-label">First Name *</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" required>
                        </div>
                        <div class="mb-3">
                            <label for="middlename" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="middlename" name="middlename">
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="form-label">Last Name *</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role *</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="">Select a role</option>
                                <option value="Regional Director">Regional Director</option>
                                <option value="OIC / CAO">OIC / CAO</option>
                                <option value="Collecting Officer">Collecting Officer</option>
                                <option value="Provincial Worker">Provincial Worker</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveUser">Save User</button>
                </div>
            </div>
        </div>
    </div>
    <!-- edit Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" method="POST">
                        <input type="hidden" id="edit_user_id" name="user_id">
                        <div class="mb-3">
                            <label for="edit_firstname" class="form-label">First Name *</label>
                            <input type="text" class="form-control" id="edit_firstname" name="firstname" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_middlename" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="edit_middlename" name="middlename">
                        </div>
                        <div class="mb-3">
                            <label for="edit_lastname" class="form-label">Last Name *</label>
                            <input type="text" class="form-control" id="edit_lastname" name="lastname" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_role" class="form-label">Role *</label>
                            <select class="form-control" id="edit_role" name="role" required>
                                <option value="">Select a role</option>
                                <option value="Regional Director">Regional Director</option>
                                <option value="OIC / CAO">OIC / CAO</option>
                                <option value="Collecting Officer">Collecting Officer</option>
                                <option value="Provincial Worker">Provincial Worker</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_status" class="form-label">Status *</label>
                            <select class="form-control" id="edit_status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="updateUser">Update User</button>
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


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const userForm = document.getElementById("userForm");
            const editUserForm = document.getElementById("editUserForm");
            const saveUserBtn = document.getElementById("saveUser");
            const updateUserBtn = document.getElementById("updateUser");

            saveUserBtn.addEventListener("click", function(e) {
                e.preventDefault();
                if (validateForm(userForm)) {
                    handleFormSubmit(userForm);
                }
            });

            updateUserBtn.addEventListener("click", function(e) {
                e.preventDefault();
                if (validateForm(editUserForm)) {
                    handleEditFormSubmit(editUserForm);
                }
            });
        });
    </script>


</body>

</html>