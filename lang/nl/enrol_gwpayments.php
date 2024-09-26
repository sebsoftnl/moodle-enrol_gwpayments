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
 */
$string['assignrole'] = 'Toekennen rol';
$string['backtolist'] = 'Terug naar overzicht';
$string['checkcode'] = 'Controleer kortingscode';
$string['cost'] = 'Inschrijf kosten';
$string['costerror'] = 'Kosten moeten als decimale waarde worden gedefinieerd';
$string['coupon:add'] = 'Voeg nieuwe kortingscode toe';
$string['coupon:delete'] = 'Kortingscode verwijderen';
$string['coupon:delete:warn'] = '<p>Je staat op het punt de coupon met de volgende details te verwijderen.</p>
<p>Cursus: <i>{$a->course}</i><br/>Couponcode: <i>{$a->code}</i><br/>Geldigheid: <i>{$a->validfrom} - {$a->validto}</i></p>
<p>Weet je zeker dat je dit wilt doen?</p>';
$string['coupon:deleted'] = 'Kortingscode successvol verwijderd';
$string['coupon:details'] = 'Kortingscode details';
$string['coupon:edit'] = 'Bewerk kortingscode';
$string['coupon:expired'] = 'Kortingscode is verlopen';
$string['coupon:invalid'] = 'Ongeldige kortingscode';
$string['coupon:newprice'] = 'Korting: {$a->currency} {$a->discount} ({$a->percentage})<br/>Nieuwe prijs: <b>{$a->currency} {$a->newprice}</b>';
$string['coupon:saved'] = 'Kortingscode successvol aangemaakt';
$string['coupon:status:active'] = 'ACTIEF';
$string['coupon:status:expired'] = 'VERLOPEN';
$string['coupon:status:impending'] = 'INACTIEF';
$string['coupon:status:maxused'] = 'VERBRUIKT';
$string['coupon:updated'] = 'Kortingscode gegevens successvol bewerkt';
$string['couponcode'] = 'Couponcode';
$string['couponcodeexists'] = 'Kortingscode bestaat al!';
$string['couponcodemissing'] = 'Couponcode moet ingegeven worden';
$string['coupons:disabled'] = 'Gebruik van kortingscodes is uitgeschekeld voor deze aanmeldplugin';
$string['coupons:manage'] = 'Kortingen beheren';
$string['coupontype'] = 'Type';
$string['coupontype:percentage'] = 'Percentage';
$string['coupontype:value'] = 'Waarde';
$string['currency'] = 'Valuta';
$string['defaultrole'] = 'Standaard roltoewijzing';
$string['defaultrole_desc'] = 'Selecteer rol die de gebruikers moeten worden toegekend tijdens gateway payments inschrijvingen';
$string['disableifmoodleapp'] = 'Betalingen uitschakelen in Moodle App?';
$string['disableifmoodleapp_help'] = 'Indien aangevinkt zullen eindgebruikers een melding krijgen dat ze vie de browser moeten inloggen op het LMS om betalingen te kunnen doen';
$string['enablebypassinggateway'] = 'Payment gateway bypassing toestaan?';
$string['enablebypassinggateway_help'] = 'Indien ingeshcakeld zal dit compleet de betaling omzeilen
via de payment gateways wanneer het totaalbedrag van de betaling 0 is.
Omdat veel implementaties niet om kunnen gaan met een "lege" betaling kan deze optie ingeschakeld worden
om dit probleem op te lossen.';
$string['enablecoupon'] = 'Gebruik van coupons inschakelen?';
$string['enablecoupon_help'] = 'Vink dezeoptie aan als je standaard het invullen van coupon codes wilt inschakelen in het betaalscherm.
Je kunt dit per enrolment instantie aan of uitschakelen.';
$string['enrol:already'] = 'Je bent al aangemeld voor cursus: {$a->fullname}';
$string['enrol:fail'] = 'Je bent niet aangemeld voor deze cursus.';
$string['enrol:invalid'] = 'Obgeldige aanmeldmethode.';
$string['enrol:ok'] = 'Bedankt voor je aankoop.<br> Je bent nu aangemeld voor cursus: {$a->fullname}';
$string['enrolenddate'] = 'Eind datum';
$string['enrolenddate_help'] = 'Indien ingeschakeld, kunnen gebruikers worden ingeschreven tot deze datum.';
$string['enrolenddaterror'] = 'Inschrijving einddatum kan niet eerder dan startdatum';
$string['enrolfreepass'] = 'Je bent gratis in de cursus aangemeld.';
$string['enrolperiod'] = 'Inschrijving duur';
$string['enrolperiod_desc'] = 'Standaard lengte dat een inschrijving geldig is. Indien ingesteld op nul, zal de inschrijving voor onbeperkte tijd zijn';
$string['enrolstartdate'] = 'Start datum';
$string['enrolstartdate_help'] = 'Indien ingeschakeld, kunnen gebruikers worden ingeschreven vanaf deze datum.';
$string['entiresite'] = 'Gehele website / alle cursussen';
$string['err:percentage-exceed'] = 'Kortingspercentage kan niet boven 100% zijn';
$string['err:percentage-negative'] = 'Kortingspercentage kan niet negatief zijn';
$string['err:value-negative'] = 'Korting kan niet negatief zijn';
$string['errorvatabove100'] = 'BTW kan niet > 100% zijn';
$string['errorvatbelow0'] = 'BTW kan niet < 0%';
$string['event:order:delivered'] = 'Bestelling geleverd/aanmelding aangemaakt';
$string['expiredaction'] = 'Enrolment verloop actie';
$string['expiredaction_help'] = 'Selecteer actie uit te voeren wanneer de gebruiker de inschrijving verloopt. Houdt u er rekening mee dat sommige gebruikersgegevens en instellingen kunnen worden verwijderd.';
$string['expirymessageenrolledbody'] = 'Beste {$a->user},

Je aanmelding in cursus \'{$a->course}\' gaat vervallen op {$a->timeend}.

ls je hier een probleem mee hebt, neem dan contact op met {$a->enroller}.';
$string['expirymessageenrolledsubject'] = 'Melding voor het vervallen van de aanmelding';
$string['expirymessageenrollerbody'] = 'De aanmelding in cursus \'{$a->course}\' zal binnen {$a->threshold} vervallen voor volgende gebruikers:

{$a->users}

Ga naar {$a->extendurl} om hun aanmelding te verlengen.';
$string['expirymessageenrollersubject'] = 'Melding voor het vervallen van de aanmelding';
$string['findcourses:noselectionstring'] = '(nog) geen cursus geselecteerd';
$string['findcourses:placeholder'] = '... selecteer cursus ...';
$string['gwpayments:config'] = 'gwpayment aanmelding configureren';
$string['gwpayments:createcoupon'] = 'Coupon/voucher codes voor gwpayment aanmeldingen aanmaken';
$string['gwpayments:deletecoupon'] = 'Coupon/voucher codes voor gwpayment aanmeldingen verwijderen';
$string['gwpayments:editcoupon'] = 'Coupon/voucher codes voor gwpayment aanmeldingen bewerken';
$string['gwpayments:manage'] = 'gwpayment aanmeldingen beheren';
$string['gwpayments:unenrol'] = 'gwpayment aanmeldingen annuleren';
$string['gwpayments:unenrolself'] = 'Zelf afmelden van gwpayment aanmeldingen';
$string['gwpayments:unenrolselfconfirm'] = 'Zelf afmelden van gwpayment aanmeldingen';
$string['mailadmins'] = 'E-mail admin';
$string['mailstudents'] = 'E-mail studenten';
$string['mailteachers'] = 'E-mail leraren';
$string['maxusage'] = 'Maximum aantal';
$string['maxusage_help'] = 'Maximum aantal keren dat de coupon code kan worden gebruikt.<br/>
Als je dit op 0 laat staan, betekent dit onbeperkt gebruik van de code.';
$string['nocost'] = 'Er zitten geen kosten aan deze cursus';
$string['paymentaccount'] = 'Payment account';
$string['paymentaccount_help'] = 'Aanmeldingen zullen op deze rekening worden bijgeschreven.';
$string['percentage'] = 'Percentage';
$string['percentagemissing'] = 'Percentage moet ingegeven worden';
$string['pluginname'] = 'Payments Aanmelding';
$string['pluginname_help'] = 'This plugin allows you to purchase a course with Moodle\'s core payment gateways
and incorporates possibilities for coupon/voucher based discounts';
$string['privacy:metadata'] = 'De PaymentS aanmeldplugin plugin slaat geen persoonlijke gegevens op.';
$string['promo'] = 'PaymentS aanmeldplugin voor Moodle';
$string['promodesc'] = 'Deze plugin is geschreven door Sebsoft Managed Hosting & Software Development
(<a href=\'http://www.sebsoft.nl/\' target=\'_new\'>http://sebsoft.nl</a>).<br /><br />
{$a}<br /><br />';
$string['purchasedescription'] = 'Aanmelding in cursus {$a}';
$string['sendpaymentbutton'] = 'Selecteer betaalmethode';
$string['status'] = 'Toestaan gateway payments inschrijvingen';
$string['status_desc'] = 'Sta gebruikers toe om gateway payments gebruiken om in te schrijven in een cursus standaard.';
$string['task:defaulttasks'] = 'Standaard taken';
$string['task:sendexpirynotifications'] = 'Verzend notificaties tbv verlopen PaymentS aanmeldingen';
$string['th:action'] = 'Actie(s)';
$string['th:code'] = 'Code';
$string['th:cost'] = 'Kosten';
$string['th:courseid'] = 'Cursus';
$string['th:discount'] = 'Korting';
$string['th:maxusage'] = 'Max gebruik';
$string['th:numused'] = '#Gebruikt';
$string['th:paymentcreated'] = 'Transactie gestart';
$string['th:paymentmodified'] = 'Laatst gewijzigd';
$string['th:percentage'] = 'Percentage';
$string['th:rawcost'] = 'Cursusprijs';
$string['th:status'] = 'Status';
$string['th:txid'] = 'Transactie ID';
$string['th:type'] = 'Type';
$string['th:user'] = 'Gebruiker';
$string['th:validfrom'] = 'Geldig van';
$string['th:validto'] = 'Geldig tot';
$string['th:value'] = 'Waarde';
$string['unenrolselfconfirm'] = 'Wil je je echt afmelden van cursus "{$a}"?';
$string['validfrom'] = 'Geldig vanaf';
$string['validfromhigherthanvalidto'] = 'Geldigheid vanaf is na geldigheid tot';
$string['validfrommissing'] = 'Startdatum moet ingegeven worden';
$string['validto'] = 'Geldig tot';
$string['validtomissing'] = 'Einddatum moet ingegeven worden';
$string['value'] = 'Waarde';
$string['valuemissing'] = 'Er moet een waarde worden opgegeven';
$string['vat'] = 'BTW';
$string['vat_help'] = 'BTW percentage voor cursus kosten (gegeven cursuskosten zijn incl. BTW).';
$string['warn:disabledifmoodleapp'] = 'Betalingen kunnen enkel vanuit de browser worden gedaan. Log aub in op het LMS met een browser op de computer/laptop om de betaling te kunnen doen.';
$string['warn:zeropayment'] = 'Belangrijk: wanneer je kortingen gebruikt die tot een zgn. nulbetaling leiden,
bijvoorbeeld wanneer je een 100% korting instelt, dien je ervan bewust te zijn dat niet alle payment gateway
implementaties hiermee overweg kunnen. Het is aannemelijk dat je uiteindelijk een foutmelding krijgt omdat de
payment service provider geen nulbetaling ondersteunt. De meeste payment gateways zullen hervoor ook geen oplossing bieden.
Je kunt hier omheen werken door zelf of door de systeembeheerder (voor deze plugin) in te laten stellen dat
de payment gateways <i>omzeilt></i> worden voor nulbetalingen (in de globale instellingen van deze aanmeldmethode).
De cursusaanmelding zal dan gedaan worden zonder tussenkomst van de betaalmogelijkheden.
Er zal dan een referentie van 0 gebruikt worden voor de (voor Moodle interne) betaalreferentie.';
