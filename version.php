<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Version information for enrol_gwpayments
 *
 * File         version.php
 * Encoding     UTF-8
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 RvD
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 **/
defined('MOODLE_INTERNAL') || die();

$plugin->version     = 2025070901;
$plugin->requires    = 2024100700; // Moode 4.5+.
$plugin->component   = 'enrol_gwpayments';
$plugin->maturity    = MATURITY_STABLE;
$plugin->release     = '1.4.0 (build 2025070901)';
$plugin->dependencies = [];
$plugin->supported = [405, 500];
