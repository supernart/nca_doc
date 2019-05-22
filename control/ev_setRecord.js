/* 2019-Apr-29 
* Pichet Saelai
* for use another project 
* v.1.0
*/

$(function () {
    $('#frmAddEvLetter').on('submit', function (e) {
        e.preventDefault();
        if (preventNull()) {
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
                        // alert(response);
                        var msg = "";
                        if (response.status == 'ok') {
                            msg = "บันทึกสำเร็จ!";
                        } else {
                            msg = "บันทึกบกพร่อง!";
                        }
                        alert(msg);
                        location.reload();
                    }
                });
            }
        }
    });
});