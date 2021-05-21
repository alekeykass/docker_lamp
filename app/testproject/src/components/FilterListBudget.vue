<template> 
        <div>
        <b-form-group label="Individual radios" v-slot="{ ariaDescribedby }">
            <b-form-radio v-model="budget_selected" :aria-describedby="ariaDescribedby" name="some-radios500" value="A" @change="FilterBudget()">До 500грн</b-form-radio>
            <b-form-radio v-model="budget_selected" :aria-describedby="ariaDescribedby" name="some-radios500-1000" value="B" @change="FilterBudget()">500 - 1000грн</b-form-radio>
            <b-form-radio v-model="budget_selected" :aria-describedby="ariaDescribedby" name="some-radios1000-5000" value="C" @change="FilterBudget()">1000 - 5000грн</b-form-radio>
            <b-form-radio v-model="budget_selected" :aria-describedby="ariaDescribedby" name="some-radios5000" value="D" @change="FilterBudget()">более 5000грн</b-form-radio>
        </b-form-group>
        </div>  
</template>
<script> 
export default {
    name: "App",
    props:['skills'],
    data(){
        return {
            skills_checked:[],
            budget_selected:[]
        }
    },
    components: { 
    },
    methods:{
        FilterBudget(){ 
            this.$emit('changeBudget',this.budget_selected);
        }
    }
}
</script>
<style scoped> 
</style>