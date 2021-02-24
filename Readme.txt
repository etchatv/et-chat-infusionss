1.) Lade den Ordner "chat_panel" in den Ordner "infusions" deiner Webseite hoch.
2.) Rufe deine Webseite auf & gehe in den Adminbereich unter "System-Admin".
3.) Klicke auf "Infusionen" & anschließend wähle über die Textbox "chat_panel" aus & klicke auf "Infuse".
4.) Rufe den Adminbereich auf, klicke auf "Infusionen" & dannach auf "PHP-Fusion - ET-Chat Bridge".
5.) Tätige hier aktuelle Einstellungen, aber bevor du den Chat verwenden kannst, musst du unbedingt einen Raum anlegen
6.) Zu guter Letzt noch, musst du die Ordnerstruktur "infusions/chat_panel/et_chat/config.php" aufrufen & öffnen.
7.) Gib hier noch die aktuellen Datenbank-Zugangsdaten an (zufinden in der config.php vom PHP-Fusion Root).
8.) Vorlage:
	$database = ""; // Datenbankname
	$sqlhost  = ""; // Datenbank Host
	$sqluser  = ""; // Datenbank Username
	$sqlpass  = ""; // Datenbank Passwort
	$prefix   = "";	// Prefix von PHP-Fusion (bspw. fusion_)
9.) Datei speichern, hochladen & unbedingt den CHMOD auf 664 setzten !!
10.) Viel Spaß ;).