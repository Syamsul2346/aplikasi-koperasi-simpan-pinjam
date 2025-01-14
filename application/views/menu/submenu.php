
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?> </h1>

                    
                    <div class="vw 100%" >
                        
                        <?php if (validation_errors()) :?>
                            <div class="alert alert-danger" role="alert">
                                <?= validation_errors(); ?>
                            </div>
                        <?php endif; ?>

                        <?= $this->session->flashdata('message'); ?>
                        <div>
                        <a class="btn btn-primary mb-3" data-toggle="modal" 
                        data-target="#newSubMenuModal">Add New Submenu</a>
                        </div>
                        
                        <table id="subMenu" class="table table-responsive table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Menu Id</th>
                                <th scope="col">Url</th>
                                <th scope="col">Icon</th>
                                <th scope="col">Active</th>
                                <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach($subMenu as $sm) : ?>
                                <tr>
                                <th scope="row"><?= $i; ?></th>
                                <td><?= $sm['title']; ?></td>
                                <td><?= $sm['menu']; ?></td>
                                <td><?= $sm['url']; ?></td>
                                <td><?= $sm['icon']; ?></td>
                                <td><?= $sm['is_active']; ?></td>
                                <td>
                                    <a href="" class="badge badge-success">edit</a>
                                    <a href="" class="badge badge-danger">delete</a>
                                </td>
                                </tr>
                                <?php $i++; ?>
                                <?php endforeach; ?>
                            </tbody>
                            </table>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Modal (Peringatan) -->

<!-- Modal -->
<div class="modal fade" id="newSubMenuModal" tabindex="-1" aria-labelledby="newSubMenuModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newSubMenuModalLabel">Add New Menu</h1>
        <span aria-hidden="true">&times;</span>
    </div>
    <form action="<?= base_url('menu/submenu'); ?>" method="post">
      <div class="modal-body">
        <div class="form-group">
           <input type="text" class="form-control" name="title" id="title" placeholder="Submenu title">
        </div>
        <div class="form-group">
            <select name="menu_id" id="menu_id" class="form-control" placeholder="Select Menu">
                <option value="">Select Menu</option>
                <?php foreach ($menu as $m) :?>
                <option value="<?= $m['id']; ?>"><?= $m['menu']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="url" name="url" placeholder="Submenu url">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="icon" name="icon" placeholder="Submenu icon">
        </div>
        <div class="form-group ">
          <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active" checked>
                <label class="form-check-label" for="is_active">
                    Active
                </label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add</button>
      </div>
      </form>
    </div>
  </div>
</div>
<script>
    $(document).ready(function() {
        new DataTable('#subMenu');
    });
</script>