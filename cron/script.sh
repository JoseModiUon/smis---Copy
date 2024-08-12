#!/bin/bash

# Sync registration documents
/usr/bin/php /var/www/html/smis/yii student-registration/documents/sync;

# Sync student profile updates
/usr/bin/php /var/www/html/smis/yii student-registration/profile/sync;

# Sync session reporting dates
/usr/bin/php /var/www/html/smis/yii student-registration/session-reporting/sync;

# Sync pending id requests
/usr/bin/php /var/www/html/smis/yii student-id/id-request/new


# Sync pending id requests
/usr/bin/php /var/www/html/smis/yii student-records/deferment/sync

