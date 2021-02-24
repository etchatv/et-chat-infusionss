<?php
/**
 * Class MessageInserter, insert system and user messages into DB and transform it if needed
 *
 * LICENSE: CREATIVE COMMONS PUBLIC LICENSE  "Namensnennung — Nicht-kommerziell 2.0"
 *
 * @copyright  2009 <SEDesign />
 * @license    http://creativecommons.org/licenses/by-nc/2.0/de/
 * @version    $3.0.6$
 * @link       http://www.sedesign.de/de_produkte_chat-v3.html
 * @since      File available since Alpha 1.0
 */
 
class MessageInserter extends EtChatConfig
{
	/**
	* DB-Connection Obj
	* @var ConnectDB
	*/
	private $dbObj;
	
	/**
	* Status var only needed for information if the user will be inserted into blacklist because of spam
	* @var String
	*/
	public $status;

	/**
	* Constructor
	*
	* @param  ConnectDB $dbObj, Obj with the db connection handler
	* @param  Array $raum_array
	* @uses ConnectDB::sqlSet()	
	* @return void
	*/
	public function __construct ($dbObj, $raum_array){ 
		
		// call parent Constructor from class EtChatConfig
		parent::__construct();
		
		$this->dbObj=$dbObj;
		
		// message after room entrance
		if ($_POST['roomchange']=="true" && !empty($raum_array[0][3])){		
			// line break WIN
			$room_message_insert = str_replace("\r\n","<br />",$raum_array[0][3]);
			// line break LIN, Uniux, MacOS
			$room_message_insert = str_replace("\n","<br />",$room_message_insert);
			
			new SysMessage($this->dbObj, "<br /><div style=\"margin: 4px;\">".$room_message_insert."<div>",(int)$_POST['room'],$_SESSION['etchat_v3_user_id']);
		}
	
		if (isset($_POST['sysmess'])){
			$_POST['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES, "UTF-8");
			$_POST['message'] = "<b>".$_SESSION['etchat_v3_username']."</b> ".$_POST['message'];
	
			// do not create a visible room entrance message if the user is invisible, just make the message as a private one
			if ($_POST['roomchange']=="true" && $_SESSION['etchat_v3_userstatus']=="status_invisible") 
				$_POST['privat']=$_SESSION['etchat_v3_user_id'];
			
			new SysMessage($this->dbObj, $_POST['message'],(int)$_POST['room'],(int)$_POST['privat']);
		}
		else{
			
			// spam test
			if ($this->spamTester()) {
				$this->status = "spam";
				return false;
			}
			
			// transforms the $_POST['message'] before inserting it
			$this->messageTransformer();
			
			// message style parameters
			$style = "color:".htmlentities($_POST['color'], ENT_QUOTES, "UTF-8").";font-weight:".htmlentities($_POST['bold'], ENT_QUOTES, "UTF-8").";font-style:".htmlentities($_POST['italic'], ENT_QUOTES, "UTF-8").";";
			
			// inserts the user message into the DB
			$this->dbObj->sqlSet("INSERT INTO {$this->_prefix}etchat_messages ( etchat_user_fid, etchat_text, etchat_text_css, etchat_timestamp, etchat_fid_room, etchat_privat)
				VALUES ( '".$_SESSION['etchat_v3_user_id']."', '".$_POST['message']."', '".$style."', ".date('U').", ".(int)$_POST['room'].", ".(int)$_POST['privat'].")");
		

			// BOT -------------------------------------------
			/*
			if (substr($_POST['message'], 0, 5)==".time"){
				$db->sql("INSERT INTO {$this->_prefix}etchat_messages ( etchat_user_fid , etchat_text, etchat_text_css, etchat_timestamp, etchat_fid_room, etchat_privat)
					VALUES ( 1, '".date('d.m.Y - H:i')."', 'color:#".$_SESSION['etchat_v3_syscolor'].";font-weight:normal;font-style:normal;', ".date('U').", ".(int)$_POST['room'].", 0)", false);
			}
			if (substr($_POST['message'], 0, 8)==".version"){
				$db->sql("INSERT INTO {$this->_prefix}etchat_messages ( etchat_user_fid , etchat_text, etchat_text_css, etchat_timestamp, etchat_fid_room, etchat_privat)
					VALUES ( 1, 'ET-Chat v3.0.5', 'color:#".$_SESSION['etchat_v3_syscolor'].";font-weight:normal;font-style:normal;', ".date('U').", ".(int)$_POST['room'].", 0)", false);
			}
			if (substr($_POST['message'], 0, 1)=="/"){
				$db->sql("INSERT INTO {$this->_prefix}etchat_messages ( etchat_user_fid , etchat_text, etchat_text_css, etchat_timestamp, etchat_fid_room, etchat_privat)
					VALUES ( 1, 'IRC Befehle werden hier nicht unterstützt. Alle Einstellungen können stattdessen in Menues bequemm eingestellt werden.', 'color:#".$_SESSION['etchat_v3_syscolor'].";font-weight:normal;font-style:normal;', ".date('U').", ".(int)$_POST['room'].", 0)", false);
			}
			*/
			//--------------------------------
		}
	}

	
	/**
	* Transforms the message
	*
	* @return void
	*/
	private function messageTransformer(){
		$_POST['message'] =	substr($_POST['message'], 0, 1000);
		if (strlen($_POST['message'])>999) $_POST['message'] .="...";

		$woerter_array=explode(" ",$_POST['message']);
		foreach($woerter_array as $wort){
			if (strlen($wort)>50 && substr($wort, 0, 7)!="http://" && substr($wort, 0, 8)!="https://" && substr($wort, 0, 6)!="ftp://" && !eregi(']http://', $wort)){
				$new_wort = wordwrap( $wort, 50, " ", 1);
				$_POST['message'] = str_replace($wort, $new_wort, $_POST['message']);
			}
		}
		$_POST['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES, "UTF-8");
	}
	
	
	/**
	* Test if the message is a spam, warns the user and insert him into the blacklist if needed
	*
	* @uses LangXml object creation
	* @uses LangXml::getLang() parser method
	* @uses Blacklist object creation
	* @uses Blacklist::userInBlacklist() checks if in the Blacklist
	* @uses Blacklist::insertUser()
	* @uses Blacklist::allowedToAndSetCookie()
	* @uses Blacklist::killUserSession()
	* @return bool
	*/
	private function spamTester(){
	
		$_SESSION['etchat_v3_spam'][]=date('U');

		// do not overload the session array in the case of server performanse, so clear it every 200 entries
		if (count($_SESSION['etchat_v3_spam'])>200) {
			unset($_SESSION['etchat_v3_spam']);
			$_SESSION['etchat_v3_spam']=array();
		}

		// 3 messages one after another are allowed, excepting ADMIN/MOD
		if (count($_SESSION['etchat_v3_spam'])>3 && $_SESSION['etchat_v3_user_priv']!="admin" && $_SESSION['etchat_v3_user_priv']!="mod"){
	
			$spam_interval=($_SESSION['etchat_v3_spam'][(count($_SESSION['etchat_v3_spam'])-1)] - $_SESSION['etchat_v3_spam'][(count($_SESSION['etchat_v3_spam'])-4)]);
	
			if ($spam_interval < 6 ){		
				// create new LangXml Object
				$langObj = new LangXml();
				$lang=$langObj->getLang()->reloader_php[0];
				new SysMessage($this->dbObj, $lang->spam[0]->tagData,(int)$_POST['room'],(int)$_SESSION['etchat_v3_user_id']);

				$_SESSION['etchat_v3_spam_warn']++;
		
				if ($_SESSION['etchat_v3_spam_warn']>2){
					// create new Blacklist Object
					$blObj = new Blacklist($this->dbObj);
					$blObj->insertUser($_SESSION['etchat_v3_user_id'],300);
					$blObj->userInBlacklist();
					$blObj->allowedToAndSetCookie();
					$blObj->killUserSession();
					return true;
				}
				else return false;		
			}
		}	
	
	}
	
	
}
