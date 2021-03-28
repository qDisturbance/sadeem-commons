<?php

return [

  /*
   * Response Messages
   */
  'GET' => ':modelName retrieved successfully',
  'GET_FAIL' => ':modelName not found',
  'POST' => ':modelName created successfully',
  'POST_FAIL' => ':modelName was not created',
  'PATCH' => ':modelName updated successfully',
  'PATCH_FAIL' => ':modelName model was not updated',
  'TOGGLE_ENABLED' => ':modelName enabled',
  'TOGGLE_DISABLED' => ':modelName disabled',
  'TOGGLE_FAIL' => ':modelName toggle failed',
  'DELETE' => ':modelName deleted successfully',
  'RESTORE' => ':modelName restored successfully',
  'NO_METHOD' => ':modelName no method selected in SharedHelper response',

  /*
   * Auth Messages
   */
  'register' => 'Registered number :phone',
  'login' => 'Logged in! Welcome back :name',
  'logout' => 'Logged out!',
  'refresh_token' => 'User token refreshed',
  'phone_login_fail' => 'No user with this phone number!',
  'email_login_fail' => 'Invalid credentials',
  'revoked_user' => 'Your account have been disabled',
];
