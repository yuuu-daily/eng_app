<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import videojs from 'video.js';
import 'video.js/dist/video-js.css';
import '../../css/videojs-skin.css';
import _ from 'lodash';
// import constants from '../../../constants';

const emit = defineEmits(['onTimeupdate']);

const props = defineProps({
    video: null,
})

const video = ref(props.video);
let player = null;
let options = {
    autoplay: false,
    controls: true,
    fluid: true,
    playbackRates: [0.8, 1, 1.25, 1.5, 2],
    // poster: video.value.thumbnail,
    // sources: [{
    //     src: video.value.src,
    //     type: video.value.type,
    // }],
    sources: [
        {
            src: props.video,
            type: 'video/mp4',
        }
    ]
};

function moveTime(seconds) {
    player.currentTime(seconds);
    player.play();
}

onMounted(() => {
    document.addEventListener('keydown', keyDown);
    player = videojs('player-element', options, () => {
        player.on('pause', function () {
            // emit('onTimeupdate', player.currentTime()); //これだと最後に二重になるため、送るのをやめた。どうせthrottleが効く
        });
        player.on('play', function () {
        });
        player.on('ended', function () {
            emit('onTimeupdate', player.currentTime()); //最後は送る
        });
        player.on('timeupdate', _.throttle(function () {
                if (player.player_) {
                    emit('onTimeupdate', player.currentTime());
                }
            }, 10000) //10秒は空けるため
        );
    });
});

onBeforeUnmount(() => {
    document.removeEventListener('keydown', keyDown);
    if (player) {
        player.dispose();
    }
});

function keyDown(ev) {
    if (props.isFocus) {
        return;
    }
    // const options = options;
    const p = player.currentTime();
    const pbr = player.playbackRate();
    const pos = options.playbackRates.indexOf(pbr, 0);
    switch (ev.keyCode) {
        case 37: //←
        case 74: //j
            player.currentTime(ev.shiftKey ? p - 10 : p - 5);
            player.userActive(true);
            break;
        case 39: //→
        case 76: //l
            player.currentTime(ev.shiftKey ? p + 10 : p + 5);
            player.userActive(true);
            break;
        case 75: //k
        case 13: //enter
        case 32: //space
            if (player.paused()) {
                player.userActive(true);
                player.play();
            } else {
                player.pause();
            }
            break;
        case 188: //<
            if (ev.shiftKey) {
                if (pos > 0) {
                    player.playbackRate(options.playbackRates[pos - 1]);
                }
            }
            break;
        case 190: //>
            if (ev.shiftKey) {
                if (pos < options.playbackRates.length) {
                    player.playbackRate(options.playbackRates[pos + 1]);
                }
            }
            break;
        case 27:
            modalHelp.value.hide();
            break;
        case 191:
            if (ev.shiftKey) {
                modalHelp.value.show();
            }
            break;
        default:
            break;
    }
}
</script>

<template>
    <div class="row">
        <div class="col-12">
            <video id="player-element" class="video-js shadow-sm"></video>
        </div>
    </div>
</template>

<style scoped>
li{
    list-style:none;
}
</style>
