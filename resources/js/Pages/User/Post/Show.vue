<script setup>
import AppLayout from '@/Layouts/User/AppLayout.vue';
import {Link, usePage} from "@inertiajs/vue3";
import myutils from "../../../myutils.js";
import {ref,watch,computed} from "vue";

const props = defineProps({
  post: null
});
const auth = usePage().props.auth.user;
const post = ref(props.post);
console.log(props.post);
function stripHtml(html) {
  const div = document.createElement('div');
  div.innerHTML = html;
  return div.textContent || div.innerText || '';
}

</script>
<template>
    <AppLayout>
      <div class="flex justify-end mx-auto max-w-5xl lg:mx-0 mb-4">
        <!-- <Link :href="route('post.create')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                New Post
        </Link> -->
      </div>
        <div class="my-panel">
            <div class="mx-auto max-w-2xl lg:mx-0">
                <h2 class="text-pretty text-2xl font-semibold tracking-tight text-gray-900 sm:text-5xl">記事一覧</h2>
                    <p class="mt-2 text-lg/8 text-gray-600">ナレッジ・ノウハウをアウトプットで貯めていき共有していく。</p>
            </div>
            <!-- <div class="mx-auto mt-10 grid max-w-3xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3">
            </div> -->
            <div class="mt-10 space-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16">
          <article class="px-4 flex flex-col items-start justify-between">
            <div class="flex items-center gap-x-4 text-xs">
              <time :datetime="post.updated_at" class="text-gray-500">{{ post.updated_at }}</time>

              <template v-for="(category, index) in post.category_names.split(',')" :key="index">
                <span class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100 inline-block whitespace-nowrap overflow-hidden text-ellipsis max-w-[150px]">
                  {{ category.trim() }}
                </span>
              </template>

              <!-- <div class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100">{{ post.title }}</div> -->
            </div>

            <div class="relative mt-8 flex items-center gap-x-4">
              <img :src="post.profile_photo_path" alt="" class="size-10 rounded-full bg-gray-50" />
              <div class="text-sm/6">
                <p class="font-semibold text-gray-900">
                    <span class="absolute inset-0" />
                    {{ post.user_name }}
                </p>
                <p class="text-gray-600">{{ post.role }}</p>
              </div>
            </div>

            <div class="group relative">
              <h3 class="mt-3 text-lg/6 font-semibold text-gray-900 group-hover:text-gray-600">
                  <span class="absolute inset-0" />
                  {{ post.title }}
              </h3>
              <p class="mt-5 text-sm/6 text-gray-600" v-html="post.content"></p>
            </div>
          </article>
        </div>
        </div>
    </AppLayout>
  </template>