
function preventNull() {
    var company = $('#comp').val();
    var func = $('#func').val();
    var dep = $('#dep').val();
    var sec = $('#sec').val();
    if (!company.trim()) {
        $('#comp').focus();
        alert("ระบุบริษัทด้วยครับ!");
        return false;
    } else if (!func.trim()) {
        $('#func').focus();
        alert("ระบุสายงานด้วยครับ!");
        return false;
    } else if (!dep.trim()) {
        $('#dep').focus();
        alert("ระบุฝ่ายด้วยครับ!");
        return false;
    } else if (!sec.trim()) {
        $('#sec').focus();
        alert("ระบุแผนกด้วยครับ!");
        return false;
    }

    var title = $('#title').val();
    var sDate = $('#startDate').val();
    var eDate = $('#endDate').val();
    if (!title.trim()) {
        alert("ระบุหัวข้อด้วยครับ!");
        $('#title').focus();
        return false;
    } else if (!sDate.trim()) {
        $('#startDate').focus();
        alert("ระบุวันที่เริ่มกิจกรรมด้วยครับ!");
        return false;
    } else if (!eDate.trim()) {
        $('#endDate').focus();
        alert("ระบุวันที่สิ้นสุดกิจกรรมด้วยครับ!");
        return false;
    }

    return true;
}