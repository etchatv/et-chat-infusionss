<?php

	if (!defined("IN_FUSION")) { die(); }

	if (!defined("DB_EBP_SETTINGS"))         { define("DB_EBP_SETTINGS",         DB_PREFIX."ebp_settings");      }
	if (!defined("DB_EBP_CHAT_CONFIG"))      { define("DB_EBP_CHAT_CONFIG",      DB_PREFIX."etchat_config");     }
	if (!defined("DB_EBP_CHAT_USERS"))       { define("DB_EBP_CHAT_USERS",       DB_PREFIX."etchat_user");       }
	if (!defined("DB_EBP_CHAT_BLACKLIST"))   { define("DB_EBP_CHAT_BLACKLIST",   DB_PREFIX."etchat_blacklist");  }
	if (!defined("DB_EBP_CHAT_MESSAGES"))    { define("DB_EBP_CHAT_MESSAGES",    DB_PREFIX."etchat_messages");   }
	if (!defined("DB_EBP_CHAT_ROOMS"))       { define("DB_EBP_CHAT_ROOMS",       DB_PREFIX."etchat_rooms");      }
	if (!defined("DB_EBP_CHAT_SMILEYS"))     { define("DB_EBP_CHAT_SMILEYS",     DB_PREFIX."etchat_smileys");    }
	if (!defined("DB_EBP_CHAT_USERONLINE"))  { define("DB_EBP_CHAT_USERONLINE",  DB_PREFIX."etchat_useronline"); }
	if (!defined("DB_EBP_CHAT_KICKED_USER")) { define("DB_EBP_CHAT_KICKED_USER", DB_PREFIX."etchat_kick_user");  }

?>