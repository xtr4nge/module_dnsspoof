<? 
/*
    Copyright (C) 2013-2014 xtr4nge [_AT_] gmail.com

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/ 
?>
<?php

include "../_info_.php";

$type = $_POST['type'];
$newdata = $_POST['newdata'];

if ($type == "logs") {
        
    $filename = $mod_logs;

    $fh = fopen($filename, "r") or die("Could not open file.");
    $data = fread($fh, filesize($filename)) or die("Could not read file.");
    fclose($fh);
    $dump = explode("\n", $data);
    //$dump = implode("\n",array_reverse($data_array));
    
    echo json_encode(array_reverse($dump));

}

if ($type == "hosts") {

    if ($newdata != "") { $newdata = ereg_replace(13,  "", $newdata);
        $exec = "/bin/echo '$newdata' > /usr/share/fruitywifi/conf/spoofhost.conf";
        //exec("/usr/share/FruityWifi/www/bin/danger \"" . $exec . "\"", $output); //DEPRECATED
	exec_fruitywifi($exec);
    }

    $exec = "cat /usr/share/fruitywifi/conf/spoofhost.conf";
    //exec("/usr/share/FruityWifi/www/bin/danger \"" . $exec . "\"", $dump); //DEPRECATED
    exec_fruitywifi($exec);
    
    echo json_encode($dump);

}
?>
