<?php

namespace App\Service;

class QueryBuilder
{
    private function buildBaseQuery(int $pageNumber, ?string $name, ?array $ingredients): string
    {
        $recipeQuery = '
            MATCH (a:Author)-[:WROTE]->(r:Recipe)
            MATCH (r)-[:CONTAINS_INGREDIENT]->(i:Ingredient)
        ';

        $ingredientsFilterString = json_encode($ingredients);

        $ingredientFilterQuery = 'i.name IN'.$ingredientsFilterString;

        $nameQuery = 'toLower(r.name) CONTAINS toLower("'.$name.'")';

        $query = $recipeQuery;
        if ($ingredients) {
            $query .= ' WHERE ';
            $query .= $ingredientFilterQuery;
        }

        if ($name) {
            if (!$ingredients) {
                $query .= ' WHERE ';
            } else {
                $query .= ' AND ';
            }

            $query = $query . $nameQuery;
        }

        return $query;
    }

    public function returnResultQuery(int $pageNumber, ?string $name, ?array $ingredients): string
    {
        $skip = ($pageNumber - 1) * 20;
        $limit = 20;

        $query = $this->buildBaseQuery($pageNumber, $name, $ingredients);

        $returnQuery = '
            RETURN a, r, COLLECT(DISTINCT i) as ingredients ORDER BY r.name ASC SKIP '. $skip .' LIMIT '. $limit .' 
        ';

        $query .= $returnQuery;

        return $query;
    }

    public function returnCountQuery(int $pageNumber, ?string $name, ?array $ingredients): string
    {
        $query = $this->buildBaseQuery($pageNumber, $name, $ingredients);

        $resultQuery = '
            RETURN COUNT(DISTINCT r) as count
        ';

        $query .= $resultQuery;
        return $query;
    }
}
