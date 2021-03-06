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

include 'includes/config.php';

$msg = '';
if(!isset($_SESSION['database']))
{
  header('Location: index.php');
  exit;
}

if(isset($_POST['btnSubmit']))
{
  $table = isset($_POST['table']) ? $_POST['table'] : '';
  
  if($table != '')
  {
    $_SESSION['table'] = $table;
    header('Location: fields.php');
    exit;
  }
  else
  {
    $msg = 'Invalid Table!';
  }
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
  <meta name="generator" content="PSPad editor, www.pspad.com">
  <title></title>
  </head>
  <body>
    <?=$msg?>
    <form name="frmTABLE" action="" method="post">
      <table>
        <tr>
          <td colspan="2"><h1>SELECT TABLE</h1></td>
        </tr>
        <?php
          $sqlD = 'show tables';
          $qryD = mysql_query($sqlD) or die('Error: ' . mysql_error());
          while($qryDResult = mysql_fetch_array($qryD))
          {
        ?>
            <tr>
              <td><input type="radio" name="table" value="<?=$qryDResult[0]?>" checked /></td>
              <td><?=$qryDResult[0]?></td>
            </tr>
        <?php
          }
        ?>
        <tr>
          <td colspan="2"><input type="submit" name="btnSubmit" value="NEXT>>>"></td>
        </tr>
      </table>
    </form>
  </body>
</html>
