<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5 col-lg-7 mx-auto">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                        </div>
                        <form class="user" method="post" action="<?= base_url('auth/registration'); ?>">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="name" name="name" placeholder="Full Name" value="<?= set_value('name'); ?>">
                                <?= form_error('name', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Email Address" value="<?= set_value('email'); ?>">
                                <?= form_error('email', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="phone"
                                    placeholder="Nomor Telepon" name="phone" value="<?= set_value('phone'); ?>">
                                <?= form_error('phone', '<small class="text-danger pl-3">','</small>' ); ?>
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="ktp"
                                    placeholder="Nomor KTP" name="ktp" value="<?= set_value('ktp'); ?>">
                                <?= form_error('ktp', '<small class="text-danger pl-3">','</small>' ); ?>
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="dob" name="dob"
                                    placeholder="Tanggal Lahir" value="<?= set_value('dob', isset($dob_display) ? $dob_display : ''); ?>">
                                <?= form_error('dob', '<small class="text-danger pl-3">','</small>' ); ?>
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="job"
                                    placeholder="Pekerjaan" name="job" value="<?= set_value('job'); ?>">
                                <?= form_error('job', '<small class="text-danger pl-3">','</small>' ); ?>
                            </div>
                            <div class="form-group">
                                <select id="gender" class="form-control" name="gender">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" <?= set_value('gender') == 'L' ? 'selected' : ''; ?>>Laki-laki</option>
                                    <option value="P" <?= set_value('gender') == 'P' ? 'selected' : ''; ?>>Perempuan</option>
                                </select>
                                <?= form_error('gender', '<small class="text-danger pl-3">','</small>' ); ?>
                            </div>

                            <div class="form-group">
                                <select id="education" class="form-control" name="education">
                                    <option value="">Pilih Pendidikan Terakhir</option>
                                    <option value="SD" <?= set_value('education') == 'SD' ? 'selected' : ''; ?>>SD</option>
                                    <option value="SMP" <?= set_value('education') == 'SMP' ? 'selected' : ''; ?>>SMP</option>
                                    <option value="SMA" <?= set_value('education') == 'SMA' ? 'selected' : ''; ?>>SMA</option>
                                    <option value="D3" <?= set_value('education') == 'D3' ? 'selected' : ''; ?>>D3</option>
                                    <option value="S1" <?= set_value('education') == 'S1' ? 'selected' : ''; ?>>S1</option>
                                    <option value="S2" <?= set_value('education') == 'S2' ? 'selected' : ''; ?>>S2</option>
                                    <option value="S3" <?= set_value('education') == 'S3' ? 'selected' : ''; ?>>S3</option>
                                </select>
                                <?= form_error('education', '<small class="text-danger pl-3">','</small>' ); ?>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <select name="province_id" id="province_id" class="form-control">
                                        <option value="">Pilih Provinsi</option>
                                        <?php foreach ($provinces as $p) : ?>
                                            <option value="<?= $p['id']; ?>"><?= $p['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <select name="regency_id" id="regency_id" class="form-control">
                                        <option value="">Pilih Kabupaten</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <select name="district_id" id="district_id" class="form-control">
                                        <option value="">Pilih Kelurahan</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <select name="village_id" id="village_id" class="form-control">
                                        <option value="">Pilih Kecamatan/Desa</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="address"
                                    placeholder="Alamat Lengkap (Jalan, RT/RW, No. Rumah)" name="address"
                                    value="<?= set_value('address'); ?>">
                                <?= form_error('address', '<small class="text-danger pl-3">','</small>' ); ?>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user" id="password1" name="password1" placeholder="Password">
                                    <?= form_error('password1', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user" id="password2" name="password2" placeholder="Repeat Password">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Register Account
                            </button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small" href="<?= base_url('auth/forgotpassword'); ?>">Lupa Password?</a>
                        </div>
                        <div class="text-center">
                            <a class="small" href="<?= base_url('auth'); ?>">Sudah Punya Akun? Masuk!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
