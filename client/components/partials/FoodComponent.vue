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
            <div class="">
                <progress-bars :totals="totals" :goals="goals" :isEditing="isEditing"></progress-bars>
            </div>
        </div>
        <div class="container-child inline food-stuff">
            <div class="new-food">
                <p class="header-small">Log New Meal</p>
                <a class="link-small inline no-select" @click="showHistory=!showHistory">{{ (showHistory ? 'View Search' : 'View History') }}</a>
                <div v-show="!showHistory">
                    <form class="food-form" action="#" method="post">
                        <input class="food-search" placeholder="Search food database" @input="search" v-model="searchInput">
                    </form>
                    <div class="food-search-results">
                        <p class="search-prompt" v-if="foods.length < 1">Search From USDA Database of Over 8000 Foods</p>
                        <span v-else>
                            <p v-for="(food, index) in foods" :key="index" class="food-search-item" @click="showNutrients(food.ndbno)" :class="{ 'item-odd' : index % 2 == 0}">
                                {{ food.name }}
                            </p>
                        </span>
                    </div>
                </div>
                <div v-show="showHistory" class="food-history">
                    <p v-for="(food, index) in recentFoods" :key="index" class="food-history-item" :class="{ 'item-odd' : index % 2 == 0}">
                        {{ food.name }}
                    </p>
                </div>
            </div>

            <div class="modal" :class="{ 'modal-is-active': modal }">
                <div v-if="modalFood">
                    <p class="food-history-item modal-title"> {{ modalFood.name }}</p>
                    <div v-for="(nutrient, index) in modalFood.nutrients" :key="index">
                        <p class="food-history-item">{{ nutrient.nutrient }}: {{ nutrient.value }}{{ nutrient.unit }}</p>
                    </div>
                    <button class="form-submit" @click="saveFood">Save Food</button>
                    <button class="form-submit" @click="exitModal">Back</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import ProgressBars from '@/components/nutrition/progress'

export default {
    components: {
        ProgressBars
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
            searchInput: '',
            isEditing: false,
            modal: false,
            modalFood: null,
            completedSteps: 1250,
            totalSteps: 2000,
            showHistory: false,
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
            var currentNutrient
            for (var i = 0; i < this.modalFood.nutrients.length; i++) {
                currentNutrient = this.sanitizeNutrient(this.modalFood.nutrients[i])
                if (currentNutrient.nutrient_id == 205) {
                    params.carbohydrate = currentNutrient.value
                    this.totals.carbs += Math.round(currentNutrient.value)
                }
                else if (currentNutrient.nutrient_id == 204) {
                    params.fat = currentNutrient.value
                    this.totals.fat += Math.round(currentNutrient.value)
                }
                else if (currentNutrient.nutrient_id == 203) {
                    params.protein = currentNutrient.value
                    this.totals.protein += Math.round(currentNutrient.value)
                }
                else if (currentNutrient.nutrient_id == 208) {
                    params.calories = currentNutrient.value
                    this.totals.cals += Math.round(currentNutrient.value)
                }
            }

            console.log(params)
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
        sanitizeNutrient(nutrient) {
            if (isNaN(nutrient.value)) {
                nutrient.value = 0
            }
            return nutrient
        },
        ...mapGetters([
            'getKey',
            'getId',
        ])
    },
    computed: {
        display: function() {
            if (!this.isEditing) return "Edit Goals";
            return "Save";
        },
    }
}
</script>

<style lang="css">

</style>
