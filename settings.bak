<?php


// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This plugin is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * local_nudge
 *
 * @author      Murray Lohoar 
 * @copyright   (c) 2020 Red Tuna Technology Ltd
 * @license     GPU
 * @package     local_pluginsbackup
 */

defined('MOODLE_INTERNAL') || die();


if ($hassiteconfig) {
    /*
     if ($CFG->branch >= 32) { // Moodle 3.2 and later.
     $section = 'email';
     } else { // Up to and including Moodle 3.1.x .
     $section = 'server';
     }
     $ADMIN->add($section, new admin_externalpage('local_mailtest',
     get_string('pluginname', 'local_mailtest'),
     new moodle_url('/local/mailtest/')
     ));
     
     */
    $settings = new admin_settingpage('local_pluginsbackup', get_string('pluginname', 'local_pluginsbackup'));
    
    $ADMIN->add('localplugins', $settings);
    
    $settings->add(new admin_setting_configcheckbox('local_pluginsbackup/active', 'Active', 'Whether plugins backup is active', '1'));
    
    $settings->add(new admin_setting_configtext('local_pluginsbackup/backup_path', 'Backup Path', 'The path to the backup folder e.g. C:\backups', ''));
    
    $settings->add(new admin_setting_configtext('local_pluginsbackup/password', 'password', 'The password to enable immediate triggering {host}/local/pluginsbackup/backup.php?password=password', ''));
    
    $section = 'localplugins';
    
    $ADMIN->add('modules', new admin_externalpage('local_pluginsbackup2',
        'Backup Additional Plugins',
        new moodle_url('/local/pluginsbackup/backup.php')
        ));
   
    
}