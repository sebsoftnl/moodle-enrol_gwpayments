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
 * Controller for coupons
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

namespace enrol_gwpayments\local\coupons;

use moodle_url;
use html_writer;
use stdClass;

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
            case 'delete':
                $this->process_delete();
                break;
            case 'add':
            case 'edit':
                $this->process_edit();
                break;
            default:
                $this->process_overview();
                break;
        }
    }

    /**
     * Display overview table
     */
    protected function process_overview() {
        $cid = optional_param('cid', 0, PARAM_INT);
        $pageurl = clone $this->page->url;
        if ($this->page->course->id <> SITEID) {
            $pageurl->param('cid', $this->page->course->id);
        }
        $strnewform = '';
        if (has_capability('enrol/gwpayments:createcoupon', $this->page->context)) {
            $options = new stdClass();
            $options->instance = (object) ['id' => 0];
            if ($this->page->course->id <> SITEID) {
                $options->lockcourse = $this->page->course->id;
            }
            $newform = new \enrol_gwpayments\local\coupons\couponform($pageurl, $options);
            $newform->process_post($options->instance, $pageurl);
            $strnewform = '<div class="enrol-gwpayments-container">' . $newform->render() . '</div>';
        }

        $filter = optional_param('list', 'all', PARAM_ALPHA);
        $table = new \enrol_gwpayments\local\coupons\coupontable($cid, $filter);
        $table->baseurl = $this->get_url();

        echo $this->renderer->header();
        echo $this->renderer->get_tabs($this->page->context, 'coupons', empty($cid) ? [] : ['cid' => $cid]);
        echo '<div class="enrol-gwpayments-container">';
        echo '<div class="alert alert-warning">';
        echo get_string('warn:zeropayment', 'enrol_gwpayments');
        echo '</div>';
        $table->render(25);
        echo '</div>';
        echo $strnewform;
        echo $this->renderer->footer();
    }

    /**
     * Process delete
     */
    protected function process_edit() {
        global $DB;

        // We'll do this using a form for now.
        $cid = optional_param('cid', 0, PARAM_INT);
        $id = optional_param('id', null, PARAM_INT);
        if (empty($id)) {
            require_capability('enrol/gwpayments:createcoupon', $this->page->context);
            $instance = (object)['id' => 0, 'maxusage' => 0];
            $actionstr = get_string('coupon:add', 'enrol_gwpayments');
            $postparams = ['action' => 'add'];
        } else {
            require_capability('enrol/gwpayments:editcoupon', $this->page->context);
            $instance = $DB->get_record('enrol_gwpayments_coupon', ['id' => $id], '*', MUST_EXIST);
            $actionstr = get_string('coupon:edit', 'enrol_gwpayments');
            $postparams = ['action' => 'edit', 'id' => $id];
        }
        $this->page->set_title($actionstr);

        $options = new stdClass();
        $options->instance = (object) ['id' => 0];
        if ($this->page->course->id <> SITEID) {
            $options->lockcourse = $this->page->course->id;
        }
        $posturl = $this->get_url($postparams);
        $redirect = optional_param('redirect', $this->get_url(), PARAM_LOCALURL);

        $form = new \enrol_gwpayments\local\coupons\couponform($posturl, $options);
        $form->process_post($instance, $redirect);
        $form->set_data($instance);

        echo $this->renderer->header();
        echo $this->renderer->get_tabs($this->page->context, "coupons-{$postparams['action']}", empty($cid) ? [] : ['cid' => $cid]);
        echo '<div class="enrol-gwpayments-container">';
        $form->display();
        echo '</div>';
        echo $this->renderer->footer();
    }

    /**
     * Process delete
     */
    protected function process_delete() {
        global $DB;
        require_capability('enrol/gwpayments:deletecoupon', $this->page->context);
        $id = required_param('id', PARAM_INT);
        $cid = optional_param('cid', 0, PARAM_INT);
        $this->page->set_title(get_string('coupon:delete', 'enrol_gwpayments'));

        $posturl = $this->get_url(['action' => 'delete', 'id' => $id]);
        $redirect = optional_param('redirect', $this->get_url(), PARAM_LOCALURL);

        $coupon = $DB->get_record('enrol_gwpayments_coupon', ['id' => $id]);
        $deleteform = new \enrol_gwpayments\local\coupons\coupondelete($posturl, $coupon);
        $deleteform->process_post($redirect);

        echo $this->renderer->header();
        echo $this->renderer->get_tabs($this->page->context, 'coupons-delete', empty($cid) ? [] : ['cid' => $cid]);
        echo '<div class="enrol-gwpayments-container">';
        $deleteform->display();
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
