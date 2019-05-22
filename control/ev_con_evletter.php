<?php 
require_once("../../../include/include.inc.php");
require_once("../model/ev_m_evletter.php");
require_once("../model/ev_m_person.php");

$s_ar_login['user_id'] = set_a_ar_login($s_ar_login['user_id']);// set user if you test in local by pass

/*
List method from ajax
method{
    getEvLetter,
    getRptCard,
    getRptList,
    getEvLetterRptCardDetail,
    getEvLetterRptListDetail,
    deleteEvlt,
    getCompany,
    getFunc,
    getDep,
    getSec,
    getEvLetterById
}
*/

switch ($_POST['method']) {
    case 'getRptCard': echo searchEvLetterCard($_POST['sdate'], $_POST['edate'], $_POST['keyword']); break;
    case 'getRptList': echo searchEvLetterList($_POST['sdate'], $_POST['edate'], $_POST['keyword']); break;
    case 'getEvLetterRptCardDetail': echo getEvLetterRptCardDetail($_POST['evid']); break;
    case 'getEvLetterRptListDetail': echo getEvLetterRptListDetail($_POST['evid']); break;
    case 'deleteEvlt': echo deleteEvlt($_POST['evid']); break;
    case 'getCompany': echo getCompany(null); break;
    case 'getFunc': echo getFunc($_POST['company'], null); break;
    case 'getDep': echo getDep($_POST['func'], null); break;
    case 'getSec': echo getSec($_POST['dep'], null); break;
    case 'getEvLetterById': 
        $mEvid = $_POST['evid'];
        $rpt = getEvLetterById($mEvid);
        echo getFormEdit($rpt[$mEvid]);
        break;
    case 'getFormRecord': echo getFormRecord(); break;
    default:
        echo "not found method call!";
        break;
} 

////////////////// function /////////////////////////
function searchEvLetterCard($sdate, $edate, $keyword){
    $sdate = dmy2ymd($sdate, "en");
    $edate = dmy2ymd($edate, "en");
    $rpt = getArrEvLetterRpt( $sdate, $edate, $keyword, null);
    if(!$rpt)
        $s = "<h4 style='text-align: center;'>ไม่พบข้อมูล</h4>";
    else
        $s = showDivCard($rpt);
    return $s;
}

function searchEvLetterList($sdate, $edate, $keyword){
    $sdate = dmy2ymd($sdate, "en");
    $edate = dmy2ymd($edate, "en");
    $rpt = getArrEvLetterRpt( $sdate, $edate, $keyword, null);
    if(!$rpt)
        $s = "<h4 style='text-align: center;'>ไม่พบข้อมูล</h4>";
    else
        $s = showDivList($rpt);
    return $s;
}

function showDivCard($rpt){
    global $s_ar_login;
    $user = getUserName();
    $s = "<div class='row' style='padding-left:25px;padding-right:25px;'>";
        foreach ($rpt as $evid => $val) {
            $evid        = $val['evid'];
            $title       = $val['title'];
            $descp       = $val['descp'];
            $rec         = $val['rec'];
            $recdatetime = $val['recdatetime'];
            $imgUrl      = getImgCover($val['files']);
            $s .= "<div class='col-sm-4' style='padding:0px;margin-bottom:5px;' id='div$evid'>";
                if($rec == $s_ar_login['user_id']){
                    // if u want to show del, u can set $del = ""; 15-May-2019 10:04 AM
                    $del = "display:none;";
                    $s .= "<div align='right' style='$del'>";
                        $s .= "<img class='delDivCard' src='ev_inc/img/cancel_red.png' onclick='delEventLetter($evid);'>";
                    $s .= "</div>";
                }
                $s .= "<div class='box'>";
                    $s .= "<img class='grid-img imgop' src='$imgUrl' onclick='showDetailCard($evid);'>";
                    $s .= "<div style='background-color: white;padding-bottom: 0px;text-align: left;padding-left: 10px;'>";
                        $s .= "<span onclick='showDetailCard($evid);'>";
                            $s .= "<span class='headSpan head-break' style='font-size: 17px;'>"; // ev_rpt_carddt.php
                                $s .= getShr($title, 40, "...");
                            $s .= "</span>";
                        $s .= "</span>";
                    $s .= "</div>";
                    $s .= "<div class='descp' style='background-color: white;height: 60px;padding: 10px;text-align:left;'>";
                        $link = "";
                        if (utf8_strlen($descp) > 45) {
                            $link = "&nbsp;...&nbsp;<span class='headSpan' onclick='showDetailCard($evid);'>ดูเพิ่มเติม</span>";
                        }
                        $s .= "<span class='head-break'>" . getShr($descp, 45, $link) . "</span>";
                    $s .= "</div>";
                    $s .= "<div class='bar-author'>";
                        $s .= "<span style='padding-left: 2.5px;float:left;font-size: 12px;'>โดย ".$user[$rec]."</span>";
                        $s .= "<span style='padding-right: 2.5px;float:right;font-size: 12px;'>" . getDateX($recdatetime, "d-m-Y") . "</span>";
                    $s .= "</div>";
                $s .= "</div>";#box
            $s .= "</div>";
        }#end foreach
    echo "</div>";#end row
    return $s;
}

function showDivList($rpt){
    // setup style TABLE
    $TH = "font-size: 12px;";
    $TD = "font-size: 12px;padding-right: 0px;vertical-align: top;";
    $TXTC = "text-align:center;";
    $TXTL = "text-align:left;";
    $TXTR = "text-align:right;";
    $W[] = "";
    $W[] = "width:50px;";
    $W[] = "width:50px;";
    $W[] = "";
    $W[] = "";
    $W[] = "";
    $W[] = "";
    $W[] = "";

    $user = getUserName();
    $s = "<table class='dataTableEv display responsive' style='width:100%;'>";
        $s .= "<thead>";
            $s .= "<tr>";
                $s .= "<th style='".$TH.$W[0].$TXTC."'>ลำดับ</th>";
                $s .= "<th style='".$TH.$W[1].$TXTC."'>วันเริ่ม</th>";
                $s .= "<th style='".$TH.$W[2].$TXTC."'>วันสิ้นสุด</th>";
                $s .= "<th style='".$TH.$W[3].$TXTL."'>เรื่อง</th>";
                $s .= "<th style='".$TH.$W[4].$TXTL."'>รายละเอียด</th>";
                $s .= "<th style='".$TH.$W[5].$TXTL."'>เอกสาร</th>";
                $s .= "<th style='".$TH.$W[6].$TXTC."'>คนบันทึก</th>";
                $s .= "<th style='".$TH.$W[7].$TXTC."'>แก้ไข</th>";
            $s .= "</tr>";
        $s .= "</thead>";
        $s .= "<tbody>";
            $n=0;
            foreach ($rpt as $evid => $val) {
                $n++;
                $title = "<span class='head-break span-link'>".$val['title']."</span>";
                $descp = "<span class='head-break span-link'>".$val['descp']."</span>";
                $s .= "<tr id='div$evid'>";
                    $s .= "<td style='".$TD.$TXTC."'>".$n."</td>";
                    $sDate = getDateX($val['sDate'], "d-m-Y");
                    $eDate = getDateX($val['eDate'], "d-m-Y");
                    $s .= "<td style='".$TD.$TXTC."'>".$sDate."</td>";
                    $s .= "<td style='".$TD.$TXTC."'>".$eDate."</td>";
                    $s .= "<td style='".$TD.$TXTL."'>";
                        $s .= "<span onclick='showDetailList($evid)'>".getShr($title, 77, "...")."</span>";
                    $s .= "</td>";
                    $s .= "<td style='".$TD.$TXTL."'>";
                        $s .= "<span onclick='showDetailList($evid)'>".getShr($descp, 200, "...")."</span>";
                    $s .= "</td>";
                    $s .= "<td style='".$TD.$TXTL."'>".getAllStrFile($val['files'])."</td>";
                    $s .= "<td style='".$TD.$TXTC."'><i>".$user[$val['rec']]."<br>".getDateX($val['recdatetime'], "d-m-Y")."</i></td>";

                    // $del is hide, becuase I don't want user delete data, But you can set $dpDel = ""; for show delete on page.
                    $dpDel = "display:none;";
                    $del = "<img class='delDivCard' src='../view/ev_inc/img/cancel_red.png' style='$dpDel' onclick='delEventLetter($evid);'>";
                    $edit = "<img class='img-edit' src='ev_inc/img/edit-document.png' onclick='editEventLetter($evid, \"list\");'>";
                    $s .= "<td style='".$TD.$TXTC."'>".$edit.$del."</td>";
                $s .= "</tr>";
            }
        $s .= "</tbody>";
        $s .= "<tfoot></tfoot>";
    $s .= "</table>";
    return $s;
}#end showDivList()

function getAllStrFile($file){
    $listType = array("jpeg", "jpg", "png", "pdf", "bmp", "tif");
    $s = "<div>";
    for ($i=0; $i < $file['count']; $i++) {
        $path = $file['fpath'][$i];
        if($path == "ev_uploadFile/"){
            $nameori = getShr($file['fnameori'][$i], 15, "...");
            $nameup = $file['fnameup'][$i];
            $type = $file['ftype'][$i];
            $url = mergeUrl($path, $nameup, $type);

            $urlPop = "javascript:void();";
            if(in_array($type, $listType)){
                $urlPop = "javascript:window.open(\"$url\", \"_blank\", \"width=560,height=460\");";
            }else{
                $download = "download";
            }

            $s .= "<a href='$urlPop'><span class='txtupload' $download>".$nameori.",&nbsp;".$type."</span></a>";
            $s .= "<a href='$url' style='margin-left:10px;' download><img src='ev_inc/img/download.png' width='16px'></a>";
            $s .= "<br>";
        }
    }
    $s .= "</div>";
    return $s;
}#end getAllStrFile()

function getAllStrFileDel($file){
    $listType = array("jpeg", "jpg", "png", "pdf", "bmp", "tif");
    $s = "<div>";
    for ($i=0; $i < $file['count']; $i++) {
        $path = $file['fpath'][$i];
        $fileId = $file['fileid'][$i];
        if($path == "ev_uploadFile/"){
            $nameori = getShr($file['fnameori'][$i], 15, "...");
            $nameup = $file['fnameup'][$i];
            $type = $file['ftype'][$i];
            $url = mergeUrl($path, $nameup, $type);

            $urlPop = "javascript:void();";
            if(in_array($type, $listType)){
                $urlPop = "javascript:window.open(\"$url\", \"_blank\", \"width=560,height=460\");";
            }else{
                $download = "download";
            }

            $s .= "<div>";
                $s .= "<img class='icon-cool' alt='ลบ' src='ev_inc/img/cancel_red.png' onclick='delFile(this,".$fileId.", \"".$url."\");' style='margin-right: 10px;'>";
                $s .= "<a href='$urlPop'><span class='txtupload' $download>".$nameori.",&nbsp;".$type."</span></a>";
                $s .= "<a href='$url' style='margin-left:10px;' download><img src='ev_inc/img/download.png' width='16px'></a>";
            $s .= "</div>";
        }
    }
    $s .= "</div>";
    return $s;
}#end getAllStrFile()

function showSize($size){
    if($size>1024)
        $size = round($size/1024) . "&nbsp;KB";
    else
        $size = $size . "&nbsp;Byte";
    return $size;
}#end showSize()

function getShr($txt, $len, $appendText){
    if (utf8_strlen($txt) > $len) {
        $txt = getSubStrTH($txt, 0, $len).$appendText;
    }
    return $txt;
}#end getShr()

// this's must b contain css in ev_rpt_card.php will show img correct
function getEvLetterRptCardDetail($evid){
    $rpt = getArrEvLetterRpt(null, null, null, $evid);
    return componentRptDetail($rpt, $evid, "card");
}#end getEvLetterRptCardDetail()

// this's must b contain css in ev_rpt_card.php will show img correct
function getEvLetterRptListDetail($evid){
    $rpt = getArrEvLetterRpt(null, null, null, $evid);
    return componentRptDetail($rpt, $evid, "list");
}#end getEvLetterRptListDetail()

function componentRptDetail($rpt, $evid, $fromPage){
    $s = "";
    if($rpt){
        $v = $rpt[$evid];
        $vf = $v['files'];
        $s .= componentShowHeadDetail($v);
        $s .= componentShowImgDetail($vf, "320px", "imgSlide[]");
        $s .= componentShowTitleDetail($v);
        $s .= componentShowDateEvent($v);
        $s .= componentShowDescpDetail($v);
        $s .= componentShowAttachFileDetail($vf);
        $s .= componentShowRptDetailFooterDetail($v, $fromPage);
    }
    return $s;
}#end componentRptDetail()

function componentShowHeadDetail($v){
    $s = "<div align='left'>";
        $s .= "<span class='headcom'>บริษัท: </span><span class='headcomdt'>".getCompName($v['comp'])."&nbsp;&nbsp;>>&nbsp;&nbsp;</span>";
        $s .= "<span class='headcom'>สายงาน: </span><span class='headcomdt'>".getFuncName($v['func'])."&nbsp;&nbsp;>>&nbsp;&nbsp;</span>";
        $s .= "<span class='headcom'>ฝ่าย: </span><span class='headcomdt'>".getDepName($v['dep'])."&nbsp;&nbsp;>>&nbsp;&nbsp;</span>";
        $s .= "<span class='headcom'>แผนก: </span><span class='headcomdt'>".getSecName($v['sec'])."</span>";
    $s .= "</div>";
    $s .= "<hr>";
    return $s;
}#end componentShowHeadDetail()

// function componentShowImgDetail($fileArray, $imgHeightForShow, $setElementName); 
function componentShowImgDetail($vf, $height , $element){
    $s = "";
    $s .= "<div style='position:relative;'>";
    $totalShowImg = 0;
    $count = $vf['count'];
    for ($i=0; $i < $count; $i++) { 
        if($vf['fpath'][$i] == 'ev_uploadEventImg/'){
            $totalShowImg++;
        }
    }
    for ($i=0; $i < $count; $i++) {
        $fpath = $vf['fpath'][$i];
        if($fpath == 'ev_uploadEventImg/'){
            $countShowImg++;
            $fnameup = $vf['fnameup'][$i];
            $ftype = $vf['ftype'][$i];
            $url = mergeUrl($fpath, $fnameup, $ftype);
            $dis = "display:none;";
            if($countShowImg==1){
                $dis = "";
            }
            $s .= "<div name='$element' style='text-align: center;".$dis."'>";
                $noImg = ($countShowImg) ." / ". $totalShowImg;
                $s .= "<div class='numbertext'>".$noImg."</div>";
                $s .= "<img src='$url' style='height:$height;'>";
            $s .= "</div>";
        }
    }
    if($countShowImg > 1){
        $onclick = ($count>1) ? "onclick='plusSlides(-1,\"$element\")'":"";
        $s .= "<a class='prevv' $onclick>&#10094;</a>";
        $onclick = ($count>1) ? "onclick='plusSlides(1,\"$element\")'":"";
        $s .= "<a class='nextt' $onclick>&#10095;</a>";       
    }
    $s .= "</div>";
    return $s;
}#end componentShowImgDetail()

// function componentShowImgDetail($fileArray, $imgHeightForShow, $setElementName); 
function componentShowImgDetailDel($vf, $height , $element){
    $s = "";
    $s .= "<div style='position:relative;'>";
        $totalShowImg = 0;
        $count = $vf['count'];
        for ($i=0; $i < $count; $i++) { 
            if($vf['fpath'][$i] == 'ev_uploadEventImg/'){
                $totalShowImg++;
            }
        }
        for ($i=0; $i < $count; $i++) {
            $fpath = $vf['fpath'][$i];
            $fileId = $vf['fileid'][$i];
            if($fpath == 'ev_uploadEventImg/'){
                $countShowImg++;
                $fnameup = $vf['fnameup'][$i];
                $ftype = $vf['ftype'][$i];
                $url = mergeUrl($fpath, $fnameup, $ftype);
                $dis = "display:none;";
                if($countShowImg==1){
                    $dis = "";
                }
                $s .= "<div name='$element' style='text-align: center;".$dis."'>";
                    $noImg = ($countShowImg) ." / ". $totalShowImg;
                    $s .= "<div class='numbertext' name='noImg[]'>".$noImg."</div>";
                    $s .= "<img src='$url' style='margin-right: 5px;height:".$height."'>";
                    $s .= "<img class='delDivCard' style='bottom: 0px;' alt='ลบ' src='ev_inc/img/delete.png' onclick='delImgFile(this,\"$element\",".$fileId.", \"".$url."\");'>";
                $s .= "</div>";
            }
        }
        if($countShowImg > 1){
            $onclick = ($count>1) ? "onclick='plusSlides(-1,\"$element\")'":"";
            $s .= "<a id='prevA' class='prevv' $onclick>&#10094;</a>";
            $onclick = ($count>1) ? "onclick='plusSlides(1,\"$element\")'":"";
            $s .= "<a id='nextA' class='nextt' $onclick>&#10095;</a>";       
        }
    $s .= "</div>";
    return $s;
}#end componentShowImgDetailDel()

function componentShowTitleDetail($v){
    $s = "<div align='center'>";
        $s .= "<h3 id='headTitle'>".$v['title']."</h3>";
    $s .= "</div>";
    return $s;
}#end componentShowTitleDetail()

function componentShowDateEvent($v){
    $s = "";
    $s .= "<div>";
        $date = "วันที่ ".getDateX($v['sDate'], "d-m-Y")." - ".getDateX($v['eDate'], "d-m-Y");
        $time = "";
        if($v['time']){
            $time = "เวลา ".getTimeX($v['time']);
        }
        $s .= "<label style='text-indent: 2.5em;'>".$date."&nbsp;&nbsp;&nbsp;".$time."</label>";
    $s .= "</div>";
    return $s;
}#end componentShowDateEvent()

function componentShowDescpDetail($v){
    $s = "<div>";
        $s .= "<p style='text-indent: 2.5em;'>".$v['descp']."</p>";
    $s .= "</div>";
    $s .= "<br>";
    return $s;
}#end componentShowDescpDetail()

function componentShowAttachFileDetail($v){
    $showAttachFile = "<div>";
        $showAttachFile .= "<p style='text-indent: 2.5em;font-weight:bold;'>เอกสารแนบ</p>";
        $showAttachFile .= "<p style='text-indent: 2.5em;'>";
            $showAttachFile .= "<ul>";
                $listType = array("jpeg", "jpg", "png", "pdf", "bmp", "tif");
                $countShow = 0;
                $count = $v['count'];
                for ($i=0; $i < $count; $i++) {

                    $path = $v['fpath'][$i];
                    
                    if($path == "ev_uploadFile/"){
                        $countShow++;
                        $nameori = getShr($v['fnameori'][$i], 30, "...");
                        $nameup = $v['fnameup'][$i];
                        $type = $v['ftype'][$i];
                        $url = mergeUrl($path, $nameup, $type);
                
                        $urlPop = "javascript:void();";
                        if( in_array($type, $listType) ){
                            $urlPop = "javascript:window.open(\"$url\", \"_blank\", \"width=560,height=460\");";
                        }else{
                            $download = "download";
                        }

                        $showAttachFile .= "<li>";
                            $showAttachFile .= "<a href='".$urlPop."' $download><span class='txtupload'>".$nameori.".".$type."</span></a>";
                            $showAttachFile .= "<a href='".$url."' style='margin-left:10px;' download><img src='ev_inc/img/download.png' width='16px'></a>";
                        $showAttachFile .= "</li>";
                    }
                }
            $showAttachFile .= "</ul>";
        $showAttachFile .= "</p>";
    $showAttachFile .= "</div>";
    if($countShow==0)
        return "";
    return $showAttachFile;
}#end componentShowAttachFileDetail()

function componentShowRptDetailFooterDetail($v, $fromPage){
    global $s_ar_login;
    $s = "<div align='right'>";
        $s .= "<label>บันทึกล่าสุด: ".getUserNameById($v['rec']).", ".getDateX($v['recdatetime'], "d-m-Y")."</label>";
    $s .= "</div>";
    $s .= "<div align='center'>";
        $s .= "<input type='button' class='btn btn-primary' onclick='back2Rpt();' id='btnBack2Rpt' value='<< ย้อนกลับ'>";
        if($s_ar_login['user_id'] == $v['rec']){
            $type       = "button";
            $class      = "btn btn-warning";
            $id         = "btnEditRpt";
            $onclick    = "editRptDetail(".$v['evid'].",\"".$fromPage."\")";// editRptDetail(p1, p2) at ev_getEvLetterDetail.js
            $style      = "margin-left:15px;";
            $value      = "แก้ไขเอกสาร";
            $s .= "<input type='$type' class='$class' onclick='$onclick' id='$id' style='$style' value='$value'>";
        }
    $s .= "</div>";
    return $s;
}#end componentShowRptDetailFooterDetail()

function getFormRecord(){
    $s = "";
    // $fillCol = "background-color: #f5f5dc;";
    $s .= "<div class='form-group' style='margin-top:15px;'>";
        $s .= "<div class='row'>";
            $s .= "<div class='col-md-3'>";
                $s .= "<label>บริษัท</label>";
                $s .= "<select class='form-control' name='comp' id='comp' onchange='getFunc()' style='$fillCol'>";
                    $s .= getCompany();
                $s .= "</select>";
            $s .= "</div>";
            $s .= "<div class='col-md-3'>";
                $s .= "<label>สายงาน</label>";
                $s .= "<select class='form-control' name='func' id='func' onchange='getDep()' style='$fillCol' disabled>";
                    $s .= "<option> เลือก </option>";
                $s .= "</select>";
            $s .= "</div>";
            $s .= "<div class='col-md-3'>";
                $s .= "<label>ฝ่าย</label>";
                $s .= "<select class='form-control' name='dep' id='dep' onchange='getSec()' style='$fillCol' disabled>";
                    $s .= "<option> เลือก </option>";
                $s .= "</select>";
            $s .= "</div>";
            $s .= "<div class='col-md-3'>";
                $s .= "<label>แผนก</label>";
                $s .= "<select class='form-control' name='sec' id='sec' style='$fillCol' disabled>";
                    $s .= "<option> เลือก </option>";
                $s .= "</select>";
            $s .= "</div>";
        $s .= "</div>";
    $s .= "</div>";

    $s .= "<div class='form-group' style='margin-top:25px;'>";
        $s .= "<div class='row'>";
            $s .= "<div class='col-md-6'>";
                $s .= "<label>หัวข้อ</label>";
                $s .= "<input type='text' class='form-control' name='title' id='title' style='$fillCol'>";
            $s .= "</div>";
            $s .= "<div class='col-md-3'>";
                $s .= "<label>วันที่เริ่มกิจกรรม</label>";
                $s .= "<table style='width:100%;'>";
                    $s .= "<tr>";
                        $s .= "<td style='width: 50%;'>";
                            $s .= "<input type='text' class='datepicker form-control' name='startDate' id='startDate' value='' autocomplete='off' style='$fillCol' placeholder='dd-mm-yyyy'>";
                        $s .= "</td>";
                        $s .= "<td style='width: 50%;'>";
                            $s .= "<div class='input-group clockpicker'>";
                                $s .= "<input type='text' class='form-control' name='startTime' id='startTime' value='' readonly='readonly' autocomplete='off' style='background-color: #FFFFFF;$fillCol' placeholder='เวลา'>";
                                $s .= "<span class='input-group-addon'>";
                                    $s .= "<span class='glyphicon glyphicon-time'></span>";
                                $s .= "</span>";
                            $s .= "</div>";
                        $s .= "</td>";
                    $s .= "</tr>";
                $s .= "</table>";
            $s .= "</div>";
            $s .= "<div class='col-md-3'>";
                $s .= "<label>วันสิ้นสุดกิจกรรม</label>";
                $s .= "<table style='width:100%;'>";
                    $s .= "<tr>";
                        $s .= "<td style='width: 50%;'>";
                            $s .= "<input type='text' class='datepicker form-control' name='endDate' id='endDate' value='' autocomplete='off' style='$fillCol' placeholder='dd-mm-yyyy'>";
                        $s .= "</td>";
                        $s .= "<td style='width: 50%;'>";
                            $s .= "<div class='input-group clockpicker'>";
                                $s .= "<input type='text' class='form-control' name='endTime' id='endTime' value='".getTimeForm($rpt['eTime'])."' readonly='readonly' autocomplete='off' style='background-color: #FFFFFF;$fillCol' placeholder='เวลา'>";
                                $s .= "<span class='input-group-addon'>";
                                    $s .= "<span class='glyphicon glyphicon-time'></span>";
                                $s .= "</span>";
                            $s .= "</div>";
                        $s .= "</td>";
                    $s .= "</tr>";
                $s .= "</table>";
            $s .= "</div>";
        $s .= "</div>";
    $s .= "</div>";

    $s .= "<div class='form-group' style='margin-top:25px;'>";
        $s .= "<div class='row'>";
            $s .= "<div class='col-md-6'>";
                $s .= "<label>รายละเอียด</label>";
                $s .= "<textarea class='form-control' rows='5' name='descp' style='height: 150px;$fillCol'>";
                $s .= "</textarea>";
            $s .= "</div>";
            $s .= "<div class='col-md-6'>";
                $s .= "<label>อัพโหลดเอกสาร</label><span style='color:red;'> (จำกัดไฟล์ไม่เกิน 2 MB)</span>";
                $s .= "<table style='width:100%;height: 150px;'>";
                    $s .= "<tr>";
                        $s .= "<td style='width:100px;padding:0px;text-align: left;vertical-align: top;'>";
                            $s .= "<button type='button' class='btn btn-default btn-sm' onclick='uploadFilesFromBtn()' style='background-color: darkgoldenrod;color: white;'>";
                                $s .= "<span class='glyphicon glyphicon-open'></span> Upload";
                            $s .= "</button>";
                        $s .= "</td>";
                        $s .= "<td style='padding-top: 5px;vertical-align: top;'>";
                            $s .= "<div>";
                                $s .= "<span id='countFile' style='text-align:left;font-size: 15px;font-weight: bold;'>Total File: 0</span>";
                            $s .= "</div>";
                        $s .= "</td>";
                    $s .= "</tr>";
                    $s .= "<tr>";
                        $s .= "<td colspan='2' style='padding:0px;vertical-align: bottom;'>";
                            $s .= "<div style='height: 100px; overflow-y: scroll;border: 1px solid greenyellow;$fillCol'>";
                                $s .= "<span id='listNameFile'></span>";
                            $s .= "</div>";
                            $s .= "<div id='listInputFile'>";
                                $s .= "<input type='file' onchange='showName();' style='display:none;' name='ev_uploadFile[]'>";
                            $s .= "</div>";
                        $s .= "</td>";
                    $s .= "</tr>";
                $s .= "</table>";
            $s .= "</div>";
        $s .= "</div>";
    $s .= "</div>";

    $s .= "<div class='form-group' style='margin-top:25px;'>";
        $s .= "<div class='row'>";
            $s .= "<div class='col-md-12'>";
                $s .= "<label>เพิ่มรูปภาพงาน</label><span style='color:red;'> (ขนาดรูปไม่เกิน 2 MB)</span>";
                $s .= "<table border='0' style='width:100%;height: 150px;'>";
                    $s .= "<tr>";
                        $s .= "<td style='width:100px;padding:0px;text-align: left;'>";
                            $s .= "<button type='button' class='btn btn-warning btn-sm' onclick='uploadImgFromBtn()' style='background-color: darkgoldenrod;color: white;'>";
                                $s .= "<span class='glyphicon glyphicon-picture'></span> Upload";
                            $s .= "</button>";
                        $s .= "</td>";
                        $s .= "<td style='padding:0px;'>";
                            $s .= "<div>";
                                $s .= "<span id='countEventImage' style='text-align:left;font-size: 15px;font-weight: bold;'>Total Image: 0</span>";
                            $s .= "</div>";
                        $s .= "</td>";
                    $s .= "</tr>";
                    $s .= "<tr>";
                        $s .= "<td colspan='2' style='padding:0px;'>";
                            $s .= "<div class='row' style='padding-left: 15px;padding-right: 15px;margin-bottom: 0px;'>";
                                $s .= "<div class='col-md-12' id='listEventImg' style='height: 90px; overflow-y: scroll;$fillCol;border: 1px solid greenyellow;'>";
                                    $s .= "<h5>List image</h5>";
                                $s .= "</div>";
                            $s .= "</div>";
                            $s .= "<div class='row' style='padding-left: 15px;padding-right: 15px;margin-bottom: 0px;'>";
                                $s .= "<div class='col-md-12' id='previewImg' style='height: 150px; overflow-y: scroll;$fillCol;border: 1px solid greenyellow;display:none;'>";
                                    $s .= "<img name='ev_previewEventImg[]' src='' style='height:150px;margin:2.5px;'>";
                                $s .= "</div>";
                            $s .= "</div>";
                            $s .= "<div id='listInputEventImg'>";
                                $s .= "<input type='file' onchange='showEventImage(this);' style='display:none;' name='ev_uploadEventImg[]' accept='image/x-png,image/gif,image/jpeg'>";
                            $s .= "</div>";
                        $s .= "</td>";
                    $s .= "</tr>";
                $s .= "</table>";
            $s .= "</div>";
        $s .= "</div>";
    $s .= "</div>";

    $s .= "<div class='form-group'>";
        $s .= "<div class='row'>";
            $s .= "<div class='col-md-12 text-center'>";
                $s .= "<input type='submit' class='btn btn-danger submitBtn' value='บันทึก' style='width: 140px;font-size: 16px;font-weight: bold;' />";
                $s .= "<input type='button' class='btn btn-warning' value='ยกเลิก' style='width: 140px;margin-left:15px;font-size: 16px;font-weight: bold;' onclick='back2Rpt();' />";
            $s .= "</div>";
        $s .= "</div>";
    $s .= "</div>";
    $s .= "<input type='hidden' name='method' value='setEvletter'>";
    return $s;
}

function getFormEdit($rpt){
    $s = "";
    $fillCol = "background-color: #f5f5dc;";
    $s .= "<input type='hidden' name='evid' id='evid' value='".$rpt['evid']."'>";
    $s .= "<div class='form-group' style='margin-top:15px;'>";
        $s .= "<div class='row'>";
            $s .= "<div class='col-md-3'>";
                $s .= "<label>บริษัท</label>";
                $s .= "<select class='form-control' name='comp' id='comp' onchange='getFunc()' style='$fillCol'>";
                    $s .= getCompany($rpt['comp']);
                $s .= "</select>";
                $s .= "<input type='hidden' value='".$rpt['comp']."' name='tmp_comp'>";
            $s .= "</div>";
            $s .= "<div class='col-md-3'>";
                $s .= "<label>สายงาน</label>";
                $s .= "<select class='form-control' name='func' id='func' onchange='getDep()' style='$fillCol'>";
                    $s .= getFunc($rpt['comp'], $rpt['func']);
                $s .= "</select>";
                $s .= "<input type='hidden' value='".$rpt['func']."' name='tmp_func'>";
            $s .= "</div>";
            $s .= "<div class='col-md-3'>";
                $s .= "<label>ฝ่าย</label>";
                $s .= "<select class='form-control' name='dep' id='dep' onchange='getSec()' style='$fillCol'>";
                    $s .= getDep($rpt['func'], $rpt['dep']);
                $s .= "</select>";
                $s .= "<input type='hidden' value='".$rpt['dep']."' name='tmp_dep'>";
            $s .= "</div>";
            $s .= "<div class='col-md-3'>";
                $s .= "<label>แผนก</label>";
                $s .= "<select class='form-control' name='sec' id='sec' style='$fillCol'>";
                    $s .= getSec($rpt['dep'], $rpt['sec']);
                $s .= "</select>";
                $s .= "<input type='hidden' value='".$rpt['sec']."' name='tmp_sec'>";
            $s .= "</div>";
        $s .= "</div>";
    $s .= "</div>";

    $s .= "<div class='form-group' style='margin-top:25px;'>";
        $s .= "<div class='row'>";
            $s .= "<div class='col-md-6'>";
                $s .= "<label>หัวข้อ</label>";
                $s .= "<input type='text' class='form-control' name='title' id='title' value='".$rpt['title']."' style='$fillCol'>";
                $s .= "<input type='hidden' value='".$rpt['title']."' name='tmp_title'>";
            $s .= "</div>";
            $s .= "<div class='col-md-3'>";
                $s .= "<label>วันที่เริ่มกิจกรรม</label>";
                $s .= "<table style='width:100%;'>";
                    $s .= "<tr>";
                        $s .= "<td style='width: 50%;'>";
                            $s .= "<input type='text' class='datepicker form-control' name='startDate' id='startDate' value='".date("d-m-Y", strtotime($rpt['sDate']))."' autocomplete='off' style='$fillCol' placeholder='dd-mm-yyyy'>";
                            $s .= "<input type='hidden' value='".date("d-m-Y", strtotime($rpt['sDate']))."' name='tmp_startDate'>";        
                        $s .= "</td>";
                        $s .= "<td style='width: 50%;'>";
                            $s .= "<div class='input-group clockpicker'>";
                                $s .= "<input type='text' class='form-control' name='startTime' id='startTime' value='".getTimeForm($rpt['sTime'])."' readonly='readonly' autocomplete='off' style='background-color: #FFFFFF;$fillCol' placeholder='เวลา'>";
                                $s .= "<span class='input-group-addon'>";
                                    $s .= "<span class='glyphicon glyphicon-time'></span>";
                                $s .= "</span>";
                            $s .= "</div>";
                            $s .= "<input type='hidden' value='".getTimeForm($rpt['sTime'])."' name='tmp_startTime'>";
                        $s .= "</td>";
                    $s .= "</tr>";
                $s .= "</table>";
            $s .= "</div>";
            $s .= "<div class='col-md-3'>";
                $s .= "<label>วันสิ้นสุดกิจกรรม</label>";
                $s .= "<table style='width:100%;'>";
                    $s .= "<tr>";
                        $s .= "<td style='width: 50%;'>";
                            $s .= "<input type='text' class='datepicker form-control' name='endDate' id='endDate' value='".date("d-m-Y", strtotime($rpt['eDate']))."' autocomplete='off' style='$fillCol' placeholder='dd-mm-yyyy'>";
                            $s .= "<input type='hidden' value='".date("d-m-Y", strtotime($rpt['eDate']))."' name='tmp_endDate'>";        
                        $s .= "</td>";
                        $s .= "<td style='width: 50%;'>";
                            $s .= "<div class='input-group clockpicker'>";
                                $s .= "<input type='text' class='form-control' name='endTime' id='endTime' value='".getTimeForm($rpt['eTime'])."' readonly='readonly' autocomplete='off' style='background-color: #FFFFFF;$fillCol' placeholder='เวลา'>";
                                $s .= "<span class='input-group-addon'>";
                                    $s .= "<span class='glyphicon glyphicon-time'></span>";
                                $s .= "</span>";
                            $s .= "</div>";
                            $s .= "<input type='hidden' value='".getTimeForm($rpt['eTime'])."' name='tmp_endTime'>";
                        $s .= "</td>";
                    $s .= "</tr>";
                $s .= "</table>";
            $s .= "</div>";
        $s .= "</div>";
    $s .= "</div>";

    $s .= "<div class='form-group' style='margin-top:25px;'>";
        $s .= "<div class='row'>";
            $s .= "<div class='col-md-6'>";
                $s .= "<label>รายละเอียด</label>";
                $s .= "<textarea class='form-control' rows='5' name='descp' style='height: 150px;$fillCol'>";
                    $s .= $rpt['descp'];
                $s .= "</textarea>";
                $s .= "<input type='hidden' value='".($rpt['descp'])."' name='tmp_descp'>";
            $s .= "</div>";
            $s .= "<div class='col-md-6'>";
                $s .= "<label>อัพโหลดเอกสาร</label><span style='color:red;'> (จำกัดไฟล์ไม่เกิน 2 MB)</span>";
                $s .= "<table style='width:100%;height: 150px;'>";
                    $s .= "<tr>";
                        $s .= "<td style='width:100px;padding:0px;text-align: left;vertical-align: top;'>";
                            $s .= "<button type='button' class='btn btn-default btn-sm' onclick='uploadFilesFromBtn()' style='background-color: darkgoldenrod;color: white;'>";
                                $s .= "<span class='glyphicon glyphicon-open'></span> Upload";
                            $s .= "</button>";
                        $s .= "</td>";
                        $s .= "<td style='padding-top: 5px;vertical-align: top;'>";
                            $s .= "<div>";
                                $s .= "<span id='countFile' style='text-align:left;font-size: 15px;font-weight: bold;'>Total File: 0</span>";
                            $s .= "</div>";
                        $s .= "</td>";
                    $s .= "</tr>";
                    $s .= "<tr>";
                        $s .= "<td colspan='2' style='padding:0px;vertical-align: bottom;'>";
                            $s .= "<div style='height: 100px; overflow-y: scroll;border: 1px solid greenyellow;$fillCol'>";
                                $s .= "<span id='listNameFile'></span>";
                            $s .= "</div>";
                            $s .= "<div id='listInputFile'>";
                                $s .= "<input type='file' onchange='showName();' style='display:none;' name='ev_uploadFile[]'>";
                            $s .= "</div>";
                        $s .= "</td>";
                    $s .= "</tr>";
                $s .= "</table>";
            $s .= "</div>";
        $s .= "</div>";
    $s .= "</div>";

    $s .= "<div class='form-group' style='margin-top:25px;'>";
        $s .= "<div class='row'>";
            $s .= "<div class='col-md-12'>";
                $s .= "<label>เพิ่มรูปภาพงาน</label><span style='color:red;'> (ขนาดรูปไม่เกิน 2 MB)</span>";
                $s .= "<table border='0' style='width:100%;height: 150px;'>";
                    $s .= "<tr>";
                        $s .= "<td style='width:100px;padding:0px;text-align: left;'>";
                            $s .= "<button type='button' class='btn btn-warning btn-sm' onclick='uploadImgFromBtn()' style='background-color: darkgoldenrod;color: white;'>";
                                $s .= "<span class='glyphicon glyphicon-picture'></span> Upload";
                            $s .= "</button>";
                        $s .= "</td>";
                        $s .= "<td style='padding:0px;'>";
                            $s .= "<div>";
                                $s .= "<span id='countEventImage' style='text-align:left;font-size: 15px;font-weight: bold;'>Total Image: 0</span>";
                            $s .= "</div>";
                        $s .= "</td>";
                    $s .= "</tr>";
                    $s .= "<tr>";
                        $s .= "<td colspan='2' style='padding:0px;'>";
                            $s .= "<div class='row' style='padding-left: 15px;padding-right: 15px;margin-bottom: 0px;'>";
                                $s .= "<div class='col-md-12' id='listEventImg' style='height: 90px; overflow-y: scroll;$fillCol;border: 1px solid greenyellow;'>";
                                    $s .= "<h5>List image</h5>";
                                $s .= "</div>";
                            $s .= "</div>";
                            $s .= "<div class='row' style='padding-left: 15px;padding-right: 15px;margin-bottom: 0px;'>";
                                $s .= "<div class='col-md-12' id='previewImg' style='height: 150px; overflow-y: scroll;$fillCol;border: 1px solid greenyellow;display:none;'>";
                                    $s .= "<img name='ev_previewEventImg[]' src='' style='height:150px;margin:2.5px;'>";
                                $s .= "</div>";
                            $s .= "</div>";
                            $s .= "<div id='listInputEventImg'>";
                                $s .= "<input type='file' onchange='showEventImage(this);' style='display:none;' name='ev_uploadEventImg[]' accept='image/x-png,image/gif,image/jpeg'>";
                            $s .= "</div>";
                        $s .= "</td>";
                    $s .= "</tr>";
                $s .= "</table>";
            $s .= "</div>";
        $s .= "</div>";
    $s .= "</div>";

    $height = "200px;";
    $styleFile = "background-color: #f2f2f2;border: 1px solid greenyellow;height:$height";
    $s .= "<div class='form-group'>";
        $s .= "<div class='row'>";
            $s .= "<div class='col-md-8 text-left'>";
                $s .= "<label>รูปภาพที่มีแล้ว</label>";
                $s .= "<div style='".$styleFile."'>";
                    $s .= componentShowImgDetailDel($rpt['files'], $height, "imgSlide[]");
                $s .= "</div>";
            $s .= "</div>";
            $s .= "<div class='col-md-4 text-left'>";
                $s .= "<label>ไฟล์ที่อัพโหลดแล้ว</label>";
                $s .= "<div style='".$styleFile."padding: 10px;'>";
                    $s .= getAllStrFileDel($rpt['files']);
                $s .= "</div>";
            $s .= "</div>";
        $s .= "</div>";
    $s .= "</div>";

    $s .= "<div class='form-group'>";
        $s .= "<div class='row'>";
            $s .= "<div class='col-md-12 text-center'>";
                $s .= "<input type='submit' class='btn btn-danger submitBtn' value='บันทึกการแก้ไข' style='width: 140px;font-size: 16px;font-weight: bold;' />";
                $s .= "<input type='button' class='btn btn-warning' value='ยกเลิก' style='width: 140px;margin-left:15px;font-size: 16px;font-weight: bold;' onclick='back2Rpt();' />";
            $s .= "</div>";
        $s .= "</div>";
    $s .= "</div>";
    $s .= "<input type='hidden' name='method' value='updateEvLetter'>";
    return $s;
}

////////////////// end function //////////////////////

function getCompany($key = ""){
    $lc_html = "";
    $data = getCompanyDB();
    if ($data) {
        $sel = "";
        $lc_html = "<option value=''>เลือก</option>\n";
        for ($i = 0; $i < count($data); $i++) {
            if ($key) {
                if ($key == $data[$i]["m_comp"]) $sel = "SELECTED";
                else $sel = "";
            }
            $lc_html .= "<option value='" . $data[$i]["m_comp"] . "' " . $sel . ">" . $data[$i]["m_comp_name_th"] . "</option>\n";
        }
    }
    return $lc_html;
}

function getFunc($comp, $key = ""){
    $lc_html = "";
    if ($comp) {
        $data = getFuncDB($comp);
        if ($data) {
            $sel = "";
            $lc_html = "<option value=''>เลือก</option>\n";
            for ($i = 0; $i < count($data); $i++) {
                if ($key) {
                    if ($key == $data[$i]["m_compfunc"]) $sel = "SELECTED";
                    else $sel = "";
                }
                $lc_html .= "<option value='" . $data[$i]["m_compfunc"] . "' " . $sel . ">" . $data[$i]["m_compfunc_name_th"] . "</option>\n";
            }
        }
    }
    return $lc_html;
}

function getDep($func, $key = ""){
    $lc_html = "";
    if ($func) {
        $data = getDepDB($func);
        if ($data) {
            $sel = "";
            $lc_html = "<option value=''>เลือก</option>\n";
            for ($i = 0; $i < count($data); $i++) {
                if ($key) {
                    if ($key == $data[$i]["m_compfuncdep"]) $sel = "SELECTED";
                    else $sel = "";
                }
                $lc_html .= "<option value='" . $data[$i]["m_compfuncdep"] . "' " . $sel . ">" . $data[$i]["m_compfuncdep_name_th"] . "</option>\n";
            }
        }
    }
    return $lc_html;
}

function getSec($dep, $key = ""){
    $lc_html = "";
    if ($dep) {
        $data = getSecDB($dep);
        if ($data) {
            $sel = "";
            $lc_html = "<option value=''>เลือก</option>\n";
            for ($i = 0; $i < count($data); $i++) {
                if ($key) {
                    if ($key == $data[$i]["m_compfuncdepsec"]) $sel = "SELECTED";
                    else $sel = "";
                }
                $lc_html .= "<option value='" . $data[$i]["m_compfuncdepsec"] . "' " . $sel . ">" . $data[$i]["m_compfuncdepsec_name_th"] . "</option>\n";
            }
        }
    }
    return $lc_html;
}

