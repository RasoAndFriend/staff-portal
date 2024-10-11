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
                <div class="card-header bg-info">
                    <div class="col">
                        <h3 class="card-title">Product List</h3>
                    </div>
                    <div class="col" align="right"><button class="btn btn-outline-light" data-toggle="modal" data-target="#exampleModal">Add Product</button></div>
                </div>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <b class="modal-title fs-5" id="exampleModalLabel">Add product</b>
                            </div>

                            <div class="modal-body">
                                <span class="add-status-bar" style="display:none;"></span>
                                <div class="row mb-3">
                                    <div class="col">
                                        <input type='number' class="form-control product-id" placeholder="Enter Product ID">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <input type='text' class="form-control product-name" placeholder="Enter Product Name">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <select class="form-control enable-status">
                                            <option value="0">Disable</option>
                                            <option value="1">Enable</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary addProduct">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="promotion" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width:10%">Product ID</th>
                                <th>Product Name</th>
                                <th>Enable Status</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query_product = mysqli_query($conn, "SELECT * FROM product");
                            $i = 0;
                            while ($fetch_product = mysqli_fetch_assoc($query_product)) {
                            ?>
                                <tr class="tbl-content<?= $i ?>">
                                    <td><?php echo $fetch_product['product_id'] ?></td>
                                    <td><?php echo htmlentities($fetch_product['product_name']) ?></td>
                                    <td>
                                        <span class="enableStatus<?= $i ?>">
                                            <?php if ($fetch_product['enable'] == 1) { ?>
                                                <div class="bg-success">Enabled</div>
                                            <?php } else { ?>
                                                <div class="bg-danger">Disabled</div>
                                            <?php } ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="enableBtn<?= $i ?>">
                                            <?php if ($fetch_product['enable'] == 1) { ?>
                                                <button class="btn btn-danger disableProduct" data-value="0" data-id="<?= $fetch_product['product_id'] ?>">Disable</button>
                                            <?php } else { ?>
                                                <button class="btn btn-success enableProduct" data-value="1" data-id="<?= $fetch_product['product_id'] ?>">Enable</button>
                                            <?php } ?>
                                        </span>
                                        <button class="btn btn-info">Edit</button>
                                        <button class="btn btn-danger" data-toggle="modal" data-target="#deleteProductModal<?= $fetch_product['product_id'] ?>">Delete</button>
                                    </td>
                                    <!-- DELETE MODAL -->
                                    <div class="modal fade" id="deleteProductModal<?= $fetch_product['product_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <b class="modal-title fs-5" id="exampleModalLabel">Delete Product</b>
                                                </div>
                                                <div class="modal-body">
                                                    <span class="delete-status-bar" style="display:none;"></span>
                                                    <div class="row mb-3">
                                                        <div class="col">
                                                            Are you sure want to delete product <b><?= htmlentities($fetch_product['product_name']) ?></b>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-danger deleteProduct" data-productid="<?php echo $fetch_product['product_id'] ?>">Confirm Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                            <?php
                                $i++;
                            } ?>
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

    $(".addProduct").click(function() {
        $.post(engine, {
            csrf: csrf,
            add_product: 1,
            product_id: $(".product-id").val(),
            product_name: $(".product-name").val(),
            enable_status: $(".enable-status").val()
        }, function(data) {
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

    for (let i = 0; i < <?= $i ?>; i++) {
        $(".tbl-content" + i).on("click", ".enableProduct", function(data) {
            $.post(engine, {
                csrf: csrf,
                enable_product: $(this).data("value"),
                id: $(this).data("id")
            }, function(data) {
                jsonData = JSON.parse(data);
                if (jsonData['status'] == true) {
                    $(".enableBtn" + i).html('<button class="btn btn-danger disableProduct" data-value="0" data-id="' + $(this).data("id") + '">Disable</button>');
                    $(".enableStatus" + i).html('<div class="bg-success">Enabled</div>');
                }
            });
        }).on("click", ".disableProduct", function(data) {
            $.post(engine, {
                csrf: csrf,
                enable_product: $(this).data("value"),
                id: $(this).data("id")
            }, function(data) {
                jsonData = JSON.parse(data);
                if (jsonData['status'] == true) {
                    $(".enableBtn" + i).html('<button class="btn btn-success enableProduct" data-value="1" data-id="' + $(this).data("id") + '">Enable</button>');
                    $(".enableStatus" + i).html('<div class="bg-danger">Disabled</div>');
                }
            });
        })
    }


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