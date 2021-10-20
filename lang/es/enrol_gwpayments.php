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
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['pluginname'] = 'Inscripción po pago';
$string['pluginname_help'] = 'Este pluginn permite inscribirse a un curso pagando con las pasarelas de pago estandar de Moodle y incorpora la posibilidad de usar cupones de descuento';
$string['promo'] = 'PaymentS enrolment plugin for Moodle';
$string['promodesc'] = 'This plugin is written by Sebsoft Managed Hosting & Software Development
(<a href=\'https://www.sebsoft.nl/\' target=\'_new\'>https://sebsoft.nl</a>).<br /><br />
{$a}<br /><br />';
$string['mailadmins'] = 'Notificar al administrador';
$string['mailstudents'] = 'Notificar a los estudiantes';
$string['mailteachers'] = 'Notificar a los profesores';
$string['expiredaction'] = 'Acción al espirar la inscripción';
$string['expiredaction_help'] = 'Selecciona la acción que ocurrirá cuando expire la inscripción. Tenga en cuenta que algunos datos de usuario y configuraciones del mismo son borradas del curso cuando se desinscribe el usuario';;
$string['status'] = 'Permite inscripciones ClassicPay';
$string['status_help'] = 'Permite a los usuarios usar ClassicPay para inscribirse a un curso por defecto';
$string['cost'] = 'Coste de inscripción';
$string['vat'] = 'IVA';
$string['vat_help'] = 'Porcentaje del precio que corresponde al IVA(Se muestra el coste con IVA incluido) .';
$string['enablecoupon'] = '¿Habilitar cupones?';
$string['enablecoupon_help'] = 'Habilite esta opción para que se muestre el campo de cupón en la pantalla de pago. Puede ser habilitado o deshabilitado a nivel de curso';
'Check this option to enable entering of coupons by default in the payment screen.
You can enable or disable it on a per enrolment instance level.';
$string['defaultrole'] = 'Asignación del rol por defecto';
$string['defaultrole_help'] = 'Selecciona el rol que será asignado al usuario durante las inscripciones ClassicPay';
$string['enrolperiod'] = 'Periodo de inscripción';
$string['enrolperiod_help'] = 'Duración en que la inscrición es valida desde el momento en que usuario se inscribe';
$string['nocost'] = 'No hay coste para enrolarse en este curso.';
$string['currency'] = 'Moneda';
$string['assignrole'] = 'Rol asignado';
$string['enrolenddate'] = 'Fecha de fin';
$string['enrolenddate_help'] = 'Si se habilita, los usuarios solo pueden inscribirse hasta esta fecha.';

$string['enrolenddaterror'] = 'La fecha de fin no puede ser menor que la fecha de inicio,';
$string['enrolstartdate'] = 'Fecha de inicio';
$string['enrolstartdate_help'] = 'Si se habilita, los usuarios solo pueden inscribirse desd esta fecha.';
$string['paymentaccount'] = 'cuenta de pago';
$string['paymentaccount_help'] = 'El precio de la inscripción se realizará conn esta cuenta de pago.';

$string['coupons:manage'] = 'Gestionar cupones';
$string['coupon:delete'] = 'Borrar cupon';
$string['coupon:delete:warn'] = '<p>Vas a borrar un cupon cupón con los siguientes detalles.</p>
<p>Curso: <i>{$a->course}</i><br/>Código de cupon: <i>{$a->code}</i><br/>Validez: <i>{$a->validfrom} - {$a->validto}</i></p>
<p>¿Estás seguro de querer borrarlo?</p>';
$string['coupon:edit'] = 'Editar Cupon';
$string['coupon:saved'] = 'Cupón añadido correctamente';
$string['coupon:updated'] = 'Cupón actualizado correctamenter';
$string['coupon:add'] = 'Añadir cupón';
$string['coupon:edit'] = 'Editar cupón';
$string['coupon:invalid'] = 'Código de cupón invalido';
$string['coupon:expired'] = 'Este cupón ha expirado';
$string['coupon:deleted'] = 'Cupón borrado correctamente ';
$string['coupon:details'] = 'Detalles del cupón';
$string['coupons:disabled'] = 'Los cupones se han deshabilitado en este curso.';

$string['th:courseid'] = 'Curso';
$string['th:code'] = 'Código';
$string['th:discount'] = 'Descuento';
$string['th:percentage'] = 'Porcentaje';
$string['th:validfrom'] = 'Válido desde';
$string['th:validto'] = 'Válido hasta';
$string['th:numused'] = '#Usado';
$string['th:maxusage'] = 'Número máximo de usos';
$string['th:txid'] = 'Id de transactión';
$string['th:action'] = 'Acciones';
$string['th:status'] = 'Estado';
$string['th:user'] = 'Usuario';
$string['th:paymentcreated'] = 'Transacción iniciada';
$string['th:paymentmodified'] = 'Última actualización';
$string['th:cost'] = 'Coeste';
$string['th:rawcost'] = 'Precio del curso';
$string['th:type'] = 'Tipo';
$string['th:value'] = 'Valor';

$string['backtolist'] = 'Vuelta al listado';
$string['entiresite'] = 'Todo el entorno / cualquier curso';
$string['couponcode'] = 'Código del cupón';
$string['couponcodemissing'] = 'El código debe ser incluido';
$string['validfrom'] = 'Válido desde';
$string['validfrommissing'] = 'Inicio de validez debe ser relleno';
$string['validto'] = 'Válido hasta';
$string['validtomissing'] = 'Final de validez debe ser relleno';
$string['percentage'] = 'Porcentaje';
$string['percentagemissing'] = 'Porcentaje no puede ser vacío';
$string['maxusage'] = 'Máximo número de usos';
$string['maxusage_help'] = 'Número de veces que el cupón se puede usar.<br/>
Si se deja a 0, es ilimítado.';
$string['coupontype'] = 'Tipo';
$string['coupontype:percentage'] = 'Porcentaje';
$string['coupontype:value'] = 'Valor';
$string['value'] = 'Valor';
$string['valuemissing'] = 'Valor no debe estar vacío';
$string['err:value-negative'] = 'El descuento no puede ser negativo';
$string['coupon:status:impending'] = 'IMPENDING';
$string['coupon:status:active'] = 'ACTIVE';
$string['coupon:status:expired'] = 'EXPIRED';
$string['coupon:status:maxused'] = 'MAXUSED';
$string['errorvatbelow0'] = 'IVA no puede ser inferior 0%';
$string['errorvatabove100'] = 'IVA no puede ser superior 100%';
$string['privacy:metadata'] = 'Este sistema no almacena datos personales.';
$string['purchasedescription'] = 'Inscripción en curso {$a}';
$string['sendpaymentbutton'] = 'Seleccione tipo de pago';
$string['checkcode'] = 'Chequear el código de descuento';
$string['err:percentage-negative'] = 'Descuento no puede ser negativo';
$string['err:percentage-exceed'] = 'Descuento no puede ser mayor de 100%';
$string['validfromhigherthanvalidto'] = 'Validity from data is past validity to date';

$string['coupon:newprice'] = 'Descuento: {$a->currency} {$a->discount} ({$a->percentage})<br/>Nuevo precio: <b>{$a->currency} {$a->newprice}</b>';

$string['enrol:invalid'] = 'Registro de inscripción no vñalido.';
$string['enrol:fail'] = 'No has sido inscrito en el curso.';
$string['enrol:ok'] = 'Gracias por su compre.<br> Has sido inscrito en el curso: {$a->fullname}';
$string['enrol:already'] = 'Ya estabas inscrito en este curso: {$a->fullname}';

$string['findcourses:noselectionstring'] = 'no has seleccionado ningún curso';
$string['findcourses:placeholder'] = '... seleccione cursos ...';
$string['task:defaulttasks'] = 'Tareas por defecto';
$string['task:sendexpirynotifications'] = 'envío de expiración de inscripción';
$string['couponcodeexists'] = 'El código ya existe';

$string['expirymessageenrollersubject'] = 'Notificación de expiración de inscripción';
$string['expirymessageenrollerbody'] = 'Inscripción al curso \'{$a->course}\' expirará en el el próximo {$a->threshold} para los siguientes usuarios:

{$a->users}

Para extender su inscripción clique {$a->extendurl}';
$string['expirymessageenrolledsubject'] = 'Notificación de expiración de inscripción';
$string['expirymessageenrolledbody'] = 'Estimado/a {$a->user},

Esta es una notificación de que su inscripción \'{$a->course}\' expirará en {$a->timeend}.

Si necesita ayuda contacte con {$a->enroller}.';
