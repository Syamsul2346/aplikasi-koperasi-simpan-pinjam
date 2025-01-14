<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row mx-3">
        <table id="myHistory" class="table table-responsive table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <!-- <th>#</th> -->
                            <th>Waktu</th>
                            <th>User</th> 
                            <th>Action</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <?php $i = 1; ?> -->
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <!-- <td><?= $i++; ?></td> -->
                                <td><?= $log['timestamp']; ?></td>
                                <td><?= $log['user_name']; ?></td> 
                                <td><?= $log['action']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            
    </div>
</div>

<script>
    $(document).ready(function() {
        new DataTable('#myHistory');
    });
</script>
