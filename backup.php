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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * local_nudge
 *
 * @author Murray Lohoar
 * @copyright (c) 2020 Red Tuna Technology Ltd
 * @license GPU
 * @package local_pluginsbackup
 */
require_once (__DIR__ . '/../../config.php');
require_once (__DIR__ . '/lib.php');
if (get_config('local_pluginsbackup', 'active') == "0") {
    throw new coding_exception('Not Active');
}

$password = optional_param('password', '', PARAM_ALPHANUM);
$action = optional_param('action', '', PARAM_ALPHA);

if ($action != '' && $action != 'backup') {
    throw new coding_exception('Invalid Action');
}

if ($password != '' && $password == get_config('local_pluginsbackup', 'password')) {

    $pluginman = core_plugin_manager::instance();

    $plugininfo = $pluginman->get_plugins();

    $additional_plugins = [];

    foreach ($plugininfo as $type => $plugins) {
        foreach ($plugins as $name => $plugin) {
            if (! $plugin->is_standard()) {

                $additional_plugins[$name] = $plugin;
            }
        }
    }

    $target = get_config('local_pluginsbackup', 'backup_path');

    $subdir = optional_param('subdir', '', PARAM_SAFEPATH);

    // Clear down the entire target folder
    $error = local_pluginsbackup_recursiveDelete($target, false);

    foreach ($additional_plugins as $adplug) {
        $source = $adplug->rootdir;

        $plugindir = str_replace($CFG->dirroot, '', $source);

        $plugindir = $subdir . $plugindir;

        $error .= local_pluginsbackup_backup_folder($source, $target, $plugindir);
    }

    if ($error != '') {
        echo $error;
    }

    echo 'OK';

    die();
}

$context = context_system::instance();

require_login();

$PAGE->set_context($context);

$pluginman = core_plugin_manager::instance();

$plugininfo = $pluginman->get_plugins();

$additional_plugins = [];

foreach ($plugininfo as $type => $plugins) {
    foreach ($plugins as $name => $plugin) {
        if (! $plugin->is_standard()) {

            $additional_plugins[$name] = $plugin;
        }
    }
}

if ($action == 'backup' && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $target = get_config('local_pluginsbackup', 'backup_path');

    $subdir = optional_param('subdir', '', PARAM_SAFEPATH);

    // Remove leading \
    if (substr($subdir, 0, 1) == '\\') {
        $subdir = substr($subdir, 1);
    }

    if (substr($target, - 1) != '\\') {
        $target .= '\\';
    }

    $to_backup = [];

    foreach ($_POST as $key => $value) {
        $prefix = 'plugin_';
        if (substr($key, 0, strlen($prefix)) == $prefix) {
            $to_backup[] = $value;
        }
    }

    $results_table = new \html_table();

    $results_table->head = [
        'Type',
        'Name',
        'Target',
        'Result'
    ];

    // Clear down the entire target folder
    $error = local_pluginsbackup_recursiveDelete($target, false);

    foreach ($to_backup as $plug) {
        if (array_key_exists($plug, $additional_plugins)) {
            $plu = $additional_plugins[$plug];

            $source = $plu->rootdir;

            $plugindir = str_replace($CFG->dirroot, '', $source);

            $plugindir = $subdir . $plugindir;

            $error = local_pluginsbackup_backup_folder($source, $target, $plugindir);

            $result = $error == '' ? 'success' : $error;

            $results_table->data[] = [
                $plu->type,
                $plu->displayname,
                $plugindir,
                $result
            ];
        }
    }
}

$PAGE->set_url('/local/pluginsbackup/backup.php');

$PAGE->set_cacheable(false);
$PAGE->set_heading('Backup Additonal Plugins');

echo $OUTPUT->header();
echo $OUTPUT->heading('Plugins');

$plugin_table = new \html_table();
$plugin_table->head = [
    '',
    'Type',
    'Name',
    'Version',
    'Location'
];

$action_url = new moodle_url('/local/pluginsbackup/backup.php', [
    'action' => 'backup'
]);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo '<form action="' . $action_url . '" method="POST">';

    foreach ($additional_plugins as $name => $p) {
        $plugin_table->data[] = [
            '<input type="checkbox" name="plugin_' . $name . '" value="' . $name . '" checked >',
            $p->type,
            $p->displayname,
            $p->release,
            $p->rootdir
        ];
    }

    echo \html_writer::table($plugin_table);

    echo 'Backup to: ' . get_config('local_pluginsbackup', 'backup_path');
    echo '<input type="text" name="subdir" id=""subdir><br> ';
    echo '<br><input type="submit" value="Backup Plugins" name="submitbutton" class="btn btn-primary"><br><br>';

    $settings = new moodle_url('/admin/settings.php?section=local_pluginsbackup');

    if (get_config('local_pluginsbackup', 'password') != '') {

        $pass_url = new moodle_url('/local/pluginsbackup/backup.php', [
            'password' => get_config('local_pluginsbackup', 'password')
        ]);

        echo '<br>' . \html_writer::link($pass_url, $pass_url) . '<br>';
        $purge_url = new moodle_url('/local/pluginsbackup/purge.php', [
            'password' => get_config('local_pluginsbackup', 'password')
        ]);
        
        echo '<br>' . \html_writer::link($purge_url, $purge_url) . '<br>';
    }
    echo '<br>' . \html_writer::link($settings, 'settings') . '<br>';

    echo '</form>';
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    echo \html_writer::table($results_table);

    $start_url = new moodle_url('/local/pluginsbackup/backup.php', [
        'action' => 'backup'
    ]);

    echo \html_writer::link($start_url, 'Start again', [
        'class' => 'btn btn-primary'
    ]);
}

echo $OUTPUT->footer();
