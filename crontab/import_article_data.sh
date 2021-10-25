#!/bin/sh
# 导入数据库操作.

#获取目录
DIR=${BASH_SOURCE%/*} # /dir1/dir2/dir3

. $DIR/global.config.sh  # 引入配置

source $DIR/export_icodebang_article_max_id.sh

if [ ! -e $SSH_STORE_DIR/MAX_ARTICLE_ID.txt ]; then
    echo "No article id found"
    exit 0
fi

ARTICLE_ID=`tail -1 $SSH_STORE_DIR/MAX_ARTICLE_ID.txt`

# 数据表中已存在数据记录， 只做id大小简单判断
if [[ $ARTICLE_ID -le 100000 ]]; then
    exit 0
fi


if [ ! -e $SSH_STORE_DIR/article_great_than_${ARTICLE_ID}.tgz ]; then
    echo "No article data tgz found"
    exit 0
fi

cd $SSH_STORE_DIR

tar zxf $SSH_STORE_DIR/article_great_than_${ARTICLE_ID}.tgz
rm -f $SSH_STORE_DIR/article_great_than_${ARTICLE_ID}.tgz

if [ ! -e $SSH_STORE_DIR/article_great_than_${ARTICLE_ID}.sql ]; then
    echo "No article sql found"
    exit 0
fi
if [ ! -e $SSH_STORE_DIR/article_post_great_than_${ARTICLE_ID}.sql ]; then
    echo "No article post sql found"
    exit 0
fi

if [[ "$DB_PASSWORD" = "" ]]; then
    # 1. 数据库备份

    mysql -u $DB_USERNAME -proot -D$DB_DATABASE << EOF
    source $SSH_STORE_DIR/article_great_than_${ARTICLE_ID}.sql;
    source $SSH_STORE_DIR/article_post_great_than_${ARTICLE_ID}.sql;
EOF

else
    # 1. 数据库备份
    mysql -u $DB_USERNAME -p$DB_PASSWORD -D$DB_DATABASE << EOF
    source $SSH_STORE_DIR/article_great_than_${ARTICLE_ID}.sql;
    source $SSH_STORE_DIR/article_post_great_than_${ARTICLE_ID}.sql;
EOF

rm -f $SSH_STORE_DIR/article_great_than_${ARTICLE_ID}.sql $SSH_STORE_DIR/article_post_great_than_${ARTICLE_ID}.sql

fi

. $DIR/export_icodebang_article_max_id.sh

# 退出
exit 0;
