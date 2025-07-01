<template>
    <AppLayout>
      <div class="flex justify-end mx-auto max-w-8xl lg:mx-0 mb-4">
        <Link :href="route('user.post.create')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                New Post
        </Link>
      </div>
        <div class="my-panel">
            <div class="mx-auto max-w-2xl lg:mx-0">
                <h2 class="text-pretty text-lg font-semibold tracking-tight text-gray-900 sm:text-md">記事一覧</h2>
                    <p class="mt-2 text-sm text-gray-600">ナレッジ・ノウハウをアウトプットで貯めていき共有していきます。ここでは、主に自分自身のトレーニングを通して、学んだこと・感じたことを、書き残していこうと思っています。</p>
            </div>
            <div class="mx-auto mt-10 grid max-w-3xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3">
          <article v-for="post in posts" :key="post.id" class="my-3 mx-2 flex max-w-3xl flex-col items-start justify-between">
            <Link :href="route('user.post.show', post.id)" class="my-panel cursor-pointer">
            <div class="flex items-center gap-x-4 text-xs">
              <time :datetime="post.update_at" class="text-gray-500">{{ myutils.getDateStr2(post.updated_at) }}</time>

              <template v-for="(category, index) in post.category_names.split(',')" :key="index">
                <span class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100 inline-block whitespace-nowrap overflow-hidden text-ellipsis max-w-[150px]">
                  {{ category.trim() }}
                </span>
              </template>
              <!-- <span class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100">{{ post.category.title }}</span> -->
            </div>
            <div class="group relative">
              <h3 class="mt-3 text-lg/6 font-semibold text-gray-900 group-hover:text-gray-600">
                <a :href="post.href">
                  <span class="absolute inset-0" />
                  {{ post.title }}
                </a>
              </h3>
              <p class="mt-5 line-clamp-3 text-sm/6 text-gray-600 break-words whitespace-normal">{{ stripHtml(post.content) }}</p>
            </div>
            <div class="relative mt-8 flex items-center gap-x-4">
              <img :src="post.profile_photo_path" alt="" class="size-10 rounded-full bg-gray-50" />
              <div class="text-sm/6">
                <p class="font-semibold text-gray-900">
                  <span>
                    <span class="absolute inset-0" />
                    {{ post.user_name }}
                  </span>
                </p>
                <p class="text-gray-600">{{ post.role }}</p>
              </div>
            </div>
          </Link>
          </article>
        </div>
        </div>
    </AppLayout>
  </template>

  <script setup>
  import AppLayout from '@/Layouts/User/AppLayout.vue';
  import {Link, usePage} from "@inertiajs/vue3";
  import myutils from "../../../myutils.js";

  const props = defineProps({
    users: Array,
    companies: Array,
    posts: null
});
const auth = usePage().props.auth.user;
const posts = props.posts;
console.log(posts);

function stripHtml(html) {
  const div = document.createElement('div');
  div.innerHTML = html;
  return div.textContent || div.innerText || '';
}

  // const posts = [
  //   {
  //     id: 1,
  //     title: 'Boost your conversion rate',
  //     href: '#',
  //     description:
  //       'Illo sint voluptas. Error voluptates culpa eligendi. Hic vel totam vitae illo. Non aliquid explicabo necessitatibus unde.',
  //     date: 'Mar 16, 2020',
  //     datetime: '2020-03-16',
  //     category: { title: 'Marketing', href: '#' },
  //     author: {
  //       name: 'Michael Foster',
  //       role: 'Co-Founder / CTO',
  //       href: '#',
  //       imageUrl:
  //         'https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
  //     },
  //   },
  //   {
  //     id: 1,
  //     title: 'Boost your conversion rate',
  //     href: '#',
  //     description:
  //       'Illo sint voluptas. Error voluptates culpa eligendi. Hic vel totam vitae illo. Non aliquid explicabo necessitatibus unde. Sed exercitationem placeat consectetur nulla deserunt vel. Iusto corrupti dicta.',
  //     date: 'Mar 16, 2020',
  //     datetime: '2020-03-16',
  //     category: { title: 'Marketing', href: '#' },
  //     author: {
  //       name: 'Michael Foster',
  //       role: 'Co-Founder / CTO',
  //       href: '#',
  //       imageUrl:
  //         'https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
  //     },
  //   },
  //   {
  //     id: 1,
  //     title: 'Boost your conversion rate',
  //     href: '#',
  //     description:
  //       'Illo sint voluptas. Error voluptates culpa eligendi. Hic vel totam vitae illo. Non aliquid explicabo necessitatibus unde. Sed exercitationem placeat consectetur nulla deserunt vel. Iusto corrupti dicta.',
  //     date: 'Mar 16, 2020',
  //     datetime: '2020-03-16',
  //     category: { title: 'Marketing', href: '#' },
  //     author: {
  //       name: 'Michael Foster',
  //       role: 'Co-Founder / CTO',
  //       href: '#',
  //       imageUrl:
  //         'https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
  //     },
  //   },
  // ]
  </script>
