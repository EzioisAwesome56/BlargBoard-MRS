# Blargboard

http://kuribo64.net/blargboard/

http://kuribo64.net/?page=forum&id=82

-------------------------------------------------------------------------------

Board software written in PHP. Uses MySQL for storage.

This is the software that powers Kuribo64 (http://kuribo64.net/). Or well, not quite.
The code provided here is a cleaned up version, with all the K64-specific stuff removed.

It is based off ABXD. ABXD is made by Dirbaio, Nina, GlitchMr & co, and was originally
Kawa's project. See http://abxd.dirbaio.net/ for more details.

It uses Smarty for its templates, and Font Awesome. And possibly some other funny things 
I forgot about.

This board follows the Acmlmboard concept. If you don't know Acmlmboard, you don't know what you're missing.

## Requirements

Blargboard requires PHP 5.3. You also need the mcrypt extension and PHP-GD

You will also need mod-rewrite for apache2 for rewritten URLS

There is no exact requirement for MySQL, but make sure to have a recent version.

Everything else is provided in the package.

## How to install and use

PHP and MySQL knowledge isn't required to use Blargboard but is a plus.

Get a webserver. Upload the Blargboard codebase to it. Create an empty MySQL database.

Browse to your board's install.php (http://my.board.for.example/install.php) and follow the instructions.

If everything went fine, browse to your freshly installed board and configure it. If not, let us know.

We recommend you take some time and make your own board themes and banner to give your board a truly unique feel.
If you have HTML knowledge, you can even edit the templates to change your board's look more in-depth.

## Working plugins

The following plugins have been updated and are known to work with current Blargboard. Any other plugins in the repo are probably broken, so don't try using them.

 * CustomUserNameColors
 * ircreport
 * layoutblockstats
 * postplusone (thanks to maorninja322 for this plugin)

## How to update your board

Download the most recent Blargboard package (be it an official release, unoffical release, or a Git package).

Copy the files over your existing board's files.

Make sure to not overwrite/delete the config directory, especially config/salt.php! Lose that one and you'll have fun resetting everyone's passwords.
Everything else is safe to overwrite. Be careful to not lose any changes you have made, though.

Once that is done, run update.php (http://my.board.for.example/update.php) to update the board's database structure.

Depending on the versions, your update may involve running extra scripts to fix certain things. Make sure to follow those instructions.


Updating from Blargboard 1.0 and mrs 1.2.1-1.2.2 isn't covered.

To update from Blargboard 1.2, just drag and drop the new files into your install of blarg, exact files are listed here:
*/index.php
*/install.php
*/lib/bbcode_callbacks.php
*/lib/bbcode_parser.php
*/lib/common.php
*/lib/syndromes.php
*/lib/permstrings.php
*/pages/register.php
*/pages/404.php
*/pages/newreply.php
*/pages/newthread.php
*/pages/nuke.php
*/pages/reregs.php
*/instameme (the whole folder. put in the root of your board)
*/pages/irc.php
*/menus.php
*/pages/profile.php
*/js/tricks.js
*/ranksets/mario (whole folder)
*/kurichallenge.php

to update from blargboard 1.2.3, drag these files
*/lib/common.php
*/ranksets/mario (whole folder)
*/kurichallenge.php

## Features

 * Flexible permission system
 * Plugin system
 * Templates (in the works, about 80% done)
 * URL rewriting, enables human-readable forum and thread URLs for public content (requires code editing to enable it as of now)
 * Post layouts
 * more Acmlmboard feel
 * typical messageboard features
 
## New Features in Blargboard 1.2.3 MRS

 * Fixed syndromes
 * Fixed spelling in newreply.php and newthread.php
 * 4 new instamemes (wrote the new code and found maorninja did it first. sorry man)
 * added a button for instameme on the reply box
 * fixed up reregs.php
 * fixed user nuke link in profile.php
 
-------------------------------------------------------------------------------

Coders and such, who like to hack new features in their software, may think that the use
of templates in Blargboard gets in their way. Well uh, can't please everybody. I tried to
do my best at separating logic and presentation. Besides, the use of templates actually
makes the code nicer. Just look at the first few revisions and see how much duplicate logic
is powering the mobile layout, for example. Templates allowed to get rid of all that madness.

As of now, there are no official releases for this, and the ABXD database installer hasn't
been adapted to Blargboard's database structure yet. Thus, when updating your Blargboard
copy, you need to check for changes to database.sql and modify your database's structure
accordingly.

## Board owner's tips (first one works without URL rewriting, second one does)

http://board.example/?page=makelr (http://board.example/makelr) -> regenerates the L/R tree used for forum listings and such.
Use if some of your forums are showing up in wrong places.

http://board.example/?page=editperms&gid=X -> edit permissions for group ID X.

http://board.example/?page=secgroups (http://board.example/secgroups) -> assign secondary groups to a user.

http://board.example/?page=nuke&id=X (http://board.example/nuke/X-user-name or just /nuke/x) -> delete user X from the board


How to add groups: add to the usergroups table via PMA
 * type: 0 for primary groups, 1 for secondary
 * display: 0 for normal group, 1 for group listed as staff, -1 for hidden group
 * rank: a user may not mess with users of higher ranks no matter his permissions

 
How to add/remove secondary groups to someone: add to/remove from the secondarygroups table via PMA (or use ?page=secgroups for adding)
 * userid: the user's ID
 * groupid: the group's ID. Do not use the ID of a primary group!
 
WARNING: when banning someone, make sure that the secondary groups' permissions won't override the banned group's permissions. If that happens, you'll need to delete the secondarygroups assignments for the user.


How to (insert action): first look into your board's admin panel, settings panel, etc... then if you still can't find, ask us. But please don't be a noob and ask us about every little thing.

To use URL rewriting, make sure you have mod_rewrite on, if you dont you can turn it on using "sudo a2enmod rewrite". Then make sure you have "Allowoveride All" for your website directory and the included .htaccess file in the root of your board.

## Support, troubleshooting, etc

The Blargboard help forum is at Kuribo64: http://kuribo64.net/?page=forum&id=82

If anything goes wrong with your board, go there and let us know. Make sure to describe your problems in detail, our crystal ball is scratched so we can't see well.

If the error is a 'MySQL Error', to get a detailed report, you need to open config/database.php in a text editor, find `$debugMode = 0;` and replace it with `$debugMode = 1;`. 
This will make the board give you the MySQL error message and the query which went wrong. Once you're done troubleshooting your board, it is recommended that you edit config/database.php back so that `$debugMode` is 0.

YOU WILL NOT RECEIVE HELP IF YOU HAVEN'T READ THE INSTRUCTIONS WHEN INSTALLING YOUR BOARD.

## TODO list

(no particular order there)

 * finish implementing templates
 * improve the permission editing interfaces
 * port the 'show/hide sidebar' feature from Kuribo64? or just nuke the sidebar? more leaning towards the latter.
 * merge/split threads a la phpBB (albeit without the shitty interface)
 * support multiple password hashing methods? (for importing from other board softwares, or for those who feel SHA256 with per-user salt isn't enough) (kinda addressed via login plugins)
 * more TODO at Kuribo64 and RVLution
 
 * low priority: change/remove file headers? most of the original files still say 'AcmlmBoard XD'
 * besides it'd be an opportunity to add a license like the GPL
 * Add more stuff
 * make blargboard 1.2.5 MRS
 * finish up intergrated hall of shame
-------------------------------------------------------------------------------

Blargboard is provided as-is, with no guarantee that it'll be useful or even work. I'm not
responsible if it explodes in your face. Use that thing at your own risk.

Oh well, it should work rather well. See Kuribo64. But uh, we never know.

-------------------------------------------------------------------------------

Have fun.

blarg
