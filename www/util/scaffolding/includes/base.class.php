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

  class Base
  {
    function generateFields($fields = NULL, $primary = NULL)
    {
      $i = 0;
      $field = '';
      if($fields == NULL)
      {
        return ' * ';
      }
      else
      {
        foreach($fields as $key => $value)
        {
          if($i++ == 0)
            $field .= $key;
          else
            $field .= ', ' . $key;
        }
        if($primary != NULL)
          $field .= ', ' . $primary;
        
        return $field;
      }
    }
    
    function ssValidation($ss_fields = NULL)
    {
      if($ss_fields == NULL)
      {
        return '';
      }
      else
      {
        $out = '$validator = new FormValidator();' . "\n";
        foreach($ss_fields as $key => $value)
        {
          $out .= '$validator->addValidation("' . $key . '", "req", "Please enter ' . $key . '");' . "\n";
          
          if($value != 'req')
            $out .= '$validator->addValidation("' . $key . '", "' . $value . '", "Invalid ' . $key . '");' . "\n";
        }
        return $out;
      }
    }
  }
  
?>
