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
 * Coupon usage dynamic table filterset
 *
 * File         cusage_filterset.php
 * Encoding     UTF-8
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2025 RvD
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_gwpayments\table;

use core_table\local\filter\integer_filter;

/**
 * Coupon usage dynamic table filterset
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2025 RvD
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cusage_filterset extends \core_table\local\filter\filterset {

    /**
     * Get required filters
     *
     * @return array
     */
    protected function get_required_filters(): array {
        $rs = [];
        return $rs;
    }

    /**
     * Get optional filters
     *
     * @return array
     */
    protected function get_optional_filters(): array {
        $rs = [
            'userid' => integer_filter::class,
            'courseid' => integer_filter::class,
        ];
        return $rs;
    }

}
