#!/bin/sh
# 备份数据库操作. 1.将远程数据库备份复制过来。2.将7天前的备份删除释放空间

#获取目录
DIR=${BASH_SOURCE%/*} # /dir1/dir2/dir3

. $DIR/global.config.sh  # 引入配置

# 获取服务器端的文章最大的id值。 根据这个id值， 获取到最新的数据。
scp -P $SSH_PORT $SSH_USERNAME@$SSH_HOST:$SSH_STORE_DIR/MAX_ARTICLE_ID.txt $LOCAL_STORE_DIR/ > /dev/null 2>&1;

scp -P $SSH_PORT $SSH_USERNAME@$SSH_HOST:$SSH_STORE_DIR/MAX_ARTICLE_POST_ID.txt $LOCAL_STORE_DIR/ > /dev/null 2>&1;
# 如果没有从服务器端获取到文章id数据，直接退出
if [ ! -e $LOCAL_STORE_DIR/MAX_ARTICLE_ID.txt ]; then
    exit 0
fi
if [ ! -e $LOCAL_STORE_DIR/MAX_ARTICLE_POST_ID.txt ]; then
    exit 0
fi
# 从文件中获取到id值
ARTICLE_ID=`tail -1 $LOCAL_STORE_DIR/MAX_ARTICLE_ID.txt`
ARTICLE_POST_ID=`tail -1 $LOCAL_STORE_DIR/MAX_ARTICLE_POST_ID.txt`

# 数据表中已存在数据记录， 只做id大小简单判断
if [[ $ARTICLE_ID -le 100000 ]]; then
    exit 0
fi
if [[ $ARTICLE_POST_ID -le 100000 ]]; then
    exit 0
fi
# id值已经获取到， 删除对饮的文件
rm -f $LOCAL_STORE_DIR/MAX_ARTICLE_ID.txt $LOCAL_STORE_DIR/MAX_ARTICLE_POST_ID.txt

# 根据是否设置了数据库密码，使用不用的命令
if [[ "$DB_PASSWORD" = "" ]]; then
    # 1. 数据库备份, 获取到 > id 的数据
    /usr/local/bin/mysqldump -h $DB_HOST -u $DB_USERNAME $DB_DATABASE icb_posts_index --column-statistics=0 --no-create-info  -w "post_id>${ARTICLE_POST_ID}"  > $LOCAL_STORE_DIR/article_post_great_than_${ARTICLE_POST_ID}.sql
    /usr/local/bin/mysqldump -h $DB_HOST -u $DB_USERNAME $DB_DATABASE icb_article --column-statistics=0 --no-create-info  -w "id>${ARTICLE_ID}"  > $LOCAL_STORE_DIR/article_great_than_${ARTICLE_ID}.sql
else
    # 1. 数据库备份
    /usr/local/bin/mysqldump -h $DB_HOST -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE icb_posts_index --column-statistics=0 --no-create-info  -w "post_id>${ARTICLE_POST_ID}"  > $LOCAL_STORE_DIR/article_post_great_than_${ARTICLE_POST_ID}.sql
    /usr/local/bin/mysqldump -h $DB_HOST -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE icb_article --column-statistics=0 --no-create-info  -w "id>${ARTICLE_ID}"  > $LOCAL_STORE_DIR/article_great_than_${ARTICLE_ID}.sql
fi

chmod 666 $LOCAL_STORE_DIR/article_post_great_than_${ARTICLE_POST_ID}.sql
chmod 666 $LOCAL_STORE_DIR/article_great_than_${ARTICLE_ID}.sql

# 1.1 打包数据库备份文件
cd $LOCAL_STORE_DIR
tar zcf $LOCAL_STORE_DIR/article_great_than_${ARTICLE_ID}.tgz  article_great_than_${ARTICLE_ID}.sql article_post_great_than_${ARTICLE_POST_ID}.sql
# 1.2 打包后，将刚生成的sql文件删除，只保留打包文件，释放空间。
rm -f $LOCAL_STORE_DIR/article_great_than_${ARTICLE_ID}.sql $LOCAL_STORE_DIR/article_post_great_than_${ARTICLE_POST_ID}.sql
# 将打包的数据文件包远程复制到服务器
scp -P $SSH_PORT $LOCAL_STORE_DIR/article_great_than_${ARTICLE_ID}.tgz $SSH_USERNAME@$SSH_HOST:$SSH_STORE_DIR/


# 2. 删除打包文件， 释放空间
if [ -e $LOCAL_STORE_DIR/article_great_than_${ARTICLE_ID}.tgz ]; then
    rm -f $LOCAL_STORE_DIR/article_great_than_${ARTICLE_ID}.tgz
fi

# 退出
exit 0;
