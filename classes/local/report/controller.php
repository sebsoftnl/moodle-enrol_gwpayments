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
 * Controller for report
 *
 * File         controller.php
 * Encoding     UTF-8
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 RvD
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_gwpayments\local\report;

use core_table\local\filter\filter;
use core_table\local\filter\integer_filter;

/**
 * enrol_gwpayments\local\coupons\controller
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 RvD
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class controller {

    /**
     * @var \moodle_page
     */
    protected $page;
    /**
     * @var \core_renderer
     */
    protected $output;
    /**
     * @var \enrol_gwpayments\output\renderer
     */
    protected $renderer;

    /**
     * Create new manager instance
     * @param \moodle_page $page
     * @param \core\output_renderer $output
     * @param \core_renderer|null $renderer
     */
    public function __construct($page, $output, $renderer = null) {
        $this->page = $page;
        $this->output = $output;
        $this->renderer = $renderer;
    }

    /**
     * Execute page request
     */
    public function execute_request() {
        $action = optional_param('action', 'list', PARAM_ALPHA);

        switch ($action) {
            default:
                $this->process_overview();
                break;
        }
    }

    /**
     * Display overview table
     */
    protected function process_overview() {
        $cid = optional_param('courseid', null, PARAM_INT);
        $uid = optional_param('userid', null, PARAM_INT);

        $pageurl = clone $this->page->url;
        if (!empty($cid)) {
            $pageurl->param('courseid', $cid);
        }
        if (!empty($uid)) {
            $pageurl->param('userid', $uid);
        }

        $filterset = new \enrol_gwpayments\table\cusage_filterset();
        if (!empty($cid)) {
            $filterset->add_filter(new integer_filter('courseid', filter::JOINTYPE_ALL, [$cid]));
        }
        if (!empty($uid)) {
            $filterset->add_filter(new integer_filter('userid', filter::JOINTYPE_ALL, [$uid]));
        }
        $filterset->check_validity();

        $table = new \enrol_gwpayments\table\cusage();
        $table->set_filterset($filterset);

        echo $this->renderer->header();
        echo $this->renderer->heading(get_string('report:cusage', 'enrol_gwpayments'));
        echo '<div class="enrol-gwpayments-report-container">';
        echo $table->render(25, false);
        echo '</div>';
        echo $this->renderer->footer();
    }

    /**
     * Return new url based on the current page-url
     *
     * @param array $mergeparams
     * @return \moodle_url
     */
    protected function get_url($mergeparams = []) {
        $url = $this->page->url;
        $url->params($mergeparams);
        return $url;
    }

}
