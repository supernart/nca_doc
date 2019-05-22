

function getCompany() {
    $.ajax({
        type: 'POST',
        url: '../control/ev_con_evletter.php',
        dataType: "html",
        data: { method: 'getCompany' },
        success: function (data) {
            $('#comp').html(data);
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
            company: $('#comp').val()
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