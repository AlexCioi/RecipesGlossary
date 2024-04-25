<?php

namespace App\Service;

class QueryBuilder
{
    private DictionaryService $dictionary;

    public function __construct(DictionaryService $dictionary)
    {
        $this->dictionary = $dictionary;
    }

    private function buildRecipeBaseQuery(?string $name, ?array $ingredients): string
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

    public function returnRecipeResultQuery(
        int $pageNumber, ?string $name, ?array $ingredients, bool $orderDirection, int $criterion): string
    {
        $skip = ($pageNumber - 1) * 20;
        $limit = 20;

        $orderQuery = ' ORDER BY ';
        $skipLimitQuery = ' SKIP ' . $skip . ' LIMIT ' . $limit;

        $orderSkipLimitQuery =
            $orderQuery.
            $this->dictionary->getCriterion($criterion).
            $this->dictionary->getOrder($orderDirection).
            $skipLimitQuery;

//        if ($criterion == 'ingredients') {
//            $orderSkipLimitQuery = ' ORDER BY numberOfIngredients' . $orderDirection . ' SKIP ' . $skip . ' LIMIT ' . $limit;
//        }
//
//        if ($criterion == 'name') {
//            $orderSkipLimitQuery = ' ORDER BY r.name ASC SKIP ' . $skip . ' LIMIT ' . $limit;
//        }

        $query = $this->buildRecipeBaseQuery($name, $ingredients);
        if (!$ingredients) {
            $returnQuery = '
                RETURN a, r, COLLECT(DISTINCT i), size(COLLECT(DISTINCT i)) as numberOfIngredients' . $orderSkipLimitQuery;
        } else {
            $returnQuery = 'RETURN a, r, all_ingredients, SIZE(all_ingredients) as numberOfIngredients' . $orderSkipLimitQuery;
        }
        $query .= $returnQuery;

        return $query;
    }

    public function returnRecipeCountQuery(?string $name, ?array $ingredients): string
    {
        $query = $this->buildRecipeBaseQuery($name, $ingredients);

        $resultQuery = '
            RETURN COUNT(DISTINCT r) as count
        ';

        $query .= $resultQuery;
        return $query;
    }

    public function returnAuthorRecipesQuery(string $authorName): string
    {
        $query = 'MATCH (a:Author {name: "'.$authorName.'"})-[:WROTE]->(r:Recipe) RETURN COLLECT(DISTINCT r.name)';

        return $query;
    }

    public function returnRecipeDetailsQuery(string $recipeId): string
    {
        $query = '
            MATCH (r:Recipe {id: "'.$recipeId.'"})
            OPTIONAL MATCH (r)-[:COLLECTION]->(c:Collection)
            OPTIONAL MATCH (r)-[:DIET_TYPE]->(d:DietType)
            OPTIONAL MATCH (r)-[:KEYWORD]->(k:Keyword)
            RETURN COLLECT(DISTINCT c.name), COLLECT(DISTINCT d.name), COLLECT(DISTINCT k.name)
        ';

        return $query;
    }
}
