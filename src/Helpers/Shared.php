<?php

use Illuminate\Http\Response;

/**
 * Checks for strings in the include param
 *
 * @param $requestInclude
 * @param $modelRelationship
 *
 * @return bool
 */
function getIncludeCondition($requestInclude, $modelRelationship): bool
{
  $includeArr = [];

  if (!$requestInclude) return false;

  $includeString = explode(",", $requestInclude);

  foreach ($includeString as $condition) {
    $includeArr[] = trim($condition);
  }

  if ($includeArr == []) {
    return false;
  } else {
    $condition = in_array($modelRelationship, $includeArr);
  }

  return $condition;
}

/**
 * Ensure an array input is set
 * And is an Array
 * And has count > 0
 *
 * @param $requestArray
 * @return bool
 */
function isSetArrayInput($requestArray): bool
{
  return (
    isset($requestArray)
    && is_array($requestArray)
    && count($requestArray) > 0
  );
}

/**
 * Ensure a param input is set
 * And is not an empty string
 *
 * @param $value
 * @return bool
 */
function isSetNotEmpty($value): bool
{
  return (isset($value) && $value != '');
}

/**
 * Unified Model response
 *
 * @param $method
 * @param $modelName
 * @param $modelResource
 * @return Response
 */
function modelResponse($method, $modelName, $modelResource): Response
{

  switch ($method) {
    case 'GET':
      $statusCode = 200;
      $msg = __('messages.GET', ['modelName'  => $modelName]);
      break;
    case 'GET FAIL':
      $statusCode = 404;
      $msg = __('messages.GET_FAIL', ['modelName'  => $modelName]);
      break;
    case 'POST':
      $statusCode = 201;
      $msg = __('messages.POST', ['modelName'  => $modelName]);
      break;
    case 'POST FAIL':
      $statusCode = 200;
      $msg = __('messages.POST_FAIL', ['modelName'  => $modelName]);
      break;
    case 'PATCH':
      $statusCode = 200;
      $msg = __('messages.PATCH', ['modelName'  => $modelName]);
      break;
    case 'PATCH FAIL':
      $statusCode = 200;
      $msg = __('messages.PATCH_FAIL', ['modelName'  => $modelName]);
      break;
    case 'PATCH TOGGLE':
      $isDisabled = !is_null($modelResource) ? $modelResource->is_disabled : false;
      $statusCode = 200;
      $msg = $isDisabled ?
        __('messages.TOGGLE_DISABLED', ['modelName'  => $modelResource->name]) :
        __('messages.TOGGLE_ENABLED', ['modelName'  => $modelResource->name]) ;
      break;
    case 'PATCH TOGGLE FAIL':
      $statusCode = 200;
      $msg = __('messages.TOGGLE_FAIL', ['modelName'  => $modelName]);
      break;
    case 'DELETE':
      $statusCode = 200;
      $msg = __('messages.DELETE', ['modelName'  => $modelName]);
      break;
    case 'RESTORE':
      $statusCode = 200;
      $msg = __('messages.RESTORE', ['modelName'  => $modelName]);
      break;
    default:
      $statusCode = 500;
      $msg = __('messages.NO_METHOD', ['modelName'  => $modelName]);
      break;
  }
  $responseArray = [
    'msg' => $msg,
  ];

  if ($modelResource && $method != 'PATCH TOGGLE') {
    $responseArray['data'] = $modelResource;
  }

  return response($responseArray, $statusCode);
}
