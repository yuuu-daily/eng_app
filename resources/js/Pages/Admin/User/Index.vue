<script setup>
import MyBaseGrid from "@/Components/Commons/MyBaseGrid.vue";
import {ref, watchEffect, computed} from 'vue';
import {createColumnHelper, FlexRender} from "@tanstack/vue-table";

const props = defineProps({
    users: Array,
    companies: Array
});
props.users = [{id:1, name: "test", email: "test@email.com"}];
const btnDel = ref(null);
const confirmDel = (id, name) => {
    btnDel.value.del(id, name);
}

const columnHelper = createColumnHelper();
const columns = [
    columnHelper.accessor('id', {header: 'ID', meta: {thClass: 'w-16'}}),
    columnHelper.accessor('name', {header: '名前', meta: {thClass: 'w-28'}}),
    columnHelper.accessor('email', {header: 'ログインID', meta: {thClass: 'w-36'}}),
    columnHelper.accessor('company', {header: '法人', mete: {thClass: 'w-36'}}),
    columnHelper.accessor('role', {header: 'ロール', mete: {thClass: ''}}),
    columnHelper.accessor('last_login_at', {header: '最終ログイン日時', mete: {thClass: 'w-36'}}),
    columnHelper.accessor('actions', {header: '', meta: {thClass: 'w-20'}}),
]
</script>

<template>
    <AppLayout title="ユーザ" :is-top-navigation="1">
        
        <div class="my-panel">
            <MyBaseGrid :columns="columns" :items="props.users">
                <template #cell="prop">
                    <div v-if="prop.cell.column.columnDef.accessorKey === 'id'">
                        {{ prop.cell.row.original.id }}
                    </div>
                    <div v-else-if="prop.cell.column.columnDef.accessorKey === 'company'">
                        <span v-for="c in props.companies" :key="c.id">
                            <span v-if="prop.cell.row.original.company_id === c.id">{{ c.name }}</span>
                        </span>
                    </div>
                    <div v-else-if="prop.cell.column.columnDef.accessorKey === 'actions'">
                        <div class="text-end" v-if="$page.props.auth.user.id !== prop.cell.row.original.id && prop.cell.row.original.role < $page.props.auth.user.role">
                            <Link :href="route('admin.user.edit', prop.cell.row.original.id)">
                                <span class=""><i class="bi bi-pencil-square"></i></span>
                            </Link>
                            <span class="cursor-pointer ms-4"
                                  @click="confirmDel(prop.cell.row.original.id, prop.cell.row.original.name)">
                                <i class="bi bi-trash"></i>
                            </span>
                        </div>
                    </div>
                    <FlexRender
                        v-else
                        :render="prop.cell.column.columnDef.cell" :props="prop.cell.getContext()"
                    />
                </template>
            </MyBaseGrid>
        </div>
    <MyButtonDel target-route="admin.user.destroy" ref="btnDel"></MyButtonDel>
    </AppLayout>
</template>


