<?php
/**
 * Logout, class to logout any user
 *
 * LICENSE: CREATIVE COMMONS PUBLIC LICENSE  "Namensnennung — Nicht-kommerziell 2.0"
 *
 * @copyright  2009 <SEDesign />
 * @license    http://creativecommons.org/licenses/by-nc/2.0/de/
 * @version    $3.0.6$
 * @link       http://www.sedesign.de/de_produkte_chat-v3.html
 * @since      File available since Alpha 1.0
 */
 
class Logout extends DbConectionMaker
{
	/**
	* Constructor
	*
	* @uses ConnectDB::sqlSet()	
	* @uses ConnectDB::close()	
	* @return void
	*/
	public function __construct (){
	
		// call parent Constructor from class DbConectionMaker
		parent::__construct();
	
		session_start();
		
		// all documentc requested per AJAX should have this part to turn off the browser and proxy cache for any XHR request
		header('Cache-Control: no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0');
		
		// create new LangXml Object
		$langObj = new LangXml();
		$lang=$langObj->getLang()->logout_php[0];
		
		if ($_SESSION['etchat_v3_userstatus']!="status_invisible")
			if (isset($_SESSION['etchat_v3_username']) && !empty($_SESSION['etchat_v3_username']))
				new SysMessage($this->dbObj, "<b>".$_SESSION['etchat_v3_username']."</b> ".$lang->exit[0]->tagData,0,0);
			
		$this->dbObj->sqlSet("DELETE FROM {$this->_prefix}etchat_useronline WHERE etchat_onlineuser_fid = ".(int)$_SESSION['etchat_v3_user_id']);
		
		if(!isset($_SESSION['etchat_v3_logout_url'])){
			@session_unset();
			@session_destroy();
			header("Location: ./");
		}
		else header("Location: ".$_SESSION['etchat_v3_logout_url']);
	}
}