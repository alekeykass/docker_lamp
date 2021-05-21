<template>
    <b-list-group>
        <div v-if="todos.length==0">Не найдено</div>
        <TodoItem v-for="todo of todos" v-bind:todo="todo" /> 
    </b-list-group> 
</template>
<script>
import TodoItem from '@/components/TodoItem'

export default {
  props:['todos'],
  components: {
    TodoItem
  }
}
</script>