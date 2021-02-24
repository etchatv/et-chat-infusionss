<?php
/**
 * Config DB and Chat parameters
 *
 * LICENSE: CREATIVE COMMONS PUBLIC LICENSE  "Namensnennung — Nicht-kommerziell 2.0"
 *
 * @copyright  2009 <SEDesign />
 * @license    http://creativecommons.org/licenses/by-nc/2.0/de/
 * @version    $3.0.6$
 * @link       http://www.sedesign.de/de_produkte_chat-v3.html
 * @since      File available since Beta 1.0
 */
 
 
	$database = ""; // Datenbankname
	$sqlhost  = ""; // Datenbank Host
	$sqluser  = ""; // Datenbank Username
	$sqlpass  = ""; // Datenbank Passwort
	$prefix   = "";	// Prefix von PHP-Fusion (bspw. fusion_)
	
// Parameter wird IMMER benötigt um die richtige SQL-Syntaxis zu erzeugen und auch bei der Anbindung über PDO umd die richtige DB auszuwählen
$usedDatabaseType = "mysql";	// "mysql" oder "pgsql"

// ############################################################################
/*
 Welche Datenbankanbindung soll benutzt werden?
 Wenn Sie sich mit der Serverkonfiguration  nicht besonders gut auskennen,  sollen Sie diese Einstellungen nicht verändern!
*/

// PDO ist die einheitliche Datenbankanbindungskomponennte in PHP5 für alle Datenbanken, also MySQL und PostgreSQL 

$usedDatabaseExtension = "pdo";

// Nach Wunsch oder wenn die PDO nicht verfügbar ist, kann die MySQLi für die Anbindung an MySQL benutzt werden. 
// Es soll angeblich auch etwas performanter sein.

//$usedDatabaseExtension = "mysqli";

// ############################################################################
// Chatparameter optional zu verändern

// Wieviele alte Messages sieht der User, wenn er den Chat neu betritt.
$messages_shown_on_entrance=1;

// Wieviele Male darf man sich in drei Muten in den Chat neu einloggen.
$limit_logins_in_three_minutes=4;
// ############################################################################
