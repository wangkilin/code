#!/bin/sh
# 导入数据库操作.

#获取目录
DIR=${BASH_SOURCE%/*} # /dir1/dir2/dir3

. $DIR/global.config.sh  # 引入配置
# 生成存放文章最大id的文件
source $DIR/export_icodebang_article_max_id.sh
# 如果没有生成对应的存放id文件， 直接退出
if [ ! -e $SSH_STORE_DIR/MAX_ARTICLE_ID.txt ]; then
    echo "No article id found"
    exit 0
fi
if [ ! -e $SSH_STORE_DIR/MAX_ARTICLE_POST_ID.txt ]; then
    echo "No article post id found"
    exit 0
fi
# 获取到文章id
ARTICLE_ID=`tail -1 $SSH_STORE_DIR/MAX_ARTICLE_ID.txt`
ARTICLE_POST_ID=`tail -1 $SSH_STORE_DIR/MAX_ARTICLE_POST_ID.txt`

# 数据表中已存在数据记录， 只做id大小简单判断
if [[ $ARTICLE_ID -le 100000 ]]; then
    exit 0
fi
if [[ $ARTICLE_POST_ID -le 100000 ]]; then
    exit 0
fi

# 对应的数据包不存在， 直接退出
if [ ! -e $SSH_STORE_DIR/article_great_than_${ARTICLE_ID}.tgz ]; then
    echo "No article data tgz found"
    exit 0
fi
# 切换到文件存放目录， 方便下一步解压文件
cd $SSH_STORE_DIR
# 加压文件， 删除包
tar zxf $SSH_STORE_DIR/article_great_than_${ARTICLE_ID}.tgz
rm -f $SSH_STORE_DIR/article_great_than_${ARTICLE_ID}.tgz
# 判断是否解压出了对应的数据文件， 如果不存在数据文件，直接退出
if [ ! -e $SSH_STORE_DIR/article_great_than_${ARTICLE_ID}.sql ]; then
    echo "No article sql found"
    exit 0
fi
if [ ! -e $SSH_STORE_DIR/article_post_great_than_${ARTICLE_POST_ID}.sql ]; then
    echo "No article post sql found"
    exit 0
fi
# 根据数据库是否设置了密码， 执行不同的命令, 导入数据
if [[ "$DB_PASSWORD" = "" ]]; then
    # 1. 数据库备份

    mysql -u $DB_USERNAME -D$DB_DATABASE << EOF
    source $SSH_STORE_DIR/article_great_than_${ARTICLE_POST_ID}.sql;
    source $SSH_STORE_DIR/article_post_great_than_${ARTICLE_ID}.sql;
EOF

else
    # 1. 数据库备份
    mysql -u $DB_USERNAME -p$DB_PASSWORD -D$DB_DATABASE << EOF
    source $SSH_STORE_DIR/article_great_than_${ARTICLE_POST_ID}.sql;
    source $SSH_STORE_DIR/article_post_great_than_${ARTICLE_ID}.sql;
EOF
# 删除sql文件
rm -f $SSH_STORE_DIR/article_great_than_${ARTICLE_ID}.sql $SSH_STORE_DIR/article_post_great_than_${ARTICLE_POST_ID}.sql

fi
# 导入数据后， 重新获取并生成文章最大id
. $DIR/export_icodebang_article_max_id.sh

# 退出
exit 0;
