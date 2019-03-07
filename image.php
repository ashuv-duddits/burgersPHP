<?php
require_once './vendor/autoload.php';
use Intervention\Image\ImageManagerStatic as IImage;
$origin = 'photo1_origin.jpg';
$result = 'photo1.jpg';
$image = IImage::make(realpath($origin));

$image->rotate(45);
$image->text(
    'WATERMARK',
    $image->width() / 2,
    $image->height() / 2,
    function ($font) {
        $font->color(array(255, 0, 0, 0.5));
        $font->align('center');
        $font->valign('center');
    }
)
    ->resize(200, null, function ($image) {
        $image->aspectRatio();
    })
    ->save($result, 100);
echo 'success';
