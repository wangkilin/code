#!/bin/sh
# 在服务器端输出数据库中文章表对应id的最大值， 写入/tmp/下。 供scp复制后本地做比较。

#获取目录
DIR=${BASH_SOURCE%/*} # /dir1/dir2/dir3

. $DIR/global.config.sh  # 引入数据库配置

# 判断是否有数据库密码， 使用不同的命令行参数
if [[ "$DB_PASSWORD" = "" ]]; then
    mysql -u $DB_USERNAME -D${DB_DATABASE} << EOF > /tmp/MAX_ARTICLE_ID.txt
    SELECT MAX(id) AS max_id FROM icb_article;
EOF

    mysql -u $DB_USERNAME -D${DB_DATABASE} << EOF > /tmp/MAX_ARTICLE_POST_ID.txt
    SELECT MAX(post_id) AS max_id FROM icb_posts_index;
EOF

else
    mysql -u $DB_USERNAME -p${DB_PASSWORD} -D${DB_DATABASE} << EOF > /tmp/MAX_ARTICLE_ID.txt
    SELECT MAX(id) AS max_id FROM icb_article;
EOF

    mysql -u $DB_USERNAME -p${DB_PASSWORD} -D${DB_DATABASE} << EOF > /tmp/MAX_ARTICLE_POST_ID.txt
    SELECT MAX(post_id) AS max_id FROM icb_posts_index;
EOF

fi

