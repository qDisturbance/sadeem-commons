<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;


/*
|--------------------------------------------------------------------------
| Uploads an image and returns path
|--------------------------------------------------------------------------
*/
function insertImage($file, $subDirectory)
{
  if (isset($file)) {
    $file_ext = $file->getClientOriginalExtension();
    $file_name = "{$subDirectory}_" . date_timestamp_get(now()) . '_' . auth()->user()->id . '.' . $file_ext;

    makeThumbnail($file, getThumbnailWidth(), $subDirectory, $file_name, $file_ext);

    $path = $file->storeAs("public/pictures/{$subDirectory}", $file_name);

    return $path;
  }
  return null;
}

/*
|--------------------------------------------------------------------------
| deletes an image on new upload to same object
|--------------------------------------------------------------------------
*/
function updateImage($modelInstance, $modelName, $subDirectory, $file): Response
{
  if (!empty($file)) {
    $storedFileName = basename(Storage::url($modelInstance->img));

    Storage::delete($modelInstance->img);
    Storage::delete("public/pictures/{$subDirectory}/thumbs/thumb_{$storedFileName}");

  }

  $data['img'] = insertImage($file, $subDirectory);

  $modelInstance->update($data);

  if ($modelInstance->wasChanged()) {
    return modelResponse('PATCH', $modelName, $data);
  } else {
    return modelResponse('PATCH FAIL', $modelName, null);
  }
}

function makeThumbnail($image, $thumbWidth, $subDirectory, $file_name, $file_ext)
{
  $store_in = "storage/pictures/{$subDirectory}/thumbs/thumb_" . $file_name;

  // create by extension

  if(empty($file_ext)) {
    return modelResponse('POST FAIL', 'image file extension not provided', null);
  }

  if ($file_ext == 'jpg' || $file_ext == 'jpeg')
    $sourceImage = imagecreatefromjpeg($image);

  if ($file_ext == 'png')
    $sourceImage = imagecreatefrompng($image);

  if ($file_ext == 'bmp')
    $sourceImage = imagecreatefrombmp($image);

  //  resize

  $orgWidth = imagesx($sourceImage);
  $orgHeight = imagesy($sourceImage);
  $thumbHeight = floor($orgHeight * ($thumbWidth / $orgWidth));
  $destImage = imagecreatetruecolor($thumbWidth, $thumbHeight);

  imagecopyresampled($destImage, $sourceImage, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $orgWidth, $orgHeight);

  //Store the file

  if ($file_ext == 'jpg' || $file_ext == 'jpeg')
    imagejpeg($destImage, $store_in);

  if ($file_ext == 'png')
    imagepng($destImage, $store_in);

  if ($file_ext == 'bmp')
    imagebmp($destImage, $store_in);

  // destroy temps
  imagedestroy($sourceImage);
  imagedestroy($destImage);
}
