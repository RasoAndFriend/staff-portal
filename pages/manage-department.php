<?php
admin_access();
?>
<!-- DataTables -->
<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<div class="fadeIn" style="display:none;">
    <div class="row">
        <div class="col-lg">
            <div class="card">
                <div class="card-header bg-danger">
                    <div class="col">
                        <h3 class="card-title">Department</h3>
                    </div>
                    <div class="col" align="right"><button class="btn btn-outline-light" data-toggle="modal" data-target="#exampleModal">Add Department</button></div>
                </div>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <b class="modal-title fs-5" id="exampleModalLabel">Add new department</b>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col">
                                        <span class="add-status-bar"></span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col"><input type='text' class="form-control dpt-id" placeholder="Enter Department ID"></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col"><input type='text' class="form-control dpt-name" placeholder="Enter Department Name"></div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary submitDepartment" data-userid="<?php echo $fetch_user['staff_id'] ?>">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="promotion" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width:10%">Department ID</th>
                                <th>Department Name</th>
                                <th>Total Rank</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($conn, "SELECT * FROM department");
                            while ($fetch = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?= htmlentities($fetch['dpt_id']) ?></td>
                                    <td><?= htmlentities($fetch['dpt_name']) ?></td>
                                    <td>
                                        <?php
                                        $calculate_rank = mysqli_query($conn, "SELECT COUNT(*) AS total_rank FROM rank WHERE dpt_id = '$fetch[dpt_id]'");
                                        $fetch_total = mysqli_fetch_assoc($calculate_rank);
                                        echo $fetch_total['total_rank'];
                                        ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-success click_editDepartment" data-toggle="modal" data-target="#editRankModal<?= $fetch['dpt_id'] ?>">Edit</button>
                                        <button class="btn btn-danger" data-toggle="modal" data-target="#deleteRankModal<?= $fetch['dpt_id'] ?>">Delete</button>
                                    </td>
                                </tr>

                                <!-- DELETE MODAL -->
                                <div class="modal fade" id="deleteRankModal<?= $fetch['dpt_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <b class="modal-title fs-5" id="exampleModalLabel">Delete Department</b>
                                            </div>
                                            <div class="modal-body">
                                                <span class="delete-status-bar" style="display:none;"></span>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        Are you sure want to delete department <b><?= $fetch['dpt_name'] ?></b>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-danger deleteDepartment" data-dptid="<?php echo $fetch['dpt_id'] ?>">Confirm Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- EDIT MODAL -->
                                <div class="modal fade" id="editRankModal<?= $fetch['dpt_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <b class="modal-title fs-5" id="exampleModalLabel">Edit Department</b>
                                            </div>
                                            <div class="modal-body">
                                                <span class="status-bar"></span>
                                                <div class="row mb-3">
                                                    <div class="col"><input type='text' class="form-control dpt-id" placeholder="Enter Department ID" value="<?= $fetch['dpt_id'] ?>"></div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col"><input type='text' class="form-control dpt-name" placeholder="Enter Department Name" value="<?= $fetch['dpt_name'] ?>"></div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary submitDepartment">Save changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    $(".fadeIn").fadeIn("slow");

    $(".submitDepartment").click(function() {
        $.post(engine, {
            csrf: csrf,
            add_department: 1,
            dpt_id: $(".dpt-id").val(),
            dpt_name: $(".dpt-name").val()
        }, function(data) {
            jsonData = JSON.parse(data);
            if (jsonData['status'] == true) {
                window.location.reload();
            } else {
                $(".add-status-bar").html("<div class='mb-2 bg-danger text-white rounded card-header' style='width:100%'>Error</div>");
                $(".add-status-bar").fadeIn("slow")
            }
        });
    });

    $(".deleteDepartment").click(function() {
        $.post(engine, {
            csrf: csrf,
            delete_department: 1,
            dpt_id: $(this).data("dptid"),
        }, function(data) {
            jsonData = JSON.parse(data);
            if (jsonData['status'] == true) {
                window.location.reload();
            } else {
                $(".delete-status-bar").html("<div class='mb-2 bg-danger text-white rounded card-header' style='width:100%'>Error</div>");
                $(".delete-status-bar").fadeIn("slow")
            }
        });
    });

    // Datatables
    $(function() {
        let logContent = ["promotion"]
        for (let i = 0; i < logContent.length; i++) {
            $("#" + logContent[i]).DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": true,
                "responsive": true,
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        }
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>