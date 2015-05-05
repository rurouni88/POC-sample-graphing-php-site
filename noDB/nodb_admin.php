<?php
/*
NoDB File-Based Simple Database System for PHP
- NoDB Administration Page -

Version 0.4

Copyright (C) 2010 Sebastian Lukas

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses/>.

---

All used icons in this file are by Mark James.
All used icons are under Creative Commons Attribution 2.5 License.
http://www.famfamfam.com/lab/icons/silk/

*/

// SET PASSWORD TO ENTER HERE:

$password = "secret";

session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>NoDB Administrator Page</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link rel="shortcut icon" href="img/table_lightning.ico">
    <style type="text/css">
      body, table, input, textarea, select {
        font-family: verdana;
        font-size: 12px;
      }
      table {
        border-collapse: collapse;
      }
      td, th {
        vertical-align: top;
        border: 1px solid #999;
      }
      img {
        border-width: 0px;
      }
      body {
        background: #fff url(img/nodb_logo_small.png) top right no-repeat;
      }
      a:link, a:active, a:visited {
        color: blue;
        text-decoration: underline;
      }
      a:hover {
        color: red;
      }
    </style>
  </head>
  <body>
    <?php
      if(!empty($password) && $password != $_POST['password'] && !$_SESSION['loggedin']) {
        echo "    <h1>NoDB Administrator Page</h1>
                  <form action=\"?\" method=\"post\">
                    <p>You need to enter the correct password to enter this page.</p>
                    <p><input type=\"password\" name=\"password\"></p>
                    <p><input type=\"submit\" value=\"Enter\"></p>
                  </form>
                </body>
              </html>";
        exit;
      }
      elseif(!empty($password)) {
        $_SESSION['loggedin'] = true;
      }
      include("nodb_functions.php");
      $e = $_GET['e'];
      switch ($e) {
        default:
          echo "<table border=0 cellpadding=5 cellspacing=0 style=\"width:100%; height: 100%;\">
                <tr>
                  <td colspan=3 style=\"border: 0;\">
                    <h1>NoDB Administrator Page</h1>
                  </td>
                </tr>
                <tr><td>";
          echo "<h2>Add database</h2>
                <form action=\"?e=add_db\" method=\"post\">
                  <table border=0 cellpadding=5 cellspacing=0>
                    <tr>
                      <td>Name:</td>
                      <td>
                        <input type=\"text\" name=\"db_name\">
                      </td>
                    </tr>
                    <tr>
                      <td>In Folder:</td>
                      <td>
                        <select name=\"folder\" onchange=\"if(this.value==''){this.form.newFolder.style.display='inline';}else{this.form.newFolder.style.display='none';}\">
                          <option value=\"no_database_selected\" selected>-- Select --</option>";
          $handle = opendir("db/");
          while($dir = readdir($handle)) {
            if(is_dir("db/" . $dir) && $dir != "." && $dir != "..") {
              echo "<option>$dir</option>";
            }
          }
          echo "          <option value=\"\">Create new...</option>
                        </select>
                        <input type=\"text\" name=\"newFolder\" style=\"display:none;\">
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td><input type=\"submit\" value=\"Add Database\"></td>
                    </tr>
                  </table>
                </form>";
          
          echo "</td><td>
                <h2>View databases</h2>
                <ul style=\"list-style-image: url(img/folder.png);\">";
          $handle = opendir("db/");
          while($dir = readdir($handle)) {
            if(is_dir("db/" . $dir) && $dir != "." && $dir != "..") {
              echo "<li><a href=\"?e=show_databases_in_folder&amp;folder=$dir\" target=\"folderFrame\">$dir</a></li>";
            }
          }
          echo "</ul>
                </td>
                <td>
                  <iframe src=\"?e=show_databases_in_folder\" style=\"width:100%; height:100%; border:0px;\" name=\"folderFrame\"></iframe>
                </td>
                </tr>
                <tr>
                  <td colspan=3>
                    <p>
                      <span style=\"font-weight: bold;\">Supported special characters:</span>";
          $characters = array(
            '!', '@', '#', '$', '%', '^', '*', '(', ')', '-', '_', '=', '+', '&copy;',
            '[', ']', '{', '}', '\\', '|', ';', ':', '\'', '"', ',', '.', '?',
            '&auml;', '&Auml;', '&ouml;', '&Ouml;', '&uuml;', '&Uuml;', '&szlig;',
            '&eacute;', '&egrave;', '&ecirc;', '&#235;', '&agrave;', '&aacute;', '&acirc;', '&#227;',
            '&ograve;', '&oacute;', '&ocirc;', '&#245;', '&ugrave;', '&uacute;', '&ucirc;',
            '&#178;', '&#179;', '`', '&#180;', '&#231;', '&#171;', '&#187;', '&#163;', '&#8364;',
          );
          foreach($characters as $character) {
            echo "<span style=\"display: inline; margin-left: 5px;\">$character</span>";
          }
          echo "</p>
                <p>
                  Also, the character '/' will always be replaced by '|',
                  and the character '&' will always be replaced by the word 'and'.
                </p>
                  </td>
                </tr>
                <tr>
                  <td colspan=3 style=\"font-size: 10px; border: 0;\">
                    V0.4 - By Sebastian Lukas - <a href=\"http://sebastianlukas.square7.net/\">sebastianlukas.square7.net</a>
                  </td>
                </tr>
                </table>";
        break;
        case "add_db":
          $db_name = $_POST['db_name'];
          $folder = $_POST['folder'];
          $newFolder = $_POST['newFolder'];
          
          if($folder = createNewDatabase($db_name, "db", $folder, $newFolder)) {
            echo "<p>The database has been created.</p>
                  <script type=\"text/javascript\">
                    window.setTimeout(\"document.location.href='?e=show_database&folder=$folder&db_file=$db_name.nodb'\", 1000);
                  </script>";
          }
        break;
        case "show_databases_in_folder":
          echo "<script type=\"text/javascript\">
                  document.getElementsByTagName('body')[0].style.background = 'none';
                </script>";
          $folder = $_GET['folder'];
          
          if(is_dir("db/$folder/") && $folder) {
            $handle = opendir("db/$folder/");
            echo "<h3>db/$folder/</h3>
                    <ul style=\"list-style-image: url(img/table.png);\">";
            $i = 0;
            while($db_file = readdir($handle)) {
              if(!is_dir("db/$folder/" . $db_file) && $db_file != "index.php") {
                echo "<li>
                        <a href=\"?e=delete_database&amp;folder=$folder&amp;db_file=$db_file\" target=\"_parent\" onclick=\"if(!confirm('Delete the database $db_file and all its contents?')) return false;\"><img src=\"img/cross.png\" alt=\"X\" title=\"Delete this database\"></a>
                        <a href=\"?e=show_database&amp;folder=$folder&amp;db_file=$db_file\" target=\"_parent\">$db_file</a>
                        
                      </li>";
                $i++;
              }
            }
            if(!$i) {
              echo "<li>There are no databases in this folder.</li>";
            }
            echo "</ul>";
          }
        break;
        case "show_database":
          $folder = $_GET['folder'];
          $db_file = $_GET['db_file'];
          echo "<h1>NoDB Administrator Page</h1>
                <a style=\"float: right;\" href=\"?\">back</a>
                <h3>View database: <i>db/$folder/$db_file</i></h3>
                <div style=\"width:100%; height:150px; overflow: auto; border-top: 1px dotted #000; border-bottom: 1px dotted #000;\">
                  <code>";
          $xml = readDatabase($db_file, $folder, "db", true);
          echo "  </code>
                </div>";
          
          displayDatabaseAsTable($db_file, $folder, "db", $_GET['orderby']);
          
          for($i = 0; $column[$i] = $xml->column[$i]; $i++);
          
          echo "<h4>Search database</h4>
                <form action=\"?e=search_database&amp;db_file=$db_file&amp;folder=$folder\" method=\"post\">
                  Search for:
                  <input type=\"text\" name=\"searchfor\">
                  in column:
                  <select name=\"column\">";
          for($i = 0; $i + 1 < count($column); $i++) {
            echo "<option>$column[$i]</option>";
          }
          echo "  </select>
                  <input type=\"submit\" value=\"Find\">
                </form>";
          
          echo "<h4>Add column</h4>
                <form action=\"?e=add_column&amp;folder=$folder&amp;db_file=$db_file\" method=\"post\">
                  Column name:
                  <input type=\"text\" name=\"column_name\">
                  <input type=\"submit\" value=\"Add column\">
                </form>
                <h4>Add row</h4>
                <form action=\"?e=add_row&amp;folder=$folder&amp;db_file=$db_file\" method=\"post\">
                <table border=0 cellpadding=5 cellspacing=0 style=\"width:100%;\">
                  <tr>";
          for($i = 0; $i + 1 < count($column); $i++) {
            echo "<th>$column[$i]</th>";
          }
          echo "  </tr>
                  <tr>
                    <td>&nbsp;</td>";
          for($j = 1; $j < $i; $j++) {
            echo "<td style=\"min-width:100px;\">
                    <textarea name=\"$column[$j]\" rows=5 cols=1 style=\"width:100%;\"></textarea>
                  </td>";
          }
          echo "  </tr>
                </table>
                <p><input type=\"submit\" value=\"Add row\"></p>
                </form>";
        break;
        
        case "add_column":
          if(addColumn($_POST['column_name'], $db_file = $_GET['db_file'], $folder = $_GET['folder'])) {
            echo "<p>The column was added to the database.</p>
                    <script type=\"text/javascript\">
                      window.setTimeout(\"document.location.href='?e=show_database&folder=$folder&db_file=$db_file'\", 1000);
                    </script>";
          }
        break;
        
        case "add_row":
          $folder = $_GET['folder'];
          $db_file = $_GET['db_file'];
          $xml_array = readDatabase($db_file, $folder, "db", false, true);
          for($i = 0; $column = $xml_array["column"][$i]; $i++) {
            $row[$column] = $_POST[$column];
          }
          if(insertRow($row, $db_file, $folder)) {
            echo "<p>The row was added to the database.</p>
                    <script type=\"text/javascript\">
                      window.setTimeout(\"document.location.href='?e=show_database&folder=$folder&db_file=$db_file'\", 1000);
                    </script>";
          }
        break;
        
        case "delete_row":
          $folder = $_GET['folder'];
          $db_file = $_GET['db_file'];
          $id = $_GET['id'];
          
          if(deleteRow($id, $db_file, $folder)) {
            echo "<p>The row was deleted from the database.</p>
                    <script type=\"text/javascript\">
                      window.setTimeout(\"document.location.href='?e=show_database&folder=$folder&db_file=$db_file'\", 500);
                    </script>";
          }
        break;
        
        case "delete_database":
          $folder = $_GET['folder'];
          $db_file = $_GET['db_file'];
          if(deleteDatabase($db_file, $folder)) {
            echo "<p>The database was deleted.</p>
                    <script type=\"text/javascript\">
                      window.setTimeout(\"document.location.href='?'\", 1000);
                    </script>";
          }
        break;
        
        case "edit_row":
          $folder = $_GET['folder'];
          $db_file = $_GET['db_file'];
          $id = $_GET['id'];
          
          $xml = readDatabase($db_file, $folder);
          
          for($rownr = 0; $xml->row[$rownr]; $rownr++) {
            if($xml->row[$rownr]->id == $id) {
              break;
            }
          }
          
          echo "<h1>NoDB Administrator Page</h1>
                <a style=\"float: right;\" href=\"?e=show_database&amp;db_file=$db_file&amp;folder=$folder\">back</a>
                <h3>Edit row</h3>
                <form action=\"?e=edit_row_done&amp;folder=$folder&amp;db_file=$db_file\" method=\"post\">
                <table border=0 cellpadding=5 cellspacing=0 style=\"width:100%;\">
                  <tr>";
          for($i = 0; $column[$i] = $xml->column[$i]; $i++) {
            echo "<th>$column[$i]</th>";
          }
          echo "  </tr>
                  <tr>
                    <td>$id</td>";
          for($j = 1; $j < $i; $j++) {
            echo "<td style=\"min-width:100px;\">
                    <textarea name=\"$column[$j]\" rows=5 cols=1 style=\"width:100%;\">" .
                    $xml->row[$rownr]->{$xml->column[$j]} .
                    "</textarea>
                  </td>";
          }
          echo "  </tr>
                </table>
                <input type=\"hidden\" name=\"id\" value=\"$id\">
                <p><input type=\"submit\" value=\"Edit row\"></p>
                </form>";
        break;
        
        case "delete_column":
          $column = $_GET['column'];
          $db_file = $_GET['db_file'];
          $folder = $_GET['folder'];
          
          if(deleteColumn($column, $db_file, $folder)) {
            echo "<p>The column was deleted from the database.</p>
                    <script type=\"text/javascript\">
                      window.setTimeout(\"document.location.href='?e=show_database&folder=$folder&db_file=$db_file'\", 1000);
                    </script>";
          }
        break;
        
        case "edit_column":
          $column_old = $_GET['column'];
          $db_file = $_GET['db_file'];
          $folder = $_GET['folder'];
          echo "<h1>NoDB Administrator Page</h1>
                <a style=\"float: right;\" href=\"?e=show_database&amp;db_file=$db_file&amp;folder=$folder\">back</a>
                <h3>Edit column name</h3>
                <form
                  action=\"?e=edit_column_done&amp;db_file=$db_file&amp;folder=$folder&amp;column_old=$column_old\"
                  method=\"post\">
                  New column name for column <b>$column_old</b>:
                  <input type=\"text\" name=\"column_new\">
                  <input type=\"submit\" value=\"Change column name\">
                </form>";
        break;
        
        case "edit_column_done":
          $column_old = $_GET['column_old'];
          $column_new = $_POST['column_new'];
          $db_file = $_GET['db_file'];
          $folder = $_GET['folder'];
          
          if(editColumn($column_old, $column_new, $db_file, $folder)) {
            echo "<p>The column name was successfully edited.</p>
                    <script type=\"text/javascript\">
                      window.setTimeout(\"document.location.href='?e=show_database&folder=$folder&db_file=$db_file'\", 1000);
                    </script>";
          }
        break;
        
        case "edit_row_done":
          $folder = $_GET['folder'];
          $db_file = $_GET['db_file'];
          $id = $_POST['id'];
          $xml_array = readDatabase($db_file, $folder, "db", false, true);
          for($i = 0; $column = $xml_array["column"][$i]; $i++) {
            $row[$column] = $_POST[$column];
          }
          if(editRow($id, $row, $db_file, $folder)) {
            echo "<p>The row was successfully edited.</p>
                    <script type=\"text/javascript\">
                      window.setTimeout(\"document.location.href='?e=show_database&folder=$folder&db_file=$db_file'\", 1000);
                    </script>";
          }
        break;
        
        case "search_database":
          $db_file = $_GET['db_file'];
          $folder = $_GET['folder'];
          
          echo "<h1>NoDB Administrator Page</h1>
                <a style=\"float: right;\" href=\"?e=show_database&amp;db_file=$db_file&amp;folder=$folder\">back</a>
                <h3>Search database</h3>";
            
          $xml = readDatabase($db_file, $folder);
          
          if($results = searchDatabase($searchfor = $_POST['searchfor'], $column = $_POST['column'], $db_file, $folder)) {
            
            echo "<table border=0 cellpadding=5 cellspacing=0 style=\"width:100%;\">
                    <tr>";
            for($i = 0; $columns[$i] = $xml->column[$i]; $i++) {
              echo "<th>$columns[$i]</th>";
            }
            echo "    <th></th>
                    </tr>
                    <tr>
                      <th></th>";
            for($i = 1; $columns[$i] = $xml->column[$i]; $i++) {
              echo "<th>
                      <a href=\"?e=delete_column&amp;db_file=$db_file&amp;folder=$folder&amp;column=" .
                        $columns[$i] . 
                        "\" onclick=\"if(!confirm('Are you sure that you want to delete that column? All entries in that column will also be deleted!')) return false;\"><img src=\"img/cross.png\" alt=\"delete\" title=\"delete column\"></a>
                      <a href=\"?e=edit_column&amp;db_file=$db_file&amp;folder=$folder&amp;column=" .
                        $columns[$i] . 
                        "\"><img src=\"img/pencil.png\" alt=\"edit\" title=\"edit column name\"></a>
                    </th>";
            }
            echo "    <th></th>
                    </tr>";
            foreach($results as $key => $j) {
              echo "<tr>";
              for($i = 0; $columns[$i]; $i++) {
                echo "<td>" . nl2br($xml->row[$j]->{$columns[$i]}) . "</td>";
              }
              echo "  <td>
                        <a href=\"?e=delete_row&amp;db_file=$db_file&amp;folder=$folder&amp;id=" .
                          $xml->row[$j]->{$columns[0]} . 
                          "\" onclick=\"if(!confirm('Are you sure that you want to delete that row?')) return false;\"><img src=\"img/cross.png\" alt=\"delete\" title=\"delete row\"></a>
                        <a href=\"?e=edit_row&amp;db_file=$db_file&amp;folder=$folder&amp;id=" .
                          $xml->row[$j]->{$columns[0]} . 
                          "\"><img src=\"img/pencil.png\" alt=\"edit\" title=\"edit row\"></a>
                      </td>
                    </tr>";
            }
            echo "</table>";
          }
          
          echo "<h4>Search database</h4>
                <form action=\"?e=search_database&amp;db_file=$db_file&amp;folder=$folder\" method=\"post\">
                  Search for:
                  <input type=\"text\" name=\"searchfor\">
                  in column:
                  <select name=\"column\">";
          for($i = 0; $columns[$i] = $xml->column[$i]; $i++) {
              echo "<option>$columns[$i]</option>";
            }
          echo "  </select>
                  <input type=\"submit\" value=\"Find\">
                </form>";
        break;
      }
    ?>
  </body>
</html>