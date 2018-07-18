<template lang="html">
    <div class="lift-field">
        <p class="lift-prompt">{{ this.prompt }}</p>
        <input v-model="this.model" class="numeric-input lift-input" type="text" placeholder="pounds" autocomplete="off">
    </div>
</template>

<script>
export default {
    props: ['name', 'prompt', 'model'],
    created: function() {
        console.log(this.model);
    }
}
</script>

<style lang="css">
</style>
