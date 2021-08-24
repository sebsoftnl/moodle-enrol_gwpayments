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
 * External functions and service definitions for the Mollie payment gateway plugin.
 *
 * File         services.php
 * Encoding     UTF-8
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'enrol_gwpayments_check_coupon' => [
        'classname'   => 'enrol_gwpayments\external',
        'methodname'  => 'check_coupon',
        'classpath'   => '',
        'description' => 'Validate coupon code',
        'type'        => 'read',
        'ajax'        => true,
    ],
    'enrol_gwpayments_find_courses' => array(
        'classname'   => 'enrol_gwpayments\external',
        'methodname'  => 'find_courses',
        'classpath'   => '',
        'description' => 'Find courses.',
        'type'        => 'read',
        'ajax'        => true,
        'loginrequired' => true
    ),
];
