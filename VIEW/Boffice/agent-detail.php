<?php
session_start();
$_SESSION['role'] = 'administrator';
require_once 'header.php';
?>

<div class="page-content-wrapper p-xxl-4">
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <h1 class="h3 mb-0">Agent Detail</h1>
        </div>
    </div>

    <div class="row g-4">
        <!-- Profile info START -->
        <div class="col-md-4 col-xxl-3">
            <div class="card bg-mode shadow p-4 text-center">
                <div class="avatar avatar-xl mb-3 mx-auto">
                    <img class="avatar-img rounded-circle" src="../../assets/images/avatar/05.jpg" alt="avatar">
                </div>
                <h5 class="mb-2">Jacqueline Miller</h5>
                <span class="badge bg-primary bg-opacity-10 text-primary mb-3">Administrator</span>
                
                <div class="text-start mt-4">
                    <h6 class="mb-3">Contact Details</h6>
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-md bg-light text-primary h6 mb-0 rounded-circle flex-shrink-0"><i class="bi bi-envelope-fill"></i></div>
                        <div class="ms-2">
                            <small class="d-block">Email id</small>
                            <h6 class="fw-normal small mb-0">hello@e-dossier.com</h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="icon-md bg-light text-primary h6 mb-0 rounded-circle flex-shrink-0"><i class="bi bi-telephone-fill"></i></div>
                        <div class="ms-2">
                            <small class="d-block">Phone</small>
                            <h6 class="fw-normal small mb-0">+1(404) 586-854</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Profile info END -->

        <!-- Details START -->
        <div class="col-md-8 col-xxl-9">
            <div class="card shadow">
                <div class="card-header border-bottom d-flex justify-content-between">
                    <h5 class="mb-0">Personal Information</h5>
                    <?php if($_SESSION['role'] === 'administrator'): ?>
                    <button class="btn btn-sm btn-primary-soft mb-0">Edit Profile</button>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small>Full Name</small>
                            <h6 class="fw-normal">Jacqueline Miller</h6>
                        </div>
                        <div class="col-md-6">
                            <small>Email Address</small>
                            <h6 class="fw-normal">hello@e-dossier.com</h6>
                        </div>
                        <div class="col-md-6">
                            <small>Mobile Number</small>
                            <h6 class="fw-normal">+1(404) 586-854</h6>
                        </div>
                        <div class="col-md-6">
                            <small>Joining Date</small>
                            <h6 class="fw-normal">29 Aug 2019</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Details END -->
    </div>
</div>

<?php require_once 'footer.php'; ?>