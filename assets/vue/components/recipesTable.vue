<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-2">
                <div class="row">
                    <img src="../../../public/images/recipe-logo.jpg" alt="" class="ps-0">
                </div>
                <div class="d-flex flex-wrap">
                    <div v-for="ingredient in checkedIngredients"
                         :key="ingredient"
                         class="bg-secondary text-white rounded-pill d-flex align-items-center m-1"
                    >
                        <div class="my-1 ms-3 me-1">
                            {{ ingredient }}
                        </div>
                        <button type="button"
                                class="bg-secondary border border-0 px-0 py-0 me-3 text-white"
                                @click="removeIngredient(ingredient)"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square" viewBox="0 0 16 16">
                                <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="accordion ps-0 mt-3 pt-3" id="ingredientAccordion">
                        <div class="accordion-item"
                             v-for="letter in letters"
                             :key="letter"
                        >
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        :data-bs-target="'#collapse_' + letter" aria-expanded="true"
                                        :aria-controls="'collapse_' + letter"
                                >
                                    {{ letter }}
                                </button>
                            </h2>
                            <div :id="'collapse_'+ letter" class="accordion-collapse collapse" data-bs-parent="#ingredientAccordion">
                                <div class="accordion-body">
                                    <div class="container-fluid">
                                        <div
                                            class="row"
                                            v-for="ingredient in ingredients[letter.toLowerCase()]"
                                            :key="ingredient.id"
                                        >
                                            <div class="col-2">
                                                <input
                                                    type="checkbox"
                                                    :id=ingredient.id
                                                    :value=ingredient.name
                                                    v-model="checkedIngredients"
                                                    @change="fetchPageData"
                                                >
                                            </div>
                                            <div class="col-10">
                                                <label :for=ingredient.id>
                                                    {{ ingredient.name }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-10 p-4 bg-light">
                <leaderboard-component class="mb-4"/>
                <div class="shadow p-4 mb-5 bg-body-tertiary rounded">
                    <div class="input-group input-group">
                        <span class="input-group-text" id="search">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                            </svg>
                        </span>
                        <input type="text"
                               class="form-control"
                               placeholder="Find a recipe..."
                               aria-label="Username"
                               aria-describedby="search"
                               v-model="queryName"
                               v-on:input="nameSearchHandle"
                        >
                    </div>
                    <table class="table table-hover table-bordered" id="table">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" @click="orderByName">
                                    <div class="d-flex">
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
                                    </div>
                                </th>
                                <th scope="col">Author</th>
                                <th scope="col" @click="orderByIngredients">
                                    <div class="d-flex">
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
                                    </div>
                                </th>
                                <th scope="col" @click="orderBySkill">
                                    <div class="d-flex">
                                        <div class="me-1">
                                            Skill
                                        </div>
                                        <div v-if="criterion === 2">
                                            <svg v-if="direction === 0" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-sort-up-alt" viewBox="0 0 16 16">
                                                <path d="M3.5 13.5a.5.5 0 0 1-1 0V4.707L1.354 5.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.5.5 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L3.5 4.707zm4-9.5a.5.5 0 0 1 0-1h1a.5.5 0 0 1 0 1zm0 3a.5.5 0 0 1 0-1h3a.5.5 0 0 1 0 1zm0 3a.5.5 0 0 1 0-1h5a.5.5 0 0 1 0 1zM7 12.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7a.5.5 0 0 0-.5.5"/>
                                            </svg>
                                            <svg v-if="direction === 1" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-sort-down" viewBox="0 0 16 16">
                                                <path d="M3.5 2.5a.5.5 0 0 0-1 0v8.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L3.5 11.293zm3.5 1a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5M7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="item in items"
                                :key="item.id"
                            >
                                <td class="col-5"
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
                                                                <div class="col-2">
                                                                    <p class="fw-bold"> Ingredients: </p>
                                                                </div>
                                                                <div class="col-4">
                                                                    <ul>
                                                                        <li
                                                                            class="list-group-item my-1"
                                                                            v-for="ingredient in item.ingredientList"
                                                                            :key="ingredient.id"
                                                                        >
                                                                            <button
                                                                                type="button"
                                                                                class="btn btn-light"
                                                                                disabled
                                                                            >
                                                                                {{ ingredient.name }}
                                                                            </button>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                                <div class="col-2">
                                                                    <p class="fw-bold"> Close matches: </p>
                                                                </div>
                                                                <div class="col-4">
                                                                    <ol class="list-group list-group-numbered">
                                                                        <li
                                                                            class="list-group-item d-flex justify-content-between align-items-center"
                                                                            v-for="recipe in recipeDetails[3]"
                                                                            :key="recipe[0].properties.id"
                                                                        >
                                                                            <div class="ms-2 me-auto">
                                                                                <div class="fw-medium"> {{ recipe[0].properties.name }} </div>
                                                                            </div>
                                                                            <span class="badge text-bg-primary rounded-pill">{{ recipe[1] }}%</span>
                                                                        </li>
                                                                    </ol>
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
                                <td class="col-2"> {{ item.skill }} </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
import leaderboardComponent from './leaderboardComponent.vue';

export default {
    name: 'TableComponent',
    components: {
        leaderboardComponent,
    },
    data() {
        return {
            modalDetailLabel: 'col-4',
            modalDetailContent: 'col-8',
            queryName: null,
            checkedIngredients: [],
            isBusy: 1,
            isLoadingIngredients: 0,
            currentPage: 1,
            ingredients: [],
            items: [],
            numberOfRecipes: 0,
            authorRecipes: [],
            recipeDetails: [],
            direction: 0,
            criterion: 0,
            letters: [
                'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
                'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '@',
            ],
        };
    },
    methods: {
        toggleBusy() {
            this.isBusy = !this.isBusy;
        },
        toggleIngredientsLoading() {
            this.isLoadingIngredients = !this.isLoadingIngredients;
        },
        orderByName() {
            if (this.criterion === 0) {
                this.switchDirection();
            } else {
                this.toggleNameCriterion();
            }

            this.fetchPageData();
        },
        orderByIngredients() {
            if (this.criterion === 1) {
                this.switchDirection();
            } else {
                this.toggleIngredientCriterion();
            }

            this.fetchPageData();
        },
        orderBySkill() {
            if (this.criterion === 2) {
                this.switchDirection();
            } else {
                this.toggleSkillCriterion();
            }

            this.fetchPageData();
        },
        switchDirection() {
            this.direction = this.direction === 0 ? 1 : 0;
        },
        toggleNameCriterion() {
            this.criterion = 0;
        },
        toggleIngredientCriterion() {
            this.criterion = 1;
        },
        toggleSkillCriterion() {
            this.criterion = 2;
        },
        removeIngredient(ingredient) {
            this.checkedIngredients = this.checkedIngredients.filter((item) => item !== ingredient);

            this.fetchPageData();
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
                })
                .catch((error) => {
                    console.log(error);
                });

            this.fetchNumberOfPages();
        },
        processDataArray(array) {
            this.items = array.map((node) => {
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
            this.ingredients = array.reduce((acc, ingredientNode) => {
                const { id } = ingredientNode[0];
                const { name } = ingredientNode[0].properties;
                const firstLetter = name.charAt(0).toLowerCase(); // Convert to lowercase

                const key = this.isNormalLetter(firstLetter) ? firstLetter : '@';

                if (!acc[key]) {
                    acc[key] = [];
                }

                acc[key].push({ id, name });

                return acc;
            }, {});

            Object.keys(this.ingredients).forEach((key) => {
                this.ingredients[key].sort((a, b) => a.name.localeCompare(b.name));
            });
        },
        isNormalLetter(character) {
            return /^[a-zA-Z]+$/.test(character);
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
                    this.processRecipeDetailArray(response.data);
                    console.log(this.recipeDetails);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        processRecipeDetailArray(array) {
            const similarRecipes = [];
            for (let i = 3; i < 8; i += 1) {
                similarRecipes.push(array[i]);
            }

            this.recipeDetails.splice(3, 5);
            this.recipeDetails.push(similarRecipes);
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
