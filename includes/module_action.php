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
<?
//include "../login_check.php";
include "../_info_.php";
include "/usr/share/FruityWifi/www/config/config.php";
include "/usr/share/FruityWifi/www/functions.php";

// Checking POST & GET variables...
if ($regex == 1) {
    regex_standard($_GET["service"], "../msg.php", $regex_extra);
    regex_standard($_GET["file"], "../msg.php", $regex_extra);
    regex_standard($_GET["action"], "../msg.php", $regex_extra);
    regex_standard($_GET["page"], "../msg.php", $regex_extra);
    regex_standard($io_action, "../msg.php", $regex_extra);
}

$service = $_GET['service'];
$action = $_GET['action'];
$page = $_GET['page'];
$install = $_GET['install'];

if($service == "dnsspoof") {
    if ($action == "start") {
        // COPY LOG
        $exec = "$bin_cp $mod_logs $mod_logs_history/".gmdate("Ymd-H-i-s").".log";
        exec("$bin_danger \"$exec\"", $dump);

        $exec = "$bin_echo '' > $mod_logs";
        exec("$bin_danger \"$exec\"" );
        $exec = "$bin_dnsspoof -i $io_action -f /usr/share/FruityWifi/conf/spoofhost.conf > /dev/null 2> $mod_logs &";
        exec("$bin_danger \"$exec\"" );
    } else if($action == "stop") {
        $exec = "$bin_killall dnsspoof";
        exec("$bin_danger \"$exec\"" );
    }
}

if ($install == "install_$mod_name") {

    $exec = "chmod 755 install.sh";
    exec("$bin_danger \"$exec\"" );

    $exec = "$bin_sudo ./install.sh > /usr/share/FruityWifi/logs/install.txt &";
    exec("$bin_danger \"$exec\"" );

    header('Location: ../../install.php?module='.$mod_name);
    exit;
}

if ($page == "status") {
    header('Location: ../../../action.php');
} else {
    header('Location: ../../action.php?page=dnsspoof');
}

?>
