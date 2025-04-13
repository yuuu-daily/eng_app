<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
const emit = defineEmits(['submitted']);

const handleClick = () => {
    emit('submitted');
}
</script>

<template>
    <button :type="type"  @click="handleClick"
            class="rounded-md bg-white px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
        <slot/>
    </button>
</template>
