#/bin/sh

IS_MYSQL_RUNNING=`/bin/systemctl status  mysql.service | grep active | grep running | wc -l`
if [[ $IS_MYSQL_RUNNING = 0 ]]; then
    myisamchk -f /var/lib/mysql/WeCenter/icb_article.MYI
    /bin/systemctl start  mysql.service

fi
