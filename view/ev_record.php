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
    <script src='../control/ev_setRecord.js<?php echo "?x=" . time(); ?>'></script>
    <script src='../control/ev_uploadFile.js<?php echo "?x=" . time(); ?>'></script>
    <script src='../control/ev_uploadEventImage.js<?php echo "?x=" . time(); ?>'></script>

    <style>
        th,
        td {
            /* border-bottom: 1px solid #ddd; */
        }

        /* .glyphicon:before {
            visibility: visible;
        }

        .glyphicon.glyphicon-ok:checked:before {
            content: "\e006";
        }

        input[type=radio].glyphicon {
            visibility: hidden;
        } */

        /* .glyphicon.glyphicon-ok:checked {
            visibility: visible;
        } */

        .glyphicon.glyphicon-ok {
            visibility: hidden;
        }

        input[type=radio].glyphicon.glyphicon.glyphicon-ok:checked {
            visibility: visible;
        }
    </style>

    <script>
        getCompany(); // load company data to form in id "company"

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
    </script>
</head>

<body>
    <div class='container-fluid' style='padding: 0;'>
        <div class="panel panel-info">
            <div class="panel-heading" style="font-size: 20px;text-align: center;font-weight: bold;">จดหมายเหตุ</div>
            <div class="panel-body" style='padding-top: 0px;'>
                <form id="frmAddEvLetter" enctype="multipart/form-data">
                    <!-- company ----------------------------------------------------------------------------------------- -->
                    <div class='form-group' style='margin-top:15px;'>
                        <div class='row'>
                            <div class='col-md-3'>
                                <label>บริษัท</label>
                                <select class='form-control' name='comp' id='comp' onchange='getFunc()' style='border: 1px solid #ff80d5;'>
                                    <option> เลือก </option>
                                </select>
                            </div>
                            <div class='col-md-3'>
                                <label>สายงาน</label>
                                <select class='form-control' name='func' id='func' onchange='getDep()' disabled style='border: 1px solid #ff80d5;'>
                                    <option> เลือก </option>
                                </select>
                            </div>
                            <div class='col-md-3'>
                                <label>ฝ่าย</label>
                                <select class='form-control' name='dep' id='dep' onchange='getSec()' disabled style='border: 1px solid #ff80d5;'>
                                    <option> เลือก </option>
                                </select>
                            </div>
                            <div class='col-md-3'>
                                <label>แผนก</label>
                                <select class='form-control' name='sec' id='sec' disabled style='border: 1px solid #ff80d5;'>
                                    <option> เลือก </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- title ----------------------------------------------------------------------------------------- -->
                    <div class='form-group' style='margin-top:25px;'>
                        <div class='row'>
                            <div class='col-md-6'>
                                <label>หัวข้อ</label>
                                <input type='text' class='form-control' name='title' id='title' style='border: 1px solid #ff80d5;'>
                            </div>
                            <div class='col-md-3'>
                                <label>วันที่เริ่มกิจกรรม</label>
                                <table style='width:100%;'>
                                    <tr>
                                        <td style='width: 50%;'>
                                            <input type='text' class='datepicker form-control' name='startDate' id='startDate' autocomplete="off" style='border: 1px solid #ff80d5;' placeholder="dd-mm-yyyy">
                                        </td>
                                        <td style='width: 50%;'>
                                            <div class='input-group clockpicker'>
                                                <input type='text' class='form-control' name='startTime' id='startTime' value='' readonly='readonly' autocomplete='off' style='background-color: #FFFFFF;border: 1px solid #ff80d5;' placeholder="เวลา">
                                                <span class='input-group-addon'>
                                                    <span class='glyphicon glyphicon-time'></span>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class='col-md-3'>
                                <label>วันสิ้นสุดกิจกรรม</label>
                                <table style='width:100%;'>
                                    <tr>
                                        <td style='width: 50%;'>
                                            <input type='text' class='datepicker form-control' name='endDate' id='endDate' autocomplete="off" style='border: 1px solid #ff80d5;' placeholder="dd-mm-yyyy">
                                        </td>
                                        <td style='width: 50%;'>
                                            <div class='input-group clockpicker'>
                                                <input type='text' class='form-control' name='endTime' id='endTime' value='' readonly='readonly' autocomplete='off' style='background-color: #FFFFFF;border: 1px solid #ff80d5;' placeholder="เวลา">
                                                <span class='input-group-addon'>
                                                    <span class='glyphicon glyphicon-time'></span>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- detail ----------------------------------------------------------------------------------------- -->
                    <div class='form-group' style='margin-top:25px;'>
                        <div class='row'>
                            <div class='col-md-6'>
                                <label>รายละเอียด</label>
                                <textarea class="form-control" rows="5" name='descp' style='height: 150px;border: 1px solid #ff80d5;'></textarea>
                            </div>
                            <div class='col-md-6'>
                                <label>อัพโหลดเอกสาร</label><span style='color:red;'> (จำกัดไฟล์ไม่เกิน 2 MB)</span>
                                <table style='width:100%;height: 150px;'>
                                    <tr>
                                        <td style='width:100px;padding:0px;text-align: left;'>
                                            <button type="button" class="btn btn-default btn-sm" id='btnUpload' style='background-color: darkgoldenrod;color: white;'>
                                                <span class="glyphicon glyphicon-open"></span> Upload
                                            </button>
                                        </td>
                                        <td style='padding:0px;'>
                                            <div>
                                                <span id='countFile' style='text-align:left;font-size: 15px;font-weight: bold;'>Total File:
                                                    0
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='2' style='padding:0px;vertical-align: bottom;'>
                                            <div style='height: 100px; overflow-y: scroll;background-color: #f2f2f2;border: 1px solid greenyellow;'>
                                                <span id='listNameFile'></span>
                                            </div>
                                            <div id='listInputFile'>
                                                <input type='file' onchange='showName();' style='display:none;' name='ev_uploadFile[]'>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- upload image ----------------------------------------------------------------------------------- -->
                    <div class='form-group' style='margin-top:25px;'>
                        <div class='row'>
                            <div class='col-md-12'>
                                <label>เพิ่มรูปภาพงาน</label><span style='color:red;'> (ขนาดรูปไม่เกิน 2 MB)</span>
                                <table style='width:100%;height: 150px;'>
                                    <tr>
                                        <td style='width:100px;padding:0px;text-align: left;'>
                                            <button type="button" class="btn btn-warning btn-sm" id='btnEventImg' style='background-color: darkgoldenrod;color: white;'>
                                                <span class="glyphicon glyphicon-picture"></span> Upload
                                            </button>
                                        </td>
                                        <td style='padding:0px;'>
                                            <div>
                                                <span id='countEventImage' style='text-align:left;font-size: 15px;font-weight: bold;'>Total Image:
                                                    0
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='2' style='padding:0px;'>
                                            <div class='row' style='padding-left: 15px;padding-right: 15px;margin-bottom: 0px;'>
                                                <div class='col-md-12' id='listEventImg' style='height: 90px; overflow-y: scroll;background-color: #f2f2f2;border: 1px solid greenyellow;'>
                                                    <h5>List image</h5>
                                                </div>
                                            </div>
                                            <div class='row' style='padding-left: 15px;padding-right: 15px;margin-bottom: 0px;'>
                                                <div class='col-md-12' id='previewImg' style='height: 150px; overflow-y: scroll;background-color: #f2f2f2;border: 1px solid greenyellow;display:none;'>
                                                    <img name="ev_previewEventImg[]" src="" style="height:150px;margin:2.5px;">
                                                </div>
                                            </div>
                                            <div id='listInputEventImg'>
                                                <input type='file' onchange='showEventImage(this);' style='display:none;' name='ev_uploadEventImg[]' accept="image/x-png,image/gif,image/jpeg">
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- save ----------------------------------------------------------------------------------------- -->
                    <div class='form-group'>
                        <div class='row'>
                            <div class='col-md-12 text-center'>
                                <input type="submit" class="btn btn-danger submitBtn" value="บันทึก" style='width: 20%;font-size: 16px;font-weight: bold;' />
                            </div>
                        </div>
                    </div>

                    <input type='hidden' name='method' value='setEvletter'>
                </form>
            </div>
        </div>
    </div>
</body>

</html>