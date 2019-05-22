<!DOCTYPE html>
<html>

<head>
    <title>บริษัท นครชัยแอร์ จำกัด</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="ev_inc/js/jquery.min.js"></script>

    <link href="ev_inc/jquery-ui/jquery-ui.css" rel="stylesheet">
    <script src="ev_inc/jquery-ui/jquery-ui.js"></script>

    <link rel='stylesheet' href='ev_inc/css/bootstrap.min.css'>
    <script src="ev_inc/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="ev_inc/clockpicker/src/clockpicker.css">
    <script type="text/javascript" src="ev_inc/clockpicker/src/clockpicker.js"></script>

    <script src='../control/ev_getComFuncDepSec.js'></script>
    <script src="../control/ev_validate.js" type="text/javascript"></script>
    <script src='../control/ev_updateRecord.js<?php echo "?x=" . time(); ?>'></script>
    <script src='../control/ev_uploadFileUpdate.js<?php echo "?x=" . time(); ?>'></script>
    <script src='../control/ev_uploadEventImageUpdate.js<?php echo "?x=" . time(); ?>'></script>
    <link href="ev_inc/css/slideimgspn.css" rel="stylesheet" type="text/css" />
    
    <style>
        th,
        td {
            /* border-bottom: 1px solid #ddd; */
        }

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

        .delDivCard {
			position: absolute;
			z-index: 1;
		}
		
		.delDivCard:hover{
			cursor: pointer;
			opacity: 0.90;
			filter: alpha(opacity=90);/* For IE8 and earlier */
			-ms-transform: scale(1.3); /* IE 9 */
			-webkit-transform: scale(1.3); /* Safari 3-8 */
			transform: scale(1.3); 
		}

        .icon-cool:hover{
			cursor: pointer;
			opacity: 0.90;
			filter: alpha(opacity=90);/* For IE8 and earlier */
			-ms-transform: scale(1.3); /* IE 9 */
			-webkit-transform: scale(1.3); /* Safari 3-8 */
			transform: scale(1.3); 
        }
    </style>

    <script>
        $(function() {
            $('.datepicker').datepicker({
                dateFormat: 'dd-mm-yy'
                // minDate: new Date()
            });
            $('.clockpicker').clockpicker({
                placement: 'left',
                align: 'top',
                donetext: 'Done'
            });
        });

        function back2Rpt() {
            var showStyle = $('#showStyle').val();
            location.href = "ev_rpt.php?showStyle="+showStyle;
        }
    </script>
</head>

<body>
    <div class='container-fluid' style='padding: 0;'>
        <div class='panel panel-info'>
            <div class='panel-heading' style='font-size: 20px;text-align: center;font-weight: bold;'>จดหมายเหตุ</div>
            <div class='panel-body' style='padding-top: 0px;'>
                <input type='hidden' name='showStyle' id='showStyle' value='<?php echo $_GET['showStyle']; ?>'>
                <form id='frmEditEvLetter' enctype='multipart/form-data'>
                    <input type='hidden' name='evid' id='evid' value='<?php echo $_GET['evid']; ?>'>
                </form>
                <div id='test_response'></div>
            </div>
        </div>
    </div>
</body>

</html>