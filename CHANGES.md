Version 1.0.8 build 2024060400
* Thank you https://github.com/dreblen for providing a patch to fix issue 18.
* Thank you https://github.com/dreblen for providing a hint on the actual issue of issue 19.
* Updated CI to also support newer Moodle versions.

Version 1.0.7 build 2022092700
* After 98456093214985 stale builds, we decided to ONLY support 4.0 onwards.

Version 1.0.6 build 2021081606

** Removed "exit()" statement after redirect in several forms [phpmd:VIOLATION].
** Removed unused variables in several places [phpmd:VIOLATION].
** Removed several unused local variables over several files [phpmd:VIOLATION].
** Fixed newline issues [phpmd:VIOLATION].
** Removed all MOODLE_INTERNAL for singular classes as per MDLSITE-5967
** Resolved #11 (coupon code not "used" on free pass)
** Small readme fix

-----
* Version 1.0.5 build 2021081605

** Emergency fix: Re-add lib.php due to it's mandated by "enrol_get_instances()" in enrollib.php

-----
* Version 1.0.4 build 2021081604

** Resolved #6 (fixed in previous version, this will autoclose the issue).
** Emergency fix: couponmanager fatal error for requiring removed lib.php
** Re-add lib.php due to it's mandated by "enrol_get_instances()" in enrollib.php

-----

* Version 1.0.3 build 2021081603

** Resolve #5 (do not include coupon functionality if not in use)
** Resolve #7 General change of returned result in external::check_coupon()
   We pretended to return floats that were actually formatted using localisation
   whereas only the HTML part needs this.
** Resolve #9 (100% discount workaround)
** Added code to actually "use" a coupon code (increase "numused")
** Added specific warning/informative message in discount manager related to "zero payments".

-----

* Version 1.0.2 build 2021081602

** Resolve #2 (redirect to server root)
** Resolve #3 (missing capability names)
** Resolve #4 (avoid empty lib.php; removed file)

-----

* Version 1.0.1 build 2021081601

** Added missing translations
** Fixed wrong field used for VAT (customint3)
** Fixed non existing page url (couponmanager; thanks @vicencarlos)
** Fixed table in event order_delivered (should be "enrol")

-----
Version 1.0.0 build 2021081600

** Initial version

-----
