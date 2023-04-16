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
 * @license     GNU
 * @package     local_pluginsbackup
 */

defined('MOODLE_INTERNAL') || die();


$plugin             = new stdClass();

$plugin->version    = 2023041600;
$plugin->requires   = 2018120300;
$plugin->release    = "0.2";
$plugin->component  = 'local_pluginsbackup';
$plugin->cron       = 0;
$plugin->maturity   = MATURITY_STABLE;
