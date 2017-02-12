<?php
/**
 * Created by PhpStorm.
 * User: Lukas Dabkowski <lukas@phpcat.net>
 * Date: 2017-02-12
 */

namespace app\Models;


abstract class AbstractIngredient
{

  protected $name;

  protected $currentDate;

  protected $bestBefore;

  protected $useBy;

  protected $isYetUsable = false;

  public function __construct( string $name, string $currentDate, string $bestBefore, string $useBy ) {

    $this->name = $name;
    $this->currentDate = strtotime( $currentDate );
    $this->bestBefore = strtotime( $bestBefore );
    $this->useBy = strtotime( $useBy );
  }

  public function isValid() : bool {

    if ( $this->currentDate === false ) {
      return false;
    }

    if ( $this->currentDate > $this->useBy ) {
      return false;
    }

    if ( $this->currentDate >= $this->bestBefore && $this->currentDate <= $this->useBy ) {

      $this->isYetUsable = true;
      return true;
    }

    return true;

  }

  public function getName() {
    return $this->name;
  }

  public function isYetUsable() : bool {
    return $this->isYetUsable;
  }



}