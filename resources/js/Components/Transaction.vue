<script setup>
    import SuccessBadge from '@/Components/Badges/Small/Rounded/Success.vue';
    import WarningBadge from '@/Components/Badges/Small/Rounded/Warning.vue';
    import DangerBadge from '@/Components/Badges/Small/Rounded/Danger.vue';
    import moment from 'moment';

    const props = defineProps(['transaction']);
    
    const view = () => {
        return window.location.href = route('transactions.show', props.transaction.uuid);
    }

    const formatDate = (value) => {
        return moment(value).format("MMMM DD, YYYY h:m:s A")
    }

</script>
 
<template>
    <tr class="text-gray-700">
        <td class="px-4 py-3 text-xs capitalize font-bold">
            {{transaction.receipt_no}}
        </td>
        <td class="px-4 py-3 text-xs capitalize">
            {{transaction.total_order}}
        </td>
        <td class="px-4 py-3 text-xs capitalize">
            {{transaction.amount}}
        </td>
        <td class="px-4 py-3 text-xs capitalize">
            <SuccessBadge v-if="transaction.status === 'completed'">
                {{transaction.status}}
            </SuccessBadge>
            <WarningBadge v-else-if="transaction.status === 'pending' || transaction.status=== 'preparing' || transaction.status=== 'serving'">
                {{transaction.status}}
            </WarningBadge>
            <DangerBadge v-else>
                {{transaction.status}}
            </DangerBadge>
        </td>
        <td class="px-4 py-3 text-xs capitalize">
            {{formatDate(transaction.sold_at)}}
        </td>
        <td class="px-4 py-3 text-sm">
            <button class="truncate" @click="view()">
                <span class="text-sky-500 hover:text-sky-700 font-medium mr-4">View</span>
            </button>
        </td>
    </tr>
</template>