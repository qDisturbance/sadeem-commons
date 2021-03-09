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
      $code = 1000;
      $msg = "{$modelName} retrieved successfully";
      break;
    case 'POST':
      $statusCode = 201;
      $code = 1000;
      $msg = "{$modelName} created successfully";
      break;
    case 'POST FAIL':
      $statusCode = 201;
      $code = 3001;
      $msg = "{$modelName} was not created";
      break;
    case 'PATCH':
      $statusCode = 200;
      $code = 1000;
      $msg = "{$modelName} updated successfully";
      break;
    case 'PATCH FAIL':
      $statusCode = 200;
      $code = 3002;
      $msg = "{$modelName} model was not updated";
      break;
    case 'PATCH TOGGLE':

      $isDisabled = !is_null($modelResource) ? $modelResource->is_disabled : false;
      $statusCode = 200;
      $code = 1000;
      $msg = $isDisabled ?
        "{$modelResource->name} disabled" :
        "{$modelResource->name} enabled";
      break;
    case 'PATCH TOGGLE FAIL':
      $statusCode = 200;
      $code = 3002;
      $msg = "{$modelName}: Toggle failed";
      break;
    case 'DELETE':
      $statusCode = 200;
      $code = 1000;
      $msg = "{$modelName} deleted successfully";
      break;
    case 'RESTORE':
      $statusCode = 200;
      $code = 1000;
      $msg = "{$modelName} restored successfully";
      break;
    default:
      $statusCode = 500;
      $code = 3002;
      $msg = "no method selected in SharedHelper response";
      break;
  }
  $responseArray = [
    'code' => $code,
    'msg' => $msg,
  ];

  if ($modelResource && $method != 'PATCH TOGGLE') {
    $responseArray['data'] = $modelResource;
  }

  return response($responseArray, $statusCode);
}
