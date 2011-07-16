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

class Scaffold extends Base
{
  var $fields = array();
  var $list_fields = array();         //fields to be available in listing
  var $view_fields = array();         //fields to be available in view
  var $edit_fields = array();         //fields to be available in editing      
  var $ss_fields = array();           //fields for server-side validation
  var $type = array();
  var $table;
  var $database;
  var $primary;
  var $dir;
  var $upload = 'upload';
  var $init = 'init.php';
  
  function Scaffold($settings)
  {
    $this->fields       = $settings['all_fields'];
    $this->list_fields  = $settings['list_fields'];
    $this->view_fields  = $settings['view_fields'];
    $this->edit_fields  = $settings['edit_fields'];
    $this->ss_fields    = $settings['ss_fields'];
    $this->table        = $settings['table'];
    $this->database     = $settings['database'];
    $this->primary      = $settings['primary'];
    $this->dir          = $settings['table'];
    $this->type         = array_unique($settings['all_fields']);
    
    if(!is_dir($this->table))
      mkdir($this->table);

    if(!is_dir($this->table . '/templates/'))
      mkdir($this->table . '/templates/');

    if(!is_dir($this->table . '/' . $this->upload . '/'))
      mkdir($this->table . '/' . $this->upload . '/');
  }
  
  function generateCode()
  {
    $filename = $this->table . '/' . $this->table . '.php';
    $fp = fopen($filename, 'w');

    $this->generateInit();
    fwrite($fp, $this->generateHeader());
    fwrite($fp, $this->generateView());
    fwrite($fp, $this->generateEdit());
    fwrite($fp, $this->generateDelete());
    fwrite($fp, $this->generateUpdate());
    fwrite($fp, $this->generateAdd());
    fwrite($fp, $this->generateInsert());
    fwrite($fp, $this->generateListing());
    fwrite($fp, $this->generateFooter());
  }
  
  function generateInit()
  {
    $filename = $this->table . '/' . $this->init;
    $fp = fopen($filename, 'w');

    $out = '<?php' . "\n";
    $out .= '  session_start();' . "\n";
    $out .= "  DEFINE('HOST', '" . HOST . "');\n";
    $out .= "  DEFINE('USER', '" . USER . "');\n";
    $out .= "  DEFINE('PSWD', '" . PSWD . "');\n";
    $out .= "  mysql_connect(HOST, USER, PSWD) or die('Error: ' . mysql_error());\n";
    $out .= "  mysql_select_db('" . $this->database . "') or die('Error: ' . mysql_error());\n";
    $out .= '?>';
    
    fwrite($fp, $out);
  }  
  
  function generateHeader()
  {
    $out = '<?php' . "\n";
    $out .= 'include \'' . $this->init . '\';' . "\n";
    $out .= '$opt = isset($_REQUEST[\'option\']) ? $_REQUEST[\'option\'] : \'\';' . "\n";
    $out .= 'switch($opt)' . "\n";
    $out .= '{' . "\n";
    
    return $out;
  }
  
  function generateView()
  {
    $field = $this->generateFields($this->view_fields, $this->primary);

    $out = 'case \'view\':' . "\n";
    $out .= '$id = isset($_REQUEST[\'' . $this->primary . '\']) ? $_REQUEST[\'' . $this->primary . '\'] : \'\';' . "\n";
    $out .= '$sqlV = \'SELECT ' . $field . ' FROM ' . $this->table . ' WHERE ' . $this->primary . '="\'' . '.$id.' . '\'"\';' . "\n";
    $out .= '$qryV = mysql_query($sqlV) or die(\'Error: \' . mysql_error());' . "\n";
    $out .= '$qryVResult = mysql_fetch_assoc($qryV) or die(\'Error: \' . mysql_error());' . "\n";
    $out .= 'include \'templates/' . $this->table . '_view.php' . '\';' . "\n";
    $out .= 'break;' . "\n";
    
    $this->generateViewHTML();
    return $out;
  }
  
  function generateViewHTML()
  {
    $filename = $this->table . '/templates/' . $this->table . '_view.php';
    $fp = fopen($filename, 'w');
    
    $out = '<table>' . "\n";

    foreach($this->view_fields as $key => $value)
    {
      $out .= '<tr>' . "\n";
      $out .= '<td>' . $key . '</td>' . "\n";
      $out .= '<td>' . '<?=$qryVResult[\'' . $key . '\']?>' . '</td>' . "\n";
      $out .= '</tr>' . "\n";
    }

    $out .= '</table>';
    fwrite($fp, $out);    
  }
  
  function generateEdit()
  {
    $field = $this->generateFields($this->edit_fields, $this->primary);

    $out = 'case \'edit\':' . "\n";
    $out .= '$msg = isset($msg) ? $msg : \'\';' . "\n";
    $out .= '$id = isset($_REQUEST[\'' . $this->primary . '\']) ? $_REQUEST[\'' . $this->primary . '\'] : \'\';' . "\n";
    $out .= '$sqlE = \'SELECT ' . $field . ' FROM ' . $this->table . ' WHERE ' . $this->primary . '="\'' . '.$id.' . '\'"\';' . "\n";
    $out .= '$qryE = mysql_query($sqlE) or die(\'Error: \' . mysql_error());' . "\n";
    $out .= '$qryEResult = mysql_fetch_assoc($qryE) or die(\'Error: \' . mysql_error());' . "\n";
    $out .= '@extract($qryEResult);' . "\n";
     
    $out .= 'include \'templates/' . $this->table . '_edit.php' . '\';' . "\n";
    $out .= 'break;' . "\n";
    
    $this->generateEditHTML();
    return $out;
  }
  
  function generateEditHTML()
  {
    $filename = $this->table . '/templates/' . $this->table . '_edit.php';
    $fp = fopen($filename, 'w');
    
    
    $out = '<form name="frmUpdate" id="frmUpdate" action="" method="post" enctype="multipart/form-data">' . "\n";
    $out .= '<table>' . "\n";
    $out .= '<tr>' . "\n";
    $out .= '<td colspan="2">'. '<?php echo $msg; ?>' . '</td>' . "\n";
    $out .= '</tr>' . "\n";

    foreach($this->edit_fields as $key => $value)
    {
      $out .= '<tr>' . "\n";
      $out .= '<td>' . $key . '</td>' . "\n";
      
      if($value == 'text')
        $out .= '<td>' . '<input type="text" name="'.$key.'" value="' . '<?=$'.$key.'?>' . '" />' . '</td>' . "\n";
      else if($value == 'textarea')
        $out .= '<td>' . '<textarea name="'.$key.'">' . '<?=$'.$key.'?>' . '</textarea>' . '</td>' . "\n";
      else if($value == 'file')
      {
        $out .= '<?php' . "\n";
        $out .= 'if(isset($qryEResult[\''.$key.'\']))' . "\n";
        $out .= '{' . "\n";
        $out .= '?>' . "\n";
        $out .= '<td>' . '<input type="file" name="'.$key.'" />' . '<?php if($qryEResult[\''.$key.'\'] != \'\' && $qryEResult[\''.$key.'\'] != NULL) ?>' . '<a href="' . $this->upload . '/<?=$qryEResult[\''.$key.'\']?>"><?=$qryEResult[\''.$key.'\']?></a></td>' . "\n";
        $out .= '<?php' . "\n";
        $out .= '}' . "\n";
        $out .= 'else' . "\n";
        $out .= '{' . "\n";
        $out .= '?>' . "\n";
        $out .= '<td>' . '<input type="file" name="'.$key.'" /></td>' . "\n";
        $out .= '<?php' . "\n";
        $out .= '}' . "\n";
        $out .= '?>' . "\n";
      }
      $out .= '</tr>' . "\n";
    }
    $out .= '<tr>' . "\n";
    $out .= '<td>' . '<input type="hidden" name="option" value="update">' . '</td>' . "\n";
    $out .= '<td>' . '<input type="submit" name="btnUpdate" value="Update" />&nbsp;<input type="reset" value="Reset">' . '</td>' . "\n";
    $out .= '</tr>' . "\n";
    $out .= '</table>';
    $out .= '</form>';
    fwrite($fp, $out);    
  }

  function generateDelete()
  {
    $out = 'case \'delete\':' . "\n";
    $out .= '$id = isset($_REQUEST[\'' . $this->primary . '\']) ? $_REQUEST[\'' . $this->primary . '\'] : \'\';' . "\n";
    $out .= '$sqlD = \'DELETE FROM ' . $this->table . ' WHERE ' . $this->primary . '="\'' . '.$id.' . '\'"\';' . "\n";
    $out .= '$qryD = mysql_query($sqlD) or die(\'Error: \' . mysql_error());' . "\n";
    $out .= 'if($qryD)' . "\n";
    $out .= '{' . "\n";
    $out .= '$_SESSION[\'msg\'] = \'Record Deleted Successfully!\';' . "\n";
    $out .= '}' . "\n";
    $out .= 'else' . "\n";
    $out .= '{' . "\n";
    $out .= '$_SESSION[\'msg\'] = \'Error in deleting record!\';' . "\n";
    $out .= '}' . "\n";
    $out .= 'header(\'Location: ' . $this->table . '.php' . '\');' . "\n";
    $out .= 'exit;' . "\n";
    $out .= 'break;' . "\n";
    
    return $out;
  }

  function generateListing()
  {
    $field = $this->generateFields($this->list_fields, $this->primary);
    
    $out = 'default:' . "\n";
    $out .= 'if(isset($_SESSION[\'msg\'])) {' . "\n";
    $out .= '$msg = $_SESSION[\'msg\'];' . "\n";
    $out .= 'unset($_SESSION[\'msg\']);';
    $out .= '}' . "\n";
    $out .= 'include \'../library/paginator.class.php\';' . "\n";
    $out .= '$sqlL = \'SELECT ' . $field . ' FROM ' . $this->table . '\';' . "\n";

		$out .= '$pag = new Paginator($sqlL, 10);' . "\n";
		$out .= '$link1 = $pag->getCount(\'Item %d of %d - %d\');' . "\n";
		$out .= '$link2 = $pag->getLinks(5);' . "\n";
		$out .= '$tempSql = $pag->getQuery();' . "\n";

    $out .= '$qryL = mysql_query($tempSql) or die(\'Error: \' . mysql_error());' . "\n";
    $out .= '$result = array();' . "\n";
    $out .= 'while($qryLResult = mysql_fetch_assoc($qryL))' . "\n";
    $out .= '{' . "\n";
    $out .= '$result[] = $qryLResult;' . "\n";
    $out .= '}' . "\n";
    $out .= 'include \'templates/' . $this->table . '_listing.php' . '\';' . "\n";
    $out .= 'break;' . "\n";
    
    $this->generateListingHTML();
    return $out;
  }
  
  function generateListingHTML()
  {
    $filename = $this->table . '/templates/' . $this->table . '_listing.php';
    $fp = fopen($filename, 'w');

    $out = '<?php' . "\n";
    $out .= 'if(isset($msg)) {' . "\n";
    $out .= 'echo $msg;' . "\n";
    $out .= '}' . "\n";
    $out .= '?>' . "\n";
    $out .= '<a href="' . $this->table . '.php?option=add">Add Record</a><br />' . "\n";
    $out .= "<table>\n";
    $out .= "<tr>\n";
    
    $i = 0;
    foreach($this->list_fields as $key => $value)
    {
      $out .= "<td>";
      $out .= $key;
      $out .= "</td>\n";
      
      if($i++ == 0)
        $fields = $key;
      else
        $fields .= ', ' . $key;
    }
    $out .= "<td>";
    $out .= "Action";
    $out .= "</td>\n";
    $out .= "</tr>\n";

    $out .= '<?php' . "\n";
    $out .= 'foreach($result as $key => $value)';
    $out .= '{';
    $out .= '?>' . "\n";
    $out .= '<tr>' . "\n";
    foreach($this->list_fields as $k => $v)
    {
      $out .= '<td>' . '<?=$result[' . '$key' . '][\'' . $k . '\']?>' . '</td>' . "\n";
    }
    $out .= "<td>";
    $out .= '<a href="' . $this->table . '.php?option=view&' . $this->primary . '=<?=$result[$key][\'' . $this->primary . '\']?>' . '">View</a>';
    $out .= '&nbsp;|&nbsp;<a href="' . $this->table . '.php?option=edit&' . $this->primary . '=<?=$result[$key][\'' . $this->primary . '\']?>' . '">Edit</a>';
    $out .= '&nbsp;|&nbsp;<a href="' . $this->table . '.php?option=delete&' . $this->primary . '=<?=$result[$key][\'' . $this->primary . '\']?>' . '" onclick="return confirm(\'Are you sure you want to delete this record?\');">Delete</a>';
    $out .= "</td>\n";
    $out .= '</tr>' . "\n";
    $out .= '<?php' . "\n";
    $out .= '}';
    $out .= '?>' . "\n";

    $out .= '</table><br />' . "\n";
    $out .= '<?php echo $link1 . \' \' . $link2; ?>' . "\n";
    fwrite($fp, $out);    
  }
  
  function generateUpdate()
  {
    $out = 'case \'update\':' . "\n";
    $out .= '$msg = isset($msg) ? $msg : \'\';' . "\n";
    $out .= 'include \'../library/formvalidator.php\';' . "\n";
    $out .= '$id = isset($_REQUEST[\'' . $this->primary . '\']) ? $_REQUEST[\'' . $this->primary . '\'] : \'\';' . "\n";

    $out .= $this->ssValidation($this->ss_fields);

    if(count($this->ss_fields) > 0)
    {
      $out .= 'if($validator->ValidateForm())' . "\n";
      $out .= '{' . "\n";
    }

    $i = 0;
    $outS = '';
    foreach($this->edit_fields as $key => $value)
    {
      if($value != 'file')
      {
        $out .= '$' . $key . ' = isset($_REQUEST[\'' . $key . '\']) ? addslashes($_REQUEST[\'' . $key . '\']) : \'\';' . "\n";

        if($i == 0)
          $outS .= $key . '= \'$' . $key . '\'';
        else
          $outS .= ', ' . $key . '= \'$' . $key . '\'';
      }
      else
      {
        $out .= 'if(isset($_FILES[\'' . $key . '\'][\'name\']) && $_FILES[\'' . $key . '\'][\'name\'] != \'\')' . "\n";
        $out .= '{' . "\n";
        $out .= '$' . $key . ' = time() . \'_\' . $_FILES[\'' . $key . '\'][\'name\'];' . "\n";
        $out .= '$desc = \'' . $this->upload . '/\'.' . '$' . $key . ";\n";
        $out .= 'move_uploaded_file($_FILES[\'' . $key . '\'][\'tmp_name\'], $desc);' . "\n";
        $out .= '}' . "\n"; 

        if($i == 0)
          $outS .= $key . '= \'$' . $key . '\'';
        else
          $outS .= ', ' . $key . '= \'$' . $key . '\'';
      }
      $i++;
    }

    $out .= '$sqlU = "UPDATE ' . $this->table . ' SET ';
    $out .= $outS;
    $out .= ' WHERE ' . $this->primary . '= \'$id\'";' . "\n";
    $out .= '$qryU = mysql_query($sqlU) or die(\'Error: \' . mysql_error());' . "\n";
    $out .= 'if($qryU)' . "\n";
    $out .= '{' . "\n";
    $out .= '$_SESSION[\'msg\'] = \'Record Updated Successfully!\';' . "\n";
    $out .= '}' . "\n";
    $out .= 'else' . "\n";
    $out .= '{' . "\n";
    $out .= '$_SESSION[\'msg\'] = \'Error in updating record!\';' . "\n";
    $out .= '}' . "\n";
    $out .= 'header(\'Location: ' . $this->table . '.php' . '\');' . "\n";
    $out .= 'exit;' . "\n";

    if(count($this->ss_fields) > 0)
    {
      $out .= '}' . "\n";
      $out .= 'else' . "\n";
      $out .= '{' . "\n";
      $out .= '$error_hash = $validator->GetErrors();' . "\n";
      $out .= 'foreach($error_hash as $inpname => $inp_err)' . "\n";
      $out .= '{' . "\n";
      $out .= '$msg =  "$inp_err";' . "\n";
      $out .= 'break;' . "\n";  //if in case we want to view only one error, at a time
      $out .= '}' . "\n";        
      $out .= '@extract($_REQUEST);' . "\n";
      $out .= '}' . "\n";
      $out .= 'include \'templates/' . $this->table . '_edit.php' . '\';' . "\n";
    }

    $out .= 'break;' . "\n";
    return $out;
  }
  
  function generateAdd()
  {
    $out = 'case \'add\':' . "\n";
    $out .= '$msg = isset($msg) ? $msg : \'\';' . "\n";
    $out .= 'include \'templates/' . $this->table . '_add.php' . '\';' . "\n";
    $out .= 'break;' . "\n";
    
    $this->generateAddHTML();
    return $out;
  }
  
  function generateAddHTML()
  {
    $filename = $this->table . '/templates/' . $this->table . '_add.php';
    $fp = fopen($filename, 'w');
    
    
    $out = '<form name="frmAdd" id="frmAdd" action="" method="post" enctype="multipart/form-data">' . "\n";
    $out .= '<table>' . "\n";
    $out .= '<tr>' . "\n";
    $out .= '<td colspan="2">'. '<?php echo $msg; ?>' . '</td>' . "\n";
    $out .= '</tr>' . "\n";

    foreach($this->edit_fields as $key => $value)
    {
      $out .= '<tr>' . "\n";
      $out .= '<td>' . $key . '</td>' . "\n";
      
      if($value == 'text')
        $out .= '<td>' . '<input type="text" name="'.$key.'" value="<?php echo isset($_REQUEST["'.$key.'"]) ? $_REQUEST["'.$key.'"] : \'\'; ?>" />' . '</td>' . "\n";
      else if($value == 'textarea')
        $out .= '<td>' . '<textarea name="'.$key.'"><?php echo isset($_REQUEST["'.$key.'"]) ? $_REQUEST["'.$key.'"] : \'\'; ?></textarea>' . '</td>' . "\n";
      else if($value == 'file')
        $out .= '<td>' . '<input type="file" name="'.$key.'" />' . "\n";
      
      $out .= '</tr>' . "\n";
    }
    $out .= '<tr>' . "\n";
    $out .= '<td>' . '<input type="hidden" name="option" value="insert">' . '</td>' . "\n";
    $out .= '<td>' . '<input type="submit" name="btnAdd" value="Add" />&nbsp;<input type="reset" value="Reset">' . '</td>' . "\n";
    $out .= '</tr>' . "\n";
    $out .= '</table>';
    $out .= '</form>';
    fwrite($fp, $out);    
  }
  
  function generateInsert()
  {
    $out = 'case \'insert\':' . "\n";
    $out .= '$msg = isset($msg) ? $msg : \'\';' . "\n";
    $out .= 'include \'../library/formvalidator.php\';' . "\n";
    $out .= $this->ssValidation($this->ss_fields);

    if(count($this->ss_fields) > 0)
    {
      $out .= 'if($validator->ValidateForm())' . "\n";
      $out .= '{' . "\n";
    }

    $i = 0;
    foreach($this->edit_fields as $key => $value)
    {
      if($value != 'file')
      {
        $out .= '$' . $key . ' = isset($_REQUEST[\'' . $key . '\']) ? addslashes($_REQUEST[\'' . $key . '\']) : \'\';' . "\n";
      }
      else
      {
        $out .= 'if(isset($_FILES[\'' . $key . '\'][\'name\']) && $_FILES[\'' . $key . '\'][\'name\'] != \'\')' . "\n";
        $out .= '{' . "\n";
        $out .= '$' . $key . ' = time() . \'_\' . $_FILES[\'' . $key . '\'][\'name\'];' . "\n";
        $out .= '$desc = \'' . $this->upload . '/\'.' . '$' . $key . ";\n";
        $out .= 'move_uploaded_file($_FILES[\'' . $key . '\'][\'tmp_name\'], $desc);' . "\n";
        $out .= '}' . "\n"; 
      }
      $i++;
    }

    $out .= '$sqlI = "INSERT INTO ' . $this->table . ' (';
    $i = 0;
    foreach($this->edit_fields as $key => $value)
    {
      if($i == 0)
        $out .= $key;
      else
        $out .= ', ' . $key;
      $i++;
    }
    $out .= ') VALUES (';
    
    $i = 0;
    foreach($this->edit_fields as $key => $value)
    {
      if($i == 0)
        $out .= '\'$' . $key . '\'';
      else
        $out .= ', \'$' . $key . '\'';
      $i++;
    }
    $out .= ')";' . "\n";

    $out .= '$qryI = mysql_query($sqlI) or die(\'Error: \' . mysql_error());' . "\n";
    $out .= 'if($qryI)' . "\n";
    $out .= '{' . "\n";
    $out .= '$_SESSION[\'msg\'] = \'Record Added Successfully!\';' . "\n";
    $out .= '}' . "\n";
    $out .= 'else' . "\n";
    $out .= '{' . "\n";
    $out .= '$_SESSION[\'msg\'] = \'Error in adding record!\';' . "\n";
    $out .= '}' . "\n";
    $out .= 'header(\'Location: ' . $this->table . '.php' . '\');' . "\n";
    $out .= 'exit;' . "\n";

    if(count($this->ss_fields) > 0)
    {
      $out .= '}' . "\n";
      $out .= 'else' . "\n";
      $out .= '{' . "\n";
      $out .= '$error_hash = $validator->GetErrors();' . "\n";
      $out .= 'foreach($error_hash as $inpname => $inp_err)' . "\n";
      $out .= '{' . "\n";
      $out .= '$msg =  "$inp_err";' . "\n";
      $out .= 'break;' . "\n";  //if in case we want to view only one error, at a time
      $out .= '}' . "\n";        
      $out .= '}' . "\n";
      $out .= 'include \'templates/' . $this->table . '_add.php' . '\';' . "\n";
    }

    $out .= 'break;' . "\n";
    
    return $out;
  }
  
  function generateFooter()
  {
    $out = '}' . "\n";
    $out .= '?>' . "\n";
    
    return $out;
  }
}

?>
