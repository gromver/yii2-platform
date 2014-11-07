<?php
/**
 * @link https://github.com/gromver/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace gromver\cmf\backend\behaviors\upload;

use Imagine\Image\ManipulatorInterface;
use yii\imagine\Image;

/**
 * Class ThumbnailProcessor
 * @package yii2-cms
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class ThumbnailProcessor extends BaseProcessor {
    public $width = 140;
    public $height = 140;
    public $mode = ManipulatorInterface::THUMBNAIL_INSET;

    public function process($filePath)
    {
        Image::thumbnail($filePath, $this->width, $this->height, $this->mode)->save($filePath);
    }
}