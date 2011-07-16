<?php
/**
* Php Scaffolder: A tool to generate CRUD functionality
*
* Copyright 2009, Syed Abdul Baqi, baqi.syed@gmail.com
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*
* @package php scaffolder
* @author Syed Abdul Baqi <baqi.syed@gmail.com>
*/

session_start();

DEFINE('HOST', 'localhost');
DEFINE('USER', 'root');
DEFINE('PSWD', 'root');

mysql_connect(HOST, USER, PSWD) or die('Error: ' . mysql_error());
if(isset($_SESSION['database']))
{
  mysql_select_db($_SESSION['database']) or die('Error: ' . mysql_error());
}

$type_arr = array(
                    'text' => 'Text Box', 
                    'textarea' => 'Textarea', 
                    //'checkbox' => 'Checkbox', 
                    'file' => 'File',
                    //'date' => 'Date', 
                    //'modified'  => 'Modified',
                    //'created'   => 'Created',
                    '0' => '--DON\'T USE--'
                 );

$ss_arr = array(
                  '0' => '--NO VALIDATIONS--',
                  'req' => 'Required',
                  'alpha' => 'Alpha Characters', 
                  'alpha_s' => 'Alpha Characters with Space', 
                  'num' => 'Numeric Characters', 
                  'alnum' => 'Alpha-Numeric Characters', 
                  'alnum_s' => 'Alpha-Numeric Characters with Space', 
                  'zip' => 'Zip',
                  'email' => 'Email',
                  'phone' => 'Phone'
               );

?>
