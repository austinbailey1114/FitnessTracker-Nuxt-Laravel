<template lang="html">
    <div class="lifts-container container">
        <div class="lift-graph inline container-child">
            <lift-history :lifts="lifts" :lifttypes="lifttypes"></lift-history>
        </div>
        <div class="new-lift inline container-child">
            <p class="header-small">Log New Lift</p>
            <form>
                <div class="lift-field">
                    <p class="lift-prompt">Weight</p>
                    <input v-model="liftFormData.weight" class="numeric-input lift-input" type="text" placeholder="pounds" autocomplete="off">
                    <p class="help is-danger" v-if="errors.weight">
                        {{ errors.weight[0] }}
                    </p>
                </div>
                <div class="lift-field">
                    <p class="lift-prompt">Repetitions</p>
                    <input v-model="liftFormData.reps" class="numeric-input lift-input" type="text" placeholder="pounds" autocomplete="off">
                    <p class="help is-danger" v-if="errors.reps">
                        {{ errors.reps[0] }}
                    </p>
                </div>
                <div class="lift-field">
                    <p class="lift-prompt">Date</p>
                    <input v-model="liftFormData.date" class="numeric-input lift-input" v-mask="'##/##/####'" placeholder="mm/dd/yyyy - blank for today">
                </div>
                <div class="lift-field">
                    <p class="lift-prompt inline">Type</p>
                    <div v-if="!newLiftType" class="select-container">
                        <select v-model="liftFormData.type" class="select" @change="selectChanged">
                            <option :value="null">-- Select Type--</option>
                            <option value="new">New</option>

                            <option v-if="lifttypes.length > 0" v-for="(type, index) in lifttypes" :key="index" :val="type.name">{{ type.name }}</option>
                        </select>
                    </div>
                    <div v-else>
                        <div class="X" @click.prevent="clearNewType">
                            X
                        </div>
                        <input class="lift-input" placeholder='New Lift Type' autocomplete='off' v-model="liftFormData.newType">
                    </div>
                </div>
                <button @click.prevent="postLift()" class="form-submit">Save Lift</button>
            </form>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import LiftField from '@/components/lifts/LiftField'
import LiftHistory from '@/components/lifts/LiftHistory'
import moment from 'moment'
import momentTimezone from 'moment-timezone'

export default {
    components: {
        'lift-history': LiftHistory,
        'lift-field': LiftField,
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
                newType: null,
            },
            newLiftType: false,
        }
    },
    created: function() {
        var self = this;
        this.$axios.get(
            'http://localhost:8000/api/lifts/' + self.user.id
        ).then(function(response) {
            self.lifts = response.data.data;
        });

        this.$axios.get(
            'http://localhost:8000/api/lifttypes/' + self.user.id
        ).then(function(response) {
            self.lifttypes = response.data;
            self.selectedLiftChartType = response.data[0].name;
            self.liftFormData.type = null;
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
                var dateString
                if (self.liftFormData.date) {
                    dateString = moment(self.liftFormData.date).tz('America/New_York').utc().format("YYYY-MM-DD 00:00:00")
                } else {
                    dateString = moment().tz('America/New_York').utc().format("YYYY-MM-DD 00:00:00")
                }
                var newLift = {
                    weight: self.liftFormData.weight,
                    reps: self.liftFormData.reps,
                    type: (self.liftFormData.newType ? self.liftFormData.newType : self.liftFormData.type),
                    date: dateString
                }
                var inserted = false
                for (var i = 0; i < self.lifts.length; i++) {
                    if (moment(self.lifts[i].date).diff(moment(newLift.date)) > 0) {
                        self.lifts.splice(i, 0, newLift)
                        inserted = true
                        break
                    }
                }

                if (!inserted) {
                    self.lifts.push(newLift)
                }

                if (self.liftFormData.newType) {
                    self.lifttypes.push({name: self.liftFormData.newType})
                    self.clearNewType()
                }
            });
        },
        selectChanged() {
            if (this.liftFormData.type == "new") {
                this.setNewType()
            }
        },
        clearNewType() {
            this.newLiftType = false
            this.liftFormData.type = null
            this.liftFormData.newType = null
        },
        setNewType() {
            this.newLiftType = true
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
