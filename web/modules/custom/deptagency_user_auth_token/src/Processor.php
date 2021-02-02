<?php

namespace Drupal\deptagency_user_auth_token;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\user\UserInterface;

/**
 * Processor service.
 */
class Processor {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a Processor object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Generates the authentication token.
   *
   * @return string
   *   The token.
   */
  public function generateAuthToken(): string {
    return substr(str_shuffle('0123456789012345678901234567890123456789'), 0, 32);
  }

  /**
   * Finds the user by auth token.
   *
   * @param string $token
   *   The auth token.
   *
   * @return \Drupal\user\UserInterface|null
   *   The User instance or NULL if not found.
   */
  public function findUser(string $token): ?UserInterface {
    if (empty($token)) {
      return NULL;
    }

    $users = $this->entityTypeManager->getStorage('user')->loadByProperties([
      'auth_token' => $token,
    ]);

    if (empty($users)) {
      return NULL;
    }

    // We assume the auth token is unique so there should be just one user.
    return current($users);
  }

  /**
   * Authenticates the user.
   *
   * @param string $token
   *   The authentication token.
   *
   * @return bool
   *   TRUE if the user was authenticated, FALSE otherwise.
   */
  public function authenticateUser(string $token): bool {
    $user = $this->findUser($token);

    if (!$user) {
      return FALSE;
    }

    user_login_finalize($user);

    return TRUE;
  }

}
