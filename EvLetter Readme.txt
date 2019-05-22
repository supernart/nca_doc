Last update	:	22/05/2019
Created by	:	Mr.Pichet Saelai
Project		:	จดหมายเวียน (Event Letter: evletter)
//////////////////////////////////////////////////////////////////////////////////////////////////////
Directory structure{
	model{
		ev_m_evletter.php
		ev_m_person.php
		ev_uploadEventImg/	// folder for upload image files.
		ev_uploadFile/		// folder for upload files such as "jpg", "png", "pdf", "rar" ect.
	}
	controller{
		ev_con_evcalendar.php
		ev_con_evletter.php
		ev_getComFuncDepSec.js
		ev_getEvLetter.js
		ev_getEvLetterDetail.js
		ev_getsetRecord.js
		ev_setRecord.js
		ev_updateRecord.js
		ev_uploadEventImage.js
		ev_uploadEventImageUpdate.js
		ev_uploadFile.js
		ev_uploadFileUpdate.js
		ev_validate.js
	}
	view{
		ev_inc/
		ev_record.php		// insert
		ev_rpt.php			// report, update, insert
		ev_rpt_card.php		// report detail, update
		ev_rpt_list.php		// report detail, update
		ev_edit.php			// update
	}
}

Database Structure{
	nca_document{
		evfile				// data about upload file
		evletter			// data about event letter
		evlog
	}
}

CREATE TABLE `evfile` (
  `evfile` int(5) NOT NULL auto_increment,
  `evfile_evletter` int(5) default NULL,
  `evfile_nameoriginal` varchar(255) default NULL,
  `evfile_path` varchar(255) default NULL,
  `evfile_nameupload` varchar(255) default NULL COMMENT 'year month day _ time() _ no',
  `evfile_type` varchar(10) default NULL,
  `evfile_size` int(7) default NULL COMMENT 'byte',
  `evfile_datetime` datetime default NULL,
  `evfile_active` varchar(1) default NULL,
  PRIMARY KEY  (`evfile`),
  KEY `evfile_evletter` USING BTREE (`evfile_evletter`)
) ENGINE=MyISAM AUTO_INCREMENT=254 DEFAULT CHARSET=utf8;

CREATE TABLE `evletter` (
  `evletter` int(5) NOT NULL auto_increment,
  `evletter_title` varchar(75) NOT NULL,
  `evletter_start_date` date NOT NULL,
  `evletter_start_time` time default NULL,
  `evletter_end_date` date default NULL,
  `evletter_end_time` time default NULL,
  `evletter_descp` text,
  `evletter_comp` int(2) default NULL,
  `evletter_func` int(3) default NULL,
  `evletter_dep` int(4) default NULL,
  `evletter_sec` int(5) default NULL,
  `evletter_recid` int(6) default NULL,
  `evletter_recdatetime` datetime default NULL,
  `evletter_modi_recid` int(11) default NULL,
  `evletter_modi_recdatetime` datetime default NULL,
  `evletter_active` char(1) default NULL,
  PRIMARY KEY  (`evletter`),
  KEY `evletter` USING BTREE (`evletter`,`evletter_title`,`evletter_start_date`,`evletter_start_time`)
) ENGINE=MyISAM AUTO_INCREMENT=242 DEFAULT CHARSET=utf8;

CREATE TABLE `evlog` (
  `evlog` int(5) NOT NULL auto_increment,
  `evlog_table` varchar(75) default NULL,
  `evlog_action` varchar(75) default NULL,
  `evlog_descp` varchar(255) default NULL,
  `evlog_usr` int(5) default NULL,
  `evlog_datetime` datetime default NULL,
  PRIMARY KEY  (`evlog`)
) ENGINE=MyISAM AUTO_INCREMENT=701 DEFAULT CHARSET=utf8;



//////////////////////////////////////////////////////////////////////////////////////////////////////
Explain:
1.go link: http://192.1.1.250/nca_project/view/nca_login.php?addr=N0MtNjctQTItQTgtNDktRTEvbmNhfTE0MjM=
2.Login
3.Select Menu: งานประชาสัมพันธ์ -> งานประชาสัมพันธ์
4.	จดหมายเหตุ -> view/ev_record.php
	รายงานจดหมาย -> view/ev_rpt.php
//////////////////////////////////////////////////////////////////////////////////////////////////////
Abbreviations used
	obj	:	object
	db	:	databasee
	ev	:	event
	m	:	"m" is model such as ev_m_evletter.php => event_model_evletter.php
	con	:	"con" is controller such as ev_con_evletter.php => event_controller_evletter.php
	rpt	:	report
Every file I'll use "ev" before name file such as ev_edit.php, ev_record.php ect.
//////////////////////////////////////////////////////////////////////////////////////////////////////

