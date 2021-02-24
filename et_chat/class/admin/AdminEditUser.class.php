<?php
/**
 * Class AdminEditUser - Admin area
 *
 * LICENSE: CREATIVE COMMONS PUBLIC LICENSE  "Namensnennung � Nicht-kommerziell 2.0"
 *
 * @copyright  2009 <SEDesign />
 * @license    http://creativecommons.org/licenses/by-nc/2.0/de/
 * @version    $3.0.6$
 * @link       http://www.sedesign.de/de_produkte_chat-v3.html
 * @since      File available since Alpha 1.0
 */

class AdminEditUser extends DbConectionMaker
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
		$lang=$langObj->getLang()->admin[0]->admin_user[0];
		
		
		if ($_SESSION['etchat_v3_user_priv']=="admin"){
			
			$feld=$this->dbObj->sqlGet("SELECT etchat_user_id, etchat_username, etchat_userpw, etchat_userprivilegien FROM {$this->_prefix}etchat_user WHERE etchat_user_id=".(int)$_GET['id']);
			$this->dbObj->close();
						
			// initialize Template
			$this->initTemplate($lang, $feld);
			
		}else{
			echo $lang->error[0]->tagData;
			return false;
		}	
	}
	
	/**
	* Initializer for template
	*
	* @param Array $feld
	* @param XMLParser $lang, Obj with the needed lang tag from XML lang-file
	* @return void
	*/
	private function initTemplate($lang, $feld){
		// Include Template
		include_once("styles/admin_tpl/editUser.tpl.html");
	}
}