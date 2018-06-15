<template lang="html">
    <div class="container">
        <div class="bodyweight-graph container-child inline">
            <bodyweight-history :bodyweights="bodyweights"></bodyweight-history>
        </div>
        <div class="new-weight container-child inline">
            <form class="bodyweight-form" method="post">
                <div class="bodyweight-field">
                    <p class="bodyweight-field-prompt">Weight</p>
                    <input v-model="newWeight" class="numeric-input bodyweight-input" type="text" name="weight" placeholder="pounds">
                </div>
                <button @click.prevent="postBodyweight()" class="form-submit form-submit-bodyweight">Update</button>
            </form>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import BodyweightHistory from '@/components/partials/BodyweightHistory'

export default {
    components: {
        'bodyweight-history': BodyweightHistory
    },
    data: function() {
        return {
            bodyweights: [],
            newWeight: null,
        }
    },
    created: function() {
        var self = this;
        this.$axios.get(
            'http://localhost:8080/api/bodyweights/' + self.user.id
        ).then(response => {
            var data = JSON.parse(response.bodyText);
            self.bodyweights = data;
        });
    },
    methods: {
        postBodyweight: function() {
            this.$axios.post(
                'http://localhost:8080/api/bodyweights/',
                {
                    weight: this.newWeight,
                    key: this.getKey(),
                    user: this.getId()
                }
            ).then(response => {
                var dateString = this.getTonightMidnight();
                var newBodyweight = {
                    date: dateString,
                    weight: this.newWeight,
                };
                this.bodyweights.push(newBodyweight);
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
