<?php
/**
 * Created by PhpStorm.
 * User: Lukas Dabkowski <lukas@phpcat.net>
 * Date: 2017-02-12
 * Time: 12:37
 */

namespace app\Http\Controllers;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Unauthorized
 *
 * @package app\Http\Controllers
 */
class UnauthorizedController
{

    /**
     * Sends 401
     *
     * @return JsonResponse
     */
    public function send401() : JsonResponse
    {
        return new JsonResponse(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

}