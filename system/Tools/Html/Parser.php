<?php

class Tools_Html_Parser
{
    /**
     * 是否开启调试模式
     */
    public $debug = false;
    /**
     * 是否将内容图片获取，并替换url
     */
    public $doLoadImgFlag = true;
    /**
     * 要处理的文件目录。 文件目录级别为 listRootDir/dir/articleFile
     */
    public $listRootDir = '';
    /**
     * 当前处理的文件目录
     */
    public $currentDirname = '/';
    /**
     * 当前处理的文件名字
     */
    protected $currentFilename = '';

    /**
     * 待解析的文件内容
     */
    protected $htmlContent = '';
    /**
     * DOMDocument 实例
     */
    protected $dom = null;
    /**
     * content dom element
     */
    protected $domContent = null;

    protected $authorDomInfo = array(
        'id'            => '',
        'tag'           => 'div',
        'class'         => 'error-content',
        'class_index'   => 0,
        'is_list'       => 0,
    );

    protected $copyfromDomInfo = array(
        'id'            => '',
        'tag'           => 'div',
        'class'         => 'error-content',
        'class_index'   => 0,
        'is_list'       => 0,
    );

    protected $errorDomInfo = array(
        'id'            => '',
        'tag'           => 'div',
        'class'         => 'error-content',
        'class_index'   => 0,
        'is_list'       => 0,
    );
    protected $contentDomInfo = array(
        'method'        => 'id', // id, class, auto:  通过id获取/通过class获取/自动获取
        // 根据id获取dom， 或者根据 tag和class获取，然后定位到具体的index
        'id'            => '',
        'tag'           => 'div',
        'class'         => 'container',
        'index'         => '0',
        // 定位到具体位置后， 获取相关子项
        'sub_tag'       => '',
        'sub_class'     => '',
        'sub_index'     => '',
        // 是否需要解析成列表类型的数据
        'is_list'       => 1,
    );

    protected $titleDomInfo = array(
        'id'            => '',
        'tag'           => 'div',
        'class'         => '',
        'index'         => 0,
    );

    protected $menuDomInfo = array(
        // 根据id获取dom， 或者根据 tag和class获取，然后定位到具体的index
        'id'            => '',
        'tag'           => 'div',
        'class'         => 'container',
        'index'         => '0',
        // 定位到具体位置后， 获取相关子项
        'sub_tag'       => 'a',
        'sub_class'     => 'tag',
        'sub_index'     => '',
        // 是否需要解析成列表类型的数据
        'is_list'       => 1,
    );

    public function __construct()
    {
        $this->dom = new DOMDocument();
    }
    public function getDomInstance()
    {
        return $this->dom;
    }

    /**
     * 载入HTML字符串
     * @param string $content 字符串
     * @param string $charset 用来解析字符串的编码
     * @return Tools_Html_Parser self instance
     */
    public function loadDomHTML ($content, $charset='')
    {
        $this->htmlContent = $content;

        if ($charset) {
            $this->htmlContent = '<meta http-equiv="Content-Type" content="text/html; charset='.$charset.'">' . $content;
        }

        $this->dom->loadHTML($this->htmlContent);

        return $this;
    }

    /**
     * 获取图片并存储
     */
    public function processImage ($moduleName, & $elementDom, $baseUrl, $lazyLoadAttrName='')
    {
        $urlInfo = parse_url($baseUrl);
        $scheme = $urlInfo['scheme'];
        $refPath = dirname($urlInfo['path']);
        $host = $urlInfo['host'];
        $attachAccessKey = '';
        // 将图片转换成本站内容
        $imgList = $elementDom->getElementsByTagName('img');
        $imgLength = $imgList->length;
        //echo ' image length is:', $imgLength, '---------     ', __LINE__, "\r\n";
        $attachAccessKey = md5(round(1, 100000) . microtime(true).'icodebang.com');
        //echo __LINE__ , "\r\n";
        //*
        for($i=$imgLength-1; $i>=0; $i--) {
            $_src = $imgList[$i]->getAttribute('src');
            //echo $_src, "=============    ", __LINE__, "\r\n";
            if (! $_src && $lazyLoadAttrName && $imgList[$i]->getAttribute($lazyLoadAttrName)) {
                $_src = $imgList[$i]->getAttribute($lazyLoadAttrName);
            }
            //var_dump($imgList);
            // 先获取到图片属性列表， 存放。 然后再根据列表删除。 直接删除失败
            $attrList = array();
            foreach ($imgList[$i]->attributes as $_attrNode) {
                $attrList[] = $_attrNode->name;
            }
            foreach ($attrList as $_attrName) {
                $imgList[$i]->removeAttribute($_attrName);
            }

            if ( strpos($_src, 'http://')===0 || strpos($_src, 'https://')===0 ) {
            } else if(strpos($_src, '//')===0) {
                $_src = $scheme . '://' . $_src;
            } else if(strpos($_src, '/')===0) {
                $_src = $scheme . '://' . $host . $_src;
            } else {
                $_src = $scheme . '://' . $host . $refPath . '/' . $_src;
            }
            $tryTimes = 3;
            while($tryTimes-- > 0) {
                //echo $_src , "\r\n";
                $_imgData = @ file_get_contents($_src);
                $extInfo = explode('.', strtolower(substr($_src, -5)));
                if (count($extInfo)>1) {
                    $_extension = array_pop($extInfo);
                } else {
                    $_extension = 'png';
                }
                // $_tmpFile = tempnam(sys_get_temp_dir(), 'icb_') . substr($_src, strrpos($_src, '.'));
                // $_result = $_imgData!==false && file_put_contents($_tmpFile, $_imgData);
                $_result = false;
                //echo __LINE__, "\r\n";
                if ($_imgData) {
                    try {
                        if ($this->doLoadImgFlag) {
                            $_uploadInfo = doUploadAttach($moduleName, $attachAccessKey, '1.'.$_extension, $_imgData, $_src);
                        } else {
                            $_uploadInfo = array('url'=>$_src . '.' . $_extension);
                        }
                       //var_dump($_uploadInfo);//exit;
                       // echo $_src , "+++++ image url.\r\n";
                    } catch (Exception $e) {
                        //var_dump($e);
                        throw $e;
                    }
                    if(is_array($_uploadInfo) && isset($_uploadInfo['url']))  {
                        $imgList[$i]->setAttribute('src', $_uploadInfo['url']);
                        //var_dump($_uploadInfo['url']);
                        $_result = true;
                    }
                }
                //echo __LINE__, "\r\n";
                //echo $_tmpFile;
                if ($_result) {
                    break;
                }
            }
        }

        if ($imgLength > 0) {
            return $_result ? $attachAccessKey : false;
        } else {
            return '';
        }
    }

    /**
     * 获取文件列表
     */
    public function loadFileList ($listRootDir)
    {
        $fileList = array();
        $dir = dir($listRootDir);
        while (false!==($dirname=$dir->read())) {
            //Ignore parent- and self-links
            if ($dirname=="." || $dirname=="..") continue;

            $filepath = $listRootDir . DIRECTORY_SEPARATOR . $dirname;
            if (! is_dir($filepath)) continue; // 不是目录， 继续处理下一个文件

            $_fileListInDir = array(); // 目录下的文件列表

            $dirArticle = dir($filepath);
            // 遍历目录
            while (false!==($filename=$dirArticle->read())) {
                //Ignore parent- and self-links
                if ($filename=="." || $filename=="..") continue;

                $_fileListInDir[] = $filename;
            }

            if ($_fileListInDir) {
                $fileList[$dirname] = $_fileListInDir;
            }
        }

        return $fileList;
    }

    public function parse ($content, $charset='')
    {
        $this->htmlContent = $content;
        $this->dom = new DOMDocument();
        if ($charset) {
            $this->htmlContent = '<meta http-equiv="Content-Type" content="text/html; charset='.$charset.'">' . $content;
        }

        $this->dom->loadHTML($this->htmlContent);

        if ($this->parseError () ) {
            return false;
        }

        $menuList = $this->parseMenuList();
        $this->parseContent();
        //$this->processImage();

    }
    public function getDom ($contentDomInfo)
    {
        $content = '';
        $tmpList = array();
        if ($contentDomInfo['id']) {
            $contentDom = $this->dom->getElementById($contentDomInfo['id']);
        } else if ($contentDomInfo['tag']) {
            $domList = $this->dom->getElementsByTagName($contentDomInfo['tag']);
            $index = 0;
            foreach ($domList as $_tmpDom) {
                if ( $contentDomInfo['class'] && (! $_tmpDom->hasAttribute('class')
                 || (strpos($_tmpDom->getAttribute('class'), $contentDomInfo['class'].' ') ===false
                     && strpos($_tmpDom->getAttribute('class'),' '.$contentDomInfo['class']) ===false
                     && $_tmpDom->getAttribute('class')!==$contentDomInfo['class']
                    ) ) ) {
                    continue;
                }
                if (is_numeric($contentDomInfo['index']) && $index == $contentDomInfo['index']) { // 找到了对应的dom
                    $contentDom = $_tmpDom;
                    break;
                } else if (! is_numeric($contentDomInfo['index'])) {
                    $tmpList[] = $_tmpDom;
                }
                $index++;
            }
            // 没有指定dom的序号， 返回列表
            if (!is_numeric($contentDomInfo['index'])) {
                $contentDom = $tmpList;
            }
        }
        if (empty($contentDom)) {
            return $content;
        }

        if ($contentDomInfo['sub_tag']) {
            $tmpList = array();
            is_array($contentDom) OR $contentDom = array($contentDom);
            foreach ($contentDom as $_dom) {

                $domList = $_dom->getElementsByTagName($contentDomInfo['sub_tag']);
                $index = 0;
                foreach ($domList as $_tmpDom) {
                    if ( $contentDomInfo['sub_class'] && (! $_tmpDom->hasAttribute('class')
                    || (strpos($_tmpDom->getAttribute('class'),' '.$contentDomInfo['sub_class']) ===false
                       && strpos($_tmpDom->getAttribute('class'),$contentDomInfo['sub_class'].' ') ===false
                       && $_tmpDom->getAttribute('class')!==$contentDomInfo['sub_class']
                       ) ) ) {
                        continue;
                    }

                    if (is_numeric($contentDomInfo['sub_index']) && $index == $contentDomInfo['sub_index']) { // 找到了对应的dom
                        $tmpList[] = $_tmpDom;
                        break;
                    } else if (! is_numeric($contentDomInfo['sub_index'])) {
                        $tmpList[] = $_tmpDom;
                    }
                    $index++;
                }

                $contentDom = $tmpList;
            }
        }

        return $contentDom;

    }

    public function generateDomHtml($elementDom)
    {
        return $this->dom->saveHTML($elementDom);
    }


    public function parseContent ($contentDomInfo=array())
    {
        //$contentDom = $this->getDom($this->contentDomInfo);
        $contentDom = $this->getDom($contentDomInfo);
        if ($contentDom) {
            return $this->dom->saveHTML($contentDom);
        } else {
            return '';
        }
    }

    public function parseMenuList ($menuDomInfo=array())
    {
        // 从左侧菜单解析章节列表
        $chapterList = array();
        // if ($this->menuDomInfo['id']) {
        //     $menuContainerDom = $this->dom->getElementById($this->menuDomInfo['id']);
        // } else if ($this->menuDomInfo['tag'] && $this->menuDomInfo['class'] && $this->menuDomInfo['class_index']) {
        //     $domList = $this->dom->getElementsByTagName($this->menuDomInfo['tag']);
        //     $index = 0;
        //     foreach ($domList as $_tmpDom) {
        //         if (! $_tmpDom->hasAttribute('class')
        //          || strpos($_tmpDom->getAttribute('class'),$this->menuDomInfo['class']) ===false ) {
        //             continue;
        //         }
        //         if ($index == $this->menuDomInfo['class_index']) { // 找到了对应的dom
        //             $menuContainerDom = $_tmpDom;
        //             break;
        //         }
        //         $index++;
        //     }
        // }
        //$menuContainerDom = $this->getDom($this->menuDomInfo);
        $menuContainerDom = $this->getDom($menuDomInfo);

        if (empty($menuContainerDom)) {
            return $chapterList;
        }

        foreach ($menuContainerDom as $_chapterElement) {
            $link = $_chapterElement->getAttribute('href');
            $chapterList[] = array('link'=>$link,'title'=>trim($_chapterElement->nodeValue));
        }

        return $chapterList;
    }

    public function parseError ()
    {
        $errorDom = null;
        if ($this->errorDomInfo['id']) {
            $errorDom = $this->dom->getElementById($this->errorDomInfo['id']);
        } else if ($this->errorDomInfo['class'] && $this->errorDomInfo['tag']
         && $this->errorDomInfo['class_index']) {
            $domList = $this->dom->getElementsByTagName($this->errorDomInfo['tag']);
            $index = 0;
            foreach ($domList as $_tmpDom) {
                if (! $_tmpDom->hasAttribute('class')
                 || strpos($_tmpDom->getAttribute('class'),$this->errorDomInfo['class']) ===false ) {
                    continue;
                }
                if ($index == $this->errorDomInfo['class_index']) { // 找到了对应的dom
                    $errorDom = $_tmpDom;
                    break;
                }
                $index++;
            }
        }
        $hasError = is_object($errorDom) && $errorDom->length > 0;

        return $hasError;
    }

    /**
     * 将需要替换的字符串， 做映射数组来替换
     */
    public function replaceKeywords (array $replacementMap)
    {
        $searchList = $replaceList = array();
        foreach ($replacementMap as $_search=>$_replace) {
            $content = str_replace($searchList, $replaceList, $this->content);
        }

        return $content;
    }
}

/* EOF */
