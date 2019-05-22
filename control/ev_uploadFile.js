/* 2019-Apr-30 
* Pichet Saelai
* for use another project 
* v.1
*/

$(function () {
    $('#btnUpload').on('click', function () {
        var uploadFile = document.getElementsByName('ev_uploadFile[]');
        var total = 0;
        for (var i = 0; i < uploadFile.length; i++) {
            if (uploadFile[i].value) {
                total++;
            }
        }
        var diff = uploadFile.length - total;
        if (diff > 1) {
            uploadFile[uploadFile.length - 1].parentNode.removeChild(uploadFile[uploadFile.length - 1]);
        }
        uploadFile[(uploadFile.length - 1)].click();
        document.getElementById("listInputFile").appendChild(createInputFile());
    });
});

function createInputFile() {
    var newElem = document.createElement('input');
    newElem.setAttribute("type", "file");
    newElem.setAttribute("onchange", "showName();");
    newElem.setAttribute("style", "display:none;");
    newElem.setAttribute("name", "ev_uploadFile[]");
    return newElem;
}

function showName() {
    var color = ["primary", "success", "info", "warning", "danger", "default"];
    var listNameFile = "";
    var uploadFile = document.getElementsByName('ev_uploadFile[]');
    var total = 0;
    var n = 0;
    for (var i = 0; i < uploadFile.length; i++) {
        if (uploadFile[i].value) {
            if (parseInt(uploadFile[i].files[0].size) < 2 * 1000000) {
                total++;
                listNameFile += "<div>";
                    listNameFile += "<span id='lbName1" + i + "'>" + (i + 1) + ". </span>";
                    listNameFile += "<span class='label label-" + color[n] + "' id='lbName2" + i + "' style='padding:3px;margin-right:5px;'>";
                        listNameFile += shrTxt(uploadFile[i].value);
                    listNameFile += "</span > ";
                    listNameFile += "<span class='badge' id='lbName3" + i + "' onclick='removeInputFile(" + i + ");' style='cursor: pointer;'>X</span>";
                listNameFile += "</div > ";
                n++;
            } else {
                // console.log(uploadFile[i].files[0].size);
                alert("ขนาดไฟล์ เกิน 2 MB ไม่สามารถเพิ่มได้ครับ!");
                removeInputFile(i);
            }
        }
        if (n == 6) n = 0;
    }
    $('#countFile').html('Total File: ' + total.toString());
    $('#listNameFile').html(listNameFile);
}

function removeInputFile(i) {
    $('#lbName1' + i).remove();
    $('#lbName2' + i).remove();
    $('#lbName3' + i).remove();
    var uploadFile = document.getElementsByName('ev_uploadFile[]');
    uploadFile[i].parentNode.removeChild(uploadFile[i]);
    showName();
}

function shrTxt(str) {
    var path = str;
    path = path.replace("C:\\fakepath\\", "");
    if (path.length > 40) {
        var type = path.substring(path.length - 4, path.length)
        path = path.substring(0, 30) + "... " + type;
    }
    return path;
}