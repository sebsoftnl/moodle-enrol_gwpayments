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

import * as Ajax from 'core/ajax';
import * as Notification from 'core/notification';

/**
 * @type const
 */
const SELECTORS = {
    input: '#id_coupon',
    action: '#btncheckcoupon'
};

/**
 * Call server to validate given coupon code.
 *
 * @param {string} couponCode coupon code
 * @param {Number} courseId course ID
 * @param {Number} instanceId enrol instance ID
 * @returns {Promise<>}
 */
const checkCode = (couponCode, courseId, instanceId) => {
    const request = {
        methodname: 'enrol_gwpayments_check_coupon',
        args: {
            couponcode: couponCode,
            courseid: courseId,
            instanceid: instanceId
        }
    };

    return Ajax.call([request])[0];
};

/**
 * Perform coupon code check.
 *
 * @param {Event} e
 * @returns {Promise}
 */
const performCheck = (e) => {
    e.preventDefault();
    let el = document.querySelector(SELECTORS.input);
    if (typeof el === 'undefined' || el === null) {
        return;
    }
    let code = el.value;
    if (code.length === 0) {
        return;
    }
    checkCode(code, el.dataset.courseid, el.dataset.instanceid)
        .then((response) => {
            if (response.result) {
                if (response.data.freepass) {
                    window.location.href = response.data.freepassredirect;
                } else {
                    document.querySelector('#enrol-gwpayments-coupondiscount').innerHTML = response.data.html;
                    document.querySelector('#enrol-gwpayments-coupondiscount').classList.remove('warning');
                    document.querySelector('#enrol-gwpayments-coupondiscount').classList.add('success');
                    document.querySelector('#enrol-gwpayments-basecost').classList.add('enrol-gwpayments-strike');
                }
            } else {
                document.querySelector('#enrol-gwpayments-coupondiscount').innerHTML = response.error;
                document.querySelector('#enrol-gwpayments-coupondiscount').classList.add('warning');
                document.querySelector('#enrol-gwpayments-coupondiscount').classList.remove('success');
                document.querySelector('#enrol-gwpayments-basecost').classList.remove('enrol-gwpayments-strike');
            }
        })
        .fail(Notification.exception);
};

/**
 * Exported initializer
 *
 * @returns {void}
 */
export const init = () => {
    document.querySelector(SELECTORS.input).addEventListener('blur', performCheck);
    document.querySelector(SELECTORS.action).addEventListener('click', performCheck);
};
