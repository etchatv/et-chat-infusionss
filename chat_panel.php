<?php

	if (!defined("IN_FUSION")) { die(); }
	include_once INFUSIONS."chat_panel/functions.php";


	if ($settings_ebp['online_chatter'] == 1) {
		openside("Chatter im Chat");
		require_once INFUSIONS."chat_panel/et_chat/online_chatter.php";
		closeside();
	}

?>