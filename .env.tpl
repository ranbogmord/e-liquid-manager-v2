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
CACHE_DRIVER={{ keyOrDefault "elm/cache-driver" "file" }}
SESSION_DRIVER={{ keyOrDefault "elm/session-driver" "file" }}
SESSION_LIFETIME=120
QUEUE_DRIVER={{ keyOrDefault "elm/queue-driver" "sync" }}

REDIS_HOST={{ keyOrDefault "elm/redis-host" "127.0.0.1" }}
REDIS_PASSWORD=null
REDIS_PORT={{ keyOrDefault "elm/redis-port" "6379" }}

MAIL_DRIVER=smtp
MAIL_HOST={{ keyOrDefault "elm/mail-host" "null" }}
MAIL_PORT={{ keyOrDefault "elm/mail-port" "null" }}
MAIL_USERNAME={{ keyOrDefault "elm/mail-user" "null" }}
MAIL_PASSWORD={{ keyOrDefault "elm/mail-pass" "null" }}
MAIL_FROM_ADDRESS="{{ keyOrDefault "elm/mail-from-address" "" }}"
MAIL_FROM_NAME="{{ keyOrDefault "elm/mail-from-name" "null" }}"
MAIL_ENCRYPTION=null

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

GA_KEY={{ keyOrDefault "elm/ga-key" "null" }}
