<?php

	require_once "../../maincore.php";
	require_once THEMES."templates/header.php";
	require_once INFUSIONS."chat_panel/functions.php";
	
	opentable("Chat");
	if (isset($_GET['logout']) && $_GET['logout'] == "true") {

			echo "<center>Das Logout aus dem Chat war erfolgreich.</center>\n";

	} else {

		if (!iMEMBER) {

			echo "<center>Bitte logge dich ein, um den Chat betretten zu k&ouml;nnen.</center>\n";

		} else {

			session_start();
			$s_name = session_name();
			$s_id = session_id();
			header("Location: ".INFUSIONS."chat_panel/et_chat/start.php?".$s_name."=".$s_id."");
			$_SESSION['phpfusion_username'] = $userdata['user_name'];
			$_SESSION['phpfusion_logouturl'] = $settings['site_url']."infusions/chat_panel/index.php?logout=true";
			session_write_close();

		}

	}
	echo ebp_copyright();
	closetable();
	
	require_once THEMES."templates/footer.php";
	
?>