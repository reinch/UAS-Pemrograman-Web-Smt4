<div class="modal fade" id="editUserModal<?= $row['user_id']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editUserLabel<?= $row['user_id']; ?>">Edit Pengguna</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="function/editUser.php">
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($row['user_id']); ?>">
                    
                    <div class="mb-3">
                        <label for="edit_complete_name_<?= $row['user_id']; ?>" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="edit_complete_name_<?= $row['user_id']; ?>" 
                               name="complete_name" value="<?= htmlspecialchars($row['complete_name']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_designation_<?= $row['user_id']; ?>" class="form-label">Tujuan</label>
                        <textarea class="form-control" id="edit_designation_<?= $row['user_id']; ?>" 
                                  name="designation" required><?= htmlspecialchars($row['designation']); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_user_type_<?= $row['user_id']; ?>" class="form-label">Tipe Pengguna</label>
                        <select class="form-select" id="edit_user_type_<?= $row['user_id']; ?>" name="user_type" required>
                            <option value="admin" <?= ($row['user_type'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="user" <?= ($row['user_type'] == 'user') ? 'selected' : ''; ?>>User</option>
                        </select>
                    </div>
                </div> 

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="edit" class="btn btn-warning">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
