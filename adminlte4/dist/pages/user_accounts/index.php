<?php include '../includes/auth.php'; ?>
<?php include '../includes/config.php'; // Database connection ?>

<!doctype html>
<html lang="en">
  <?php include '../includes/header.php'; ?>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <?php include '../includes/navbar.php'; ?>
      <!--end::Header-->
      <!--begin::Sidebar-->
      <?php include '../includes/sidebar.php'; ?>
      <!--end::Sidebar-->
      <!--begin::App Main-->
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Pusat Akun</h3></div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <?php include 'add_modal.php'; ?>
            <?php include '../includes/success_message.php'; ?>
            <?php include '../includes/error_message.php'; ?>

            <div class="row">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <a href="#addnew" data-bs-toggle="modal" class="btn btn-primary">Tambah Pengguna</a>
                  </div>
                  <div class="card-body">
                    <table id="userTable" class="table table-striped">
                      <thead>
                        <tr>
                          <th>Profil</th>
                          <th>Nama Lengkap</th>
                          <th>Username</th>
                          <th>Tujuan</th>
                          <th>Tipe Pengguna</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $db = Database::getInstance()->getConnection();
                        $stmt = $db->query("SELECT * FROM tbl_user");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
                          $userId = htmlspecialchars($row['user_id']); // Secure output
                        ?>
                          <tr>
                            <td>
                              <a href="#updateImageModal<?= $userId; ?>" data-bs-toggle="modal">
                                <img src="uploads/<?= htmlspecialchars($row['profile_image']); ?>" 
                                     width="40" height="40" 
                                     class="rounded-circle" 
                                     alt="<?= htmlspecialchars($row['complete_name']); ?>" />
                              </a>
                            </td>
                            <td><?= htmlspecialchars($row['complete_name']); ?></td>
                            <td><?= htmlspecialchars($row['username']); ?></td>
                            <td><?= htmlspecialchars($row['designation']); ?></td>
                            <td><?= htmlspecialchars($row['user_type']); ?></td>
                            <td>
                              <a href="#editUserModal<?= $userId; ?>" data-bs-toggle="modal" class="btn btn-sm btn-warning">Edit</a>
                              <a href="#deleteUserModal<?= $userId; ?>" data-bs-toggle="modal" class="btn btn-sm btn-danger">Hapus</a>
                              <a href="#changeCredentialModal<?= $userId; ?>" data-bs-toggle="modal" class="btn btn-sm btn-info">Info Login</a>
                            </td>
                          </tr>

                          <!-- Include unique modals for each user -->
                          <?php include 'edit_modal.php'; ?>
                          <?php include 'delete_modal.php'; ?>
                          <?php include 'change_credential_modal.php'; ?>  
                          <?php include 'update_image_modal.php'; ?>  

                        <?php endwhile; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </main>

      <?php include '../includes/footer.php'; ?>

    </div>

    <?php include '../includes/script.php'; ?>

    <script>
      $(document).ready(function() {
        $('#userTable').DataTable({
          "paging": true,
          "searching": true,
          "lengthChange": true,
          "info": true
        });
      });
    </script>
  </body>
</html>
