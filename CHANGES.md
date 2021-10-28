* Version 1.0.4 build 2021081604

** Resolved #6 (fixed in previous version, this will autoclose the issue).
** Emergency fix: couponmanager fatal error for requiring removed lib.php

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
