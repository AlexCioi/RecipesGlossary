<template>
    <div class="mt-1 container-fluid">
        <div class="row">
            <div class="col shadow p-3 bg-body-tertiary rounded me-5">
                <div>
                    <p class="fw-bold">Most common ingredients</p>
                </div>
                <ol class="list-group list-group-numbered">
                    <li v-for="ingredient in ingredients"
                        :key="ingredient.id"
                        class="list-group-item d-flex align-items-end"
                    >
                        <b-button
                            v-b-tooltip.hover.right
                            :title="ingredient.usages + ' usages'"
                            variant="white outline-white"
                            diabled
                            size="md"
                            class="ms-2 p-0"
                        >
                            {{ ingredient.name }}
                        </b-button>
                    </li>
                </ol>
            </div>
            <div class="col shadow p-3 bg-body-tertiary rounded me-5">
                <div>
                    <p class="fw-bold">Most prolific authors</p>
                </div>
                <ol class="list-group list-group-numbered">
                    <li v-for="author in authors"
                        :key="author.id"
                        class="list-group-item d-flex align-items-end"
                    >
                        <b-button
                            v-b-tooltip.hover.right
                            :title="author.recipeCount + ' recipes written'"
                            variant="white outline-white"
                            diabled
                            size="md"
                            class="ms-2 p-0"
                        >
                            {{ author.name }}
                        </b-button>
                    </li>
                </ol>
            </div>
            <div class="col shadow p-3 bg-body-tertiary rounded">
                <div>
                    <p class="fw-bold">Most complex recipes</p>
                </div>
                <ol class="list-group list-group-numbered">
                    <li v-for="recipe in recipes"
                        :key="recipe.id"
                        class="list-group-item d-flex justify-content-between align-items-start">
                        <b-button
                            v-b-tooltip.hover.right
                            :title="recipe.preparationTime + ' minutes'"
                            variant="white outline-white"
                            diabled
                            size="md"
                            class="ms-2 p-0"
                        >
                            {{ recipe.name }}
                        </b-button>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'LeaderboardComponent',
    data() {
        return {
            ingredients: {},
            authors: {},
            recipes: {},
        };
    },
    methods: {
        fetchTopIngredients() {
            axios
                .get('/api/ingredients/common')
                .then((response) => {
                    this.processIngredientData(response.data, this.ingredients);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchTopAuthors() {
            axios
                .get('/api/authors/prolific')
                .then((response) => {
                    this.processAuthorData(response.data);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchTopRecipes() {
            axios
                .get('/api/recipes/complex')
                .then((response) => {
                    this.processRecipeData(response.data);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        processIngredientData(array) {
            let index = 0;
            this.ingredients = array.map((subArray) => {
                const id = index;
                const name = subArray[0];
                const usages = subArray[1];

                index += 1;
                return { id, name, usages };
            });
        },
        processAuthorData(array) {
            let index = 0;
            this.authors = array.map((subArray) => {
                const id = index;
                const name = subArray[0];
                const recipeCount = subArray[1];

                index += 1;
                return { id, name, recipeCount };
            });
        },
        processRecipeData(array) {
            let index = 0;
            this.recipes = array.map((subArray) => {
                const id = index;
                const name = subArray[0];
                const preparationTime = subArray[1];

                index += 1;
                return { id, name, preparationTime };
            });
        },
    },
    mounted() {
        this.fetchTopIngredients();
        this.fetchTopAuthors();
        this.fetchTopRecipes();
    },
};
</script>

<style>

</style>
