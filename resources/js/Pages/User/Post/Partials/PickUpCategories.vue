<script setup>
import {ref, computed, watch} from 'vue';
import vSelect from 'vue-select';

const props = defineProps({
    categories: Array,
    categoryIds: Array
});

const emit = defineEmits(['update']);

let selectedCategoryIds = ref(props.categoryIds);
let init = true;

watch(selectedCategoryIds, newVal => {
    emit('update', selectedCategoryIds.value);
});

watch(props.categoryIds, newVal => {
    selectedCategoryIds.value = newVal;
});

let allOptions = ref(props.categories.map(l => ({id: l.id, name: l.name})));

const availableOptions = computed(() => {
    // return allOptions.value.filter(option => !selectedCategoryIds.value.find((selectedOption) => selectedOption.id === option.id));
    return allOptions.value;
});
</script>

<template>
    <div class="mt-2">
        <v-select
            multiple
            label="name"
            :options="availableOptions"
            v-model="selectedCategoryIds"
            :close-on-select="false"
        />
    </div>
</template>
