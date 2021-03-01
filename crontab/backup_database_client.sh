#!/bin/sh
# 备份数据库操作. 1.将远程数据库备份复制过来。2.将7天前的备份删除释放空间

SSH_HOST=www.ekotlin.com           # 远程主机地址
SSH_USERNAME=root            # 远程主机用户名
#SSH_PASSWORD=password         # 远程主机密码
SSH_PORT=8433                  # 远程主机端口
SSH_STORE_DIR=/tmp           # 远程保存数据备份文件地址
LOCAL_STORE_DIR=/tmp         # 本地保存地址
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

scp -P $SSH_PORT $SSH_USERNAME@$SSH_HOST:$SSH_STORE_DIR/sinho_db_$NOW_DATE.tgz $LOCAL_STORE_DIR/


# 2. 删除7点前的打包文件， 释放空间
if [ -e $LOCAL_STORE_DIR/sinho_db_$ONE_WEEK_AGO_DATE.tgz ]; then
    rm -f $LOCAL_STORE_DIR/sinho_db_$ONE_WEEK_AGO_DATE.tgz
fi

# 退出
exit 0;
