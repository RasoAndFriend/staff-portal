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
                        <h3 class="card-title">Staff List</h3>
                    </div>
                    <div class="col" align="right"><button class="btn btn-outline-light" data-toggle="modal" data-target="#exampleModal">Add Staff</button></div>
                </div>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <b class="modal-title fs-5" id="exampleModalLabel">Add new staff</b>
                            </div>

                            <div class="modal-body">
                                <span class="status-bar"></span>
                                <div class="row mb-3">
                                    <div class="col">
                                        <input type='text' class="form-control username" placeholder="Enter Username">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <input type='mail' class="form-control email" placeholder="Enter Email">
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary addStaff">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="promotion" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width:10%">User ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Portal Admin</th>
                                <th>Ban Status</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody class="tbl-content">
                            <?php
                            $query_users = get_all_staff();
                            while ($fetch_users = mysqli_fetch_assoc($query_users)) {
                            ?>
                                <tr>
                                    <td><?php echo $fetch_users['staff_id'] ?></td>
                                    <td><?php echo $fetch_users['username'] ?></td>
                                    <td><?php echo $fetch_users['email'] ?></td>
                                    <td>
                                        <?php if ($fetch_users['admin'] == '1') { ?>
                                            <div class="bg-success">True</div>
                                        <?php } else { ?>
                                            <div class="bg-danger">False</div>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($fetch_users['ban_status'] == '1') { ?>
                                            <div class="bg-danger">Banned</div>
                                        <?php } ?>
                                    </td>
                                    <td><button class="btn btn-success click_editUser" data-userid="<?php echo $fetch_users['staff_id'] ?>">Edit user</button></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".fadeIn").fadeIn("slow")
        $(".tbl-content").on('click', '.click_editUser',function() {
            $.post(engine, {
                csrf: csrf,
                click_editUser: 1,
                user_id: $(this).data("userid")
            }, function(data) {
                $(".pages").html(data)
            })
        })

        $(".addStaff").click(function() {
            $.post(engine, {
                csrf: csrf,
                add_staff: 1,
                username: $(".username").val(),
                email: $(".email").val(),
            }, function(data){
                console.log(data);
            });
        });
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