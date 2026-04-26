<?php
session_start();
// This is user-level settings (can be agent or client or admin)
require_once 'header.php';
?>

<div class="page-content-wrapper p-xxl-4">
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <h1 class="h3 mb-0">Account Settings</h1>
        </div>
    </div>

    <div class="row g-4">
        <!-- Personal Info -->
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header border-bottom">
                    <h5 class="card-header-title">Personal Information</h5>
                </div>
                <div class="card-body">
                    <form class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" value="Administrator">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" value="admin@e-dossier.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" placeholder="Change password">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control" value="+123456789">
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary mb-0">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>