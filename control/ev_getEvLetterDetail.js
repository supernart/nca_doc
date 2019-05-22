/* 2019-May-09
* Pichet Saelai
* v.1
*/

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

function editRptDetail(evid, showStyle) {
    location.href = "ev_edit.php?evid=" + evid + "&showStyle="+showStyle;
}