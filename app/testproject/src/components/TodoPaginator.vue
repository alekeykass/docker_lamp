<template> 
    <div class="paginator">
        <b-pagination
            v-model="currentPage"
            :total-rows="count/perPage"
            :per-page="perPage"
            first-number
            last-number
            @input="getProjects(currentPage)"
        ></b-pagination> 
    </div> 
</template>
<script>
export default {
    props:['todos','count'],
    data() {
      return { 
        perPage: 10,
        currentPage: 1
      }
    },
    methods:{
        getProjects(page) {
            this.$emit('changeTodo',page); 
        }
    }
}
</script>
<style scoped>
    .paginator {
        margin-top: 20px;
    }
</style>