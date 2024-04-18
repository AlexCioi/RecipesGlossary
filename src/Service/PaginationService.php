<?php

namespace App\Service;

class PaginationService
{
    public function getNumberOfPages(int $numberOfRecipes, int $pageSize): int
    {
        $result = $numberOfRecipes / $pageSize;

        if ($numberOfRecipes % $pageSize != 0) {
            $result++;
        }

        return $result;
    }
}
