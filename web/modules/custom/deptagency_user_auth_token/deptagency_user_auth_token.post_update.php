<?php

use Drupal\user\Entity\User;

/**
 * Generates an auth token for each user.
 */
function deptagency_user_auth_token_post_update_0001_set_token() {
  /** @var \Drupal\deptagency_user_auth_token\Processor $processor */
  $processor = \Drupal::service('deptagency_user_auth_token.processor');

  $users = User::loadMultiple();

  foreach ($users as $user) {
    $user->set('auth_token', $processor->generateAuthToken());

    $user->save();
  }
}
