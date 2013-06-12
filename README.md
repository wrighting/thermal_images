thermal_images
==============

This application uses one or more spreadsheet index files to display images

Also required in the webapp directory are:
http://code.google.com/p/php-excel-reader/
http://code.google.com/p/php-spreadsheetreader/
http://code.google.com/p/google-api-php-client/

You will also need to enable the Drive API via the Google APIs Console

Once this is done edit the local_settings.php file and move, or link to, it in the google-api-php-client/src/ directory
Next create the database using resources/schema.sql and enter the settings in db.php
