<script setup>
import {Link} from "@inertiajs/vue3";
import {Countdown} from 'vue3-flip-countdown';
import myutils from "@/myutils.js";

const props = defineProps({
    lessons: Array,
});
const now = dayjs(new Date()).format('YYYY-MM-DD HH:mm:ss');
const today = dayjs(new Date()).format('YYYY-MM-DD');

// const today_lessons = props.lessons.filter(lesson => {
//     return formatDate(lesson.start_at) == today;
// });
let today_lessons = [];

function formatDate(dateString) {
    const lesson_date = dayjs(new Date(dateString)).format('YYYY-MM-DD');
    return lesson_date;
}
</script>

<template>
    <div class="sm:p-4">
        <div class="mx-2 text-lg font-semibold">本日のイベント一覧</div>
        <hr class="h-px my-2 bg-gray-200 border-0 dark:bg-gray-700">
        <div
            class="divide-y divide-gray-200 overflow-hidden rounded-lg shadow md:grid md:grid-cols-1 md:gap-px md:divide-y-0" v-if="today_lessons.length > 0">
            <div v-for="(lesson, idx) in today_lessons" :key="lesson.lesson_name">
                <div
                    :class="[
                     idx === 0 ? 'rounded-tl-lg rounded-tr-lg sm:rounded-tr-none' : '',
                     idx === 1 ? 'sm:rounded-tr-lg' : '',
                     idx === lessons.length - 2 ? 'sm:rounded-bl-lg' : '',
                     idx === lessons.length - 1 ? 'rounded-bl-lg rounded-br-lg sm:rounded-bl-none' : '',
                    'group relative bg-white p-4 pt-2 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500'
                  ]">
                    <div class="flex mb-4">
                        <div>
                            <img :src="'/images/cat' + lesson.category_id + '.svg'" class="!h-14">
                        </div>
                        <div>
                            <h3 class="text-base font-semibold leading-6 text-gray-900 mt-0 ms-4 mb-1">
                                <Link :href="route('teacher.lesson.show', lesson.lesson_id)" class="link-primary">
                                    {{ lesson.course_name }} {{ lesson.lesson_name }}
                                </Link>
                            </h3>
                            <div class="text-gray-400 ms-3">
                            <span class="date">
                                {{ myutils.getDateStrMid(lesson.start_at) }}
                            </span>
                                <span class="mx-1">～</span>
                                <span class="date">
                                {{ myutils.getDateStrMid(lesson.end_at) }}
                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="mb-4 text-sm text-gray-500">
                            {{ lesson.description }}
                        </p>
                        <div class="">
                            <Countdown
                                v-if="lesson.start_at > now"
                                class="hidden"
                                :deadline="lesson.start_at"
                                countdownSize="1.5rem"
                                labelSize="0.8rem"
                                mainColor="#FFF"
                                secondFlipColor="#EEE"
                                :showSeconds="true"
                                :labels="{days: '日',hours: '時間',minutes: '分',seconds: '秒',}"
                            />
                            <div v-else class="bg-red-100 p-4 rounded-lg shadow">
                                終了しました。
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>本日のイベント予定はありません</div>
    </div>
</template>

<style scoped>
.date {
    @apply inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-sm font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10;
}
</style>
