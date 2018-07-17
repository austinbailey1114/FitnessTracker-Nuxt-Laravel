<template lang="html">
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
</template>

<script>
export default {
    props: ['goals', 'totals', 'isEditing'],
    data() {
        return {
            barStyle: {
                height: '30px',
                zIndex: '3',
                position: 'absolute',
                borderRadius: '7px',
                minWidth: 'max-content',
            },
        }
    },
    methods: {
        getWidth(id) {
            if (document.getElementById(id)) {
                return document.getElementById(id).offsetWidth
            } else {
                return 0
            }
        }
    },
    computed: {
        fatWidth: function() {
            var baseWidth = this.getWidth('fat')
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
            var baseWidth = this.getWidth('fat')
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
            var baseWidth = this.getWidth('fat')
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
    }
}
</script>

<style lang="css">
</style>
