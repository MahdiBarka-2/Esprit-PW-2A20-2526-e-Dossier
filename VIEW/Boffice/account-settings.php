<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../../CONTROLLER/UserCONTROLLER.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: sign-in.php");
    exit();
}

$user = findUserById($_SESSION['user_id']);
require_once 'header.php';
?>

<div class="page-content-wrapper p-xxl-4">
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <h1 class="h3 mb-0">Account Settings</h1>
            <p class="text-muted">Manage your personal information and profile preferences.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Personal Info -->
        <div class="col-lg-8">
            <div class="card shadow border-0">
                <div class="card-header border-bottom bg-transparent">
                    <h5 class="card-header-title mb-0"><i class="bi bi-person-circle me-2 text-primary"></i>Personal Information</h5>
                </div>
                <div class="card-body">
                    <form class="row g-3" action="../../CONTROLLER/UserCONTROLLER.php?action=edit" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                        <input type="hidden" name="role" value="<?php echo $user['role']; ?>">
                        <input type="hidden" name="status" value="<?php echo $user['status']; ?>">
                        <input type="hidden" name="source" value="backoffice">

                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Age</label>
                            <input type="number" name="age" class="form-control" value="<?php echo htmlspecialchars($user['age']); ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Password (leave blank to keep current)</label>
                            <input type="password" name="password" class="form-control" placeholder="New password">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Update Profile Image</label>
                            <input type="file" name="profile_image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Update CV (PDF)</label>
                            <input type="file" name="cv_file" class="form-control" accept=".pdf">
                        </div>
                        <div class="col-12 text-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Profile Preview -->
        <div class="col-lg-4">
            <div class="card shadow border-0 text-center p-4">
                <div class="avatar avatar-xxl mb-3 mx-auto">
                    <?php 
                    $profile_img = (!empty($user['profile_image_url'])) 
                                    ? $user['profile_image_url'] 
                                    : '../../assets/images/avatar/01.jpg';
                    ?>
                    <img class="avatar-img rounded-circle border border-3 border-primary" src="<?php echo $profile_img; ?>" alt="avatar" style="width: 120px; height: 120px; object-fit: cover;">
                </div>
                <h5 class="mb-1"><?php echo htmlspecialchars($user['name']); ?></h5>
                <p class="mb-2 text-primary fw-bold text-uppercase small"><?php echo $user['role']; ?></p>
                <div class="d-grid gap-2 mt-3">
                    <?php if (!empty($user['cv_file_path'])): ?>
                        <a href="<?php echo $user['cv_file_path']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-file-earmark-pdf me-1"></i> View Current CV
                        </a>
                    <?php endif; ?>
                    <a href="logout.php" class="btn btn-sm btn-danger-soft">
                        <i class="bi bi-power me-1"></i> Sign Out
                    </a>
                </div>
                <hr>
                <div class="text-start">
                    <p class="small mb-1 text-muted">Account Details</p>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><i class="bi bi-calendar3 me-2"></i>Joined: <?php echo date('M d, Y', strtotime($user['created_at'])); ?></li>
                        <li class="mb-2"><i class="bi bi-shield-check me-2 text-success"></i>Status: <?php echo ucfirst($user['status']); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
