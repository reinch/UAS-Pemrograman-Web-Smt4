<div class="modal fade" id="addnew" tabindex="-1" aria-labelledby="addnewLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addnewLabel">Tambah Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="function/addUser.php" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="complete_name" class="form-label">Nama Lengkap</label>
                                <input class="form-control" type="text" id="complete_name" name="complete_name" required>
                                <div id="response"></div>
                            </div>
                            <div class="mb-3">
                                <label for="designation" class="form-label">Tujuan</label>
                                <textarea class="form-control" id="designation" name="designation" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="user_type" class="form-label">Tipe Pengguna</label>
                                <select class="form-select" id="user_type" name="user_type" required>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input class="form-control" type="text" id="username" name="username" required onkeyup="checkDuplicateUsername()">
                                <small id="usernameResponse" class="text-danger"></small>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="profile_image" class="form-label">Foto Profil (MAX 2MB)</label>
                                <input class="form-control" type="file" id="profile_image" name="profile_image" required accept="image/*" onchange="previewImage()">
                                <img id="imagePreview" src="#" alt="Image Preview" style="max-width: 100%; display: none;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="add_button" name="add" class="btn btn-primary" disabled>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Real-time username validation
    function checkDuplicateUsername() {
        var username = document.getElementById('username').value.trim();
        var usernameResponse = document.getElementById('usernameResponse');
        var addButton = document.getElementById('add_button');

        if (username !== '') {
            fetch('function/checkDuplicate.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'username=' + encodeURIComponent(username)
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'exists') {
                    usernameResponse.textContent = 'Username already exists. Please choose another.';
                    addButton.disabled = true; // Disable the submit button
                } else {
                    usernameResponse.textContent = '';
                    addButton.disabled = false; // Enable the submit button
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        } else {
            usernameResponse.textContent = '';
            addButton.disabled = true; // Keep disabled if username is empty
        }
    }

    // Image preview
    function previewImage() {
        var fileInput = document.getElementById('profile_image');
        var imagePreview = document.getElementById('imagePreview');

        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            }

            reader.readAsDataURL(fileInput.files[0]);
        }
    }
</script>
