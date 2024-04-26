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

    public function returnSimilarRecipes(string $recipeId): string
    {
        $query = '
            MATCH (r1:Recipe {id: "'.$recipeId.'"})-[:CONTAINS_INGREDIENT]->(i1:Ingredient)
            WITH r1, collect(i1.name) AS ingredients1
            MATCH (r2:Recipe)-[:CONTAINS_INGREDIENT]->(i2:Ingredient)
            WHERE r2.id <> "'.$recipeId.'" AND NOT r2 = r1
            WITH r1, ingredients1, r2, collect(i2.name) AS ingredients2
            RETURN r2 AS recipe,
                   round(toFloat(size(apoc.coll.intersection(ingredients1, ingredients2))) / size(apoc.coll.union(ingredients1, ingredients2)), 4) * 100 AS similarity_percentage
            ORDER BY similarity_percentage DESC
            LIMIT 5;
        ';

        return $query;
    }

    public function returnTopIngredients(): string
    {
        $query = '
            MATCH (i:Ingredient)<-[:CONTAINS_INGREDIENT]-(:Recipe)
            RETURN i.name AS ingredient, COUNT(*) AS usageCount
            ORDER BY usageCount DESC
            LIMIT 5;
        ';

        return $query;
    }

    public function returnProlificAuthors(): string
    {
        $query = '
            MATCH (a:Author)-[:WROTE]->(r:Recipe)
            RETURN a.name, COUNT(r) AS recipeCount
            ORDER BY recipeCount DESC
            LIMIT 5;
        ';

        return $query;
    }

    public function returnComplexRecipes(): string
    {
        $query = '
            MATCH (r:Recipe)
            RETURN r.name AS recipe, (r.preparationTime + r.cookingTime) / 60 AS complexity
            ORDER BY complexity DESC
            LIMIT 5
        ';

        return $query;
    }
}
