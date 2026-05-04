<?php
session_start();
// Security Check: Only Administrators can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'administrator') {
    header("Location: index.php?error=unauthorized");
    exit();
}
require_once 'header.php';
?>

<div class="page-content-wrapper p-xxl-4">
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <h1 class="h3 mb-0">Admin Settings</h1>
        </div>
    </div>

    <div class="row g-4">
        <!-- Site Settings -->
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header border-bottom">
                    <h5 class="card-header-title">General Settings</h5>
                </div>
                <div class="card-body">
                    <form class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" class="form-control" value="E-Dossier">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Email</label>
                            <input type="email" class="form-control" value="contact@e-dossier.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">System Mode</label>
                            <select class="form-select">
                                <option selected>Production</option>
                                <option>Maintenance</option>
                                <option>Development</option>
                            </select>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary mb-0">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Role Permissions -->
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header border-bottom">
                    <h5 class="card-header-title">Role Permissions</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive border-0">
                        <table class="table align-middle p-4 mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 rounded-start">Feature</th>
                                    <th class="border-0 text-center">Admin</th>
                                    <th class="border-0 text-center">Employee</th>
                                    <th class="border-0 rounded-end text-center">Client</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Manage Users</td>
                                    <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                                    <td class="text-center"><i class="bi bi-x-circle-fill text-danger"></i></td>
                                    <td class="text-center"><i class="bi bi-x-circle-fill text-danger"></i></td>
                                </tr>
                                <tr>
                                    <td>Edit Dossiers</td>
                                    <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                                    <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                                    <td class="text-center"><i class="bi bi-x-circle-fill text-danger"></i></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>