<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\backend\behaviors\upload;

use Imagine\Image\ManipulatorInterface;
use yii\imagine\Image;

/**
 * Class ThumbnailProcessor
 * @package yii2-cmf
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