<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import Table from '@/Components/Table.vue';
    import Transaction from '@/Components/Transaction.vue';
    import SimplePagination from '@/Components/SimplePagination.vue';
    import { Head } from '@inertiajs/vue3';

    defineProps(['transactions']);

</script>

<template>
    <Head title="Transactions" />

    <AuthenticatedLayout>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 lg:ml-60 md:ml-64">
                <Table :title="'Transactions'" :description="'Showing transactions'">
                    <template #action>
                    </template>
                    <div class="w-full overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                                <th class="px-4 py-3">Receipt No.</th>
                                <th class="px-4 py-3">Order</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Sold At</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y" v-if="transactions.data.length > 0">
                            <Transaction v-for="transaction in transactions.data"
                                :key="transaction.id"
                                :transaction="transaction">
                            </Transaction>
                        </tbody>
                        <tbody class="bg-white divide-y" v-else>
                            <tr class="text-gray-700">
                                <td class="px-4 py-3 text-sm  font-bold" colspan="2">
                                   There are no records as of this moment.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    <SimplePagination :data="transactions" />
                </Table>
            </div>
        </div>
    </AuthenticatedLayout>

</template>
