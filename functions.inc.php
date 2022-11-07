<?php /* $Id: function.inc.php  $ */
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed'); }
//	License for all code of this FreePBX module can be found in the license file inside the module directory
//	Copyright 2013 Schmooze Com Inc.
//  Copyright (C) 2010 Mikael Carlsson
//

define ('DEFAULT_MSG', -1);
define ('CONGESTION_TONE', -2);

function outroutemsg_get_config($engine) {
	global $db;
	global $ext;
	global $version;

	switch($engine) {
		case "asterisk":

			/* here we add macro-outisbusy with the following actions:
			 * if ( EMERGENCYROUTE=YES ):
			 * 	choose Emergency Message over everything else, ANSWER CALL
			 * if ( INTRACOMPANYROUTE=YES ):
			 * 	choose Intracompany Message over default
			 * Use default
			 */

		$contextname = 'macro-outisbusy';

		$outroutemsg_ids = outroutemsg_get();
		$exten = 's';

		$ext->replace($contextname, $exten, '1', new ext_progress());
		$ext->add($contextname, $exten, '', new ext_gotoif('$["${EMERGENCYROUTE}" = "YES"]', 'emergency,1'));
		$ext->add($contextname, $exten, '', new ext_gotoif('$["${INTRACOMPANYROUTE}" = "YES"]', 'intracompany,1'));

		switch ($outroutemsg_ids['default_msg_id']) {
			case DEFAULT_MSG:
				$ext->add($contextname, $exten, '', new ext_playback("all-circuits-busy-now&please-try-call-later, noanswer"));
				break;
			case CONGESTION_TONE:
				$ext->add($contextname, $exten, '', new ext_playtones("congestion"));
				break;
			default:
				$message = recordings_get_file($outroutemsg_ids['default_msg_id']);
				$message = ($message != "") ? $message : "all-circuits-busy-now&please-try-call-later";
				$ext->add($contextname, $exten, '', new ext_playback("$message, noanswer"));
		}
		$ext->add($contextname, $exten, '', new ext_congestion());
		$ext->add($contextname, $exten, '', new ext_hangup());

		$exten = 'intracompany';
		switch ($outroutemsg_ids['intracompany_msg_id']) {
			case DEFAULT_MSG:
				$ext->add($contextname, $exten, '', new ext_playback("all-circuits-busy-now&please-try-call-later, noanswer"));
				break;
			case CONGESTION_TONE:
				$ext->add($contextname, $exten, '', new ext_playtones("congestion"));
				break;
			default:
				$message = recordings_get_file($outroutemsg_ids['intracompany_msg_id']);
				$message = ($message != "") ? $message : "all-circuits-busy-now&please-try-call-later";
				$ext->add($contextname, $exten, '', new ext_playback("$message, noanswer"));
		}
		$ext->add($contextname, $exten, '', new ext_congestion());
		$ext->add($contextname, $exten, '', new ext_hangup());

		$exten = 'emergency';
		switch ($outroutemsg_ids['emergency_msg_id']) {
			case DEFAULT_MSG:
				$ext->add($contextname, $exten, '', new ext_playback("all-circuits-busy-now&please-try-call-later"));
				break;
			case CONGESTION_TONE:
				$ext->add($contextname, $exten, '', new ext_playtones("congestion"));
				break;
			default:
				$message = recordings_get_file($outroutemsg_ids['emergency_msg_id']);
				$message = ($message != "") ? $message : "all-circuits-busy-now&please-try-call-later";
				$ext->add($contextname, $exten, '', new ext_playback("$message"));
		}
		$ext->add($contextname, $exten, '', new ext_congestion());
		$ext->add($contextname, $exten, '', new ext_hangup());
	}
}

function outroutemsg_add($default_msg_id, $intracompany_msg_id, $emergency_msg_id, $no_answer_msg_id, $invalidnmbr_msg_id) {
    FreePBX::Modules()->deprecatedFunction();
    return FreePBX::Outroutemsg()->set($default_msg_id, $intracompany_msg_id, $emergency_msg_id, $no_answer_msg_id, $invalidnmbr_msg_id);
}

function outroutemsg_get() {
	FreePBX::Modules()->deprecatedFunction();
    return FreePBX::Outroutemsg()->get();

}

function outroutemsg_recordings_usage($recording_id) {
	global $active_modules;

	$my_id = sql("SELECT `data` FROM `outroutemsg` WHERE `data` = '$recording_id'","getOne");
	if (!isset($my_id) || $my_id == '') {
		return array();
	} else {
		$type = isset($active_modules['outroutemsg']['type'])?$active_modules['outroutemsg']['type']:'tool';
		$usage_arr[] = array(
			'url_query' => 'config.php?type='.$type.'&display=outroutemsg',
			'description' => _("Route Congestion Messages"),
		);
		return $usage_arr;
	}
}

?>
