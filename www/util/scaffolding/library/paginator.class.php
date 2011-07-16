<?php
/**
 * Paginator.php
 *
 * Project    : 
 * Coded By   : Syed Sardar Ahmed
 * Started On : Thu, 14 Sep 2006
 *
 * Class for paginating the listings
 *
 */


  class Paginator
  {
    var $_strQuery;
    var $_intTotalRows;
    var $_intCurrentPage;
    var $_intTotalPages;
    var $_intRowsPerPage;
    var $_strPageName;
    var $_strStyleText = 'yellow-n';

    function Paginator($strQuery, $intMaxRows, $strCountKey = '*', $strPageHolder = 'pg')
    {
      global $_GET, $_POST;

      $this->_strQuery = $strQuery;
      $this->_strPageName = $strPageHolder;

      if (isset($_GET[$strPageHolder])) {
        $page = $_GET[$strPageHolder];
      } elseif (isset($_POST[$strPageHolder])) {
        $page = $_POST[$strPageHolder];
      } else {
        $page = '';
      }

      if (empty($page) || !is_numeric($page)) $page = 1;
      $this->_intCurrentPage = $page;

      $this->_intRowsPerPage = $intMaxRows;
      $queryLwr = strtolower($this->_strQuery);

      $union_pos = strpos($queryLwr, 'union', 0);

      if($union_pos)
        $posTo = $union_pos;
      else
        $posTo = strlen($this->_strQuery);
        
      $posFrom = strpos($queryLwr, ' from', 0);
      $posGroupBy = strpos($queryLwr, ' group by', $posFrom);
      if (($posGroupBy < $posTo) && ($posGroupBy != false)) $posTo = $posGroupBy;

      $posHaving = strpos($queryLwr, ' having', $posFrom);
      if (($posHaving < $posTo) && ($posHaving != false)) $posTo = $posHaving;

      $posOrderBy = strpos($queryLwr, ' order by', $posFrom);
      if (($posOrderBy < $posTo) && ($posOrderBy != false)) $posTo = $posOrderBy;

      if (strpos($queryLwr, 'distinct') || strpos($queryLwr, 'group by')) {
        $strCount = 'distinct ' . $strCountKey;
      } else {
        $strCount = $strCountKey;
      }

      $qry = mysql_query("select count(" . $strCount . ") as total " . substr($queryLwr, $posFrom, ($posTo - $posFrom)));
      $obj = mysql_fetch_object($qry);
      $count = $obj->total;

      if($posLimit =  strpos($queryLwr, ' limit'))
      {
        $this->_strQuery = substr($this->_strQuery, 0, $posLimit);
        $this->_intTotalRows = substr($strQuery, $posLimit + 7);
      }
      else
      {
        $this->_intTotalRows = $count;
      }
      
      if($this->_intTotalRows > $count)
        $this->_intTotalRows = $count;

      if (($posOrderBy < $posTo) && ($posOrderBy != false)) $posTo = $posOrderBy;

      if($union_pos)
      {
        $posTo = strlen($this->_strQuery);
        $posFrom = strpos($queryLwr, ' from', $union_pos);
        $posGroupBy = strpos($queryLwr, ' group by', $posFrom);
        if (($posGroupBy < $posTo) && ($posGroupBy != false)) $posTo = $posGroupBy;

        $posHaving = strpos($queryLwr, ' having', $posFrom);
        if (($posHaving < $posTo) && ($posHaving != false)) $posTo = $posHaving;

        $posOrderBy = strpos($queryLwr, ' order by', $posFrom);
        if (($posOrderBy < $posTo) && ($posOrderBy != false)) $posTo = $posOrderBy;

        if (strpos($queryLwr, 'distinct') || strpos($queryLwr, 'group by')) {
          $strCount = 'distinct ' . $strCountKey;
        } else {
          $strCount = $strCountKey;
        }

        $qry = mysql_query("select count(" . $strCount . ") as total2 " . substr($queryLwr, $posFrom, ($posTo - $posFrom)));
        $count2 = mysql_num_rows($qry);
        
        $this->_intTotalRows += $count2->total2;
      }

//      if($this->_intRowsPerPage >= $this->_intTotalRows)
//        $this->_intRowsPerPage = $this->_intTotalRows;
        
      $this->_intTotalPages = ceil($this->_intTotalRows / $this->_intRowsPerPage);

      if ($this->_intCurrentPage > $this->_intTotalPages) {
        $this->_intCurrentPage = $this->_intTotalPages;
      }

      $offset = ($this->_intRowsPerPage * ($this->_intCurrentPage - 1));
      
      if($offset < 0) $offset = 0;

      $this->_strQuery .= " limit " . $offset . ", " . $this->_intRowsPerPage;
    }

// split-page-number-links for display
    function getLinks($maxPageLinks, $parameters = '') {
      global $PHP_SELF, $request_type;

      $strLinks = '';

      if($this->_intTotalRows <= 0)
        return '&nbsp;';

      $class = 'class="' . $this->_strStyleText . '"';
      if (trim($parameters) == '' || (substr($parameters, -1) != '&')) $parameters .= '&';

// previous button - not displayed on first page

      if ($this->_intCurrentPage > 1)
        $strLinks .= '<a href="' . basename($PHP_SELF) . '?' . $parameters . $this->_strPageName . '=' . ($this->_intCurrentPage - 1) . '">&laquo;&nbsp;Prev</a>';
      else
        $strLinks .= '<span class="' . $this->_strStyleText . '">&laquo;Prev</span>&nbsp;';

// check if number_of_pages > $maxPageLinks
      $curWindowNum = intval($this->_intCurrentPage / $maxPageLinks);
      if ($this->_intCurrentPage % $maxPageLinks) $curWindowNum++;

      $maxWindowNum = intval($this->_intTotalPages / $maxPageLinks);
      if ($this->_intTotalPages % $maxPageLinks) $maxWindowNum++;

// previous window of pages
      if ($curWindowNum > 1)
        $strLinks .= '<a href="' . (basename($PHP_SELF) . '?' . $parameters . $this->_strPageName . '=' . (($curWindowNum - 1) * $maxPageLinks) .  '">...</a>');

// page nn button
      for ($jumpToPage = 1 + (($curWindowNum - 1) * $maxPageLinks); ($jumpToPage <= ($curWindowNum * $maxPageLinks)) && ($jumpToPage <= $this->_intTotalPages); $jumpToPage++) {
        if ($jumpToPage == $this->_intCurrentPage) {
          $strLinks .= '&nbsp;<span class="' . $this->_strStyleText . '">' . $jumpToPage . '</span>&nbsp;';
        } else {
          $strLinks .= '&nbsp;' . '<a href="' . (basename($PHP_SELF) . '?' . $parameters . $this->_strPageName . '=' . $jumpToPage .'">'.  $jumpToPage) . '</a>&nbsp;';
        }
      }

// next window of pages
      if ($curWindowNum < $maxWindowNum)
        $strLinks .= '<a href="' . (basename($PHP_SELF) . '?' . $parameters . $this->_strPageName . '=' . (($curWindowNum) * $maxPageLinks + 1) . '">...</a>' );
// next button
      if (($this->_intCurrentPage < $this->_intTotalPages) && ($this->_intTotalPages != 1))
        $strLinks .= '&nbsp' . '<a href="' . (basename($PHP_SELF) . '?' . $parameters . $this->_strPageName . '=' . ($this->_intCurrentPage + 1) . '">Next&nbsp;&raquo;</a>');
      else
        $strLinks .= '&nbsp;<span class="' . $this->_strStyleText . '">Next&raquo;</span>';
        
      return $strLinks;
    }

//  number of total products found
// must contain format-specifier place holders for start-num, end-num, total-num
    function getCount($textOutput) {
      $toNum = ($this->_intRowsPerPage * $this->_intCurrentPage);
      if ($toNum > $this->_intTotalRows) $toNum = $this->_intTotalRows;

      $fromNum = ($this->_intRowsPerPage * ($this->_intCurrentPage - 1));

      if ($toNum == 0) {
        $fromNum = 0;
      } else {
        $fromNum++;
      }

      return sprintf($textOutput, $fromNum, $toNum, $this->_intTotalRows);
    }

    function getQuery()
    {
        return $this->_strQuery;
    }
  }
?>
