<template> 
        <div>
        <b-form-checkbox-group
        v-model="skills_checked"
        :options="skills"
        class="mb-3"
        value-field="skill_id"
        text-field="skill_name"
        disabled-field="notEnabled"
        @change="FilterSkills()"
        ></b-form-checkbox-group>
        </div>  
</template>
<script> 
export default {
    name: "App",
    props:['skills'],
    data(){
        return {
            skills_checked:[]
        }
    },
    components: { 
    },
    methods:{
        FilterSkills(){ 
            this.$emit('changeSkills',this.skills_checked);
        }
    }
}
</script>
<style  > 
.custom-control-label {
    margin-left: 10px;
}
</style>