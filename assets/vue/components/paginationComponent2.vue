<template>
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-2">
                <div class="container-fluid d-flex flex-wrap">
                    <div
                        class="mx-2 w-50"
                        v-for="ingredient in ingredients"
                        :key="ingredient.id"
                    >
                        <input
                            type="checkbox"
                            :id=ingredient.id
                            :value=ingredient.name
                            v-model="checkedIngredients"
                            @change="fetchNameSearchResults">
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
                <table class="table table-hover table-bordered">
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
                            <td class="col-6">
                                <a class="btn w-100 text-start" data-bs-toggle="collapse" :href="'#' + item.recipeId" role="button" aria-expanded="false" aria-controls="collapseExample">
                                    {{ item.recipe }}
                                </a>
                                <div class="collapse" :id="item.recipeId">
                                    <div class="card card-body">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-4">
                                                    <p class="fw-bold"> Description: </p>
                                                </div>
                                                <div class="col-8">
                                                    {{ item.description }}
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-4">
                                                    <p class="fw-bold"> Cooking time: </p>
                                                </div>
                                                <div class="col-8">
                                                    {{ item.cookingTime / 60 }} minutes
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-4">
                                                    <p class="fw-bold"> Preparation time: </p>
                                                </div>
                                                <div class="col-8">
                                                    {{ item.preparationTime / 60 }} minutes
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-4">
                                                    <p class="fw-bold"> Ingredients: </p>
                                                </div>
                                                <div class="col-8">
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
                            </td>
                            <td class="col-3"> {{ item.authorName }} </td>
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
                    <div class="overflow-auto">
                        <b-pagination-nav
                            :link-gen="linkGen"
                            use-router
                            :number-of-pages="numberOfPages"
                            @input="fetchPageData"
                        >
                        </b-pagination-nav>
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
            queryName: '',
            checkedIngredients: [''],
            ingredients: null,
            isBusy: 1,
            items: [],
            currentPage: 1,
            numberOfPages: null,
        };
    },
    methods: {
        toggleBusy() {
            this.isBusy = !this.isBusy;
        },
        linkGen(pageNum) {
            return pageNum === 1 ? '?' : `?page=${pageNum}`;
        },
        nameSearchHandle() {
            if (this.queryName.length >= 3) {
                this.fetchNameSearchResults(this.queryName);
            }
        },
        fetchNameSearchResults() {
            axios
                .get('/api/recipes', {
                    params: {
                        pageNumber: 1,
                        name: this.queryName,
                        ingredients: this.checkedIngredients,
                    },
                })
                .then((response) => {
                    this.processDataArray(response.data);
                    // this.toggleBusy();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchPageData(page) {
            if (page) {
                axios
                    .get('/api/recipes', {
                        params: {
                            pageNumber: page,
                            name: this.queryName,
                            ingredients: this.checkedIngredients,
                        },
                    })
                    .then((response) => {
                        this.processDataArray(response.data);
                        this.fetchIngredients();
                        this.toggleBusy();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        processDataArray(array) {
            this.items = array.map((node) => {
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

                const collectionList = node[3].map((ingredient) => {
                    const { name } = ingredient.properties;

                    const { id } = ingredient;

                    return { name, id };
                });

                const dietTypeList = node[2].map((ingredient) => {
                    const { name } = ingredient.properties;

                    const { id } = ingredient;

                    return { name, id };
                });

                const keywordList = node[2].map((ingredient) => {
                    const { name } = ingredient.properties;

                    const { id } = ingredient;

                    return { name, id };
                });

                return {
                    recipeId, recipe, cookingTime, description, preparationTime, skill, authorName, authorId, numberOfIngredients,
                    ingredientList, collectionList, dietTypeList, keywordList,
                };
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
                .get('/api/pagination/numberOfPages')
                .then((response) => {
                    this.numberOfPages = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchIngredients() {
            axios
                .get('/api/ingredients')
                .then((response) => {
                    this.processIngredientData(response.data);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
    mounted() {
        this.fetchNumberOfPages();
    },
};
</script>

<style>

</style>
