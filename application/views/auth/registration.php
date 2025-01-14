<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5 col-lg-7 mx-auto">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Buat Akun Mu!</h1>
                        </div>
                        <form class="user" method="POST"id="registrationForm" action="<?= base_url('auth/registration'); ?>">
                        <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="name"
                                    placeholder="Full Name" name="name" value="<?= set_value('name'); ?>">
                                <?= form_error('name', '<small class="text-danger pl-3">','</small>' ); ?>
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="email"
                                    placeholder="Email Address" name="email" value="<?= set_value('email'); ?>">
                                <?= form_error('email', '<small class="text-danger pl-3">','</small>' ); ?>
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
                                    placeholder="Tanggal Lahir" value="<?= set_value('dob'); ?>">
                                <?= form_error('dob', '<small class="text-danger pl-3">','</small>' ); ?>
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="job"
                                    placeholder="Pekerjaan" name="job" value="<?= set_value('job'); ?>">
                                <?= form_error('job', '<small class="text-danger pl-3">','</small>' ); ?>
                            </div>

                            <!-- Wilayah Dropdown -->
                            <div class="form-group">
                                <select id="form_prov" class="form-control" name="provinsi">
                                    <option value="">Pilih Provinsi</option>
                                    <?php foreach ($daerah as $prov) : ?>
                                        <option value="<?= $prov['kode']; ?>"><?= $prov['nama']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <select id="form_kab" class="form-control" name="kabupaten" style="display: none;">
                                    <option value="">Pilih Kabupaten/Kota</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <select id="form_kec" class="form-control" name="kecamatan" style="display: none;">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <select id="form_kel" class="form-control" name="kelurahan" style="display: none;">
                                    <option value="">Pilih Kelurahan</option>
                                </select>
                            </div>
                            <!-- End Wilayah Dropdown -->

                            <!-- Additional Fields -->
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="alamat"
                                    placeholder="Alamat Lengkap (Jalan, RT/RW, No. Rumah)" name="alamat"
                                    value="<?= set_value('alamat'); ?>">
                                <?= form_error('alamat', '<small class="text-danger pl-3">','</small>' ); ?>
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
                            <!-- End Additional Fields -->

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user" id="password1"
                                        placeholder="Password" name="password1">
                                    <?= form_error('password1', '<small class="text-danger pl-3">','</small>' ); ?>
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user" id="password2"
                                        placeholder="Repeat Password" name="password2">
                                </div>
                            </div>

                            <button id="kirim" type="submit" class="btn btn-primary btn-user btn-block" method="POST" action="<?= base_url('auth/registration'); ?>">
                                Register Akun
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
