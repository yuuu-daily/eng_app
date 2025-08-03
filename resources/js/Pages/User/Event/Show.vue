<script setup>
// import AppLayout from '@/Layouts/AppLayout.vue';
import AppLayout from '@/Layouts/User/AppLayout.vue';
import {Link, router, usePage} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import EventDetail from "@/Pages/User/Event/Partials/EventDetail.vue";
import InputLabel from "@/Components/InputLabel.vue";
import myconsts from "@/myconsts.js";
import {computed} from "vue";

const props = defineProps({
    event: null,
    video: null,
});
const authUser = usePage().props.auth.user;
const now = dayjs(new Date()).format('YYYY-MM-DD HH:mm:ss');
const nowMonth = dayjs().format('YYYY-MM');
// const filteredAllStudents = computed(() => {
//     return props.allStudents.filter(student => {
//         const lessonStartMonth = dayjs(student.course_start_at).format('YYYY-MM');
//         return lessonStartMonth <= nowMonth;
//     });
// });
//
// const filteredStudentsByJuku = computed(() => {
//     return props.students.filter(student => {
//         const lessonStartMonth = dayjs(student.course_start_at).format('YYYY-MM');
//         return lessonStartMonth <= nowMonth;
//     });
// });
function onClickEdit() {
    router.visit('/user/event/edit/' + props.event.id);
}
</script>

<template>
    <AppLayout title="イベント詳細">
<!--        <button type="button" @click="onClickEdit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-5 focus:outline-none">Event Edit</button>-->
        <div class="text-sm px-5 py-2.5 me-2 mb-5 focus:outline-none">
            <Link :href="route('user.event.edit', props.event.id)" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                Edit Event
            </Link>
        </div>
        <div class="my-panel">
            <div class="mx-auto px-4 py-6 sm:px-6 sm:py-6 lg:grid lg:gap-6">
                <div class="">
                    <EventDetail :lesson="props.event" :video="props.video"></EventDetail>
                </div>
                <div class="mt-4 lg:col-start-2 lg:row-span-2 lg:mt-0 lg:border-t-0 lg:border-l lg:ps-3">
                    <div>
                        <div>
                            <h3 class="text-lg font-semibold">対象生徒 12名</h3>
                            <div class="p-4 mt-2 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50" role="alert">
                                <p>・<span class="font-semibold">太字</span>が出席生徒です</p>
                            </div>
                            <div class="my-2">
                                <Link
                                    :href="route('user.event.edit', props.event.id)"
                                    class="link-primary"
                                >
                                    <PrimaryButton :disabled="props.event.start_at < now">
                                        出欠設定
                                    </PrimaryButton>
                                </Link>
                            </div>
                        </div>
                        <ul>
<!--                            <li v-for="student in filteredStudentsByJuku">-->
<!--                                <span :class="[student.status ? 'font-semibold' : 'text-gray-500']">{{ student.name }}<span v-if="student.name_kana"> ({{ student.name_kana }})</span></span>-->
<!--                            </li>-->
                        </ul>
                        <hr class="my-3">
                    </div>
                    <div>
<!--                        <InputLabel :value="'全教室対象生徒' + '(' + filteredAllStudents.length + '名)'" class="mb-3 font-semibold"/>-->
<!--                        <div class="p-4 mt-2 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50" role="alert">-->
<!--                            <p>・<span class="font-semibold">太字</span>が出席生徒です</p>-->
<!--                        </div>-->
<!--                        <ul>-->
<!--                            <li v-for="s in filteredAllStudents">-->
<!--                            <span :class="[s.status ? 'font-semibold' : 'text-gray-500']">-->
<!--                                <span class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded">{{ s.juku_name }}<span v-if="s.address"> ({{ s.address }})</span></span>-->
<!--                                <span>{{ s.name }}</span>-->
<!--                                <span v-if="s.name_kana">-->
<!--                                    ({{ s.name_kana }})-->
<!--                                </span>-->
<!--                            </span>-->
<!--                            </li>-->
<!--                        </ul>-->
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<style scoped>
</style>
