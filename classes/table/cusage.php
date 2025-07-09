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
 * Coupon usage dynamic table
 *
 * File         cusage.php
 * Encoding     UTF-8
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2024 RvD
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_gwpayments\table;

use lang_string;
use moodle_url;
use core_table\sql_table;

/**
 * overview dynamic table.
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2024 RvD
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cusage extends sql_table implements \core_table\dynamic {

    /**
     * @var \context
     */
    protected $context;

    /**
     * Create a new instance of the overview table
     */
    public function __construct() {
        global $USER;
        $uniqueid = 'enrol_gwpayments_table_cusage' . $USER->id;
        parent::__construct($uniqueid);

        $this->collapsible(false);
        $this->sortable(true, 'timecreated', SORT_DESC);
        $this->useridfield = 'userid';
    }

    /**
     * Set the filterseu.
     *
     * @param \core_table\local\filter\filterset $filterset
     * @return void
     */
    public function set_filterset(\core_table\local\filter\filterset $filterset): void {
        $this->context = \context_system::instance();
        parent::set_filterset($filterset);
    }

    /**
     * Guess the base url for the templates table.
     */
    public function guess_base_url(): void {
        $params = [];
        if ($this->filterset->has_filter('courseid')) {
            $params['courseid'] = $this->filterset->get_filter('courseid')->current();
        }
        if ($this->filterset->has_filter('userid')) {
            $params['userid'] = $this->filterset->get_filter('userid')->current();
        }
        $this->baseurl = new moodle_url('/enrol/gwpayments/view/report.php', $params);
    }

    /**
     * Get the context of the current table.
     *
     * Note: This function should not be called until after the filterset has been provided.
     *
     * @return context
     */
    public function get_context(): \context {
        return $this->context;
    }

    /**
     * CHeck capability
     *
     * @return bool
     */
    public function has_capability(): bool {
        return has_capability('enrol/gwpayments:config', $this->get_context());
    }

    /**
     * Convenience method to call a number of methods for you to display the table.
     *
     * @param int $pagesize
     * @param bool $useinitialsbar
     * @param string $downloadhelpbutton
     */
    public function out($pagesize, $useinitialsbar, $downloadhelpbutton = '') {
        $this->define_columns_and_headers();
        parent::out($pagesize, $useinitialsbar, $downloadhelpbutton);
    }

    /**
     * Convenience method to call a number of methods for you to display the table.
     *
     * @param int $pagesize
     * @param bool $useinitialsbar
     * @param string $downloadhelpbutton
     * @return string
     */
    public function render($pagesize, $useinitialsbar, $downloadhelpbutton = '') {
        $this->define_columns_and_headers();
        ob_start();
        parent::out($pagesize, $useinitialsbar, $downloadhelpbutton);
        $table = ob_get_clean();
        return $table;
    }

    /**
     * Define headers and columns
     */
    protected function define_columns_and_headers() {
        $columns = [
            'fullname',
            'coursefullname',
            'code',
            'typ',
            'value',
            'discount',
            'originalcost',
            'timecreated',
        ];
        $headers = [
            get_string('fullname'),
            get_string('course'),
            get_string('couponcode', 'enrol_gwpayments'),
            get_string('coupontype', 'enrol_gwpayments'),
            get_string('value', 'enrol_gwpayments'),
            get_string('th:discount', 'enrol_gwpayments'),
            get_string('th:originalcost', 'enrol_gwpayments'),
            get_string('th:timeused', 'enrol_gwpayments'),
        ];
        $this->define_columns($columns);
        $this->define_headers($headers);
    }

    /**
     * Initialise database related parts.
     */
    protected function init_sql() {
        global $DB;

        $fields = 'cu.*, ';
        $fields .= 'c.shortname as courseshortname, c.fullname as coursefullname, ';
        $fields .= static::get_all_user_name_fields(true, 'u');
        $from = '{enrol_gwpayments_cusage} cu
            JOIN {enrol_gwpayments_coupon} cp ON cu.couponid = cp.id
            JOIN {course} c ON cu.courseid = c.id
            JOIN {user} u ON cu.userid = u.id
            ';
        $wheres = [];
        $params = [];

        if ($this->filterset->has_filter('courseid')) {
            $wheres[] = 'c.id = :courseid';
            $params['courseid'] = $this->filterset->get_filter('courseid')->current();
        }
        if ($this->filterset->has_filter('userid')) {
            $wheres[] = 'u.id = :userid';
            $params['userid'] = $this->filterset->get_filter('userid')->current();
        }

        // Filters.
        if ($this->filterset->has_filter('keyword')) {
            $kwfilter = $this->filterset->get_filter('keyword');
            $keywords = $kwfilter->get_filter_values();
            $kwwheres = [];
            $kwfields = [];

            foreach ($keywords as $index => $keyword) {
                $tmpwheres = [];
                foreach ($kwfields as $field) {
                    $param = str_replace('.', '', "{$field}key{$index}");
                    $kwsqlwhere = $DB->sql_like("COALESCE(p.{$field}, '')", ":{$param}", false, false,
                            $kwfilter->get_join_type() === $kwfilter::JOINTYPE_NONE);
                    $tmpwheres[] = $kwsqlwhere;
                    $params[$param] = "%{$keyword}%";
                }

                $kwwheres[] = "(" . implode(' OR ', $tmpwheres) . ")";
            }

            if (!empty($kwwheres)) {
                switch ($kwfilter->get_join_type()) {
                    case $kwfilter::JOINTYPE_ALL:
                        $wheres[] = implode(' AND ', $kwwheres);
                        break;
                    default:
                        $wheres[] = implode(' OR ', $kwwheres);
                        break;
                }
            }
        }

        // Prepare final values.
        if ($wheres) {
            switch ($this->filterset->get_join_type()) {
                case $this->filterset::JOINTYPE_ALL:
                    $wherenot = '';
                    $wheresjoin = ' AND ';
                    break;
                case $this->filterset::JOINTYPE_NONE:
                    $wherenot = ' NOT ';
                    $wheresjoin = ' AND NOT ';

                    // Some of the $where conditions may begin with `NOT` which results in `AND NOT NOT ...`.
                    // To prevent this from breaking on Oracle the inner WHERE clause is wrapped in brackets, making it
                    // `AND NOT (NOT ...)` which is valid in all DBs.
                    $wheres = array_map(function ($where) {
                        return "({$where})";
                    }, $wheres);

                    break;
                default:
                    // Default to 'Any' jointype.
                    $wherenot = '';
                    $wheresjoin = ' OR ';
                    break;
            }

            $outerwhere = $wherenot . implode($wheresjoin, $wheres);
        } else {
            $outerwhere = '';
        }

        $this->set_sql($fields, $from, $outerwhere, $params);
    }

    /**
     * Query the database
     *
     * @param int $pagesize
     * @param boolean $useinitialsbar -- unused
     */
    public function query_db($pagesize, $useinitialsbar = true) {
        global $DB;

        $this->init_sql();

        $countsql = "SELECT COUNT(cu.id) FROM {$this->sql->from}";
        $countsql .= !empty($this->sql->where) ? " WHERE {$this->sql->where}" : '';

        $sql = "SELECT {$this->sql->fields} FROM {$this->sql->from}";
        $sql .= !empty($this->sql->where) ? " WHERE {$this->sql->where}" : '';

        $sort = $this->get_sql_sort();
        if ($sort) {
            $sql .= " ORDER BY {$sort}";
        }

        if (!$this->is_downloading()) {
            $this->pagesize($pagesize, $DB->count_records_sql($countsql, $this->sql->params));
            $this->rawdata = $DB->get_recordset_sql($sql, $this->sql->params, $this->get_page_start(), $this->get_page_size());
        } else {
            $this->rawdata = $DB->get_recordset_sql($sql, $this->sql->params);
        }
    }

    /**
     * format course column
     *
     * @param stdClass $row
     * @return string
     */
    public function col_coursefullname($row) {
        return \html_writer::link(new \moodle_url('/course/view.php', ['id' => $row->courseid]),
                    $row->coursefullname);
    }

    /**
     * format course column
     *
     * @param stdClass $row
     * @return string
     */
    public function col_typ($row) {
        if ($row->typ === 'percentage') {
            return get_string('th:percentage', 'enrol_gwpayments');
        } else if ($row->typ === 'percentage') {
            return get_string('th:value', 'enrol_gwpayments');
        }
    }

    /**
     * format value column
     *
     * @param stdClass $row
     * @return string
     */
    public function col_value($row) {
        if ($row->typ === 'percentage') {
            return sprintf('%.2f', $row->value) . '%';
        } else if ($row->typ === 'value') {
            return sprintf('%.2f', $row->value);
        }
    }

    /**
     * format discount column
     *
     * @param stdClass $row
     * @return string
     */
    public function col_discount($row) {
        return sprintf('%.2f', $row->discount);
    }

    /**
     * format originalcost column
     *
     * @param stdClass $row
     * @return string
     */
    public function col_originalcost($row) {
        return sprintf('%.2f', $row->originalcost);
    }

    /**
     * format originalcost column
     *
     * @param stdClass $row
     * @return string
     */
    public function col_timecreated($row) {
        return userdate($row->timecreated, get_string('strftimedate', 'langconfig'));
    }

    /**
     * Utility function to generate all user name fields.
     * It's only here so we don't have to keep duplicating multiple lines of code
     * where Moodle's core function used to be a simple one liner!
     *
     * @param bool $returnsql
     * @param string|null $tableprefix
     * @param string $prefix prefix added to the name fields e.g. authorfirstname.
     * @param string $fieldprefix sql field prefix e.g. id AS userid.
     * @param bool $order moves firstname and lastname to the top of the array / start of the string.
     *
     * @return string
     */
    private static function get_all_user_name_fields($returnsql = false, $tableprefix = '',
            $prefix = '', $fieldprefix = '', $order = false) {
        if (class_exists('\core_user\fields', true)) {
            // This is Moodle 3.11+.
            $usql = \core_user\fields::for_name()->get_sql($tableprefix, true, $fieldprefix, '', false);
            return $usql->selects;
        } else {
            return get_all_user_name_fields($returnsql, $tableprefix, $prefix, $fieldprefix, $order);
        }
    }

}
