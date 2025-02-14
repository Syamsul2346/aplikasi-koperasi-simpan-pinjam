
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="<?= base_url('auth/logout/'); ?>">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('assets'); ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets'); ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('assets'); ?>/js/sb-admin-2.min.js"></script>
    <script>
        $(document).ready(function() {
            console.log("DOM is fully loaded");
        });
        
        $('.custom-file input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });


        $('.form-check-input').on('click', function() {
            const menuId = $(this).data('menu');
            const roleId = $(this).data('role');

            $.ajax({
                url: "<?= base_url('admin/changeaccess'); ?>",
                type: 'post',
                data: {
            // objek data : variable
                    menuId: menuId,
                    roleId: roleId
                },
                // mengirimkan kembali tampilan ke halaman "roleaccess/"
                // concapt dijavascript pakai tanda "+"
                success: function(){
                    document.location.href = "<?= base_url('admin/roleaccess/'); ?>" + roleId; 
                }
            });
        });
    </script>
    <script src="<?php echo base_url()?>assets/theme/scripts/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>assets/theme/scripts/DataTables/media/js/DT_bootstrap.js"></script>
<!-- JQueryUI v1.9.2 -->
	<script src="<?php echo base_url()?>assets/theme/scripts/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js"></script>
	
	<!-- JQueryUI Touch Punch -->
	<!-- small hack that enables the use of touch events on sites using the jQuery UI user interface library -->
	<script src="<?php echo base_url()?>assets/theme/scripts/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
	
	<!-- MiniColors -->
	<script src="<?php echo base_url()?>assets/theme/scripts/jquery-miniColors/jquery.miniColors.js"></script>
	
	<!-- Themer -->
	<script>
	var themerPrimaryColor = '#71c39a';
	</script>
	<script src="<?php echo base_url()?>assets/theme/scripts/jquery.cookie.js"></script>
	<script src="<?php echo base_url()?>assets/theme/scripts/themer.js"></script>
	
	
	 <!-- <script type="text/javascript" src="https://www.google.com/jsapi"></script> -->

		<!-- Sparkline -->
	<script src="<?php echo base_url()?>assets/theme/scripts/jquery.sparkline.min.js" type="text/javascript"></script>

		
	<!--  Flot (Charts) JS -->
	<script src="<?php echo base_url()?>assets/theme/scripts/flot/jquery.flot.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/theme/scripts/flot/jquery.flot.pie.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/theme/scripts/flot/jquery.flot.tooltip.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/theme/scripts/flot/jquery.flot.selection.js"></script>
	<script src="<?php echo base_url()?>assets/theme/scripts/flot/jquery.flot.resize.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/theme/scripts/flot/jquery.flot.orderBars.js" type="text/javascript"></script>
	
		
	
	
	
	<!-- Resize Script -->
	<script src="<?php echo base_url()?>assets/theme/scripts/jquery.ba-resize.js"></script>
	
	<!-- Uniform -->
	<script src="<?php echo base_url()?>assets/theme/scripts/pixelmatrix-uniform/jquery.uniform.min.js"></script>
	
	<!-- Bootstrap Script -->
	<script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>
	
	<!-- Bootstrap Extended -->
	<script src="<?php echo base_url()?>assets/bootstrap/extend/bootstrap-select/bootstrap-select.js"></script>
	<script src="<?php echo base_url()?>assets/bootstrap/extend/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script>
	<script src="<?php echo base_url()?>assets/bootstrap/extend/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js"></script>
	<script src="<?php echo base_url()?>assets/bootstrap/extend/jasny-bootstrap/js/jasny-bootstrap.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/bootstrap/extend/jasny-bootstrap/js/bootstrap-fileupload.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/bootstrap/extend/bootbox.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/bootstrap/extend/bootstrap-wysihtml5/js/wysihtml5-0.3.0_rc2.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url()?>assets/bootstrap/extend/bootstrap-wysihtml5/js/bootstrap-wysihtml5-0.0.2.js" type="text/javascript"></script>
	
	<!-- Custom Onload Script -->
	<script src="<?php echo base_url()?>assets/theme/scripts/load.js"></script>

</body>

</html>