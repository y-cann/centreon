<?php
/*
 * Copyright 2005-2014 MERETHIS
 * Centreon is developped by : Julien Mathis and Romain Le Merlus under
 * GPL Licence 2.0.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation ; either version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see <http://www.gnu.org/licenses>.
 *
 * Linking this program statically or dynamically with other modules is making a
 * combined work based on this program. Thus, the terms and conditions of the GNU
 * General Public License cover the whole combination.
 *
 * As a special exception, the copyright holders of this program give MERETHIS
 * permission to link this program with independent modules to produce an executable,
 * regardless of the license terms of these independent modules, and to copy and
 * distribute the resulting executable under terms of MERETHIS choice, provided that
 * MERETHIS also meet, for each linked independent module, the terms  and conditions
 * of the license of that module. An independent module is a module which is not
 * derived from this program. If you modify this program, you may extend this
 * exception to your version of the program, but you are not obliged to do so. If you
 * do not wish to do so, delete this exception statement from your version.
 *
 * For more information : contact@centreon.com
 *
 *
 */

	if (!isset($oreon)) {
		exit();
	}

	$oreon->optGen["AjaxFirstTimeReloadStatistic"] == 0 ? $tFS = 10 : $tFS = $oreon->optGen["AjaxFirstTimeReloadStatistic"] * 1000;
	$oreon->optGen["AjaxFirstTimeReloadMonitoring"] == 0 ? $tFM = 10 : $tFM = $oreon->optGen["AjaxFirstTimeReloadMonitoring"] * 1000;
	$sid = session_id();
	$time = time();

	$obis = $o;
	if(isset($_GET["problem"])) {
		$obis .= '_pb';
	}
	if(isset($_GET["acknowledge"])) {
		$obis .= '_ack_' . $_GET["acknowledge"];
	}
?>
<script type="text/javascript">
var _debug = 0;

var _addrXML = "./include/monitoring/status/Scheduling/xml/<?php print $centreon->broker->getBroker(); ?>/serviceScheduleXML.php";
var _addrXSL = "./include/monitoring/status/Scheduling/xsl/serviceSchedule.xsl";

<?php include_once "./include/monitoring/status/Common/commonJS.php"; ?>

function set_header_title(){
	var _img_asc = mk_img('./img/icones/7x7/sort_asc.gif', "asc");
	var _img_desc = mk_img('./img/icones/7x7/sort_desc.gif', "desc");

	if(document.getElementById('host_name')){

		var h = document.getElementById('host_name');
		h.innerHTML = '<?php echo addslashes(_("Hosts"))?>';
	  	h.indice = 'host_name';
	  	h.onclick=function(){change_type_order(this.indice)};
		h.style.cursor = "pointer";
		h.style.cursor = "pointer";

		var h = document.getElementById('service_description');
		h.innerHTML = '<?php echo addslashes(_("Services"))?>';
	  	h.indice = 'service_description';
	  	h.onclick=function(){change_type_order(this.indice)};
		h.style.cursor = "pointer";
		h.style.cursor = "pointer";

		var h = document.getElementById('last_check');
		h.innerHTML = '<?php echo addslashes(_("Last Check"))?>';
	  	h.indice = 'last_check';
	  	h.onclick=function(){change_type_order(this.indice)};
		h.style.cursor = "pointer";
		h.style.cursor = "pointer";

		var h = document.getElementById('next_check');
		h.innerHTML = '<?php echo addslashes(_("Next Check"))?>';
	  	h.indice = 'next_check';
	  	h.onclick=function(){change_type_order(this.indice)};
		h.style.cursor = "pointer";
		h.style.cursor = "pointer";

		var h = document.getElementById('active_check');
		h.innerHTML = '<?php echo addslashes(_("Active Check"))?>';
	  	h.indice = 'active_check';
	  	h.onclick=function(){change_type_order(this.indice)};
		h.style.cursor = "pointer";
		h.style.cursor = "pointer";

		var h = document.getElementById(_sort_type);
		var _linkaction_asc = document.createElement("a");
		if(_order == 'ASC')
			_linkaction_asc.appendChild(_img_asc);
		else
			_linkaction_asc.appendChild(_img_desc);
		_linkaction_asc.href = '#' ;
		_linkaction_asc.onclick=function(){change_order()};
		h.appendChild(_linkaction_asc);
	}
}

function goM(_time_reload, _sid, _o) {

	_lock = 1;
	var proc = new Transformation();

	if (_counter == 0) {
		document.getElementById("input_search").value = _search;
		document.getElementById("host_search").value = _host_search;
		_counter++;
	}
	proc.setCallback(monitoringCallBack);
	proc.setXml(_addrXML+"?"+'&sid='+_sid+'&search_host='+_host_search+'&search='+_search+'&num='+_num+'&limit='+_limit+'&sort_type='+_sort_type+'&order='+_order+'&date_time_format_status='+_date_time_format_status+'&o='+_o+'&p='+_p+'&instance='+_instance+'&nc='+_nc+'&enable=<?php echo urlencode(_("Enabled"))?>'+'&disable=<?php echo urlencode(_("Disabled"))?>'+'&time=<?php print time(); ?>');
	proc.setXslt(_addrXSL);
	proc.transform("forAjax");
	_lock = 0;

	viewDebugInfo('--end--');

	_timeoutID = setTimeout('goM("'+ _time_reload +'","'+ _sid +'","'+_o+'")', _time_reload);
	_time_live = _time_reload;
	_on = 1;

	set_header_title();
}
</SCRIPT>