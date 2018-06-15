<template lang="html">
    <div class="">
        <div>
            <h2 class="header-small inline no-select">Your Lift Progress</h2>
            <a class="link-small inline no-select" @click="showGraph=!showGraph">View as {{ viewAs }}</a>
        </div>
        <div v-show="showGraph">
            <div class="select-container narrow-select">
                <select v-model="selectedLiftChartType" class="select">
                    <option v-for="(type, index) in lifttypes" :key="index" :val="type.name">{{ type.name }}</option>
                </select>
            </div>
            <div class="lift-chart-container">
                <chart id="lift-chart" :axes="getLiftAxes"></chart>
            </div>
        </div>
        <div v-show="!showGraph">
            <div class="underline">
                <p class="table-item inline">Weight</p>
                <p class="table-item inline">Reps</p>
                <p class="table-item inline">Type</p>
                <p class="table-item inline">Date</p>
            </div>
            <div class="overflow-y">
                <div v-for="(lift, index) in lifts" :key="index" class="table-row">
                    <p class="table-item inline">{{ lift.weight }}</p>
                    <p class="table-item inline">{{ lift.reps }}</p>
                    <p class="table-item inline">{{ lift.type }}</p>
                    <p class="table-item inline">{{ lift.date }}</p>
                    <button @click="deleteLift(lift)" class="delete-button inline">X</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Graph from '@/components/partials/Chart.vue'
import { mapGetters } from 'vuex'

export default {
    props: ['lifts', 'lifttypes'],
    components: {
        'chart': Graph
    },
    data: function() {
        return {
            showGraph: true,
            selectedLiftChartType: null,
        }
    },
    methods: {
        deleteLift: function(lift) {
            this.$axios.delete(
                'http://localhost:8000/api/lifts/',
                {
                    body: {
                        id: lift.id,
                        key: this.getKey(),
                        user: this.getId()
                    }
                }).then(response => {
                    var index = this.lifts.indexOf(lift);
                    this.lifts.splice(index, 1);
                }
            );
        },
        ...mapGetters([
            'getKey',
            'getId'
        ])
    },
    watch: {
        lifttypes: function() {
            this.selectedLiftChartType = this.lifttypes[0].name
        }
    },
    computed: {
        getLiftAxes: function() {
            var type = this.selectedLiftChartType;
            var xAxis = [];
            var yAxis = [];
            for (var i = 0; i < this.lifts.length; i++) {
                // If the lift is of the selected type
                if(this.lifts[i].type == type) {
                    if (xAxis.length > 0) {
                        // Find the index of the date of the current item
                        var index = xAxis.findIndex(function(element) {
                            return element == this.lifts[i].date;
                        }.bind(this));
                        // If the date already exists, check if the corresponding lift (in the y axis) needs to be updated
                        if (index != -1) {
                            // Only change the lift if the 1RM of the current index is larger
                            if (yAxis[index] < (this.lifts[i].weight * (1 + (this.lifts[i].reps  / 30)))) {
                                yAxis[index] = this.lifts[i].weight * (1 + (this.lifts[i].reps  / 30));
                            }
                        } else {
                            xAxis.push(this.lifts[i].date);
                            yAxis.push(this.lifts[i].weight * (1 + (this.lifts[i].reps  / 30)));
                        }
                    } else {
                        xAxis.push(this.lifts[i].date);
                        yAxis.push(this.lifts[i].weight * (1 + (this.lifts[i].reps  / 30)));
                    }

                }
            }

            // Convert dates to neater format
            for (var i = 0; i < xAxis.length; i++) {
                var element = new Date(xAxis[i]);
                var newDate = (element.getMonth() + 1) + "/" + element.getDate() + "/" + element.getFullYear();
                xAxis[i] = newDate;
            }

            return { xAxis: xAxis, yAxis: yAxis };
        },
        viewAs: function() {
            if (this.showGraph) return "Table";
            return "Graph"
        },
    }
}
</script>

<style lang="css">
</style>
