<?php
/**
 * ChangeStatus for user blocking in the session
 *
 * LICENSE: CREATIVE COMMONS PUBLIC LICENSE  "Namensnennung — Nicht-kommerziell 2.0"
 *
 * @copyright  2009 <SEDesign />
 * @license    http://creativecommons.org/licenses/by-nc/2.0/de/
 * @version    $3.0.6$
 * @link       http://www.sedesign.de/de_produkte_chat-v3.html
 * @since      File available since Alpha 1.0
 */
 
class ChangeStatus extends DbConectionMaker
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
		
		
		if($_POST['img']=="status_online"){$_POST['img']=""; $_POST['text']="";}

		if ($_SESSION['etchat_v3_user_priv']!="admin" && $_SESSION['etchat_v3_user_priv']!="mod"){
			if($_POST['img']=="status_invisible"){$_POST['img']=""; $_POST['text']="";}
		}

		$this->dbObj->sqlSet("UPDATE {$this->_prefix}etchat_useronline SET 
			etchat_user_online_user_status_img = '".addslashes($_POST['img'])."',
			etchat_user_online_user_status_text = '".addslashes(urldecode($_POST['text']))."'
			WHERE etchat_onlineuser_fid = ".(int)$_SESSION['etchat_v3_user_id']);
		
		// close DB connection
		$this->dbObj->close();
		
		echo "1";
	}
}