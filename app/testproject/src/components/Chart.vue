<script>
import { Pie } from 'vue-chartjs' 

export default {
  extends: Pie,  
  props:['pie_skills'],
  data(){
      return {
        options:{responsive: true, maintainAspectRatio: false},
        chartdata:{
            labels: [
                'до 500',
                '500 - 1000грн',
                '1000 - 5000грн',
                'более 5000грн'
            ],
            datasets: [{
                label: 'Amount',
                data: [this.pie_skills.type_a, this.pie_skills.type_b, this.pie_skills.type_c,this.pie_skills.type_d],
                backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)',
                '#13d176'
                ],
                hoverOffset: 4
            }]
        }
      }
  },
  methods:{ 
      
  }, 
  mounted () {  
   this.renderChart(this.chartdata, this.options)
  }
}
</script>

<style>
</style>