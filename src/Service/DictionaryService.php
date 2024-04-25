<?php

namespace App\Service;

class DictionaryService
{
    private array $orderDictionary;
    private array $criterionDictionary;

    public function __construct()
    {
        $this->orderDictionary[0] = ' ASC ';
        $this->orderDictionary[1] = ' DESC ';

        $this->criterionDictionary[0] = ' r.name ';
        $this->criterionDictionary[1] = ' numberOfIngredients ';
        $this->criterionDictionary[2] = ' 
            CASE r.skillLevel
                WHEN "Easy" THEN 1
                WHEN "More effort" THEN 2
                WHEN "A challenge" THEN 3
            END
        ';
    }

    public function getOrder(bool $code)
    {
        return $this->orderDictionary[$code];
    }

    public function getCriterion(int $code)
    {
        return $this->criterionDictionary[$code];
    }
}
