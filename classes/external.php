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
 * All-in-one enrolment plugin services
 *
 * File         external.php
 * Encoding     UTF-8
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_gwpayments;

use external_api;
use external_function_parameters;
use external_value;
use external_single_structure;
use external_multiple_structure;
use stdClass;
use exception;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

/**
 * All-in-one enrolment plugin services.
 *
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external extends external_api {

    /**
     * Check coupon code.
     *
     * @param string $couponcode
     * @param int $courseid
     * @param int $instanceid
     * @return stdClass
     * @throws exception
     */
    public static function check_coupon($couponcode, $courseid, $instanceid) {
        global $DB, $USER;
        // We always must pass webservice params through validate_parameters.
        $params = self::validate_parameters(
            self::check_coupon_parameters(),
                ['couponcode' => $couponcode, 'courseid' => $courseid, 'instanceid' => $instanceid]
        );

        $enrol = $DB->get_record('enrol', ['id' => $instanceid]);
        if (!$enrol) {
            throw new exception('enrol:invalid');
        }
        // Do NOT call validate context!! This performs a course login requirement where our call CANNOT rely on that.

        if (!(bool)$enrol->customint2) {
            throw new exception('coupons:disabled');
        }

        try {
            $coupon = $DB->get_record('enrol_gwpayments_coupon', array('code' => $params['couponcode']));
            if (!$coupon) {
                throw new exception('coupon:invalid');
            } else {
                if ($coupon->courseid > 0 && ((int) $coupon->courseid !== (int)$params['courseid'])) {
                    throw new exception('coupon:invalid');
                } else if ($coupon->validfrom > time()) {
                    throw new exception('coupon:invalid');
                } else if ($coupon->validto < time()) {
                    throw new exception('coupon:expired');
                } else if ($coupon->maxusage > 0 && $coupon->numused >= $coupon->maxusage) {
                    throw new exception('coupon:invalid');
                }
            }

            // Generate result.
            if ($coupon->typ === 'percentage') {
                $percentage = $coupon->value;
                $discount = intval($coupon->value * $enrol->cost) / 100;
            } else {
                $discount = floatval($coupon->value);
                $percentage = intval(100 * ($coupon->value / $enrol->cost));
            }

            $a = [
                'currency' => $enrol->currency,
                'cost' => format_float($enrol->cost, 2, true),
                'percentage' => format_float($percentage, 2, true) . '%',
                'discount' => format_float($discount, 2, true),
                'newprice' => format_float($enrol->cost - $discount, 2, true)
            ];

            $rs = (object)[
                'currency' => $enrol->currency,
                'cost' => floatval($enrol->cost),
                'percentage' => floatval($percentage),
                'discount' => floatval($discount),
                'newprice' => floatval($enrol->cost - $discount),
                'html' => get_string('coupon:newprice', 'enrol_gwpayments', $a),
                'freepass' => false
            ];

            if ((float)$rs->newprice < 0.01) {
                // New price is basically a zero payment. Check config!
                if ((bool) get_config('enrol_gwpayments', 'enablebypassinggateway')) {
                    // Free pass! Perform delivery of order BUT with 0 as payment id.
                    payment\service_provider::deliver_order('fee', $enrol->id, 0, $USER->id);
                    // Set variables.
                    $rs->freepass = true;
                    $rs->freepassredirect = payment\service_provider::get_success_url('fee', $enrol->id)->out(false);
                    \core\notification::success(get_string('enrolfreepass', 'enrol_gwpayments'));
                }
            } else {
                // Please do NOT forget to stuff this in the session.
                local\helper::store_session_coupon($coupon->code, $coupon->courseid, $params['instanceid']);
            }

            return (object)[
                'result' => true,
                'data' => $rs
            ];
        } catch (\Exception $e) {
            return (object)[
                'result' => false,
                'error' => $e->getMessage()
            ];
        }

        return $rs;
    }

    /**
     * Returns description of method parameters for check_coupon
     *
     * @return external_function_parameters
     */
    public static function check_coupon_parameters() {
        return new external_function_parameters([
            'couponcode' => new external_value(PARAM_TEXT, 'Coupon code'),
            'courseid' => new external_value(PARAM_INT, 'Course ID'),
            'instanceid' => new external_value(PARAM_INT, 'Enrol instance ID'),
        ]);
    }

    /**
     * Returns description of method return parameters for check_coupon
     *
     * @return external_function_parameters
     */
    public static function check_coupon_returns() {
        return new external_single_structure([
            'result' => new external_value(PARAM_BOOL, 'result of action'),
            'error' => new external_value(PARAM_TEXT, 'if result was false, error is here', false),
            'data' => new external_single_structure([
                'currency' => new external_value(PARAM_ALPHA, 'Currency'),
                'cost' => new external_value(PARAM_FLOAT, 'Cost'),
                'percentage' => new external_value(PARAM_FLOAT, 'Discount percentage'),
                'discount' => new external_value(PARAM_FLOAT, 'Discount amount'),
                'newprice' => new external_value(PARAM_FLOAT, 'New cost amount'),
                'html' => new external_value(PARAM_RAW, 'HTML formatted replacement for the new cost'),
                'freepass' => new external_value(PARAM_BOOL, 'Whether or not a free pass was granted', VALUE_OPTIONAL),
                'freepassredirect' => new external_value(PARAM_LOCALURL, 'Free pass redirect URL', VALUE_OPTIONAL),
            ], 'response data', false)
        ]);
    }

    /**
     * Returns courses based on search query.
     *
     * @param string $query search string
     * @return array $courses
     */
    public static function find_courses($query) {
        global $DB;

        $where = array();
        $qparams = array();
        // Dont include the SITE.
        $where[] = 'c.id <> ' . SITEID;
        $where[] = 'c.visible = 1';

        $query = "%{$query}%";
        $qwhere = [];
        $qwhere[] = $DB->sql_like('c.shortname', '?', false, false);
        $qparams[] = $query;

        $qwhere[] = $DB->sql_like('c.fullname', '?', false, false);
        $qparams[] = $query;

        $qwhere[] = $DB->sql_like('c.idnumber', '?', false, false);
        $qparams[] = $query;

        $where[] = '('.implode(' OR ', $qwhere).')';

        $sql = "SELECT id, shortname, fullname, idnumber FROM {course} c
             WHERE ".implode(" AND ", $where).
             " ORDER BY shortname ASC";
        $rs = $DB->get_recordset_sql($sql, $qparams);
        $courses = [];
        $courses[] = (object)['id' => 0, 'name' => get_string('entiresite', 'enrol_gwpayments')];
        foreach ($rs as $course) {
            $courses[] = (object)[
                'id' => $course->id,
                'name' => $course->shortname . (empty($course->idnumber) ? '' : ' ('.$course->idnumber.')')
                ];
        }
        $rs->close();

        return $courses;
    }

    /**
     * Returns description of method parameters for find_courses
     *
     * @return external_function_parameters
     */
    public static function find_courses_parameters() {
        return new external_function_parameters(array(
            'query' => new external_value(PARAM_TEXT,
                    'search string', VALUE_REQUIRED, null, NULL_NOT_ALLOWED),
        ));
    }

    /**
     * Returns description of method return parameters for find_courses
     *
     * @return external_value
     */
    public static function find_courses_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'course id'),
                    'name' => new external_value(PARAM_TEXT, 'name'),
                )
            )
        );
    }

}
