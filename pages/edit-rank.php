<?php
admin_access();
$arrayColName = ['loa', 'sale_approval'];
$arrayPermission = ['LOA Response', 'Sale Approval'];
?>

<div class="row">
    <div class="col-lg">
        <br>
        <div class="card">
            <div class="card-header bg-danger">
                <h3 class="card-title">Rank Permission [<?= getRank($rank_id, "rank_name") ?>]</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="divisionPermission" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Permission</th>
                            <th>Status</th>
                            <th>Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cntPermission = 0;
                        for ($i = 0; $i < count($arrayPermission); $i++) {
                            $query_permission = mysqli_query($conn, "SELECT * FROM rank_permission WHERE rank_id = '$rank_id'");
                            $fetch_permission = mysqli_fetch_assoc($query_permission);
                        ?>
                            <tr class="tbl-content<?= $cntPermission ?>">
                                <td><?php echo $arrayPermission[$i] ?></td>
                                <td>
                                    <span class="allow-status<?= $i ?>">
                                        <?php
                                        if ($fetch_permission[$arrayColName[$i]] > 0) {
                                        ?>
                                            <div class="bg-success">Allowed</div>
                                        <?php
                                        } else {
                                        ?>
                                            <div class="bg-danger">Disallowed</div>
                                        <?php
                                        }
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="allow-btn<?= $cntPermission ?>">
                                        <?php if ($fetch_permission[$arrayColName[$i]] != 1) { ?>
                                            <button class="btn btn-success allowPermission" data-value="1" data-rankid="<?= $rank_id ?>" data-col="<?= $arrayColName[$i] ?>">Allow</button>
                                        <?php } else { ?>
                                            <button class="btn btn-danger disallowPermission" data-value="0" data-rankid="<?= $rank_id ?>" data-col="<?= $arrayColName[$i] ?>">Disallow</button>
                                        <?php } ?>
                                    </span>
                                </td>
                            </tr>
                        <?php
                            $cntPermission++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <!-- /.card -->
        </div>
    </div>
</div>


<script>
    // $(".fadeIn").fadeIn("slow");

    for (let i = 0; i < <?= $cntPermission ?>; i++) {
        $(".tbl-content" + i).on("click", ".allowPermission", function(data) {
            rankID = $(this).data("rankid");
            column = $(this).data("col");
            $.post(engine, {
                csrf: csrf,
                rank_permission: $(this).data("value"),
                rank_id: $(this).data("rankid"),
                col: $(this).data("col"),
            }, function(data) {
                jsonData = JSON.parse(data);
                if (jsonData['status'] == true) {
                    $(".allow-btn" + i).html('<button class="btn btn-danger disallowPermission" data-value="0" data-rankid="' + rankID + '" data-col="' + column + '">Disallow</button>');
                    $(".allow-status" + i).html('<div class="bg-success">Allowed</div>');
                }
            });
        }).on("click", ".disallowPermission", function(data) {
            rankID = $(this).data("rankid");
            column = $(this).data("col");
            $.post(engine, {
                csrf: csrf,
                rank_permission: $(this).data("value"),
                rank_id: $(this).data("rankid"),
                col: $(this).data("col"),
            }, function(data) {
                jsonData = JSON.parse(data);
                if (jsonData['status'] == true) {
                    $(".allow-btn" + i).html('<button class="btn btn-success allowPermission" data-value="1" data-rankid="' + rankID + '" data-col="' + column + '">Allow</button>');
                    $(".allow-status" + i).html('<div class="bg-danger">Disallowed</div>');
                }
            });
        })
    }

    $(".back").click(function() {
        $.post(engine, {
            includePage: "add_division",
            csrf: csrf,
        }, function(data) {
            $(".pages").html(data)
        })
    })

    // Datatables
    $(function() {
        let logContent = ["divisionPermission", "allowPromotion"]
        for (let i = 0; i < logContent.length; i++) {
            $("#" + logContent[i]).DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
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