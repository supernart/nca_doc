/* 2019-May-03
* Pichet Saelai
* v.1
*/

$(function () {
    $('#btnEventImg').on('click', function () {

        var uploadEventImg = document.getElementsByName('ev_uploadEventImg[]');
        var total = 0;
        for (var i = 0; i < uploadEventImg.length; i++) {
            if (uploadEventImg[i].value) {
                total++;
            }
        }

        var diff = uploadEventImg.length - total;
        if (diff > 1) {
            uploadEventImg[uploadEventImg.length - 1].parentNode.removeChild(uploadEventImg[uploadEventImg.length - 1]);
        }
        uploadEventImg[(uploadEventImg.length - 1)].click();
        document.getElementById("listInputEventImg").appendChild(createInputEventImg());
        document.getElementById("previewImg").appendChild(createPreviewEventImg());
    });
});

function createInputEventImg() {
    var newElem = document.createElement('input');
    newElem.setAttribute("type", "file");
    newElem.setAttribute("onchange", "showEventImage(this);");
    newElem.setAttribute("style", "display:none;");
    newElem.setAttribute("name", "ev_uploadEventImg[]");
    newElem.setAttribute("accept", "image/x-png,image/gif,image/jpeg");
    return newElem;
}

function createPreviewEventImg() {
    var newElem = document.createElement('img');
    newElem.setAttribute("name", "ev_previewEventImg[]");
    newElem.setAttribute("style", "height:150px;margin:2.5px;");
    return newElem;
}

function showEventImage() {
    var color = ["primary", "success", "info", "warning", "danger", "default"];
    var listNameFile = "";
    var uploadEventImg = document.getElementsByName('ev_uploadEventImg[]');
    var total = 0;
    var n = 0;
    for (var i = 0; i < uploadEventImg.length; i++) {
        if (uploadEventImg[i].value) {
            if (parseInt(uploadEventImg[i].files[0].size) < 2 * 1000000) {
                total++;
                listNameFile += "<div>";
                    listNameFile += "<span id='lbEvImgName1" + i + "'>" + (i + 1) + ". </span>";
                    listNameFile += "<span class='label label-" + color[n] + "' id='lbEvImgName2" + i + "' style='padding:3px;margin-right:5px;'>";
                        listNameFile += shrTxtt(uploadEventImg[i].value);
                    listNameFile += "</span > ";
                    listNameFile += "<span class='badge' id='lbEvImgName3" + i + "' onclick='removeInputEventImg(" + i + ");' style='cursor: pointer;'>X</span>";
                listNameFile += "</div > ";
                readURL(uploadEventImg[i], n);
                n++;
            } else {
                alert("ขนาดไฟล์ เกิน 2 MB ไม่สามารถเพิ่มได้ครับ!");
                removeInputEventImg(i);
            }
            $('#previewImg').fadeIn("slow");
        }
        if (n == 6) n = 0;
    }
    $('#countEventImage').html('Total File: ' + total.toString());
    if(parseInt(total) == 0) $('#previewImg').fadeOut("fast");
    $('#listEventImg').html(listNameFile);
}

function removeInputEventImg(i) {
    $('#lbEvImgName1' + i).remove();
    $('#lbEvImgName2' + i).remove();
    $('#lbEvImgName3' + i).remove();
    var uploadEventImg = document.getElementsByName('ev_uploadEventImg[]');
    uploadEventImg[i].parentNode.removeChild(uploadEventImg[i]);

    var preViewEventImg = document.getElementsByName('ev_previewEventImg[]');
    preViewEventImg[i].parentNode.removeChild(preViewEventImg[i]);
    showEventImage();
}

function shrTxtt(str) {
    var path = str;
    path = path.replace("C:\\fakepath\\", "");
    if (path.length > 40) {
        var type = path.substring(path.length - 4, path.length)
        path = path.substring(0, 30) + "... " + type;
    }
    return path;
}

function readURL(input, n) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
  
      var newElem = document.getElementsByName("ev_previewEventImg[]");
      reader.onload = function(e) {
        newElem[n].setAttribute("src", e.target.result);
      }
  
      reader.readAsDataURL(input.files[0]);
    }
}