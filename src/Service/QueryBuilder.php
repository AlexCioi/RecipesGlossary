<?php

namespace App\Service;

class QueryBuilder
{
    private function buildBaseQuery(?string $name, ?array $ingredients): string
    {
        $recipeQuery = '
            MATCH (a:Author)-[:WROTE]->(r:Recipe)
            MATCH (r)-[:CONTAINS_INGREDIENT]->(i:Ingredient)
        ';

        $ingredientsFilterString = json_encode($ingredients);

        $ingredientFilterQuery = '
        a, r, COLLECT(DISTINCT i) as all_ingredients WHERE ANY (i IN all_ingredients WHERE i.name IN'.$ingredientsFilterString.')';

        $nameQuery = 'toLower(r.name) CONTAINS toLower("'.$name.'")';

        $query = $recipeQuery;
        if ($ingredients) {
            $query .= ' WITH ';
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

        $orderSkipLimitQuery = ' ORDER BY r.name ASC SKIP ' . $skip . ' LIMIT ' . $limit;

        $query = $this->buildBaseQuery($name, $ingredients);
        if (!$ingredients) {
            $returnQuery = '
                RETURN a, r, COLLECT(DISTINCT i) as ingredients'.$orderSkipLimitQuery;
        } else {
            $returnQuery = 'RETURN a, r, all_ingredients'.$orderSkipLimitQuery;
        }
        $query .= $returnQuery;

        return $query;
    }

    public function returnCountQuery(?string $name, ?array $ingredients): string
    {
        $query = $this->buildBaseQuery($name, $ingredients);

        $resultQuery = '
            RETURN COUNT(DISTINCT r) as count
        ';

        $query .= $resultQuery;
        return $query;
    }
}
