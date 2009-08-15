<?php
//Copyright (C) 2009 Philippe Lindheimer 
//Copyright (C) 2009 Bandwidth.com
//
//This program is free software; you can redistribute it and/or
//modify it under the terms of the GNU General Public License
//as published by the Free Software Foundation version 2
//of the License.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.

if (! function_exists("out")) {
	function out($text) {
		echo $text."<br />";
	}
}

if (! function_exists("outn")) {
	function outn($text) {
		echo $text;
	}
}

global $db;
global $amp_conf;

$sql = "
CREATE TABLE IF NOT EXISTS `outroutemsg` 
(
	`keyword` varchar(40) NOT NULL default '',
	`data` varchar(10) NOT NULL,
	PRIMARY KEY  (`keyword`)
)
";
$check = $db->query($sql);
if(DB::IsError($check)) {
	die_freepbx(_("Can not create outroutemsg table"));
}

?>
