 <!-- Bootstrap core JavaScript-->
 <script src="<?= base_url('assets'); ?>/vendor/jquery/jquery.min.js"></script>
 <script src="<?= base_url('assets'); ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

 <!-- Core plugin JavaScript-->
 <script src="<?= base_url('assets'); ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

 <!-- Custom scripts for all pages-->
 <script src="<?= base_url('assets'); ?>/js/sb-admin-2.min.js"></script>
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
 <script>
    $(document).ready(function() {
        // Ketika Provinsi dipilih
        $('#form_prov').change(function() {
            var prov_id = $(this).val();

            if (prov_id) {
                $.ajax({
                    url: '<?= site_url("wilayah/get_kabupaten/") ?>' + prov_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        var kabupatenOptions = '<option value="">Pilih Kabupaten/Kota</option>';
                        $.each(response, function(index, kabupaten) {
                            kabupatenOptions += '<option value="' + kabupaten.kode + '">' + kabupaten.nama + '</option>';
                        });
                        $('#form_kab').html(kabupatenOptions).show();
                        $('#form_kec').hide();
                        $('#form_kel').hide();
                    }
                });
            } else {
                $('#form_kab').hide();
                $('#form_kec').hide();
                $('#form_kel').hide();
            }
        });

        // Ketika Kabupaten dipilih
        $('#form_kab').change(function() {
            var kab_id = $(this).val();

            if (kab_id) {
                $.ajax({
                    url: '<?= site_url("wilayah/get_kecamatan/") ?>' + kab_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        var kecamatanOptions = '<option value="">Pilih Kecamatan</option>';
                        $.each(response, function(index, kecamatan) {
                            kecamatanOptions += '<option value="' + kecamatan.kode + '">' + kecamatan.nama + '</option>';
                        });
                        $('#form_kec').html(kecamatanOptions).show();
                        $('#form_kel').hide();
                    }
                });
            } else {
                $('#form_kec').hide();
                $('#form_kel').hide();
            }
        });

        // Ketika Kecamatan dipilih
        $('#form_kec').change(function() {
            var kec_id = $(this).val();

            if (kec_id) {
                $.ajax({
                    url: '<?= site_url("wilayah/get_kelurahan/") ?>' + kec_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        var kelurahanOptions = '<option value="">Pilih Kelurahan</option>';
                        $.each(response, function(index, kelurahan) {
                            kelurahanOptions += '<option value="' + kelurahan.kode + '">' + kelurahan.nama + '</option>';
                        });
                        $('#form_kel').html(kelurahanOptions).show();
                    }
                });
            } else {
                $('#form_kel').hide();
            }
        });
    });
</script>
<script>
    $('#kirim').on('submit', function(event) {
    event.preventDefault(); // Mencegah submit form default
    
    var alamatLengkap = $('#alamat').val();
    var provinsi = $('#form_prov').val();
    var kabupaten = $('#form_kab').val();
    var kecamatan = $('#form_kec').val();
    var desa = $('#form_des').val();
    
    // Gabungkan data alamat dan wilayah
    var alamatGabungan = alamatLengkap +', kel. '+desa+', kec. '+ kecamatan +', kab.'+ kabupaten+', kota.'+ provinsi;
    // if (desa) alamatGabungan += ', ' + desa;
    // if (kecamatan) alamatGabungan += ', ' + kecamatan;
    // if (kabupaten) alamatGabungan += ', ' + kabupaten;
    // if (provinsi) alamatGabungan += ', ' + provinsi;

    var name = $('#name').val();
    var email = $('#email').val();
    var phone = $('#phone').val();
    var ktp = $('#ktp').val();
    var dob = $('#dob').val();
    var job = $('#job').val();
    var gender = $('#gender').val();
    var education = $('#education').val();
    var password = $('#password1').val();


    // Set nilai gabungan ke address
    $('#address').val(alamatGabungan);

    console.log('klik berhasil')

    // Kirim data dengan AJAX
    $.ajax({
        url: "<?= base_url('auth/registration'); ?>", // URL pengiriman form
        type: "POST",
        data: {
            name: name,
            email: email,
            phone: phone,
            ktp: ktp,
            dob: dob,
            job: job,
            gender: gender,
            education: education,
            address: alamatGabungan, // Kirim alamat gabungan
            password1: password
        },
        success: function(result) {
            console.log(result);
            alert("Pendaftaran berhasil!");
        },
        error: function(xhr, status, error) {
            alert("Terjadi kesalahan: " + error);
        }
    });
});

</script>
<script>
    $(function() {
        $("#dob").datepicker({
            dateFormat: "dd/mm/yy", // Format Indonesia
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+0", // Rentang tahun: 100 tahun ke belakang hingga tahun sekarang
            maxDate: new Date() // Tidak bisa memilih tanggal di masa depan
        });
    });
</script>
 </body>

 </html>