<?php

	if (!defined("IN_FUSION")) { die(); }

	require_once INFUSIONS."chat_panel/functions.php";
	
	$LOCALE = array(
		"INF_TITLE" => "PHP-Fusion - ET-Chat Bridge",
		"INF_DESC" => "Eine Bridge von PHP-Fusion v7.00.xx zu ET-Chat v3.0.6",
		"INF_ADMINLINK_NAME" => "Chat",
		"INF_SITELINK_NAME" => "Chat"
	);

	
	$inf_title = $LOCALE['INF_TITLE'];
	$inf_description = $LOCALE['INF_DESC'];
	$inf_version = "1.0 BETA";
	$inf_developer = "David G&uuml;tl";
	$inf_email = "Eistee100@hotmail.com";
	$inf_weburl = "http://www.darkweb.at/";
	$inf_folder = "chat_panel";
	

	$inf_newtable[1] = DB_EBP_SETTINGS." (
		system TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
		online_chatter TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
		copyright TINYINT(1) UNSIGNED NOT NULL DEFAULT '1'
	) TYPE=MyISAM;";

	$inf_newtable[2] = DB_EBP_CHAT_CONFIG." (
		etchat_config_id int(11) NOT NULL auto_increment,
		etchat_config_reloadsequenz int(11) NOT NULL,
		etchat_config_messages_im_chat int(11) NOT NULL,
		etchat_config_style varchar(100) NOT NULL,
		etchat_config_loeschen_nach int(11) NOT NULL,
		etchat_config_lang varchar(30) NOT NULL,
		PRIMARY KEY  (etchat_config_id)
	) TYPE=MyISAM;";

	$inf_newtable[3] = DB_EBP_CHAT_USERS." (
		etchat_user_id int(8) NOT NULL auto_increment,
		etchat_username varchar(200) NOT NULL,
		etchat_userpw varchar(40) default NULL,
		etchat_userprivilegien varchar(15) NOT NULL default 'gast',
		etchat_usersex varchar(1) NOT NULL default 'n',
		PRIMARY KEY  (etchat_user_id)
	) TYPE=MyISAM;";

	$inf_newtable[4] = DB_EBP_CHAT_BLACKLIST." (
		etchat_blacklist_id int(8) NOT NULL auto_increment,
		etchat_blacklist_ip varchar(255) NOT NULL,
		etchat_blacklist_userid int(8) NOT NULL,
		etchat_blacklist_time int(30) NOT NULL,
		PRIMARY KEY  (etchat_blacklist_id)
	) TYPE=MyISAM;";

	$inf_newtable[5] = DB_EBP_CHAT_MESSAGES." (
		etchat_id bigint(20) unsigned NOT NULL auto_increment,
		etchat_user_fid int(8) NOT NULL,
		etchat_text text NOT NULL,
		etchat_text_css text NOT NULL,
		etchat_timestamp bigint(20) NOT NULL,
		etchat_fid_room int(11) NOT NULL default '1',
		etchat_privat int(8) default '0',
		PRIMARY KEY  (etchat_id)
	) TYPE=MyISAM;";

	$inf_newtable[6] = DB_EBP_CHAT_ROOMS." (
		etchat_id_room int(3) unsigned NOT NULL auto_increment,
		etchat_roomname varchar(100) NOT NULL,
		etchat_room_goup int(6) NOT NULL default '0',
		etchat_room_pw varchar(50) default NULL,
		etchat_room_message text,
		PRIMARY KEY  (etchat_id_room)
	) TYPE=MyISAM;";

	$inf_newtable[7] = DB_EBP_CHAT_SMILEYS." (
		etchat_smileys_id int(5) NOT NULL auto_increment,
		etchat_smileys_sign varchar(20) NOT NULL,
		etchat_smileys_img varchar(100) NOT NULL,
		PRIMARY KEY  (etchat_smileys_id)
	) TYPE=MyISAM;";

	$inf_newtable[8] = DB_EBP_CHAT_USERONLINE." (
		etchat_onlineid bigint(20) unsigned NOT NULL auto_increment,
		etchat_onlineuser_fid int(8) NOT NULL,
		etchat_onlinetimestamp bigint(20) NOT NULL,
		etchat_onlineip varchar(255) NOT NULL,
		etchat_fid_room int(11) NOT NULL default '1',
		etchat_user_online_room_goup int(6) NOT NULL,
		etchat_user_online_room_name varchar(100) NOT NULL,
		etchat_user_online_user_name varchar(200) NOT NULL,
		etchat_user_online_user_priv varchar(20) NOT NULL,
		etchat_user_online_user_sex varchar(1) NOT NULL,
		etchat_user_online_user_status_img varchar(200) default NULL,
		etchat_user_online_user_status_text varchar(200) default NULL,
		PRIMARY KEY  (etchat_onlineid)
	) TYPE=MyISAM;";

	$inf_newtable[9] = DB_EBP_CHAT_KICKED_USER." (
		id int(11) NOT NULL auto_increment,
		etchat_kicked_user_id int(11) NOT NULL,
		etchat_kicked_user_time int(11) NOT NULL,
		PRIMARY KEY  (id)
	) TYPE=MyISAM;";
	

	$inf_insertdbrow[1] = DB_EBP_CHAT_CONFIG." (etchat_config_id, etchat_config_reloadsequenz, etchat_config_messages_im_chat, etchat_config_style, etchat_config_loeschen_nach, etchat_config_lang) VALUES (1, 2000, 25, 'etchat_white', 3, 'lang_de.xml')";
	$inf_insertdbrow[2] = DB_EBP_SETTINGS." (system, online_chatter, copyright) VALUES (1, 1, 1)";
	$inf_insertdbrow[3] = DB_EBP_CHAT_ROOMS." (etchat_id_room, etchat_roomname, etchat_room_goup, etchat_room_pw, etchat_root_messages) VALUES (1, 'Lobby', 0, NULL, 'Herzlich Willkommen im Chat ;)')";
	$inf_insertdbrow[4] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':angry:', 'smilies/angry.gif')";
	$inf_insertdbrow[5] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':-]', 'smilies/approve.gif')";
	$inf_insertdbrow[6] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':-}', 'smilies/blushing.gif')";
	$inf_insertdbrow[7] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':charming:', 'smilies/charming.gif')";
	$inf_insertdbrow[8] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':-P', 'smilies/cheeky.gif')";
	$inf_insertdbrow[9] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':cheesy:', 'smilies/cheesy.gif')";
	$inf_insertdbrow[10] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES ('8-)', 'smilies/cool.gif')";
	$inf_insertdbrow[11] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':*(', 'smilies/cry.gif')";
	$inf_insertdbrow[12] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES ('x-(', 'smilies/dead.gif')";
	$inf_insertdbrow[13] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':dissap:', 'smilies/dissappointed.gif')";
	$inf_insertdbrow[14] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':embar:', 'smilies/embarassed.gif')";
	$inf_insertdbrow[15] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':evil:', 'smilies/evil.gif')";
	$inf_insertdbrow[16] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':goofy:', 'smilies/goofy.gif')";
	$inf_insertdbrow[17] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':-D', 'smilies/grin.gif')";
	$inf_insertdbrow[18] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':-?', 'smilies/huh.gif')";
	$inf_insertdbrow[19] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':idea:', 'smilies/idea.gif')";
	$inf_insertdbrow[20] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':-L', 'smilies/laugh.gif')";
	$inf_insertdbrow[21] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':lips:', 'smilies/lips.gif')";
	$inf_insertdbrow[22] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':-x', 'smilies/lipsrsealed.gif')";
	$inf_insertdbrow[23] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':mad:', 'smilies/mad.gif')";
	$inf_insertdbrow[24] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':-K', 'smilies/ok.gif')";
	$inf_insertdbrow[25] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':rolleyes:', 'smilies/rolleyes.gif')";
	$inf_insertdbrow[26] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':-(', 'smilies/sad.gif')";
	$inf_insertdbrow[27] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':-O', 'smilies/shocked.gif')";
	$inf_insertdbrow[28] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':shy:', 'smilies/shy.gif')";
	$inf_insertdbrow[29] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':smartass:', 'smilies/smartass.gif')";
	$inf_insertdbrow[30] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':smarty:', 'smilies/smarty.gif')";
	$inf_insertdbrow[31] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':-)', 'smilies/smiley.gif')";
	$inf_insertdbrow[32] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':tongue:', 'smilies/tongue.gif')";
	$inf_insertdbrow[33] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':undecided:', 'smilies/undecided.gif')";
	$inf_insertdbrow[34] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':veryangry:', 'smilies/veryangry.gif')";
	$inf_insertdbrow[35] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (';-)', 'smilies/wink.gif')";
	$inf_insertdbrow[36] = DB_EBP_CHAT_SMILEYS." (etchat_smileys_sign, etchat_smileys_img) VALUES (':worried:', 'smilies/worried.gif')";


	$inf_droptable[1] = DB_EBP_SETTINGS;
	$inf_droptable[2] = DB_EBP_CHAT_CONFIG;
	$inf_droptable[3] = DB_EBP_CHAT_USERS;
	$inf_droptable[4] = DB_EBP_CHAT_BLACKLIST;
	$inf_droptable[5] = DB_EBP_CHAT_MESSAGES;
	$inf_droptable[6] = DB_EBP_CHAT_ROOMS;
	$inf_droptable[7] = DB_EBP_CHAT_SMILEYS;
	$inf_droptable[8] = DB_EBP_CHAT_USERONLINE;
	$inf_droptable[9] = DB_EBP_CHAT_KICKED_USER;


	$inf_adminpanel[1] = array(
		"title" => $LOCALE['INF_ADMINLINK_NAME'],
		"image" => "image.gif",
		"panel" => "administration/index.php",
		"rights" => "EBP"
	);


	$inf_sitelink[1] = array(
		"title" => $LOCALE['INF_SITELINK_NAME'],
		"url" => "index.php",
		"visibility" => "101"
	);


?>