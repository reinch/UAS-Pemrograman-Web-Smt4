<!-- Upload and Update Profile Picture Modal -->
<div class="modal fade" id="updateImageModal<?= $row['user_id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload atau Update Foto Profile</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="function/uploadProfileImage.php" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                    <h5 class="fw-bold"><?= htmlspecialchars($row['complete_name']); ?></h5> 
                    <div class="mb-3">
                        <label for="profile_image_<?php echo $row['user_id']; ?>" class="form-label">Pilih Foto Profile</label>
                        <input type="file" class="form-control" id="profile_image_<?php echo $row['user_id']; ?>" name="profile_image" accept="image/*" required>
                        
                        <div class="mt-3">
                            <img id="preview_<?php echo $row['user_id']; ?>" src="<?php echo !empty($row['profile_image']) ? 'uploads/' . htmlspecialchars($row['profile_image']) : 'assets/img/default.png'; ?>" 
                                alt="Profile Preview" class="img-thumbnail" style="max-width: 150px; display: block;">
                        </div>
                    </div>
            </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="upload_profile_image" class="btn btn-primary">Update Foto Profile</button>
                    </div>
                </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("profile_image_<?php echo $row['user_id']; ?>").addEventListener("change", function(event) {
            var preview = document.getElementById("preview_<?php echo $row['user_id']; ?>");
            var file = event.target.files[0];
            var reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        });
    });
</script>
