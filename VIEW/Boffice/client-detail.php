<?php
session_start();
$_SESSION['role'] = 'administrator';
require_once 'header.php';
?>

<div class="page-content-wrapper p-xxl-4">
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <h1 class="h3 mb-0">Guest Detail</h1>
        </div>
    </div>

    <div class="row g-4">
        <!-- Profile info START -->
        <div class="col-md-4 col-xxl-3">
            <div class="card bg-mode shadow p-4 text-center h-100">
                <div class="avatar avatar-xl mb-3 mx-auto">
                    <img class="avatar-img rounded-circle" src="../../assets/images/avatar/01.jpg" alt="avatar">
                </div>
                <h5 class="mb-2">Lori Ferguson</h5>
                <span class="badge bg-success bg-opacity-10 text-success mb-3">Active Guest</span>
                
                <div class="text-start mt-4">
                    <h6 class="mb-3">Contact Details</h6>
                    <ul class="list-group list-group-borderless small">
                        <li class="list-group-item d-flex align-items-center mb-2">
                            <i class="bi bi-envelope-fill me-2 text-primary"></i> lori@example.com
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <i class="bi bi-telephone-fill me-2 text-primary"></i> +1(404) 586-854
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Profile info END -->

        <!-- Details START -->
        <div class="col-md-8 col-xxl-9">
            <div class="card shadow">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">Dossier Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <ul class="list-group list-group-borderless">
                                <li class="list-group-item">
                                    <small class="d-block text-muted">Total Dossiers Created</small>
                                    <h6 class="fw-normal">14</h6>
                                </li>
                                <li class="list-group-item mt-3">
                                    <small class="d-block text-muted">Last Activity</small>
                                    <h6 class="fw-normal">12 April 2026 - 02:45 PM</h6>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <button class="btn btn-primary mb-0"><i class="bi bi-file-earmark-plus me-2"></i>New Dossier</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>