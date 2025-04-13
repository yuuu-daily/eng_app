<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
const emit = defineEmits(['submitted']);

const handleClick = () => {
    emit('submitted');
}
</script>

<template>
    <!--    <button :type="type" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">-->
    <button :type="type" @click="handleClick"
            class="rounded-md bg-indigo-600 px-3 py-2 text-sm text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-40">
        <slot/>
    </button>
</template>