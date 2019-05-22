<?php

require_once("../../../include/include.inc.php");

/*
List method from ajax
method{
    setEvletter
}
*/


if(!$s_ar_login['user_id']) $s_ar_login['user_id'] = 666;

if ($_POST['method'] == 'setEvletter') {
    $mDatetime = ncanow();
    $mTitle = $_POST['title'];
    $userId = $s_ar_login['user_id'];
    $status['status_letter'] = setEvLetter($_POST, $mDatetime);// save to DB
    $evletter = getEvLetterIdBy3Par($mTitle, $mDatetime, $userId);
    $status['status_upload'] = setFile($_FILES, $evletter, $mDatetime);// save file at url server
    $status['status'] = "ok";
    echo json_encode($status);
} else if($_POST['method'] == 'updateEvLetter'){
    echo updateEvLetter($_POST, $_FILES);
} else if($_POST['method'] == 'delFileUpload'){
    echo delFileUpload($_POST['fileId'], $_POST['url']);
}

// convert date d-m-Y b4
function getRptEvCalendar($sdate="", $edate="", $keyword=""){
    $objDb = new ncadb();
    if($sdate && $edate)
        $wDate = " AND evletter_start_date between '$sdate' AND '$edate' ";
    elseif($sdate)
        $wDate = " AND evletter_start_date = '$sdate' ";
    elseif($edate)
        $wDate = " AND evletter_start_date = '$edate' ";
    if(trim($keyword))
        $wKeyword = " AND evletter_title LIKE '%$keyword%' ";

    $where = " 1 = 1 ".$wDate.$wKeyword;
    $sql = "SELECT	e.evletter AS evid, 
                    e.evletter_title AS title,
                    e.evletter_start_date AS sDate,
                    e.evletter_start_time AS sTime,
                    e.evletter_end_date AS eDate,
                    e.evletter_end_time AS eTime
            FROM evletter e
            WHERE ".$where." AND e.evletter_active = '1' ";
    $data = $objDb->ncaretrieve($sql, "ncadoc");
    for ($i=0; $i < count($data); $i++) {
        $evid = $data[$i]["evid"];
        $title = $data[$i]["title"];
        // $color = "red";
        $sTime = $eTime = "";
        if($data[$i]["sTime"] && $data[$i]["eTime"]){
            $sTime  = "T".$data[$i]["sTime"];
            $eTime  = "T".$data[$i]["eTime"];
            $start  = $data[$i]["sDate"].$sTime;
            $end    = $data[$i]["eDate"].$eTime;
        }
        else{
            $start  = $data[$i]["sDate"];
            $end    = date ("Y-m-d", strtotime("+1 day", strtotime($data[$i]["eDate"])));
        }

        $calen[] = array(
            "evid" => $evid,
            "title" => $title,
            "start" => $start,
            "end" => $end,
            // "color" => $color
        );
    }
    return $calen;
}

//copy to method=>setEvletter: test_SetEvLetter($_FILES, $evletter, $mDatetime);
function test_SetEvLetter($_FILES, $evletter, $mDatetime){
    echo "<pre>".print_r($_FILES, true)."</pre>";
    echo "evletter: ".$evletter."<br>";
    echo "mDatetime: ".$mDatetime."<br>";
}

function test_getSqlEvLetter($evid){
    if($evid) 
        $where = " AND evletter = ".$evid;
    $sql = "SELECT	e.evletter, 
                    e.evletter_title,
                    e.evletter_descp,
                    e.evletter_start_date,
                    e.evletter_start_time,
                    e.evletter_end_date,
                    e.evletter_end_time,
                    e.evletter_comp,
                    e.evletter_func,
                    e.evletter_dep,
                    e.evletter_sec,
                    e.evletter_recid,
                    e.evletter_recdatetime,
                    ef.evfile,
                    ef.evfile_datetime,
                    ef.evfile_nameoriginal,
                    ef.evfile_nameupload,
                    ef.evfile_path,
                    ef.evfile_size,
                    ef.evfile_type
            FROM evletter e
            LEFT JOIN evfile ef ON ef.evfile_evletter = e.evletter AND evfile_active = '1'
            WHERE   1 = 1 ".$where." AND e.evletter_active = '1' ";
    return $sql;
}

function setEvLetter($data, $datetime){
    global $s_ar_login;
    foreach ($data as $k => $v) {
        ${$k} = $v;
    }
    
    $objDb = new ncadb();
    $objBuild = new SqlBuilder();
    $objBuild->SetTableName("evletter");
    $x = 0;
    $objTf = null;
    $objTf[$x++] = new tfield("evletter_title",         strip_tags($title),   "string");
    $objTf[$x++] = new tfield("evletter_start_date",    dmy2ymd($startDate, 'de'),  "string");
    $objTf[$x++] = new tfield("evletter_start_time",    $startTime,                 "string");
    $objTf[$x++] = new tfield("evletter_end_date",      dmy2ymd($endDate, 'de'),    "string");
    $objTf[$x++] = new tfield("evletter_end_time",      $endTime,                   "string");
    $objTf[$x++] = new tfield("evletter_descp",         strip_tags($descp),   "string");
    $objTf[$x++] = new tfield("evletter_comp",          $comp,                      "string");
    $objTf[$x++] = new tfield("evletter_func",          $func,                      "string");
    $objTf[$x++] = new tfield("evletter_dep",           $dep,                       "string");
    $objTf[$x++] = new tfield("evletter_sec",           $sec,                       "string");
    $objTf[$x++] = new tfield("evletter_recid",         $s_ar_login['user_id'],     "string");
    $objTf[$x++] = new tfield("evletter_recdatetime",   $datetime,                  "string");
    $objTf[$x++] = new tfield("evletter_active",        1,                          "string");
    $objBuild->SetField($objTf);
    $sql_insert = $objBuild->InsertSql();
    if (!$objDb->ncaexec($sql_insert, "ncadoc")) {
        $objDb->ncarollback("ncadoc");
        $msg = "ERROR!! SQL:" . $sql_insert;
    } else {
        $msg = "save letter : ok";
    }

    saveLogInsert("title",  $title, $datetime);
    saveLogInsert("date",   dmy2ymd($date, 'de'), $datetime);
    saveLogInsert("time",   $time, $datetime);
    saveLogInsert("descp",  $descp, $datetime);
    saveLogInsert("company",$comp, $datetime);
    saveLogInsert("func",   $func, $datetime);
    saveLogInsert("dep",    $dep, $datetime);
    saveLogInsert("sec",    $sec, $datetime);

    return $msg;
}#end setEvLetter()

function saveLogInsert($head, $descp, $datetime){
    if($descp){
        global $s_ar_login;    
        $objDb = new ncadb();
        $objBuild = new SqlBuilder();
        $objBuild->SetTableName("evletter");
        $x = 0;
        $objTf = null;
        $objBuild->SetTableName("evlog");
        $x = 0;
        $objTf = null;
        $objTf[$x++] = new tfield("evlog_table",    "evletter",             "string");
        $objTf[$x++] = new tfield("evlog_action",   "insert",               "string");
        $objTf[$x++] = new tfield("evlog_descp",    $head.": ".$descp,      "string");
        $objTf[$x++] = new tfield("evlog_usr",      $s_ar_login['user_id'], "string");
        $objTf[$x++] = new tfield("evlog_datetime", $datetime,              "string");
        $objBuild->SetField($objTf);
        $sql_insert = $objBuild->InsertSql();
        $objDb->ncaexec($sql_insert, "ncadoc");
    }
}

function setFile($rootFile, $evletter, $datetime){
    global $s_ar_login;
    $objDb = new ncadb();
    $objBuild = new SqlBuilder();

    $log = "";
    $listType = array("jpeg", "jpg", "png", "bmp", "pdf", "txt", "ppt", "doc", "zip", "rar", "tif");

    if ($rootFile) {
        foreach ($rootFile as $key => $_FILES) {

            $no = 0;
            $path = $key . "/";

            for ($i = 0; $i < count($_FILES['name']); $i++) {

                $no++;
                $nameOriginal = $_FILES['name'][$i];
                $file = $_FILES['tmp_name'][$i];
                $size = $_FILES["size"][$i];
                $arrNameOriginal = explode(".", $nameOriginal);
                $nameOriginal = removeExtend($arrNameOriginal);
                $_FILES_extension = strtolower(end($arrNameOriginal)); // get file extension

                $nameUpload = date("Ymd") . "_" . time() . "_" . $no;

                $url = $path . $nameUpload . "." . $_FILES_extension;

                if ($file) {
                    $logName = $nameOriginal . $_FILES_extension;
                    if (in_array($_FILES_extension, $listType)) {
                        if ($size < (2 * 1000000)) {
                            if (move_uploaded_file($file, $url)) {
                                $log .= $no . ". " . $logName . ": ok <br>";
                                $objBuild->SetTableName("evfile");
                                $x = 0;
                                $objTf = null;
                                $objTf[$x++] = new tfield("evfile_evletter",        $evletter,          "string");
                                $objTf[$x++] = new tfield("evfile_nameoriginal",    $nameOriginal,      "string");
                                $objTf[$x++] = new tfield("evfile_path",            $path,              "string");
                                $objTf[$x++] = new tfield("evfile_nameupload",      $nameUpload,        "string");
                                $objTf[$x++] = new tfield("evfile_type",            $_FILES_extension,  "string");
                                $objTf[$x++] = new tfield("evfile_size",            $size,              "string");
                                $objTf[$x++] = new tfield("evfile_datetime",        $datetime,          "string");
                                $objTf[$x++] = new tfield("evfile_active",          "1",                "string");
                                $objBuild->SetField($objTf);
                                $sql_insert = $objBuild->InsertSql();
                                if (!$objDb->ncaexec($sql_insert, "ncadoc")) {
                                    $objDb->ncarollback("ncadoc");
                                    $msg = "ERROR!! SQL:" . $sql_insert;
                                } else {
                                    $msg = "save letter : ok";
                                }

                                // set log
                                $objBuild->SetTableName("evlog");
                                $x = 0;
                                $objTf = null;
                                $objTf[$x++] = new tfield("evlog_table",    "evfile",               "string");
                                $objTf[$x++] = new tfield("evlog_action",   "upload",               "string");
                                $descp = "file: ".mergeUrl($path, $nameOriginal, $_FILES_extension);
                                $objTf[$x++] = new tfield("evlog_descp",    $descp,                 "string");
                                $objTf[$x++] = new tfield("evlog_usr",      $s_ar_login['user_id'], "string");
                                $objTf[$x++] = new tfield("evlog_datetime", $datetime,              "string");
                                $objBuild->SetField($objTf);
                                $sql_insert = $objBuild->InsertSql();
                                $objDb->ncaexec($sql_insert, "ncadoc");

                            } else {
                                $log .= $no . ". " . $logName . ": error, can't upload <br>";
                            }
                        } else {
                            $log .= $no . ". " . $logName . ": error, file greater than 2 MB <br>";
                        }
                    } else {
                        $log .= $no . ". " . $logName . ": error, not support this type <br>";
                    }
                } #end if $file
            } #end for $_FILES
        } #end foreach $rootFile
    } #end $rootFile

    return $log;
}#end setFile()

function getEvLetterIdBy3Par($mTitle, $mDatetime, $userId){
    $objDb = new ncadb();
    $sql = "SELECT evletter
            FROM evletter e
            WHERE e.evletter_title = '" . $mTitle . "' 
              AND e.evletter_recdatetime ='" . $mDatetime . "' 
              AND e.evletter_recid = '" . $userId . "'";

    $evletter = $objDb->ncaretrieve($sql, "ncadoc");
    return $evletter[0]['evletter'];
}#end getEvLetterIdBy3Par()

function getEvLetterById($evid){
    return getArrEvLetterRpt(null, null, null, $evid);
}

function getArrEvLetterRpt($sdate="", $edate="", $keyword="", $evid=""){
    $objDb = new ncadb();
    if($sdate && $edate)
        $wDate = " AND evletter_start_date between '$sdate' AND '$edate' ";
    elseif($sdate)
        $wDate = " AND evletter_start_date = '$sdate' ";
    elseif($edate)
        $wDate = " AND evletter_start_date = '$edate' ";
    if(trim($keyword))
        $wKeyword = " AND evletter_title LIKE '%$keyword%' ";

    if($evid){
        $wEvId = " AND evletter = ".$evid;
    }
    
    $where = $wEvId.$wDate.$wKeyword;
    $sql = "SELECT	e.evletter, 
                    e.evletter_title,
                    e.evletter_descp,
                    e.evletter_start_date,
                    e.evletter_start_time,
                    e.evletter_end_date,
                    e.evletter_end_time,
                    e.evletter_comp,
                    e.evletter_func,
                    e.evletter_dep,
                    e.evletter_sec,
                    e.evletter_recid,
                    e.evletter_recdatetime,
                    ef.evfile,
                    ef.evfile_datetime,
                    ef.evfile_nameoriginal,
                    ef.evfile_nameupload,
                    ef.evfile_path,
                    ef.evfile_size,
                    ef.evfile_type
            FROM evletter e
            LEFT JOIN evfile ef ON ef.evfile_evletter = e.evletter AND evfile_active = '1'
            WHERE   1 = 1 ".$where." AND e.evletter_active = '1' 
            ORDER BY ef.evfile_datetime";
    $evletter = $objDb->ncaretrieve($sql, "ncadoc");
    $rpt = array();
    for ($i=0; $i < count($evletter); $i++) { 
        $ev     = $evletter[$i]['evletter'];
        $title  = $evletter[$i]['evletter_title'];
        $descp  = $evletter[$i]['evletter_descp'];
        $sDate   = $evletter[$i]['evletter_start_date'];
        $sTime   = $evletter[$i]['evletter_start_time'];
        $eDate   = $evletter[$i]['evletter_end_date'];
        $eTime   = $evletter[$i]['evletter_end_time'];
        $comp   = $evletter[$i]['evletter_comp'];
        $func   = $evletter[$i]['evletter_func'];
        $dep    = $evletter[$i]['evletter_dep'];
        $sec    = $evletter[$i]['evletter_sec'];
        $rec    = $evletter[$i]['evletter_recid'];
        $recdatetime    = $evletter[$i]['evletter_recdatetime'];
        $evfile     = $evletter[$i]['evfile'];
        $fdatetime   = $evletter[$i]['evfile_datetime'];
        $fnameori    = $evletter[$i]['evfile_nameoriginal'];
        $fnameup     = $evletter[$i]['evfile_nameupload'];
        $fpath       = $evletter[$i]['evfile_path'];
        $fsize       = $evletter[$i]['evfile_size'];
        $ftype       = $evletter[$i]['evfile_type'];
        $rpt[$ev]['evid']   = $ev;
        $rpt[$ev]['title']  = $title;
        $rpt[$ev]['descp']  = $descp;
        $rpt[$ev]['sDate']   = $sDate;
        $rpt[$ev]['sTime']   = $sTime;
        $rpt[$ev]['eDate']   = $eDate;
        $rpt[$ev]['eTime']   = $eTime;
        $rpt[$ev]['comp']   = $comp;
        $rpt[$ev]['func']   = $func;
        $rpt[$ev]['dep']    = $dep;
        $rpt[$ev]['sec']    = $sec;
        $rpt[$ev]['rec']    = $rec;
        $rpt[$ev]['recdatetime']    = $recdatetime;
        $rpt[$ev]['files']['fileid'][]     = $evfile;
        $rpt[$ev]['files']['fdatetime'][]  = $fdatetime;
        $rpt[$ev]['files']['fnameori'][]   = $fnameori;
        $rpt[$ev]['files']['fnameup'][]    = $fnameup;
        $rpt[$ev]['files']['fpath'][]      = $fpath;
        $rpt[$ev]['files']['fsize'][]      = $fsize;
        $rpt[$ev]['files']['ftype'][]      = $ftype;
        if(is_null($rpt[$ev]['files']['count'])){
            $rpt[$ev]['files']['count'] = 1;
        }else{
            $rpt[$ev]['files']['count']++;
        }
    }
    return $rpt;
}#end getArrEvLetterRpt()

function getUserName(){
    $objDb = new ncadb();
    $sql = "SELECT	u.user_id, u.user_dspname FROM user u ";
    $arr = $objDb->ncaretrieve($sql, "person2");
    for ($i=0; $i < count($arr); $i++) { 
        $user[$arr[$i]['user_id']] = $arr[$i]['user_dspname'];
    }
    return $user;
}#end getUserName()

function getUserNameById($id){
    $objDb = new ncadb();
    $sql = "SELECT	u.user_dspname FROM user u WHERE u.user_id = '$id'";
    $user = $objDb->ncaretrieve($sql, "person2");
    return $user[0]['user_dspname'];
}#end getUserNameById()

function getCompName($id){
    $lc_html = "";
    $objDb = new ncadb();
    $sql = "SELECT m_comp_name_th AS th_name FROM m_comp WHERE m_comp = '$id' AND m_comp_active = 1";
    $data = $objDb->ncaretrieve($sql, "person2");
    return $data[0]['th_name'];
}#end getCompName()

function getFuncName($id){
    $objDb = new ncadb();
    $sql = "SELECT m_compfunc_name_th AS th_name FROM m_compfunc WHERE m_compfunc = '$id' AND m_compfunc_active = 1";
    $data = $objDb->ncaretrieve($sql, "person2");
    return $data[0]['th_name'];
}#end getFuncName()

function getDepName($id){
    $objDb = new ncadb();
    $sql = "SELECT m_compfuncdep_name_th AS th_name FROM m_compfuncdep WHERE m_compfuncdep = '$id' AND m_compfuncdep_active = 1";
    $data = $objDb->ncaretrieve($sql, "person2");
    return $data[0]['th_name'];
}#end getDepName()

function getSecName($id){
    $objDb = new ncadb();
    $sql = "SELECT m_compfuncdepsec_name_th AS th_name FROM m_compfuncdepsec WHERE m_compfuncdepsec = '$id' AND m_compfuncdepsec_active = 1";
    $data = $objDb->ncaretrieve($sql, "person2");
    return $data[0]['th_name'];
}#end getSecName()

function deleteEvlt($evid){
    global $s_ar_login;
    $objDb = new ncadb();
    $o_build = new SqlBuilder();
    $msg = "";

    $rpt = getArrEvLetterRpt(null, null, null, $evid);
    $v = $rpt[$evid];
    $vf = $v['files'];

    ////////////////////// delete real file in server //////////////////////////
    $delDescFile = array();
    for ($i=0; $i < $vf['count']; $i++) { 
        if($vf['fileid'][$i]){
            $path = $vf['fpath'][$i];
            $name = $vf['fnameup'][$i];
            $nameOri = $vf['fnameori'][$i];
            $type = $vf['ftype'][$i];
            $myFile = mergeUrl($path, $name, $type);
            unlink($myFile) or die("Couldn't delete file");
            $msg .= ($i+1).".".$myFile.", Delete complete!!!<br>";
            $delDescFile[$i] = $nameOri.".".$type;
        }
    }

    ////////////////////// del evletter //////////////////////////
    $o_build->SetTableName("evletter");
	$ii=0;
    $o_obj = null;
    $o_obj[$ii++] = new tfield("evletter_active", '0', "string");
    $o_obj[$ii++] = new tfield("evletter_modi_recid", $s_ar_login['user_id'], "string");
    $o_obj[$ii++] = new tfield("evletter_modi_recdatetime", ncanow(), "string");
    $o_build->SetField($o_obj);

    $where = " evletter = '$evid' ";
    $o_build->setWhereClause($where);

    $sql = $o_build->UpdateSql();
    if(!$objDb->ncaexec($sql, "ncadoc")) {
        $msg .= "ERROR : ".$sql."<br>";
    }else{
        $msg .= "evletter set active 0, Complete!!!<br>";
    }

    ////////////////////// del evfile //////////////////////////
    $o_build->SetTableName("evfile");
	$ii=0;
    $o_obj = null;
    $o_obj[$ii++] = new tfield("evfile_active", '0', "string");
    $o_build->SetField($o_obj);

    $where = " evfile_evletter = '$evid' ";
    $o_build->setWhereClause($where);

    $sql = $o_build->UpdateSql();
    if(!$objDb->ncaexec($sql, "ncadoc")) {
        $msg .= "ERROR : ".$sql."<br>";
    }else{
        $msg .= "evfile set active 0, Complete!!!<br>";
    }

    ////////////////////// save log //////////////////////////
    $o_build->SetTableName("evlog");
	$ii=0;
    $o_obj = null;
    $o_obj[$ii++] = new tfield("evlog_table",       'evletter',             "string");
    $o_obj[$ii++] = new tfield("evlog_action",      'delete',               "string");
    $o_obj[$ii++] = new tfield("evlog_descp",       "title:".$v['title'],   "string");
    $o_obj[$ii++] = new tfield("evlog_usr",         $s_ar_login['user_id'], "string");
    $o_obj[$ii++] = new tfield("evlog_datetime",    ncanow(),               "string");
    $o_build->SetField($o_obj);
    $sql = $o_build->InsertSql();
    if(!$objDb->ncaexec($sql, "ncadoc")) {
        $msg .= "ERROR : ".$sql."<br>";
    }

    for ($i=0; $i < count($delDescFile); $i++) { 
        $ii=0;
        $o_obj = null;
        $o_obj[$ii++] = new tfield("evlog_table",       'evfile',                   "string");
        $o_obj[$ii++] = new tfield("evlog_action",      'delete',                   "string");
        $o_obj[$ii++] = new tfield("evlog_descp",       "file:".$delDescFile[$i],   "string");
        $o_obj[$ii++] = new tfield("evlog_usr",         $s_ar_login['user_id'],     "string");
        $o_obj[$ii++] = new tfield("evlog_datetime",    ncanow(),                   "string");
        $o_build->SetField($o_obj);
        $sql = $o_build->InsertSql();
        if(!$objDb->ncaexec($sql, "ncadoc")) {
            $msg .= "ERROR : ".$sql."<br>";
        }
    }

    return $msg;
}#end deleteEvlt()

function updateEvLetter($frm, $_FILES){
    global $s_ar_login;
    $objDb = new ncadb();
    $o_build = new SqlBuilder();
    $msg = "";
    $dateNow = ncanow();
    // update text
    $o_build->SetTableName("evletter");
	$ii=0;
    $o_obj = null;
    if($frm['title'] != $frm['tmp_title']) $o_obj[$ii++] = new tfield("evletter_title", strip_tags($frm['title']), "string");

    if($frm['startDate'] != $frm['tmp_startDate']) $o_obj[$ii++]  = new tfield("evletter_start_date", dmy2ymd($frm['startDate'], 'de'), "string");
    if($frm['startTime'] != $frm['tmp_startTime']) $o_obj[$ii++]  = new tfield("evletter_start_time", $frm['startTime'], "string");

    if($frm['endDate'] != $frm['tmp_endDate']) $o_obj[$ii++]  = new tfield("evletter_end_date", dmy2ymd($frm['endDate'], 'de'), "string");
    if($frm['endTime'] != $frm['tmp_endTime']) $o_obj[$ii++]  = new tfield("evletter_end_time", $frm['endTime'], "string");

    if(md5($frm['descp']) != $frm['tmp_descp']) $o_obj[$ii++] = new tfield("evletter_descp", strip_tags($frm['descp']), "string");
    if($frm['comp'] != $frm['tmp_comp']) $o_obj[$ii++]  = new tfield("evletter_comp", $frm['comp'], "string");
    if($frm['func'] != $frm['tmp_func']) $o_obj[$ii++]  = new tfield("evletter_func", $frm['func'], "string");
    if($frm['dep']  != $frm['tmp_dep']) $o_obj[$ii++]   = new tfield("evletter_dep", $frm['dep'], "string");
    if($frm['sec']  != $frm['tmp_sec']) $o_obj[$ii++]   = new tfield("evletter_sec", $frm['sec'], "string");
    if($ii>0){
        $o_obj[$ii++] = new tfield("evletter_modi_recid", $s_ar_login['user_id'], "string");
        $o_obj[$ii++] = new tfield("evletter_modi_recdatetime", $dateNow, "string");
        $o_build->SetField($o_obj);
        $where = " evletter = '".$frm['evid']."' ";
        $o_build->setWhereClause($where);
        $sql = $o_build->UpdateSql();
        if(!$objDb->ncaexec($sql, "ncadoc"))
            $msg .= "ERROR : ".$sql."<br>";
        else
            $msg .= "evletter set active 0, Complete!!!<br>";

        ////////////////////// save log //////////////////////////
        saveLogUpdate("title", $frm['title'], $frm['tmp_title'], $dateNow);

        saveLogUpdate("startDate", dmy2ymd($frm['startDate'], 'de'), dmy2ymd($frm['tmp_startDate'], 'de'), $dateNow);
        saveLogUpdate("startTime", $frm['startTime'], $frm['tmp_startTime'], $dateNow);

        saveLogUpdate("endDate", dmy2ymd($frm['endDate'], 'de'), dmy2ymd($frm['tmp_endDate'], 'de'), $dateNow);
        saveLogUpdate("endTime", $frm['endTime'], $frm['tmp_endTime'], $dateNow);

        saveLogUpdate("descp", $frm['descp'], $frm['tmp_descp'], $dateNow);
        saveLogUpdate("comp", $frm['comp'], $frm['tmp_comp'], $dateNow);
        saveLogUpdate("func", $frm['func'], $frm['tmp_func'], $dateNow);
        saveLogUpdate("dep", $frm['dep'], $frm['tmp_dep'], $dateNow);
        saveLogUpdate("sec", $frm['sec'], $frm['tmp_sec'], $dateNow);
    }

    // upload new file
    $str  = "";
    $str .= "<pre>".print_r($frm, true)."</pre>";
    $str .= "<pre>".print_r($_FILES, true)."</pre>";
    $log = setFile($_FILES, $frm['evid'], $dateNow);// save file at url server
    $str .= $log;
    return $str;
}

function saveLogUpdate($descp, $val, $tmp_val, $dateNow){
    if($val != $tmp_val) {
        global $s_ar_login;
        $objDb = new ncadb();
        $o_build = new SqlBuilder();
        $msg = "";
        $o_build->SetTableName("evlog");
        $ii=0;
        $o_obj = null;
        $o_obj[$ii++] = new tfield("evlog_table",       "evletter",             "string");
        $o_obj[$ii++] = new tfield("evlog_action",      "update",               "string");
        $o_obj[$ii++] = new tfield("evlog_descp",       $descp.": ".$val,       "string");
        $o_obj[$ii++] = new tfield("evlog_usr",         $s_ar_login['user_id'], "string");
        $o_obj[$ii++] = new tfield("evlog_datetime",    $dateNow,               "string");
        $o_build->SetField($o_obj);
        $sql = $o_build->InsertSql();
        if(!$objDb->ncaexec($sql, "ncadoc")) {
            $msg .= "ERROR : ".$sql."<br>";
        }
    }
}

function delFileUpload($fileId, $url){
    global $s_ar_login;
    $objDb = new ncadb();
    $o_build = new SqlBuilder();
    $msg = "";

    ////////////////////// delete real file in server //////////////////////////
    unlink($url) or die("Couldn't delete file");

    ////////////////////// del evfile //////////////////////////////////////////
    $o_build->SetTableName("evfile");
    $ii=0;
    $o_obj = null;
    $o_obj[$ii++] = new tfield("evfile_active", '0', "string");
    $o_build->SetField($o_obj);

    $where = " evfile = '$fileId' ";
    $o_build->setWhereClause($where);

    $sql = $o_build->UpdateSql();
    if(!$objDb->ncaexec($sql, "ncadoc"))
        $msg .= "ERROR : ".$sql."<br>";
    else
        $msg .= "evfile set active 0, Complete!!!<br>";

    ////////////////////// save log ////////////////////////////////////////////
    $o_build->SetTableName("evlog");
    $ii=0;
    $o_obj = null;
    $o_obj[$ii++] = new tfield("evlog_table",       'evfile',               "string");
    $o_obj[$ii++] = new tfield("evlog_action",      'delete',               "string");
    $o_obj[$ii++] = new tfield("evlog_descp",       "file:".$url,           "string");
    $o_obj[$ii++] = new tfield("evlog_usr",         $s_ar_login['user_id'], "string");
    $o_obj[$ii++] = new tfield("evlog_datetime",    ncanow(),               "string");
    $o_build->SetField($o_obj);
    $sql = $o_build->InsertSql();
    if(!$objDb->ncaexec($sql, "ncadoc"))
        $msg .= "ERROR : ".$sql."<br>";

    return $msg;
}

////////////////// normal function ///////////////////
function dmy2ymd($date="", $type=""){
    if(trim($date)){
        if ($type == 'en') {
            $arr = explode("-", $date);
            $d = $arr[0];
            $m = $arr[1];
            $y = $arr[2];
            return $y . "-" . $m . "-" . $d;
        } else if ($type == 'de') {
            $arr = explode("-", $date);
            $d = $arr[2];
            $m = $arr[1];
            $y = $arr[0];
            return $d . "-" . $m . "-" . $y;
        } else {
            return "9999-99-99";
        }
    }
}#end dmy2ymd()

function utf8_strlen($str){
    $c = strlen($str);
    $l = 0;
    for ($i = 0; $i < $c; ++$i) {
        if ((ord($str[$i]) & 0xC0) != 0x80) {
            ++$l;
        }
    }
    return $l;
}#end utf8_strlen()

// Get part of string for Character Thai
function getSubStrTH($string, $start, $length){			
	$length = ($length+$start)-1;
	$array = getMBStrSplit($string);
	$return = "";
		
	for($i=$start; $i < count($array); $i++)
	{
		$ascii = ord(iconv("UTF-8", "TIS-620", $array[$i] ));
		
		if( $ascii == 209 ||  ($ascii >= 212 && $ascii <= 218 ) || ($ascii >= 231 && $ascii <= 238 ) )
		{
			//$start++;
			$length++;
		}
		
		if( $i >= $start )
		{
			$return .= $array[$i];
		}
		
		if( $i >= $length )
			break;
		}
	
	return $return;
}#end getSubStrTH()

// Convert a string to an array with multibyte string
function getMBStrSplit($string, $split_length = 1){
	mb_internal_encoding('UTF-8');
	mb_regex_encoding('UTF-8'); 
	
	$split_length = ($split_length <= 0) ? 1 : $split_length;
	$mb_strlen = mb_strlen($string, 'utf-8');
	$array = array();
	$i = 0; 
	
	while($i < $mb_strlen)
	{
		$array[] = mb_substr($string, $i, $split_length);
		$i = $i+$split_length;
	}
	
	return $array;
}#end getMBStrSplit()

function getDateX($date, $pattern){
    $date = date($pattern, strtotime($date));
    $arr = explode("-", $date);
    $d = (int)$arr[0];
    $m = $arr[1];
    $y = $arr[2];
    $y = ($y+543);// to thai
    $yShr = subStr($y,2,2);
    $result = $d." ".getMonth($m)." ".$yShr;
    return $result;
}#end getDateX()

function getTimeX($time){
    if($time){
        $arr = explode(":", $time);
        $h = $arr[0];
        $m = $arr[1];
        // $s = $arr[2];
        $result = $h.".".$m." น.";
    }else{
        $result = "-";
    }
    return $result;
}

function getTimeForm($time){
    if($time){
        $arr = explode(":", $time);
        $h = $arr[0];
        $m = $arr[1];
        // $s = $arr[2];
        $result = $h.":".$m;
    }else{
        $result = "";
    }
    return $result;
}

function getMonth($month){
    switch ($month)
    { 
        // case 01 : $month="มกราคม"; break;
        // case 02 : $month="กุมภาพันธ์"; break;
        // case 03 : $month="มีนาคม"; break;
        // case 04 : $month="เมษายน"; break;
        // case 05 : $month="พฤษภาคม"; break;
        // case 06 : $month="มิถุนายน"; break;
        // case 07 : $month="กรกฎาคม"; break;
        // case 08 : $month="สิงหาคม"; break;
        // case 09 : $month="กันยายน"; break;
        // case 10 : $month="ตุลาคม"; break;
        // case 11 : $month="พฤศจิกายน"; break;
        // case 12 : $month="ธันวาคม"; break;
        case 01 : $month="ม.ค."; break;
        case 02 : $month="ก.พ."; break;
        case 03 : $month="มี.ค."; break;
        case 04 : $month="เม.ย."; break;
        case 05 : $month="พ.ค."; break;
        case 06 : $month="มิ.ย."; break;
        case 07 : $month="ก.ค."; break;
        case 08 : $month="ส.ค."; break;
        case 09 : $month="ก.ย."; break;
        case 10 : $month="ต.ค."; break;
        case 11 : $month="พ.ย."; break;
        case 12 : $month="ธ.ค."; break;
    }
    return $month;
}#end getMonth()
////////////////// end normal function ///////////////////

function removeExtend($arr){
    $str = "";
    for ($i = 0; $i < count($arr) - 1; $i++) {
        $str .= $arr[$i];
    }
    return $str;
}#end removeExtend()

function getImgCover($img){
    $imgUrl = "ev_inc/img/img-not-found-b.jpg";
    for ($i=0; $i < count($img); $i++) { 
        $path = $img["fpath"][$i];
        $name = $img["fnameup"][$i];
        $type = $img["ftype"][$i];
        if($path == "ev_uploadEventImg/"){
            $imgUrl = mergeUrl($path, $name, $type);
            break;
        }
    }
    return $imgUrl;
}#end getImgCover()

function mergeUrl($path, $name, $type){
    return "../model/".$path.$name.".".$type;
}#end mergeUrl()

function set_a_ar_login($userid){
    if(!$userid){
        $userid = 666;// will set when use localhost, By Pass
    }
    return $userid;
}#end set_a_ar_login()

?>