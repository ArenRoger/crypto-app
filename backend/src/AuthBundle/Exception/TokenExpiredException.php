<?php

namespace App\AuthBundle\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class TokenExpiredException extends AuthenticationException
{

}
