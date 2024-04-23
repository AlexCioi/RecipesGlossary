<template>
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-2">
                <div class="container-fluid d-flex flex-wrap">
                    <div
                        class="mx-2"
                        v-for="ingredient in ingredients"
                        :key="ingredient.id"
                    >
                        <input
                            type="checkbox"
                            :id=ingredient.id
                            :value=ingredient.name
                            v-model="checkedIngredients"
                            @change="fetchPageData(currentPage)">
                        <label :for=ingredient.id> {{ ingredient.name }} </label>
                    </div>
                </div>
            </div>
            <div class="col-10">
                <div class="input-group w-50 mb-2">
                    <span class="input-group-text" id="search">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                        </svg>
                    </span>
                    <input type="text"
                           class="form-control"
                           placeholder="find a recipe..."
                           aria-label="Username"
                           aria-describedby="search"
                           v-model="queryName"
                           v-on:input="nameSearchHandle"
                    >
                </div>
                <table class="table table-hover table-bordered" id="table">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Author</th>
                            <th scope="col">Number of ingredients</th>
                            <th scope="col">Skill level</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in items"
                            :key="item.id"
                        >
                            <td class="col-6"  data-bs-toggle="modal" :data-bs-target="'#modal-'+item.recipeId">
                                <div class="my-2 ms-2">
                                    {{ item.recipe }}
                                </div>
                                <div class="modal fade" :id="'modal-'+item.recipeId" data-bs-target="static" data-bs-keyboard="false" tabindex="-1" :aria-labelledby="'staticBackdropLabel'+item.recipeId" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" :id="'staticBackdropLabel'+item.recipeId"> {{ item.recipe }} </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="card card-body">
                                                    <div class="container">
                                                        <div class="row">
                                                            <div :class="modalDetailLabel">
                                                                <p class="fw-bold"> Written by: </p>
                                                            </div>
                                                            <div class="col-8">
                                                                <p class="light"> {{ item.authorName }} </p>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div :class="modalDetailLabel">
                                                                <p class="fw-bold"> Description: </p>
                                                            </div>
                                                            <div :class="modalDetailContent">
                                                                {{ item.description }}
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div :class="modalDetailLabel">
                                                                <p class="fw-bold"> Cooking time: </p>
                                                            </div>
                                                            <div :class="modalDetailContent">
                                                                {{ item.cookingTime / 60 }} minutes
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div :class="modalDetailLabel">
                                                                <p class="fw-bold"> Preparation time: </p>
                                                            </div>
                                                            <div :class="modalDetailContent">
                                                                {{ item.preparationTime / 60 }} minutes
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div :class="modalDetailLabel">
                                                                <p class="fw-bold"> Ingredients: </p>
                                                            </div>
                                                            <div :class="modalDetailContent">
                                                                <ul>
                                                                    <li
                                                                        v-for="ingredient in item.ingredientList"
                                                                        :key="ingredient.id"
                                                                    > {{ ingredient.name }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="col-3" @click="fetchAuthorRecipes(item.authorName)"
                                data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop"
                            >
                                <div class="ms-2 my-2">
                                    {{ item.authorName }}
                                </div>
                                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">{{ item.authorName }}</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <ul>
                                                    <li v-for="recipe in authorRecipes"
                                                        :key="recipe"
                                                        class="my-2"
                                                    >
                                                        {{recipe}}
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary">Understood</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
                            <td class="col-2"> {{ item.numberOfIngredients }} </td>
                            <td class="col-1"> {{ item.skill }} </td>
                        </tr>
                    </tbody>
                </table>

<!--                <b-button variant="primary" disabled>-->
<!--                    <b-spinner small type="grow"></b-spinner>-->
<!--                    Loading...-->
<!--                </b-button>-->

                <template>
                    <div class="overflow-auto mt-1">
                        <b-pagination
                            v-model="currentPage"
                            :total-rows="numberOfRecipes"
                            :per-page="20"
                            aria-controls="table"
                            @input="fetchPageData"
                        >
                        </b-pagination>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import tab from 'bootstrap/js/src/tab';

export default {
    name: 'paginationComponent2',
    data() {
        return {
            modalDetailLabel: 'col-4',
            modalDetailContent: 'col-8',
            queryName: null,
            checkedIngredients: [],
            isBusy: 1,
            isLoadingIngredients: 0,
            currentPage: 1,
            ingredients: null,
            items: [],
            numberOfRecipes: 0,
            authorRecipes: [],
        };
    },
    methods: {
        toggleBusy() {
            this.isBusy = !this.isBusy;
        },
        toggleIngredientsLoading() {
            this.isLoadingIngredients = !this.isLoadingIngredients;
        },
        nameSearchHandle() {
            clearTimeout(this.searchTimeout);

            this.searchTimeout = setTimeout(() => {
                if (this.queryName.length >= 3 || this.queryName === '') {
                    this.currentPage = 1;
                    this.fetchPageData();
                }
            }, 500);
        },
        fetchPageData() {
            axios
                .get('/api/recipes', {
                    params: {
                        pageNumber: this.currentPage,
                        name: this.queryName,
                        ingredients: this.checkedIngredients,
                    },
                })
                .then((response) => {
                    this.processDataArray(response.data);
                    this.toggleBusy();
                })
                .catch((error) => {
                    console.log(error);
                });

            this.fetchNumberOfPages();
        },
        processDataArray(array) {
            this.items = array.map((node) => {
                //console.log(node);
                const {
                    name: recipe, cookingTime, description, preparationTime, skillLevel: skill,
                } = node[1].properties;

                const { id: authorId } = node[0];

                const { id: recipeId } = node[1];

                const { name: authorName } = node[0].properties;

                const numberOfIngredients = node[2].length;

                const ingredientList = node[2].map((ingredient) => {
                    const { name } = ingredient.properties;

                    const { id } = ingredient;

                    return { name, id };
                });

                return {
                    recipeId, recipe, cookingTime, description, preparationTime, skill, authorName, authorId, numberOfIngredients, ingredientList,
                };
            });
        },
        fetchIngredients() {
            this.toggleIngredientsLoading();
            axios
                .get('/api/ingredients')
                .then((response) => {
                    this.processIngredientData(response.data);
                    this.toggleIngredientsLoading();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        processIngredientData(array) {
            this.ingredients = array.map((ingredientNode) => {
                const { id } = ingredientNode[0];

                const { name } = ingredientNode[0].properties;

                return { id, name };
            });
        },
        fetchNumberOfPages() {
            axios
                .get('/api/pagination', {
                    params: {
                        name: this.queryName,
                        ingredients: this.checkedIngredients,
                    },
                })
                .then((response) => {
                    console.log(response.data);
                    this.numberOfRecipes = response.data.numberOfRecipes;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchAuthorRecipes(name) {
            this.authorRecipes = [];
            axios
                .get('/api/author/recipes', {
                    params: {
                        authorName: name,
                    },
                })
                .then((response) => {
                    console.log(response.data);
                    this.authorRecipes = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
    mounted() {
        this.fetchPageData();
        this.fetchIngredients();
    },
};
</script>

<style>

</style>
