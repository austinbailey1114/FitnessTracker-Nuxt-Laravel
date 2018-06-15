<template lang="html">
    <div class="lifts-container container">
        <div class="lift-graph inline container-child">
            <lift-history :lifts="lifts" :lifttypes="lifttypes"></lift-history>
        </div>
        <div class="new-lift inline container-child">
            <form>
                <div class="lift-field">
                    <p class="lift-prompt">Weight</p>
                    <input v-model="liftFormData.weight" class="numeric-input lift-input" type="text" placeholder="pounds" autocomplete="off">
                </div>
                <div class="lift-field">
                    <p class="lift-prompt">Reps</p>
                    <input v-model="liftFormData.reps" class="numeric-input lift-input" type="text" placeholder="pounds" autocomplete="off">
                </div>
                <div class="lift-field">
                    <p class="lift-prompt">Date</p>
                    <!-- <input v-model="liftFormData.date" class="numeric-input lift-input" type="text" placeholder="pounds" autocomplete="off"> -->
                    <masked-input v-model="liftFormData.date" class="numeric-input lift-input" mask="11/11/1111" placeholder="mm/dd/yyyy"></masked-input>
                </div>
                <div class="lift-field">
                    <p class="lift-prompt inline">Type</p>
                    <div class="select-container">
                        <select v-model="liftFormData.type" class="select" name='liftType'>
                            <option value="select">-- Select Type--</option>
                            <option value="new">New</option>
                            <option v-for="(type, index) in lifttypes" :key="index" :val="type.name">{{ type.name }}</option>
                        </select>
                    </div>
                    <div id="newType" style="display: none">
                        <button id='exitNewLift' type=button>
                            <img src="" height='15' width='15' style='margin-right: 5px;'>
                        </button>
                        <input id="lift-input-type" class="lift-input" type='text' name='newType' placeholder='new type' autocomplete='off'>
                    </div>
                </div>
                <button @click.prevent="postLift()" class="form-submit">Save Lift</button>
            </form>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import LiftField from '@/components/partials/LiftField'
import LiftHistory from '@/components/partials/LiftHistory'
import MaskedInput from 'vue-masked-input'

export default {
    components: {
        'lift-history': LiftHistory,
        'lift-field': LiftField,
        'masked-input': MaskedInput
    },
    data: function() {
        return {
            lifts: [],
            lifttypes:[],
            liftFormData: {
                weight: null,
                reps: null,
                date: null,
                type: null,
                liftType: null,
            }
        }
    },
    created: function() {
        var self = this;
        this.$axios.get(
            'http://localhost:8000/api/lifts/' + self.user.id
        ).then(function(response) {
            self.lifts = response.data;
        });

        this.$axios.get(
            'http://localhost:8000/api/lifttypes/' + self.user.id
        ).then(function(response) {
            self.lifttypes = response.data;
            self.selectedLiftChartType = response.data[0].name;
            self.liftFormData.type = 'select';
        });
    },
    methods: {
        postLift: function(event) {
            var self = this;
            this.$axios.post(
                'http://localhost:8000/api/lift/',
                {
                    user: self.user.id,
                    ...this.liftFormData
                }
            ).then(function(response) {
                var dateString = self.getTonightMidnight();
                var newLift = {
                    weight: self.liftFormData.weight,
                    reps: self.liftFormData.reps,
                    type: self.liftFormData.type,
                    date: dateString
                };
                self.lifts.push(newLift);
            });
        },
        getTonightMidnight: function() {
            var d = new Date();
            d.setHours(0,0,0,0);
            var month = d.getMonth() + 1;;
            var date = d.getDate();;
            if (d.getMonth() < 9) month = '0' + month;
            if (d.getDate() < 10) date = '0' + date;
            return d.getFullYear() + '-' + month + '-' + date + " 00:00:00";
        },
        ...mapGetters([
            'getKey',
            'getId'
        ])
    }
}
</script>

<style lang="css">
</style>
