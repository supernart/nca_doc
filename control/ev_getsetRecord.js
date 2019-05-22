/* 2019-Apr-29 
* Pichet Saelai
* for use another project 
* v.1.0
*/

$(function () {
    $('#frmEvLetter').on('submit', function (e) {
        e.preventDefault();
        if(preventNull()){

            var r = confirm("ยืนยันการบันทึก!");
            if (r == true) {
                $.ajax({
                    type: 'post',
                    url: '../model/ev_m_evletter.php',
                    data: new FormData(this),
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (response) {
                        var msg = "";
                        if(response.status=='ok'){
                            msg = "บันทึกสำเร็จ!";
                        }else{
                            msg = "บันทึกบกพร่อง!";
                        }
                        alert(msg);
                        location.reload();
                        // $('#test1').html(response);
                        // $('#test2').html(response.status_upload);
                        // console.log(response);
                    }
                });
            }
        }
    });
});

function getCompany() {
    $.ajax({
        type: 'POST',
        url: '../control/ev_con_evletter.php',
        dataType: "html",
        data: { method: 'getCompany' },
        success: function (data) {
            $('#company').html(data);
        }
    });
}

function getFunc() {
    $.ajax({
        type: 'POST',
        url: '../control/ev_con_evletter.php',
        dataType: "html",
        data: {
            method: 'getFunc',
            company: $('#company').val()
        },
        success: function (data) {
            if (data) {
                $('#func').html(data);
                $('#func').prop("disabled", false);
            } else {
                $('#func').html("<option value='0'> ไม่พบข้อมูล </option>");
            }

            var s = "<option value='0'> เลือก </option>";
            $('#dep').html(s);
            $('#dep').prop("disabled", true);
            $('#sec').html(s);
            $('#sec').prop("disabled", true);
        }
    });
}

function getDep() {
    $.ajax({
        type: 'POST',
        url: '../control/ev_con_evletter.php',
        dataType: "html",
        data: {
            method: 'getDep',
            func: $('#func').val()
        },
        success: function (data) {
            if (data) {
                $('#dep').html(data);
                $('#dep').prop("disabled", false);
            } else {
                $('#dep').html("<option value='0'> ไม่พบข้อมูล </option>");
                $('#dep').prop("disabled", false);
            }
            var s = "<option value='0'> เลือก </option>";
            $('#sec').html(s);
            $('#sec').prop("disabled", true);
        }
    });
}

function getSec() {
    $.ajax({
        type: 'POST',
        url: '../control/ev_con_evletter.php',
        dataType: "html",
        data: {
            method: 'getSec',
            dep: $('#dep').val()
        },
        success: function (data) {
            if (data) {
                $('#sec').html(data);
                $('#sec').prop("disabled", false);
            }
            else {
                $('#sec').html("<option value='0'> ไม่พบข้อมูล </option>");
                $('#sec').prop("disabled", false);
            }
        }
    });
}