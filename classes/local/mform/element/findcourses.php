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
 * Courses selector field.
 *
 * File         findcourses.php
 * Encoding     UTF-8
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace enrol_gwpayments\local\mform\element;

defined('MOODLE_INTERNAL') or die();

use MoodleQuickForm_autocomplete;
global $CFG;

require_once($CFG->libdir . '/form/autocomplete.php');

/**
 * Form field type for choosing a course.
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class findcourses extends MoodleQuickForm_autocomplete {

    /**
     * Display only visible courses?
     * @var bool
     */
    protected $onlyvisible = true;

    /**
     * Has setValue() already been called already?
     *
     * @var bool
     */
    protected $selectedset = false;

    /**
     * Constructor.
     *
     * @param string $elementname Element name
     * @param mixed $elementlabel Label(s) for an element
     * @param array $options Options to control the element's display
     *                       Valid options are:
     *                       - multiple bool Whether or not the field accepts more than one values.
     */
    public function __construct($elementname = null, $elementlabel = null, $options = array()) {
        $validattributes = array(
            'ajax' => 'enrol_gwpayments/findcourses',
            'multiple' => true
        );
        if (array_key_exists('multiple', $options)) {
            $validattributes['multiple'] = $options['multiple'];
        }
        if (isset($options['onlyvisible'])) {
            $this->onlyvisible = (bool)$options['onlyvisible'];
        }
        $validattributes['tags'] = false;
        $validattributes['casesensitive'] = false;
        $validattributes['placeholder'] = get_string('findcourses:placeholder', 'enrol_gwpayments');
        $validattributes['noselectionstring'] = get_string('findcourses:noselectionstring', 'enrol_gwpayments');
        $validattributes['showsuggestions'] = true;
        parent::__construct($elementname, $elementlabel, array(), $validattributes);
    }

    /**
     * Set the value of this element.
     *
     * @param  string|array $value The value to set.
     * @return boolean
     */
    public function setValue($value) { // @codingStandardsIgnoreLine Can't change parent behaviour.
        global $DB;
        // The following lines SEEM to fix the issues around the autocomplete...
        // When e.g. postback of form introduces a server side validation error.
        // The result is that when this method has been called before, selection is reset to NOTHING.
        // See https://tracker.moodle.org/browse/MDL-53889 among others.
        // The autocomplete, is must say, is VERY poorly developed and not properly tested.
        if ($this->selectedset) {
            return;
        }
        $this->selectedset = true;

        $values = (array) $value;
        $ids = array();
        foreach ($values as $onevalue) {
            if (!empty($onevalue) && (!$this->optionExists($onevalue)) &&
                    ($onevalue !== '_qf__force_multiselect_submission')) {
                array_push($ids, $onevalue);
            }
        }
        if (empty($ids)) {
            return;
        }
        // Logic here is simulating API.
        $toselect = array();
        list($insql, $inparams) = $DB->get_in_or_equal($ids, SQL_PARAMS_NAMED, 'param');
        $courses = $DB->get_records_select('course', 'id '.$insql, $inparams);
        foreach ($courses as $course) {
            if ($this->onlyvisible && !$course->visible) {
                continue;
            }
            $optionname = $course->shortname . (empty($course->idnumber) ? '' : ' ('.$course->idnumber.')');
            $this->addOption($optionname, $course->id, ['selected' => 'selected']);
            array_push($toselect, $course->id);
        }
        $rs = $this->setSelected($toselect);
        return $rs;
    }

}
