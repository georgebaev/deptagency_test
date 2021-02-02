<?php

namespace Drupal\deptagency_user_auth_token;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * DeptagencyUserAuthTokenMiddleware middleware.
 */
class DeptagencyUserAuthTokenMiddleware implements HttpKernelInterface {

  use StringTranslationTrait;

  /**
   * The kernel.
   *
   * @var \Symfony\Component\HttpKernel\HttpKernelInterface
   */
  protected $httpKernel;

  /**
   * The processor service instance.
   *
   * @var \Drupal\deptagency_user_auth_token\Processor
   */
  protected $processor;

  /**
   * Constructs the DeptagencyUserAuthTokenMiddleware object.
   *
   * @param \Symfony\Component\HttpKernel\HttpKernelInterface $http_kernel
   *   The decorated kernel.
   * @param \Drupal\deptagency_user_auth_token\Processor $processor
   *   The processor service.
   */
  public function __construct(HttpKernelInterface $http_kernel, Processor $processor) {
    $this->httpKernel = $http_kernel;
    $this->processor = $processor;
  }

  /**
   * {@inheritdoc}
   */
  public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = TRUE) {
    $token = $request->query->get('authtoken');

    if (!empty($token)) {
      if (!$this->processor->authenticateUser($token)) {
        return new Response($this->t('Not authenticated!'), 403);
      }
    }

    return $this->httpKernel->handle($request, $type, $catch);
  }

}
