<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerExtensionModuleBlogLatest extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/blog_latest');

		$this->load->model('blog/article');

		$this->load->model('tool/image');

        $image_w    = 336;
        $image_h    = 200;

		$data['articles'] = array();

		$filter_data = array(
			'sort'  => 'p.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => $setting['limit']
		);

		$results = $this->model_blog_article->getArticles($filter_data);

		if ($results) {
			foreach ($results as $result) {
				if ($result['image_short']) {
                    $image = $this->model_tool_image->resize($result['image_short'], $image_w, $image_h, 'auto');
				} else {
                    $image = $this->model_tool_image->resize('placeholder.png', $image_w, $image_h, 'auto');
				}

				$data['articles'][] = array(
					'article_id'  => $result['article_id'],
					'thumb'       => $image,
                    'thumb_w'     => $image_w,
                    'thumb_h'     => $image_h,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('configblog_article_description_length')) . '..',
					'viewed'      => $result['viewed'],
					'href'        => $this->url->link('blog/article', 'article_id=' . $result['article_id'])
				);
			}

			return $this->load->view('extension/module/blog_latest', $data);
		}
	}
}
