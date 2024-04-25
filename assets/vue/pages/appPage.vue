<script>
import recipesTable from '../components/recipesTable.vue';
import leaderboard from '../components/leaderboardComponent.vue';

export default {
    name: 'AppPage',
    components: {
        recipesTable,
        leaderboard,
    },
    data() {
        return {
            recipes: [],
        };
    },
    methods: {

    },
    mounted() {
    },
};
</script>

<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-9">
                <recipesTable/>
            </div>
            <div class="col-3">
                <leaderboard/>
            </div>
        </div>

    </div>
</template>

<style scoped>

</style>
