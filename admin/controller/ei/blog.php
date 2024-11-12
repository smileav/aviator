<?php
class ControllerEIBlog extends Controller {
    public function index() {
        $this->load->model('ie_cli/ie');

        $languages = $this->model_ie_cli_ie->compareLanguages();

        // oc_article
        $query = $this->db_don->query("SELECT * FROM `" . DB_PREFIX . "blog` ORDER BY `blog_id`");

        $this->db->query("TRUNCATE `" . DB_PREFIX . "article`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "article_description`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "article_to_layout`");
        $this->db->query("TRUNCATE `" . DB_PREFIX . "article_to_store`");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `query` LIKE 'article_id=%'");

        $sql = "INSERT INTO `" . DB_PREFIX . "article` (`article_id`, `image`, `date_available`, `sort_order`, `article_review`, `status`, `noindex`, `date_added`, `date_modified`, `viewed`, `gstatus`) VALUES";

        foreach ($query->rows as $result) {
            if ($result['image']) {
                $this->imageCopy($result['image']);
            }

            $sql .= " (";
            $sql .= "'" . (int)$result['blog_id'] . "',";
            $sql .= "'" . $this->db->escape($result['image']) . "',";
            $sql .= "'" . $this->db->escape($result['date_added']) . "',";
            $sql .= "'" . (int)$result['sort_order'] . "',";
            $sql .= "'0',";
            $sql .= "'" . (int)$result['status'] . "',";
            $sql .= "'1',";
            $sql .= "'" . $this->db->escape($result['date_added']) . "',";
            $sql .= "'" . $this->db->escape($result['date_added']) . "',";
            $sql .= "'" . (int)$result['count_read'] . "',";
            $sql .= "'0'";
            $sql .= "),";

            // oc_article_to_layout
            $this->db->query("INSERT INTO `" . DB_PREFIX . "article_to_layout` SET `article_id` = '" . (int)$result['blog_id'] . "', `store_id` = '0', `layout_id` = '0'");

            // oc_article_to_store
            $this->db->query("INSERT INTO `" . DB_PREFIX . "article_to_store` SET `article_id` = '" . (int)$result['blog_id'] . "', `store_id` = '0'");

            // seo_url
            $seo_url_query = 'blog_id=' . (int)$result['blog_id'];

            $query = $this->db_don->query("SELECT `keyword` FROM `" . DB_PREFIX . "url_alias` WHERE `query` = '" . $this->db->escape($seo_url_query) ."'");

            $seo_url_query = 'article_id=' . (int)$result['blog_id'];

            if ($query->num_rows == 1 && !empty($query->row['keyword'])) {
                $seo_url_keyword = $query->row['keyword'];

                $sql_url = "INSERT INTO `" . DB_PREFIX . "seo_url` (`seo_url_id`, `store_id`, `language_id`, `query`, `keyword`) VALUES";

                foreach ($languages as $language_id) {
                    $sql_url .= " ('', 0, '" . (int)$language_id . "', '" . $this->db->escape($seo_url_query) . "', '" . $this->db->escape($seo_url_keyword) . "'),";
                }

                $sql_url = rtrim($sql_url, ',') . ';';

                $this->db->query($sql_url);
            }
        }

        $sql = rtrim($sql, ',') . ';';

        $this->db->query($sql);

        // oc_article_description
        $query = $this->db_don->query("SELECT * FROM `" . DB_PREFIX . "blog_description` ORDER BY `blog_id`");

        $sql = "INSERT INTO `" . DB_PREFIX . "article_description` (`article_id`, `language_id`, `name`, `short_description`, `description`, `meta_description`, `meta_keyword`, `meta_title`, `meta_h1`, `tag`) VALUES";


        foreach ($query->rows as $result) {
            $sql .= " (";
            $sql .= "'" . (int)$result['blog_id'] . "',";
            $sql .= "'" . (int)$languages[$result['language_id']] . "',";
            $sql .= "'" . $this->db->escape($result['title']) . "',";
            $sql .= "'" . $this->db->escape($result['short_description']) . "',";
            $sql .= "'" . $this->db->escape($result['description']) . "',";
            $sql .= "'" . $this->db->escape($result['meta_description']) . "',";
            $sql .= "'" . $this->db->escape($result['meta_keyword']) . "',";
            $sql .= "'" . $this->db->escape($result['page_title']) . "',";
            $sql .= "'" . $this->db->escape($result['page_title']) . "',";
            $sql .= "'" . $this->db->escape($result['tags']) . "'";
            $sql .= "),";
        }

        $sql = rtrim($sql, ',') . ';';

        $this->db->query($sql);
    }

    public function imageCopy($data) {
        if (!is_array($data)) {
            $image_don      = DIR_IMAGE_DON     . $data;
            $image          = DIR_IMAGE         . $data;
        } else {
            if (isset($data['image'])) {
                $image_don      = DIR_IMAGE_DON     . $data['image'];
                $image          = DIR_IMAGE         . $data['image'];
            }
        }

        if (!empty($image_don) && !empty($image) && !file_exists($image) && $this->imageValidate($image_don)) {
            $path = pathinfo($image);

            if (!file_exists($path['dirname'])) {
                mkdir($path['dirname'], 0775, true);
            }

            copy($image_don, $image);
        }
    }

    public function imageValidate($image): bool
    {
        if(@is_array(getimagesize($image))) {
            return true;
        } else {
            return false;
        }
    }
}
