<script setup>
import {ref} from "vue";
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import {router, usePage} from '@inertiajs/vue3';
import myutils from "@/myutils.js";
import myconsts from "@/myconsts.js";

const props = defineProps({
    lessons: Array,
});

// const props.lessons = [];

const fullCalendar = ref();
const auth = usePage().props.auth.user;

function isSmallScreen() {
    return window.innerWidth < 768;
}

let calendarOptions = {
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
    headerToolbar: {
        left: 'title',
        center: '',
        right: 'today prev,next dayGridMonth,timeGridDay' // user can switch between the two
    },
    locale: 'ja',
    buttonText: {
        today: '今日',
        month: '月',
        week: '週',
        day: '日',
    },
    businessHours: false,
    initialView: 'dayGridMonth',
    slotMinTime: "08:00:00",
    dateClick: function (info) {
        const calendarApi = fullCalendar.value.getApi();
        // calendarApi.changeView("timeGridDay", info.dateStr); //使いにくいのでヤメ
    },
    displayEventEnd: false,
    allDaySlot: false,
    dayCellContent: function (e) {
        return e.date.getDate();
    },
    eventClick: function(e) {
        const viewType = e.view.type;
        if (viewType === 'timeGridWeek' || viewType === 'timeGridDay' || viewType === 'dayGridMonth') {
            router.visit(route('user.event.show', e.event.id));
        }
    },
    eventTimeFormat: {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false, // 24-hour
    },
    events: [],
    eventContent: function (arg) {
        return {
            html: `<div style="white-space: normal; word-wrap: break-word; overflow-wrap: break-word; line-height: 1.2; max-width: 150px;">
                    ${arg.timeText ? `<div>${arg.timeText}</div>` : ''}
                    <div style="word-break: break-word;">${arg.event.title}</div>
                </div>`
        };
    },
}

//スマホでは週表示を禁止、週のヘッダを(YY/MM(D)じゃなくて(D)にできればそれでもいいかも)、あと初回ではなくWindowのリサイズイベントでやるべきかな
if (!isSmallScreen()) {
    calendarOptions.headerToolbar.right = 'today prev,next dayGridMonth,timeGridWeek,timeGridDay';
}

for (let i = 0; i < props.lessons.length; i++) {
    const lesson = props.lessons[i];
    const item = {
        id: lesson.id,
        title: lesson.title,
        start: dayjs(lesson.start_at).format('YYYY-MM-DD HH:mm'),
        end: dayjs(lesson.end_at).format('YYYY-MM-DD HH:mm'),
        className: 'cat-' + lesson.color_id,
    }
    calendarOptions.events.push(item);
}
</script>

<template>
    <div class="p-4">
        <FullCalendar ref="fullCalendar" :options="calendarOptions"/>
    </div>
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

:deep(.fc-v-event .fc-event-main) {
    color: #1a202c;
}

.fc table {
    /*    @apply rounded; */
}

:deep(.fc-timegrid-col-misc) {
    /* バグ？ */
    @apply hidden;
}

:deep(.fc-day-sun) {
    @apply bg-red-50;
}

:deep(.fc-day-sat) {
    @apply bg-blue-50;
}

:deep(.fc-header-toolbar) {
    @apply block md:flex;
}

:deep(.fc-timegrid-slot-label-cushion) {
    font-size: 0.875rem;
}

:deep(.fc-event-time) {
    font-size: 0.8rem;
}

:deep(.fc-event-title) {
    font-size: 0.8rem;
    font-weight: normal;
}

:deep(.fc-event) {
    cursor: pointer;
}

:deep(.fc-daygrid-day-number) {
    font-size: 0.8rem;
    color: gray;
    margin: 2px 2px -8px 0;
}

:deep(.fc-daygrid-event .fc-event-title),
:deep(.fc-timegrid-event .fc-event-title),
:deep(.fc-event div) {
    color: black !important;
}

</style>
