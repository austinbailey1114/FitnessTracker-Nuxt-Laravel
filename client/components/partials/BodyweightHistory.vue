<template lang="html">
    <div>
        <div>
            <p class="header-small inline no-select">Bodyweight</p>
            <a class="link-small inline no-select" @click="showGraph=!showGraph">View as {{ viewAs }}</a>
        </div>
        <div v-show="showGraph" class="bodyweight-chart-container">
            <chart id="bodyweight-chart" :axes="getBodyweightAxes"></chart>
        </div>
        <div v-show="!showGraph">
            <div class="underline">
                <p class="table-item inline">Weight</p>
                <p class="table-item inline">Date</p>
            </div>
            <div class="overflow-y">
                <div v-for="(bodyweight, index) in bodyweights" :key="index" class="table-row">
                    <p class="table-item inline">{{ bodyweight.weight }}</p>
                    <p class="table-item inline">{{ bodyweight.date }}</p>
                    <button @click="deleteBodyweight(bodyweight)" class="delete-button inline">X</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Graph from '@/components/partials/Chart.vue'
import { mapGetters } from 'vuex'

export default {
    props: ['bodyweights'],
    components: {
        'chart': Graph,
    },
    data: function() {
        return {
            showGraph: true,
        }
    },
    methods: {
        deleteBodyweight: function(bodyweight) {
            var self = this;
            this.$axios.post(
                'http://localhost:8000/api/deleteBodyweight/',
                {
                    id: bodyweight.id,
                }
            ).then(function(response) {
                var index = self.bodyweights.indexOf(bodyweight);
                self.bodyweights.splice(index, 1);
            });
        },
        ...mapGetters([
            'getKey',
            'getId'
        ])
    },
    computed: {
        getBodyweightAxes: function() {
            var xAxis = [];
            var yAxis = [];
            for (var i = 0; i < this.bodyweights.length; i++) {
                xAxis.push(this.bodyweights[i].date);
                yAxis.push(this.bodyweights[i].weight);
            }

            // Convert dates to neater format
            for (var i = 0; i < xAxis.length; i++) {
                var element = new Date(xAxis[i]);
                var newDate = (element.getMonth() + 1) + "/" + element.getDate() + "/" + element.getFullYear();
                xAxis[i] = newDate;
            }
            return { xAxis: xAxis, yAxis, yAxis }
        },
        viewAs: function() {
            if (this.showGraph) return "Table";
            return "Graph";
        },
    }
}
</script>

<style lang="css">
</style>
