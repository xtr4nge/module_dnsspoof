<? 
/*
	Copyright (C) 2013  xtr4nge [_AT_] gmail.com

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
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>FruityWifi</title>
<script src="../js/jquery.js"></script>
<script src="../js/jquery-ui.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css" />
<link rel="stylesheet" href="../css/style.css" />
<link rel="stylesheet" href="../../../style.css" />

<script>
$(function() {
    $( "#action" ).tabs();
    $( "#result" ).tabs();
});

</script>

</head>
<body>

<? include "../menu.php"; ?>

<br>

<?

include "_info_.php";

include "../../config/config.php";
include "../../functions.php";

// Checking POST & GET variables...
if ($regex == 1) {
    regex_standard($_POST["newdata"], "msg.php", $regex_extra);
    regex_standard($_GET["logfile"], "msg.php", $regex_extra);
    regex_standard($_GET["action"], "msg.php", $regex_extra);
}

$newdata = $_POST['newdata'];
$logfile = $_GET["logfile"];
$action = $_GET["action"];

// SAVE DNSSPOOF HOSTS
if ($newdata != "") { $newdata = ereg_replace(13,  "", $newdata);
    $exec = "/bin/echo '$newdata' > /usr/share/FruityWifi/conf/spoofhost.conf";
	exec("/usr/share/FruityWifi/www/bin/danger \"" . $exec . "\"", $output);
}

// DELETE LOG
if ($logfile != "" and $action == "delete") {
    $exec = "rm ".$mod_logs_history.$logfile.".log";
    exec("/usr/share/FruityWifi/www/bin/danger \"" . $exec . "\"", $dump);
}

?>

<div class="rounded-top" align="left"> &nbsp; dnsspoof </div>
<div class="rounded-bottom">
    &nbsp;&nbsp;&nbsp;version <?=$mod_version?><br>
    &nbsp;DNS Spoof <font style="color:lime">installed</font><br>
    <?
    $isdnsspoofup = exec("ps auxww | grep dnsspoof | grep -v -e grep");
    if ($isdnsspoofup != "") {
        echo "&nbsp;DNS Spoof  <font color=\"lime\"><b>enabled</b></font>.&nbsp; | <a href=\"../../scripts/status_dnsspoof.php?service=dnsspoof&action=stop&page=module\"><b>stop</b></a>";
    } else { 
        echo "&nbsp;DNS Spoof  <font color=\"red\"><b>disabled</b></font>. | <a href=\"../../scripts/status_dnsspoof.php?service=dnsspoof&action=start&page=module\"><b>start</b></a>";
    }

    ?>

</div>

<br>

<div id="result" class="module">
    <ul>
        <li><a href="#result-1">Output</a></li>
        <li><a href="#result-2">History</a></li>
        <li><a href="#result-3">Hosts</a></li>
    </ul>
    <div id="result-1" >
        <form id="formLogs" name="formLogs" method="POST" autocomplete="off">
        <input type="submit" value="refresh">
        <br><br>
        <?
            if ($logfile != "" and $action == "view") {
                $filename = $mod_logs_history.$logfile.".log";
            } else {
                $filename = $mod_logs;
            }

            $fh = fopen($filename, "r") or die("Could not open file.");
            $data = fread($fh, filesize($filename)) or die("Could not read file.");
            fclose($fh);
            $data_array = explode("\n", $data);
            $data = implode("\n",array_reverse($data_array));
            
        ?>
        <textarea id="output" class="module-content"><?=$data?></textarea>
        <input type="hidden" name="type" value="logs">
        </form>
    </div>
    <div id="result-2">
        <input type="submit" value="refresh">
        <br><br>
        
        <?
        $logs = glob($mod_logs_history.'*.log');
        print_r($a);

        for ($i = 0; $i < count($logs); $i++) {
            $filename = str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]));
            echo "<a href='?logfile=".str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]))."&action=delete'><b>x</b></a> ";
            echo $filename . " | ";
            echo "<a href='?logfile=".str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]))."&action=view'><b>view</b></a>";
            echo "<br>";
        }
        ?>
        
    </div>
    <div id="result-3" >
        <form id="formHosts" name="formHosts" method="POST" autocomplete="off">
        <input type=submit value="save">
        <br><br>
        <?
            $filename = "/usr/share/FruityWifi/conf/spoofhost.conf";

            $fh = fopen($filename, "r") or die("Could not open file.");
            $data = fread($fh, filesize($filename)) or die("Could not read file.");
            fclose($fh);
            
        ?>
        <textarea id="hosts" name="newdata" class="module-content"><?=$data?></textarea>
        <input type="hidden" name="type" value="hosts">
        </form>
    </div>
</div>

<div id="loading" class="ui-widget" style="width:100%;background-color:#000; padding-top:4px; padding-bottom:4px;color:#FFF">
    Loading...
</div>

<script>
$('#formLogs').submit(function(event) {
    event.preventDefault();
    $.ajax({
        type: 'POST',
        url: 'includes/ajax.php',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (data) {
            console.log(data);

            $('#output').html('');
            $.each(data, function (index, value) {
                if (value != "") {
                    $("#output").append( value ).append("\n");
                }
            });
            
            $('#loading').hide();

        }
    });
    
    $('#output').html('');
    $('#loading').show()

});

$('#loading').hide();

</script>

<script>
$('#formHosts').submit(function(event) {
    event.preventDefault();
    $.ajax({
        type: 'POST',
        url: 'includes/ajax.php',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (data) {
            console.log(data);

            $('#hosts').html('');
            $.each(data, function (index, value) {
                $("#hosts").append( value ).append("\n");
            });
            
            $('#loading').hide();
            
        }
    });
    
    $('#output').html('');
    $('#loading').show()

});

$('#loading').hide();

</script>

</body>
</html>
