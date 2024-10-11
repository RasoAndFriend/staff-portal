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
                        <h3 class="card-title">Rank</h3>
                    </div>
                    <div class="col" align="right"><button class="btn btn-outline-light" data-toggle="modal" data-target="#exampleModal">Add Rank</button></div>
                </div>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <b class="modal-title fs-5" id="exampleModalLabel">Add new rank</b>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col">
                                        <span class="add-status-bar" style="display:none;"></span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col"><input type='text' class="form-control rank-id" placeholder="Enter Rank ID"></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col"><input type='text' class="form-control rank-name" placeholder="Enter Rank Name"></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <select class="form-control dpt-id">
                                            <option>Select Department: </option>
                                            <?php
                                            $query = mysqli_query($conn, "SELECT * FROM department");
                                            while ($row = mysqli_fetch_assoc($query)) {
                                            ?>
                                                <option value="<?= $row['dpt_id'] ?>"><?= $row['dpt_name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary submitRank" data-userid="<?php echo $fetch_user['staff_id'] ?>">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="promotion" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width:10%">Department ID</th>
                                <th>Rank Name</th>
                                <th>Department</th>
                                <th>Total employees</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody class="tbl-content">
                            <?php
                            $query = mysqli_query($conn, "SELECT * FROM rank INNER JOIN department ON rank.dpt_id = department.dpt_id");
                            while ($fetch = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?php echo $fetch['rank_id'] ?></td>
                                    <td><?php echo $fetch['rank_name'] ?></td>
                                    <td><?php echo $fetch['dpt_name'] ?></td>
                                    <td></td>
                                    <td>
                                        <button class="btn btn-success click_editRank" data-rankid="<?php echo $fetch['rank_id'] ?>">Edit</button>
                                        <button class="btn btn-danger" data-toggle="modal" data-target="#deleteRankModal<?= $fetch['rank_id'] ?>">Delete</button>
                                    </td>
                                </tr>
                                <!-- DELETE MODAL -->
                                <div class="modal fade" id="deleteRankModal<?= $fetch['rank_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <b class="modal-title fs-5" id="exampleModalLabel">Delete Department</b>
                                            </div>
                                            <div class="modal-body">
                                                <span class="delete-status-bar" style="display:none;"></span>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        Are you sure want to delete rank <b><?= $fetch['rank_name'] ?></b>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-danger deleteRank" data-rankid="<?php echo $fetch['rank_id'] ?>">Confirm Delete</button>
                                                </div>
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

    $(".submitRank").click(function() {
        $.post(engine, {
            csrf: csrf,
            add_rank: 1,
            rank_id: $(".rank-id").val(),
            dpt_id: $(".dpt-id").val(),
            rank_name: $(".rank-name").val()
        }, function(data) {
            jsonData = JSON.parse(data);
            if (jsonData['status'] == true) {
                window.location.reload();
            } else {
                console.log("nigga")
                $(".add-status-bar").html("<div class='mb-2 bg-danger text-white rounded card-header' style='width:100%'>Error</div>");
                $(".add-status-bar").fadeIn("slow")
            }
        });
    });

    //DELETE Rank
    $(".deleteRank").click(function() {
        $.post(engine, {
            csrf: csrf,
            delete_rank: 1,
            rank_id: $(this).data("rankid"),
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

    // EDIT RANK PERMISSION
    $(".tbl-content").on('click', '.click_editRank',function() {
            $.post(engine, {
                csrf: csrf,
                click_editRank: 1,
                rank_id: $(this).data("rankid")
            }, function(data) {
                $(".pages").html(data)
            })
        })

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