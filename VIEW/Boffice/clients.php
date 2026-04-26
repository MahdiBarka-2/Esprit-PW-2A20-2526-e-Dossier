<?php
require_once '../../CONTROLLER/LanguageController.php';
require_once '../../CONTROLLER/UserController.php';
$stmt = findUsersByRole('client');
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" <?php echo ($lang === 'ar' ? 'dir="rtl"' : ''); ?>>

<head>
    <title>e_dossier - Guests</title>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="e_dossier">
    <meta name="description" content="e_dossier Management System">

    <!-- Dark mode script -->
    <script>
        const storedTheme = localStorage.getItem('theme')
        const getPreferredTheme = () => {
            if (storedTheme) return storedTheme
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
        }
        const setTheme = function (theme) {
            if (theme === 'auto') {
                document.documentElement.setAttribute('data-bs-theme', window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
            } else {
                document.documentElement.setAttribute('data-bs-theme', theme)
            }
        }
        setTheme(getPreferredTheme())
        window.addEventListener('DOMContentLoaded', () => {
            const showActiveTheme = theme => {
                const activeThemeBtn = document.querySelector(`[data-bs-theme-value="${theme}"]`)
                document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
                    element.classList.remove('active')
                })
                if (activeThemeBtn) activeThemeBtn.classList.add('active')
            }
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                if (storedTheme !== 'light' && storedTheme !== 'dark') setTheme(getPreferredTheme())
            })
            showActiveTheme(getPreferredTheme())
            document.querySelectorAll('[data-bs-theme-value]').forEach(toggle => {
                toggle.addEventListener('click', () => {
                    const theme = toggle.getAttribute('data-bs-theme-value')
                    localStorage.setItem('theme', theme)
                    setTheme(theme)
                    showActiveTheme(theme)
                })
            })
        })
    </script>

    <!-- Favicon -->
    <link rel="shortcut icon" href="../../assets/images/favicon.ico">

    <!-- Plugins CSS -->
    <link rel="stylesheet" type="text/css" href="../../assets/vendor/font-awesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="../../assets/vendor/overlay-scrollbar/css/overlayscrollbars.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/vendor/apexcharts/css/apexcharts.css">

    <!-- Theme CSS -->
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
</head>

<body>
    <main>
        <!-- Sidebar START -->
        <?php include 'sidebar.php'; ?>
        <!-- Sidebar END -->

        <!-- Page content START -->
        <div class="page-content">
            <!-- Top bar START -->
            <?php include 'topbar.php'; ?>
            <!-- Top bar END -->
            <!-- Top bar END -->

            <div class="page-content-wrapper p-xxl-4">
                <div class="row">
                    <div class="col-12 mb-4 mb-sm-5">
                        <div class="d-sm-flex justify-content-between align-items-center">
                            <h1 class="h3 mb-2 mb-sm-0">Guest List (Clients)</h1>
                            <button class="btn btn-primary mb-0" data-bs-toggle="modal" data-bs-target="#newUserModal">
                                <i class="bi bi-plus-lg fa-fw"></i> New User
                            </button>
                        </div>
                        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'duplicate'): ?>
                            <div class="alert alert-danger mt-3 py-2 small">
                                This email is already registered. Please use another email.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card shadow">
                    <div class="card-body">
                        <div class="table-responsive border-0">
                            <table class="table align-middle p-4 mb-0 table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 rounded-start"><?php echo __('name'); ?></th>
                                        <th class="border-0"><?php echo __('email'); ?></th>
                                        <th class="border-0"><?php echo __('password'); ?></th>
                                        <th class="border-0"><?php echo __('status'); ?></th>
                                        <th class="border-0"><?php echo __('cv'); ?></th>
                                        <?php if ($_SESSION['role'] === 'administrator'): ?>
                                            <th class="border-0 rounded-end">Action</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <!-- Avatar -->
                                                    <div class="avatar avatar-lg me-3" style="width: 55px; height: 55px;">
                                                        <?php if (!empty($row['profile_image_url'])): ?>
                                                            <img src="<?php echo $row['profile_image_url']; ?>"
                                                                class="rounded-circle shadow-sm" alt="" style="width: 55px; height: 55px; object-fit: cover;">
                                                        <?php else: ?>
                                                            <img src="../../assets/images/avatar/default.jpg"
                                                                class="rounded-circle shadow-sm" alt="" style="width: 55px; height: 55px; object-fit: cover;">
                                                        <?php endif; ?>
                                                    </div>
                                                    <!-- Info -->
                                                    <div>
                                                        <h6 class="mb-1"><?php echo $row['name']; ?></h6>
                                                        <span class="badge bg-primary bg-opacity-10 text-primary fw-bold">ID: #<?php echo $row['id']; ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo $row['email']; ?></td>
                                            <td>
                                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'administrator'): ?>
                                                    <h6 class="mb-0"><?php echo $row['password_plain'] ?: '********'; ?></h6>
                                                <?php else: ?>
                                                    <span class="text-muted small"><?php echo substr($row['password'], 0, 15) . '...'; ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = 'bg-success';
                                                if ($row['status'] === 'inactive') $statusClass = 'bg-danger';
                                                ?>
                                                <span class="badge <?php echo $statusClass; ?> bg-opacity-10 <?php echo str_replace('bg-', 'text-', $statusClass); ?>"><?php echo ucfirst($row['status']); ?></span>
                                            </td>
                                            <td>
                                                <?php if (!empty($row['cv_file_path'])): ?>
                                                    <a href="<?php echo $row['cv_file_path']; ?>" target="_blank"
                                                        class="btn btn-sm btn-primary-soft mb-0">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted small">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <?php if ($_SESSION['role'] === 'administrator'): ?>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-sm btn-light"
                                                            onclick="editUser(<?php echo $row['id']; ?>)">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <a href="../../CONTROLLER/UserController.php?action=delete&id=<?php echo $row['id']; ?>"
                                                            class="btn btn-sm btn-danger-soft"
                                                            onclick="return confirm('Are you sure you want to delete this client?')">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal for New User START -->
            <div class="modal fade" id="newUserModal" tabindex="-1" aria-labelledby="newUserModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="newUserModalLabel">Add New Guest</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="newUserForm" method="POST" action="../../CONTROLLER/UserController.php?action=add"
                                enctype="multipart/form-data">
                                <input type="hidden" name="role" value="client">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Enter name"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email address</label>
                                    <input type="email" name="email" class="form-control" placeholder="Enter email"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="********"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control" placeholder="+123456789">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Profile Image</label>
                                    <input type="file" name="profile_image" class="form-control" accept="image/*">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">CV Document (PDF)</label>
                                    <input type="file" name="cv_file" class="form-control" accept=".pdf">
                                </div>
                                <div class="modal-footer px-0 pb-0">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Guest</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal for New User END -->

            <!-- Modal for Edit User START -->
            <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="editUserModalLabel">Edit Guest</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editUserForm" method="POST"
                                action="../../CONTROLLER/UserController.php?action=update"
                                enctype="multipart/form-data">
                                <input type="hidden" name="id" id="edit_id">
                                <input type="hidden" name="role" id="edit_role">
                                <input type="hidden" name="existing_profile_image" id="edit_existing_profile_image">
                                <input type="hidden" name="existing_cv_file" id="edit_existing_cv_file">

                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" id="edit_name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email address</label>
                                    <input type="email" name="email" id="edit_email" class="form-control" required>
                                </div>
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'administrator'): ?>
                                    <div class="mb-3">
                                        <label class="form-label">New Password (leave blank to keep current)</label>
                                        <input type="password" name="password" id="edit_password" class="form-control" placeholder="********">
                                    </div>
                                <?php endif; ?>
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" id="edit_phone" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" id="edit_status" class="form-select" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Change Profile Image</label>
                                    <input type="file" name="profile_image" class="form-control" accept="image/*">
                                    <div id="current_image_display" class="mt-2"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Change CV Document</label>
                                    <input type="file" name="cv_file" class="form-control" accept=".pdf">
                                    <div id="current_cv_display" class="mt-2"></div>
                                </div>
                                <div class="modal-footer px-0 pb-0">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Update Guest</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal for Edit User END -->

        </div> <!-- Page content END -->
    </main>

    <!-- Bootstrap JS -->
    <script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript for Edit User -->
    <script>
        function editUser(id) {
            fetch(`../../CONTROLLER/UserController.php?action=fetch&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_id').value = data.id;
                    document.getElementById('edit_name').value = data.name;
                    document.getElementById('edit_email').value = data.email;
                    document.getElementById('edit_phone').value = data.phone;
                    document.getElementById('edit_status').value = data.status;
                    document.getElementById('edit_role').value = data.role;

                    // Hidden fields for existing files
                    document.getElementById('edit_existing_profile_image').value = data.profile_image_url || '';
                    document.getElementById('edit_existing_cv_file').value = data.cv_file_path || '';

                    // Display current image
                    const imgDisplay = document.getElementById('current_image_display');
                    if (data.profile_image_url) {
                        imgDisplay.innerHTML = `<p class="small mb-1">Current Image:</p><img src="${data.profile_image_url}" class="rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">`;
                    } else {
                        imgDisplay.innerHTML = '';
                    }

                    // Display current CV link
                    const cvDisplay = document.getElementById('current_cv_display');
                    if (data.cv_file_path) {
                        cvDisplay.innerHTML = `<a href="${data.cv_file_path}" target="_blank" class="btn btn-xs btn-outline-primary small"><i class="bi bi-file-earmark-pdf me-1"></i>View Current CV</a>`;
                    } else {
                        cvDisplay.innerHTML = '';
                    }

                    const pwdField = document.getElementById('edit_password');
                    if(pwdField) pwdField.value = '';
            
                    new bootstrap.Modal(document.getElementById('editUserModal')).show();
                })
                .catch(error => console.error('Error fetching user data:', error));
        }
    </script>

    <!-- Vendor Scripts -->
    <script src="../../assets/vendor/overlay-scrollbar/js/overlayscrollbars.min.js"></script>

    <!-- Theme Functions -->
    <script src="../../assets/js/functions.js"></script>

    <?php include 'footer.php'; ?>