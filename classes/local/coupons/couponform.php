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
 * File         edit.php
 * Encoding     UTF-8
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_gwpayments\local\coupons;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * enrol_gwpayments\forms\coupon
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class couponform extends \moodleform {

    /**
     * form definition
     */
    public function definition() {
        global $CFG;
        $path = $CFG->dirroot . '/enrol/gwpayments/classes/local/mform/element/findcourses.php';
        \MoodleQuickForm::registerElementType('findcourses', $path, '\enrol_gwpayments\local\mform\element\findcourses');

        $mform = $this->_form;
        $mform->setDisableShortforms(true);

        $instance = $this->_customdata->instance;
        $lockcourse = (isset($this->_customdata->lockcourse) ? $this->_customdata->lockcourse : null);

        if ($instance->id === 0) {
            $mform->addElement('header', 'formheader', get_string('coupon:add', 'enrol_gwpayments'));
        } else {
            $mform->addElement('header', 'formheader', get_string('coupon:edit', 'enrol_gwpayments'));
        }

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        if ($lockcourse === null) {
            $mform->addElement('findcourses', 'courseid', get_string('course'), ['multiple' => false]);
        } else {
            $mform->addElement('hidden', 'courseid', $lockcourse);
            $mform->setType('courseid', PARAM_INT);
        }

        $mform->addElement('text', 'code', get_string('couponcode', 'enrol_gwpayments'), 'maxlength="15" size="15"');
        $mform->addRule('code', get_string('couponcodemissing', 'enrol_gwpayments'), 'required', null, 'client');
        $mform->setType('code', PARAM_TEXT);

        $mform->addElement('date_selector', 'validfrom', get_string('validfrom', 'enrol_gwpayments'));
        $mform->addRule('validfrom', get_string('validfrommissing', 'enrol_gwpayments'), 'required', null, 'client');

        $mform->addElement('date_selector', 'validto', get_string('validto', 'enrol_gwpayments'));
        $mform->addRule('validto', get_string('validtomissing', 'enrol_gwpayments'), 'required', null, 'client');

        $types = array(
            'percentage' => get_string('coupontype:percentage', 'enrol_gwpayments'),
            'value' => get_string('coupontype:value', 'enrol_gwpayments'),
        );
        $mform->addElement('select', 'type', get_string('coupontype', 'enrol_gwpayments'), $types);
        $mform->setDefault('type', 'percentage');
        $mform->setType('type', PARAM_ALPHA);

        $mform->addElement('text', 'value', get_string('value', 'enrol_gwpayments'));
        $mform->addRule('value', get_string('valuemissing', 'enrol_gwpayments'), 'required', null, 'client');
        $mform->addRule('value', null, 'numeric', null, 'client');
        $mform->setType('value', PARAM_FLOAT);

        $mform->addElement('text', 'maxusage', get_string('maxusage', 'enrol_gwpayments'), 'maxlength="6" size="6"');
        $mform->addRule('maxusage', null, 'numeric', null, 'client');
        $mform->addRule('maxusage', null, 'rangelength', array(1, 6), 'client');
        $mform->setType('maxusage', PARAM_TEXT);
        $mform->setDefault('maxusage', 0);
        $mform->addHelpButton('maxusage', 'maxusage', 'enrol_gwpayments');

        $this->add_action_buttons($instance->id > 0);
    }

    /**
     * Custom validation on the server.
     *
     * @param array $data array of ("fieldname"=>value) of submitted data
     * @param array $files array of uploaded files "element_name"=>tmp_file_path
     * @return array of "element_name"=>"error_description" if there are errors,
     *         or an empty array if everything is OK (true allowed for backwards compatibility too).
     */
    public function validation($data, $files) {
        global $DB;
        $errors = parent::validation($data, $files);

        if ((int)$data['id'] === 0) {
            if ($DB->record_exists('enrol_gwpayments_coupon', array('code' => $data['code']))) {
                $errors['code'] = get_string('couponcodeexists', 'enrol_gwpayments');
            }
        }
        if ($data['validfrom'] > $data['validto']) {
            $errors['validfrom'] = get_string('validfromhigherthanvalidto', 'enrol_gwpayments');
        }

        if ($data['type'] === 'percentage') {
            if ((float)$data['value'] < 0.00) {
                $errors['value'] = get_string('err:percentage-negative', 'enrol_gwpayments');
            }
            if ((float)$data['value'] > 100.00) {
                $errors['value'] = get_string('err:percentage-exceed', 'enrol_gwpayments');
            }
        } else if ($data['type'] === 'value') {
            if ((float)$data['value'] < 0.00) {
                $errors['value'] = get_string('err:value-negative', 'enrol_gwpayments');
            }
        }

        return $errors;
    }

    /**
     * Process form. This method takes care of full processing, including display,
     * of the form.
     *
     * @param \stdClass $instance record from table enrol_gwpayments_coupon
     * @param string|\moodle_url $redirect the url to redirect to after processing
     * @return void
     */
    public function process_form($instance, $redirect) {
        global $OUTPUT;
        if (!$this->process_post($instance, $redirect)) {
            echo $OUTPUT->header();
            echo '<div class="enrol-gwpayments-container">';
            $this->set_data($instance);
            $this->display();
            echo '</div>';
            echo $OUTPUT->footer();
        }
    }

    /**
     * Process form post. This method takes care of processing cancellation and
     * submission of the form.
     *
     * @param \stdClass $instance record from table enrol_gwpayments_coupon
     * @param string|\moodle_url $redirect the url to redirect to after processing
     * @return void
     */
    public function process_post($instance, $redirect) {
        if ($this->is_cancelled()) {
            redirect($redirect);
        }
        if (!$data = $this->get_data()) {
            return false;
        }
        global $DB;
        if ($instance->id > 0) {
            $identifier = 'coupon:updated';
            $instance->courseid = $data->courseid;
            $instance->code = $data->code;
            $instance->validfrom = $data->validfrom;
            $instance->validto = $data->validto;
            $instance->typ = $data->type;
            $instance->value = $data->value;
            $instance->maxusage = $data->maxusage;
            $instance->timemodified = time();
            $DB->update_record('enrol_gwpayments_coupon', $instance);
        } else {
            $identifier = 'coupon:saved';
            unset($instance->id);
            $instance->courseid = $data->courseid;
            $instance->code = $data->code;
            $instance->validfrom = $data->validfrom;
            $instance->validto = $data->validto;
            $instance->typ = $data->type;
            $instance->value = $data->value;
            $instance->timecreated = time();
            $instance->timemodified = time();
            $instance->maxusage = $data->maxusage;
            $instance->numused = 0;
            $DB->insert_record('enrol_gwpayments_coupon', $instance);
        }
        redirect($redirect, get_string($identifier, 'enrol_gwpayments'));
    }

}
