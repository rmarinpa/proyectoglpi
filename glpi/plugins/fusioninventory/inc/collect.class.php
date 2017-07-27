<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the collect (registry, file, wmi) module of
 * the agents
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage the collect information by the agent.
 */
class PluginFusioninventoryCollect extends CommonDBTM {

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_collect';


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb=0) {
      return __('Collect information', 'fusioninventory');
   }



   /**
    * Get the tab name used for item
    *
    * @param object $item the item object
    * @param integer $withtemplate 1 if is a template form
    * @return string name of the tab
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      return '';
   }



   /**
    * Display the content of the tab
    *
    * @param object $item
    * @param integer $tabnum number of the tab to display
    * @param integer $withtemplate 1 if is a template form
    * @return boolean
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      return FALSE;
   }



   /**
    * Get all collect types
    *
    * @return array [name] => description
    */
   static function getTypes() {
      $elements             = array();
      $elements['registry'] = __('Registry', 'fusioninventory');
      $elements['wmi']      = __('WMI', 'fusioninventory');
      $elements['file']     = __('Find file', 'fusioninventory');

      return $elements;
   }



   /**
    * Add search options
    *
    * @return array
    */
   static function getSearchOptionsToAdd() {
      $tab = array();

      $i = 5200;

      $pfCollect = new PluginFusioninventoryCollect();
      foreach ($pfCollect->find(getEntitiesRestrictRequest("", $pfCollect->getTable())) as $collect) {

         //registries
         $pfCollect_Registry = new PluginFusioninventoryCollect_Registry();
         $registries = $pfCollect_Registry->find('plugin_fusioninventory_collects_id = ' . $collect['id']);
         foreach ($registries as $registry) {
            $tab[$i]['table']         = 'glpi_plugin_fusioninventory_collects_registries_contents';
            $tab[$i]['field']         = 'value';
            $tab[$i]['linkfield']     = '';
            $tab[$i]['name']          = __('Registry', 'fusioninventory')." - ".$registry['name'];
            $tab[$i]['joinparams']    = array('jointype' => 'child');
            $tab[$i]['datatype']      = 'text';
            $tab[$i]['forcegroupby']  = true;
            $tab[$i]['massiveaction'] = false;
            $tab[$i]['nodisplay']     = true;
            $tab[$i]['joinparams']    = array('condition' => "AND NEWTABLE.`plugin_fusioninventory_collects_registries_id` = ".$registry['id'],
                                          'jointype' => 'child');
            $i++;
         }

         //WMIs
         $pfCollect_Wmi = new PluginFusioninventoryCollect_Wmi();
         $wmis = $pfCollect_Wmi->find('plugin_fusioninventory_collects_id  = ' . $collect['id']);
         foreach ($wmis as $wmi) {
            $tab[$i]['table']         = 'glpi_plugin_fusioninventory_collects_wmis_contents';
            $tab[$i]['field']         = 'value';
            $tab[$i]['linkfield']     = '';
            $tab[$i]['name']          = __('WMI', 'fusioninventory')." - ".$wmi['name'];
            $tab[$i]['joinparams']    = array('jointype' => 'child');
            $tab[$i]['datatype']      = 'text';
            $tab[$i]['forcegroupby']  = true;
            $tab[$i]['massiveaction'] = false;
            $tab[$i]['nodisplay']     = true;
            $tab[$i]['joinparams']    = array('condition' => "AND NEWTABLE.`plugin_fusioninventory_collects_wmis_id` = ".$wmi['id'],
                                          'jointype' => 'child');
            $i++;
         }

         //Files
         $pfCollect_File = new PluginFusioninventoryCollect_File();
         $files = $pfCollect_File->find('plugin_fusioninventory_collects_id = ' . $collect['id']);
         foreach ($files as $file) {

            $tab[$i]['table']         = 'glpi_plugin_fusioninventory_collects_files_contents';
            $tab[$i]['field']         = 'pathfile';
            $tab[$i]['linkfield']     = '';
            $tab[$i]['name']          = __('Find file', 'fusioninventory').
                                    " - ".$file['name'].
                                    " - ".__('pathfile', 'fusioninventory');
            $tab[$i]['joinparams']    = array('jointype' => 'child');
            $tab[$i]['datatype']      = 'text';
            $tab[$i]['forcegroupby']  = true;
            $tab[$i]['massiveaction'] = false;
            $tab[$i]['nodisplay']     = true;
            $tab[$i]['joinparams']    = array('condition' => "AND NEWTABLE.`plugin_fusioninventory_collects_files_id` = ".$file['id'],
                                          'jointype' => 'child');
            $i++;

            $tab[$i]['table']         = 'glpi_plugin_fusioninventory_collects_files_contents';
            $tab[$i]['field']         = 'size';
            $tab[$i]['linkfield']     = '';
            $tab[$i]['name']          = __('Find file', 'fusioninventory').
                                    " - ".$file['name'].
                                    " - ".__('Size', 'fusioninventory');
            $tab[$i]['joinparams']    = array('jointype' => 'child');
            $tab[$i]['datatype']      = 'text';
            $tab[$i]['forcegroupby']  = true;
            $tab[$i]['massiveaction'] = false;
            $tab[$i]['nodisplay']     = true;
            $tab[$i]['joinparams']    = array('condition' => "AND NEWTABLE.`plugin_fusioninventory_collects_files_id` = ".$file['id'],
                                          'jointype' => 'child');
            $i++;
         }
      }
      return $tab;
   }



   /**
    * Display form
    *
    * @param integer $ID
    * @param array $options
    * @return true
    */
   function showForm($ID, $options=array()) {

      $this->initForm($ID, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Name');
      echo "</td>";
      echo "<td>";
      Html::autocompletionTextField($this,'name');
      echo "</td>";
      echo "<td>".__('Type')."</td>";
      echo "<td>";
      Dropdown::showFromArray('type',
                              PluginFusioninventoryCollect::getTypes(),
                              array('value' => $this->fields['type']));
      echo "</td>";
      echo "</tr>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Comments');
      echo "</td>";
      echo "<td class='middle'>";
      echo "<textarea cols='45' rows='3' name='comment' >".
              $this->fields["comment"]."</textarea>";
      echo "</td>";
      echo "<td>".__('Active')."</td>";
      echo "<td>";
      Dropdown::showYesNo('is_active', $this->fields['is_active']);
      echo "</td>";
      echo "</tr>\n";

      $this->showFormButtons($options);

      return TRUE;
   }



   /**
    * Prepare run, so it prepare the taskjob with module 'collect'.
    * It prepare collect information and computer list for task run
    *
    * @global object $DB
    * @param integer $taskjobs_id id of taskjob
    */
   function prepareRun($taskjobs_id) {
      global $DB;

      $task       = new PluginFusioninventoryTask();
      $job        = new PluginFusioninventoryTaskjob();
      $joblog     = new PluginFusioninventoryTaskjoblog();
      $jobstate   = new PluginFusioninventoryTaskjobstate();
      $agent      = new PluginFusioninventoryAgent();

      $job->getFromDB($taskjobs_id);
      $task->getFromDB($job->fields['plugin_fusioninventory_tasks_id']);

      $communication = $task->fields['communication'];
      $actions       = importArrayFromDB($job->fields['action']);
      $definitions   = importArrayFromDB($job->fields['definition']);
      $taskvalid     = 0;

      $computers = array();
      foreach ($actions as $action) {
         $itemtype = key($action);
         $items_id = current($action);

         switch($itemtype) {

            case 'Computer':
               $computers[] = $items_id;
               break;

            case 'Group':
               $computer_object = new Computer();

               //find computers by user associated with this group
               $group_users   = new Group_User();
               $group         = new Group();
               $group->getFromDB($items_id);

               $computers_a_1 = array();
               $computers_a_2 = array();

               //array_keys($group_users->find("groups_id = '$items_id'"));
               $members = $group_users->getGroupUsers($items_id);

               foreach ($members as $member) {
                  $computers = $computer_object->find("users_id = '${member['id']}'");
                  foreach ($computers as $computer) {
                     $computers_a_1[] = $computer['id'];
                  }
               }

               //find computers directly associated with this group
               $computers = $computer_object->find("groups_id = '$items_id'");
               foreach ($computers as $computer) {
                  $computers_a_2[] = $computer['id'];
               }

               //merge two previous array and deduplicate entries
               $computers = array_unique(array_merge($computers_a_1, $computers_a_2));
               break;

            case 'PluginFusioninventoryDeployGroup':
               $group = new PluginFusioninventoryDeployGroup;
               $group->getFromDB($items_id);

               switch ($group->getField('type')) {

                  case 'STATIC':
                     $query = "SELECT items_id
                     FROM glpi_plugin_fusioninventory_deploygroups_staticdatas
                     WHERE groups_id = '$items_id'
                     AND itemtype = 'Computer'";
                     $res = $DB->query($query);
                     while ($row = $DB->fetch_assoc($res)) {
                        $computers[] = $row['items_id'];
                     }
                     break;

                  case 'DYNAMIC':
                     $query = "SELECT fields_array
                     FROM glpi_plugin_fusioninventory_deploygroups_dynamicdatas
                     WHERE groups_id = '$items_id'
                     LIMIT 1";
                     $res = $DB->query($query);
                     $row = $DB->fetch_assoc($res);

                     if (isset($_GET)) {
                        $get_tmp = $_GET;
                     }
                     if (isset($_SESSION["glpisearchcount"]['Computer'])) {
                        unset($_SESSION["glpisearchcount"]['Computer']);
                     }
                     if (isset($_SESSION["glpisearchcount2"]['Computer'])) {
                        unset($_SESSION["glpisearchcount2"]['Computer']);
                     }

                     $_GET = importArrayFromDB($row['fields_array']);

                     $_GET["glpisearchcount"] = count($_GET['field']);
                     if (isset($_GET['field2'])) {
                        $_GET["glpisearchcount2"] = count($_GET['field2']);
                     }

                     $pfSearch = new PluginFusioninventorySearch();
                     Search::manageGetValues('Computer');
                     $glpilist_limit = $_SESSION['glpilist_limit'];
                     $_SESSION['glpilist_limit'] = 999999999;
                     $result = $pfSearch->constructSQL('Computer',
                                                       $_GET);
                     $_SESSION['glpilist_limit'] = $glpilist_limit;
                     while ($data=$DB->fetch_array($result)) {
                        $computers[] = $data['id'];
                     }
                     if (count($get_tmp) > 0) {
                        $_GET = $get_tmp;
                     }
                     break;

               }
               break;

         }
      }

      $c_input= array();
      $c_input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
      $c_input['state']                              = 0;
      $c_input['plugin_fusioninventory_agents_id']   = 0;
      $c_input['execution_id']                       = $task->fields['execution_id'];

      $pfCollect = new PluginFusioninventoryCollect();

      foreach ($computers as $computer_id) {
         //get agent if for this computer
         $agents_id = $agent->getAgentWithComputerid($computer_id);
         if ($agents_id === FALSE) {
            $jobstates_id = $jobstate->add($c_input);
            $jobstate->changeStatusFinish($jobstates_id,
                                          0,
                                          '',
                                          1,
                                          "No agent found for [[Computer::".$computer_id."]]");
         } else {
            foreach ($definitions as $definition) {
               $pfCollect->getFromDB($definition['PluginFusioninventoryCollect']);

               switch ($pfCollect->fields['type']) {

                  case 'registry':
                     // get all registry
                     $pfCollect_Registry = new PluginFusioninventoryCollect_Registry();
                     $a_registries = $pfCollect_Registry->find(
                             "`plugin_fusioninventory_collects_id`='".
                             $pfCollect->fields['id']."'");
                     foreach ($a_registries as $data_r) {
                        $uniqid= uniqid();
                        $c_input['state'] = 0;
                        $c_input['itemtype'] = 'PluginFusioninventoryCollect_Registry';
                        $c_input['items_id'] = $data_r['id'];
                        $c_input['date'] = date("Y-m-d H:i:s");
                        $c_input['uniqid'] = $uniqid;

                        $c_input['plugin_fusioninventory_agents_id'] = $agents_id;

                        # Push the agent, in the stack of agent to awake
                        if ($communication == "push") {
                           $_SESSION['glpi_plugin_fusioninventory']['agents'][$agents_id] = 1;
                        }

                        $jobstates_id= $jobstate->add($c_input);

                        //Add log of taskjob
                        $c_input['plugin_fusioninventory_taskjobstates_id'] = $jobstates_id;
                        $c_input['state']= PluginFusioninventoryTaskjoblog::TASK_PREPARED;
                        $taskvalid++;
                        $joblog->add($c_input);
                     }
                     break;

                  case 'wmi':
                     // get all wmi
                     $pfCollect_Wmi = new PluginFusioninventoryCollect_Wmi();
                     $a_wmies = $pfCollect_Wmi->find(
                             "`plugin_fusioninventory_collects_id`='".
                             $pfCollect->fields['id']."'");
                     foreach ($a_wmies as $data_r) {
                        $uniqid= uniqid();
                        $c_input['state'] = 0;
                        $c_input['itemtype'] = 'PluginFusioninventoryCollect_Wmi';
                        $c_input['items_id'] = $data_r['id'];
                        $c_input['date'] = date("Y-m-d H:i:s");
                        $c_input['uniqid'] = $uniqid;

                        $c_input['plugin_fusioninventory_agents_id'] = $agents_id;

                        # Push the agent, in the stack of agent to awake
                        if ($communication == "push") {
                           $_SESSION['glpi_plugin_fusioninventory']['agents'][$agents_id] = 1;
                        }

                        $jobstates_id= $jobstate->add($c_input);

                        //Add log of taskjob
                        $c_input['plugin_fusioninventory_taskjobstates_id'] = $jobstates_id;
                        $c_input['state']= PluginFusioninventoryTaskjoblog::TASK_PREPARED;
                        $taskvalid++;
                        $joblog->add($c_input);
                     }
                     break;

                  case 'file':
                     // find files
                     $pfCollect_File = new PluginFusioninventoryCollect_File();
                     $a_files = $pfCollect_File->find(
                             "`plugin_fusioninventory_collects_id`='".
                             $pfCollect->fields['id']."'");
                     foreach ($a_files as $data_r) {
                        $uniqid= uniqid();
                        $c_input['state'] = 0;
                        $c_input['itemtype'] = 'PluginFusioninventoryCollect_File';
                        $c_input['items_id'] = $data_r['id'];
                        $c_input['date'] = date("Y-m-d H:i:s");
                        $c_input['uniqid'] = $uniqid;

                        $c_input['plugin_fusioninventory_agents_id'] = $agents_id;

                        # Push the agent, in the stack of agent to awake
                        if ($communication == "push") {
                           $_SESSION['glpi_plugin_fusioninventory']['agents'][$agents_id] = 1;
                        }

                        $jobstates_id= $jobstate->add($c_input);

                        //Add log of taskjob
                        $c_input['plugin_fusioninventory_taskjobstates_id'] = $jobstates_id;
                        $c_input['state']= PluginFusioninventoryTaskjoblog::TASK_PREPARED;
                        $taskvalid++;
                        $joblog->add($c_input);
                     }
                     break;


               }
            }
         }
      }

      if ($taskvalid > 0) {
         $job->fields['status']= 1;
         $job->update($job->fields);
      } else {
         $job->reinitializeTaskjobs($job->fields['plugin_fusioninventory_tasks_id']);
      }
   }



   /**
    * run function, so return data to send to the agent for collect information
    *
    * @param object $taskjobstate PluginFusioninventoryTaskjobstate instance
    * @param array $agent agent information from agent table in database
    * @return array
    */
   function run($taskjobstate, $agent) {
      $output = array();

      $this->getFromDB($taskjobstate->fields['items_id']);

      switch ($this->fields['type']) {

         case 'registry':
            $pfCollect_Registry = new PluginFusioninventoryCollect_Registry();
            $reg_db = $pfCollect_Registry->find("`plugin_fusioninventory_collects_id`='".$this->fields['id']."'");
            foreach ($reg_db as $reg) {
               $output[] = array(
                   'function' => 'getFromRegistry',
                   'path'     => $reg['hive'].$reg['path'].$reg['key'],
                   'uuid'     => $taskjobstate->fields['uniqid'],
                   '_sid'     => $reg['id']);
            }
            break;

         case 'wmi':
            $pfCollect_Wmi = new PluginFusioninventoryCollect_Wmi();
            $wmi_db = $pfCollect_Wmi->find("`plugin_fusioninventory_collects_id`='".$this->fields['id']."'");
            foreach ($wmi_db as $wmi) {
               $datawmi = array(
                   'function'   => 'getFromWMI',
                   'class'      => $wmi['class'],
                   'properties' => array($wmi['properties']),
                   'uuid'       => $taskjobstate->fields['uniqid'],
                   '_sid'       => $wmi['id']);
               if ($wmi['moniker'] != '') {
                  $datawmi['moniker'] = $wmi['moniker'];
               }
               $output[] = $datawmi;
            }
            break;

         case 'file':
            $pfCollect_File = new PluginFusioninventoryCollect_File();
            $files_db = $pfCollect_File->find("`plugin_fusioninventory_collects_id`='".$this->fields['id']."'");
            foreach ($files_db as $files) {
               $datafile = array();
               $datafile['function'] = 'findFile';
               $datafile['dir'] = $files['dir'];
               $datafile['limit'] = $files['limit'];
               $datafile['recursive'] = $files['is_recursive'];
               $datafile['filter'] = array();
               if ($files['filter_regex'] != '') {
                  $datafile['filter']['regex'] = $files['filter_regex'];
               }
               if ($files['filter_sizeequals'] > 0) {
                  $datafile['filter']['sizeEquals'] = $files['filter_sizeequals'];
               } else if ($files['filter_sizegreater'] > 0) {
                  $datafile['filter']['sizeGreater'] = $files['filter_sizegreater'];
               } else if ($files['filter_sizelower'] > 0) {
                  $datafile['filter']['sizeLower'] = $files['filter_sizelower'];
               }
               if ($files['filter_checksumsha512'] != '') {
                  $datafile['filter']['checkSumSHA512'] = $files['filter_checksumsha512'];
               }
               if ($files['filter_checksumsha2'] != '') {
                  $datafile['filter']['checkSumSHA2'] = $files['filter_checksumsha2'];
               }
               if ($files['filter_name'] != '') {
                  $datafile['filter']['name'] = $files['filter_name'];
               }
               if ($files['filter_iname'] != '') {
                  $datafile['filter']['iname'] = $files['filter_iname'];
               }
               $datafile['filter']['is_file'] = $files['filter_is_file'];
               $datafile['filter']['is_dir'] = $files['filter_is_dir'];
               $datafile['uuid'] = $taskjobstate->fields['uniqid'];
               $datafile['_sid'] = $files['id'];
               $output[] = $datafile;
            }
            break;

      }
      return $output;
   }
}

?>
