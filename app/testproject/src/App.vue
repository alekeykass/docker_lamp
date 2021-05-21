<template>
  <div id="app"> 
    <b-container class="bv-example-row"> 
      <b-row>
        <b-col cols="8">
          
          <h4>Projects</h4>

          <TodoPaginator  v-bind:todos="todos" v-bind:count="count" @changeTodo="changeTodo" />
          <TodoList v-bind:todos="todos" v-bind:count="count"  />
          <TodoPaginator  v-bind:todos="todos" v-bind:count="count" @changeTodo="changeTodo" /> 
 
        </b-col> 
        <b-col cols="4">
          <h4>Filter</h4> 
          <Chart v-bind:pie_skills="pie_skills"   />
          <FilterListBudget  @changeBudget="changeBudget" />
          <hr/>
          <FilterList v-bind:skills="skills" @changeSkills="changeSkills" />

        </b-col>
      </b-row>
    </b-container> 
  </div>
</template>

<script>
import TodoList from '@/components/TodoList'
import FilterList from '@/components/FilterList'
import FilterListBudget from '@/components/FilterListBudget'
import TodoPaginator from '@/components/TodoPaginator'
import Chart from '@/components/Chart'

export default {
  name: 'App',
  data(){
    return {
      todos:[],
      count:0,
      pie_skills:[],
      skills:[],
      props_filter:{},
      page:1
    }
  },
  components: {
    TodoList,
    FilterList,
    FilterListBudget,
    TodoPaginator,
    Chart
  },
  methods:{
    changeTodo(data){
      this.page = data;
      this.get_products()
    },
    changeSkills(data){ 
      this.props_filter['skills']=data;
      this.get_products()
    },
    changeBudget(data){
      var budget = {min:0};
      
      if(data=='A') budget={min:0,max:500}
      if(data=='B') budget={min:500,max:1000}
      if(data=='C') budget={min:1000,max:5000}
      if(data=='D') budget={min:5000}

      this.props_filter['budget']=budget;
      this.get_products()
    },
    get_products(){ 

      fetch("http://localhost:8082/get_projects.php?api=get_list&limit=10&page="+this.page,
        {
          method: "POST",
          body: JSON.stringify(this.props_filter)
        }
      )
      .then(response => response.json())
      .then(json => {
        this.todos = json.data;
        this.count = json.count;
        this.pie_skills = json.pie_skills;  
      });
    }
  },
  mounted () {
    this.get_products();

    fetch("http://localhost:8082/get_projects.php?api=get_skills")
    .then(response => response.json())
    .then(json => {
      this.skills = json.data; 
    }) 
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale; 
  color: #2c3e50;
  margin-top: 60px;
}
</style>
