<?php
/**
 * Class AdminDeleteSmilies - Admin area
 *
 * LICENSE: CREATIVE COMMONS PUBLIC LICENSE  "Namensnennung — Nicht-kommerziell 2.0"
 *
 * @copyright  2009 <SEDesign />
 * @license    http://creativecommons.org/licenses/by-nc/2.0/de/
 * @version    $3.0.6$
 * @link       http://www.sedesign.de/de_produkte_chat-v3.html
 * @since      File available since Alpha 1.0
 */

class AdminDeleteSmilies extends DbConectionMaker
{

	/**
	* Constructor
	*	
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
		$lang=$langObj->getLang()->admin[0]->admin_smilies[0];
		
		
		if ($_SESSION['etchat_v3_user_priv']=="admin"){
			
			$this->dbObj->sqlSet("DELETE FROM {$this->_prefix}etchat_smileys WHERE etchat_smileys_id = ".(int)$_GET['id']);
			unlink("./".$_GET['pic']);
			$this->dbObj->close();
			header("Location: ./?AdminSmiliesIndex");

		}else{
			echo $lang->error[0]->tagData;
			return false;
		}
	}
}