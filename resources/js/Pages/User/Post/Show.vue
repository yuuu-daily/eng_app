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
            <div class="mx-auto mt-10 grid max-w-3xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3">
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