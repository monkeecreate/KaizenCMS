# CRANE | WEST CMS (cwCMS)

A project that started as a small bit of code that John Hoover started with for each new website, that has grown into its own CMS system. It was built with the developer in mind but utilizes smarty for the designer.

## Requirements/Installing

The requirements are simple. Apache 2+ and PHP 5+. PEAR and Memcache are recommended but not required. cwCMS will not work in a sub directory. It must be the root domain or a subdomain.

* Keeping the directory structure upload the files to your server. Remember, upload them to the directory just outside your public_html. This is important to the setup. NOTE: Backup your public_html if you already have files inside. Be sure and double check that all hidden files and folders were also uploaded (might have to show hidden files if your OS is hiding them). When copying on the command line, make sure that you copy the .pear/ and the .smarty/ directories as a cp ~/oldpath/* ~/newpath won't copy those folders.
* When moving the CMS be sure include the hidden folders. (.compiled, .pear, .smarty)
* If your host/server does not use public_html to store the files, no problem. Just rename the public_html from the repository.
* Make the following directories writable (CHMOD 777): ./.compiled/ and ./public_html/uploads/
* Rename ./inc_config_example.php to ./inc_config.php
* The CMS will guide you through the rest of the setup just visit http://yourdomain.com/admin/.

### The Team

**Current**
* James Fleeting -- Senior Developer -- @twofivethreetwo
* Blaine Bowers  -- Graphic Designer -- @flyingnowhere
* Jeffrey Gordon -- Developer        -- @Gauthic
* Joel Abeyta    -- Developer        -- @jbeyta

**Past**
* John Hoover    -- Developer        -- @defvayne23

```
                                 ZZ
                               ZZZZZZ
                               Z8ZZZZZ
                             ZZ    ZZZ
                            Z      ZZZ
                                   ZZ
                                   ZZ
                                   ZZ
                                  ZZ
                                  ZZ
                                  ZZ
                                 ZZZ
                                 ZZZ
    Z                            ZZZZ                                     Z
      Z$                        ZZZZZ                                  $Z
        ZZZ                     ZZZZZZ                              ZZZ
          ZZZ                   ZZZZZZZ                           ZZZ
           ZZZZ                 ZZZZZZZZ                        ZZZZ
             ZZZ                 ZZZZZZZZ                      ZZZ
              ZZZZ               ZZZZZZZZZ                   ZZZZ
               ZOZZ              ZZZZZZZZZZ                 ZOZZ
                ZZOZ              ZZZZZZZZZZ               ZOZZ
                 ZZZZ             ZZZZZZZZZZZ             $ZZZ
                  ZZZZ             ZZZZZZZZZZZZ          ZZZZ
                   ZZMZ              ZZZZZZZZZZZ        ZZMZ
                   ZMZZ               ZZZZZZZZZZZ      ZMZZN
                    ZZMZ              ZZZZZZZZZZZZ     ZZMZ
                     ZZZZ             Z  Z     ZZZ    ZZZZ
                      ZZMZ           ZZ  ZZ      ZZ  ZZMZ
                      ZZZZ           Z    Z       Z  ZZZZ
                       ZZMZ         Z     ZZ        ZZMZ
                       ZZMZ         Z      Z        ZZMZ
                        ZZZZ       ZN      Z       ZZZZ
                        ZZMZ       Z        Z      ZZMZ
                         ZZMZ     Z         Z     ZZMZ
                         ZZZZ     Z          Z    ZZZZ
                          ZMZ    Z           Z    ZMZ
                          ZZZZ   Z            Z  ZZZZ
                          ZZZZ  Z             Z  ZZZZ
                           ZMZ  Z             ZZ ZMZ
                           ZMZMZ               ZMZMZ
                           ZZZZZ               ZZZZZ
                            ZMZ                 ZMZ

MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMM http://crane-west.com | Wichita Falls, Texas MMMMMMMMMMMMMMMMM
MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
```