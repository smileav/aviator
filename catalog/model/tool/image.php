<?php
class ModelToolImage extends Model {
	public function resize($filename, $width, $height, $default = '') {
		if (!is_file(DIR_IMAGE . $filename) || substr(str_replace('\\', '/', realpath(DIR_IMAGE . $filename)), 0, strlen(DIR_IMAGE)) != str_replace('\\', '/', DIR_IMAGE)) {

            $filename = 'placeholder.png';
            //return;
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$image_old = $filename;
		$image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;

        // ++ WebP Support
        $image_new_webp = 'cachewebp/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . (int)$width . 'x' . (int)$height . '.webp';
        // ++ WebP Support

		if (!is_file(DIR_IMAGE . $image_new) || (filemtime(DIR_IMAGE . $image_old) > filemtime(DIR_IMAGE . $image_new))) {
			list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);

			if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
				return DIR_IMAGE . $image_old;
			}

			$path = '';

			$directories = explode('/', dirname($image_new));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}

			if ($width_orig != $width || $height_orig != $height) {

                $image = new Image(DIR_IMAGE . $image_old);

                // ++ Auto Width && Height
                if ($default == 'auto') {
                    $scale_w = $width_orig / $width;
                    $scale_h = $height_orig / $height;

                    if ($scale_h > $scale_w) {
                        $diff = $height * $scale_w;
                        $top_x = 0;
                        $top_y = ($height_orig - $diff) / 2;
                        $bottom_x = $width_orig;
                        $bottom_y = $top_y + $diff;
                        $image->crop($top_x, $top_y, $bottom_x, $bottom_y);
                    } elseif ($scale_h < $scale_w) {
                        $diff = $width * $scale_h;
                        $top_x = ($width_orig - $diff) / 2;
                        $top_y = 0;
                        $bottom_x = $top_x + $diff;
                        $bottom_y = $height_orig;
                        $image->crop($top_x, $top_y, $bottom_x, $bottom_y);
                    }
                }

				$image->resize($width, $height);
				$image->save(DIR_IMAGE . $image_new);
			} else {
				copy(DIR_IMAGE . $image_old, DIR_IMAGE . $image_new);
			}
		}

        // ++ WebP Support
        $gd = gd_info();

        if ($gd['WebP Support']) {
            if (!is_file(DIR_IMAGE . $image_new_webp) || (filectime(DIR_IMAGE . $image_new) > filectime(DIR_IMAGE . $image_new_webp))) {
                list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);

                $path = '';

                $directories = explode('/', dirname($image_new_webp));

                foreach ($directories as $directory) {
                    $path = $path . '/' . $directory;

                    if (!is_dir(DIR_IMAGE . $path)) {
                        @mkdir(DIR_IMAGE . $path, 0777);
                    }
                }

                $image_webp = new Image(DIR_IMAGE . $image_old);

                if ($width_orig != $width || $height_orig != $height) {

                    // ++ Auto Width && Height
                    if ($default == 'auto') {
                        $scale_w = $width_orig / $width;
                        $scale_h = $height_orig / $height;

                        if ($scale_h > $scale_w) {
                            $diff = $height * $scale_w;
                            $top_x = 0;
                            $top_y = ($height_orig - $diff) / 2;
                            $bottom_x = $width_orig;
                            $bottom_y = $top_y + $diff;
                            $image_webp->crop($top_x, $top_y, $bottom_x, $bottom_y);
                        } elseif ($scale_h < $scale_w) {
                            $diff = $width * $scale_h;
                            $top_x = ($width_orig - $diff) / 2;
                            $top_y = 0;
                            $bottom_x = $top_x + $diff;
                            $bottom_y = $height_orig;
                            $image_webp->crop($top_x, $top_y, $bottom_x, $bottom_y);
                        }
                    }
                }

                $image_webp->resize($width, $height, $default);
                $image_webp->save_webp(DIR_IMAGE . $image_new_webp);
            }
        }
        // ++ WebP Support

		$image_new = str_replace(' ', '%20', $image_new);  // fix bug when attach image on email (gmail.com). it is automatic changing space " " to +

		if ($this->request->server['HTTPS']) {
			return $this->config->get('config_ssl') . 'image/' . $image_new;
		} else {
			return $this->config->get('config_url') . 'image/' . $image_new;
		}
	}
}
