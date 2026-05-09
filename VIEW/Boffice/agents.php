<?php
require_once '../../CONTROLLER/UserCONTROLLER.php';
$stmt = findUsersByRole('agent');
include 'header.php';
?>

<div class="page-content-wrapper p-xxl-4">
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <div class="d-sm-flex justify-content-between align-items-center">
                <h1 class="h3 mb-2 mb-sm-0">Agent List</h1>
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
            <!-- Search and Sort Controls -->
            <div class="row g-3 align-items-center mb-4">
                <div class="col-md-2">
                    <select id="sortOrder" class="form-select form-select-sm">
                        <option value="ASC"><?php echo __('sort_asc'); ?></option>
                        <option value="DESC"><?php echo __('sort_desc'); ?></option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" id="sortBy" class="form-control form-control-sm" placeholder="<?php echo __('sort_by'); ?>">
                </div>
                <div class="col-md-7 text-md-end">
                    <button id="exportPdf" class="btn btn-sm btn-danger-soft mb-0">
                        <i class="bi bi-file-earmark-pdf-fill me-1"></i><?php echo __('export_pdf'); ?>
                    </button>
                </div>
            </div>

            <div class="table-responsive border-0">
                <table id="agentTable" class="table align-middle p-4 mb-0 table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 rounded-start" data-column="name"><?php echo __('name'); ?></th>
                            <th class="border-0" data-column="email"><?php echo __('email'); ?></th>
                            <th class="border-0" data-column="password"><?php echo __('password'); ?></th>
                            <th class="border-0" data-column="role">Role</th>
                            <th class="border-0" data-column="cv"><?php echo __('cv'); ?></th>
                            <?php if ($_SESSION['role'] === 'administrator'): ?>
                                <th class="border-0 rounded-end" data-export="false">Action</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-lg me-3" style="width: 55px; height: 55px;">
                                            <?php if (!empty($row['profile_image_url'])): ?>
                                                <img src="<?php echo $row['profile_image_url']; ?>"
                                                    class="rounded-circle shadow-sm" alt="" style="width: 55px; height: 55px; object-fit: cover;">
                                            <?php else: ?>
                                                <img src="../../assets/images/avatar/default.jpg"
                                                    class="rounded-circle shadow-sm" alt="" style="width: 55px; height: 55px; object-fit: cover;">
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <h6 class="mb-1"><?php echo $row['name']; ?></h6>
                                            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold">ID: #<?php echo $row['id']; ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo $row['email']; ?></td>
                                <td>
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'administrator'): ?>
                                        <h6 class="mb-0 text-primary"><?php echo $row['password_plain'] ?: '********'; ?></h6>
                                        <span class="text-muted small" style="font-size: 0.7rem;"><?php echo substr($row['password_hash'] ?? '', 0, 20) . '...'; ?></span>
                                    <?php else: ?>
                                        <span class="text-muted small"><?php echo substr($row['password_hash'] ?? '********', 0, 15) . '...'; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $color = ($row['role'] === 'administrator') ? 'danger' : 'info';
                                    ?>
                                    <span class="badge bg-<?php echo $color; ?> bg-opacity-10 text-<?php echo $color; ?>"><?php echo ucfirst($row['role']); ?></span>
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
                                    <td data-export="false">
                                        <div class="d-flex gap-2">
                                            <!-- Tracker Trigger -->
                                            <button class="btn btn-sm btn-info text-white" onclick="openTracker(<?php echo $row['id']; ?>, '<?php echo addslashes($row['name']); ?>')">
                                                <i class="bi bi-geo-alt-fill"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light"
                                                onclick="editUser(<?php echo $row['id']; ?>)">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <a href="../../CONTROLLER/UserCONTROLLER.php?action=delete&id=<?php echo $row['id']; ?>"
                                                class="btn btn-sm btn-danger-soft"
                                                onclick="return confirm('Are you sure you want to delete this agent?')">
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

<!-- Modal for New User -->
<div class="modal fade" id="newUserModal" tabindex="-1" aria-labelledby="newUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="newUserModalLabel">Add New Agent</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="newUserForm" method="POST" action="../../CONTROLLER/UserCONTROLLER.php?action=add" enctype="multipart/form-data">
                    <input type="hidden" name="role" value="agent">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="********" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="+123456789">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Age</label>
                        <input type="number" name="age" class="form-control" placeholder="Enter age" min="1" max="120">
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Agent</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Edit User -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="editUserModalLabel">Edit Agent</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" method="POST" action="../../CONTROLLER/UserCONTROLLER.php?action=update" enctype="multipart/form-data">
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
                    <div class="mb-3">
                        <label class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" id="edit_password" class="form-control" placeholder="********">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" id="edit_phone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Age</label>
                        <input type="number" name="age" id="edit_age" class="form-control" min="1" max="120">
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Agent</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- html2pdf Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    function editUser(id) {
        fetch(`../../CONTROLLER/UserCONTROLLER.php?action=fetch&id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_id').value = data.id;
                document.getElementById('edit_name').value = data.name;
                document.getElementById('edit_email').value = data.email;
                document.getElementById('edit_role').value = data.role;
                document.getElementById('edit_phone').value = data.phone;
                document.getElementById('edit_age').value = data.age || '';
                document.getElementById('edit_status').value = data.status;
                document.getElementById('edit_existing_profile_image').value = data.profile_image_url || '';
                document.getElementById('edit_existing_cv_file').value = data.cv_file_path || '';

                const imgDisplay = document.getElementById('current_image_display');
                if (data.profile_image_url) {
                    imgDisplay.innerHTML = `<p class="small mb-1">Current Image:</p><img src="${data.profile_image_url}" class="rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">`;
                } else {
                    imgDisplay.innerHTML = '';
                }

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

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('globalSearch');
        const table = document.getElementById('agentTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        const sortByInput = document.getElementById('sortBy');
        const sortOrderSelect = document.getElementById('sortOrder');
        const exportPdfBtn = document.getElementById('exportPdf');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const filter = searchInput.value.toLowerCase().trim();
                for (let i = 0; i < rows.length; i++) {
                    const name = rows[i].cells[0].innerText.toLowerCase();
                    const email = rows[i].cells[1].innerText.toLowerCase();
                    if (filter === "" || name.indexOf(filter) > -1 || email.indexOf(filter) > -1) {
                        rows[i].style.display = "";
                    } else {
                        rows[i].style.display = "none";
                    }
                }
            });
        }

        const performSort = function() {
            const columnText = sortByInput.value.toLowerCase().trim();
            const order = sortOrderSelect.value;
            if (columnText === "") return;
            let columnIndex = -1;
            const headers = table.querySelectorAll('thead th');
            headers.forEach((th, index) => {
                const headerText = th.innerText.toLowerCase();
                const columnAttr = th.getAttribute('data-column') ? th.getAttribute('data-column').toLowerCase() : '';
                if (headerText.includes(columnText) || columnAttr.includes(columnText) || (columnText === 'id' && index === 0)) {
                    columnIndex = index;
                }
            });
            if (columnIndex === -1) return;
            const tbody = table.querySelector('tbody');
            const rowsArray = Array.from(tbody.querySelectorAll('tr'));
            rowsArray.sort((a, b) => {
                let aText = a.cells[columnIndex].innerText.trim();
                let bText = b.cells[columnIndex].innerText.trim();
                if (columnIndex === 0) {
                    const aMatch = aText.match(/ID: #(\d+)/);
                    const bMatch = bText.match(/ID: #(\d+)/);
                    if (aMatch && bMatch) {
                        aText = aMatch[1];
                        bText = bMatch[1];
                        return order === 'ASC' ? parseInt(aText) - parseInt(bText) : parseInt(bText) - parseInt(aText);
                    }
                }
                if (!isNaN(aText) && !isNaN(bText) && aText !== '' && bText !== '') {
                    return order === 'ASC' ? parseFloat(aText) - parseFloat(bText) : parseFloat(bText) - parseFloat(aText);
                }
                return order === 'ASC' ? aText.localeCompare(bText) : bText.localeCompare(aText);
            });
            rowsArray.forEach(row => tbody.appendChild(row));
        };

        sortByInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSort();
            }
        });
        sortOrderSelect.addEventListener('change', performSort);

        exportPdfBtn.addEventListener('click', function() {
            const element = document.createElement('div');
            const tableClone = table.cloneNode(true);
            tableClone.querySelectorAll('[data-export="false"]').forEach(el => el.remove());
            element.innerHTML = `
                <div style="padding: 20px;">
                    <h2 style="text-align: center; color: #066ac9;">Agent List - E-Dossier</h2>
                    <hr>
                    ${tableClone.outerHTML}
                </div>
            `;
            const opt = {
                margin: [10, 10],
                filename: 'agents_list.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
            };
            html2pdf().set(opt).from(element).save();
        });
    });
</script>

<?php
require_once '../../CONTROLLER/LocationCONTROLLER.php';
echo renderGPSTracker();
include 'footer.php';
?>
