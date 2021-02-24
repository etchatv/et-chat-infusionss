<?php

	require_once "../../../maincore.php";
	require_once THEMES."templates/admin_header.php";
	require_once INFUSIONS."chat_panel/functions.php";
	
	if (!checkrights("EBP") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

	ob_start();
	echo "<table align='center' width='100%' cellpadding='1' cellspacing='1' class='tbl-border'>\n";
	echo "<tr>\n";
	echo "<td align='center' width='33%' class='".($_GET['page'] == 1 ? "tbl2" : "tbl1")."'>".($_GET['page'] == 1 ? "<strong>Einstellungen</strong>" : "<a href='".INFUSIONS."chat_panel/administration/index.php".$aidlink."&amp;page=1' title='Einstellungen'>Einstellungen</a>")."</td>\n";
	echo "<td align='center' width='34%' class='".($_GET['page'] == 2 ? "tbl2" : "tbl1")."'>".($_GET['page'] == 2 ? "<strong>Teammitglieder</strong>" : "<a href='".INFUSIONS."chat_panel/administration/index.php".$aidlink."&amp;page=2' title='Teammitglieder'>Teammitglieder</a>")."</td>\n";
	echo "<td align='center' width='33%' class='".($_GET['page'] == 3 ? "tbl2" : "tbl1")."'>".($_GET['page'] == 3 ? "<strong>R&auml;ume</strong>" : "<a href='".INFUSIONS."chat_panel/administration/index.php".$aidlink."&amp;page=3' title='R&auml;ume'>R&auml;ume</a>")."</td>\n";
	echo "</tr>\n";
	echo "</table><hr />\n";
	define ("EBP_ADMIN_NAVIGATION", ob_get_contents());
	ob_end_clean();

	if (isset($_GET['page']) && $_GET['page'] == 1) {

		if (isset($_POST['save'])) {

			$error = "\n";

			$chatsystem = stripinput(trim($_POST['chatsystem']));
			$onlinelist = stripinput(trim($_POST['onlinelist']));
			$copyright  = stripinput(trim($_POST['copyright']));

			$rows = stripinput(trim($_POST['rows']));
			$chache = stripinput(trim($_POST['chache']));
			$reload = stripinput(trim($_POST['reload']));

			if ($chatsystem == "" OR $chatsystem == 0) { $error .= "&raquo; Du musst ausw&auml;hlen, ob du das Chatsystem aktivieren m&ouml;chtest oder nicht.<br />\n"; }
			if ($onlinelist == "" OR $onlinelist == 0) { $error .= "&raquo; Du musst ausw&auml;hlen, ob die Onlineliste im Panel erscheinen soll.<br />\n"; }
			if ($copyright == "" OR $copyright == 0) { $error .= "&raquo; Du musst ausw&auml;hlen, ob das Copyright angezeigt werden soll oder nicht.<br />\n"; }

			if ($rows == ""  OR $rows == 0) { $error .= "&raquo; Du musst angeben, wieviele Zeilen angezeigt werden &amp; die Zahl muss gr&ouml;&szlig;er als 1 sein<br />\n"; }
			if ($chache == "" OR $chache == 0) { $error .= "&raquo; Du musst angeben, wieviele Tage der Verlauf gespeichert werden soll &amp; der Tag muss mindestens 1 sein.<br />\n"; }
			if ($reload == "" OR $reload < 1000) { $error .= "&raquo; Du musst angeben, wie hoch die Reloadsequenz sein darf &amp; diese muss mindestens 1000 sein.<br />\n"; }

			if ($error != "" && $error != "\n") {
				$msg = $error;
			} else {
				$msg = "Die &Auml;nderungen wurden erfolgreich gespeichert.";
				$update1 = dbquery("UPDATE ".DB_EBP_SETTINGS." SET system='".$chatsystem."', online_chatter='".$onlinelist."', copyright='".$copyright."'");
				$update2 = dbquery("UPDATE ".DB_EBP_CHAT_CONFIG." SET etchat_config_messages_im_chat='".$rows."', etchat_config_loeschen_nach='".$chache."', etchat_config_reloadsequenz='".$reload."' WHERE etchat_config_id='1'");
			}
		}

		$settings1 = dbarray(dbquery("SELECT system, online_chatter, copyright FROM ".DB_EBP_SETTINGS));
		$settings2 = dbarray(dbquery("SELECT etchat_config_messages_im_chat AS rows, etchat_config_loeschen_nach AS chache, etchat_config_reloadsequenz AS reload FROM ".DB_EBP_CHAT_CONFIG." WHERE etchat_config_id='1'"));
		opentable("Einstellungen");
		echo EBP_ADMIN_NAVIGATION;
		if (isset($_POST['save'])) { echo "<div id='ebp-warn-msg'>".$msg."</div><hr />\n"; }
		echo "<form action='".FUSION_SELF.$aidlink."&amp;page=1' method='post' name='postform'>\n";
		echo "<table align='center' width='80%' cellpadding='1' cellspacing='1' class='tbl-border'>\n";
		echo "<tr>\n";
		echo "<td align='center' width='100%' class='tbl2' colspan='3'><strong>Allgemeine Einstellungen</strong></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align='left' width='40%' class='tbl1'>Chatsystem aktivieren:</td>\n";
		echo "<td align='center' width='10%' class='tbl1'>".ebp_admin_help("Bitte w&auml;hle, ob du das Chatsystem verwenden m&ouml;chtest oder nicht.")."</td>\n";
		echo "<td align='left' width='50%' class='tbl1'>".ebp_field_yesno("chatsystem", $settings1['system'])."</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align='left' width='40%' class='tbl1'>Onlineliste im Panel anzeigen:</td>\n";
		echo "<td align='center' width='10%' class='tbl1'>".ebp_admin_help("Hier kannst du w&auml;hlen, ob du die Chatter im Panel anzeigen lassen m&ouml;chtest.")."</td>\n";
		echo "<td align='left' width='50%' class='tbl1'>".ebp_field_yesno("onlinelist", $settings1['online_chatter'])."</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align='left' width='40%' class='tbl1'>Copyright anzeigen:</td>\n";
		echo "<td align='center' width='10%' class='tbl1'>".ebp_admin_help("Hier kannst du das sichtbare Copyright der Infusion ausschalten. Wir w&auml;hren dir dankbar, wenn du dieses aktiviert l&auml;sst.")."</td>\n";
		echo "<td align='left' width='50%' class='tbl1'>".ebp_field_yesno("copyright", $settings1['copyright'])."</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align='center' width='100%' class='tbl2' colspan='3'><strong>Allgemeine Chateinstellungen</strong></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align='left' width='40%' class='tbl1'>Anzahl der angezeigten Zeilen im Chat:</td>\n";
		echo "<td align='center' width='10%' class='tbl1'>".ebp_admin_help("Wieviele Zeilen (Chatverlauf) im Chat angezeigt werden sollen.")."</td>\n";
		echo "<td align='left' width='50%' class='tbl1'>".ebp_field_textbox("rows", $settings2['rows'])." Zeile(n)</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align='left' width='40%' class='tbl1'>Wie lange soll der Verlauf gespeichert bleiben:</td>\n";
		echo "<td align='center' width='10%' class='tbl1'>".ebp_admin_help("Wielange Tage der Verlauf in der MySQL-Datenbank gespeichert werden soll.")."</td>\n";
		echo "<td align='left' width='50%' class='tbl1'>".ebp_field_textbox("chache", $settings2['chache'])." Tag(e)</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align='left' width='40%' class='tbl1'>Reloadsequenz in Millisekunden:</td>\n";
		echo "<td align='center' width='10%' class='tbl1'>".ebp_admin_help("Ein Wert zwischen 1000 (1 Sekunde) &amp; 10000 (10 Sekunden) w&auml;hre sinnvoll.")."</td>\n";
		echo "<td align='left' width='50%' class='tbl1'>".ebp_field_textbox("reload", $settings2['reload'])." Millisekunde(n)</td>\n";
		echo "</tr>\n";
		/* ADDED FOR V2
		echo "<tr>\n";
		echo "<td align='left' width='40%' class='tbl1'>Style:</td>\n";
		echo "<td align='center' width='10%' class='tbl1'>".ebp_admin_help("Bitte w&auml;hle aus, welchen Style der Chat benutzen soll.")."</td>\n";
		echo "<td align='left' width='50%' class='tbl1'></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align='left' width='40%' class='tbl1'>Sprache:</td>\n";
		echo "<td align='center' width='10%' class='tbl1'>".ebp_admin_help("Hier kannst du die Sprache des Chates einstellen.")."</td>\n";
		echo "<td align='left' width='50%' class='tbl1'></td>\n";
		echo "</tr>\n";
		ADDED FOR V2 */
		echo "<tr>\n";
		echo "<td align='right' width='100%' class='tbl2' colspan='3'><input type='submit' name='save' value='Einstellungen speichern' class='button' /></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		echo ebp_copyright();
		closetable();
	
	} elseif (isset($_GET['page']) && $_GET['page'] == 2) {

		if (isset($_GET['action']) && $_GET['action'] == "delete") {

			if (!isset($_GET['id']) || !isNum($_GET['id']) || $_GET['id'] == "") { redirect(FUSION_SELF.$aidlink."&amp;page=2"); }
			$result_id = dbquery("SELECT etchat_user_id FROM ".DB_EBP_CHAT_USERS." WHERE etchat_user_id='".$_GET['id']."'");
			if (dbrows($result_id) != 1) { redirect(FUSION_SELF.$aidlink."&amp;page=2"); }

			$delete1 = dbquery("DELETE FROM ".DB_EBP_CHAT_USERS." WHERE etchat_user_id='".$_GET['id']."'");

		}

		if (isset($_POST['save'])) {
	
			$error = "\n";

			$name = stripinput(trim($_POST['name']));
			$status = stripinput(trim($_POST['status']));

			if ($name == "" || $name == 0) { $error .= "&raquo; Du musst einen Benutzernamen ausw&auml;hlen.<br />\n"; }

			if ($error != "" && $error != "\n") {
				$msg = $error;
			} else {
				if ($status == "m") { $priv = "mod"; }
				elseif ($status == "a") { $priv = "admin"; }
				else { $error .= "&raquo; Du musst ausw&auml;hlen, welchen Status der Benutzer erhalten soll.<br />\n"; }

				$result_username = dbquery("SELECT etchat_username FROM ".DB_EBP_CHAT_USERS." WHERE etchat_username='".ebp_get_username($name)."'");
				if (dbrows($result_username) != 0) {
					$error .= "&raquo; Es existiert bereits ein Teammitglied namens &quot;".ebp_get_username($name)."&quot;.<br />\n";
				}

				if ($error != "" && $error != "\n") {
					$msg = $error;
				} else {
					$msg = "Das Teammitglied wurde erfolgreich hinzugef&uuml;gt.";
					$insert1 = dbquery("INSERT INTO ".DB_EBP_CHAT_USERS." (etchat_username, etchat_userpw, etchat_userprivilegien) VALUES ('".ebp_get_username($name)."', NULL, '".$priv."')");
				}
			}

		}

		opentable("Teammitglieder");
		echo EBP_ADMIN_NAVIGATION;
		if (isset($_POST['save'])) { echo "<div id='ebp-warn-msg'>".$msg."</div><hr />\n"; }
		if (isset($_GET['action']) && $_GET['action'] == "delete") { echo "<div id='ebp-warn-msg'>&raquo; Das Teammitglied wurde erfolgreich entfernt.</div><hr />\n"; }
		echo "<form action='".FUSION_SELF.$aidlink."&amp;page=2' method='post' name='postform'>\n";
		echo "<table align='center' width='80%' cellpadding='1' cellspacing='1' class='tbl-border'>\n";
		echo "<tr>\n";
		echo "<td align='left' width='70%' class='tbl1' nowrap='nowrap'>Benutzername</td>\n";
		echo "<td align='left' width='15%' class='tbl1' nowrap='nowrap'>Status</td>\n";
		echo "<td align='left' width='15%' class='tbl1' nowrap='nowrap'>Optionen</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align='left' width='100%' class='tbl2' colspan='3'><strong>Aktuelle Teammitglieder</strong></td>\n";
		echo "</tr>\n";
		$result = dbquery("SELECT etchat_user_id, etchat_username, etchat_userprivilegien FROM ".DB_EBP_CHAT_USERS." WHERE etchat_userprivilegien=('admin' || 'mod') ORDER BY etchat_userprivilegien ASC");
		if (dbrows($result) != 0) {
			while ($data = dbarray($result)) {
				echo "<tr>\n";
				echo "<td align='left' width='70%' class='tbl1' nowrap='nowrap'>&raquo; ".$data['etchat_username']."</td>\n";
				echo "<td align='left' width='15%' class='tbl1' nowrap='nowrap'>".ebp_get_status($data['etchat_userprivilegien'])."</td>\n";
				echo "<td align='left' width='15%' class='tbl1' nowrap='nowrap'><a href='".FUSION_SELF.$aidlink."&amp;page=2&amp;action=delete&amp;id=".$data['etchat_user_id']."' title='L&ouml;schen'>L&ouml;schen</a></td>\n";
				echo "</tr>\n";
			}
		} else {
			echo "<tr>\n";
			echo "<td align='center' width='100%' class='tbl1' colspan='3'>Keine Teammitglieder vorhanden.</td>\n";
			echo "</tr>\n";
		}
		echo "<tr>\n";
		echo "<td align='left' width='100%' class='tbl2' colspan='3'><strong>Teammitglied hinzuf&uuml;gen</strong></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align='left' width='70%' class='tbl1' nowrap='nowrap'>".ebp_userlist(0)."</td>\n";
		echo "<td align='left' width='15%' class='tbl1' nowrap='nowrap'>".ebp_statuslist(0)."</td>\n";
		echo "<td align='left' width='15%' class='tbl1' nowrap='nowrap'><input type='submit' name='save' value='OK' class='button' /></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		echo ebp_copyright();
		closetable();

	} elseif (isset($_GET['page']) && $_GET['page'] == 3) {

		if (isset($_GET['action']) && $_GET['action'] == "delete") {

			if (!isset($_GET['id']) || !isNum($_GET['id']) || $_GET['id'] == "") { redirect(FUSION_SELF.$aidlink."&amp;page=3"); }
			$result_id = dbquery("SELECT etchat_id_room FROM ".DB_EBP_CHAT_ROOMS." WHERE etchat_id_room='".$_GET['id']."'");
			if (dbrows($result_id) != 1) { redirect(FUSION_SELF.$aidlink."&amp;page=3"); }

			$delete1 = dbquery("DELETE FROM ".DB_EBP_CHAT_ROOMS." WHERE etchat_id_room='".$_GET['id']."'");

		}

		if (isset($_POST['save'])) {
	
			$error = "\n";

			$name = stripinput(trim($_POST['name']));
			$zugriff = stripinput(trim($_POST['zugriff']));
			$password = stripinput(trim($_POST['password']));

			if ($name == "") { $error .= "&raquo; Du musst einen Raumnamen angeben.<br />\n"; }
			if ($password != "") {
				$zugriff = 3;
			} else {
				if ($zugriff == 4) { $error .= "&raquo; Du musst ausw&auml;hlen, wer auf den Raum Zugriff haben darf.<br />\n"; }
			}

			if ($error != "" && $error != "\n") {
				$msg = $error;
			} else {
				$result_room = dbquery("SELECT etchat_roomname FROM ".DB_EBP_CHAT_ROOMS." WHERE etchat_roomname='".$name."'");
				if (dbrows($result_room) != 0) {
					$error .= "&raquo; Es existiert bereits ein Raum namens &quot;".$name."&quot;.<br />\n";
				}

				if ($error != "" && $error != "\n") {
					$msg = $error;
				} else {
					$msg = "Der Raum wurde erfolgreich hinzugef&uuml;gt.";
					if ($zugriff == 3) {
						$insert1 = dbquery("INSERT INTO ".DB_EBP_CHAT_ROOMS." (etchat_roomname, etchat_room_goup, etchat_room_pw, etchat_room_message) VALUES ('".$name."', 3, '".$password."', NULL)");
					} else {
						$insert1 = dbquery("INSERT INTO ".DB_EBP_CHAT_ROOMS." (etchat_roomname, etchat_room_goup, etchat_room_pw, etchat_room_message) VALUES ('".$name."', '".$zugriff."', NULL, NULL)");
					}
				}
			}

		}


		opentable("R&auml;ume");
		echo EBP_ADMIN_NAVIGATION;
		if (isset($_POST['save'])) { echo "<div id='ebp-warn-msg'>".$msg."</div><hr />\n"; }
		if (isset($_GET['action']) && $_GET['action'] == "delete") { echo "<div id='ebp-warn-msg'>&raquo; Der Raum wurde erfolgreich entfernt.</div><hr />\n"; }
		echo "<form action='".FUSION_SELF.$aidlink."&amp;page=3' method='post' name='postform'>\n";
		echo "<table align='center' width='80%' cellpadding='1' cellspacing='1' class='tbl-border'>\n";
		echo "<tr>\n";
		echo "<td align='left' width='60%' class='tbl1' nowrap='nowrap'>Raumname</td>\n";
		echo "<td align='left' width='25%' class='tbl1' nowrap='nowrap'>Zugriff</td>\n";
		echo "<td align='left' width='15%' class='tbl1' nowrap='nowrap'>Optionen</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align='left' width='100%' class='tbl2' colspan='3'><strong>Aktuelle R&auml;ume</strong></td>\n";
		echo "</tr>\n";
		$result = dbquery("SELECT etchat_id_room, etchat_roomname FROM ".DB_EBP_CHAT_ROOMS." ORDER BY etchat_roomname ASC");
		if (dbrows($result) != 0) {
			while ($data = dbarray($result)) {
				echo "<tr>\n";
				echo "<td align='left' width='60%' class='tbl1' nowrap='nowrap'>&raquo; ".$data['etchat_roomname']."</td>\n";
				echo "<td align='left' width='25%' class='tbl1' nowrap='nowrap'>".ebp_room_access($data['etchat_id_room'])."</td>\n";
				echo "<td align='left' width='15%' class='tbl1' nowrap='nowrap'><a href='".FUSION_SELF.$aidlink."&amp;page=3&amp;action=delete&amp;id=".$data['etchat_id_room']."' title='L&ouml;schen'>L&ouml;schen</a></td>\n";
				echo "</tr>\n";
			}
		} else {
			echo "<tr>\n";
			echo "<td align='center' width='100%' class='tbl1' colspan='3'>Keine R&auml;ume vorhanden.</td>\n";
			echo "</tr>\n";
		}
		echo "<tr>\n";
		echo "<td align='left' width='100%' class='tbl2' colspan='3'><strong>Raum hinzuf&uuml;gen</strong></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td align='left' width='60%' class='tbl1' nowrap='nowrap'>".ebp_field_textbox("name", "")."</td>\n";
		echo "<td align='left' width='25%' class='tbl1' nowrap='nowrap'>".ebp_room_accesslist()."</td>\n";
		echo "<td align='left' width='15%' class='tbl1' nowrap='nowrap'><input type='submit' name='save' value='OK' class='button' /></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		echo ebp_copyright();
		closetable();

	} else {

		redirect(INFUSIONS."chat_panel/administration/index.php".$aidlink."&amp;page=1");

	}

	require_once THEMES."templates/footer.php";

?>