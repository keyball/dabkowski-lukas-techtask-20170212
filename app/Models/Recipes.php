<?php
/**
 * Created by PhpStorm.
 * User: Lukas Dabkowski <lukas@phpcat.net>
 * Date: 2017-02-12
 */

namespace app\Models;


use app\Tools\JsonDataType;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Recipes
{

    protected $ingredients = [];

    protected $recipes = [];

    protected $finder;

    public function __construct()
    {
        $this->finder = (new Finder())->files()->in( ROOT . 'files' );
    }

    /**
     * Gets the lunch proposals
     *
     * @param array $ingredients
     *
     * @return array
     */
    public function lunchProposals(array $ingredients = []) : array
    {

        if (empty($ingredients)) {
            $ingredients = $this->getIngredients();
        }

        // can be get via parameters
        $currentDate = date('Y-m-d');

        foreach ($ingredients[ 'ingredients' ] as $ingredient) {

            $name = $ingredient[ 'title' ];
            $bestBefore = $ingredient[ 'best-before' ];
            $useBy = $ingredient[ 'use-by' ];

            $this->ingredients[ $name ] = new class($name, $currentDate, $bestBefore, $useBy) extends AbstractIngredient
            {
            };
        }

        $this->recipes = $this->getRecipes()[ 'recipes' ];

        return $this->menuALaCarte();

    }

    /**
     * Gets the ingredients
     *
     * @return array
     */
    private function getIngredients() : array
    {
        return JsonDataType::decodeJSON( $this->getFileContents( 'ingredients' ), true);
    }

    /**
     * Gets the recipes
     *
     * @return array
     */
    private function getRecipes() : array
    {
        return JsonDataType::decodeJSON( $this->getFileContents( 'recipes' ), true);
    }

    /**
     * Creates a menu
     *
     * @return array
     */
    private function menuALaCarte() : array
    {

        $proposals = [];

        foreach ($this->recipes as $recipe) {

            $name = $recipe[ 'title' ];
            $hasIngredientsAfterBestBefore = false;

            foreach ($recipe[ 'ingredients' ] as $ingredient) {

                /**
                 * @var AbstractIngredient
                 */
                $recipeIngredient = $this->ingredients[ $ingredient ] ?? null;

                if (!($recipeIngredient instanceof AbstractIngredient) || !$recipeIngredient->isValid()) {
                    continue 2;
                }

                if ($recipeIngredient->isYetUsable()) {
                    $hasIngredientsAfterBestBefore = true;
                }
            }

            if ($hasIngredientsAfterBestBefore) {
                array_push($proposals, $name);
            } else {
                array_unshift($proposals, $name);
            }
        }

        return $proposals;
    }

    /**
     * Get file contents
     *
     * @param string $filename
     *
     * @return string
     */
    private function getFileContents( string $filename ) : string
    {
        $contents = '';


        foreach ($this->finder->name($filename . '.json') as $file) {

            /**
             * @var SplFileInfo $file
             */
            $contents = $file->getContents();
        }

        return $contents;
    }

}