<script setup>
import AppLayout from "@/Layouts/User/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import {useForm, router} from "@inertiajs/vue3";
import {computed, ref, watch} from "vue";
import PrimaryButton from "@/Components/Commons/PrimaryButton.vue";
import MyButtonGoBack from "@/Components/Commons/MyButtonGoBack.vue";
import VueDatePicker from '@vuepic/vue-datepicker';
import vueSelect from 'vue-select';
import '@vuepic/vue-datepicker/dist/main.css';
import { ja } from 'date-fns/locale';
import myconsts from "@/myconsts.js";

const props = defineProps({
    event: null,
});

const form = useForm({
    title: props.event.title,
    description: props.event.description,
    color_id: props.event.color_id,
    start_at: props.event.start_at,
    end_at: props.event.end_at,
    start_at_changed: false,
    end_at_changed: false,
});

let options = ref(myconsts.COLOR_TYPE);

const getDayClass = (date) => {
    const weekDay = new Date(date).getDay();
    if (weekDay === 6) {
        return 'saturday';
    }
    if (weekDay === 0) {
        return 'sunday';
    }
    return '';
};

const upd = () => {
    form.patch(route('user.event.update', props.event.id), {
        onSuccess: () => {
            toaster.success('イベントを編集しました');
            router.visit('/user/event');
        },
        onError: () => {
            toaster.error('イベントの編集に失敗しました');
        },
    });
};

function updateSelectedStartAt(val) {
    form.start_at = val; //formのデータを更新
    form.start_at_changed = true; //少しでもいじったらフラグをオン！
}

function updateSelectedEndAt(val) {
    form.end_at = val; //formのデータを更新
    form.end_at_changed = true; //少しでもいじったらフラグをオン！
}
</script>

<template>
    <AppLayout>
        <div class="my-panel">
            <div class="mb-4 px-4 w-full sm:w-1/2 col-span-6 sm:col-span-4">
                <InputLabel for="title" value="タイトル"/>
                <TextInput
                    id="title"
                    v-model="form.title"
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError class="mt-2" :message="form.errors.title"/>
            </div>
            <div class="mb-3 mx-4">
                <InputLabel for="description " value="イベント詳細"/>
                <textarea
                    id="description"
                    v-model="form.description"
                    rows="4"
                    type="text"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                />
                <InputError class="mt-2" :message="form.errors.description"/>
            </div>
            <div class="mb-4 mx-4 w-full sm:w-1/2 col-span-6 sm:col-span-4">
                <InputLabel for="color" value="識別カラー"/>
                <vue-select
                    id="color"
                    label="label"
                    :options="options"
                    :clearable="false"
                    :reduce="(option) => option.id"
                    v-model="form.color_id"
                >
                    <template #option="{ label, class: cls }">
                        <div class="flex items-center gap-2">
                            <span :class="['inline-block w-4 h-4 rounded border', cls]"></span>
                            <span class="text-sm">{{ label }}</span>
                        </div>
                    </template>

                    <template #selected-option="{ label, class: cls }">
                        <div class="flex items-center gap-2">
                            <span :class="['inline-block w-4 h-4 rounded border', cls]"></span>
                            <span class="text-sm">{{ label }}</span>
                        </div>
                    </template>
                </vue-select>
                <InputError class="mt-2" :message="form.errors.color_id"/>
            </div>
            <div class="mb-3 flex">
                <div class="me-3 mx-4">
                    <InputLabel for="start_at" value="開始"/>
                    <div class="mt-1">
                        <!--                        <VueDatePicker-->
                        <!--                            :format="format"-->
                        <!--                            v-model="form.start_at"-->
                        <!--                            locale="ja"-->
                        <!--                            auto-apply-->
                        <!--                            placeholder="日時を選択してください。"-->
                        <!--                            :day-class="getDayClass"-->
                        <!--                            week-start="0"-->
                        <!--                            :min-date="minDate"-->
                        <!--                            @update:model-value="updateEndAt"-->
                        <!--                        />-->
                        <vue-date-picker
                            id="start_at"
                            v-model="form.start_at"
                            :format-locale="ja"
                            format="yyyy-MM-dd HH:mm"
                            :auto-apply="true"
                            :enable-time="true"
                            :enable-seconds="true"
                            :clearable="false"
                            :day-class="getDayClass"
                            @update:model-value="updateSelectedStartAt"
                        />
                    </div>
                    <InputError class="mt-2" :message="form.errors.start_at"/>
                </div>
                <div class="me-3 mx-4">
                    <InputLabel for="end_at" value="終了"/>
                    <div class="mt-1">
                        <vue-date-picker
                            id="end_at"
                            v-model="form.end_at"
                            :format-locale="ja"
                            format="yyyy-MM-dd HH:mm"
                            :auto-apply="true"
                            :enable-time="true"
                            :enable-seconds="true"
                            :clearable="false"
                            :day-class="getDayClass"
                            @update:model-value="updateSelectedEndAt"
                        />
                    </div>
                    <InputError class="mt-2" :message="form.errors.end_at"/>
                </div>
            </div>
            <div class="px-4">
                <PrimaryButton class="mt-3 me-3" :class="{ 'opacity-25': form.processing }"
                               :disabled="form.processing" @click="upd()">
                    保存
                </PrimaryButton>
                <MyButtonGoBack :target="route('user.event.show', props.event.id)"/>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
:deep(.cat-0) {
    background-color: rgba(209, 213, 219, 0.2); /* #d1d5db, 20% */
    border-color:     rgba(209, 213, 219, 0.2);
    color:            black;
}

:deep(.cat-1) {
    background-color: rgba(22, 174, 103, 0.2);
    border-color: rgba(22, 174, 103, 0.2);
}

:deep(.cat-2) {
    background-color: rgba(146, 72, 152, 0.2);
    border-color: rgba(146, 72, 152, 0.2);
    color: black;
}

:deep(.cat-3) {
    background-color: rgba(255, 0, 0, 0.2);
    border-color: white;
    color: black;
}

:deep(.cat-4) {
    background-color: rgba(43, 113, 185, 0.2);
    border-color: rgba(43, 113, 185, 0.2);
    color: black;
}

:deep(.cat-5) {
    background-color: rgba(246, 171, 0, 0.2);
    border-color: rgba(246, 171, 0, 0.2);
    color: black;
}
</style>

