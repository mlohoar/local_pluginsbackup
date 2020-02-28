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

require_once ($CFG->dirroot . '/local/nudge/locallib.php');

function local_pluginsbackup_extend_navigation_course($navigation, $course, $context)
{
    global $CFG;

    require_once ($CFG->libdir . '/completionlib.php');

}

function local_pluginsbackup_extend_navigation(global_navigation $navigation)
{
    global $CFG, $PAGE, $COURSE;

    $url = new moodle_url('/local/pluginsbackup/backup.php');
    $navigation->add('Plugins backup', $url, navigation_node::TYPE_SITE_ADMIN, null, null, new pix_icon('i/backup', ''));
}

function local_pluginsbackup_backup_folder(string $source, string $target, string $subdir): string
{
    //ensure trailing \
    if (substr($target, - 1)!='\\') {
        $target .= '\\';
    }

    // Check that the source exists
    if (! file_exists($source)) {
        return 'Source folder "' . $source . '" not found';
    }
    // Check that the target exists
    if (! file_exists($target)) {
        return 'Target folder "' . $target . '" not found';
    }

    //remove leading \
    if(substr($subdir,1)=='\\')
    {
        $subdir=substr($subdir,1);
    }
    
    $full_target=$target.$subdir;
    
    if(!file_exists($full_target))
    {
        mkdir($full_target,0777,true);
    }

    // Copy all the file from the source to the target

    if (substr($full_target, - 1) != '\\') {
        $full_target .= '\\';
    }

    $errors = local_pluginsbackup_recursiveDelete($full_target,false);

    // Delete all the files from the target
    if ($errors == '') {
        $errors = local_pluginsbackup_recurse_copy($source, $full_target);
    }

    return $errors;
}

function local_pluginsbackup_recursiveDelete($str, bool $delete_dir = false): string
{
    $str=str_replace('\\', '/', $str);
    
    if (! is_file($str) && ! is_dir($str)) {
        return 'File or Folder "' . $str . '" not found.';
    }

    if (is_file($str)) {
        unlink($str);
    } elseif (is_dir($str)) {
        
        $scan= glob($str . '{,.}[!.,!..]*',GLOB_MARK|GLOB_BRACE);
        foreach ($scan as $index => $path) {
            local_pluginsbackup_recursiveDelete($path, true);
        }

        if ($delete_dir) {
            rmdir($str);
        }
    }

    return '';
}

function local_pluginsbackup_recurse_copy($source, $target): string
{
    $source=str_replace('\\', '/', $source);
    $target=str_replace('\\', '/', $target);
    
    $dir = opendir($source);
    if (! file_exists($target)) {
        mkdir($target,0777,true);
    }
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($source . '/' . $file)) {
                local_pluginsbackup_recurse_copy($source . '/' . $file, $target . '/' . $file);
            } else {
                copy($source . '/' . $file, $target . '/' . $file);
            }
        }
    }
    closedir($dir);

    return '';
} 