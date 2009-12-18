<?php
/*
 * @version $Id: commondbtm.class.php 9363 2009-11-26 21:02:42Z moyo $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2009 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

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
 --------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')){
   die("Sorry. You can't access directly to this file");
}

/**
 *  Common GLPI object
 */
class CommonGLPI {

   /// GLPI Item type
   var $type = -1;

   /**
    * Return the localized name of the current Type
    * Shoudl be overloaded in each new class
    *
    * @return string
    */
   static function getTypeName() {
      global $LANG;

      return $LANG['help'][30];
   }

   /**
   * Define tabs to display
   *
   *@param $ID integer ID of the item
   *@param $withtemplate is a template view ?
   *
   *@return array containing the onglets
   *
   **/
   function defineTabs($ID,$withtemplate) {
      return array();
   }

   /**
   * Show onglets
   *
   *@param $ID ID of the item to display
   *@param $withtemplate is a template view ?
   *@param $actif active onglet
   *@param $addparams array of parameters to add to URLs and ajax
   *
   *@return Nothing ()
   *
   **/
   function showTabs($ID,$withtemplate,$actif,$addparams=array()) {
      global $LANG,$CFG_GLPI;

      $target=$_SERVER['PHP_SELF'];
      $template="";
      $templatehtml="";
      if(!empty($withtemplate)) {
         $template="&withtemplate=$withtemplate";
         $templatehtml="&amp;withtemplate=$withtemplate";
      }
      $extraparamhtml="";
      $extraparam="";
      if (is_array($addparams) && count($addparams)) {
         foreach ($addparams as $key => $val) {
            $extraparamhtml.="&amp;$key=$val";
            $extraparam.="&$key=$val";
         }
      }
      if (empty($withtemplate) && $ID && $this->type) {
         echo "<div id='menu_navigate'>";
         if (isset($this->sub_type)) {
            $glpilistitems =& $_SESSION['glpilistitems'][$this->type][$this->sub_type];
            $glpilisttitle =& $_SESSION['glpilisttitle'][$this->type][$this->sub_type];
            $glpilisturl   =& $_SESSION['glpilisturl'][$this->type][$this->sub_type];
         } else {
            $glpilistitems =& $_SESSION['glpilistitems'][$this->type];
            $glpilisttitle =& $_SESSION['glpilisttitle'][$this->type];
            $glpilisturl   =& $_SESSION['glpilisturl'][$this->type];
         }
         $next=$prev=$first=$last=-1;
         $current=false;
         if (is_array($glpilistitems)) {
            $current=array_search($ID,$glpilistitems);
            if ($current!==false) {
               if (isset($glpilistitems[$current+1])) {
                  $next=$glpilistitems[$current+1];
               }
               if (isset($glpilistitems[$current-1])) {
                  $prev=$glpilistitems[$current-1];
               }
               $first=$glpilistitems[0];
               if ($first==$ID) {
                  $first= -1;
               }
               $last=$glpilistitems[count($glpilistitems)-1];
               if ($last==$ID) {
                  $last= -1;
               }
            }
         }
         $cleantarget=preg_replace("/\?id=([0-9]+)/","",$target);
         echo "<ul>";
         echo "<li><a href=\"javascript:showHideDiv('tabsbody','tabsbodyimg','".$CFG_GLPI["root_doc"].
                    "/pics/deplier_down.png','".$CFG_GLPI["root_doc"]."/pics/deplier_up.png')\">";
         echo "<img alt='' name='tabsbodyimg' src=\"".$CFG_GLPI["root_doc"]."/pics/deplier_up.png\">";
         echo "</a></li>";

         echo "<li><a href=\"".$glpilisturl."\">";
         if ($glpilisttitle) {
            if (utf8_strlen($glpilisttitle)>$_SESSION['glpidropdown_chars_limit']) {
               $glpilisttitle = utf8_substr($glpilisttitle, 0, $_SESSION['glpidropdown_chars_limit'])
                                            . "&hellip;";
            }
            echo $glpilisttitle;
         } else {
            echo $LANG['common'][53];
         }
         echo "</a>:&nbsp;</li>";

         if ($first>0) {
            echo "<li><a href='$cleantarget?id=$first$extraparamhtml'><img src=\"".
                       $CFG_GLPI["root_doc"]."/pics/first.png\" alt='".$LANG['buttons'][55].
                       "' title='".$LANG['buttons'][55]."'></a></li>";
         } else {
            echo "<li><img src=\"".$CFG_GLPI["root_doc"]."/pics/first_off.png\" alt='".
                       $LANG['buttons'][55]."' title='".$LANG['buttons'][55]."'></li>";
         }

         if ($prev>0) {
            echo "<li><a href='$cleantarget?id=$prev$extraparamhtml'><img src=\"".
                       $CFG_GLPI["root_doc"]."/pics/left.png\" alt='".$LANG['buttons'][12].
                       "' title='".$LANG['buttons'][12]."'></a></li>";
         } else {
            echo "<li><img src=\"".$CFG_GLPI["root_doc"]."/pics/left_off.png\" alt='".
                       $LANG['buttons'][12]."' title='".$LANG['buttons'][12]."'></li>";
         }

         if ($current!==false) {
            echo "<li>".($current+1) . "/" . count($glpilistitems)."</li>";
         }

         if ($next>0) {
            echo "<li><a href='$cleantarget?id=$next$extraparamhtml'><img src=\"".
                       $CFG_GLPI["root_doc"]."/pics/right.png\" alt='".$LANG['buttons'][11].
                       "' title='".$LANG['buttons'][11]."'></a></li>";
         } else {
            echo "<li><img src=\"".$CFG_GLPI["root_doc"]."/pics/right_off.png\" alt='".
                       $LANG['buttons'][11]."' title='".$LANG['buttons'][11]."'></li>";
         }

         if ($last>0) {
            echo "<li><a href='$cleantarget?id=$last$extraparamhtml'><img src=\"".
                       $CFG_GLPI["root_doc"]."/pics/last.png\" alt='".$LANG['buttons'][56].
                       "' title='".$LANG['buttons'][56]."'></a></li>";
         } else {
            echo "<li><img src=\"".$CFG_GLPI["root_doc"]."/pics/last_off.png\" alt='".
                       $LANG['buttons'][56]."' title='".$LANG['buttons'][56]."'></li>";
         }
         echo "</ul></div>";
         echo "<div class='sep'></div>";
      }
      echo "<div id='tabspanel' class='center-h'></div>";

      $active=0;
      $onglets=$this->defineTabs($ID,$withtemplate);
      $display_all=true;
      if (isset($onglets['no_all_tab'])) {
         $display_all=false;
         unset($onglets['no_all_tab']);
      }
      if (count($onglets)) {

         $tabpage=getItemTypeTabsURL($this->type);

         $tabs=array();

         foreach ($onglets as $key => $val ) {
            $tabs[$key]=array('title'=>$val,
                              'url'=>$tabpage,
                              'params'=>"target=$target&itemtype=".$this->type.
                                        "&glpi_tab=$key&id=$ID$template$extraparam");
         }
         $plug_tabs = Plugin::getTabs($target,$this, $withtemplate);
         $tabs+=$plug_tabs;
         // Not all tab for templates and if only 1 tab
         if($display_all && empty($withtemplate)
            && count($tabs)>1) {
            $tabs[-1]=array('title'=>$LANG['common'][66],
                            'url'=>$tabpage,
                            'params'=>"target=$target&itemtype=".$this->type.
                                      "&glpi_tab=-1&id=$ID$template$extraparam");
         }
         createAjaxTabs('tabspanel','tabcontent',$tabs,$actif,$this->type);
      }
   }

   /**
    * Get the search page URL for the current classe
    *
    * @param $full path or relative one
    */
   function getTabsURL($full=true) {
      return getItemTypeTabsURL(get_class($this), $full);
   }

   /**
    * Get the search page URL for the current classe
    *
    * @param $full path or relative one
    */
   function getSearchURL($full=true) {
      return getItemTypeSearchURL(get_class($this), $full);
   }

   /**
    * Get the search page URL for the current classe
    *
    * @param $full path or relative one
    */
   function getFormURL($full=true) {
      return getItemTypeFormURL(get_class($this), $full);
   }

   function show() {
      $this->showTabs(0, '', getActiveTab($this->type));

      echo "<div id='tabcontent'></div>";
      echo "<script type='text/javascript'>loadDefaultTab();</script>";
   }


}

?>