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
 * Upgrade script for enrol_gwpayments
 *
 * File         upgrade.php
 * Encoding     UTF-8
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 RvD
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Upgrade
 *
 * @param int $oldversion old (current) plugin version
 * @return boolean
 */
function xmldb_enrol_gwpayments_upgrade($oldversion) {
    global $DB;
    /** @var database_manager $dbman */
    $dbman = $DB->get_manager();

    if ($oldversion < 2024090100) {
        // Adjust 'value' in enrol_gwpayments_coupon.
        $table = new xmldb_table('enrol_gwpayments_coupon');
        $field = new xmldb_field('value', XMLDB_TYPE_FLOAT, '9,3', null, XMLDB_NOTNULL, null, null, 'typ');
        $dbman->change_field_precision($table, $field);

        // Add coupon usage templates table.
        $table = new xmldb_table('enrol_gwpayments_cusage');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('enrolid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'id');
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'enrolid');
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'courseid');
        $table->add_field('paymentid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'userid');
        $table->add_field('couponid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'paymentid');
        $table->add_field('verified', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, null, 'couponid');
        $table->add_field('code', XMLDB_TYPE_CHAR, '20', null, XMLDB_NOTNULL, null, null, 'verified');
        $table->add_field('typ', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null, 'code');
        $table->add_field('value', XMLDB_TYPE_FLOAT, '9,3', null, XMLDB_NOTNULL, null, null, 'typ');
        $table->add_field('discount', XMLDB_TYPE_FLOAT, '9,3', null, XMLDB_NOTNULL, null, null, 'value');
        $table->add_field('originalcost', XMLDB_TYPE_FLOAT, '9,3', null, XMLDB_NOTNULL, null, null, 'discount');
        $table->add_field('coupondata', XMLDB_TYPE_TEXT, 'medium', null, null, null, null, 'originalcost');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, null, 'coupondata');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, null, 'timecreated');
        // Add keys.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('fk-couponid', XMLDB_KEY_FOREIGN, ['couponid'], 'enrol_gwpayments_coupon', ['id']);
        // Add indices.
        $table->add_index('idx-enrolid', XMLDB_INDEX_NOTUNIQUE, ['enrolid']);
        $table->add_index('idx-courseid', XMLDB_INDEX_NOTUNIQUE, ['courseid']);
        $table->add_index('idx-userid', XMLDB_INDEX_NOTUNIQUE, ['userid']);
        $table->add_index('idx-code', XMLDB_INDEX_NOTUNIQUE, ['code']);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
    }

    return true;
}
