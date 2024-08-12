<p align="center">
    <h1 align="center">SMIS</h1>
</p>

## !! CAUTION !!
- Extreme care must be taken to ensure that only environment variables are updated in the production or staging server. 
  These variables are listed in this install guide.
- We MUST NOT EDIT code directly in the production or staging servers.
- All other changes will first appear in the dev server, from where all updates MUST be taken from.

## Installing for development
- ```bash 
    $ git clone https://github.com/rufusy/smis.git
    $ cd smis
    $ php composer install
    ```
- Create new directory assets under /web if it's missing
- Set owner, group and permissions 0777 recursively to the directories /runtime and /web/assets
- Create or update the file /config/env_type_constants.php with the following info:
- ```yml
  YII_DEBUG: true
  YII_ENV: dev 
  ```
- Create or Update the file /config/db_constants.php with the following info:
- ```yml
  SMIS_DB_SERVER: smis admin dev db address
  SMIS_DB_PORT: smis admin dev db port
  SMIS_DB_NAME: smis admin dev db name
  SMIS_DB_SCHEMA: smis admin dev db schema
  SMIS_DB_USER: smis admin dev db user
  SMIS_DB_PASS: smis admin dev db password 
  
  SMIS_PORTAL_DB_SERVER: smis portal dev db address
  SMIS_PORTAL_DB_PORT: smis portal dev db port
  SMIS_PORTAL_DB_NAME: smis portal dev db name
  SMIS_PORTAL_DB_SCHEMA: smis portal dev db schema
  SMIS_PORTAL_DB_USER: smis portal dev db user
  SMIS_PORTAL_DB_PASS: smis portal dev db password 
  ```
- Create new directory /uploads/admissions/existing under the app root directory if it's missing and set owner, group and permissions 0777 recursively.
  This must contain the Excel file template used to upload students data

## Installing for production

Deploying the application in a production server:

- Copy all files from the directory /smis in the dev server to the directory /smis in the production server
- Create new directory assets under /web if it's missing
- Set owner, group and permissions 0777 recursively to the directories /runtime and /web/assets
- Update the file /config/env_type_constants.php with the following info:
- ```yml
  YII_DEBUG: false
  YII_ENV: prod 
  ```
- Update the file /config/db_constants.php with the following info:
- ```yml
  SMIS_DB_SERVER: smis admin prod db address
  SMIS_DB_PORT: smis admin prod db port
  SMIS_DB_NAME: smis admin prod db name
  SMIS_DB_SCHEMA: smis admin prod db schema
  SMIS_DB_USER: smis admin prod db user
  SMIS_DB_PASS: smis admin prod db password 
  
  SMIS_PORTAL_DB_SERVER: smis portal prod db address
  SMIS_PORTAL_DB_PORT: smis portal prod db port
  SMIS_PORTAL_DB_NAME: smis portal prod db name
  SMIS_PORTAL_DB_SCHEMA: smis portal prod db schema
  SMIS_PORTAL_DB_USER: smis portal prod db user
  SMIS_PORTAL_DB_PASS: smis portal prod db password  
  ```
- Create new directory /uploads/admissions/existing under the app root directory if it's missing and set owner, group and permissions 0777 recursively.
  This must contain the Excel file template used to upload students data

## Installing for staging
- By default, our staging server mimics the dev server.
- Decide which environment (prod or dev) should the staging server mimic and use configurations listed above for either.

### Mailer
- The email variables configured in the applications are set in the file /config/params.php These are the handles users will see in their inboxes.
  These can be edited for the production environment\.
- The app uses the gmail smtp server to send out emails and the email account configured for this is found in the file /config/web.php and 
  /modules/studentRegistration/config/web.php under the mailer component. Note that if you change this setting, you must 
  enable the new account to send out emails, and update the mailer dsn value.

### Password resets
- User credentials, roles and permissions are managed from the user management system.
- Therefore, the password reset link points to that system, and you MUST update the url to point to that system.
- This url is found in the file /config/params.php

### Academic years
- The active academic year and other academic years are set in the file /config/params.php










