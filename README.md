# Blargboard MRS
Minor Release Schedule for Blargboard

## A Known Working Setup

* PHP 7.4+ (mod_php)
* MariaDB 10.5+ (strict mode disabled)
* PHP extensions: mbstring, mysqli, gd
* Apache 2.4.36
* Debian 10.6

## Changelog
* Fix many deprecation errors stopping PHP 7 support from being possible.
* Fix IP query page so it actually works when rewritten URLs are on.
* Delete kurichallenge and proxy detection - it's broken.
* mod_rewrite is actually checked for in htaccess now.
* Other, less noticeable cleanups such as the credits and this very message which will hopefully be undertaken over the next point releases.

## Known Issues

* The logo image will request an invalid URI when loading the page in editprofile.
* Bugs will likely happen when URL rewriting is off - I didn't test it at all.
* each()'s deprecation in one of the Smarty generator files either - hopefully 1.2.7 will fix this.

## Plans for next Releases
* LOOK FOR SECURITY ISSUES - THIS IS OBSOLETE SOFTWARE FROM 2014
    * Start using password_hash().
* Fix all known underlying user-side issues.
* Make the board ready for PHP 8.
* 4-byte UTF8 support - a script for conversion would be handy.
* Strip out the mobile layout - a responsive view would be better in future.
* Implement proper registration security.

## A Memory
This release (1.2.6) is dedicated to the memory of Adam (Tachyon42) who was found dead at his home earlier this month; hoster of the DMF, and overall one of the closest people in Ezio's online life. You will be missed.

RIP - 1975-2020
