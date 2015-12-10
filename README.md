# docshare
Create repository of Google Doc PDF and public links, always the latest version available

##Overview

Build a shareable directory of Google Doc or Google Drive PDF links, so that the most recent version is downloaded when a user clicks a link. Currently the project does not use a database, but rather flat txt files - orginally designed for easy export, but could be connected to MySQL.

Adding files is passcode protected, so that the page can be shared without concern over vandalism.

##Installation

Requires PHP5, tested on Apache. Place folder in server root and visit in a web browser.

##Defaults

The default passcode for adding new documents is 1234.

##Future plans

Adding more doc types - Google Sheets, Google Slides

Allow document removal without editing txt files

Improved styling, clean up styles.css