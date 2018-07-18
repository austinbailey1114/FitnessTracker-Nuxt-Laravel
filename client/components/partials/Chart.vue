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
            	        borderColor: 'rgb(249,87,102)',
            	        backgroundColor: 'rgba(249,87,102,0.15)',
            	        pointBackgroundColor: 'rgb(249,87,102)',
            	        data: this.axes.yAxis,
            	    }]
            	},
            	options: {
                    defaultFontColor: 'white',
            	    responsive: true,
            	    maintainAspectRatio: false,
            	    legend: {
            	        display: false,
            	     },
                     tooltips: {
                         // Disable the on-canvas tooltip
                        enabled: false,
                     }
            	}
            });
            Chart.defaults.global.defaultFontColor='white';
            Chart.defaults.global.elements.point.radius = 5;
            Chart.defaults.global.elements.point.hitRadius = 20;
        }
    }
}
</script>

<style lang="css">
</style>
