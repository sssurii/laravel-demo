<?php

use Image;

function getEmailHeaderImage($data){
       $bg_image = $space_info['background_logo'];
       if($bg_image){
           $share_name = $space_info['share_name'].' Clientshare';
           $seller_logo = $space_info['seller_processed_logo'];
           $buyer_logo = $space_info['buyer_processed_logo'];
           $bg_resized = resizeImage($bg_image, 680, 104, true);
           $seller_logo_resized = resizeImage($seller_logo, 36, 36);
           $buyer_logo_resized = resizeImage($buyer_logo, 44, 44);
           $bg_seller_logo =  mergeImages($bg_resized, $seller_logo_resized, 'bottom-left', 66, 34);
           $bg_seller_buyer_logo =  mergeImages($bg_seller_logo, $buyer_logo_resized, 'bottom-left', 98, 30);
           $bg_seller_buyer_logo_text =  addTextToImage($bg_seller_buyer_logo, $share_name, 'left', 160, 50);
           return $bg_seller_buyer_logo_text;
       }
       return false;
   }

function resizeImage($image, $width=null, $height=null, $crop=false, $callback=null ){
    if(!$image)
      return false;
    $image = urldecode($image);
    $image_name = pathinfo($image, PATHINFO_FILENAME);
    $resize_name = $image_name."_".$width."x".($height??$width);
    $resize_name .= ($crop)? '_crop':'';
    $name = $resize_name.".png";

      $base_image = Image::make(str_replace(' ', '%20', $image));
      if(!$crop){
        $full_url = $base_image->resize($width, $height)->encode('data-url');
      }else{
          $rwidth = $width;
          $rheight = $height;
          ($width > $height)? $rwidth = null : $rheight = null;
      $full_url = $base_image->resize($rwidth, $rheight, function ($constraint) {
              $constraint->aspectRatio();
          })
          ->crop($width, $height)
          ->encode('data-url');
      }
    return $full_url;
}

function is_exists($file){
  if(empty($file))
  $file_content ='';
  try{
      $file_content = file_get_contents($file);
    }catch (Exception $e){
      return false;
   }
  return  empty($file_content) ? false : true;
}

function mergeImages($base_image, $top_image, $position='center', $offset_x=0, $offset_y=0){

    $base_image = str_replace(' ', '%20', $base_image);
    $top_image = str_replace(' ', '%20', $top_image);
    $hash_name = sha1(implode('_', func_get_args()));
    $name = $hash_name.".png";
    $s3 = \Storage::disk('s3');
    $s3_bucket = getenv("S3_BUCKET_NAME");
    $filePath = '/company_logo/' . $name;
    $full_url = config('constants.s3.url').$s3_bucket."".$filePath;
    if(!is_exists($full_url)){
      $base_image = Image::make($base_image);
      $top_image = Image::make($top_image);
      $base_image->insert($top_image, $position, $offset_x, $offset_y)->encode('data-url');
      $s3->put($filePath, file_get_contents($base_image), 'public');
    }
    return $full_url;
}

function addTextToImage($base_image, $text='Hello!', $alignment='center', $offset_x=0, $offset_y=0){
    $hash_name = sha1(implode('_', func_get_args()));
    $name = $hash_name.".png";
    $name = time().".png";
    $s3 = \Storage::disk('s3');
    $s3_bucket = getenv("S3_BUCKET_NAME");
    $filePath = '/company_logo/' . $name;
    $full_url = config('constants.s3.url').$s3_bucket."".$filePath;
    if(!is_exists($full_url)){
      $base_image = Image::make($base_image);
      $base_image->text($text, $offset_x, $offset_y, function($font) {
          $font->file(public_path('fonts/mada-semibold.ttf'));
          $font->size(21);
          $font->color('#ffffff');
          $font->align('left');
          $font->valign('middle');
        })->encode('data-url');

      $s3->put($filePath, file_get_contents($base_image), 'public');
    }
    return $full_url;
}