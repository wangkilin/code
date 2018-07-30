<?php
class tagModel extends Model
{
    /**
     * 根据ids获取标签和分类关系列表
     * @param string $type 'tag' 或者 'catgory'
     * @param array $ids id列表
     * @return multitype:
     */
    public function getRelationsByIds ($type, $ids)
    {
        $relations = array();
        array_walk_recursive($ids, 'intval');
        $type == 'tag' ? 'tag_id' : 'category_id';
        $sql = 'SELECT * FROM ' . $this->get_table('tag_category_relation') . '
                WHERE '    . $type . ' IN ( ' . join(',', $ids) . ')';
        return $this->query_all($sql);
    }
    /**
     * 获取标签和分类关系列表
     * @param string $bindType 关系列表绑定依照参数是基于 Tag还是category
     * @return Ambigous <multitype:, multitype:multitype: unknown , unknown>
     */
    public function getTagCategoryRelations ($bindType='tag', $id=null)
    {
        static $relationsByTag = array();
        static $relationsByCategory = array();

        if (! $relationsByTag) {
            $list = $this->fetch_all('tag_category_relation');
            foreach ($list as $_relation) {
                if (! isset($relationsByCategory[$_relation['category_id']])) {
                    $relationsByCategory[$_relation['category_id']] = array();
                }
                if (! isset($relationsByTag[$_relation['tag_id']])) {
                    $relationsByTag[$_relation['tag_id']] = array();
                }
                $relationsByTag[$_relation['tag_id']] [] = $_relation['category_id'];
                $relationsByCategory[$_relation['category_id']] = $_relation['tag_id'];
            }
        }
        if('tag'==$bindType) {
            $relations = $relationsByTag;
        } else if ('category'==$bindType) {
            $relations = $relationsByCategory;
        } else {
            $relations = array();
        }
        if ($id) {
            if (isset($relations[$id])) {
                $relations = $relations[$id];
            } else {
                $relations = array();
            }
        }

        return $relations;
    }
    /**
     * 通过id获取技能分类信息
     * @param int $id
     * @return Ambigous <multitype:, unknown>
     */
    public function getTagCategoryById ($id)
    {
        $categories = $this->getAllTagCategory('sort ASC, id ASC');
        $category = isset($categories[$id]) ? $categories[$id] : array();

        return $category;
    }
    /**
     * 通过token获取技能分类信息
     * @param string $token
     * @return Ambigous <multitype:, unknown>
     */
    public function getTagCategoryByToken ($token)
    {
        $category = array();
        $categories = $this->getAllTagCategory('sort ASC, id ASC');
        foreach ($categories as $_categoryInfo) {
            if ($_categoryInfo['url_token'] == $token) {
                $category = $_categoryInfo;
                break;
            }
        }
        return $category;
    }
    /**
     * 获取全部技能分类列表
     * @param string $order 排序方式
     * @return array
     */
    public function getAllTagCategory ($order = 'sort ASC,id ASC')
    {
        static $categories = array();

        if (! $categories) {
            $list = (array) $this->fetch_all('tag_category', '', $order);
            foreach ($list as $_category) {
                $_category['url_token']=='' AND $_category['url_token'] = $_category['id'];
                $categories[$_category['id']] = $_category;
            }
        }

        return $categories;
    }

    /**
     * 根据条件获取标签分类
     * @param unknown $where
     * @param string $order
     * @param number $limit
     * @param string $page
     * @return Ambigous <unknown, multitype:>
     */
    public function getTagCategory ($where, $order = 'sort ASC,id ASC', $limit = 10, $page=null)
    {
        if ($categories = $this->fetch_page('tag_category', $where, $order, $page, $limit)) {
            foreach ($list as $_category) {
                $_category['url_token']=='' AND $_category['url_token'] = $_category['id'];
                $categories[$_category['id']] = $_category;
            }
        }

        return $categories;
    }

    /**
     * 根据类型获取it技能列表
     * @param number $categoryId
     * @param string $order
     * @return multitype:|unknown
     */
    public function getTagsByCategoryId($categoryId = null, $order = 'sort ASC,id ASC')
    {
        static $tagsList = array();

        if (! $tagsList[$categoryId]) {
            $sql = 'SELECT * FROM ' . $this->get_table('tag') . ' AS t
                    INNER JOIN ' . $this->get_table('tag_category_relation') . ' AS r
                    ON t.id = r.tag_id';
            if (isset($categoryId)) {
                $sql .= ' WHERE r.category_id = ' . $this->quote($categoryId);
            }
            $tags = $this->query_all($sql);

            if ($tags) {
                foreach ($tags AS $tag) {
                    isset($tagsList[$tag['category_id']]) OR $tagsList[$tag['category_id']] = array();
                    $tagsList[$tag['category_id']][$tag['tag_id']] = $tag;
                }
            }
        }

        if (isset($categoryId)) {
            $tags = isset($tagsList[$categoryId]) ? $tagsList[$categoryId] : array();
        } else {
            $tags = $tagsList;
        }

        return $tags;
    }

    /* 获取分类 HTML 数据 */
    public function buildCategoryDropdownHtml($selectedInfo = array(), $subPrefix = '--', $withChild = true)
    {
        $categoryList = $this->getAllTagCategory();
        $tagList    = $this->getTagsByCategoryId();

        if (! is_array($selectedInfo) || ! isset($selectedInfo['type_id'], $selectedInfo['type'])) {
            $selectedInfo = array('type_id'=>0, 'type'=>'category');
        }

        $html = '';
        foreach ($categoryList AS $categoryId => $category) {
            $selected = '';
            if ('category'==$selectedInfo['type'] && $selectedInfo['type_id'] == $category['id']) {
                $selected =  'selected="selected"';
            }

            $html .= '<option value="category-' . $categoryId . '"' . $selected . '>' . $category['title'] . '</option>';
            if (! isset($tagList[$categoryId])) {
                continue;
            }
            foreach ($tagList[$categoryId] as $tagId=>$tag) {
                $selected = '';
                if ('tag'==$selectedInfo['type'] && $selectedInfo['type_id'] == $tagId) {
                    $selected =  'selected="selected"';
                }
                $html .= '<option value="tag-' . $tagId . '"' . $selected . '>' . $subPrefix . ' ' .$tag['title'] . '</option>';
            }
        }

        return $html;
    }

    /**
     * 通过id获取技能信息
     * @param int $id 技能id
     * @param bool $isReload 是否重新从数据库获取
     */
    public function getTagById($id)
    {
        static $tagsList = array();

        if (! $tagsList) {
            if ($tags = $this->fetch_all('tag')) {
                foreach ($tags AS $val) {
                    if (!$val['url_token']) {
                        $val['url_token'] = $val['id'];
                    }

                    $tagsList[$val['id']] = $val;
                }
            }
        }
        $tag = isset($tagsList[$id]) ? $tagsList[$id] : array();

        return $tag;
    }

    /**
     * 根据标签名称获取标签
     * @param string $title
     */
    public function getTagByTitle($title)
    {
        return $this->fetch_row('tag', "title ='".$this->quote($title)."'");
    }

    /**
     * 将标签加入到分类中
     * @param int $tagId
     * @param int $categoryId
     * @return number
     */
    public function addTagIntoCategory ($tagId, $categoryId)
    {
        $id = 0;
        if ($this->getTagById($tagId) && $this->getTagCategoryById($categoryId)) {
            $data = array(
                    'tag_id'      => $tagId,
                    'category_id' => $categoryId
            );
            $id = $this->insert('tag_category_relation', $data);
            $sql = 'UPDATE ' .$this->get_table('tag_category')
                 . ' SET tag_count = tag_count + 1  '
                 . ' WHERE id = ' . intval($categoryId);
            $this->query($sql);
        }

        return $id;
    }

    /**
     * 从分类中移除标签
     * @param int $tagId
     * @param int $categoryId
     * @return Ambigous <boolean, number>
     */
    public function removeTagFromCategory ($tagId, $categoryId)
    {
        $result = false;
        if ($this->getTagById($tagId) && $this->getTagCategoryById($categoryId)) {
            $result = $this->delete('tag_category_relation', " tag_id = $tagId AND category_id = $categoryId ");

            $sql = 'UPDATE ' .$this->get_table('tag_category')
                 . ' SET tag_count = tag_count - 1  '
                 . ' WHERE tag_count>0 AND id = ' . intval($categoryId);
            $this->query($sql);
        }

        return $result;
    }

    /**
     * 更新标签
     * @param int $id 标签id
     * @param array $data 标签数据列表
     * @return number
     */
    public function updateTag($id, $data)
    {
        $result = false;
        if ($set = $this->processTagData($data)) {
            if (isset($set['url_token']) && $set['url_token']==$id) {
                $set['url_token'] = '';
            }
            $result = $this->update('tag', $set, 'id = ' . intval($id));
        }
        if ($data['category_ids']) {
            $categoryIds = explode(',', $data['category_ids']);
            $this->setTagCategoryRelations($id, $categoryIds);
        }

        return $result;
    }
    /**
     *  设置标签和分类绑定关系
     * @param int $tagId
     * @param array $categoryIds
     * @return tagModel
     */
    public function setTagCategoryRelations ($tagId, $categoryIds)
    {
        $oldCategoryIds = $this->getTagCategoryRelations('tag', $tagId);
        foreach ($oldCategoryIds as $categoryId) {
            if (! in_array($categoryId, $categoryIds)) {
                $this->removeTagFromCategory($tagId, $categoryId);
            }
        }

        foreach ($categoryIds as $categoryId) {
            if (! in_array($categoryId, $oldCategoryIds)) {
                $this->addTagIntoCategory($tagId, $categoryId);
            }
        }

        return $this;
    }
    /**
     * 更新标签分类
     * @param int $id
     * @param array $data
     * @return Ambigous <boolean, number>
     */
    public function updateTagCategory ($id, $data)
    {
        $result = false;
        if ($set = $this->processTagData($data)) {
            if (isset($set['url_token']) && $set['url_token']==$id) {
                $set['url_token'] = '';
            }
            $result = $this->update('tag', $set, 'id = ' . intval($id));
        }

        return $result;
    }

    /**
     * 处理标签数据， 供更新和添加使用
     * @param array $data
     * @return multitype:string NULL
     */
    protected function processTagData ($data)
    {
        $set = array();
        if (isset($data['title'])) {
            $set['title'] = htmlspecialchars(str_replace(array('-', '/'), '_', $data['title']));
        }
        if (isset($data['url_token'])) {
            $set['url_token'] = str_replace(array('-', '/'), '_', $data['url_token']);
        }
        if (isset($data['description'])) {
            $set['description'] = htmlspecialchars($data['description']);
        }

        return $set;
    }

    /**
     * 添加新标签
     * @param unknown $data
     * @return number
     */
    public function addTag ($data)
    {
        $id = 0;
        if ($set = $this->processTagData($data)) {
            $set['add_time'] = time();
            $id = $this->insert('tag', $set);
        }
        if ($id && $data['category_ids']) {
            $categoryIds = explode(',', $data['category_ids']);
            foreach ($categoryIds as $categoryId) {
                $this->addTagIntoCategory($id, $categoryId);
            }
        }

        return $id;
    }

    /**
     * 添加新标签分类
     * @param array $data 分类信息数据
     * @return number
     */
    public function addTagCategory ($data)
    {
        $id = 0;
        if ($set = $this->processTagData($data)) {
            $set['add_time'] = time();
            $id = $this->insert('tag_category', $set);
        }

        return $id;
    }

    /**
     * 通过url token获取技能信息
     * @param int $token 技能token
     * @param bool $isReload 是否重新从数据库获取
     */
    public function getTagByToken($token)
    {
        static $tagsList = array();

        if (! $tagsList) {
            if ($tags = $this->fetch_all('tag')) {
                foreach ($tags AS $val) {
                    if (!$val['url_token']) {
                        $val['url_token'] = $val['id'];
                    }

                    $tagsList[$val['url_token']] = $val;
                }
            }
        }
        $tag = isset($tagsList[$token]) ? $tagsList[$token] : array();

        return $tag;
    }

    /**
     * 获取标签列表
     * @param string $where
     * @param string $order
     * @param number $limit
     * @param string $page
     * @return Ambigous <string, multitype:>
     */
    public function getTagList($where = null, $order = 'id DESC', $limit = 10, $page = null, $bindKey=null)
    {
        $tags = array();
        if ($tagList = $this->fetch_page('tag', $where, $order, $page, $limit)) {
            foreach ($tagList AS $key => $val) {
                if (! $val['url_token']) {
                    $val['url_token'] = $val['id'];
                }
                if (!isset($bindKey) || ! array_key_exists($bindKey, $val)) {
                    $bindKey = 'id';
                }
                $tags[$val[$bindKey]] = $val;
            }
        }

        return $tags;
    }

    /**
     * 根据标签id删除标签
     * @param array | int $ids 标签id
     * @return boolean
     */
    public function removeTagByIds ($ids)
    {
        return $this->deleteByIds('tag', $ids);
    }

    /**
     * 根据标签分类id删除分类
     * @param array | int $ids 标签分类id
     * @return boolean
     */
    public function removeTagCategoryByIds ($ids)
    {
        return $this->deleteByIds('tag_category', $ids);
    }

    /**
     * 根据文章id获取标签列表
     * @param array $ids 文章id列表
     * @param string $type 文章类型
     * @return Ambigous <multitype:multitype: , unknown>
     */
    public function getTagsByArticleIds ($ids, $type)
    {
        $tags = array();
        if (! is_array($ids)) {
            $id = intval($ids);
            $ids = array($id);
            $where = '=' . $id;
        } else {
            array_walk_recursive($ids, 'intval');
            $where = "IN ('" . join("','", $ids) . "')";
        }
        $sql = 'SELECT * FROM ' . $this->get_table('tag') . ' AS t ' .
               'INNER JOIN ' . $this->get_table('tag_article_relation') . ' AS r '.
               ' ON t.id = r.type_id ' .
               'WHERE r.article_id  ' . $where .
               " AND r.type ='tag' " .
               " AND article_type = '" . $this->quote($type) . "'";
        $list = $this->query_all($sql);
        foreach ($list as $_tag) {
            unset($_tag['id']);
            isset($tags[$_tag['article_id']]) OR $tags[$_tag['article_id']]=array();
            $tags[$_tag['article_id']][] = $_tag;
        }
        if (isset($id, $tags[$id])) {
            $tags = $tags[$id];
        }


        return $tags;
    }

    /**
     * 根据ids获取标签和分类关系列表
     * @param array $ids id列表
     * @param string $type 'tag'  'catgory', 'course'
     * @return multitype:
     */
    public function getArticleTagRelationByIds ($ids, $type=null)
    {
        $relations = array();
        array_walk_recursive($ids, 'intval');
        switch ($type) {
            case 'tag':
            case 'category':
                $where = "type = '".$type."' AND type_id IN ('" . join("','", $ids) . "')";
                break;
            case 'course':
                $where = "item_type ='".$type."' AND item_id IN ('" . join("','", $ids) . "')";
                break;
            default:
                $where = "id IN ('" . join("','", $ids) . "')";
                break;
        }

        return $this->fetch_all('tag_article_relation', $where);
    }
    protected function processArticleTagRelationData ($data)
    {
        $set = array();
        if (isset($data['article_id'])) {
            $set['article_id'] = intval($data['article_id']);
        }
        if (isset($data['article_type'])) {
            $set['article_type'] = htmlspecialchars($data['article_type']);
        }
        if (isset($data['type_id'])) {
            $set['type_id'] = intval($data['type_id']);
        }
        if (isset($data['type'])) {
            $set['type'] = htmlspecialchars($data['type']);
        }

        return $set;
    }
    /**
     * 添加文章和标签/标签分类的关系
     * @param array $data 关系数据
     * @return Ambigous <NULL, number>
     */
    public function addArticleTagRelation($data)
    {
        $id = null;
        $set = $this->processArticleTagRelationData($data);
        if ($set) {
            $set['add_time'] = time();
            $id = $this->insert('tag_article_relation', $data);
        }

        return $id;
    }
}
/* EOF */

class ztag {
    /* 获取分类 HTML 数据 */
    public function build_category_html($type, $parent_id = 0, $selected_id = 0, $prefix = '', $child = true)
    {
        if (!$category_list = $this->fetch_category($type, $parent_id))
        {
            return false;
        }

        if ($prefix)
        {
            $_prefix = $prefix . ' ';
        }

        foreach ($category_list AS $category_id => $val)
        {
            if ($selected_id == $val['id'])
            {
                $html .= '<option value="' . $category_id . '" selected="selected">' . $_prefix . $val['title'] . '</option>';
            }
            else
            {
                $html .= '<option value="' . $category_id . '">' . $_prefix . $val['title'] . '</option>';
            }

            if ($child AND $val['child'])
            {
                $html .= $this->build_category_html($type, $val['id'], $selected_id, $prefix . '--');
            }
            else
            {
                unset($prefix);
            }
        }

        return $html;
    }

    /* 获取分类 JSON 数据 */
    public function build_category_json($type, $parent_id = 0, $prefix = '')
    {
        if (!$category_list = $this->fetch_category($type, $parent_id))
        {
            return false;
        }

        if ($prefix)
        {
            $_prefix = $prefix . ' ';
        }

        foreach ($category_list AS $category_id => $val)
        {
            $data[] = array(
                'id' => $category_id,
                'title' => $_prefix . $val['title'],
                'description' => $val['description'],
                'sort' => $val['sort'],
                'parent_id' => $val['parent_id'],
                'url_token' => $val['url_token']
            );

            if ($val['child'])
            {
                $data = array_merge($data, json_decode($this->build_category_json($type, $val['id'], $prefix . '--'), true));
            }
            else
            {
                unset($prefix);
            }
        }

        return json_encode($data);
    }

    /* 获取数组信息 */
    public function get_category_info($category_id)
    {
        static $all_category;

        if (!$all_category)
        {
            if ($all_category_query = $this->fetch_all('category'))
            {
                foreach ($all_category_query AS $key => $val)
                {
                    if (!$val['url_token'])
                    {
                        $val['url_token'] = $val['id'];
                    }

                    $all_category[$val['id']] = $val;
                }
            }
        }

        return $all_category[$category_id];
    }

    /* 获取数组信息 */
    public function get_category_info_by_url_token($url_token)
    {
        static $all_category;

        if (!$all_category)
        {
            if ($all_category_query = $this->fetch_all('category'))
            {
                foreach ($all_category_query AS $key => $val)
                {
                    if (!$val['url_token'])
                    {
                        $val['url_token'] = $val['id'];
                    }

                    $all_category[$val['url_token']] = $val;
                }
            }
        }

        return $all_category[$url_token];
    }

    public function get_category_list($type)
    {
        $category_list = array();

        $category_all = $this->fetch_all('category', '`type` = \'' . $this->quote($type) . '\'', 'id ASC');

        foreach($category_all as $key => $val)
        {
            if (!$val['url_token'])
            {
                $val['url_token'] = $val['id'];
            }

            $category_list[$val['id']] = $val;
        }

        return $category_list;
    }

    public function get_category_with_child_ids($type, $category_id)
    {
        $category_ids[] = intval($category_id);

        if ($child_ids = $this->fetch_category_data($type, $category_id))
        {
            $category_ids = array_merge($category_ids, fetch_array_value($child_ids, 'id'));
        }

        return $category_ids;
    }

    public function clean_break_attach()
    {
        if ($attachs = $this->query_all("SELECT `id`, `access_key` FROM " . get_table('attach') . " WHERE item_id = 0 AND wait_approval = 0 AND add_time < " . (time() - 3600 * 24)))
        {
            foreach ($attachs AS $key => $val)
            {
                $this->model('publish')->remove_attach($val['id'], $val['access_key']);
            }
        }

        return true;
    }

    public function check_stop_keyword($keyword)
    {
        $keyword = trim($keyword);

        if ($keyword == '')
        {
            return false;
        }

        if (cjk_strlen($keyword) == 1)
        {
            return false;
        }

        if (strstr($keyword, '了') OR strstr($keyword, '的') OR strstr($keyword, '有'))
        {
            return false;
        }

        $stop_words_list = array(
            '末', '啊', '阿', '哎', '哎呀', '哎哟', '唉', '俺',
            '俺们', '按', '按照', '吧', '吧哒', '把', '被', '本',
            '本着', '比', '比方', '比如', '鄙人', '彼', '彼此', '边',
            '别', '别说', '并', '并且', '不比', '不成', '不单', '不但',
            '不独', '不管', '不光', '不过', '不仅', '不拘', '不论', '不怕',
            '不然', '不如', '不特', '不惟', '不问', '不只', '朝', '朝着',
            '趁', '趁着', '乘', '冲', '除', '除此之外', '除非', '此',
            '此间', '此外', '从', '从而', '打', '待', '但', '但是',
            '当', '当着', '到', '得', '等', '等等', '地', '第',
            '叮咚', '对', '对于', '多', '多少', '而', '而况', '而且',
            '而是', '而外', '而言', '而已', '尔后', '反过来', '反过来说',
            '反之', '非但', '非徒', '否则', '嘎', '嘎登', '该', '赶', '个',
            '各', '各个', '各位', '各种', '各自', '给', '根据', '跟', '故',
            '故此', '固然', '关于', '管', '归', '果然', '果真', '过', '哈',
            '哈哈', '呵', '和', '何', '何处', '何况', '何时', '嘿', '哼', '哼唷',
            '呼哧', '乎', '哗', '还是', '换句话说', '换言之', '或', '或是', '或者',
            '及', '及其', '及至', '即', '即便', '即或', '即令', '即若', '即使', '几',
            '几时', '己', '既', '既然', '既是', '继而', '加之', '假如', '假若', '假使',
            '鉴于', '将', '较', '较之', '叫', '接着', '结果', '借', '紧接着', '进而',
            '尽', '尽管', '经', '经过', '就', '就是', '就是说', '据', '具体地说',
            '具体说来', '开始', '开外', '靠', '咳', '可', '可见', '可是', '可以',
            '况且', '啦', '来', '来着', '离', '例如', '哩', '连', '连同', '两者',
            '临', '另', '另外', '另一方面', '论', '嘛', '吗', '慢说', '漫说', '冒',
            '么', '每', '每当', '们', '莫若', '某', '某个', '某些', '拿', '哪',
            '哪边', '哪儿', '哪个', '哪里', '哪年', '哪怕', '哪天', '哪些',
            '哪样', '那', '那边', '那儿', '那个', '那会儿', '那里', '那么',
            '那么些', '那么样', '那时', '那些', '那样', '乃', '乃至', '呢',
            '能', '你', '你们', '您', '宁', '宁可', '宁肯', '宁愿', '哦',
            '呕', '啪达', '旁人', '呸', '凭', '凭借', '其', '其次', '其二',
            '其他', '其它', '其一', '其余', '其中', '起', '起见', '起见',
            '岂但', '恰恰相反', '前后', '前者', '且', '然而', '然后', '然则',
            '让', '人家', '任', '任何', '任凭', '如', '如此', '如果', '如何',
            '如其', '如若', '如上所述', '若', '若非', '若是', '啥', '上下',
            '尚且', '设若', '设使', '甚而', '甚么', '甚至', '省得', '时候',
            '什么', '什么样', '使得', '是', '首先', '谁', '谁知', '顺',
            '顺着',  '虽', '虽然', '虽说', '虽则', '随', '随着', '所', '所以',
            '他', '他们', '他人', '它', '它们', '她', '她们', '倘', '倘或', '倘然',
            '倘若', '倘使', '腾', '替', '通过', '同', '同时', '哇', '万一', '往',
            '望', '为', '为何', '为什么', '为着', '喂', '嗡嗡', '我', '我们', '呜',
            '呜呼', '乌乎', '无论', '无宁', '毋宁', '嘻', '吓', '相对而言', '像',
            '向', '向着', '嘘', '呀', '焉', '沿', '沿着', '要', '要不', '要不然',
            '要不是', '要么', '要是', '也', '也罢', '也好', '一', '一般', '一旦',
            '一方面', '一来', '一切', '一样', '一则', '依', '依照', '矣', '以',
            '以便', '以及', '以免', '以至', '以至于', '以致', '抑或', '因',
            '因此', '因而', '因为', '哟', '用', '由', '由此可见', '由于', '又',
            '于', '于是', '于是乎', '与', '与此同时', '与否', '与其', '越是', '云云',
            '哉', '再说', '再者', '在', '在下', '咱', '咱们', '则', '怎', '怎么',
            '怎么办', '怎么样', '怎样', '咋', '照', '照着', '者', '这', '这边', '这儿',
            '这个', '这会儿', '这就是说', '这里', '这么', '这么点儿', '这么些',
            '这么样', '这时', '这些', '这样', '正如', '吱', '之', '之类', '之所以',
            '之一', '只是', '只限', '只要', '至', '至于', '诸位', '着', '着呢', '自',
            '自从', '自个儿', '自各儿', '自己', '自家', '自身', '综上所述', '总而言之',
            '总之', '纵', '纵令', '纵然', '纵使', '遵照', '作为', '兮', '呃', '呗', '咚',
            '咦', '喏', '啐', '喔唷', '嗬', '嗯', '嗳',
            'a\'s', 'able', 'about', 'above', 'according', 'accordingly', 'across', 'actually',
            'after', 'afterwards', 'again', 'against', 'ain\'t', 'all', 'allow', 'allows',
            'almost', 'alone', 'along', 'already', 'also', 'although', 'always', 'am',
            'among', 'amongst', 'an', 'and', 'another', 'any', 'anybody', 'anyhow',
            'anyone', 'anything', 'anyway', 'anyways', 'anywhere', 'apart', 'appear', 'appreciate',
            'appropriate', 'are', 'aren\'t', 'around', 'as', 'aside', 'ask', 'asking',
            'associated', 'at', 'available', 'away', 'awfully', 'be', 'became', 'because',
            'become', 'becomes', 'becoming', 'been', 'before', 'beforehand', 'behind', 'being',
            'believe', 'below', 'beside', 'besides', 'best', 'better', 'between', 'beyond',
            'both', 'brief', 'but', 'by', 'c\'mon', 'c\'s', 'came', 'can',
            'can\'t', 'cannot', 'cant', 'cause', 'causes', 'certain', 'certainly', 'changes',
            'clearly', 'co', 'com', 'come', 'comes', 'concerning', 'consequently', 'consider',
            'considering', 'contain', 'containing', 'contains', 'corresponding', 'could', 'couldn\'t', 'course',
            'currently', 'definitely', 'described', 'despite', 'did', 'didn\'t', 'different', 'do',
            'does', 'doesn\'t', 'doing', 'don\'t', 'done', 'down', 'downwards', 'during',
            'each', 'edu', 'eg', 'eight', 'either', 'else', 'elsewhere', 'enough',
            'entirely', 'especially', 'et', 'etc', 'even', 'ever', 'every', 'everybody',
            'everyone', 'everything', 'everywhere', 'ex', 'exactly', 'example', 'except', 'far',
            'few', 'fifth', 'first', 'five', 'followed', 'following', 'follows', 'for',
            'former', 'formerly', 'forth', 'four', 'from', 'further', 'furthermore', 'get',
            'gets', 'getting', 'given', 'gives', 'go', 'goes', 'going', 'gone',
            'got', 'gotten', 'greetings', 'had', 'hadn\'t', 'happens', 'hardly', 'has',
            'hasn\'t', 'have', 'haven\'t', 'having', 'he', 'he\'s', 'hello', 'help',
            'hence', 'her', 'here', 'here\'s', 'hereafter', 'hereby', 'herein', 'hereupon',
            'hers', 'herself', 'hi', 'him', 'himself', 'his', 'hither', 'hopefully',
            'how', 'howbeit', 'however', 'i\'d', 'i\'ll', 'i\'m', 'i\'ve', 'ie',
            'if', 'ignored', 'immediate', 'in', 'inasmuch', 'inc', 'indeed', 'indicate',
            'indicated', 'indicates', 'inner', 'insofar', 'instead', 'into', 'inward', 'is',
            'isn\'t', 'it', 'it\'d', 'it\'ll', 'it\'s', 'its', 'itself', 'just',
            'keep', 'keeps', 'kept', 'know', 'known', 'knows', 'last', 'lately',
            'later', 'latter', 'latterly', 'least', 'less', 'lest', 'let', 'let\'s',
            'like', 'liked', 'likely', 'little', 'look', 'looking', 'looks', 'ltd',
            'mainly', 'many', 'may', 'maybe', 'me', 'mean', 'meanwhile', 'merely',
            'might', 'more', 'moreover', 'most', 'mostly', 'much', 'must', 'my',
            'myself', 'name', 'namely', 'nd', 'near', 'nearly', 'necessary', 'need',
            'needs', 'neither', 'never', 'nevertheless', 'new', 'next', 'nine', 'no',
            'nobody', 'non', 'none', 'noone', 'nor', 'normally', 'not', 'nothing',
            'novel', 'now', 'nowhere', 'obviously', 'of', 'off', 'often', 'oh',
            'ok', 'okay', 'old', 'on', 'once', 'one', 'ones', 'only',
            'onto', 'or', 'other', 'others', 'otherwise', 'ought', 'our', 'ours',
            'ourselves', 'out', 'outside', 'over', 'overall', 'own', 'particular', 'particularly',
            'per', 'perhaps', 'placed', 'please', 'plus', 'possible', 'presumably', 'probably',
            'provides', 'que', 'quite', 'qv', 'rather', 'rd', 're', 'really',
            'reasonably', 'regarding', 'regardless', 'regards', 'relatively', 'respectively', 'right', 'said',
            'same', 'saw', 'say', 'saying', 'says', 'second', 'secondly', 'see',
            'seeing', 'seem', 'seemed', 'seeming', 'seems', 'seen', 'self', 'selves',
            'sensible', 'sent', 'serious', 'seriously', 'seven', 'several', 'shall', 'she',
            'should', 'shouldn\'t', 'since', 'six', 'so', 'some', 'somebody', 'somehow',
            'someone', 'something', 'sometime', 'sometimes', 'somewhat', 'somewhere', 'soon', 'sorry',
            'specified', 'specify', 'specifying', 'still', 'sub', 'such', 'sup', 'sure',
            't\'s', 'take', 'taken', 'tell', 'tends', 'th', 'than', 'thank',
            'thanks', 'thanx', 'that', 'that\'s', 'thats', 'the', 'their', 'theirs',
            'them', 'themselves', 'then', 'thence', 'there', 'there\'s', 'thereafter', 'thereby',
            'therefore', 'therein', 'theres', 'thereupon', 'these', 'they', 'they\'d', 'they\'ll',
            'they\'re', 'they\'ve', 'think', 'third', 'this', 'thorough', 'thoroughly', 'those',
            'though', 'three', 'through', 'throughout', 'thru', 'thus', 'to', 'together',
            'too', 'took', 'toward', 'towards', 'tried', 'tries', 'truly', 'try',
            'trying', 'twice', 'two', 'un', 'under', 'unfortunately', 'unless', 'unlikely',
            'until', 'unto', 'up', 'upon', 'us', 'use', 'used', 'useful',
            'uses', 'using', 'usually', 'value', 'various', 'very', 'via', 'viz',
            'vs', 'want', 'wants', 'was', 'wasn\'t', 'way', 'we', 'we\'d',
            'we\'ll', 'we\'re', 'we\'ve', 'welcome', 'well', 'went', 'were', 'weren\'t',
            'what', 'what\'s', 'whatever', 'when', 'whence', 'whenever', 'where', 'where\'s',
            'whereafter', 'whereas', 'whereby', 'wherein', 'whereupon', 'wherever', 'whether', 'which',
            'while', 'whither', 'who', 'who\'s', 'whoever', 'whole', 'whom', 'whose',
            'why', 'will', 'willing', 'wish', 'with', 'within', 'without', 'won\'t',
            'wonder', 'would', 'wouldn\'t', 'yes', 'yet', 'you', 'you\'d', 'you\'ll',
            'you\'re', 'you\'ve', 'your', 'yours', 'yourself', 'yourselves', 'zero'
        );

        if (in_array($keyword, $stop_words_list))
        {
            return false;
        }

        return true;
    }

    public function analysis_keyword($string)
    {
        $analysis = loadClass('Services_Phpanalysis_Phpanalysis');

        $analysis->SetSource(strtolower($string));
        $analysis->StartAnalysis();

        if ($result = explode(',', $analysis->GetFinallyResult(',')))
        {
            $result = array_unique($result);

            foreach ($result as $key => $keyword)
            {
                if (!$this->check_stop_keyword($keyword))
                {
                    unset($result[$key]);
                }
                else
                {
                    $result[$key] = trim($keyword);
                }
            }
        }

        return $result;
    }

    public function update_associate_fresh_action($page, $limit = 100)
    {
        if (!$action_history_data = $this->fetch_page('user_action_history', null, 'history_id ASC', $page, $limit))
        {
            return false;
        }

        foreach ($action_history_data AS $key => $val)
        {
            if ($val['fold_status'] == 0)
            {
                ACTION_LOG::associate_fresh_action($val['history_id'], $val['associate_id'], $val['associate_type'], $val['associate_action'], $val['uid'], $val['anonymous'], $val['add_time']);
            }
        }

        return true;
    }

    public function clean_session()
    {
        return $this->delete('sessions', '`modified` < ' . (time() - 3600));
    }

    public function remove_user_by_uid($uid, $remove_user_data = false)
    {
        $delete_tables = array(
            'active_data',
            'answer_uninterested',
            'draft',
            'education_experience',
            'favorite',
            'favorite_tag',
            'integral_log',
            'invitation',
            'question_focus',
            'question_uninterested',
            'report',
            'reputation_category',
            'reputation_topic',
            'related_links',
            'topic_focus',
            'weixin_login',
            'work_experience',
            'users_attrib',
            'users_online',
            'users_qq',
            'users_sina',
            'users_ucenter',
            'users_weixin',
            'users_google',
            'users'
        );

        $update_tables = array(
            'redirect',
            'topic_merge',
            'topic_relation'
        );

        if ($remove_user_data)
        {
            if ($user_answers = $this->query_all("SELECT answer_id FROM " . get_table('answer') . " WHERE uid = " . intval($uid)))
            {
                foreach ($user_answers AS $key => $val)
                {
                    $answer_ids[] = $val['answer_id'];
                }

                $this->update('attach', array(
                    'item_id' => 0
                ), "item_id IN (" . implode(',', $answer_ids) . ") AND item_type = 'answer'");
            }

            if ($user_articles = $this->query_all("SELECT id FROM " . get_table('article') . " WHERE uid = " . intval($uid)))
            {
                foreach ($user_articles AS $key => $val)
                {
                    $this->model('article')->remove_article($val['id']);
                }
            }

            if ($user_questions = $this->query_all("SELECT question_id FROM " . get_table('question') . " WHERE published_uid = " . intval($uid)))
            {
                foreach ($user_questions AS $key => $val)
                {
                    $this->model('question')->remove_question($val['question_id']);
                }
            }

            $update_tables[] = 'answer';
            $update_tables[] = 'article';

            $delete_tables[] = 'answer_comments';
            $delete_tables[] = 'answer_thanks';
            $delete_tables[] = 'article_comments';
            $delete_tables[] = 'article_vote';
            $delete_tables[] = 'question_comments';
            $delete_tables[] = 'question_thanks';

            if ($inbox_dialog = $this->fetch_all('inbox_dialog', 'recipient_uid = ' . intval($uid) . ' OR sender_uid = ' . intval($uid)))
            {
                foreach ($inbox_dialog AS $key => $val)
                {
                    $this->delete('inbox', 'dialog_id = ' . $val['id']);
                    $this->delete('inbox_dialog', 'id = ' . $val['id']);
                }
            }
        }
        else
        {
            $update_tables[] = 'answer';
            $update_tables[] = 'answer_comments';
            $update_tables[] = 'answer_thanks';
            $update_tables[] = 'article';
            $update_tables[] = 'article_comments';
            $update_tables[] = 'article_vote';
            $update_tables[] = 'question_comments';
            $update_tables[] = 'question_thanks';
            $delete_tables[] = 'inbox';

            $this->update('question', array(
                'published_uid' => '-1'
            ), 'published_uid = ' . intval($uid));
        }

        foreach ($delete_tables AS $key => $table)
        {
            $this->delete($table, 'uid = ' . intval($uid));
        }

        foreach ($update_tables AS $key => $table)
        {
            $this->update($table, array(
                'uid' => '-1'
            ), 'uid = ' . intval($uid));
        }

        $this->update('answer_vote', array(
            'vote_uid' => '-1'
        ), 'vote_uid = ' . intval($uid));

        $this->model('verify')->remove_apply($uid);
        $this->model('notify')->delete_notify('sender_uid = ' . intval($uid) . ' OR recipient_uid = ' . intval($uid));

        $this->delete('question_invite', 'sender_uid = ' . intval($uid) . ' OR recipients_uid = ' . intval($uid));

        ACTION_LOG::delete_action_history('uid = ' . intval($uid));

        $this->delete('user_follow', 'fans_uid = ' . intval($uid) . ' OR friend_uid = ' . intval($uid));

        return true;
    }

    public function statistic($tag, $start_time = null, $end_time = null)
    {
        if (!$start_time)
        {
            $start_time = strtotime('-6 months');
        }

        if (!$end_time)
        {
            $end_time = strtotime('Today');
        }

        $data = array();

        switch ($tag)
        {
            case 'new_user':
                $query = "SELECT COUNT(uid) AS count, FROM_UNIXTIME(reg_time, '%y-%m') AS statistic_date FROM " . get_table('users') . " WHERE reg_time BETWEEN " . intval($start_time) . " AND " . intval($end_time) . " GROUP BY statistic_date ASC";
            break;

            case 'user_valid':
                $query = "SELECT COUNT(uid) AS count, FROM_UNIXTIME(reg_time, '%y-%m') AS statistic_date FROM " . get_table('users') . " WHERE valid_email = 1 AND reg_time BETWEEN " . intval($start_time) . " AND " . intval($end_time) . " GROUP BY statistic_date ASC";
            break;

            case 'new_question':
                $query = "SELECT COUNT(question_id) AS count, FROM_UNIXTIME(add_time, '%y-%m') AS statistic_date FROM " . get_table('question') . " WHERE add_time BETWEEN " . intval($start_time) . " AND " . intval($end_time) . " GROUP BY statistic_date ASC";
            break;

            case 'new_answer':
                $query = "SELECT COUNT(answer_id) AS count, FROM_UNIXTIME(add_time, '%y-%m') AS statistic_date FROM " . get_table('answer') . " WHERE add_time BETWEEN " . intval($start_time) . " AND " . intval($end_time) . " GROUP BY statistic_date ASC";
            break;

            case 'new_topic':
                $query = "SELECT COUNT(topic_id) AS count, FROM_UNIXTIME(add_time, '%y-%m') AS statistic_date FROM " . get_table('topic') . " WHERE add_time BETWEEN " . intval($start_time) . " AND " . intval($end_time) . " GROUP BY statistic_date ASC";
            break;

            case 'new_answer_vote':
                $query = "SELECT COUNT(voter_id) AS count, FROM_UNIXTIME(add_time, '%y-%m') AS statistic_date FROM " . get_table('answer_vote') . " WHERE add_time BETWEEN " . intval($start_time) . " AND " . intval($end_time) . " GROUP BY statistic_date ASC";
            break;

            case 'new_question_thanks':
                $query = "SELECT COUNT(id) AS count, FROM_UNIXTIME(time, '%y-%m') AS statistic_date FROM " . get_table('question_thanks') . " WHERE time BETWEEN " . intval($start_time) . " AND " . intval($end_time) . " GROUP BY statistic_date ASC";
            break;

            case 'new_answer_thanks':
                $query = "SELECT COUNT(id) AS count, FROM_UNIXTIME(time, '%y-%m') AS statistic_date FROM " . get_table('answer_thanks') . " WHERE time BETWEEN " . intval($start_time) . " AND " . intval($end_time) . " GROUP BY statistic_date ASC";
            break;

            case 'new_favorite_item':
                $query = "SELECT COUNT(id) AS count, FROM_UNIXTIME(time, '%y-%m') AS statistic_date FROM " . get_table('favorite') . " WHERE time BETWEEN " . intval($start_time) . " AND " . intval($end_time) . " GROUP BY statistic_date ASC";
            break;

            case 'new_question_redirect':
                $query = "SELECT COUNT(id) AS count, FROM_UNIXTIME(time, '%y-%m') AS statistic_date FROM " . get_table('redirect') . " WHERE time BETWEEN " . intval($start_time) . " AND " . intval($end_time) . " GROUP BY statistic_date ASC";
            break;
        }

        if ($query)
        {
            if ($result = $this->query_all($query))
            {
                foreach ($result AS $key => $val)
                {
                    $data[] = array(
                        'date' => $val['statistic_date'],
                        'count' => $val['count']
                    );
                }
            }
        }

        return $data;
    }

    public function update_topic_discuss_count($page, $limit = 100)
    {
        if (!$topics_list = $this->fetch_page('topic', null, 'topic_id ASC', $page, $limit))
        {
            return false;
        }

        foreach ($topics_list AS $key => $val)
        {
            $this->model('topic')->updateDiscussCountStatById($val['topic_id']);
        }

        return true;
    }
}