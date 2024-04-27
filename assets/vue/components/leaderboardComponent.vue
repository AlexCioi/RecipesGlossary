<template>
    <div class="mt-1 container-fluid">
        <div class="row">
            <div class="col shadow p-3 bg-body-tertiary rounded me-5">
                <div>
                    <p class="fw-bold">Most common ingredients</p>
                </div>
                <ol v-if="!isLoadingIngredients" class="list-group list-group-numbered">
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
                <button v-if="isLoadingIngredients" class="btn btn-primary" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span role="status">Loading...</span>
                </button>
            </div>
            <div class="col shadow p-3 bg-body-tertiary rounded me-5">
                <div>
                    <p class="fw-bold">Most prolific authors</p>
                </div>
                <ol v-if="!isLoadingAuthors" class="list-group list-group-numbered">
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
                <button v-if="isLoadingAuthors" class="btn btn-primary" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span role="status">Loading...</span>
                </button>
            </div>
            <div class="col shadow p-3 bg-body-tertiary rounded">
                <div>
                    <p class="fw-bold">Most complex recipes</p>
                </div>
                <ol v-if="!isLoadingRecipes" class="list-group list-group-numbered">
                    <li v-for="recipe in recipes"
                        :key="recipe.id"
                        class="list-group-item d-flex align-items-end">
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
                <button v-if="isLoadingRecipes" class="btn btn-primary" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span role="status">Loading...</span>
                </button>
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
            isLoadingIngredients: false,
            isLoadingAuthors: false,
            isLoadingRecipes: false,
        };
    },
    methods: {
        toggleLoadingIngredients() {
            this.isLoadingIngredients = !this.isLoadingIngredients;
        },
        toggleLoadingAuthors() {
            this.isLoadingAuthors = !this.isLoadingAuthors;
        },
        toggleLoadingRecipes() {
            this.isLoadingRecipes = !this.isLoadingRecipes;
        },
        fetchTopIngredients() {
            this.toggleLoadingIngredients();
            axios
                .get('/api/ingredients/common')
                .then((response) => {
                    this.processIngredientData(response.data);
                    this.toggleLoadingIngredients();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchTopAuthors() {
            this.toggleLoadingAuthors();
            axios
                .get('/api/authors/prolific')
                .then((response) => {
                    this.processAuthorData(response.data);
                    this.toggleLoadingAuthors();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchTopRecipes() {
            this.toggleLoadingRecipes();
            axios
                .get('/api/recipes/complex')
                .then((response) => {
                    this.processRecipeData(response.data);
                    this.toggleLoadingRecipes();
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
