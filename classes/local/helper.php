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
 * @copyright   2021 RvD
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_gwpayments\local;
use enrol_gwpayments\exception;

/**
 * enrol_gwpayments\local\helper
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 RvD
 * @author      RvD <helpdesk@sebsoft.nl>
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
     * Clear session data
     */
    public static function has_session_coupon() {
        global $SESSION;
        return property_exists($SESSION, 'enrol_gwpayments_coupon');
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
            'courseid' => $courseid,
        ];
    }

    /**
     * Get known session coupon data.
     *
     * @return stdClass|null
     */
    public static function get_session_coupon() {
        global $SESSION;
        if (!static::has_session_coupon()) {
            return null;
        }
        return $SESSION->enrol_gwpayments_coupon;
    }

    /**
     * Use the coupon in the session.
     *
     * @param int $paymentid not used yet, but here for future usage.
     * @return void
     */
    public static function use_session_coupon(int $paymentid = 0) {
        global $DB, $SESSION;
        if (static::has_session_coupon()) {
            $couponrecord = static::get_coupon($SESSION->enrol_gwpayments_coupon->code);
            if (is_object($couponrecord)) {
                $sql = 'UPDATE  {enrol_gwpayments_coupon} SET numused = numused + 1, timemodified = ? WHERE id = ?';
                $DB->execute($sql, [time(), $couponrecord->id]);

            }
            static::clear_session_coupon();
        }
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
            return (object)[
                'price' => $amount,
                'newprice' => $amount,
                'discount' => 0,
                'percentage' => 0,
            ];
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
        return (object)[
            'price' => $amount,
            'newprice' => $newprice,
            'discount' => $discount,
            'percentage' => $percentage,
        ];
    }

    /**
     * Track coupon usage
     *
     * @param int $enrolid
     * @param int $courseid
     * @param int $userid
     * @param string $code
     * @param float $realdiscountvalue
     * @param int $paymentid
     * @param int $verified
     */
    public static function track_coupon_usage(int $enrolid, int $courseid, int $userid,
            string $code, float $realdiscountvalue = 0, int $paymentid = 0, int $verified = 0) {
        global $DB;
        $coupon = $DB->get_record('enrol_gwpayments_coupon', ['code' => $code]);
        $coupondata = clone $coupon;
        unset($coupondata->maxusage, $coupondata->numused, $coupondata->timecreated, $coupondata->timemodified);
        $enrol = $DB->get_record('enrol', ['id' => $enrolid]);
        $record = (object)[
            'id' => 0,
            'enrolid' => $enrolid,
            'courseid' => $courseid,
            'userid' => $userid,
            'paymentid' => $paymentid,
            'couponid' => $coupon->id,
            'verified' => $verified,
            'code' => $coupon->code,
            'typ' => $coupon->typ,
            'value' => $coupon->value,
            'discount' => $realdiscountvalue,
            'originalcost' => is_null($enrol->cost) ? 0 : $enrol->cost,
            'coupondata' => json_encode($coupondata),
            'timecreated' => time(),
            'timemodified' => time(),
        ];

        $record->id = $DB->insert_record('enrol_gwpayments_cusage', $record);
    }

    /**
     * Mark tracked coupon usage as verified
     *
     * @param int $enrolid
     * @param int $courseid
     * @param int $userid
     * @param int $paymentid
     */
    public static function verify_tracked_coupon_usage(int $enrolid, int $courseid, int $userid, int $paymentid) {
        global $DB;
        $record = $DB->get_record('enrol_gwpayments_cusage', [
            'enrolid' => $enrolid,
            'courseid' => $courseid,
            'userid' => $userid,
        ]);
        if (is_object($record)) {
            $record->paymentid = $paymentid;
            $record->verified = 1;
            $record->timemodified = time();
            $DB->update_record('enrol_gwpayments_cusage', $record);
            return true;
        }
        return false;
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
