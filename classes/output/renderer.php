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
 * Renderer class.
 *
 * File         renderer.php
 * Encoding     UTF-8
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 RvD
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_gwpayments\output;

/**
 * enrol_gwpayments\output\renderer
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 RvD
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends \plugin_renderer_base {

    /**
     * Create a tab object with a nice image view, instead of just a regular tabobject
     *
     * @param string $id unique id of the tab in this tree, it is used to find selected and/or inactive tabs
     * @param string $pix image name
     * @param string $component component where the image will be looked for
     * @param string|moodle_url $link
     * @param string $text text on the tab
     * @param string $title title under the link, by defaul equals to text
     * @param bool $linkedwhenselected whether to display a link under the tab name when it's selected
     * @return \tabobject
     */
    protected function create_pictab($id, $pix = null, $component = null, $link = null,
            $text = '', $title = '', $linkedwhenselected = false) {
        $img = '';
        if ($pix !== null) {
            $img = $this->image_icon($pix, $title, empty($component) ? 'moodle' : $component, ['class' => 'icon']);
        }
        return new \tabobject($id, $link, $img . $text, empty($title) ? $text : $title, $linkedwhenselected);
    }

    /**
     * Generate navigation tabs
     *
     * @param \context $context current context to work in (needed to determine capabilities).
     * @param string $selected selected tab
     * @param array $params any paramaters needed for the base url
     */
    public function get_tabs($context, $selected, $params = []) {
        global $CFG;
        $tabs = [];
        $inactive = null;
        $activated = null;

        $config = get_config('enrol_gwpayments');

        $active1 = $active2 = null;
        if (strpos($selected, '-') !== false) {
            list($active1, $active2) = explode('-', $selected);
        } else {
            $active1 = $selected;
        }

        $couponstab = $this->create_pictab('coupons', 'i/coupons', '',
                new \moodle_url($CFG->wwwroot . '/enrol/gwpayments/couponmanager.php', $params),
                get_string('coupons:manage', 'enrol_gwpayments'));
        if ($active1 == 'coupons' && !empty($active2)) {
            $couponstab->subtree[] = $this->create_pictab('coupons', 'i/up', null,
                    new \moodle_url($CFG->wwwroot . '/enrol/gwpayments/couponmanager.php', $params),
                    get_string('backtolist', 'enrol_gwpayments'), '', true);
        }
        $couponstab->subtree[] = $this->create_pictab('coupons-add', 'i/add', null,
                    new \moodle_url($CFG->wwwroot . '/enrol/gwpayments/couponmanager.php', $params + ['action' => 'add']),
                    get_string('coupon:add', 'enrol_gwpayments'), '', true);
        if ($active1 == 'coupons' && $active2 === 'edit') {
            $couponstab->subtree[] = $this->create_pictab('coupons-edit', 'i/edit', null,
                    new \moodle_url($CFG->wwwroot . '/enrol/gwpayments/couponmanager.php', $params + ['action' => 'edit']),
                    get_string('coupon:edit', 'enrol_gwpayments'), '', true);
        }
        if ($active1 == 'coupons' && $active2 === 'delete') {
            $couponstab->subtree[] = $this->create_pictab('coupons-delete', 'i/delete', null,
                    new \moodle_url($CFG->wwwroot . '/enrol/gwpayments/couponmanager.php', $params + ['action' => 'delete']),
                    get_string('coupon:delete', 'enrol_gwpayments'), '', true);
        }
        $tabs[] = $couponstab;

        return $this->tabtree($tabs, $selected);
    }

}
