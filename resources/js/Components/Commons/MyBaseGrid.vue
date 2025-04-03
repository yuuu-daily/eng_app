
<script setup>
import {
    FlexRender,
    getCoreRowModel,
    useVueTable,
    getPaginationRowModel,
    getSortedRowModel,
    getFilteredRowModel,
} from '@tanstack/vue-table';
import {MagnifyingGlassIcon} from '@heroicons/vue/20/solid';
import {ref, watchEffect, computed, onMounted, onBeforeMount, watch} from 'vue';

const props = defineProps({
    columns: Array,
    items: Array,
    filter: {
        default: null,
    },
    showSearchSection: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['update:data']);

// console.log(props.items)
// console.log(props.filter)

const columns = ref(props.columns);
const pageSizes = [10, 50, 100, 200];
const accessKeys = columns.value.map(item => item.accessorKey);
const data = ref(props.items);
const sorting = ref([]);
let searchWord = ref('');
// defineExpose({ data });
watch(data, (newData) => {
    emit('update:data', newData);
});

const table = useVueTable({
    get data() {
        return data.value
    },
    get columns() {
        return columns.value;
    },
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    state: {
        get sorting() {
            return sorting.value
        },
    },
    onSortingChange: (updaterOrValue) => {
        sorting.value = typeof updaterOrValue === 'function' ? updaterOrValue(sorting.value) : updaterOrValue
    },
    getSortedRowModel: getSortedRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    // debugTable: true,
})

function handlePageSizeChange(e) {
    table.setPageSize(Number(e.target.value));
}

function kanaToHiragana(src) {
    return src.replace(/[\u30a1-\u30f6]/g, function (match) {
        let chr = match.charCodeAt(0) - 0x60;
        return String.fromCharCode(chr);
    });
}

const getFilteredItems = (items) => {
    //全角半角カナかな大文字小文字もマッチ
    const normalizeFormat = 'NFKC';
    const v = kanaToHiragana(searchWord.value.normalize(normalizeFormat)).toLowerCase();
    return items.filter(item => {
        let targets = [];
        //表示しているものだけ検索対象
        accessKeys.forEach(key => {
            if (item.hasOwnProperty(key)) {
                if (item[key]) {
                    if (key === 'grade_id') { //むむむ…
                        targets.push(myutils.getGradeStr(item[key]));
                    } else {
                        targets.push(item[key]);
                    }
                }
            }
        });
        const s = kanaToHiragana(targets.join('\t').normalize(normalizeFormat)).toLowerCase();
        return s.includes(v);
    })
};

//オプションのフィルタ
const getFilteredItemsEx = (items) => {
    if (props.filter) {
        if ('type_id' in props.filter) {
            if (props.filter['type_id'] > 0) {
                items = items.filter(item => item.type_id === props.filter['type_id']);
            }
        }
        if ('role_id' in props.filter) {
            if (props.filter['role_id'] >= 0) {
                items = items.filter(item => item.role_id === props.filter['role_id']);
            }
        }
    }
    return items;
}

watchEffect(() => {
    let items = getFilteredItems(props.items); //検索
    data.value = getFilteredItemsEx(items); //オプションのフィルタ
    columns.value = props.columns;
})

onMounted(() => {
    data.value = getFilteredItemsEx(props.items); //初期フィルタ
    table.setPageSize(50);
})
</script>

<template>
    <div v-if="props.showSearchSection" class="flex">
        <div class="">
            <div class="flex rounded-md w-full">
                <div class="relative flex flex-grow items-stretch focus-within:z-10  sm:w-48">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <MagnifyingGlassIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/>
                    </div>
                    <input type="search" :value="searchWord" @input="searchWord = $event.target.value"
                           class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                           placeholder="検索"/>
                </div>
            </div>
            <div v-if="$slots.checkedAll" class="">
                <slot name="checkedAll"/>
            </div>
        </div>
        <div v-if="$slots.opt" class="">
            <slot name="opt"/>
        </div>
        <div v-if="$slots.opt2" class="text-end ml-auto mt-5 me-5">
            <slot name="opt2"/>
        </div>
    </div>
    <div class="-mx-2 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div v-if="table.options.data.length === 0" class="mt-4">
                データがありません
            </div>
            <div v-else>
                <table class="mb-4 w-full">
                    <thead>
                    <tr v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                        <th
                            v-for="header in headerGroup.headers"
                            :key="header.id"
                            :colSpan="header.colSpan"
                            :class="header.column.getCanSort() ? 'cursor-pointer select-none ' + (header.column.columnDef.meta?.thClass ?? '') : ''"
                            @click="header.column.getToggleSortingHandler()?.($event)"
                        >
                            <slot v-if="$slots.header">
                                <slot name="header" :cell="header"></slot>
                            </slot>
                            <template v-if="!header.isPlaceholder">
                                <FlexRender
                                    :render="header.column.columnDef.header"
                                    :props="header.getContext()"
                                />
                                <span v-if="header.column.getIsSorted() === 'asc'" class="ms-1">
                                <i class="bi bi-chevron-up"></i>
                            </span>
                                <span v-if="header.column.getIsSorted() === 'desc'" class="ms-1">
                                <i class="bi bi-chevron-down"></i>
                            </span>
                            </template>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="row in table.getRowModel().rows" :key="row.id">
                        <td v-for="cell in row.getVisibleCells()" :key="cell.id" class="align-middle">
                            <!--                            {{ $slots.default }}-->
                            <slot v-if="$slots.cell">
                                <slot name="cell" :cell="cell"></slot>
                            </slot>
                            <template v-else>
                                <FlexRender
                                    :render="cell.column.columnDef.cell"
                                    :props="cell.getContext()"
                                />
                            </template>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <nav class="flex items-center justify-between" aria-label="Pagination">
                    <div class="flex flex-1 justify-start">
                        <!--
                        <button @click="() => table.setPageIndex(0)" :disabled="!table.getCanPreviousPage()" class="btn-page-action">
                            最初
                        </button>
                        -->
                        <button @click="() => table.previousPage()" :disabled="!table.getCanPreviousPage()"
                                class="btn-page-action">
                            前ページ
                        </button>
                        <button @click="() => table.nextPage()" :disabled="!table.getCanNextPage()"
                                class="btn-page-action">
                            次ページ
                        </button>
                        <!--
                        <button @click="() => table.setPageIndex(table.getPageCount() - 1)" :disabled="!table.getCanNextPage()" class="btn-page-action">
                            最後
                        </button>
                        -->
                    </div>
                    <div>
                        <select :value="table.getState().pagination.pageSize" @change="handlePageSizeChange"
                                class="block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option :key="pageSize" :value="pageSize" v-for="pageSize in pageSizes">
                                {{ pageSize }}
                            </option>
                        </select>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm text-gray-700">
                    <span class="font-medium mx-2">
                        {{ table.options.data.length }}件
                    </span>
                            <span class="font-medium">
                        {{ table.getState().pagination.pageIndex * table.getState().pagination.pageSize + 1 }}
                        ～
                        {{ (table.getState().pagination.pageIndex + 1) * table.getState().pagination.pageSize }}
                    </span>
                        </p>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</template>

<style scoped>
:deep(table) {
    @apply min-w-full divide-y divide-gray-300;
}

:deep(tbody) {
    @apply divide-y divide-gray-200;
}

:deep(th) {
    @apply px-3 py-3.5 text-left text-sm font-semibold text-gray-900;
}

:deep(tr:nth-child(even)) {
    @apply bg-gray-50;
}

:deep(td) {
    @apply whitespace-nowrap px-3 py-4 text-sm text-gray-500;
}

.btn-page-action {
    @apply relative me-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0 disabled:opacity-30;
}
</style>