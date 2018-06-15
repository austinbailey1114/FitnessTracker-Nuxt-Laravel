<template lang="html">
    <canvas :id="this.id"></canvas>
</template>

<script>
import Chart from 'chart.js'
export default {
    props: ['id', 'axes'],
    watch: {
        axes: function() {
            // Watch for changes to axes (a computed property on the Home component)
            var ctx = document.getElementById(this.id).getContext('2d');
            var chart = new Chart(ctx, {
            	type: 'line',
            	data: {
            	    labels: this.axes.xAxis,
            	    datasets: [{
            	        borderColor: 'rgb(231,76,60)',
            	        backgroundColor: 'rgba(231,76,60,0.3)',
            	        fill: true,
            	        pointBackgroundColor: 'rgb(231,76,60)',
            	        data: this.axes.yAxis,
            	    }]
            	},
            	options: {
            	    responsive: true,
            	    maintainAspectRatio: false,
            	    legend: {
            	        display: false
            	     },
            	}
            });
        }
    }
}
</script>

<style lang="css">
</style>
