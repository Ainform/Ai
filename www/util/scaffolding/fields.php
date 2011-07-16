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
if(!isset($_SESSION['table']))
{
  header('Location: index.php');
  exit;
}

$ss_fields = array();
if(isset($_POST['btnSubmit']))
{
  if(isset($_POST['primary']) && $_POST['primary'] != '')
  {
    $all_fields = array();
    foreach($_SESSION['field'] as $fields)
    {
      if(isset($_POST[$fields]) && $_POST[$fields] != "0")
        $all_fields[$fields] = $_POST[$fields];
        
      if(isset($_POST['list_' . $fields]))
        $list_fields[$fields] = $_POST[$fields];

      if(isset($_POST['view_' . $fields]))
        $view_fields[$fields] = $_POST[$fields];

      if(isset($_POST['edit_' . $fields]))
        $edit_fields[$fields] = $_POST[$fields];

      if(isset($_POST['ss_' . $fields]) && $_POST['ss_' . $fields] != "0")
        $ss_fields[$fields] = $_POST['ss_' . $fields];
    }
//print_r($ss_fields); exit;
    include 'includes/base.class.php';
    include 'includes/scaffold.class.php';
    
    $settings = array(
                       'database'     => $_SESSION['database'],
                       'table'        => $_SESSION['table'],
                       'all_fields'   => $all_fields,
                       'list_fields'  => $list_fields,
                       'view_fields'  => $view_fields,
                       'edit_fields'  => $edit_fields,
                       'ss_fields'    => $ss_fields,
                       'primary'      => $_POST['primary']
                     );

    $scaffold = new Scaffold($settings);
    $scaffold->generateCode();
    header('Location: ' . $_SESSION['table'] . '/' . $_SESSION['table'] . '.php');
    exit;
  }
  else
  {
    $msg = 'Table doesn\'t contain any primary key!';
  }
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
  <meta name="generator" content="PSPad editor, www.pspad.com">
  <title></title>
  <script type="text/javascript">
    function checkField(field)
    {
      var name = field.name;
      var val  = field.value;
      
      if(val == 0)
      {
        document.getElementById('list_' + name).disabled = true;
        document.getElementById('view_' + name).disabled = true;
        document.getElementById('edit_' + name).disabled = true;
        document.getElementById('ss_' + name).disabled = true;

        document.getElementById('tr_' + name).style.background = "#cdcdcd";
      }
      else
      {
        document.getElementById('list_' + name).disabled = false;
        document.getElementById('view_' + name).disabled = false;
        document.getElementById('edit_' + name).disabled = false;
        document.getElementById('ss_' + name).disabled = false;

        document.getElementById('tr_' + name).style.background = "";
      }
    }
  </script>
  </head>
  <body>
    <?=$msg?>
    <form name="frmFIELDS" action="" method="post">
      <table border="1px">
        <tr>
          <td align="center" colspan="6"><h1>TABLE FIELD TYPES</h1></td>
        </tr>
        <tr>
          <td align="center"><strong>Field</strong></td>
          <td align="center"><strong>Type</strong></td>
          <td align="center"><strong>List</strong></td>
          <td align="center"><strong>View</strong></td>
          <td align="center"><strong>Edit/Add</strong></td>
          <td align="center"><strong>Validations</strong></td>
        </tr>
        <?php
          $fields = array();
          $sqlD = 'show columns from ' . $_SESSION['table'];
          $qryD = mysql_query($sqlD) or die('Error: ' . mysql_error());
          while($qryDResult = mysql_fetch_array($qryD))
          {
            $fields[] = $qryDResult[0];
            
            if($qryDResult['Key'] == 'PRI')
            {
        ?>
              <input type="hidden" name="primary" value="<?=$qryDResult[0]?>" />
              <tr>
                <td><?=$qryDResult[0]?></td>
                <td>
                  <strong>PRIMARY KEY</strong>
                </td>
              </tr>
        <?php
            }
            else
            {
        ?>
            <tr id="tr_<?=$qryDResult[0]?>">
              <td><?=$qryDResult[0]?></td>
              <td>
                <select name="<?=$qryDResult[0]?>" onchange="return checkField(this);">
                  
                  <?php foreach($type_arr as $key => $value): ?>
                  <option value="<?=$key?>"><?=$value?></option>
                  <?php endforeach; ?>
                </select>
              </td>
              <td>
                <input type="checkbox" name="list_<?=$qryDResult[0]?>" id="list_<?=$qryDResult[0]?>" checked/>
              </td>
              <td>
                <input type="checkbox" name="view_<?=$qryDResult[0]?>" id="view_<?=$qryDResult[0]?>" checked/>
              </td>
              <td>
                <input type="checkbox" name="edit_<?=$qryDResult[0]?>" id="edit_<?=$qryDResult[0]?>" checked/>
              </td>
              <td>
                <select name="ss_<?=$qryDResult[0]?>" id="ss_<?=$qryDResult[0]?>">
                  
                  <?php foreach($ss_arr as $key => $value): ?>
                  <option value="<?=$key?>"><?=$value?></option>
                  <?php endforeach; ?>
                </select>
              </td>
            </tr>
        <?php
            }
            $_SESSION['field'] = $fields;
          }
        ?>
        <tr>
          <td colspan="6"><br /><input type="submit" name="btnSubmit" value="NEXT>>>"></td>
        </tr>
      </table>
    </form>
  </body>
</html>
