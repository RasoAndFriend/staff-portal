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
                    <div class="col" align="right"><button class="btn btn-outline-light" data-toggle="modal" data-target="#exampleModal">Submit Sales</button></div>
                </div>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <b class="modal-title fs-5" id="exampleModalLabel">Submit new sales</b>
                            </div>

                            <div class="modal-body">
                                <span class="status-bar"></span>
                                <div class="row mb-3">
                                    <div class="col">
                                        <select class="form-control product-id">
                                            <option>Select Product: </option>
                                            <?php
                                            $query_product = mysqli_query($conn, "SELECT * FROM product WHERE enable = '1'");
                                            while ($fetch_product = mysqli_fetch_assoc($query_product)) {
                                            ?>
                                                <option value="<?= htmlentities($fetch_product['product_id']) ?>"><?= htmlentities($fetch_product['product_name']) ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <input type='number' class="form-control total-sales" placeholder="Enter Sales Total (RM)">
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary addSale">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="promotion" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width:10%">Sales ID</th>
                                <th>Product</th>
                                <th>Sales Total (RM)</th>
                                <th>Sales Date</th>
                                <th>Checked Status</th>
                                <th>Checked Date</th>
                                <th>Approved by</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query_sales = mysqli_query($conn, "SELECT * FROM sales INNER JOIN product ON sales.product_id = product.product_id WHERE staff_id = '$_SESSION[user]'");
                            while ($fetch_sales = mysqli_fetch_assoc($query_sales)) {
                                $checked_person = userDetails($fetch_sales['approved_by'], 'username');
                            ?>
                                <tr>
                                    <td><?php echo $fetch_sales['sale_id'] ?></td>
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
    $(".fadeIn").fadeIn("slow");
    $(document).ready(function() {
        $(".addSale").click(function() {
            console.log('assda')
            $.post(engine, {
                csrf: csrf,
                add_sales: 1,
                product_id: $(".product-id").val(),
                total_sales: $(".total-sales").val(),
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