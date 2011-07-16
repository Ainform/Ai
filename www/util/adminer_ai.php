<?php

require_once '../config.php';
error_reporting(0);
function adminer_object() {

     class AdminerSoftware extends Adminer {

        /*function name() {
            // custom name in title and heading
            return 'Ainform';
        }

        function permanentLogin() {
            // key used for permanent login
            return "2e292853d7988024996549606cfb4e59";
        }
*/
        function credentials() {
           // debug("test2");
            // server, username and password for connecting to database
            return array(DbHost, DbUserName, DbUserPass);
        }
/*
        function database() {
            // database name, will be escaped by Adminer
            return DbName;
        }

        function login($login, $password) {
            // validate user submitted credentials
            return true;//($login == DbUserName && $password == DbUserPass);
        }

        function tableName($tableStatus) {
            // tables without comments would return empty string and will be ignored by Adminer
            return h($tableStatus["Comment"]);
        }

        function fieldName($field, $order = 0) {
            // only columns with comments will be displayed and only the first five in select
            return ($order <= 5 && !ereg('_(md5|sha1)$', $field["field"]) ? h($field["comment"]) : "");
        }*/
        function headers() {
		if ($this->sameOrigin) {
			header("X-Frame-Options: SameOrigin");
		}
		header("X-XSS-Protection: 0");
		return true;
	}

    }

    return new AdminerSoftware;
}

// include original Adminer or Adminer Editor
include "./adminer-3.2.2.php";
?>
