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
 * general global plugin settings
 *
 * File         settings.php
 * Encoding     UTF-8
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */
defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    $config = get_config('enrol_gwpayments');

    $currencies = enrol_get_plugin('gwpayments')->get_possible_currencies();
    if (empty($currencies)) {
        $notify = new \core\output\notification(
            get_string('nocurrencysupported', 'core_payment'),
            \core\output\notification::NOTIFY_WARNING
        );
        $settings->add(new admin_setting_heading('enrol_gwpayments_nocurrency', '', $OUTPUT->render($notify)));
    }

    // Logo.
    $image = '<a href="http://www.sebsoft.nl" target="_new"><img src="' .
            $OUTPUT->image_url('logo', 'enrol_gwpayments') . '" /></a>&nbsp;&nbsp;&nbsp;';
    $donate = '<a href="https://customerpanel.sebsoft.nl/sebsoft/donate/intro.php" target="_new"><img src="' .
            $OUTPUT->image_url('donate', 'enrol_gwpayments') . '" /></a>';
    $header = '<div class="enrol_gwpayments-logopromo">' . $image . $donate . '</div>';
    $settings->add(new admin_setting_heading('enrol_gwpayments_logopromo',
            get_string('promo', 'enrol_gwpayments'),
            get_string('promodesc', 'enrol_gwpayments', $header)));

    // Settings.
    $settings->add(new admin_setting_heading('enrol_gwpayments_settings', '',
            get_string('pluginname_help', 'enrol_gwpayments')));
    $settings->add(new admin_setting_configcheckbox('enrol_gwpayments/mailstudents',
            get_string('mailstudents', 'enrol_gwpayments'), '', 0));
    $settings->add(new admin_setting_configcheckbox('enrol_gwpayments/mailteachers',
            get_string('mailteachers', 'enrol_gwpayments'), '', 0));
    $settings->add(new admin_setting_configcheckbox('enrol_gwpayments/mailadmins',
            get_string('mailadmins', 'enrol_gwpayments'), '', 0));

    $options = array(
        ENROL_EXT_REMOVED_KEEP => get_string('extremovedkeep', 'enrol'),
        ENROL_EXT_REMOVED_SUSPENDNOROLES => get_string('extremovedsuspendnoroles', 'enrol'),
        ENROL_EXT_REMOVED_UNENROL => get_string('extremovedunenrol', 'enrol'),
    );
    $settings->add(new admin_setting_configselect('enrol_gwpayments/expiredaction',
            get_string('expiredaction', 'enrol_gwpayments'), get_string('expiredaction_help', 'enrol_gwpayments'),
            ENROL_EXT_REMOVED_SUSPENDNOROLES, $options));

    $options = array();
    for ($i = 0; $i < 24; $i++) {
        $options[$i] = $i;
    }
    $settings->add(new admin_setting_configselect('enrol_gwpayments/expirynotifyhour',
            get_string('expirynotifyhour', 'core_enrol'), '', 6, $options));

    // Enrol instance defaults.
    $settings->add(new admin_setting_heading('enrol_gwpayments_defaults',
            get_string('enrolinstancedefaults', 'admin'), get_string('enrolinstancedefaults_desc', 'admin')));

    $optionsyesno = array(
        ENROL_INSTANCE_ENABLED => get_string('yes'),
        ENROL_INSTANCE_DISABLED => get_string('no')
    );
    $settings->add(new admin_setting_configselect('enrol_gwpayments/status', get_string('status', 'enrol_gwpayments'),
            get_string('status_help', 'enrol_gwpayments'), ENROL_INSTANCE_DISABLED, $optionsyesno));
    $settings->add(new admin_setting_configtext('enrol_gwpayments/cost', get_string('cost', 'enrol_gwpayments'),
            '', 10.00, PARAM_FLOAT, 4));
    $settings->add(new admin_setting_configtext('enrol_gwpayments/vat', get_string('vat', 'enrol_gwpayments'),
            get_string('vat_help', 'enrol_gwpayments'), 21, PARAM_INT, 4));

    if (!empty($currencies)) {
        $settings->add(new admin_setting_configselect('enrol_gwpayments/currency',
                get_string('currency', 'enrol_gwpayments'), '', 'EUR', $currencies));
    }

    $settings->add(new admin_setting_configcheckbox('enrol_gwpayments/enablecoupon', get_string('enablecoupon', 'enrol_gwpayments'),
            get_string('enablecoupon_help', 'enrol_gwpayments'), 1));

    if (!during_initial_install()) {
        $options = get_default_enrol_roles(context_system::instance());
        $student = get_archetype_roles('student');
        $student = reset($student);
        $settings->add(new admin_setting_configselect('enrol_gwpayments/roleid',
                get_string('defaultrole', 'enrol_gwpayments'),
                get_string('defaultrole_help', 'enrol_gwpayments'), $student->id, $options));
    }
    $settings->add(new admin_setting_configduration('enrol_gwpayments/enrolperiod',
            get_string('enrolperiod', 'enrol_gwpayments'), get_string('enrolperiod_help', 'enrol_gwpayments'), 0));

    $options = array(
        0 => get_string('no'),
        1 => get_string('expirynotifyenroller', 'core_enrol'),
        2 => get_string('expirynotifyall', 'core_enrol')
    );
    $settings->add(new admin_setting_configselect('enrol_gwpayments/expirynotify',
            get_string('expirynotify', 'core_enrol'),
            get_string('expirynotify_help', 'core_enrol'), 0, $options));

    $settings->add(new admin_setting_configduration('enrol_gwpayments/expirythreshold',
            get_string('expirythreshold', 'core_enrol'),
            get_string('expirythreshold_help', 'core_enrol'), 86400, 86400));

    $settings->add(new admin_setting_configcheckbox('enrol_gwpayments/enablebypassinggateway',
            get_string('enablebypassinggateway', 'enrol_gwpayments'),
            get_string('enablebypassinggateway_help', 'enrol_gwpayments'), 0));

}

// We shall add some navigation.
if ($hassiteconfig) {
    $node = new admin_category('gwpayments', get_string('pluginname', 'enrol_gwpayments'));
    $ADMIN->add('root', $node);
    $ADMIN->add('gwpayments', new admin_externalpage('aiocoupons', get_string('coupons:manage', 'enrol_gwpayments'),
            new moodle_url('/enrol/gwpayments/couponmanager.php', array('page' => 'aiocoupons'))));
}
