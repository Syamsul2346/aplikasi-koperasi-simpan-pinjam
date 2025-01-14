               <!-- Begin Page Content -->
               <div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800"><?= $title; ?> </h1>
<div>
    <div class="col-lg-8">
      
    <?= form_open_multipart('admin/memberedit/'.$data_user['id']); ?>

    <!-- Input ID User untuk Proses Update -->
    <input type="hidden" name="id" value="<?= $data_user['id']; ?>">

        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="email" name="email" value="<?= $data_user['email']; ?>" readonly>
                </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">Registered</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" value="<?= $data_user['date_created']; ?>" readonly>
                </div>
        </div>
        <div class="form-group row">
            <label for="is_active" class="col-sm-2 col-form-label">Aktivasi</label>
            <div class="col-sm-10">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                        <?= $data_user['is_active'] ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Full Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name="name" value="<?= $data_user['name']; ?>">
                    <?= form_error('name', '<small class="text-danger pl-3">','</small>' ); ?>
                </div>
        </div>
        <div class="form-group row">
            <label for="role" class="col-sm-2 col-form-label">Role</label>
            <div class="col-sm-10">
                <?php foreach ($roles as $role): ?>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="role_<?= $role['id']; ?>" 
                            name="role_id" value="<?= $role['id']; ?>" 
                            <?= $data_user['role_id'] == $role['id'] ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="role_<?= $role['id']; ?>">
                            <?= $role['role']; ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-2">Picture</div>
            <div class="col-sm-10">
                <div class="row">
                <div class="col-sm-3">
                    <img src="<?= base_url('assets/profile/') . $data_user['image']; ?>" alt="" class="img-thumbnail">
                </div>
                <div class="col-sm-9">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="image" name="image">
                        <label class="custom-file-label" for="image">Choose file</label>
                    </div>
                </div>
                </div>
            </div>
        </div>

    </div>
    <div class="form-group row justify-content-end">
        <div class="col-sm-10">
            <button type="submit" class="btn btn-primary">Edit</button>
        </div>
    </div>
    <?= form_close(); ?>
</div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

