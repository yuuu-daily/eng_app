<script setup>
import {ref} from 'vue'
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import {router, useForm} from "@inertiajs/vue3";

const props = defineProps({
    targetRoute: String,
    action: String,
});

const target = ref('');
const beingDeleted = ref(null);
const deleteForm = useForm({});

const del = (id, name) => {
    beingDeleted.value = id;
    target.value = name;
};

const doDel = (id) => {
    deleteForm.delete(route(props.targetRoute, id), {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            toaster.success('削除しました');
            beingDeleted.value = null;
        },
        onError: () => {
            toaster.error('削除に失敗しました')
        },
    });
};
defineExpose({del});
</script>

<template>
    <ConfirmationModal :show="beingDeleted != null" @close="beingDeleted = null">
        <template #title>確認</template>
        <template #content>
            <span class="font-semibold text-xl">{{ target }}</span> を本当に削除してよろしいですか？
        </template>
        <template #footer>
            <SecondaryButton @click="beingDeleted = null">キャンセル</SecondaryButton>
            <DangerButton
                class="ms-3"
                :class="{ 'opacity-25': deleteForm.processing }"
                :disabled="deleteForm.processing"
                @click="doDel(beingDeleted)">
                削除
            </DangerButton>
        </template>
    </ConfirmationModal>
</template>