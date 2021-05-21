<template> 
    <line-chart :chart-data="datacollection"></line-chart>  
</template>

<script>
  import LineChart from './Chart.js'

  export default {
    components: {
      LineChart
    },
    props:['pie_skills'],
    data () {
      return {
        datacollection: null
      }
    },
    mounted () {
      setTimeout(()=>{ this.fillData()},1000);
    },
    methods: {
      fillData () {
        this.datacollection = {
          labels: [
                'до 500',
                '500 - 1000грн',
                '1000 - 5000грн',
                'более 5000грн'
            ],
          datasets: [
            {
              label: 'Data One',
              backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)',
                '#13d176'
                ],
              data: [this.pie_skills.type_a, this.pie_skills.type_b, this.pie_skills.type_c,this.pie_skills.type_d]
            }
          ]
        }
      }
    }
  }
</script>

<style>
  .small {
    max-width: 600px;
    margin:  150px auto;
  }
</style>