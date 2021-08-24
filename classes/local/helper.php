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
 * helper.
 *
 * File         helper.php
 * Encoding     UTF-8
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_gwpayments\local;

defined('MOODLE_INTERNAL') || die();

/**
 * enrol_gwpayments\local\helper
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class helper {

    /**
     * Find coupon record by code.
     *
     * @param string $code
     * @return stdClass|false
     */
    public static function get_coupon($code) {
        global $DB;
        $coupon = $DB->get_record('enrol_gwpayments_coupon', ['code' => $code]);
        return $coupon;
    }

    /**
     * Clear session data
     */
    public static function clear_session_coupon() {
        global $SESSION;
        unset($SESSION->enrol_gwpayments_coupon);
    }

    /**
     * Store last known valid coupon code in session.
     *
     * @param string $code
     * @param int $courseid
     * @param int $instanceid
     */
    public static function store_session_coupon($code, $courseid, $instanceid) {
        global $SESSION;
        $SESSION->enrol_gwpayments_coupon = (object)[
            'code' => $code,
            'component' => 'enrol_gwpayments',
            'paymentarea' => 'fee',
            'itemid' => $instanceid,
            'courseid' => $courseid
        ];
    }

    /**
     * Apply coupon code on given amount
     *
     * @param float $amount
     * @return float
     * @throws exception
     */
    public static function apply_stored_coupon_on_cost($amount) {
        global $SESSION;
        if (!isset($SESSION->enrol_gwpayments_coupon)) {
            return $amount;
        }

        $applydata = $SESSION->enrol_gwpayments_coupon;
        $coupon = static::get_coupon($applydata->code);
        if (!$coupon) {
            throw new exception('coupon:invalid');
        } else {
            if ($coupon->courseid > 0 && ((int) $coupon->courseid !== (int)$applydata->courseid)) {
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
            $discount = intval($coupon->value * $amount) / 100;
        } else {
            $discount = floatval($coupon->value);
            $percentage = intval(100 * ($coupon->value / $amount));
        }

        $newprice = unformat_float(format_float($amount - $discount, 2, true));
        return $newprice;
    }

    /**
     * Remove expired coupons from database.
     */
    public static function expire_coupons() {
        global $DB;
        $lifetime = 24 * HOURSECS;
        $DB->execute('DELETE FROM {enrol_gwpayments_coupon} WHERE validto < ?', [time() - $lifetime]);
    }

}
