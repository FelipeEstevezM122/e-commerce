<?php

use Cloudinary\Cloudinary;

if (!function_exists('cloudinary_url')) {
    function cloudinary_url($public_id, $width = null, $height = null)
    {
        $cloudinary = new Cloudinary();
        $image = $cloudinary->image($public_id);
        
        if ($width && $height) {
            $image->resize('fill', $width, $height);
        }
        
        return $image->format('webp')->quality('auto')->toUrl();
    }
}