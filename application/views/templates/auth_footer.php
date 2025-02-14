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
$(document).ready(function () {
    $('#province_id').on('change', function () {
        var province_id = $(this).val();
        if (province_id) {
            $.ajax({
                url: '<?= base_url('wilayah/get_regencies'); ?>', // Sesuaikan URL dengan controller 'wilayah'
                method: 'GET',
                data: { province_id: province_id },
                success: function (data) {
                    var regencies = JSON.parse(data);
                    $('#regency_id').html('<option value="">Select Regency</option>');
                    regencies.forEach(function (regency) {
                        $('#regency_id').append('<option value="' + regency.id + '">' + regency.name + '</option>');
                    });
                }
            });
        } else {
            $('#regency_id').html('<option value="">Select Regency</option>');
        }
    });

    $('#regency_id').on('change', function () {
        var regency_id = $(this).val();
        if (regency_id) {
            $.ajax({
                url: '<?= base_url('wilayah/get_districts'); ?>',
                method: 'GET',
                data: { regency_id: regency_id },
                success: function (data) {
                    var districts = JSON.parse(data);
                    $('#district_id').html('<option value="">Select District</option>');
                    districts.forEach(function (district) {
                        $('#district_id').append('<option value="' + district.id + '">' + district.name + '</option>');
                    });
                }
            });
        } else {
            $('#district_id').html('<option value="">Select District</option>');
        }
    });

    $('#district_id').on('change', function () {
        var district_id = $(this).val();
        if (district_id) {
            $.ajax({
                url: '<?= base_url('wilayah/get_villages'); ?>',
                method: 'GET',
                data: { district_id: district_id },
                success: function (data) {
                    var villages = JSON.parse(data);
                    $('#village_id').html('<option value="">Select Village</option>');
                    villages.forEach(function (village) {
                        $('#village_id').append('<option value="' + village.id + '">' + village.name + '</option>');
                    });
                }
            });
        } else {
            $('#village_id').html('<option value="">Select Village</option>');
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