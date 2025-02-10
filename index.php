<?php
session_start();
if (isset($_SESSION['user_id'])) {

    if (isset($_SESSION['role_id'])) {
        switch ($_SESSION['role_id']) {
            case 1:
                header("Location: super_admin/dashboard.php");
                break;
            case 2:
                header("Location: admin/dashboard.php");
                break;
            case 3:
                header("Location: regional-director/dashboard.php");
                break;
            case 4:
                header("Location: cao/dashboard.php");
                break;
            case 5:
                header("Location: collecting-officer/dashboard.php");
                break;
            case 6:
                header("Location: provincial/dashboard.php");
                break;
            default:
                # code...
                break;
        }
    }
} else {
    header("Location: login.php");
}
