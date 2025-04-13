<script setup>
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {router} from "@inertiajs/vue3";


const props = defineProps({
    target: null,
});

function goBack() {
    if (props.target) {
        router.visit(props.target);
    } else {
        history.back();
    }
}
</script>

<template>
    <SecondaryButton @click="goBack()">戻る</SecondaryButton>
</template>