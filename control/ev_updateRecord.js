/* 2019-May-15
* Pichet Saelai
* v.1.0
*/

$(function () {
    var evid = $('#evid').val();
    loadDataForUpdate(evid);

    $('#frmEditEvLetter').on('submit', function (e) {
        e.preventDefault();
        if (preventNull()) {
            var r = confirm("ยืนยันการบันทึก!");
            if (r == true) {
                $.ajax({
                    type: 'post',
                    url: '../model/ev_m_evletter.php',
                    data: new FormData(this),// method is updateEvLetter
                    // dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (response) {
                        // if (response) $('#test_response').html(response);
                        location.href = "ev_rpt.php?showStyle=" + $('#showStyle').val();
                    }
                });
            }
        }
    });
});

function loadDataForUpdate(pEvid) {
    $.ajax({
        type: 'post',
        url: '../control/ev_con_evletter.php',
        data: {
            method: 'getEvLetterById',
            evid: pEvid
        },
        dataType: "html",
        success: function (response) {
            $('#frmEditEvLetter').html(response);

            $('.datepicker').datepicker({
                dateFormat: 'dd-mm-yy'
                // minDate: new Date()
            });
            $('.clockpicker').clockpicker({
                placement: 'left',
                align: 'top',
                donetext: 'Done'
            });
        }
    });
}

function plusSlides(n, elem) {
    var imgSlide = document.getElementsByName(elem);
    var imgLen = imgSlide.length;
    var tmpIndex = 0;
    var lastKey = imgLen - 1;
    if (n == 1) {
        for (var i = 0; i < imgLen; i++) {
            if (imgSlide[i].style.display == "") {
                tmpIndex = i;
                break;
            }
        }
        if (tmpIndex < lastKey) {
            $(imgSlide[tmpIndex + 1]).fadeIn('slow');
            imgSlide[tmpIndex].style.display = "none";
        } else {
            $(imgSlide[0]).fadeIn('slow');
            imgSlide[tmpIndex].style.display = "none";
        }
    } else if (n == -1) {
        for (var i = 0; i < imgLen; i++) {
            if (imgSlide[i].style.display == "") {
                tmpIndex = i;
                break;
            }
        }
        if (tmpIndex == 0) {
            $(imgSlide[lastKey]).fadeIn('slow');
            imgSlide[tmpIndex].style.display = "none";
        } else {
            $(imgSlide[tmpIndex - 1]).fadeIn('slow');
            imgSlide[tmpIndex].style.display = "none";
        }
    }
}

function plusSlidesDel(elem, elemName) {
    var imgSlide = document.getElementsByName(elemName);
    var nodeImg = imgSlide.length;

    removeAtParent(elem);// remove node
    nodeImg = nodeImg - 1;// decrease node

    if (nodeImg > 0) {
        $(imgSlide[0]).fadeIn('slow');

        // refresh number
        var noImg = document.getElementsByName('noImg[]');
        for (var i = 0; i < noImg.length; i++) {
            noImg[i].innerHTML = (i + 1).toString() + " / " + noImg.length;
        }
    }
    if (nodeImg == 1) {
        $('#prevA').css("display", "none");
        $('#nextA').css("display", "none");
    }
}

function delFileUpload(pFileId, pUrl) {
    $.ajax({
        type: 'post',
        url: '../model/ev_m_evletter.php',
        data: {
            method: 'delFileUpload',
            fileId: pFileId,
            url: pUrl
        },
        dataType: "html",
        success: function (response) {
            console.log(response);
        }
    });
}

function delImgFile(elem, elemName, pFileId, pUrl) {
    var r = confirm("ยืนยันการลบ!");
    if (r == true) {
        plusSlidesDel(elem, elemName);// remove element
        delFileUpload(pFileId, pUrl);// update db 
    }
}

function delFile(elem, pFileId, pUrl) {
    var r = confirm("ยืนยันการลบ!");
    if (r == true) {
        removeAtParent(elem);
        delFileUpload(pFileId, pUrl);// update db 
    }
}

function removeAtParent(elem) {
    var div = elem.parentNode;
    var divv = div.parentNode;
    divv.removeChild(div);
}