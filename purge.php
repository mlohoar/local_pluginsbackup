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

require_once (__DIR__ . '/../../config.php');
require_once (__DIR__ . '/lib.php');
if (get_config('local_pluginsbackup', 'active') == "0") {
    throw new coding_exception('Not Active');
}


$password = optional_param('password', '', PARAM_ALPHANUM);

if($password!='' && $password==get_config('local_pluginsbackup','password'))
{
    
    opcache_reset();
    echo 'OK';
    die();
}