<?php
/**
 * Created by PhpStorm.
 * User: Lukas Dabkowski <lukas@phpcat.net>
 * Date: 2017-02-12
 */

namespace app\Models;


use app\Tools\JsonDataType;

class Recipes {

  protected $ingredients = [];

  protected $recipes = [];


  /**
   * Gets the lunch proposals
   *
   * @param array $ingredients
   *
   * @return array
   */
  public function lunchProposals( array $ingredients = [] ) : array {

    if ( empty( $ingredients ) ) {
      $ingredients = $this->getIngredients();
    }

    // can be get via parameters
    $currentDate = date( 'Y-m-d' );

    foreach( $ingredients[ 'ingredients' ] as $ingredient ) {

      $name = $ingredient[ 'title' ];
      $bestBefore = $ingredient[ 'best-before' ];
      $useBy = $ingredient[ 'use-by' ];

      $this->ingredients[ $name ] = new class( $name, $currentDate, $bestBefore, $useBy ) extends AbstractIngredient {};
    }

    $this->recipes = $this->getRecipes()[ 'recipes' ];

    return $this->menuALaCarte();

  }

  /**
   * Gets the recipes
   *
   * @return array
   */
  private function getRecipes() : array {

    $recipesJSON = <<<RECIPES_JSON
    {

    "recipes":[
        {
            "title":"Ham and Cheese Toastie",
            "ingredients":[
                "Ham",
                "Cheese",
                "Bread",
                "Butter"
            ]
        },
        {
            "title":"Fry-up",
            "ingredients":[
                "Bacon",
                "Eggs",
                "Baked Beans",
                "Mushrooms",
                "Sausage",
                "Bread"
            ]
        },
        {
            "title":"Salad",
            "ingredients":[
                "Lettuce",
                "Tomato",
                "Cucumber",
                "Beetroot",
                "Salad Dressing"
            ]
        },
        {
            "title":"Hotdog",
            "ingredients":[
                "Hotdog Bun",
                "Sausage",
                "Ketchup",
                "Mustard"
            ]
        }
    ]

}
RECIPES_JSON;

  return JsonDataType::decodeJSON( $recipesJSON, true );
  }

  /**
   * Gets the ingredients
   *
   * @return array
   */
  private function getIngredients() : array {

    $ingredientsJSON = <<<INGREDIENTS
    {

    "ingredients":[
        {
            "title":"Ham",
            "best-before":"2017-02-25",
            "use-by":"2017-02-27"
        },
        {
            "title":"Cheese",
            "best-before":"2017-02-08",
            "use-by":"2017-02-13"
        },
        {
            "title":"Bread",
            "best-before":"2017-02-25",
            "use-by":"2017-02-27"
        },
        {
            "title":"Butter",
            "best-before":"2017-02-25",
            "use-by":"2017-02-27"
        },
        {
            "title":"Bacon",
            "best-before":"2017-02-25",
            "use-by":"2017-02-27"
        },
        {
            "title":"Eggs",
            "best-before":"2017-02-25",
            "use-by":"2017-02-27"
        },
        {
            "title":"Mushrooms",
            "best-before":"2017-02-25",
            "use-by":"2017-02-27"
        },
        {
            "title":"Sausage",
            "best-before":"2017-02-25",
            "use-by":"2017-02-27"
        },
        {
            "title":"Hotdog Bun",
            "best-before":"2017-02-25",
            "use-by":"2017-02-27"
        },
        {
            "title":"Ketchup",
            "best-before":"2017-02-25",
            "use-by":"2017-02-27"
        },
        {
            "title":"Mustard",
            "best-before":"2017-02-25",
            "use-by":"2017-02-27"
        },
        {
            "title":"Lettuce",
            "best-before":"2017-02-25",
            "use-by":"2017-02-27"
        },
        {
            "title":"Tomato",
            "best-before":"2017-02-25",
            "use-by":"2017-02-27"
        },
        {
            "title":"Cucumber",
            "best-before":"2017-02-25",
            "use-by":"2017-02-27"
        },
        {
            "title":"Beetroot",
            "best-before":"2017-02-25",
            "use-by":"2017-02-27"
        },
        {
            "title":"Salad Dressing",
            "best-before":"2017-02-06",
            "use-by":"2017-02-07"
        }
    ]

}
INGREDIENTS;

    return JsonDataType::decodeJSON( $ingredientsJSON, true );
  }


  /**
   * Creates a menu
   *
   * @return array
   */
  private function menuALaCarte() : array {

    $proposals = [];

    foreach ( $this->recipes as $recipe ) {

      $name = $recipe[ 'title' ];
      $hasIngredientsAfterBestBefore = false;

      foreach ( $recipe[ 'ingredients' ] as $ingredient ) {

        /**
         * @var AbstractIngredient
         */
        $recipeIngredient = $this->ingredients[ $ingredient ] ?? null;

        if ( !( $recipeIngredient instanceof AbstractIngredient ) || !$recipeIngredient->isValid() ) {
          continue 2;
        }

        if ( $recipeIngredient->isYetUsable() ) {
          $hasIngredientsAfterBestBefore = true;
        }
      }

      if ( $hasIngredientsAfterBestBefore ) {
        array_push( $proposals, $name );
      } else {
        array_unshift( $proposals, $name );
      }
    }

    return $proposals;
  }

}