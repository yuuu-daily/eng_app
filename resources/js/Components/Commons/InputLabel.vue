<script setup>
defineProps({
    value: String,
});
</script>

<template>
    <label class="block font-semibold mb-1 text-medium text-gray-700">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
        <span v-if="$slots.opt">
            <slot name="opt" />
        </span>
    </label>
</template>
