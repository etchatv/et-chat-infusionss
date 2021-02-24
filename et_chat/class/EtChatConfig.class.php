<?php
/**
 * Abstract Class EtChatConfig contains vars for inheritance
 *
 * LICENSE: CREATIVE COMMONS PUBLIC LICENSE  "Namensnennung — Nicht-kommerziell 2.0"
 *
 * @copyright  2009 <SEDesign />
 * @license    http://creativecommons.org/licenses/by-nc/2.0/de/
 * @version    $3.0.6$
 * @link       http://www.sedesign.de/de_produkte_chat-v3.html
 * @since      File available since Alpha 1.0
 */

abstract class EtChatConfig
{
	protected $_database;
	protected $_sqlhost;
	protected $_sqluser;
	protected $_sqlpass;
	
	protected $_prefix;
	
	protected $_usedDatabase;
	protected $_usedDatabaseExtension;

	protected $_messages_shown_on_entrance;
	protected $_limit_logins_in_three_minutes;
	
	/**
	* Constructor
	*
	* @return void
	*/
	protected function __construct (){
	
		error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
		
		// Require the config.php
		if (isset($GLOBALS["path"]))
			require ($GLOBALS["path"].'config.php');
		else 
			require ('./config.php');

		
		$this->_database=$database;
		$this->_sqlhost=$sqlhost;
		$this->_sqluser=$sqluser;
		$this->_sqlpass=$sqlpass;
		$this->_prefix=$prefix;
		$this->_usedDatabase=$usedDatabaseType;
		$this->_usedDatabaseExtension=$usedDatabaseExtension;
		$this->_messages_shown_on_entrance=$messages_shown_on_entrance;
		$this->_limit_logins_in_three_minutes=$limit_logins_in_three_minutes;
	}
}
