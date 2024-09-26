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
 * this file contains the coupon table class.
 *
 * File         coupontable.php
 * Encoding     UTF-8
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 RvD
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_gwpayments\local\coupons;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/tablelib.php');

use moodle_url;
use pix_icon;

/**
 * enrol_gwpayments\tables\coupons
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 RvD
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class coupontable extends \table_sql {

    /**
     * table type identifier for all coupons
     */
    const ALL = 'all';

    /**
     * table type identifier expired coupons
     */
    const EXPIRED = 'expired';

    /**
     * table type identifier for generic status
     */
    const USED = 'used';

    /**
     * table type identifier for users to delete
     */
    const UNUSED = 'unused';

    /**
     * Course id
     *
     * @var int
     */
    protected $courseid;

    /**
     * internal display type
     *
     * @var string
     */
    protected $displaytype;

    /**
     * Create a new instance of the statustable
     *
     * @param int $courseid course id
     * @param string $type table render type
     */
    public function __construct($courseid = 0, $type = 'all') {
        global $USER;
        parent::__construct(__CLASS__ . '-' . $USER->id . '-' . $courseid . '-' . $type);
        $this->courseid = $courseid;
        $this->displaytype = $type;
        $this->collapsible(false);
        $this->no_sorting('action');
        $this->no_sorting('type');
        $this->no_sorting('numused');
        $this->no_sorting('status');
    }

    /**
     * Return a list of applicable viewtypes for this table
     *
     * @return array list of view types
     */
    public static function get_viewtypes() {
        return [
            self::ALL,
            self::EXPIRED,
            self::USED,
            self::UNUSED,
        ];
    }

    /**
     * Return a list of HTML links for viewtypes of this table.
     *
     * @param \moodle_url $url base url for the page
     * @return array list of view types
     */
    public static function get_viewtype_menu($url) {
        $rs = [];
        foreach (self::get_viewtypes() as $type) {
            $murl = clone $url;
            $murl->param('listtype', $type);
            $rs[] = '<a href="' . $murl->out() . '">' . get_string('coupon:filter:' . $type, 'enrol_gwpayments') . '</a>';
        }
        return $rs;
    }

    /**
     *
     * Set the sql to query the db.
     * This method is disabled for this class, since we use internal queries
     *
     * @param string $fields
     * @param string $from
     * @param string $where
     * @param array $params
     * @throws exception
     */
    public function set_sql($fields, $from, $where, array $params = []) {
        // We'll disable this method.
        throw new exception('err:statustable:set_sql');
    }

    /**
     * Display the general suspension status table.
     *
     * @param int $pagesize
     * @param bool $useinitialsbar
     */
    public function render($pagesize, $useinitialsbar = true) {
        switch ($this->displaytype) {
            case self::EXPIRED:
                $this->render_all($pagesize, $useinitialsbar);
                break;
            case self::USED:
                $this->render_all($pagesize, $useinitialsbar);
                break;
            case self::UNUSED:
                $this->render_all($pagesize, $useinitialsbar);
                break;
            case self::ALL:
            default:
                $this->render_all($pagesize);
                break;
        }
    }

    /**
     * Display the general suspension status table for users that haven't
     * been excluded
     *
     * @param int $pagesize
     * @param bool $useinitialsbar
     */
    protected function render_all($pagesize, $useinitialsbar = true) {
        $this->define_columns(['courseid', 'code', 'type', 'value', 'status',
            'validfrom', 'validto', 'numused', 'maxusage', 'action']);
        $this->define_headers([
            get_string('th:courseid', 'enrol_gwpayments'),
            get_string('th:code', 'enrol_gwpayments'),
            get_string('th:type', 'enrol_gwpayments'),
            get_string('th:value', 'enrol_gwpayments'),
            get_string('th:status', 'enrol_gwpayments'),
            get_string('th:validfrom', 'enrol_gwpayments'),
            get_string('th:validto', 'enrol_gwpayments'),
            get_string('th:numused', 'enrol_gwpayments'),
            get_string('th:maxusage', 'enrol_gwpayments'),
            get_string('th:action', 'enrol_gwpayments'),
        ]);
        $fields = 'c.*,NULL AS action';
        $where = [];
        $params = [];
        if ($this->courseid > 0) {
            $where[] = 'c.courseid = ?';
            $params[] = $this->courseid;
        }

        switch ($this->displaytype) {
            case self::EXPIRED:
                $where[] = 'validto < ?';
                $params[] = time();
                break;
            case self::USED:
                $where[] = 'numused > ?';
                $params[] = 0;
                break;
            case self::UNUSED:
                $where[] = 'numused = ?';
                $params[] = 0;
                break;
            case self::ALL:
            default:
                break;
        }

        if (empty($where)) {
            $where[] = '1 = ?';
            $params[] = 1;
        }
        parent::set_sql($fields, '{enrol_gwpayments_coupon} c', implode(' AND ', $where), $params);
        $this->out($pagesize, $useinitialsbar);
    }

    /**
     * Take the data returned from the db_query and go through all the rows
     * processing each col using either col_{columnname} method or other_cols
     * method or if other_cols returns NULL then put the data straight into the
     * table.
     */
    public function build_table() {
        if ($this->rawdata) {
            foreach ($this->rawdata as $row) {
                $formattedrow = $this->format_row($row);
                $this->add_data_keyed($formattedrow, $this->get_row_class($row));
            }
        }
    }

    /**
     * Render visual representation of the 'courseid' column for use in the table
     *
     * @param \stdClass $row
     * @return string time string
     */
    public function col_courseid($row) {
        global $DB;
        if ($row->courseid == 0) {
            return get_string('entiresite', 'enrol_gwpayments');
        } else {
            return $DB->get_field('course', 'fullname', ['id' => $row->courseid]);
        }
    }

    /**
     * Render visual representation of the 'type' column for use in the table
     *
     * @param \stdClass $row
     * @return localised type string
     */
    public function col_type($row) {
        return get_string('coupontype:' . $row->typ, 'enrol_gwpayments');
    }

    /**
     * Render visual representation of the 'value' column for use in the table
     *
     * @param \stdClass $row
     * @return formatted value
     */
    public function col_value($row) {
        $rs = number_format($row->value, 2);
        if ($row->typ === 'percentage') {
            $rs .= ' %';
        }
        return $rs;
    }

    /**
     * Render visual representation of the 'status' column for use in the table
     *
     * @param \stdClass $row
     * @return string time string
     */
    public function col_status($row) {
        if ($row->maxusage > 0 && $row->numused >= $row->maxusage) {
            return get_string('coupon:status:maxused', 'enrol_gwpayments');
        }
        if ($row->validfrom > time()) {
            return get_string('coupon:status:impending', 'enrol_gwpayments');
        }
        if ($row->validto < time()) {
            return get_string('coupon:status:expired', 'enrol_gwpayments');
        }
        if ($row->validto > time()) {
            return get_string('coupon:status:active', 'enrol_gwpayments');
        }
    }

    /**
     * Render visual representation of the 'validfrom' column for use in the table
     *
     * @param \stdClass $row
     * @return string time string
     */
    public function col_validfrom($row) {
        return userdate($row->validfrom);
    }

    /**
     * Render visual representation of the 'validto' column for use in the table
     *
     * @param \stdClass $row
     * @return string time string
     */
    public function col_validto($row) {
        return userdate($row->validto);
    }

    /**
     * Get any extra classes names to add to this row in the HTML.
     *
     * @param \stdClass $row array the data for this row.
     * @return string added to the class="" attribute of the tr.
     */
    public function get_row_class($row) {
        if ($row->validto < time() || $row->validfrom > time()
                || ($row->maxusage > 0 && $row->numused >= $row->maxusage)) {
            return 'dimmed_text';
        }
        return parent::get_row_class($row);
    }

    /**
     * Render visual representation of the 'action' column for use in the table
     *
     * @param \stdClass $row
     * @return string actions
     */
    public function col_action($row) {
        global $PAGE, $OUTPUT;
        $actions = [];
        if (has_capability('enrol/gwpayments:deletecoupon', $PAGE->context)) {
            $actions[] = $OUTPUT->action_icon(
                    new moodle_url($this->baseurl, ['action' => 'delete', 'id' => $row->id]),
                    new pix_icon('i/delete', get_string('coupon:delete', 'enrol_gwpayments'), 'moodle', ['class' => 'icon']),
                    null,
                    ['alt' => get_string('coupon:delete', 'enrol_gwpayments')],
                    false
                );
        }
        if (has_capability('enrol/gwpayments:editcoupon', $PAGE->context)) {
            $actions[] = $OUTPUT->action_icon(
                    new moodle_url($this->baseurl, ['action' => 'edit', 'id' => $row->id]),
                    new pix_icon('i/edit', get_string('coupon:edit', 'enrol_gwpayments'), 'moodle', ['class' => 'icon']),
                    null,
                    ['alt' => get_string('coupon:edit', 'enrol_gwpayments')],
                    false
                );
        }
        return implode(' ', $actions);
    }

}
