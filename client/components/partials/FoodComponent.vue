<template lang="html">
    <div class="container">
        <div class="food-total container-child inline">
            <div class="food-total-field">
                <a class="link-small inline no-select" @click="editClicked()">{{ display }}</a>
                <p class="food-total-prompt">Calories</p>
                <div id="cals" class="food-total-bar inline">
                    <div class="food-total-progess inline" :style="calsWidth">
                        <p class="food-total-value">{{ totals.cals }}</p>
                    </div>
                </div>
                <p v-if="!isEditing" class="food-total-goal inline">{{ goals.cals }}</p>
                <input v-else class="food-goal-input inline" v-model="goals.cals">
            </div>
            <div class="food-total-field">
                <p class="food-total-prompt">Fat</p>
                <div id="fat" class="food-total-bar">
                    <div class="food-total-progess inline" :style="fatWidth">
                        <p class="food-total-value">{{ totals.fat }}</p>
                    </div>
                </div>
                <p v-if="!isEditing" class="food-total-goal inline">{{ goals.fat }}</p>
                <input v-else class="food-goal-input inline" v-model="goals.fat">
            </div>
            <div class="food-total-field">
                <p class="food-total-prompt">Carbs</p>
                <div id="carbs" class="food-total-bar">
                    <div class="food-total-progess inline" :style="carbsWidth">
                        <p class="food-total-value">{{ totals.carbs }}</p>
                    </div>
                </div>
                <p v-if="!isEditing" class="food-total-goal inline">{{ goals.carbs }}</p>
                <input v-else class="food-goal-input inline" v-model="goals.carbs">
            </div>
            <div class="food-total-field">
                <p class="food-total-prompt">Protein</p>
                <div id="protein" class="food-total-bar">
                    <div class="food-total-progess inline" :style="proteinWidth">
                        <p class="food-total-value">{{ totals.protein }}</p>
                    </div>
                </div>
                <p v-if="!isEditing" class="food-total-goal inline">{{ goals.protein }}</p>
                <input v-else class="food-goal-input inline" v-model="goals.protein">
            </div>
        </div>
        <div class="container-child inline food-stuff">
            <div class="new-food">
                <form class="food-form" action="#" method="post">
                    <input class="food-search" placeholder="Search food database" @input="search" v-model="searchInput">
                </form>
            </div>
            <div class="food-modal" v-if="foods.length > 0">
                <p v-for="(food, index) in foods" :key="index" class="food-search-item" @click="showNutrients(food.ndbno)">
                    {{ food.name }}
                </p>
            </div>
            <div class="food-history">
                <p v-if="foods.length < 1" class="food-history-none">No recent foods to show</p>
                <span v-else>
                    <p v-for="(food, index) in recentFoods" :key="index" class="food-history-item">
                        {{ food }}
                    </p>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
import $ from 'jquery'
import { mapGetters } from 'vuex'

export default {
    data: function() {
        return {
            foods: [],
            recentFoods: [],
            totals: {
                'cals': 200,
                'fat': 41,
                'carbs': 30,
                'protein': 12
            },
            goals: {
                'cals': null,
                'fat': null,
                'carbs': null,
                'protein': null,
            },
            barStyle: {
                height: '25px',
                zIndex: '3',
                position: 'absolute',
                borderRadius: '7px',
                minWidth: 'max-content',
            },
            searchInput: '',
            isEditing: false,
        }
    },
    mounted: function() {
        var self = this;
        this.$axios.get(
            'http://localhost:8000/api/foodGoals/' + self.user.id
        ).then(function(response) {
            var data = response.data
            self.goals.cals = data.calories;
            self.goals.fat = data.fat;
            self.goals.carbs = data.carbohydrate;
            self.goals.protein = data.protein;
        });
    },
    methods: {
        editClicked: function() {
            console.log(this.goals)
            if (this.isEditing) {
                var data = {
                    user: this.user.id,
                    ...this.goals
                };

                this.$axios.post(
                    'http://localhost:8000/api/foodGoals/',
                    data
                ).then(function(response) {
                    console.log(response);
                });

            }
            this.isEditing = !this.isEditing;
        },
        search() {
            //console.log(this.searchInput)
            if (this.searchInput == '') {
                this.foods = []
                return
            }
            var self = this
            this.$axios.get(
                'https://api.nal.usda.gov/ndb/search/',
                {
                    params: {
                        api_key: 'vTyovxE8ZlRheES3nZoKa3v0CM2Vga1KGOylnDiG',
                        q: self.searchInput,
                        max: 10
                    }
                }
            ).then(function(response) {
                self.foods = response.data.list.item
                console.log(self.foods)
            })
        },
        showNutrients(id) {
            console.log(id)
            var self = this
            this.$axios.get(
                'https://api.nal.usda.gov/ndb/nutrients/?nutrients=204&nutrients=205&nutrients=208&nutrients=269',
                {
                    params: {
                        api_key: 'vTyovxE8ZlRheES3nZoKa3v0CM2Vga1KGOylnDiG',
                        ndbno: id,
                    }
                }
            ).then(function(response) {
                console.log(response)
            })
        },
        ...mapGetters([
            'getKey',
            'getId',
        ])
    },
    computed: {
        calsWidth: function() {
            var baseWidth = $('#cals').width();
            var barWidth = (this.totals.cals / this.goals.cals) * baseWidth;
            if (barWidth > baseWidth) {
                return {
                    width: baseWidth + 'px',
                    backgroundColor: '#6F9F52',
                    ...this.barStyle
                };
            }
            return {
                width: (this.totals.cals / this.goals.cals) * baseWidth + 'px',
                minWidth: 'max-content',
                backgroundColor: '#E74C3C',
                ...this.barStyle
            };
        },
        fatWidth: function() {
            var baseWidth = $('#fat').width();
            var barWidth = (this.totals.fat / this.goals.fat) * baseWidth;
            if (barWidth > baseWidth) {
                return {
                    width: baseWidth + 'px',
                    backgroundColor: '#6F9F52',
                    ...this.barStyle
                };
            }
            return {
                width: barWidth + 'px',
                backgroundColor: '#E74C3C',
                ...this.barStyle
            }
        },
        carbsWidth: function() {
            var baseWidth = $('#carbs').width();
            var barWidth = (this.totals.carbs / this.goals.carbs) * baseWidth;
            if (barWidth > baseWidth) {
                return {
                    width: baseWidth + 'px',
                    backgroundColor: '#6F9F52',
                    ...this.barStyle
                };
            }
            return {
                width: barWidth + 'px',
                backgroundColor: '#E74C3C',
                ...this.barStyle
            }
        },
        proteinWidth: function() {
            var baseWidth = $('#protein').width();
            var barWidth = (this.totals.protein / this.goals.protein) * baseWidth;
            if (barWidth > baseWidth) {
                return {
                    width: baseWidth + 'px',
                    backgroundColor: '#6F9F52',
                    ...this.barStyle
                };
            }
            return {
                width: barWidth + 'px',
                backgroundColor: '#E74C3C',
                ...this.barStyle
            }
        },
        display: function() {
            if (!this.isEditing) return "Edit";
            return "Save";
        },
    }
}
</script>

<style lang="css">
.food-total-bar {
    height: 25px;
    width: 80%;
    background-color: #EEEEF2;
    border-radius: 7px;
    float: left;
    box-shadow: 0 0 0 1px rgba(50,50,93,.1),
                0 2px 5px 0 rgba(50,50,93,.08),
                0 1px 1.5px 0 rgba(0,0,0,.07),
                0 1px 2px 0 rgba(0,0,0,.08);
}

.food-total-progress {
    background-color: red;
    height: 25px;
    float: left;
    position: absolute;
    z-index: 3;
    box-shadow: 0 0 0 1px rgba(50,50,93,.1),
                0 2px 5px 0 rgba(50,50,93,.08),
                0 1px 1.5px 0 rgba(0,0,0,.07),
                0 1px 2px 0 rgba(0,0,0,.08);
}

.food-total-goal {
    font-weight: 200;
	padding: 7px 20px;
	font-size: 14px;
}

.food-history-none {
    font-weight: 200;
    font-size: 22px;
    padding: 10px;
    text-align: center;
}

.food-goal-input {
    width: 30px;
    font-weight: 200;
	padding: 5.5px 10px;
    width: 50px;
	font-size: 14px;
    border: 1px solid #DCDCDC;
    border-radius: 5px;
    margin-left: 5px;
}
</style>
