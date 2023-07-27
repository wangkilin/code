#!/bin/sh
# 备份数据库操作. 1.将远程数据库备份复制过来。2.将7天前的备份删除释放空间


#获取目录
DIR=${BASH_SOURCE%/*} # /dir1/dir2/dir3

. $DIR/global.config.sh  # 引入配置

DB_TABLES="icb_sinho_employee_workload icb_sinho_company_workload"  #要备份的表名列表

NOW_DATE=`date "+%Y-%m-%d"`                     # 当前日期，用于生成数据库备份文件名称
NOW_HOUR=`date "+%H"`
if [[ "$OSTYPE" =~ "linux" ]]; then
    ONE_WEEK_AGO_DATE=`date -d "-7day"  "+%Y-%m-%d"`      # 7天前日期， 用于生成数据库备份文件名， 执行删除文件操作， 释放空间
else
    ONE_WEEK_AGO_DATE=`date -v-7d "+%Y-%m-%d"`      # 7天前日期， 用于生成数据库备份文件名， 执行删除文件操作， 释放空间
fi

AM_PM=""
if [[ $NOW_HOUR -ge 12 ]]; then
    AM_PM="PM"
else
    AM_PM="AM"
fi

NOW_DATE="${NOW_DATE}_${AM_PM}"
ONE_WEEK_AGO_DATE="${ONE_WEEK_AGO_DATE}_${AM_PM}"

scp -P $SSH_PORT $SSH_USERNAME@$SSH_HOST:$SSH_STORE_DIR/${DB_DATABASE_SY}_$NOW_DATE.tgz $LOCAL_STORE_DIR/


# 2. 删除7点前的打包文件， 释放空间
if [ -e $LOCAL_STORE_DIR/${DB_DATABASE_SY}_$ONE_WEEK_AGO_DATE.tgz ]; then
    rm -f $LOCAL_STORE_DIR/${DB_DATABASE_SY}_$ONE_WEEK_AGO_DATE.tgz
fi

# 解压打包文件， 执行sql内容，导入数据
cd $LOCAL_STORE_DIR
tar zxf ${DB_DATABASE_SY}_$NOW_DATE.tgz
# 替换mysql8到mysql5不支持的字符集
sed -ie 's/COLLATE=utf8mb4_0900_ai_ci//g' $LOCAL_STORE_DIR/${DB_DATABASE_SY}_$NOW_DATE.sql
#sed -ie 's/CHARSET=utf8mb3/CHARSET=utf8/g' $LOCAL_STORE_DIR/${DB_DATABASE_SY}_$NOW_DATE.sql

# 根据数据库密码是否为空， 执行不同的命令
if [[ "$DB_PASSWORD" = "" ]]; then
    /usr/local/bin/mysql -u $DB_USERNAME -D$DB_DATABASE << EOF
    source $LOCAL_STORE_DIR/${DB_DATABASE_SY}_$NOW_DATE.sql;
EOF

else

    /usr/local/bin/mysql -u $DB_USERNAME -p$DB_PASSWORD -D$DB_DATABASE << EOF
    source $LOCAL_STORE_DIR/${DB_DATABASE_SY}_$NOW_DATE.sql;
EOF

fi

rm -f $LOCAL_STORE_DIR/${DB_DATABASE_SY}_$NOW_DATE.sql

# 退出
exit 0;
