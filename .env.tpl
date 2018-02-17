APP_NAME="E-Liquid Manager"
APP_ENV={{ keyOrDefault "elm/env" "production" }}
APP_KEY={{ key "elm/key" }}
APP_DEBUG={{ keyOrDefault "elm/debug" "false" }}
APP_LOG_LEVEL={{ keyOrDefault "elm/log-level" "info" }}
APP_URL={{ key "elm/url" }}

DB_CONNECTION=mysql
DB_HOST={{ key "elm/db-host" }}
DB_PORT={{ keyOrDefault "elm/db-port" "3306" }}
DB_DATABASE={{ key "elm/db-name" }}
DB_USERNAME={{ key "elm/db-user" }}
DB_PASSWORD={{ key "elm/db-pass" }}

BROADCAST_DRIVER=log
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_DRIVER=sync

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST={{ key "elm/mail-host" }}
MAIL_PORT={{ key "elm/mail-port" }}
MAIL_USERNAME={{ key "elm/mail-user" }}
MAIL_PASSWORD={{ key "elm/mail-pass" }}
MAIL_ENCRYPTION=null

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

GA_KEY="{{ key "elm/ga-key" }}"
