<?php
$split_date = explode("-", $current_date);
$month = $split_date[1];
$year = $split_date[2];
$day = $split_date[0];

if ($day > 25) {
    if ($month >= 12) {
        $year++;
        $month = 1;
    } else {
        $month++;
    }
}
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
                        <h3 class="card-title">Monthly Sales Leaderboard (<?= $months[$month - 1] . " " . $year ?>)</h3>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="promotion" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width:5%">Ranking</th>
                                <th>Staff Name [ID]</th>
                                <th>Sales Total (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $query_staff = mysqli_query($conn, "SELECT * FROM staff");
                            $data_list = array();
                            while ($fetch = mysqli_fetch_assoc($query_staff)) {
                                $sale = mysqli_query($conn, "SELECT SUM(sale_total) AS total_sales FROM sales WHERE staff_id = '$fetch[staff_id]' AND year = '$year' AND month = '$month' AND approval = '1'");
                                $fetch_sale = mysqli_fetch_assoc($sale);
                                $sale_total = 0;

                                if ($fetch_sale['total_sales'] > 0) {
                                    $sale_total = $fetch_sale['total_sales'];
                                }

                                $user_list = array(
                                    'name' => $fetch['username'],
                                    'id' => $fetch['staff_id'],
                                    'total_sale' => $sale_total
                                );

                               array_push($data_list, $user_list);
                            }  
                            
                            usort($data_list, function($a, $b) {
                                return $b['total_sale'] <=> $a['total_sale'];
                            });

                            foreach($data_list as $staff){
                                $i++;
                            ?>
                                <tr>
                                    <td><?php echo $i ?></td>
                                    <td><?php echo $staff['name'] . " [" . $staff['id'] . "]" ?></td>
                                    <td><?php echo $staff['total_sale'] ?></td>
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