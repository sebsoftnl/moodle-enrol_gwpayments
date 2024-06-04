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
 * Payment subsystem callback implementation for enrol_gwpayments.
 *
 * File         service_provider.php
 * Encoding     UTF-8
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_gwpayments\payment;

use enrol_gwpayments\local\helper;

/**
 * Payment subsystem callback implementation for enrol_gwpayments.
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class service_provider implements \core_payment\local\callback\service_provider {

    /**
     * Generate payable data.
     *
     * This is a utility method that can modify the actual variables and modify the payment
     * amount. This is where we transform the initial cost for e.g. coupons, discounts etc etc.
     *
     * @param \stdClass $instance enrol instance.
     * @param string $paymentarea Payment area
     * @param int $itemid The enrolment instance id
     * @return stdClass
     */
    private static function generate_payabledata(\stdClass $instance, string $paymentarea, int $itemid) {
        $result = (object) [
            'amount' => $instance->cost,
            'currency' => $instance->currency,
            'accountid' => $instance->customint1,
        ];

        // See if we have a valid "coupon record" or any other need to modify our data.
        try {
            $result->amount = helper::apply_stored_coupon_on_cost($result->amount);
        } catch (\Exception $e) {
            debugging($e->getMessage(), DEBUG_DEVELOPER);
        }

        // And return result.
        return $result;
    }

    /**
     * Callback function that returns the enrolment cost and the accountid
     * for the course that $instanceid enrolment instance belongs to.
     *
     * @param string $paymentarea Payment area
     * @param int $instanceid The enrolment instance id
     * @return \core_payment\local\entities\payable
     */
    public static function get_payable(string $paymentarea, int $instanceid): \core_payment\local\entities\payable {
        global $DB;

        $instance = $DB->get_record('enrol', ['enrol' => 'gwpayments', 'id' => $instanceid], '*', MUST_EXIST);

        $payabledata = static::generate_payabledata($instance, $paymentarea, $instanceid);

        return new \core_payment\local\entities\payable($payabledata->amount, $payabledata->currency, $payabledata->accountid);
    }

    /**
     * Callback function that returns the URL of the page the user should be redirected to in the case of a successful payment.
     *
     * @param string $paymentarea Payment area
     * @param int $instanceid The enrolment instance id
     * @return \moodle_url
     */
    public static function get_success_url(string $paymentarea, int $instanceid): \moodle_url {
        global $DB;

        $courseid = $DB->get_field('enrol', 'courseid', ['enrol' => 'gwpayments', 'id' => $instanceid], MUST_EXIST);

        return new \moodle_url('/course/view.php', ['id' => $courseid]);
    }

    /**
     * Callback function that delivers what the user paid for to them.
     *
     * @param string $paymentarea
     * @param int $instanceid The enrolment instance id
     * @param int $paymentid payment id as inserted into the 'payments' table, if needed for reference
     * @param int $userid The userid the order is going to deliver to
     * @return bool Whether successful or not
     */
    public static function deliver_order(string $paymentarea, int $instanceid, int $paymentid, int $userid): bool {
        global $DB;

        $instance = $DB->get_record('enrol', ['enrol' => 'gwpayments', 'id' => $instanceid], '*', MUST_EXIST);

        $plugin = enrol_get_plugin('gwpayments');

        if ($instance->enrolperiod) {
            $timestart = time();
            $timeend   = $timestart + $instance->enrolperiod;
        } else {
            $timestart = 0;
            $timeend   = 0;
        }

        $plugin->enrol_user($instance, $userid, $instance->roleid, $timestart, $timeend);

        // This is somewhat nasty because it's not fool-proof (payments can come in MUCH later).
        // Use coupon if in session.
        if (helper::has_session_coupon()) {
            helper::use_session_coupon($paymentid);
        }

        // We will dispatch an event.
        \enrol_gwpayments\event\order_delivered::trigger_from_data($instance->id, $userid);

        return true;
    }

}
