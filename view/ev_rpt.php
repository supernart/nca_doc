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

	<link rel="stylesheet" type="text/css" href="ev_inc/DataTables/datatables.min.css">
	<script type="text/javascript" src="ev_inc/DataTables/datatables.min.js"></script>

	<script src='../control/ev_getComFuncDepSec.js'></script>
	<script src='../control/ev_getEvLetter.js<?php echo '?x=' . time(); ?>'></script>
	<script src='../control/ev_validate.js<?php echo '?x=' . time(); ?>'></script>
	<script src='../control/ev_setRecord.js<?php echo '?x=' . time(); ?>'></script>
	<script src='../control/ev_updateRecord.js<?php echo '?x=' . time(); ?>'></script>
	<script src='../control/ev_uploadFileUpdate.js<?php echo '?x=' . time(); ?>'></script>
	<script src='../control/ev_uploadEventImageUpdate.js<?php echo '?x=' . time(); ?>'></script>

	<link href="ev_inc/fullcalendar-2.1.1/fullcalendar.min.css" rel="stylesheet" type="text/css" />
	<script src="ev_inc/fullcalendar-2.1.1/lib/moment.min.js" type="text/javascript"></script>
	<script src="ev_inc/fullcalendar-2.1.1/fullcalendar.min.js" type="text/javascript"></script>
	<script src="ev_inc/fullcalendar-2.1.1/lang/th.js" type="text/javascript"></script>

	<link href="ev_inc/css/calendarspn.css" rel="stylesheet" type="text/css" />
	<link href="ev_inc/css/slideimgspn.css" rel="stylesheet" type="text/css" />

	<style>
		body {
			font-family: "Karma", sans-serif;
		}

		.grid-img {
			border-top-left-radius: 5px;
			border-top-right-radius: 5px;
			border-bottom: 1px solid #d2dbe2;
			margin-bottom: 0;
			width: 100%;
			height: 250px;
			object-fit: cover;
		}

		.bar-author {
			overflow: hidden;
			background-color: white;
			border-bottom: 1px solid #d2dbe2;
			border-top: 1px solid #d2dbe2;
			width: 100%;
			padding: 5px;

			border-bottom-left-radius: 5px;
			border-bottom-right-radius: 5px;

			position: absolute;
			bottom: 0;
			width: 100%;
		}

		.box {
			position: relative;
			margin: 7px;
			border-bottom-left-radius: 5px;
			border-bottom-right-radius: 5px;
			box-shadow: 0 4px 5px 0 rgba(0, 0, 0, 0.2), 0 1px 5px 0 rgba(0, 0, 0, 0.19);
			height: 380px;

		}

		.box:hover {
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
		}

		.imgop:hover {
			cursor: pointer;
			opacity: 0.90;
			filter: alpha(opacity=90);
			/* For IE8 and earlier */
		}

		@media only screen and (max-width: 950px) {
			div.descp {
				/* background-color: lightblue; */
				/* display: none; */
			}
		}

		.txtupload {
			font-size: 11px;
			cursor: pointer;
		}

		.txtupload:hover {
			color: blue;
		}

		.headSpan {
			cursor: pointer;
			color: #0066ff;
			text-decoration: none;
			font-weight: bold;
		}

		.headSpan:hover {
			text-decoration: underline;
			/* text-shadow: 1px 1px 1px #555; */
		}

		.head-break {
			word-break: break-all;
		}

		.span-link {
			cursor: pointer;
		}

		.span-link:hover {
			text-decoration: underline;
			color: blue;
		}

		.delDivCard {
			position: absolute;
			z-index: 1;
			right: 5px;
		}

		.delDivCard:hover {
			cursor: pointer;
			opacity: 0.90;
			filter: alpha(opacity=90);
			/* For IE8 and earlier */
			-ms-transform: scale(1.3);
			/* IE 9 */
			-webkit-transform: scale(1.3);
			/* Safari 3-8 */
			transform: scale(1.3);
		}

		.img-edit {}

		.img-edit:hover {
			cursor: pointer;
			opacity: 0.90;
			filter: alpha(opacity=90);
			/* For IE8 and earlier */
			-ms-transform: scale(1.3);
			/* IE 9 */
			-webkit-transform: scale(1.3);
			/* Safari 3-8 */
			transform: scale(1.3);
		}

		.icon-cool:hover {
			cursor: pointer;
			opacity: 0.90;
			filter: alpha(opacity=90);
			/* For IE8 and earlier */
			-ms-transform: scale(1.3);
			/* IE 9 */
			-webkit-transform: scale(1.3);
			/* Safari 3-8 */
			transform: scale(1.3);
		}
	</style>

	<script>
		$(function() {
			$('.datepicker').datepicker({
				dateFormat: 'dd-mm-yy'
				// ,minDate: new Date()
			});
			$('.clockpicker').clockpicker({
				placement: 'left',
				align: 'left',
				donetext: 'Done'
			});
		});

		function back2Rpt() {
			$('#frmAddCalendar').fadeOut("fast");
		}
	</script>
</head>

<body>
	<div class='container-fluid' style='padding: 0;'>
		<div class="panel panel-info">
			<div class="panel-heading" style="font-size: 20px;text-align: center;font-weight: bold;">รายงานจดหมายเหตุ</div>
			<div class="panel-body">
				<div class='row'>
					<div class='col-sm-12'>
						<div class='row'>
							<div class='col-sm-4' style='padding: 5px;'>
								<input type='text' class='form-control' placeholder="ค้นหาชื่อเรื่อง" id='nSearch'>
							</div>
							<div class='col-sm-2' style='padding: 5px;'>
								<input type='text' class='datepicker form-control' id='nSdate' style='' placeholder="วันที่" autocomplete="off">
							</div>
							<div class='col-sm-2' style='padding: 5px;'>
								<input type='text' class='datepicker form-control' id='nEdate' style='' placeholder="ถึงวันที่" autocomplete="off">
							</div>

							<div class='col-sm-2' style='padding: 5px;'>
								<select id='nStyle' class='form-control'>
									<?php
									$card = $list = $calendar = "";
									if ($_GET['showStyle'] == "card") {
										$card = "selected";
									} else if ($_GET['showStyle'] == "list") {
										$list = "selected";
									} else {
										$calendar = "selected";
									}
									echo "<option value='getRptCalendar' $calendar> รูปแบบปฏิทิน </option>";
									echo "<option value='getRptCard' $card> รูปแบบการ์ด </option>";
									echo "<option value='getRptList' $list> รูปแบบรายการ </option>";
									?>
								</select>
							</div>

							<div class='col-sm-2' style='padding: 5px;'>
								<input type='button' class='btn btn-danger' style='width:100%;' value='แสดงรายการ' onclick="loadPage();">
							</div>

						</div>
					</div>
				</div>
				<div class='row'>
					<div class='col-sm-12' id='divEvLetter' style='padding:5px;display:none;'>
						<!-- show report -->
					</div>
					<div id='calendarRpt'></div>
				</div>
				<div>
					<div id='frmAddCalendar' class="modal">
						<div class="modal-content">
							<span class="close">&times;</span>

							<form id='frmEditEvLetter' enctype='multipart/form-data'>
							</form>
							<form id='frmAddEvLetter' enctype='multipart/form-data'>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<script>
	// Get the <span> element that closes the modal
	var span = document.getElementsByClassName("close")[0];

	// When the user clicks on <span> (x), close the modal
	span.onclick = function() {
		$('#frmAddCalendar').fadeOut("fast");
	}

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if (event.target == document.getElementById('frmAddCalendar')) {
			$('#frmAddCalendar').fadeOut("fast");
		}
	}
</script>

</html>