<?php
/*
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2005 by the INDEPNET Development Team.
 
 http://indepnet.net/   http://glpi.indepnet.org
 ----------------------------------------------------------------------

 LICENSE

	This file is part of GLPI.

    GLPI is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    GLPI is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with GLPI; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
*/
 
// Based on:
// IRMA, Information Resource-Management and Administration
// Christian Bauer 
// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

include ("_relpos.php");
// FUNCTIONS peripheral


function titleperipherals(){
                GLOBAL  $lang,$HTMLRel;
                echo "<div align='center'><table border='0'><tr><td>";
                echo "<img src=\"".$HTMLRel."pics/periphs.png\" alt='".$lang["peripherals"][0]."' title='".$lang["peripherals"][0]."'></td><td><a  class='icon_consol' href=\"peripherals-add-select.php\"><b>".$lang["peripherals"][0]."</b></a>";
                echo "</td>";
                echo "<td><a class='icon_consol' href='".$HTMLRel."setup/setup-templates.php?type=".PERIPHERAL_TYPE."'>".$lang["common"][8]."</a></td>";
                echo "</tr></table></div>";
}

function showPeripheralOnglets($target,$withtemplate,$actif){
	global $lang, $HTMLRel;
	
	$template="";
	if(!empty($withtemplate)){
		$template="&amp;withtemplate=$withtemplate";
	}

	echo "<div id='barre_onglets'><ul id='onglet'>";
	echo "<li "; if ($actif=="1"){ echo "class='actif'";} echo  "><a href='$target&amp;onglet=1$template'>".$lang["title"][26]."</a></li>";
	echo "<li "; if ($actif=="4") {echo "class='actif'";} echo "><a href='$target&amp;onglet=4$template'>".$lang["Menu"][26]."</a></li>";
	echo "<li "; if ($actif=="5") {echo "class='actif'";} echo "><a href='$target&amp;onglet=5$template'>".$lang["title"][25]."</a></li>";
	if(empty($withtemplate)){
	echo "<li "; if ($actif=="6") {echo "class='actif'";} echo "><a href='$target&amp;onglet=6$template'>".$lang["title"][28]."</a></li>";
	echo "<li "; if ($actif=="7") {echo "class='actif'";} echo "><a href='$target&amp;onglet=7$template'>".$lang["title"][34]."</a></li>";
	echo "<li class='invisible'>&nbsp;</li>";
	echo "<li "; if ($actif=="-1") {echo "class='actif'";} echo "><a href='$target&amp;onglet=-1$template'>".$lang["title"][29]."</a></li>";
	}
	
	echo "<li class='invisible'>&nbsp;</li>";
	
	if (empty($withtemplate)&&preg_match("/\?ID=([0-9]+)/",$target,$ereg)){
	$ID=$ereg[1];
	$next=getNextItem("glpi_peripherals",$ID);
	$prev=getPreviousItem("glpi_peripherals",$ID);
	$cleantarget=preg_replace("/\?ID=([0-9]+)/","",$target);
	if ($prev>0) echo "<li><a href='$cleantarget?ID=$prev'><img src=\"".$HTMLRel."pics/left.png\" alt='".$lang["buttons"][12]."' title='".$lang["buttons"][12]."'></a></li>";
	if ($next>0) echo "<li><a href='$cleantarget?ID=$next'><img src=\"".$HTMLRel."pics/right.png\" alt='".$lang["buttons"][11]."' title='".$lang["buttons"][11]."'></a></li>";
	}
	echo "</ul></div>";
	
}

function searchFormperipheral($field="",$phrasetype= "",$contains="",$sort= "",$deleted="",$link="") {
	// Print Search Form
	
	GLOBAL $cfg_install, $cfg_layout, $layout, $lang,$HTMLRel;

	$option["periph.name"]				= $lang["peripherals"][5];
	$option["periph.ID"]				= $lang["peripherals"][23];
	$option["glpi_dropdown_locations.name"]			= $lang["peripherals"][6];
	$option["glpi_type_peripherals.name"]				= $lang["peripherals"][9];
	$option["periph.serial"]			= $lang["peripherals"][10];
	$option["periph.otherserial"]		= $lang["peripherals"][11]	;
	$option["periph.comments"]			= $lang["peripherals"][12];
	$option["periph.contact"]			= $lang["peripherals"][8];
	$option["periph.contact_num"]		= $lang["peripherals"][7];
	$option["periph.date_mod"]			= $lang["peripherals"][16];
	$option["glpi_enterprises.name"]			= $lang["common"][5];
	$option["resptech.name"]			=$lang["common"][10];
	$option=addInfocomOptionFieldsToResearch($option);
	$option=addContractOptionFieldsToResearch($option);

	echo "<form method='get' action=\"".$cfg_install["root"]."/peripherals/peripherals-search.php\">";
	echo "<div align='center'><table  width='850' class='tab_cadre'>";
	echo "<tr><th colspan='4'><b>".$lang["search"][0].":</b></th></tr>";
	echo "<tr class='tab_bg_1'>";
	echo "<td align='center'>";
	echo "<table>";
	
	for ($i=0;$i<$_SESSION["glpisearchcount"];$i++){
		echo "<tr><td align='right'>";
		if ($i==0){
			echo "<a href='".$cfg_install["root"]."/computers/computers-search.php?add_search_count=1'><img src=\"".$HTMLRel."pics/plus.png\" alt='+'></a>&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($_SESSION["glpisearchcount"]>1)
			echo "<a href='".$cfg_install["root"]."/computers/computers-search.php?delete_search_count=1'><img src=\"".$HTMLRel."pics/moins.png\" alt='-'></a>&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		if ($i>0) {
			echo "<select name='link[$i]'>";
			
			echo "<option value='AND' ";
			if(is_array($link)&&isset($link[$i]) && $link[$i] == "AND") echo "selected";
			echo ">AND</option>";
			
			echo "<option value='OR' ";
			if(is_array($link)&&isset($link[$i]) && $link[$i] == "OR") echo "selected";
			echo ">OR</option>";		
			
			echo "<option value='AND NOT' ";
			if(is_array($link)&&isset($link[$i]) && $link[$i] == "AND NOT") echo "selected";
			echo ">AND NOT</option>";		
			
			echo "<option value='OR NOT' ";
			if(is_array($link)&&isset($link[$i]) && $link[$i] == "OR NOT") echo "selected";
			echo ">OR NOT</option>";

			echo "</select>";
		}
		
		echo "<input type='text' size='15' name=\"contains[$i]\" value=\"". (is_array($contains)&&isset($contains[$i])?stripslashes($contains[$i]):"" )."\" >";
		echo "&nbsp;";
		echo $lang["search"][10]."&nbsp;";
	
		echo "<select name=\"field[$i]\" size='1'>";
        	echo "<option value='all' ";
		if(is_array($field)&&isset($field[$i]) && $field[$i] == "all") echo "selected";
		echo ">".$lang["search"][7]."</option>";
        	reset($option);
		foreach ($option as $key => $val) {
			echo "<option value=\"".$key."\""; 
			if(is_array($field)&&isset($field[$i]) && $key == $field[$i]) echo "selected";
			echo ">". $val ."</option>\n";
		}
		echo "</select>&nbsp;";

		
		echo "</td></tr>";
	}
	echo "</table>";
	echo "</td>";

	echo "<td>";
	
	echo $lang["search"][4];
	echo "&nbsp;<select name='sort' size='1'>";
	reset($option);
	foreach ($option as $key => $val) {
		echo "<option value=\"".$key."\"";
		if($key == $sort) echo "selected";
		echo ">".$val."</option>\n";
	}
	echo "</select> ";
	echo "</td><td><input type='checkbox' name='deleted' ".($deleted=='Y'?" checked ":"").">";
	echo "<img src=\"".$HTMLRel."pics/showdeleted.png\" alt='".$lang["common"][3]."' title='".$lang["common"][3]."'>";
	echo "</td><td width='80' align='center' class='tab_bg_2'>";
	echo "<input type='submit' value=\"".$lang["buttons"][0]."\" class='submit'>";
	echo "</td></tr></table></div></form>";
}


function showPeripheralList($target,$username,$field,$phrasetype,$contains,$sort,$order,$start,$deleted,$link) {

	// Lists peripheral

	GLOBAL $cfg_install, $cfg_layout, $cfg_features, $lang, $HTMLRel;

	$db = new DB;

	$where ="";
	
	foreach ($field as $k => $f)
	if ($k<$_SESSION["glpisearchcount"])
	if ($contains[$k]==""){
		if ($k>0) $where.=" ".$link[$k]." ";
		$where.=" ('1'='1') ";
		}
	else {
		if ($k>0) $where.=" ".$link[$k]." ";
		$where.="( ";
		// Build query
		if($f=="all") {
			$fields = $db->list_fields("glpi_peripherals");
			$columns = $db->num_fields($fields);
		
			for ($i = 0; $i < $columns; $i++) {
				if($i != 0) {
				$where .= " OR ";
				}
				$coco = $db->field_name($fields, $i);	

				if($coco == "location") {
					$where .= getRealSearchForTreeItem("glpi_dropdown_locations",$contains[$k]);
				}
				elseif($coco == "type") {
					$where .= " glpi_type_peripherals.name LIKE '%".$contains[$k]."%'";
				}
				else {
 	  				$where .= "periph.".$coco . " LIKE '%".$contains[$k]."%'";
				}
			}
			$where.=" OR glpi_enterprises.name LIKE '%".$contains[$k]."%'";
			$where .= " OR resptech.name LIKE '%".$contains[$k]."%'";
			$where .= getInfocomSearchToViewAllRequest($contains[$k]);
			$where .= getContractSearchToViewAllRequest($contains[$k]);
		}
		else {
			if ($f=="glpi_dropdown_locations.name"){
				$where .= getRealSearchForTreeItem("glpi_dropdown_locations",$contains[$k]);
			}		
			else if ($phrasetype == "contains") {
				$where .= "($f LIKE '%".$contains[$k]."%')";
			}
			else {
				$where .= "($f LIKE '".$contains[$k]."')";
			}
		}
	$where.=" )";
	}

	if (!$start) {
		$start = 0;
	}
	if (!$order) {
		$order = "ASC";
	}
	$query = "select DISTINCT periph.ID from glpi_peripherals as periph LEFT JOIN glpi_dropdown_locations on periph.location=glpi_dropdown_locations.ID ";
	$query .= " LEFT JOIN glpi_type_peripherals on periph.type = glpi_type_peripherals.ID ";
	$query.= " LEFT JOIN glpi_enterprises ON (glpi_enterprises.ID = periph.FK_glpi_enterprise ) ";
	$query.= " LEFT JOIN glpi_users as resptech ON (resptech.ID = periph.tech_num ) ";
	$query.= getInfocomSearchToRequest("periph",PERIPHERAL_TYPE);
	$query.= getContractSearchToRequest("periph",PERIPHERAL_TYPE);
	$query.= " where ";
	if (!empty($where)) $query .= " $where AND ";
	$query .= " periph.deleted='$deleted' AND periph.is_template = '0'  ORDER BY $sort $order";


	// Get it from database	
	if ($result = $db->query($query)) {
		$numrows =  $db->numrows($result);

		// Limit the result, if no limit applies, use prior result
		if ($numrows > $cfg_features["list_limit"]) {
			$query_limit = $query ." LIMIT $start,".$cfg_features["list_limit"]." ";
			$result_limit = $db->query($query_limit);
			$numrows_limit = $db->numrows($result_limit);
		} else {
			$numrows_limit = $numrows;
			$result_limit = $result;
		}
		

		if ($numrows_limit>0) {
			// Pager
			$parameters="sort=$sort&amp;order=$order".getMultiSearchItemForLink("field",$field).getMultiSearchItemForLink("link",$link).getMultiSearchItemForLink("contains",$contains);
			printPager($start,$numrows,$target,$parameters);

			// Produce headline
			echo "<center><table  class='tab_cadre'><tr>";
			// Name
			echo "<th>";
			if ($sort=="periph.name") {
				if ($order=="DESC") echo "<img src=\"".$HTMLRel."pics/puce-down.png\" alt='' title=''>";
				else echo "<img src=\"".$HTMLRel."pics/puce-up.png\" alt='' title=''>";
			}
			echo "<a href=\"$target?sort=periph.name&amp;order=".($order=="ASC"?"DESC":"ASC")."&amp;start=$start".getMultiSearchItemForLink("field",$field).getMultiSearchItemForLink("link",$link).getMultiSearchItemForLink("contains",$contains)."\">";
			echo $lang["peripherals"][5]."</a></th>";
			
			// State		
			echo "<th>".$lang["state"][0]."</th>";
			
			// Manufacturer		
			echo "<th>";
			if ($sort=="glpi_enterprises.name") {
				if ($order=="DESC") echo "<img src=\"".$HTMLRel."pics/puce-down.png\" alt='' title=''>";
				else echo "<img src=\"".$HTMLRel."pics/puce-up.png\" alt='' title=''>";
			}
			echo "<a href=\"$target?sort=glpi_enterprises.name&amp;order=".($order=="ASC"?"DESC":"ASC")."&amp;start=$start".getMultiSearchItemForLink("field",$field).getMultiSearchItemForLink("link",$link).getMultiSearchItemForLink("contains",$contains)."\">";
			echo $lang["common"][5]."</a></th>";
			
			// Location			
			echo "<th>";
			if ($sort=="glpi_dropdown_locations.name") {
				if ($order=="DESC") echo "<img src=\"".$HTMLRel."pics/puce-down.png\" alt='' title=''>";
				else echo "<img src=\"".$HTMLRel."pics/puce-up.png\" alt='' title=''>";
			}
			echo "<a href=\"$target?sort=glpi_dropdown_locations.name&amp;order=".($order=="ASC"?"DESC":"ASC")."&amp;start=$start".getMultiSearchItemForLink("field",$field).getMultiSearchItemForLink("link",$link).getMultiSearchItemForLink("contains",$contains)."\">";
			echo $lang["peripherals"][6]."</a></th>";

			// Type
			echo "<th>";
			if ($sort=="glpi_type_peripherals.name") {
				if ($order=="DESC") echo "<img src=\"".$HTMLRel."pics/puce-down.png\" alt='' title=''>";
				else echo "<img src=\"".$HTMLRel."pics/puce-up.png\" alt='' title=''>";
			}
			echo "<a href=\"$target?sort=glpi_type_peripherals.name&amp;order=".($order=="ASC"?"DESC":"ASC")."&amp;start=$start".getMultiSearchItemForLink("field",$field).getMultiSearchItemForLink("link",$link).getMultiSearchItemForLink("contains",$contains)."\">";
			echo $lang["peripherals"][9]."</a></th>";

			// Last modified		
			echo "<th>";
			if ($sort=="periph.date_mod") {
				if ($order=="DESC") echo "<img src=\"".$HTMLRel."pics/puce-down.png\" alt='' title=''>";
				else echo "<img src=\"".$HTMLRel."pics/puce-up.png\" alt='' title=''>";
			}
			echo "<a href=\"$target?sort=periph.date_mod&amp;order=".($order=="ASC"?"DESC":"ASC")."&amp;start=$start".getMultiSearchItemForLink("field",$field).getMultiSearchItemForLink("link",$link).getMultiSearchItemForLink("contains",$contains)."\">";
			echo $lang["peripherals"][16]."</a></th>";

			// Contact person
			echo "<th>";
			if ($sort=="periph.contact") {
				if ($order=="DESC") echo "<img src=\"".$HTMLRel."pics/puce-down.png\" alt='' title=''>";
				else echo "<img src=\"".$HTMLRel."pics/puce-up.png\" alt='' title=''>";
			}
			echo "<a href=\"$target?sort=periph.contact&amp;order=".($order=="ASC"?"DESC":"ASC")."&amp;start=$start".getMultiSearchItemForLink("field",$field).getMultiSearchItemForLink("link",$link).getMultiSearchItemForLink("contains",$contains)."\">";
			echo $lang["peripherals"][8]."</a></th>";

			echo "</tr>";

			for ($i=0; $i < $numrows_limit; $i++) {
				$ID = $db->result($result_limit, $i, "ID");
				$mon = new Peripheral;
				$mon->getfromDB($ID);
				$state=new StateItem;
				$state->getfromDB(PERIPHERAL_TYPE,$ID);
				echo "<tr class='tab_bg_2'>";
				echo "<td><b>";
				echo "<a href=\"".$cfg_install["root"]."/peripherals/peripherals-info-form.php?ID=$ID\">";
				echo $mon->fields["name"]." (".$mon->fields["ID"].")";
				echo "</a></b></td>";
				echo "<td>".getDropdownName("glpi_dropdown_state",$state->fields["state"])."</td>";
				echo "<td>". getDropdownName("glpi_enterprises",$mon->fields["FK_glpi_enterprise"]) ."</td>";
				echo "<td>". getDropdownName("glpi_dropdown_locations",$mon->fields["location"]) ."</td>";
				echo "<td>". getDropdownName("glpi_type_peripherals",$mon->fields["type"]) ."</td>";
				echo "<td>".$mon->fields["date_mod"]."</td>";
				echo "<td>".$mon->fields["contact"]."</td>";
				echo "</tr>";
			}

			// Close Table
			echo "</table></center>";

			// Pager
			echo "<br>";
//			$parameters="sort=$sort&amp;order=$order".getMultiSearchItemForLink("field",$field).getMultiSearchItemForLink("link",$link).getMultiSearchItemForLink("contains",$contains);
			printPager($start,$numrows,$target,$parameters);

		} else {
			echo "<center><b>".$lang["peripherals"][17]."</b></center>";
			//echo "<hr noshade>";
			//searchFormperipheral();
		}
	}
}


function showperipheralForm ($target,$ID,$withtemplate='') {

	GLOBAL $cfg_install, $cfg_layout, $lang,$HTMLRel;

	$mon = new Peripheral;

	$mon_spotted = false;

	if(empty($ID) && $withtemplate == 1) {
		if($mon->getEmpty()) $mon_spotted = true;
	} else {
		if($mon->getfromDB($ID)) $mon_spotted = true;
	}

	if($mon_spotted) {
		if(!empty($withtemplate) && $withtemplate == 2) {
			$template = "newcomp";
			$datestring = $lang["computers"][14].": ";
			$date = date("Y-m-d H:i:s");
		} elseif(!empty($withtemplate) && $withtemplate == 1) { 
			$template = "newtemplate";
			$datestring = $lang["computers"][14].": ";
			$date = date("Y-m-d H:i:s");
		} else {
			$datestring = $lang["computers"][11]." : ";
			$date = $mon->fields["date_mod"];
			$template = false;
		}


	echo "<div align='center'>";
	echo "<form method='post' name=form action=\"$target\">";
	if(strcmp($template,"newtemplate") === 0) {
		echo "<input type=\"hidden\" name=\"is_template\" value=\"1\" />";
	}

	echo "<table width='700' class='tab_cadre' cellpadding='2'>";

		echo "<tr><th align='center' >";

		
		
		if(!$template) {
			echo $lang["peripherals"][29].": ".$mon->fields["ID"];
		}elseif (strcmp($template,"newcomp") === 0) {
			echo $lang["peripherals"][30].": ".$mon->fields["tplname"];
		}elseif (strcmp($template,"newtemplate") === 0) {
			echo $lang["common"][6]."&nbsp;: <input type='text' name='tplname' value=\"".$mon->fields["tplname"]."\" size='20'>";
		}
		
		echo "</th><th  align='center'>".$datestring.$date;
		echo "</th></tr>";

	echo "<tr><td class='tab_bg_1' valign='top'>";

	echo "<table cellpadding='1' cellspacing='0' border='0'>\n";

	echo "<tr><td>".$lang["peripherals"][5].":	</td>";
	echo "<td><input type='text' name='name' value=\"".$mon->fields["name"]."\" size='20'></td>";
	echo "</tr>";

	echo "<tr><td>".$lang["peripherals"][6].": 	</td><td>";
		dropdownValue("glpi_dropdown_locations", "location", $mon->fields["location"]);
	echo "</td></tr>";

	echo "<tr class='tab_bg_1'><td>".$lang["common"][10].": 	</td><td colspan='2'>";
		dropdownUsersID( $mon->fields["tech_num"],"tech_num");
	echo "</td></tr>";
		
	echo "<tr><td>".$lang["peripherals"][7].":	</td>";
	echo "<td><input type='text' name='contact_num' value=\"".$mon->fields["contact_num"]."\" size='20'></td>";
	echo "</tr>";

	echo "<tr><td>".$lang["peripherals"][8].":	</td>";
	echo "<td><input type='text' name='contact' size='20' value=\"".$mon->fields["contact"]."\"></td>";
	echo "</tr>";

		if (!$template){
		echo "<tr><td>".$lang["reservation"][24]."&nbsp;:</td><td><b>";
		showReservationForm(PERIPHERAL_TYPE,$ID);
		echo "</b></td></tr>";
		}
		
	echo "<tr><td>".$lang["peripherals"][33]."</td><td>";
	echo "<select name='is_global'>";
	echo "<option value='0' ".(!$mon->fields["is_global"]?" selected":"").">".$lang["peripherals"][32]."</option>";
	echo "<option value='1' ".($mon->fields["is_global"]?" selected":"").">".$lang["peripherals"][31]."</option>";
	echo "</select>";
	echo "</td></tr>";
	echo "</table>";

	echo "</td>\n";	
	echo "<td class='tab_bg_1' valign='top'>";

	echo "<table cellpadding='1' cellspacing='0' border='0'>";

	echo "<tr><td>".$lang["peripherals"][9].": 	</td><td>";
		dropdownValue("glpi_type_peripherals", "type", $mon->fields["type"]);
	echo "</td></tr>";
	
	echo "<tr class='tab_bg_1'><td>".$lang["common"][5].": 	</td><td colspan='2'>";
		dropdownValue("glpi_enterprises","FK_glpi_enterprise",$mon->fields["FK_glpi_enterprise"]);
	echo "</td></tr>";
		
	echo "<tr><td>".$lang["peripherals"][18].":</td>";
	echo "<td><input type='text' size='20' name='brand' value=\"".$mon->fields["brand"]."\"></td>";
	echo "</tr>";

	
	echo "<tr><td>".$lang["peripherals"][10].":	</td>";
	echo "<td><input type='text' name='serial' size='20' value=\"".$mon->fields["serial"]."\"></td>";
	echo "</tr>";

	echo "<tr><td>".$lang["peripherals"][11].":</td>";
	echo "<td><input type='text' size='20' name='otherserial' value=\"".$mon->fields["otherserial"]."\"></td>";
	echo "</tr>";

		
		echo "<tr><td>".$lang["state"][0]."&nbsp;:</td><td><b>";
		$si=new StateItem();
		$t=0;
		if ($template) $t=1;
		$si->getfromDB(PERIPHERAL_TYPE,$mon->fields["ID"],$t);
		dropdownValue("glpi_dropdown_state", "state",$si->fields["state"]);
		echo "</b></td></tr>";
		

	
	echo "</table>";
	echo "</td>\n";	
	echo "</tr>";
	echo "<tr>";
	echo "<td class='tab_bg_1' valign='top' colspan='2'>";

	echo "<table width='100%' cellpadding='0' cellspacing='0' border='0'><tr><td valign='top'>";
	echo $lang["peripherals"][12].":	</td>";
	echo "<td align='center'><textarea cols='35' rows='4' name='comments' >".$mon->fields["comments"]."</textarea>";
	echo "</td></tr></table>";

	echo "</td>";
	echo "</tr>";
	
	echo "<tr>";

	if ($template) {

			if (empty($ID)||$withtemplate==2){
			echo "<td class='tab_bg_2' align='center' colspan='2'>\n";
			echo "<input type='hidden' name='ID' value=$ID>";
			echo "<input type='submit' name='add' value=\"".$lang["buttons"][8]."\" class='submit'>";
			echo "</td>\n";
			} else {
			echo "<td class='tab_bg_2' align='center' colspan='2'>\n";
			echo "<input type='hidden' name='ID' value=$ID>";
			echo "<input type='submit' name='update' value=\"".$lang["buttons"][7]."\" class='submit'>";
			echo "</td>\n";
			}


	} else {

		echo "<td class='tab_bg_2' valign='top'>";
		echo "<input type='hidden' name='ID' value=\"$ID\">\n";
		echo "<center><input type='submit' name='update' value=\"".$lang["buttons"][7]."\" class='submit'></center>";
		echo "</td>";
//		echo "</form>\n\n";
		echo "<td class='tab_bg_2' valign='top'>\n";
//		echo "<form action=\"$target\" method='post'>\n";
//		echo "<input type='hidden' name='ID' value=\"$ID\">\n";
		echo "<div align='center'>";
		if ($mon->fields["deleted"]=='N')
		echo "<input type='submit' name='delete' value=\"".$lang["buttons"][6]."\" class='submit'>";
		else {
		echo "<input type='submit' name='restore' value=\"".$lang["buttons"][21]."\" class='submit'>";
		
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='purge' value=\"".$lang["buttons"][22]."\" class='submit'>";
		}
		echo "</div>";
		echo "</td>";
	}
		echo "</tr>";

		echo "</table></form></div>";
	
		return true;	
	}
	else {
                echo "<div align='center'><b>".$lang["printers"][17]."</b></div>";
                echo "<hr noshade>";
                searchFormPrinters();
                return false;
        }

}


function updatePeripheral($input) {
	// Update a Peripheral in the database

	$mon = new Peripheral;
	$mon->getFromDB($input["ID"]);

	// set new date and make sure it gets updated
	$updates[0]= "date_mod";
	$mon->fields["date_mod"] = date("Y-m-d H:i:s");

	// Get all flags and fill with 0 if unchecked in form
	foreach ($mon->fields as $key => $val) {
		if (eregi("\.*flag\.*",$key)) {
			if (!isset($input[$key])) {
				$input[$key]=0;
			}
		}
	}

	// Fill the update-array with changes
	$x=1;
	foreach ($input as $key => $val) {
		if (array_key_exists($key,$mon->fields) && $mon->fields[$key] != $input[$key]) {
			$mon->fields[$key] = $input[$key];
			$updates[$x] = $key;
			$x++;
		}
	}
	if (isset($input["is_template"])&&$input["is_template"]==1)
	updateState(PERIPHERAL_TYPE,$input["ID"],$input["state"],1);
	else updateState(PERIPHERAL_TYPE,$input["ID"],$input["state"]);

	$mon->updateInDB($updates);

}

function addPeripheral($input) {
	// Add Peripheral, nasty hack until we get PHP4-array-functions
	$db=new DB;
	$mon = new Peripheral;

	$oldID=$input["ID"];

	// dump status
	$null = array_pop($input);
	$null = array_pop($input);

	// Manage state
	$state=$input["state"];
	unset($input["state"]);
 	
	// set new date.
 	$mon->fields["date_mod"] = date("Y-m-d H:i:s");
	
	// fill array for udpate
	foreach ($input as $key => $val) {
		if (!isset($mon->fields[$key]) || $mon->fields[$key] != $input[$key]) {
			$mon->fields[$key] = $input[$key];
		}
	}

	$newID=$mon->addToDB();

	
	// Add state
	if (isset($input["is_template"])&&$input["is_template"]==1)
	updateState(PERIPHERAL_TYPE,$newID,$state,1);
	else updateState(PERIPHERAL_TYPE,$newID,$state);
	
	// ADD Infocoms
	$ic= new Infocom();
	if ($ic->getFromDB(PERIPHERAL_TYPE,$oldID)){
		$ic->fields["FK_device"]=$newID;
		unset ($ic->fields["ID"]);
		$ic->addToDB();
	}
	
		// ADD Ports
	$query="SELECT ID from glpi_networking_ports WHERE on_device='$oldID' AND device_type='".PERIPHERAL_TYPE."';";
	$result=$db->query($query);
	if ($db->numrows($result)>0){
		
		while ($data=$db->fetch_array($result)){
			$np= new Netport();
			$np->getFromDB($data["ID"]);
			unset($np->fields["ID"]);
			unset($np->fields["ifaddr"]);
			unset($np->fields["ifmac"]);
			unset($np->fields["netpoint"]);
			$np->fields["on_device"]=$newID;
			$np->addToDB();
			}
	}

	// ADD Contract				
	$query="SELECT FK_contract from glpi_contract_device WHERE FK_device='$oldID' AND device_type='".PERIPHERAL_TYPE."';";
	$result=$db->query($query);
	if ($db->numrows($result)>0){
		
		while ($data=$db->fetch_array($result))
			addDeviceContract($data["FK_contract"],PERIPHERAL_TYPE,$newID);
	}
	
	// ADD Documents			
	$query="SELECT FK_doc from glpi_doc_device WHERE FK_device='$oldID' AND device_type='".PERIPHERAL_TYPE."';";
	$result=$db->query($query);
	if ($db->numrows($result)>0){
		
		while ($data=$db->fetch_array($result))
			addDeviceDocument($data["FK_doc"],PERIPHERAL_TYPE,$newID);
	}

	return $newID;
}

function deletePeripheral($input,$force=0) {
	// Delete Printer
	
	$mon = new Peripheral;
	$mon->deleteFromDB($input["ID"],$force);
	
}

function restorePeripheral($input) {
	// Restore Peripheral
	
	$ct = new Peripheral;
	$ct->restoreInDB($input["ID"]);
} 
 	
?>
