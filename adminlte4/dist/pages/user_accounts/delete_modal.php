<!-- Delete Modal -->
<div class="modal fade" id="deleteUserModal<?= $row['user_id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Hapus Pengguna</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">    
                <p>Apakah kamu ingin menghapus akun ini?</p>
                <h4 class="fw-bold"><?php echo htmlspecialchars($row['complete_name']); ?></h4>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <a href="function/deleteUser.php?id=<?php echo $row['user_id']; ?>" class="btn btn-danger">Iya, Hapus</a>
            </div>
        </div>
    </div>
</div>
