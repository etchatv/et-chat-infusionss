<?php
/**
 * Class RoomsIndex - Admin area
 *
 * LICENSE: CREATIVE COMMONS PUBLIC LICENSE  "Namensnennung — Nicht-kommerziell 2.0"
 *
 * @copyright  2009 <SEDesign />
 * @license    http://creativecommons.org/licenses/by-nc/2.0/de/
 * @version    $3.0.6$
 * @link       http://www.sedesign.de/de_produkte_chat-v3.html
 * @since      File available since Alpha 1.0
 */

class AdminRoomsIndex extends DbConectionMaker
{

	/**
	* Constructor
	*
	* @uses LangXml object creation
	* @uses LangXml::getLang() parser method
	* @uses ConnectDB::sqlSet()	
	* @uses ConnectDB::sqlGet()	
	* @uses ConnectDB::close()	
	* @return void
	*/
	public function __construct (){ 
		
		// call parent Constructor from class DbConectionMaker
		parent::__construct(); 

		session_start();

		header('Cache-Control: no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0');
		
		// create new LangXml Object
		$langObj = new LangXml();
		$lang=$langObj->getLang()->admin[0]->admin_rooms[0];
		
		
		if ($_SESSION['etchat_v3_user_priv']=="admin"){
			
			$feld=$this->dbObj->sqlGet("SELECT etchat_id_room, etchat_roomname FROM {$this->_prefix}etchat_rooms");
			$this->dbObj->close();
			
			if (is_array($feld)){
				$print_room_list = "<table>";
				foreach($feld as $datasets){
					if ($datasets[0]!=1) 
						$print_room_list.= "<tr><td><b>".$datasets[1]."</b></td><td>&nbsp;&nbsp;&nbsp;</td><td><a href=\"./?AdminDeleteRoom&id=".$datasets[0]."\">".$lang->delete[0]->tagData."</a></td><td><a href=\"./?AdminEditRoom&id=".$datasets[0]."\">".$lang->rename[0]->tagData."</a></td></tr>";
					else 
						$print_room_list.= "<tr><td><b>".$datasets[1]."</b></td><td>&nbsp;&nbsp;&nbsp;</td><td style=\"color: #888888;\"><strike>".$lang->delete[0]->tagData."</strike></td><td><a href=\"./?AdminEditRoom&id=".$datasets[0]."\">".$lang->rename[0]->tagData."</a></td></tr>";
				}
				$print_room_list.= "</table>";
			}
			
			// initialize Template
			$this->initTemplate($lang, $print_room_list);
			
		}else{
			echo $lang->error[0]->tagData;
			return false;
		}
		
	}
	
	/**
	* Initializer for template
	*
	* @param  String $print_room_list
	* @param  XMLParser $lang, Obj with the needed lang tag from XML lang-file
	* @return void
	*/
	private function initTemplate($lang, $print_room_list){
		// Include Template
		include_once("styles/admin_tpl/indexRooms.tpl.html");
	}
}