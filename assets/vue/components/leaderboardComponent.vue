<template>
    <div class="mt-5 container">
        <div class="row">
            <div>
                <h4 class="fw-bold">Most common ingredients</h4>
            </div>
            <ol class="list-group list-group-numbered">
                <li v-for="ingredient in ingredients"
                    :key="ingredient.id"
                    class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">{{ingredient.name}}</div>
                    </div>
                    <span class="badge text-bg-primary rounded-pill">{{ingredient.usages}} usages </span>
                </li>
            </ol>
        </div>
        <div class="row mt-5">
            <div>
                <h4 class="fw-bold">Most prolific authors</h4>
            </div>
            <ol class="list-group list-group-numbered">
                <li v-for="author in authors"
                    :key="author.id"
                    class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold"> {{ author.name }} </div>
                    </div>
                    <span class="badge text-bg-primary rounded-pill"> {{ author.recipeCount }} recipes </span>
                </li>
            </ol>
        </div>
        <div class="row mt-5">
            <div>
                <h4 class="fw-bold">Most complex recipes</h4>
            </div>
            <ol class="list-group list-group-numbered">
                <li v-for="recipe in recipes"
                    :key="recipe.id"
                    class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold"> {{ recipe.name }} </div>
                    </div>
                    <span class="badge text-bg-primary rounded-pill"> {{ recipe.preparationTime }} minutes </span>
                </li>
            </ol>
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
