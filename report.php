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
 * Coupon usage report
 *
 * File         report.php
 * Encoding     UTF-8
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2025 RvD
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require("../../config.php");

// Not for guests.
if (isguestuser()) {
    redirect(new moodle_url('/'));
}

$cid = optional_param('courseid', null, PARAM_INT);
$uid = optional_param('userid', null, PARAM_INT);
$pageparams = [];

if ($cid > 0) {
    $pageparams['courseid'] = $cid;
    require_course_login($cid);
    $PAGE->set_context(context_course::instance($cid));
    $PAGE->set_course($DB->get_record('course', ['id' => $cid]));
} else if ($uid > 0) {
    $pageparams['userid'] = $uid;
    require_login(null, false);
    $PAGE->set_context(context_system::instance());
} else {
    require_login(null, false);
    $PAGE->set_context(context_system::instance());
}

$pageurl = new moodle_url('/enrol/gwpayments/report.php', $pageparams);

require_capability('enrol/gwpayments:config', $PAGE->context);

$PAGE->set_url($pageurl);
$PAGE->set_heading(get_string('report:cusage', 'enrol_gwpayments'));
$PAGE->set_title(get_string('report:cusage', 'enrol_gwpayments'));
$PAGE->set_pagelayout('admin');
$PAGE->navbar->add(get_string('pluginname', 'enrol_gwpayments'));

// Since out page url is not at all 100% the same at all times we override the active url.
navigation_node::override_active_url($PAGE->url);

$renderer = $PAGE->get_renderer('enrol_gwpayments');
$controller = new \enrol_gwpayments\local\report\controller($PAGE, $OUTPUT, $renderer);
$controller->execute_request();

