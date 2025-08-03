<script setup>
import {ref} from 'vue';
import {Countdown} from 'vue3-flip-countdown';
import myutils, {getFileNameFromUrl} from "@/myutils.js";
import VideoPlayerComponent from '@/Components/VideoPlayerComponent.vue';
import myconsts from "@/myconsts.js";

const props = defineProps({
    lesson: null,
    video: null,
});
// let decodeURI = (props.lesson.url_slide);
let player = ref();
const now = dayjs(new Date()).format('YYYY-MM-DD HH:mm:ss');

function drawMaterialLink(url) {
    let ret = '';
    if (url) {
        if (url.indexOf('storage/pdf') !== -1) {
            ret = "<a class='link-primary'"
                + " href='" + url + "' target='_blank'>"
                + myutils.getFileNameFromPath(url) + '<i class="ms-2 bi bi-download" style="font-size: 1.5rem"></i></a>';
        } else if (url.indexOf('//cdn1.juku-aid.jp') !== -1) {
            ret = "<a class='link-primary'"
                + " href='" + url + "' target='_blank'>"
                + myutils.removeTimestamp(url) + '<i class="ms-2 bi bi-download" style="font-size: 1.5rem"></i></a>';
        } else {
            ret = "<a class='link-primary'"
                + " href='" + url + "' target='_blank'>"
                + myutils.getFileNameFromUrl(url) + '<i class="ms-2 bi bi-download" style="font-size: 1.5rem"></i></a>';
        }
    }
    return ret;
}

function drawRemoveDateLink(url) {
    let ret = '';
    if (!url) return ret;
    if (url.indexOf('//cdn1.juku-aid.jp') !== -1 || url.indexOf('storage/pdf') !== -1) {
        ret = "<a class='link-primary'"
            + " href='" + url + "' target='_blank'>"
            + myutils.removeTimestamp(url) + '<i class="ms-2 bi bi-download" style="font-size: 1.5rem"></i></a>';

    } else {
        ret = "<a class='link-primary'"
            + " href='" + url + "' target='_blank'>"
            + myutils.getFileNameFromUrl(url) + '<i class="ms-2 bi bi-download" style="font-size: 1.5rem"></i></a>';
    }
    return ret;
}
</script>

<template>
    <div class="">
        <div class="mt-0">
            <div class="text-3xl font-bold tracking-tight text-gray-900 flex">
<!--                <div class="w-16">-->
<!--                    <img :src="'/images/cat' + props.lesson.color_id + '.svg'" class="h-14">-->
<!--                </div>-->
                <div class="mt-2 ms-2">
                    {{ props.lesson.title }}
                </div>
            </div>
        </div>

        <section class="mt-4">
            <div class="flex items-center mb-4">
                <span class="date">{{ myutils.getDateStrMid(props.lesson.start_at) }}</span>
                <span class="mx-1">～</span>
                <span class="date">{{ myutils.getDateStrMid(props.lesson.end_at) }}</span>
            </div>
            <div class="">
                <Countdown
                    v-if="props.lesson.end_at > now"
                    :deadline="props.lesson.start_at"
                    countdownSize="1.5rem"
                    labelSize="0.8rem"
                    mainColor="#FFF"
                    secondFlipColor="#EEE"
                    :showSeconds="true"
                    :labels="{days: '日',hours: '時間',minutes: '分',seconds: '秒',}"
                />
                <div v-else class="mb-4 bg-red-100 p-4 rounded-lg shadow">
                    この授業は終了しました
                </div>
<!--                <div v-if="$page.props.auth.user.role === myconsts.ROLE_TEACHER || $page.props.auth.user.role === myconsts.ROLE_STUDENT" class="p-4 mt-2 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50" role="alert">-->
<!--                    ・授業に必要な資料はこちらから印刷することができます。<br>-->
<!--                    ・もし、欠席した場合は、こちらのサイトから授業動画を視聴することができます(※授業日から2週間以内の機能です)。<br>-->
<!--                </div>-->
                <div class="my-panel-sub">
                    <div class="mb-1 font-semibold">授業アーカイブ</div>
<!--                    <div v-if="props.video.expired_at < now" class="text-red-500 font-medium">※アーカイブ期間が終了しているため、動画視聴できません</div>-->
<!--                    <div v-else-if="props.video.is_hls === 1">-->
<!--                        <div class="mb-3 text-red-500">※アーカイブの視聴有効期間は、<span class="font-bold">{{ props.video.expired_at }}</span>　までです</div>-->
                        <video-player-component ref="player" :video="video"></video-player-component>
<!--                    </div>-->
<!--                    <div v-else class="text-red-500 font-medium">※アップロードに時間がかかっています(エンコード中)　しばらくお待ちください</div>-->
                </div>
<!--                <div class="my-panel-sub" v-else-if="!props.video.src && props.lesson.end_at < now">-->
<!--                    <span class="mb-1 font-semibold">授業アーカイブ</span>-->
<!--                    <div class="text-red-500 font-medium">※表示できる動画が存在しません</div>-->
<!--                </div>-->
            </div>
            <!--
            <div class="my-4 space-y-6">
                <p class="text-base text-gray-500">{{ props.lesson.description }}</p>
            </div>
            -->
            <div class="my-panel-sub">
                <div class="mb-1">授業URL</div>
<!--                <div v-if="props.lesson.url_lesson">-->
<!--                    <a :href="props.lesson.url_lesson" class="link-primary" target="_blank">-->
<!--                        {{ props.lesson.url_lesson }}-->
<!--                    </a>-->
<!--                </div>-->
                <div class="text-red-400">
                    この授業のzoom linkが設定されていません。管理者に確認してください。
                </div>
            </div>
<!--            <div class="my-panel-sub" v-if="$page.props.auth.user.role == 99 || $page.props.auth.user.role == 999">-->
<!--                <div class="mb-1">授業用スライド</div>-->
<!--                <div v-if="props.lesson.url_slide">-->
<!--                    <a class="link-primary" :href="props.lesson.url_slide" target="_blank">{{ myutils.removeTimestamp(props.lesson.url_slide) }}</a>-->
<!--                </div>-->
<!--                <div v-else class="text-gray-500">-->
<!--                    この授業のスライドは設定されていません-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="my-panel-sub">-->
<!--                <div class="mb-1">確認テスト</div>-->
<!--                <div-->
<!--                    v-if="props.lesson.url_check_test_1 || props.lesson.url_check_test_2 || props.lesson.url_check_test_3 || props.lesson.url_check_test_4">-->
<!--                    <div v-if="props.lesson.is_approved_test_1" v-html="drawRemoveDateLink(props.lesson.url_check_test_1)"></div>-->
<!--                    <div v-if="props.lesson.is_approved_test_2" v-html="drawRemoveDateLink(props.lesson.url_check_test_2)"></div>-->
<!--                    <div v-if="props.lesson.is_approved_test_3" v-html="drawRemoveDateLink(props.lesson.url_check_test_3)"></div>-->
<!--                    <div v-if="props.lesson.is_approved_test_4" v-html="drawRemoveDateLink(props.lesson.url_check_test_4)"></div>-->
<!--                </div>-->
<!--                <div v-else class="text-gray-500">-->
<!--                    この授業の確認テストは設定されていません-->
<!--                </div>-->
<!--            </div>-->
            <div class="my-panel-sub">
                <div class="mb-1">教材</div>
<!--                <div-->
<!--                    v-if="props.lesson.material_url_1 || props.lesson.material_url_2 || props.lesson.material_url_3 || props.lesson.material_url_4 || props.lesson.material_url_5 || props.lesson.material_url_6">-->
<!--                    <div v-if="props.lesson.is_approved_1" v-html="drawRemoveDateLink(props.lesson.material_url_1)"></div>-->
<!--                    <div v-if="props.lesson.is_approved_2" v-html="drawRemoveDateLink(props.lesson.material_url_2)"></div>-->
<!--                    <div v-if="props.lesson.is_approved_3" v-html="drawRemoveDateLink(props.lesson.material_url_3)"></div>-->
<!--                    <div v-if="props.lesson.is_approved_4" v-html="drawRemoveDateLink(props.lesson.material_url_4)"></div>-->
<!--                    <div v-if="props.lesson.is_approved_5" v-html="drawRemoveDateLink(props.lesson.material_url_5)"></div>-->
<!--                    <div v-if="props.lesson.is_approved_6" v-html="drawRemoveDateLink(props.lesson.material_url_6)"></div>-->
<!--                </div>-->
                <div class="text-gray-500">
                    この授業の教材はありません
                </div>
            </div>
            <div class="my-panel-sub">
                <div class="mb-1">宿題</div>
<!--                <div v-if="props.lesson.lecturer_notes">-->
<!--                    {{ props.lesson.lecturer_notes }}-->
<!--                </div>-->
                <div class="text-gray-500">
                    この授業の宿題は記載されていません
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>
.date {
    @apply text-lg font-semibold text-gray-600;
}

.my-panel-sub {
    @apply mb-4 bg-gray-50 p-4 rounded-lg shadow;
}
</style>
