<script>
import recipesTable from '../components/recipesTable.vue';

export default {
    name: 'AppPage',
    components: {
        recipesTable,
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
        <recipesTable/>
    </div>
</template>

<style scoped>

</style>
