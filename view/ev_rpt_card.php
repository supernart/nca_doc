<!DOCTYPE html>
<html>

<head>
    <title>บริษัท นครชัยแอร์ จำกัด</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="ev_inc/js/jquery.min.js"></script>

    <link rel="stylesheet" href="ev_inc/jquery-ui/jquery-ui.css">
    <script src="ev_inc/jquery-ui/jquery-ui.js"></script>

    <link rel='stylesheet' href='ev_inc/css/bootstrap.min.css'>
    <script src="ev_inc/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="ev_inc/clockpicker/src/clockpicker.css">
    <script type="text/javascript" src="ev_inc/clockpicker/src/clockpicker.js"></script>

    <link rel='stylesheet' href='ev_inc/DataTables/datatables.min.css'>
    <script src='ev_inc/DataTables/datatables.min.js'></script>

    <script src='../control/ev_getEvLetterDetail.js<?php echo "?x=" . time(); ?>'></script>
    <link href="ev_inc/css/slideimgspn.css" rel="stylesheet" type="text/css" />

    <style>
        .headcom {
            font-size: 14px;
            font-weight: bold;
            /* letter-spacing: 1px; */
            color: blue;
        }

        .headcomdt {
            font-size: 14px;
            font-weight: bold;
            color: blue;
        }
    </style>

    <script>
        $(document).ready(function() {
            var mEvid = $('#evid').val();
            $.ajax({
                type: 'POST',
                url: '../control/ev_con_evletter.php',
                dataType: "html",
                data: {
                    method: 'getEvLetterRptCardDetail',
                    evid: mEvid
                },
                success: showEvLtDetail
            });
        });

        // callback
        function showEvLtDetail(resp) {
            $('#divEvLetter').html(resp);
            $('#divEvLetter').fadeIn("slow");
        }

        function back2Rpt() {
            location.href = "ev_rpt.php?showStyle=card";
        }
    </script>
</head>

<body>
    <?php
    echo "<input type='hidden' id='evid' value='" . $_GET['evid'] . "'>";
    ?>
    <div class='container-fluid' style='padding: 0;'>
        <div class="panel panel-info">
            <div class="panel-heading" style="font-size: 20px;text-align: center;font-weight: bold;">รายงานจดหมายเหตุ</div>
            <div class="panel-body">
                <div class='row'>
                    <div class='col-sm-12' id='divEvLetter' style='display:none;'>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>