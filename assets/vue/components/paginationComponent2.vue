<template>
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-3">
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
                            @change="fetchPageData">
                        <label :for=ingredient.id> {{ ingredient.name }} </label>
                    </div>
                </div>
            </div>
            <div class="col-6">
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
                            <th scope="col" @click="orderByName" class="d-flex align-content-end">
                                <div class="me-1">
                                    Name
                                </div>
                                <div v-if="criterion === 0">
                                    <svg v-if="direction === 0" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-sort-alpha-up" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M10.082 5.629 9.664 7H8.598l1.789-5.332h1.234L13.402 7h-1.12l-.419-1.371zm1.57-.785L11 2.687h-.047l-.652 2.157z"/>
                                        <path d="M12.96 14H9.028v-.691l2.579-3.72v-.054H9.098v-.867h3.785v.691l-2.567 3.72v.054h2.645zm-8.46-.5a.5.5 0 0 1-1 0V3.707L2.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.5.5 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L4.5 3.707z"/>
                                    </svg>
                                    <svg v-if="direction === 1" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-sort-alpha-down-alt" viewBox="0 0 16 16">
                                        <path d="M12.96 7H9.028v-.691l2.579-3.72v-.054H9.098v-.867h3.785v.691l-2.567 3.72v.054h2.645z"/>
                                        <path fill-rule="evenodd" d="M10.082 12.629 9.664 14H8.598l1.789-5.332h1.234L13.402 14h-1.12l-.419-1.371zm1.57-.785L11 9.688h-.047l-.652 2.156z"/>
                                        <path d="M4.5 2.5a.5.5 0 0 0-1 0v9.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L4.5 12.293z"/>
                                    </svg>
                                </div>
                            </th>
                            <th scope="col">Author</th>
                            <th scope="col" @click="orderByIngredients" class="d-flex align-content-end">
                                <div class="me-1">
                                    Ing. count
                                </div>
                                <div v-if="criterion === 1">
                                    <svg v-if="direction === 0" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-sort-numeric-up" viewBox="0 0 16 16">
                                        <path d="M12.438 1.668V7H11.39V2.684h-.051l-1.211.859v-.969l1.262-.906h1.046z"/>
                                        <path fill-rule="evenodd" d="M11.36 14.098c-1.137 0-1.708-.657-1.762-1.278h1.004c.058.223.343.45.773.45.824 0 1.164-.829 1.133-1.856h-.059c-.148.39-.57.742-1.261.742-.91 0-1.72-.613-1.72-1.758 0-1.148.848-1.835 1.973-1.835 1.09 0 2.063.636 2.063 2.687 0 1.867-.723 2.848-2.145 2.848zm.062-2.735c.504 0 .933-.336.933-.972 0-.633-.398-1.008-.94-1.008-.52 0-.927.375-.927 1 0 .64.418.98.934.98"/>
                                        <path d="M4.5 13.5a.5.5 0 0 1-1 0V3.707L2.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.5.5 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L4.5 3.707z"/>
                                    </svg>
                                    <svg v-if="direction === 1" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-sort-numeric-down-alt" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M11.36 7.098c-1.137 0-1.708-.657-1.762-1.278h1.004c.058.223.343.45.773.45.824 0 1.164-.829 1.133-1.856h-.059c-.148.39-.57.742-1.261.742-.91 0-1.72-.613-1.72-1.758 0-1.148.848-1.836 1.973-1.836 1.09 0 2.063.637 2.063 2.688 0 1.867-.723 2.848-2.145 2.848zm.062-2.735c.504 0 .933-.336.933-.972 0-.633-.398-1.008-.94-1.008-.52 0-.927.375-.927 1 0 .64.418.98.934.98"/>
                                        <path d="M12.438 8.668V14H11.39V9.684h-.051l-1.211.859v-.969l1.262-.906h1.046zM4.5 2.5a.5.5 0 0 0-1 0v9.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L4.5 12.293z"/>
                                    </svg>
                                </div>
                            </th>
                            <th scope="col">Skill</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in items"
                            :key="item.id"
                        >
                            <td class="col-6"
                                data-bs-toggle="modal"
                                :data-bs-target="'#modal-'+item.recipeId"
                                @click="fetchRecipeDetails(item.recipeId)"
                            >
                                <div class="my-2 ms-2">
                                    {{ item.recipe }}
                                </div>
                                <div class="modal fade" :id="'modal-'+item.recipeId" data-bs-target="static" data-bs-keyboard="false" tabindex="-1" :aria-labelledby="'staticBackdropLabel'+item.recipeId" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
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
                                                                        class="list-group-item my-1"
                                                                        v-for="ingredient in item.ingredientList"
                                                                        :key="ingredient.id"
                                                                    >
                                                                        <button type="button"
                                                                                class="btn btn-light"
                                                                                disabled>
                                                                            {{ ingredient.name }}
                                                                        </button>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div :class="modalDetailLabel">
                                                                <p class="fw-bold"> Collections: </p>
                                                            </div>
                                                            <div :class="modalDetailContent">
                                                                <div class="d-flex flex-wrap">
                                                                    <button type="button"
                                                                            class="btn btn-sm btn-light mx-1 my-1"
                                                                            disabled
                                                                            v-for="collection in recipeDetails[0]"
                                                                            :key="collection"
                                                                    >
                                                                        {{ collection }}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div :class="modalDetailLabel">
                                                                <p class="fw-bold"> Keywords: </p>
                                                            </div>
                                                            <div :class="modalDetailContent">
                                                                <div class="d-flex flex-wrap">
                                                                    <button type="button"
                                                                            class="btn btn-sm btn-light mx-1 my-1 text-black"
                                                                            disabled
                                                                            v-for="collection in recipeDetails[2]"
                                                                            :key="collection"
                                                                    >
                                                                        {{ collection }}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div :class="modalDetailLabel">
                                                                <p class="fw-bold"> Diet Types: </p>
                                                            </div>
                                                            <div :class="modalDetailContent">
                                                                <div class="d-flex flex-wrap">
                                                                    <button type="button"
                                                                            class="btn btn-sm btn-light mx-1 my-1"
                                                                            disabled
                                                                            v-for="collection in recipeDetails[1]"
                                                                            :key="collection"
                                                                    >
                                                                        {{ collection }}
                                                                    </button>
                                                                </div>
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
            recipeDetails: [],
            direction: 0,
            criterion: 0,
        };
    },
    methods: {
        toggleBusy() {
            this.isBusy = !this.isBusy;
        },
        toggleIngredientsLoading() {
            this.isLoadingIngredients = !this.isLoadingIngredients;
        },
        orderByIngredients() {
            if (this.criterion === 1) {
                this.switchDirection();
            } else {
                this.toggleIngredientCriterion();
            }

            this.fetchPageData();
        },
        orderByName() {
            if (this.criterion === 0) {
                this.switchDirection();
            } else {
                this.criterion = 0;
            }

            this.fetchPageData();
        },
        switchDirection() {
            this.direction = this.direction === 0 ? 1 : 0;
        },
        toggleIngredientCriterion() {
            this.criterion = 1;
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
                        direction: this.direction,
                        criterion: this.criterion,
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

                const { id: recipeId } = node[1].properties;

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
                    this.authorRecipes = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        fetchRecipeDetails(id) {
            this.recipeDetails = [];
            axios
                .get('/api/recipe/details', {
                    params: {
                        recipeId: id,
                    },
                })
                .then((response) => {
                    this.recipeDetails = response.data;
                    console.log(this.recipeDetails);
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
