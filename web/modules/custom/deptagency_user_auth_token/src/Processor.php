<?php

namespace Drupal\deptagency_user_auth_token;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\user\Entity\User;

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
   * Method description.
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
   * @return \Drupal\user\Entity\User|null
   *   The User instance or NULL if not found.
   */
  public function findUser(string $token): ?User {
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

}
