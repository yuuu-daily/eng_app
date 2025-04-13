<script setup>
import {ref, watch} from 'vue';
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    title: {type: String, default: '画像'},
    imgUrl: null,
    maxSize: {type: Number, default: 512}, //Kb
    imgFile: null,
})

let img_file = ref('');
const fileInput = ref(null);
let errors = ref('');
let previewSrc = ref(null);
const imgStyle = ref({});
const imgWidth = ref(0);
const imgHeight = ref(0);
const emit = defineEmits(['update:imgFile']);

function onFileChange(e) {
    const validTypes = ["image/jpeg", "image/png"];
    const maxSize = 1024 * props.maxSize; //kb
    if (e.target.files.length === 0) { //キャンセル
        errors.value = "";
        return
    }
    if (!validTypes.includes(e.target.files[0].type)) {
        errors.value = "ファイルはpngまたはjpegである必要があります。";
        return;
    }
    if (e.target.files[0].size > maxSize) {
        errors.value = "最大ファイルサイズは" + maxSize / 1024 + "kbです。このファイルは" + Math.ceil(e.target.files[0].size / 1024) + "kbです。";
        return;
    }
    //ここまで来たら更新
    img_file.value = e.target.files[0];
    previewSrc.value = URL.createObjectURL(img_file.value);
    const reader = new FileReader();reader.onload = (e) => {
        previewSrc.value = e.target.result;

        // 画像のサイズを取得
        const img = new Image();
        img.src = e.target.result;
        img.onload = () => {
            imgWidth.value = img.width;
            imgHeight.value = img.height;

            // 画像のサイズに応じてスタイルを変更
            const maxWidth = 240; // 任意の閾値
            if (img.width > maxWidth) {
                imgStyle.value = {
                    maxWidth: `${maxWidth}px`,
                    width: '100%',
                    height: 'auto',
                    objectFit: 'contain',
                };
            } else {
                imgStyle.value = {};
            }
        };
    };
    reader.readAsDataURL(img_file.value);

    emit('update:imgFile', img_file.value);
}

const removeFile = () => {
    img_file.value = null;
    previewSrc.value = '';
    if (fileInput.value) {
        fileInput.value.value = '';
    }
    emit('update:imgFile', null);
};

// const { imgFile } = toRefs(props);
watch(() => props.imgUrl, (newUrl) => {
    previewSrc.value = newUrl || '';
});
</script>

<template>
    <div class="mb-5">
        <InputLabel for="name" :value="title"/>
        <div v-if="imgUrl" class="relative my-4 flex-1 w-64 overflow-hidden">
            <img :src="imgUrl" :style="imgStyle" class="shadow bg-gray-100 border-2 p-1" alt="">
        </div>
        <input type="file" ref="fileInput" :name="'img_url'" @change="onFileChange"
               class="block w-full text-sm text-slate-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-violet-50 file:text-violet-700
                hover:file:bg-violet-100 cursor-pointer"/>
        <InputError class="mt-2" :message="errors" v-if="errors !== ''"/>
        <div v-if="previewSrc" class="mt-4">
            <InputLabel>差し替え{{ title }}プレビュー</InputLabel>
            <button @click="removeFile"
                    class="mb-3 bg-red-600 text-white px-2 py-1 text-xs rounded-full hover:bg-red-700">
                削除
            </button>
            <img :src="previewSrc" :style="imgStyle" class="shadow" alt="">
        </div>
    </div>
</template>