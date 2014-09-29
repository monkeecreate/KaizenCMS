# KaizenCMS

Just a CMS that is now open for business.

## Requirements/Installing
_SUBJECT TO AND WILL CHANGE_

The requirements are simple. Apache 2+ and PHP 5+. PEAR and Memcache are recommended but not required. KaizenCMS will not work in a sub directory. It must be the root domain or a subdomain.

* Keeping the directory structure upload the files to your server. Remember, upload them to the directory just outside your public_html. This is important to the setup. NOTE: Backup your public_html if you already have files inside. Be sure and double check that all hidden files and folders were also uploaded (might have to show hidden files if your OS is hiding them).
* If your host/server does not use public_html to store the files, no problem. Just rename the public_html from the repository.
* Make the following directories writable (CHMOD 777): ./.compiled/ and ./public_html/uploads/
* Rename ./inc_config_example.php to ./inc_config.php
* The CMS will guide you through the rest of the setup just visit http://yourdomain.com/admin/.

## The Team

* James Fleeting @fleetingftw
* John Hoover @defvayne23
