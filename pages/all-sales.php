<?php
permission_access('sale_approval');
?>

<!-- DataTables -->
<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<div class="fadeIn" style="display:none;">
    <div class="row">
        <div class="col-lg">
            <div class="card">
                <div class="card-header bg-info">
                    <div class="col">
                        <h3 class="card-title">Sales List</h3>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="promotion" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width:6%">Sales ID</th>
                                <th style="width:10%">Staff</th>
                                <th>Product</th>
                                <th style="width:10%">Sales Total (RM)</th>
                                <th style="width:10%">Sales Date</th>
                                <th style="width:10%">Checked Status</th>
                                <th style="width:10%">Checked Date</th>
                                <th style="width:10%">Approved by</th>
                                <th>Remark</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query_sales = mysqli_query($conn, "SELECT * FROM sales INNER JOIN product ON sales.product_id = product.product_id");
                            while ($fetch_sales = mysqli_fetch_assoc($query_sales)) {
                                $checked_person = userDetails($fetch_sales['approved_by'], 'username');
                            ?>
                                <tr class="tbl-content">
                                    <td><?php echo $fetch_sales['sale_id'] ?></td>
                                    <td><?php echo userDetails($fetch_sales['staff_id'], 'username'); ?></td>
                                    <td><?php echo htmlentities($fetch_sales['product_name']) ?></td>
                                    <td><?php echo $fetch_sales['sale_total'] ?></td>
                                    <td><?php echo $fetch_sales['sale_date'] ?></td>
                                    <td>
                                        <?php if ($fetch_sales['approval'] == 1) { ?>
                                            <div class="bg-success">Approved</div>
                                        <?php } else if ($fetch_sales['approval'] == 2) { ?>
                                            <div class="bg-danger">Rejected</div>
                                        <?php } else { ?>
                                            <div class="bg-warning">Pending</div>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo $fetch_sales['checked_date'] ?></td>
                                    <td><?php echo $checked_person ?></td>
                                    <td><?php echo htmlentities($fetch_sales['remark']) ?></td>
                                    <td>
                                        <?php if (($fetch_sales['staff_id'] != $_SESSION['user'] || userDetails($_SESSION['user'], 'admin') == '1') && $fetch_sales['approval'] == 0) { ?>
                                            <button class="btn btn-success" data-toggle="modal" data-target="#approveModal<?= $fetch_sales['sale_id'] ?>">Approve</button>
                                            <button class="btn btn-danger" data-toggle="modal" data-target="#rejectModal<?= $fetch_sales['sale_id'] ?>">Reject</button>
                                        <?php } else { ?>
                                            <button class="btn btn-danger" disabled>Not Allowed</button>
                                        <?php } ?>
                                    </td>
                                </tr>

                                <!-- APPROVE MODAL -->
                                <div class="modal fade approveModal" id="approveModal<?= $fetch_sales['sale_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <b class="modal-title fs-5" id="exampleModalLabel">Approve Sales ID [<?= $fetch_sales['sale_id'] ?>]</b>
                                            </div>

                                            <div class="modal-body">
                                                <span class="approve-status-bar"></span>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <textarea type='text' class="form-control approveRemark<?= $fetch_sales['sale_id'] ?>" placeholder="Remarks"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-success approveSales" data-id="<?= $fetch_sales['sale_id'] ?>">Approve</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- REJECT MODAL -->
                                <div class="modal fade rejectModal" id="rejectModal<?= $fetch_sales['sale_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <b class="modal-title fs-5" id="exampleModalLabel">Reject Sales ID [<?= $fetch_sales['sale_id'] ?>]</b>
                                            </div>

                                            <div class="modal-body">
                                                <span class="status-bar"></span>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <textarea type='text' class="form-control rejectRemark<?= $fetch_sales['sale_id'] ?>" placeholder="Remarks"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-danger rejectSales" data-id="<?= $fetch_sales['sale_id'] ?>">Reject</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
    $(".fadeIn").fadeIn("slow");
    $(document).ready(function() {
        $(".approveModal").on('click', '.approveSales', function() {
            $.post(engine, {
                csrf: csrf,
                checked_sales: 1,
                sales_id: $(this).data("id"),
                remarks: $(".approveRemark" + $(this).data("id")).val()
            }, function(data) {
                console.log(data);
                jsonData = JSON.parse(data);
                if (jsonData['status'] == true) {
                    window.location.reload();
                } else {
                    $(".add-status-bar").html("<div class='mb-2 bg-danger text-white rounded card-header' style='width:100%'>Error</div>");
                    $(".add-status-bar").fadeIn("slow")
                    setTimeout(function() {
                        $(".add-status-bar").fadeOut("slow")
                    }, 2000);
                }
            });
        });

        $(".rejectModal").on('click', '.rejectSales', function() {
            $.post(engine, {
                csrf: csrf,
                checked_sales: 2,
                sales_id: $(this).data("id"),
                remarks: $(".rejectRemark" + $(this).data("id")).val()
            }, function(data) {
                console.log(data);
                jsonData = JSON.parse(data);
                if (jsonData['status'] == true) {
                    window.location.reload();
                } else {
                    $(".add-status-bar").html("<div class='mb-2 bg-danger text-white rounded card-header' style='width:100%'>Error</div>");
                    $(".add-status-bar").fadeIn("slow")
                    setTimeout(function() {
                        $(".add-status-bar").fadeOut("slow")
                    }, 2000);
                }
            });
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
    })
</script>