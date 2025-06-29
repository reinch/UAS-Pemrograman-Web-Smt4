<!-- Change Username and Password Modal -->
<div class="modal fade" id="changeCredentialModal<?= $row['user_id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Akun</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="function/updateCredentials.php">
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($row['user_id']); ?>">
                    <h5 class="fw-bold"><?= htmlspecialchars($row['complete_name']); ?></h5>
                    <div class="mb-3">
                        <label for="new_username_<?= $row['user_id']; ?>" class="form-label">Username Baru</label>
                        <input type="text" class="form-control" id="new_username_<?= $row['user_id']; ?>" 
                               name="new_username" value="<?= htmlspecialchars($row['username']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password_<?= $row['user_id']; ?>" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="new_password_<?= $row['user_id']; ?>" 
                               name="new_password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password_<?= $row['user_id']; ?>" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="confirm_password_<?= $row['user_id']; ?>" 
                               name="confirm_password" required>
                    </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" name="change_credentials" class="btn btn-info">Update</button>
            </div>
                </form>
        </div>
    </div>
</div>
