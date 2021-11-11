#!/bin/bash

# 参数1为输入文件路劲
FILE=$1

# 检查文件是否存在， 不存在直接提示错误信息退出
if [ ! -e $FILE ]; then
    echo "$FILE  is NOT exist"
    exit 0
fi

# 判断是否为有效pdf文件, 不是pdf文件， 直接退出
if [ `pdfinfo $FILE 2>/dev/null | wc -l ` -le 1 ]; then
    echo "$FILE is NOT a PDF"
    exit 0
fi

# 生成临时文件， 将 gs分析的pdf信息输入到临时文件中
tmp_file=`mktemp -t gs`
#echo $tmp_file
gs -q -o - -sDEVICE=inkcov $FILE >  $tmp_file
#cat $tmp_file
COLOR_PAGE_NUM=""
INDEX=1
# 读取临时文件的每行， 获取对应的 CMY值。 如果CMY值都为0，那么对应的页码就是黑白色
while read C M Y left;
do
    #echo $left
    # 前后有空格的等号， 用户判断逻辑
    if [ "$C-$M-$Y" != "0.00000-0.00000-0.00000" ] ; then
    # CMY值不是都为0， 对应的pdf页面是彩色的。 将页码存放
        COLOR_PAGE_NUM="$COLOR_PAGE_NUM $INDEX"
    fi
    INDEX=$((INDEX+1))
done <  $tmp_file

#echo $COLOR_PAGE_NUM
#echo $INDEX
# 删除临时文件
rm -f $tmp_file
# 提取pdf中的彩色页码，生成新的pdf文件
pdftk $FILE cat $COLOR_PAGE_NUM output $FILE.pdf

exit 0;
