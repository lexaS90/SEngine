<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 05.10.2016
 * Time: 13:30
 */

namespace SEngine\Core;

use Intervention\Image\ImageManager;
use SEngine\Core\Libs\Singleton;


class imgTools
{
    use Singleton;

    protected $imgManager;

    protected function __construct()
    {
        $this->imgManager = new ImageManager(array('driver' => 'imagick'));
    }

    public function fit($sPath, $dPath, $width, $height)
    {
        if (empty($sPath))
            return false;

        $image = $this->imgManager->make($sPath)->fit($width, $height, function ($constraint) {
            $constraint->upsize();
        });

        return $image->save($dPath);
    }
}