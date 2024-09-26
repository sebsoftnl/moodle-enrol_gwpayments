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
 * Language file for enrol_gwpayments, EN
 *
 * File         enrol_gwpayments.php
 * Encoding     UTF-8
 *
 * @package     enrol_gwpayments
 *
 * @copyright   2021 RvD
 * @author      RvD <helpdesk@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */$string['assignrole'] = 'Assign role';
$string['backtolist'] = 'Back to overview';
$string['checkcode'] = 'Check coupon code';
$string['cost'] = 'Enrol cost';
$string['costerror'] = 'Cost must be defined as a numeric/floating point value';
$string['coupon:add'] = 'Add a new coupon';
$string['coupon:delete'] = 'Delete Coupon';
$string['coupon:delete:warn'] = '<p>You are about to remove a coupon with the following details.</p>
<p>Course: <i>{$a->course}</i><br/>Couponcode: <i>{$a->code}</i><br/>Validity: <i>{$a->validfrom} - {$a->validto}</i></p>
<p>Are you sure you want to do this?</p>';
$string['coupon:deleted'] = 'Coupon successfully deleted';
$string['coupon:details'] = 'Coupon details';
$string['coupon:edit'] = 'Edit Coupon';
$string['coupon:expired'] = 'Coupon code has expired';
$string['coupon:invalid'] = 'Invalid coupon code';
$string['coupon:newprice'] = 'Discount: {$a->currency} {$a->discount} ({$a->percentage})<br/>New price: <b>{$a->currency} {$a->newprice}</b>';
$string['coupon:saved'] = 'Coupon successfully inserted';
$string['coupon:status:active'] = 'ACTIVE';
$string['coupon:status:expired'] = 'EXPIRED';
$string['coupon:status:impending'] = 'IMPENDING';
$string['coupon:status:maxused'] = 'MAXUSED';
$string['coupon:updated'] = 'Coupon data successfully updated';
$string['couponcode'] = 'Couponcode';
$string['couponcodeexists'] = 'Coupon code already exists';
$string['couponcodemissing'] = 'Couponcode must be set';
$string['coupons:disabled'] = 'Coupon usage has been disabled for this enrolment/purchase';
$string['coupons:manage'] = 'Manage coupons';
$string['coupontype'] = 'Type';
$string['coupontype:percentage'] = 'Percentage';
$string['coupontype:value'] = 'Value';
$string['currency'] = 'Currency';
$string['defaultrole'] = 'Default role assignment';
$string['defaultrole_help'] = 'Select role which should be assigned to users during gateway payments enrolments';
$string['enablebypassinggateway'] = 'Allow bypassing of payment gateways?';
$string['enablebypassinggateway_help'] = 'When enabled, this will completely bypass the payment gateways
whenever the (discounted) cost is zero. Many PSP\'s can\'t cope with a payment of 0.
Because not all payment gateway implementations will be able to bypass trying to make a zero payment,
but will fail as a result, this setting can be enabled to provide a solution.';
$string['enablecoupon'] = 'Enable coupons?';
$string['enablecoupon_help'] = 'Check this option to enable entering of coupons by default in the payment screen.
You can enable or disable it on a per enrolment instance level.';
$string['enrol:already'] = 'You have already been enrolled for course: {$a->fullname}';
$string['enrol:fail'] = 'You have not been enrolled to this course.';
$string['enrol:invalid'] = 'Invalid enrolment record.';
$string['enrol:ok'] = 'Thanks for your purchase.<br> You have now been enrolled for course: {$a->fullname}';
$string['enrolenddate'] = 'End date';
$string['enrolenddate_help'] = 'If enabled, users can be enrolled until this date only.';
$string['enrolenddaterror'] = 'Enrolment end date cannot be earlier than start date';
$string['enrolfreepass'] = 'You\'ve freely been enrolled into the course.';
$string['enrolperiod'] = 'Enrolment duration';
$string['enrolperiod_help'] = 'Length of time that the enrolment is valid, starting with the moment the user is enrolled. If disabled, the enrolment duration will be unlimited.';
$string['enrolstartdate'] = 'Start date';
$string['enrolstartdate_help'] = 'If enabled, users can be enrolled from this date onward only.';
$string['entiresite'] = 'Entire site / any course';
$string['err:percentage-exceed'] = 'Discount percentage can\'t exceed 100%';
$string['err:percentage-negative'] = 'Discount percentage can\'t be negative';
$string['err:value-negative'] = 'Discount can\'t be negative';
$string['errorvatabove100'] = 'VAT cannot exceed 100%';
$string['errorvatbelow0'] = 'VAT cannot be below 0%';
$string['event:order:delivered'] = 'Order delivered/enrolment created';
$string['expiredaction'] = 'Enrolment expiration action';
$string['expiredaction_help'] = 'Select action to carry out when user enrolment expires. Please note that some user data and settings are purged from course during course unenrolment.';
$string['expirymessageenrolledbody'] = 'Dear {$a->user},

This is a notification that your enrolment in the course \'{$a->course}\' is due to expire on {$a->timeend}.

If you need help, please contact {$a->enroller}.';
$string['expirymessageenrolledsubject'] = 'Enrolment expiry notification';
$string['expirymessageenrollerbody'] = 'Enrolment in the course \'{$a->course}\' will expire within the next {$a->threshold} for the following users:

{$a->users}

To extend their enrolment, go to {$a->extendurl}';
$string['expirymessageenrollersubject'] = 'Enrolment expiry notification';
$string['findcourses:noselectionstring'] = 'no course(s) selected yet';
$string['findcourses:placeholder'] = '... select course(s) ...';
$string['gwpayments:config'] = 'Configure gwpayment enrolment';
$string['gwpayments:createcoupon'] = 'Delete coupon/voucher codes for gwpayment enrolments';
$string['gwpayments:deletecoupon'] = 'Delete coupon/voucher codes for gwpayment enrolments';
$string['gwpayments:editcoupon'] = 'Edit coupon/voucher codes for gwpayment enrolments';
$string['gwpayments:manage'] = 'Manage gwpayment enrolments';
$string['gwpayments:unenrol'] = 'Unenrol gwpayment enrolments';
$string['gwpayments:unenrolself'] = 'Self unenrolment for gwpayment enrolments';
$string['mailadmins'] = 'Notify admin';
$string['mailstudents'] = 'Notify students';
$string['mailteachers'] = 'Notify teachers';
$string['maxusage'] = 'Maximum usage';
$string['maxusage_help'] = 'Maximum number of times this coupon code can be used.<br/>
If 0 is entered, it means unlimited usage.';
$string['messageprovider:expiry_notification'] = 'Enrolment expiry notification';
$string['messageprovider:gwpayments_enrolment'] = 'GWPayments enrolment notification';
$string['nocost'] = 'There is no cost associated with enrolling in this course!';
$string['paymentaccount'] = 'Payment account';
$string['paymentaccount_help'] = 'Enrolment fees will be paid to this account.';
$string['percentage'] = 'Percentage';
$string['percentagemissing'] = 'Percentage must be given';
$string['pluginname'] = 'Payments Enrolment';
$string['pluginname_help'] = 'This plugin allows you to purchase a course with Moodle\'s core payment gateways
and incorporates possibilities for coupon/voucher based discounts';
$string['privacy:metadata'] = 'The enrolment on payment enrolment plugin does not store any personal data.';
$string['promo'] = 'PaymentS enrolment plugin for Moodle';
$string['promodesc'] = 'This plugin is written by Sebsoft Managed Hosting & Software Development
(<a href=\'https://www.sebsoft.nl/\' target=\'_new\'>https://sebsoft.nl</a>).<br /><br />
{$a}<br /><br />';
$string['purchasedescription'] = 'Enrolment in course {$a}';
$string['sendpaymentbutton'] = 'Select payment type';
$string['status'] = 'Allow gateway payments enrolments';
$string['status_help'] = 'Allow users to use gateway payments to enrol into a course by default.';
$string['task:defaulttasks'] = 'Default tasks';
$string['task:sendexpirynotifications'] = 'PaymentS enrolment send expiry notifications task';
$string['th:action'] = 'Action(s)';
$string['th:code'] = 'Code';
$string['th:cost'] = 'Cost';
$string['th:courseid'] = 'Course';
$string['th:discount'] = 'Discount';
$string['th:maxusage'] = 'Max usage';
$string['th:numused'] = '#Used';
$string['th:paymentcreated'] = 'Transaction started';
$string['th:paymentmodified'] = 'Last updated';
$string['th:percentage'] = 'Percentage';
$string['th:rawcost'] = 'Course Price';
$string['th:status'] = 'Status';
$string['th:txid'] = 'Transaction ID';
$string['th:type'] = 'Type';
$string['th:user'] = 'User';
$string['th:validfrom'] = 'Valid from';
$string['th:validto'] = 'Valid to';
$string['th:value'] = 'Value';
$string['unenrolselfconfirm'] = 'Do you really want to unenrol yourself from course "{$a}"?';
$string['validfrom'] = 'Valid from';
$string['validfromhigherthanvalidto'] = 'Validity from data is past validity to date';
$string['validfrommissing'] = 'Start date of validity must be set';
$string['validto'] = 'Valid to';
$string['validtomissing'] = 'End date for validity must be set';
$string['value'] = 'Value';
$string['valuemissing'] = 'A value must be given';
$string['vat'] = 'VAT';
$string['vat_help'] = 'VAT percentage of course cost (note: course cost is including VAT).';
$string['warn:zeropayment'] = 'Note: when using discount codes that lead to an ultimate payment of zero,
for instance when using a 100% discount, you should be aware not all payment gateway implementations
can handle this. It is very likely you will ultimately end up with an error from the payment service provider.
To work around this, you or the systems administrator <i>can</i> enable bypassing the payment gateways for
zero payments (through the global plugin settings). The enrolment will then be done but <i>without</i> a valid payment id (we\'ll use a value of 0 as paymentid).';
