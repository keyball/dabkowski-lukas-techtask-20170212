<?php
/**
 * Created by PhpStorm.
 * User: Lukas Dabkowski <lukas@phpcat.net>
 * Date: 2017-02-12
 */

namespace app\Http\Controllers;

use app\Models\Recipes;
use app\Tools\JsonDataType;
use app\Tools\Twitter;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LunchController
 *
 * @package app\Http\Controllers
 */
class LunchController
{

    /**
     * Lunch controllers
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function lunch(Request $request, Application $app) : JsonResponse
    {

        $ingredients = [];

        $jsonContent = $request->getContent();

        if (!empty($jsonContent)) {

            if (!JsonDataType::isValidJSON($jsonContent)) {
                return new JsonResponse(['error' => 'The given input is not a JSON string!'],
                  Response::HTTP_BAD_REQUEST);
            }

            $ingredients = JsonDataType::decodeJSON($jsonContent, true);
        }

        $proposals = (new Recipes())->lunchProposals($ingredients);

        $tweet = new Twitter($app[ 'twitter' ][ 'consumer.key' ], $app[ 'twitter' ][ 'consumer.secret' ]);
        $tweet->updateStatus(implode(' or ', $proposals));

        return new JsonResponse(['Lunch proposals' => $proposals], Response::HTTP_OK);

    }

}