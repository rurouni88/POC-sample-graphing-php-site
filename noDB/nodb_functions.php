<?php
/*
NoDB File-Based Simple Database System for PHP

Version 0.4

Copyright (C) 2010 Sebastian Lukas

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses/>.
*/
function createNewDatabase($db_name, $path, $folder, $newFolder = "") {
if(
      !$db_name
      || $folder == "no_database_selected"
      || (!$folder && !$newFolder)
      || (!$folder && is_dir("$path/" . $newFolder))
    ) {
    echo "<p>Error. Please fill out the form again.</p>";
    return false;;
  }
  else {
    if(!$folder) {
      mkdir("$path/" . $newFolder);
      copy("$path/index.php","$path/$newFolder/index.php");
      $folder = $newFolder;
    }
    elseif(file_exists("$path/$folder/$db_name.nodb")) {
      echo "<p>Error: The database already exists.</p>";
      return false;
    }

    $file = fopen("$path/$folder/$db_name.nodb", "w");
    $content =
"<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<database>
  <column>id</column>
</database>";
    fwrite($file, $content);
    fclose($file);
    return $folder;
  }
}

function readDatabase($db_file, $folder, $path = "db", $display_code = false, $return_as_array = false) {
  if(!is_dir($path . "/" . $folder)) {
    echo "Error: The path/folder '$path/$folder' does not exist.";
    return false;
  }
  if(!file_exists("$path/$folder/$db_file")) {
    echo "Error: Database doesn't exist ($path/$folder/$db_file). (readDatabase)";
    return false;
  }
  $file = fopen("$path/$folder/$db_file","r");
  $content = fread($file, filesize("$path/$folder/$db_file"));

  $xml = new SimpleXMLElement($content);

  if($display_code) {
    $code = nl2br(str_replace(" ", "&nbsp;", convertToEntities($content)));
    echo $code;
  }

  if($return_as_array) {
    return get_object_vars($xml);
  }
  else {
    return $xml;
  }
}

function displayDatabaseAsTable($db_file, $folder, $path = "db", $orderby = false) {
  $xml = readDatabase($db_file, $folder, $path);
  echo "<table border=0 cellpadding=5 cellspacing=0 style=\"width:100%;\">
          <tr>";
  for($i = 0; $column[$i] = $xml->column[$i]; $i++) {
    echo "<th>$column[$i]</th>";
  }
  echo "    <th></th>
          </tr>
          <tr>
            <th></th>";
  for($i = 1; $column[$i] = $xml->column[$i]; $i++) {
    echo "<th>
            <a href=\"?e=delete_column&amp;db_file=$db_file&amp;folder=$folder&amp;column=" .
              $column[$i] .
              "\" onclick=\"if(!confirm('Are you sure that you want to delete that column? All entries in that column will also be deleted!')) return false;\"><img src=\"img/cross.png\" alt=\"delete\" title=\"delete column\"></a>
            <a href=\"?e=edit_column&amp;db_file=$db_file&amp;folder=$folder&amp;column=" .
              $column[$i] .
              "\"><img src=\"img/pencil.png\" alt=\"edit\" title=\"edit column name\"></a>
          </th>";
  }
  echo "    <th></th>
          </tr>";
  for($j = 0; $row[$j] = $xml->row[$j]; $j++) {
    echo "<tr>";
    for($i = 0; $column[$i]; $i++) {
      echo "<td>" . nl2br($xml->row[$j]->{$column[$i]}) . "</td>";
    }
    echo "  <td>
              <a href=\"?e=delete_row&amp;db_file=$db_file&amp;folder=$folder&amp;id=" .
                $xml->row[$j]->{$column[0]} .
                "\" onclick=\"if(!confirm('Are you sure that you want to delete that row?')) return false;\"><img src=\"img/cross.png\" alt=\"delete\" title=\"delete row\"></a>
              <a href=\"?e=edit_row&amp;db_file=$db_file&amp;folder=$folder&amp;id=" .
                $xml->row[$j]->{$column[0]} .
                "\"><img src=\"img/pencil.png\" alt=\"edit\" title=\"edit row\"></a>
            </td>
          </tr>";
  }
  echo "</table>";
}

function addColumn($column, $db_file, $folder, $path = "db") {
  $folder = htmlspecialchars($folder);
  if(strstr($column, "<")
      || strstr($column, ">")
      || strstr($column, "^")
      || strstr($column, "°")
      || strstr($column, "!")
      || strstr($column, "\"")
      || strstr($column, "§")
      || strstr($column, "$")
      || strstr($column, "%")
      || strstr($column, "&")
      || strstr($column, "/")
      || strstr($column, "(")
      || strstr($column, ")")
      || strstr($column, "=")
      || strstr($column, "?")
      || strstr($column, "`")
      || strstr($column, "²")
      || strstr($column, "³")
      || strstr($column, "{")
      || strstr($column, "[")
      || strstr($column, "]")
      || strstr($column, "}")
      || strstr($column, "\\")
      || strstr($column, "´")
      || strstr($column, ".")
      || strstr($column, ":")
      || strstr($column, ",")
      || strstr($column, ";")
      || strstr($column, "-")
      || strstr($column, "_")
      || strstr($column, "+")
      || strstr($column, "*")
      || strstr($column, "~")
      || strstr($column, "'")
      || strstr($column, "#")
  ) {
    echo "Error: The column name must not contain special characters.";
    return false;
  }
  $xml = readDatabase($db_file, $folder, $path);

  for($i = 0; $xml->column[$i]; $i++) {
    if($xml->column[$i] == $column) {
      echo "Error: Column name <b>$column</b> already exists in database <b>$db_file</b>.";
      return false;
    }
  }

  $xml->column[$i] = $column;

  $xmlstr = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n" .
            "<database>\n";
  for($i = 0; $xml->column[$i]; $i++) {
    $xmlstr .= "  <column>" . $xml->column[$i] . "</column>\n";
  }

  for($i = 0; $row = $xml->row[$i]; $i++) {
    $xmlstr .= "  <row>\n";
    for($j = 0; $xml->column[$j]; $j++) {
      $xmlstr .= "    <" . $xml->column[$j] . ">" . $row->{$xml->column[$j]} . "</" . $xml->column[$j] . ">\n";
    }
    $xmlstr .= "  </row>\n";
  }

  $xmlstr .= "</database>";

  $file = fopen("$path/$folder/$db_file", "w");
  fwrite($file, $xmlstr);
  fclose($file);
  return true;
}

function editColumn($column_old, $column, $db_file, $folder, $path = "db") {
  if(strstr($column, "<")
      || strstr($column, ">")
      || strstr($column, "^")
      || strstr($column, "°")
      || strstr($column, "!")
      || strstr($column, "\"")
      || strstr($column, "§")
      || strstr($column, "$")
      || strstr($column, "%")
      || strstr($column, "&")
      || strstr($column, "/")
      || strstr($column, "(")
      || strstr($column, ")")
      || strstr($column, "=")
      || strstr($column, "?")
      || strstr($column, "`")
      || strstr($column, "²")
      || strstr($column, "³")
      || strstr($column, "{")
      || strstr($column, "[")
      || strstr($column, "]")
      || strstr($column, "}")
      || strstr($column, "\\")
      || strstr($column, "´")
      || strstr($column, ".")
      || strstr($column, ":")
      || strstr($column, ",")
      || strstr($column, ";")
      || strstr($column, "-")
      || strstr($column, "_")
      || strstr($column, "+")
      || strstr($column, "*")
      || strstr($column, "~")
      || strstr($column, "'")
      || strstr($column, "#")
  ) {
    echo "Error: The column name must not contain special characters.";
    return false;
  }
  if($column_old == "id") {
    echo "Error: You cannot edit the column <b>id</b>!";
    return false;
  }
  $xml = readDatabase($db_file, $folder, $path);

  $xmlstr = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n" .
            "<database>\n";
  for($i = 0; $xml->column[$i]; $i++) {
    if($xml->column[$i] != $column_old) {
      $xmlstr .= "  <column>" . $xml->column[$i] . "</column>\n";
    }
    else {
      $xmlstr .= "  <column>" . $column . "</column>\n";
    }
  }

  for($i = 0; $row = $xml->row[$i]; $i++) {
    $xmlstr .= "  <row>\n";
    for($j = 0; $xml->column[$j]; $j++) {
      if($xml->column[$j] != $column_old) {
        $xmlstr .= "    <" . $xml->column[$j] . ">" . $row->{$xml->column[$j]} . "</" . $xml->column[$j] . ">\n";
      }
      else {
        $xmlstr .= "    <$column>" . $row->{$xml->column[$j]} . "</$column>\n";
      }
    }
    $xmlstr .= "  </row>\n";
  }

  $xmlstr .= "</database>";

  $file = fopen("$path/$folder/$db_file", "w");
  fwrite($file, $xmlstr);
  fclose($file);
  return true;
}

function deleteColumn($column, $db_file, $folder, $path = "db") {
  if($column == "id") {
    echo "Error: You cannot delete the column <b>id</b>!";
    return false;
  }
  $xml = readDatabase($db_file, $folder, $path);

  $xmlstr = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n" .
            "<database>\n";
  for($i = 0; $xml->column[$i]; $i++) {
    if($xml->column[$i] != $column) {
      $xmlstr .= "  <column>" . $xml->column[$i] . "</column>\n";
    }
  }

  for($i = 0; $row = $xml->row[$i]; $i++) {
    $xmlstr .= "  <row>\n";
    for($j = 0; $xml->column[$j]; $j++) {
      if($xml->column[$j] != $column) {
        $xmlstr .= "    <" . $xml->column[$j] . ">" . $row->{$xml->column[$j]} . "</" . $xml->column[$j] . ">\n";
      }
    }
    $xmlstr .= "  </row>\n";
  }

  $xmlstr .= "</database>";

  $file = fopen("$path/$folder/$db_file", "w");
  fwrite($file, $xmlstr);
  fclose($file);
  return true;
}

function editRow($id, $row, $db_file, $folder, $path = "db") {
  if(deleteRow($id, $db_file, $folder, $path) && insertRow($row, $db_file, $folder, $path, $id)) {
    return true;
  }
}

function deleteRow($id, $db_file, $folder, $path = "db") {
  $xml = readDatabase($db_file, $folder, $path);

  $xmlstr = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n" .
            "<database>\n";
  for($i = 0; $xml->column[$i]; $i++) {
    $xmlstr .= "  <column>" . $xml->column[$i] . "</column>\n";
  }

  for($i = 0; $xmlrow = $xml->row[$i]; $i++) {
    if($xmlrow->{$xml->column[0]} != $id) {
      $xmlstr .= "  <row>\n";
      for($j = 0; $xml->column[$j]; $j++) {
        $xmlstr .= "    <" . $xml->column[$j] . ">" . convertToEntities($xmlrow->{$xml->column[$j]}) . "</" . $xml->column[$j] . ">\n";
      }
      $xmlstr .= "  </row>\n";
    }
  }

  $xmlstr .= "</database>";

  $file = fopen("$path/$folder/$db_file", "w");
  fwrite($file, $xmlstr);
  fclose($file);
  return true;
}

function insertRow($row, $db_file, $folder, $path = "db", $given_id = false) {
  $xml = readDatabase($db_file, $folder, $path);
  for($i = 0; $i < count($row); $i++) {
    if($xml->column[$i] == key($row)) {
      next($row);
    }
    else {
      echo "Error: Given columns do not match database. <b>" . key($row) . "</b> is not in database or is at another place.";
      return false;
    }
  }

  $xmlstr = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n" .
            "<database>\n";
  for($i = 0; $xml->column[$i]; $i++) {
    $xmlstr .= "  <column>" . $xml->column[$i] . "</column>\n";
  }

  $new_id = 1;

  for($i = 0; $xmlrow = $xml->row[$i]; $i++) {
    $xmlstr .= "  <row>\n";
    for($j = 0; $xml->column[$j]; $j++) {
      $xmlstr .= "    <" . $xml->column[$j] . ">" . convertToEntities($xmlrow->{$xml->column[$j]}) . "</" . $xml->column[$j] . ">\n";
    }

    if($new_id <= $xmlrow->{$xml->column[0]}) {
      $new_id = $xmlrow->{$xml->column[0]} + 1;
    }
    $xmlstr .= "  </row>\n";
  }

  $row = array_values($row);

  $xmlstr .= "  <row>\n";
  for($i = 0; $i < count($row); $i++) {
    if($xml->column[$i] == "id" && !$given_id) {
      $id = $new_id;
      $xmlstr .= "    <id>$id</id>\n";
    }
    elseif($xml->column[$i] == "id" && $given_id) {
      $xmlstr .= "    <id>$given_id</id>\n";
    }
    else {
      $xmlstr .= "    <" . $xml->column[$i] . ">" . convertToEntities($row[$i]) . "</" . $xml->column[$i] . ">\n";
    }
  }

  $xmlstr .= "  </row>\n" .
             "</database>";

  $file = fopen("$path/$folder/$db_file", "w");
  fwrite($file, $xmlstr);
  fclose($file);
  return true;
}

function deleteDatabase($db_file, $folder, $path = "db") {
  if(file_exists("$path/$folder/$db_file")) {
    @unlink("$path/$folder/$db_file");
    return true;
  }
  else {
    echo "Error: The file &quot;$path/$folder/$db_file&quot; does not exists. (deleteDatabase)";
    return false;
  }
}

function convertToEntities($str) {
  /*$search = array(
    "/",
    "&Acirc;",
    "&deg;",
    "&quot;",
    "&sect;",
    "&amp;",
    "&sup2;",
    "&sup3;",
    "&acute;",
    "&lt;",
    "&gt;",
    "&Atilde;",
    "&frac14;",
    "&para;",
    "&curren;",
    "&szlig;",
  );*/
  $search = array(
    "/",
    "^",
    "°",
    "\"",
    "§",
    "²",
    "³",
    "´",
    "<",
    ">",
    "~",
    "ß",
    "&amp;",
    "&Acirc;&copy;",
    "&Atilde;&curren;",
    "&Atilde;„",
    "&Atilde;&para;",
    "&Atilde;–",
    "&Atilde;&frac14;",
    "&Atilde;œ",
    "&Atilde;Ÿ",
    "&Atilde;&copy;",
    "&Atilde;&uml;",
    "&Atilde;&ordf;",
    "&Atilde;&laquo;",
    "&Atilde;&nbsp;",
    "&Atilde;&iexcl;",
    "&Atilde;&cent;",
    "&Atilde;&pound;",
    "&Atilde;&not;",
    "&Atilde;&shy;",
    "&Atilde;&reg;",
    "&Atilde;&sup2;",
    "&Atilde;&sup3;",
    "&Atilde;&acute;",
    "&Atilde;&micro;",
    "&Atilde;&sup1;",
    "&Atilde;&ordm;",
    "&Atilde;&raquo;",
    "&Acirc;&deg;",
    "&Acirc;&sup2;",
    "&Acirc;&sup3;",
    "&Acirc;&acute;",
    "&Atilde;&sect;",
    "&Acirc;&laquo;",
    "&Acirc;&raquo;",
    "&Acirc;&pound;",
    "&acirc;‚&not;",
  );
  $replace = array(
    "|",
    "&#94;",
    "&#176;",
    "&#34;",
    "&#167;",
    "&#178;",
    "&#179;",
    "&#180;",
    "&#60;",
    "&#62;",
    "&#126;",
    "&#223;",
    "and",
    "&#169;",
    "&#228;",
    "&#196;",
    "&#246;",
    "&#214;",
    "&#252;",
    "&#220;",
    "&#223;",
    "&#233;",
    "&#232;",
    "&#234;",
    "&#235;",
    "&#224;",
    "&#225;",
    "&#226;",
    "&#227;",
    "&#236;",
    "&#237;",
    "&#238;",
    "&#242;",
    "&#243;",
    "&#244;",
    "&#245;",
    "&#249;",
    "&#250;",
    "&#251;",
    "&#176;",
    "&#178;",
    "&#179;",
    "&#180;",
    "&#231;",
    "&#171;",
    "&#187;",
    "&#163;",
    "&#8364;",
  );
  $str = htmlentities($str);
  return str_replace($search, $replace, $str);
}

function searchDatabase($searchfor, $column, $db_file, $folder, $path = "db", $show_text = true, $exact = false) {
  if(!$searchfor) {
    echo "Error: No search query given.";
    return false;
  }
  if(!$column) {
    echo "Error: No column given.";
    return false;
  }

  $xml = readDatabase($db_file, $folder, $path);

  $results = array();

  for($rownr = 0; $xml->row[$rownr]; $rownr++) {
    if($exact) {
      if($xml->row[$rownr]->{$column} == $searchfor) {
        $results[] = $rownr;
      }
    }
    else {
      if(stristr($xml->row[$rownr]->{$column}, $searchfor)) {
        $results[] = $rownr;
      }
    }
  }

  if($results) {
    if($show_text) {
      echo "<p>" . count($results) . " results were found for your query.</p>";
    }
    return $results;
  }
  else {
    if($show_text) {
      echo "There were no results found for your query.";
    }
    return false;
  }
}
?>