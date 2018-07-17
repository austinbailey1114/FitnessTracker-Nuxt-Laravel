<template lang="html">
    <div class="container">
        <div class="food-total container-child inline">
            <div class="">
                <a class="link-small inline no-select" @click="editClicked()">{{ display }}</a>
                <p class="header-small">Today's Nutrition</p>
            </div>
            <div class="calories-total">
                <p class="food-total-prompt">Calories</p>
                <radial-progress-bar :diameter="220"
                                     :completed-steps="(totals.cals > 0 ? totals.cals : 1)"
                                     :total-steps="goals.cals"
                                     :startColor="'#F3C74F'"
                                     :stopColor="'#F3C74F'">
                <p v-if="!isEditing">{{ totals.cals }}/{{ goals.cals }}</p>
                <input v-else class="food-goal-input inline" v-model="goals.cals">
                </radial-progress-bar>
            </div>
            <div class="progress-bars">
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
        </div>
        <div class="container-child inline food-stuff">
            <div class="new-food">
                <form class="food-form" action="#" method="post">
                    <input class="food-search" placeholder="Search food database" @input="search" v-model="searchInput">
                </form>
            </div>
            <div class="food-modal" v-if="foods.length > 0">
                <p v-for="(food, index) in foods" :key="index" class="food-search-item" @click="showNutrients(food.ndbno)" :class="{ 'item-odd' : index % 2 == 0}">
                    {{ food.name }}
                </p>
            </div>
            <div class="food-history">
                <p v-if="recentFoods.length < 1" class="food-history-none">No recent foods to show</p>
                <span v-else>
                    <p v-for="(food, index) in recentFoods" :key="index" class="food-history-item" :class="{ 'item-odd' : index % 2 == 0}">
                        {{ food.name }}
                    </p>
                </span>
            </div>
            <div class="modal" :class="{ 'modal-is-active': modal }">
                <div v-if="modalFood">
                    <p class="food-history-item"> {{ modalFood.name }}</p>
                    <div v-for="(nutrient, index) in modalFood.nutrients" :key="index">
                        <p class="food-history-item">{{ nutrient.nutrient }}: {{ nutrient.value }}{{ nutrient.unit }}</p>
                    </div>
                    <button class="form-submit" @click="saveFood">Save</button>
                    <button class="form-submit" @click="exitModal">Back</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import $ from 'jquery'
import { mapGetters } from 'vuex'
import RadialProgressBar from 'vue-radial-progress'

export default {
    components: {
        RadialProgressBar
    },
    data: function() {
        return {
            foods: [],
            recentFoods: [],
            totals: {
                'cals': 0,
                'fat': 0,
                'carbs': 0,
                'protein': 0
            },
            goals: {
                'cals': null,
                'fat': null,
                'carbs': null,
                'protein': null,
            },
            barStyle: {
                height: '30px',
                zIndex: '3',
                position: 'absolute',
                borderRadius: '7px',
                minWidth: 'max-content',
            },
            searchInput: '',
            isEditing: false,
            modal: false,
            modalFood: null,
            completedSteps: 1250,
            totalSteps: 2000
        }
    },
    created() {
        var self = this
        this.$axios.get(
            'http://localhost:8000/api/foodGoals/' + self.user.id
        ).then(function(response) {
            var data = response.data
            self.goals.cals = data.calories
            self.goals.fat = data.fat
            self.goals.carbs = data.carbohydrate
            self.goals.protein = data.protein
        })

        this.$axios.get(
            process.env.apiURL + '/api/recentFoods/' + self.user.id
        ).then(function(response) {
            console.log(response)
            self.recentFoods = response.data
        })

        this.$axios.get(
            process.env.apiURL + '/api/totals/' + self.user.id
        ).then(function(response) {
            console.log(response)
            self.totals.cals = response.data.calories
            self.totals.carbs = response.data.carbohydrate
            self.totals.fat = response.data.fat
            self.totals.protein = response.data.protein
        })
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
                'https://api.nal.usda.gov/ndb/nutrients/?nutrients=204&nutrients=205&nutrients=208&nutrients=203',
                {
                    params: {
                        api_key: 'vTyovxE8ZlRheES3nZoKa3v0CM2Vga1KGOylnDiG',
                        ndbno: id,
                    }
                }
            ).then(function(response) {
                console.log(response)
                self.modalFood = response.data.report.foods[0]
                self.modal = true
            })
        },
        exitModal() {
            this.modalFood = null
            this.modal = false
            this.foods = []
            this.searchInput = ''
        },
        saveFood() {
            var self = this
            var params = {
                user: this.user.id,
                name: this.modalFood.name,
            }
            for (var i = 0; i < this.modalFood.nutrients.length; i++) {
                if (this.modalFood.nutrients[i].nutrient_id == 205) {
                    params.carbohydrate = this.modalFood.nutrients[i].value
                    this.totals.carbs += Math.round(this.modalFood.nutrients[i].value)
                }
                else if (this.modalFood.nutrients[i].nutrient_id == 204) {
                    params.fat = this.modalFood.nutrients[i].value
                    this.totals.fat += Math.round(this.modalFood.nutrients[i].value)
                }
                else if (this.modalFood.nutrients[i].nutrient_id == 203) {
                    params.protein = this.modalFood.nutrients[i].value
                    this.totals.protein += Math.round(this.modalFood.nutrients[i].value)
                }
                else if (this.modalFood.nutrients[i].nutrient_id == 208) {
                    params.calories = this.modalFood.nutrients[i].value
                    this.totals.cals += Math.round(this.modalFood.nutrients[i].value)
                }
            }
            this.$axios.post(
                process.env.apiURL + '/api/food',
                params
            ).then(function(response) {
                self.recentFoods.unshift({
                    name: self.modalFood.name
                })

                self.exitModal()
            })
        },
        ...mapGetters([
            'getKey',
            'getId',
        ])
    },
    computed: {
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
                width: (barWidth < 28 ? '28px' : barWidth + 'px'),
                backgroundColor: '#508CFC',
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
                width: (barWidth < 28 ? '28px' : barWidth + 'px'),
                backgroundColor: '#508CFC',
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
                width: (barWidth < 28 ? '28px' : barWidth + 'px'),
                backgroundColor: '#508CFC',
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

.food-total-value {
    line-height: 30px;
    margin-left: 10px;
    font-weight: 200;
    font-size: 14px;
}

.food-total-bar {
    height: 30px;
    width: 85%;
    background-color: #EEEEF2;
    border-radius: 7px;
    float: left;
    box-shadow: 0 0 0 1px rgba(50,50,93,.1),
                0 2px 5px 0 rgba(50,50,93,.08),
                0 1px 1.5px 0 rgba(0,0,0,.07),
                0 1px 2px 0 rgba(0,0,0,.08);
                margin-right: 8px;
}

.food-total-progress {
    background-color: red;
    height: 30px;
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
	font-size: 14px;
    line-height: 30px;
    left: 5px;
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
	padding: 2px 2px;
    text-align: center;
    width: 50px;
    color: white;
	font-size: 14px;
    background-color: #3B3C4E;
    border: none;
    border-bottom: 1px solid #DCDCDC;
}

.food-total-field {
    margin-top: 15px;
}

.item-odd {
    background-color: #424355;
}
</style>
