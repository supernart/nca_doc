/* 
 ** 2019-May-01
 ** Pichet Saelai
 ** for use another project 
 ** v.1.1
 */

$(document).ready(function () {
    var mMethod = document.getElementById('nStyle').value;
    switch (mMethod) {
        case 'getRptCard':
            $.ajax({
                type: 'POST',
                url: "../control/ev_con_evletter.php",
                dataType: "html",
                data: { method: mMethod },
                success: callShowEvLt
            });
            break;
        case 'getRptList':
            $.ajax({
                type: 'POST',
                url: "../control/ev_con_evletter.php",
                dataType: "html",
                data: { method: mMethod },
                success: callShowEvLt
            });
            break;
        case 'getRptCalendar':
            initialFullCalendar();
            break;
        default:
            break;
    }
});

function loadPage() {
    var mKeyword = document.getElementById('nSearch').value;
    var mSDate = document.getElementById('nSdate').value;
    var mEDate = document.getElementById('nEdate').value;
    var mMethod = document.getElementById('nStyle').value;
    $('#divEvLetter').css("display", "none");
    $('#calendarRpt').fadeOut("fast");
    switch (mMethod) {
        case 'getRptCard':
            $.ajax({
                type: 'POST',
                url: "../control/ev_con_evletter.php",
                dataType: "html",
                data: {
                    method: mMethod,
                    sdate: mSDate,
                    edate: mEDate,
                    keyword: mKeyword
                },
                success: callShowEvLt
            });
            break;
        case 'getRptList':
            $.ajax({
                type: 'POST',
                url: "../control/ev_con_evletter.php",
                dataType: "html",
                data: {
                    method: mMethod,
                    sdate: mSDate,
                    edate: mEDate,
                    keyword: mKeyword
                },
                success: callShowEvLt
            });
            break;
        case 'getRptCalendar':
            $('#calendarRpt').fadeIn("fast");
            getEventCalendar(mSDate, mEDate, mKeyword);
            break;
        default:
            break;
    }
}

function callShowEvLt(resp) {
    $('#divEvLetter').html(resp); // show data
    $('#divEvLetter').fadeIn("fast");
    $('.dataTableEv').DataTable({
        "searching": false,
        "iDisplayLength": 30,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
}

function initialFullCalendar() {
    $('#calendarRpt').fullCalendar({
        header: {
            left: 'prev',
            center: 'title',
            right: 'next'
            //today,month,agendaWeek,agendaDay
        },
        nextDayThreshold: '00:00:00',
        defaultDate: getDateNow(),
        dayClick: function (date, jsEvent, view) {
            loadFormRecord(date, jsEvent, view);
        },
        eventClick: function (event) {
            $('#frmAddCalendar').fadeIn("fast");
            loadFormForUpdate(event.evid);
        }
    });
    getEventCalendar('', '', '');
}

function getEventCalendar(mSDate, mEDate, mKeyword) {
    $.ajax({
        type: 'POST',
        url: "../control/ev_con_evcalendar.php",
        dataType: "json",
        data: {
            method: 'getRptEvCalendar',
            sdate: mSDate,
            edate: mEDate,
            keyword: mKeyword
        },
        success: fullCalenAddEvent
    });
}

function fullCalenAddEvent(resJson) {
    // remove event
    // $('#calendarRpt').fullCalendar('removeEventSource');
    // $('#calendarRpt').fullCalendar('addEventSource');
    $('#calendarRpt').fullCalendar('refetchEvents');

    // add event
    var tmp;
    if (resJson) {
        for (var i = 0; i < resJson.length; i++) {
            tmp = {
                evid: resJson[i].evid,
                title: resJson[i].title,
                start: resJson[i].start,
                end: resJson[i].end,
                // color: resJson[i].color
            };
            $('#calendarRpt').fullCalendar('renderEvent', tmp);
        }
    }
}

function getDateNow() {
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    // today = mm + '/' + dd + '/' + yyyy;
    today = yyyy + '-' + mm + '-' + dd;
    return today;
}

function showDetailCard(evid) {
    location.href = "ev_rpt_card.php?evid=" + evid;
}

function showDetailList(evid) {
    location.href = "ev_rpt_list.php?evid=" + evid;
}

// warning, this's a danger function wa-hahaha
function delEventLetter(mEvid) {
    var r = confirm("ยืนยันการลบ!");
    if (r == true) {
        $("#div" + mEvid).fadeOut('fast');
        var mMethod = "deleteEvlt";
        $.ajax({
            type: 'POST',
            url: '../control/ev_con_evletter.php',
            dataType: "html",
            data: {
                method: mMethod,
                evid: mEvid,
            },
            success: callDelEvLtComplete
        });
    }
}

function callDelEvLtComplete(resp) {
    // alert("ลบสำเร็จ!");
    // $('#divEvLetter').html(resp);
}

function popItemEvletter(url) {
    window.open(url, "_blank", "width=560,height=460");
}

function editEventLetter(evid, showStyle) {
    location.href = "ev_edit.php?evid=" + evid + "&showStyle=" + showStyle;
}

function loadFormForUpdate(pEvid) {
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
            $('#frmAddEvLetter').html("");

            $('.datepicker').datepicker({
                dateFormat: 'dd-mm-yy'
            });
            $('.clockpicker').clockpicker({
                placement: 'left',
                align: 'top',
                donetext: 'Done'
            });
        }
    });
}

function loadFormRecord(date, jsEvent, view) {
    // $('#calendarRpt').fullCalendar('renderEvent', {
    //     title: 'add news event',
    //     start: date,
    //     allDay: true
    // });
    // alert('Clicked on: ' + date.format());
    // alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
    // alert('Current view: ' + view.name);
    $('#frmAddCalendar').fadeIn("fast");
    $.ajax({
        type: 'post',
        url: '../control/ev_con_evletter.php',
        data: {
            method: 'getFormRecord'
        },
        dataType: "html",
        success: function (response) {
            $('#frmEditEvLetter').html("");
            $('#frmAddEvLetter').html(response);

            $('.datepicker').datepicker({
                dateFormat: 'dd-mm-yy'
            });
            $('.clockpicker').clockpicker({
                placement: 'left',
                align: 'top',
                donetext: 'Done'
            });
            var tmp = date.format();
            var arrDate = tmp.split("-");
            var datex = arrDate[2] + "-" + arrDate[1] + "-" + arrDate[0];
            $('#startDate').val(datex);
            $('#endDate').val(datex);
        }
    });
}