#!/bin/sh
# 备份数据库操作. 1.将数据库备份打包存放。2.将7天前的备份删除释放空间

DB_HOST=localhost               # 数据库主机地址
DB_USERNAME=root		# 数据库用户名
DB_PASSWORD=""            # 数据库密码
DB_DATABASE=WeCenter            # 数据库名
DB_TABLES="icb_sinho_employee_workload icb_sinho_company_workload icb_users_ask_leave icb_users_ask_leave_date icb_users_attribute icb_sinho_schedule icb_sinho_key_value"  #要备份的表名列表
DIR_STORE=/tmp

NOW_DATE=`date "+%Y-%m-%d"`                     # 当前日期，用于生成数据库备份文件名称
NOW_HOUR=`date "+%H"`
AM_PM=""
CWD=`pwd`
if [[ $NOW_HOUR -ge 12 ]]; then
    AM_PM="PM"
else
    AM_PM="AM"
fi

if [[ "$OSTYPE" =~ "linux" ]]; then
    ONE_WEEK_AGO_DATE=`date -d "-7day"  "+%Y-%m-%d"`      # 7天前日期， 用于生成数据库备份文件名， 执行删除文件操作， 释放空间
else
    ONE_WEEK_AGO_DATE=`date -v-7d "+%Y-%m-%d"`      # 7天前日期， 用于生成数据库备份文件名， 执行删除文件操作， 释放空间
fi

NOW_DATE="${NOW_DATE}_${AM_PM}"
ONE_WEEK_AGO_DATE="${ONE_WEEK_AGO_DATE}_${AM_PM}"

if [[ "$DB_PASSWORD" = "" ]]; then
    # 1. 数据库备份
    mysqldump -h $DB_HOST -u $DB_USERNAME $DB_DATABASE  $DB_TABLES> $DIR_STORE/isinho_$NOW_DATE.sql
else
    # 1. 数据库备份
    mysqldump -h $DB_HOST -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE  $DB_TABLES> $DIR_STORE/isinho_$NOW_DATE.sql
fi
# 1.1 打包数据库备份文件
cd $DIR_STORE
tar zcf $DIR_STORE/sinho_db_$NOW_DATE.tgz  isinho_$NOW_DATE.sql
# 1.2 打包后，将刚生成的sql文件删除，只保留打包文件，释放空间。
rm -f $DIR_STORE/isinho_$NOW_DATE.sql

# 2. 删除7点前的打包文件， 释放空间
if [ -e $DIR_STORE/sinho_db_$ONE_WEEK_AGO_DATE.tgz ]; then
    rm -f $DIR_STORE/sinho_db_$ONE_WEEK_AGO_DATE.tgz
fi

cd $CWD
# 退出
exit 0;


################# END ########################

mysql -u root -proot -Dtongxinonlinev1 << EOF >> /tmp/mysql_test.log
#UPDATE tx_account_subject_general SET code = '5802' WHERE id = 559;
#ALTER TABLE tx_account_subject_general ADD UNIQUE( code, criterion);
EOF
#x=1001
#while [ $x -le 1999 ]

#do
#mysql -u root -proot  -Dtongxinonlinev1 << EOF >> /tmp/mysql_test.log
#INSERT INTO tx_account_subject_general (category, category_id, pid, level, path, code, name, unit, direction, criterion, side, available)
#VALUES
#        (1, 11, 0, 1, '/0/', $x, '科目_$x', '', 0, 1, 0, 1);

#EOF
#x=`expr $x + 1`
#done
x=10010001
while [ $x -le 10019999 ]

do
mysql -u root -proot -Dtongxinonlinev1 << EOF >> /tmp/mysql_test.log
INSERT INTO tx_account_subject_general (category, category_id, pid, level, path, code, name, unit, direction, criterion, side, available)
VALUES
        (1, 11, 1, 2, '/0/1/', $x, '科目_$x', '', 0, 1, 0, 1);

EOF
x=`expr $x + 1`
done



exit 0;
