<?php

	if (!defined("IN_FUSION")) { die(); }
	
	require_once INFUSIONS."chat_panel/infusion_db.php";

	if (dbrows(dbquery("SHOW TABLES LIKE '".DB_EBP_SETTINGS."'")) == 1) {
		$settings_ebp = dbarray(dbquery("SELECT * FROM ".DB_EBP_SETTINGS));
	} else {
		$settings_ebp = "";
	}
	
	function ebp_copyright() {
		
		global $settings_ebp;
		
		if ($settings_ebp['copyright'] == 1) {
			$res = "<p style='text-align: right; padding: 0px; margin: 6px 0px 0px 0px;'><a href='http://www.darkweb.at/' title='Copyright &copy; Darkweb.at' target='blank'>&copy;</a></p>\n";
		} else {
			$res = "";
		}

		return $res;
		
	}

	function ebp_get_username($id) {

		$id = isNum($id) ? $id : 0;

		$result = dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$id."'");
		if (dbrows($result) == 1) {
			$data = dbarray($result);
			return $data['user_name'];
		} else {
			return "User existiert nicht";
		}

	}

	function ebp_get_status($name) {

		if ($name == "mod") { return "Moderator"; }
		elseif ($name == "admin") { return "Administrator"; }
		else { return "Keine Rechte"; }

	}

	function ebp_userlist($value) {

		$value = isNum($value) ? $value : 0;

		$result = dbquery("SELECT user_id, user_name FROM ".DB_USERS." ORDER BY user_level DESC, user_name ASC");
		if (dbrows($result) != 0) {
			$res  = "\n<select name='name' id='name' class='textbox'>\n";
			$res .= "<option value='0'".($value == 0 ? " selected='selected'" : "").">Bitte w&auml;hlen</option>\n";
			while ($data = dbarray($result)) {
				$res .= "<option value='".$data['user_id']."'".($value == $data['user_id'] ? " selected='selected'" : "").">".$data['user_name']."</option>\n";
			}
			$res .= "</select>\n";
		} else {
			$res = "Keine Mitglieder vorhanden.";
		}

		return $res;

	}

	function ebp_statuslist($value) {

		$value = ($value == "m" || $value == "a") ? $value : 0;

		$res  = "\n<select name='status' id='status' class='textbox'>\n";
		$res .= "<option value='0'".($value == 0   ? " selected='selected'" : "").">Bitte w&auml;hlen</option>\n";
		$res .= "<option value='m'".($value == "m" ? " selected='selected'" : "").">Moderator</option>\n";
		$res .= "<option value='a'".($value == "a" ? " selected='selected'" : "").">Administrator</option>\n";
		$res .= "</select>\n";

		return $res;

	}

	function ebp_field_textbox($name, $value) {
		return "<input type='text' name='".$name."' id='".$name."' value='".$value."' class='textbox' style='width: 120px;' />";
	}

	function ebp_field_yesno($name, $value) {

		$value = isNum($value) ? $value : 0;

		$res  = "\n<select name='".$name."' class='textbox' id='".$name."'>\n";
		$res .= "<option value='0'".($value == 0 ? " selected='selected'" : "").">Bitte w&auml;hlen</option>\n";
		$res .= "<option value='1'".($value == 1 ? " selected='selected'" : "").">Ja</option>\n";
		$res .= "<option value='2'".($value == 2 ? " selected='selected'" : "").">Nein</option>\n";
		$res .= "</select>\n";
		return $res;

	}

	function ebp_admin_help($help) {
		return "<abbr title='".$help."'><img src='".INFUSIONS."chat_panel/images/help.png' title='' alt='Hilfe' style='border: 0px;' /></abbr>\n";
	}

	function ebp_room_access($room_id) {

		$room_id = (!isNum($room_id) OR $room_id == "") ? 0 : $room_id;
		$result = dbquery("SELECT etchat_room_goup, etchat_room_pw FROM ".DB_EBP_CHAT_ROOMS." WHERE etchat_id_room='".$room_id."'");
		if (dbrows($result) != 1) {
			return "Nicht verf&uuml;gbar";
		} else {
			$data = dbarray($result);
			if ($data['etchat_room_goup'] == 0) { return "jedes Mitglied"; 
			} elseif ($data['etchat_room_goup'] == 1) { return "Adminis &amp; Mods";
			} elseif ($data['etchat_room_goup'] == 2) { return "nur Admins";
			} elseif ($data['etchat_room_goup'] == 3) { return "Passwortgesch&uuml;tzt<br /><em>".$data['etchat_room_pw']."</em>";
			} else { return "Nicht verf&uuml;gbar"; }
		}

	}

	function ebp_room_accesslist() {

		$res  = "<select name='zugriff' class='textbox'>\n";
		$res .= "<option value='4' selected='selected'>Bitte w&auml;hlen</option>\n";
		$res .= "<option value='0'>jedes Mitglied</option>\n";
		$res .= "<option value='1'>Admins &amp; Mods</option>\n";
		$res .= "<option value='2'>nur Admins</option>\n";
		$res .= "</select>\n";
		$res .= "<br />oder Passwortgesch&uuml;tzt:<br />\n";
		$res .= ebp_field_textbox("password", "");
		return $res;

	}

	$style = "
	<style type='text/css'>
	abbr, acronym {
		cursor: help;
		border-bottom: 1px dotted #e1e1e1;
	}

	#ebp-warn-msg {
		margin: 0px auto;
		width: 80%;
		border: 1px solid #e1e1e1;
		background-color: #ffffff;
		padding: 4px;
	}
	</style>\n";

	add_to_head($style);

?>