<script setup>
import {ref} from 'vue'
// import AppLayout from '@/Layouts/AppLayout.vue';
import AppLayout from '@/Layouts/User/AppLayout.vue';
import LatestNotifications from "@/Pages/User/Event/Partials/LatestNotifications.vue";
import LatestCalendar from "@/Pages/User/Event/Partials/LatestCalendar.vue";
import {Link} from "@inertiajs/vue3";
// import DashboardAdmin from "@/Pages/Admin/DashboardAdmin.vue";
// import DashboardLecturer from "@/Pages/Lecturer/DashboardLecturer.vue";
import myconsts from "@/myconsts.js";

const props = defineProps({
    events: Array,
    courses: Array,
});
</script>

<template>
    <AppLayout>
<!--        <div class="text-end mb-8 me-4 sm:me-0">-->
<!--            <Link :href="route('user.event.create')" class="my-btn-add">-->
<!--                新規追加-->
<!--            </Link>-->
<!--        </div>-->
        <div class="flex justify-end mx-auto max-w-8xl lg:mx-0 mb-4">
            <Link :href="route('user.event.create')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                New Event
            </Link>
        </div>
        <div class="my-panel">
            <LatestNotifications></LatestNotifications>
            <LatestCalendar :lessons="props.events"></LatestCalendar>
        </div>
    </AppLayout>
</template>
