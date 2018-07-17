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
            	        //fill: true,
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

            custom: function(tooltipModel) {
                // Tooltip Element
                var tooltipEl = document.getElementById('chartjs-tooltip');

                // Create element on first render
                if (!tooltipEl) {
                    tooltipEl = document.createElement('div');
                    tooltipEl.id = 'chartjs-tooltip';
                    tooltipEl.innerHTML = "<table></table>";
                    document.body.appendChild(tooltipEl);
                }

                // Hide if no tooltip
                if (tooltipModel.opacity === 0) {
                    tooltipEl.style.opacity = 0;
                    return;
                }

                // Set caret Position
                tooltipEl.classList.remove('above', 'below', 'no-transform');
                if (tooltipModel.yAlign) {
                    tooltipEl.classList.add(tooltipModel.yAlign);
                } else {
                    tooltipEl.classList.add('no-transform');
                }

                function getBody(bodyItem) {
                    return bodyItem.lines;
                }

                // Set Text
                if (tooltipModel.body) {
                    var titleLines = tooltipModel.title || [];
                    var bodyLines = tooltipModel.body.map(getBody);

                    var innerHtml = '<thead>';

                    titleLines.forEach(function(title) {
                        innerHtml += '<tr><th>' + title + '</th></tr>';
                    });
                    innerHtml += '</thead><tbody>';

                    bodyLines.forEach(function(body, i) {
                        var colors = tooltipModel.labelColors[i];
                        var style = 'background:' + colors.backgroundColor;
                        style += '; border-color:' + colors.borderColor;
                        style += '; border-width: 2px';
                        var span = '<span style="' + style + '"></span>';
                        innerHtml += '<tr><td>' + span + body + '</td></tr>';
                    });
                    innerHtml += '</tbody>';

                    var tableRoot = tooltipEl.querySelector('table');
                    tableRoot.innerHTML = innerHtml;
                }

                // `this` will be the overall tooltip
                var position = this._chart.canvas.getBoundingClientRect();

                // Display, position, and set styles for font
                tooltipEl.style.opacity = 1;
                tooltipEl.style.position = 'absolute';
                tooltipEl.style.left = position.left + tooltipModel.caretX + 'px';
                tooltipEl.style.top = position.top + tooltipModel.caretY + 'px';
                tooltipEl.style.fontFamily = tooltipModel._bodyFontFamily;
                tooltipEl.style.fontSize = tooltipModel.bodyFontSize + 'px';
                tooltipEl.style.fontStyle = tooltipModel._bodyFontStyle;
                tooltipEl.style.padding = tooltipModel.yPadding + 'px ' + tooltipModel.xPadding + 'px';
            }
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
