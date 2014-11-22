InspIRCd Xlines Viewer
---

Built for a (procedural) assignment, this will parse an InspIRCd xlines.db database and allow you to list/filter through them.
Usage of this software is simple, and requires a minimum of PHP5.3 installed on ones computer/server.

----

##Configuration##

While it is possible to specify a file manually using the command-line version of this application, a file path is pulled from the configuration file the rest of the time.  This configuration file is xlines.ini, and it can be configured as described below:

```ini
[xlines]
database_path=example.db
date_format=d/m/Y H:i:s
date_timezone=Europe/London
```

- `database_path` is a relative path to the location of the database file, and so for example can be used to point to a /var/ircd folder should this be reading live data.
- `date_format` is a string used to determine how the dates should be output in the JSON.  Note that only the default format will work with the custom sorting functionality used on the front-end.
- `date_timezone` is included to allow the system timezone to be overriden, useful for example when system time may differ from the times used in the databsae.


This can be run multiple ways, detailed below:

##Command-line usage:##

This tool has been designed to parse the xline database in such a way that it can be used on the command line, either by passing in the filename as a command line parameter, or by directly piping in the contents of the database using standard input.

`./xlines.php example.db`

`cat example.db | ./xlines.php`


##Web-usage:##

Included is a frontend which will automatically query xlines.php via AJAX and then dynamically populate and tabulate the information contained therein.  You can get to the front-end by browsing to the index.html stored inside the directory (named index so as to automatically be detected by webservers).

Simply copy the directory to your webserver, and then alter the configuration file as documented above, which will then allow you to visualise this information directly by accessing it in your web-browser.

As of PHP 5.4, this can actually be run from the command line by leveraging PHP5.4's inbuilt web-server, simply by running the following from the root of the project directory:

`php -S localhost:8080`

Running this will then allow you to see the database from `http://localhost:8080/`

(License included in LICENSE.md; though this is a small project, please provide full attribution)
