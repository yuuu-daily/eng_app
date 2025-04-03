<script setup>
import Banner from '@/Components/Banner.vue';
import {Head, Link, router, usePage} from '@inertiajs/vue3';
import myconsts from "@/myconsts.js";
import { ref,watch } from 'vue';
import {
    Dialog,
    DialogPanel,
    Menu,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
import {Bars3Icon, XMarkIcon} from '@heroicons/vue/24/outline'
import MyBreadcrumbs from "@/Components/Commons/MyBreadcrumbs.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import MyBadge from "@/Components/Commons/MyBadge.vue";
import Dropdown from "@/Components/Dropdown.vue";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import TopNavigation from "@/Layouts/Partials/TopNavigation.vue";
import AwardNavigation from "@/Layouts/Partials/AwardNavigation.vue";
import JudgeNavigation from "@/Layouts/Partials/JudgeNavigation.vue";

const sidebarOpen = ref(false);

const props = defineProps({
    title: {type: String, default: null},
    isTopNavigation: {type: Number, default: 0},
    breadcrumbs: {type: Array, default: null},
});

if (!usePage().props.admin.award && !sessionStorage.getItem('redirectedToAward')) {
    sessionStorage.setItem('redirectedToAward', 'true');
    router.visit(route('admin.award.index')); //セッション切れなどでアワードを一度も選択してない場合、その先で不都合なのでトップへ(無限ループ防止装置あり)。
}

const award = usePage().props.admin.award;
let mergedBreadcrumbs = [];
if (award !== null) {
    const baseBreadcrumbs = [{name: award.name, route: 'admin.award.show', params: award.id}];
    mergedBreadcrumbs = props.breadcrumbs ? baseBreadcrumbs.concat(props.breadcrumbs) : baseBreadcrumbs;
} else {
    mergedBreadcrumbs = props.breadcrumbs;
}


const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="">
        <Head :title="title"/>
        <Banner/>
        <TransitionRoot as="template" :show="sidebarOpen">
            <Dialog class="relative z-50 lg:hidden" @close="sidebarOpen = false">
                <TransitionChild as="template" enter="transition-opacity ease-linear duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="transition-opacity ease-linear duration-300" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-900/80" />
                </TransitionChild>

                <div class="fixed inset-0 flex">
                    <TransitionChild as="template" enter="transition ease-in-out duration-300 transform" enter-from="-translate-x-full" enter-to="translate-x-0" leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0" leave-to="-translate-x-full">
                        <DialogPanel class="relative mr-16 flex w-full max-w-xs flex-1">
                            <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-300" leave-from="opacity-100" leave-to="opacity-0">
                                <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                                    <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                                        <span class="sr-only">Close sidebar</span>
                                        <XMarkIcon class="h-6 w-6 text-white" aria-hidden="true" />
                                    </button>
                                </div>
                            </TransitionChild>
                            <!-- Sidebar component, swap this element with another sidebar if you like -->
                            <div class="flex grow flex-col gap-y-3 overflow-y-auto bg-teal-50 px-6 pb-4">
                                <div class="flex h-16 shrink-0 items-center ps-6">
                                    <Link :href="route('dashboard')">
                                        <ApplicationMark/>
                                    </Link>
                                </div>
                                <TopNavigation v-if="isTopNavigation === 1" />
                                <AwardNavigation v-else :award="award" :title="title" />
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div class="flex h-16 shrink-0 items-center ps-6 bg-teal-50 border-r-2">
                <Link :href="route('dashboard')">
                    <ApplicationMark/>
                </Link>
            </div>
            <div v-if="$page.props.auth.user.role === myconsts.ROLE.JUDGE" class="flex grow flex-col gap-y-3 overflow-y-auto bg-teal-50 px-6 pb-4 border-r-2">
                <JudgeNavigation></JudgeNavigation>
            </div>
            <div v-else class="flex grow flex-col gap-y-3 overflow-y-auto bg-teal-50 px-6 pb-4 border-r-2">
                <TopNavigation v-if="isTopNavigation === 1" />
                <AwardNavigation v-else :award="award" :title="title" />
            </div>
        </div>

        <div class="lg:pl-72 bg-gray-50">
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <Bars3Icon class="h-6 w-6" aria-hidden="true" />
                </button>

                <!-- Separator -->
                <div class="h-6 w-px bg-gray-900/10 lg:hidden" aria-hidden="true" />

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <MyBreadcrumbs :title="title" :breadcrumbs="mergedBreadcrumbs"></MyBreadcrumbs>
                    <form class="relative flex flex-1" action="#" method="GET">
<!--
                        <label for="search-field" class="sr-only">Search</label>
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('dashboard')">
                                <div class="flex">
                                    <div class="text-teal-500 ms-2 mt-1 font-semibold">
                                        <img src="/images/logo_all.svg" class="h-5 lg:invisible" >
                                    </div>
                                </div>
                            </Link>
                        </div>
-->
                    </form>
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <!-- Separator -->
<!--                        <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-900/10" aria-hidden="true" />-->

                        <!-- Profile dropdown -->
                        <Menu as="div" class="relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                <span class="me-2 text-gray-300">
                                                    <span v-if="$page.props.auth.user.role === myconsts.ROLE.SYSTEM_ADMIN">
                                                        <MyBadge :type="2" title="管理者"></MyBadge>
                                                    </span>
                                                </span>
                                                <span>
                                                    {{ $page.props.auth.user.name }}
                                                </span>
                                                <i class="ms-2 bi bi-chevron-down"></i>
                                            </button>
                                        </span>
                                </template>
                                <template #content>
                                    <DropdownLink :href="route('profile.show')">
                                        設定
                                    </DropdownLink>
                                    <div class="border-t border-gray-200"/>
                                    <!-- Authentication -->
                                    <form @submit.prevent="logout">
                                        <DropdownLink as="button">
                                            {{ $t('Log Out') }}
                                        </DropdownLink>
                                    </form>
                                </template>
                            </Dropdown>
                        </Menu>
                    </div>
                </div>
            </div>
            <!-- Page Heading -->
            <header>
                <div class="mx-auto max-w-screen-2xl py-6 px-4 sm:px-6 lg:px-8" v-if="title">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ title }}
                    </h2>
                </div>
                <div v-else class="mb-4"></div>
            </header>

            <main class="mx-auto max-w-screen-2xl px-6 py-0 lg:px-8 min-h-screen" style="min-height: calc(100vh - 153px)">
                <slot />
            </main>
        </div>
    </div>
</template>
<style scoped>
</style>