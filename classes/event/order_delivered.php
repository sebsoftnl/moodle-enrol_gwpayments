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
 * Event class implementation dispatched when orders are delivered.
 *
 * File         order_delivered.php
 * Encoding     UTF-8
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 **/
namespace enrol_gwpayments\event;

/**
 * enrol_gwpayments\event\order_delivered
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class order_delivered extends \core\event\base {

    /**
     * Initialise required event data properties.
     */
    protected function init() {
        $this->data['objecttable'] = 'enrol';
        $this->data['crud'] = 'u';
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }

    /**
     * Returns localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('event:order:delivered', 'enrol_gwpayments');
    }

    /**
     * Returns non-localised event description with id's for admin use only.
     *
     * @return string
     */
    public function get_description() {
        $msg = "The (enrolment) order with id '{$this->objectid}' has been delivered to user with id '{$this->relateduserid}'";
        return $msg . '.';
    }

    /**
     * Custom validation.
     *
     * @throws \coding_exception
     * @return void
     */
    protected function validate_data() {
        parent::validate_data();

        if (!isset($this->relateduserid)) {
            debugging('The \'relateduserid\' value must be specified in the event.', DEBUG_DEVELOPER);
            $this->relateduserid = $this->userid;
        }
    }

    /**
     * Create event from given data
     *
     * @param int $objectid
     * @param int $userid
     * @param \context|null $context
     * @return static
     */
    public static function create_from_data($objectid, $userid, $context = null) {
        global $DB;
        if ($context === null) {
            $record = $DB->get_record('enrol', ['id' => $objectid]);
            $context = \context_course::instance($record->courseid);
        }
        $self = static::create([
            'objectid' => $objectid,
            'userid' => $userid,
            'relateduserid' => $userid,
            'context' => $context,
        ]);
        return $self;
    }

    /**
     * Trigger event from given data
     *
     * @param int $objectid
     * @param int $userid
     * @param \context|null $context
     */
    public static function trigger_from_data($objectid, $userid, $context = null) {
        $self = static::create_from_data($objectid, $userid, $context);
        $self->trigger();
    }

}
