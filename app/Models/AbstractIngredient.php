<?php
/**
 * Created by PhpStorm.
 * User: Lukas Dabkowski <lukas@phpcat.net>
 * Date: 2017-02-12
 */

namespace app\Models;


abstract class AbstractIngredient
{

    /**
     * Name of the ingredient
     *
     * @var string
     */
    protected $name;

    /**
     * Current date
     *
     * @var false|int
     */
    protected $currentDate;

    /**
     * Best before date
     *
     * @var false|int
     */
    protected $bestBefore;

    /**
     * Use by date
     *
     * @var false|int
     */
    protected $useBy;

    /**
     * Is the ingredient still usable
     *
     * @var bool
     */
    protected $isYetUsable = false;

    /**
     * AbstractIngredient constructor.
     *
     * @param string $name
     * @param string $currentDate
     * @param string $bestBefore
     * @param string $useBy
     */
    public function __construct(string $name, string $currentDate, string $bestBefore, string $useBy)
    {

        $this->name = $name;
        $this->currentDate = strtotime($currentDate);
        $this->bestBefore = strtotime($bestBefore);
        $this->useBy = strtotime($useBy);
    }

    /**
     * Checks the validity of the ingredient
     *
     * @return bool
     */
    public function isValid() : bool
    {

        if ($this->currentDate === false) {
            return false;
        }

        if ($this->currentDate > $this->useBy) {
            return false;
        }

        if ($this->currentDate >= $this->bestBefore && $this->currentDate <= $this->useBy) {

            $this->isYetUsable = true;
            return true;
        }

        return true;

    }

    /**
     * Gets ingredient's name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets ingredients usability
     *
     * @return bool
     */
    public function isYetUsable() : bool
    {
        return $this->isYetUsable;
    }


}