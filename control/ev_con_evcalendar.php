<?php
header("Content-type:application/json; charset=UTF-8");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
require_once("../model/ev_m_evletter.php");

$method = ($_GET['method']) ? $_GET['method'] : $_POST['method'];
switch ($method) {
    case 'getRptEvCalendar':

        $sdate = dmy2ymd($_POST['sdate'], "en");
        $edate = dmy2ymd($_POST['edate'], "en");
        $data = getRptEvCalendar($sdate, $edate, $_POST['keyword']);

        if ($data) {
            for ($i = 0; $i < count($data); $i++) {
                $events[] = array(
                    "evid" =>  $data[$i]['evid'],
                    "title" =>  $data[$i]['title'],
                    "start" =>  $data[$i]['start'],
                    "end"   =>  $data[$i]['end']
                    // "color" =>  "blue"
                );
            }
        }

        break;
    default:
        # code...
        // $events = testInstance();
        break;
}

// $events = testInstance();
echo json_encode($events);

function testInstance()
{
    for ($i = 0; $i < 4; $i++) {
        $events[] = array(
            "id"    =>  "36734",
            "title" =>  $i . "This's a test title!",
            "start" =>  "2019-05-13",
            "end"   =>  "2019-05-14",
            "color" =>  "green"
        );
    }
    return $events;
}
