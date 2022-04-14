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
 * Contains form to apply for PAYNL services through Sebsoft
 *
 * File         coupondelete.php
 * Encoding     UTF-8
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_gwpayments\local\coupons;

defined('MOODLE_INTERNAL') or die();

require_once($CFG->libdir . '/formslib.php');

/**
 * enrol_gwpayments\forms\coupondelete
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class coupondelete extends \moodleform {

    /**
     * form definition
     */
    public function definition() {
        global $DB;
        $a = $this->_customdata;
        if ($a->courseid > 0) {
            $a->course = $DB->get_field('course', 'fullname', array('id' => $a->courseid));
        } else {
            $a->course = get_string('entiresite', 'enrol_gwpayments');
        }
        $a->validfrom = userdate($a->validfrom);
        $a->validto = userdate($a->validto);

        $mform = $this->_form;
        $mform->setDisableShortforms(true);
        $mform->addElement('header', 'formheader', get_string('coupon:delete', 'enrol_gwpayments'));
        $mform->addElement('static', 'formstatic', '', get_string('coupon:delete:warn', 'enrol_gwpayments', $a));
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $this->add_action_buttons(true, get_string('coupon:delete', 'enrol_gwpayments'));
    }

    /**
     * Process form. This method takes care of full processing, including display,
     * of the form.
     *
     * @param int $id id from table enrol_gwpayments_coupon
     * @param string|\moodle_url $redirect the url to redirect to after processing
     * @return void
     */
    public function process_form($id, $redirect) {
        global $OUTPUT;
        if (!$this->process_post($redirect)) {
            echo $OUTPUT->header();
            echo '<div class="enrol-gwpayments-container">';
            $this->set_data(array('id' => $id));
            $this->display();
            echo '</div>';
            echo $OUTPUT->footer();
        }
    }

    /**
     * Process form post. This method takes care of processing cancellation and
     * submission of the form.
     *
     * @param string|\moodle_url $redirect the url to redirect to after processing
     * @return void
     */
    public function process_post($redirect) {
        if ($this->is_cancelled()) {
            redirect($redirect);
        }
        if (!$data = $this->get_data()) {
            return false;
        }
        global $DB;
        $DB->delete_records('enrol_gwpayments_coupon', array('id' => $data->id));
        redirect($redirect, get_string('coupon:deleted', 'enrol_gwpayments'));
    }

}
