<script>
import Test from '../components/testComponent.vue';
import Pagination from '../components/paginationComponent.vue';

export default {
    name: 'TestPage',
    components: {
        Test,
        Pagination,
    },
};
</script>

<template>
    <div>
        <Pagination/>
        <Test></Test>
    </div>
</template>

<style scoped>

</style>
