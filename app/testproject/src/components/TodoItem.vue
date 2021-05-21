<template>
    <b-list-group-item> 
        <span v-if="todo.budget>0" class="budget">{{todo.budget}} UAH</span>
        <a :href="todo.json_data.links.self.web" target="_blank"><b>{{todo.json_data.attributes.name}}</b></a>
        <div class="user_name">
            <b-avatar :src="todo.json_data.attributes.employer.avatar.small.url"  size="sm"></b-avatar>
            {{todo.json_data.attributes.employer.first_name}} {{todo.json_data.attributes.employer.last_name}} ({{todo.json_data.attributes.employer.login}})
        </div>
        <hr/>
        <div class="item_content" v-html="todo.json_data.attributes.description_html"></div>  
    </b-list-group-item>
</template>
<script>
export default {
    props:{
        todo: {
            type: Object,
            required: true
        }
    }
}
</script>
<style scoped>
    .item_content {
        overflow: hidden;
    }
    .budget {
        float: right;
        font-weight: bold;
    }
    .user_name {
        font-size: 12px;
    } 
</style>