
                <!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?> </h1>
    <div class="row">
        <div class="col-lg-6">
            <?= $this->session->flashdata('message'); ?>
        </div>
    </div>
    <div class="row mx-3">
        <table id="members" class="table table-responsive table-striped table-bordered" style="width:100%" >
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Email</th>
                    <th scope="col">Image</th>
                    <th scope="col">Role</th>
                    <th scope="col">Active</th>
                    <th scope="col">Date Created</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach($members as $u) : ?>
                <tr>
                    <th scope="row"><?= $i; ?></th>
                    <td><?= $u['name']; ?></td>
                    <td><?= $u['email']; ?></td>
                    <td><img src="<?= base_url('assets/profile/') . $u['image']; ?>" alt="" class="img-thumbnail" style="width: 50px;"></td>
                    <td><?= $u['role_name']; ?></td>
                    <td><?= $u['is_active'] ? 'Active' : 'Inactive'; ?></td>
                    <td><?= $u['date_created']; ?></td>
                    <td>
                        <!-- Link Edit Member -->
                        <a href="<?= base_url('admin/memberedit/') . $u['id']; ?>" class="badge badge-warning">Edit</a>
                        
                        <!-- Link Delete Member -->
                        <a href="<?= base_url('admin/deleteMember/') . $u['id']; ?>" class="badge badge-danger" onclick="return confirm('Anda yakin ingin menghapus member ini?');">Delete</a>
                    </td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        new DataTable('#members');
    });
</script>
<!-- End of Main Content -->
