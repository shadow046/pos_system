<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import SalesCard from '@/Components/Cards/Sales.vue';
    import InputError from '@/Components/InputError.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';
    import { Head } from '@inertiajs/vue3';
    import { ref, onMounted, watch } from 'vue';
    import { useForm } from '@inertiajs/vue3';

    const form = useForm({
        from: '',
        to: '',
    });

    const pending_order_category = ref([]);
    const completed_order_category = ref([]);
    const void_order_category = ref([]);
    const total_order_category = ref([]);

    const total_completed_order = ref(0);
    const total_void_order = ref(0);
    const total_sales = ref(0);

    const pie_graph_sales = ref([]);

    const filter = () => {
      pending_order_category.value.splice(0, pending_order_category.value.length);
      completed_order_category.value.splice(0, completed_order_category.value.length);
      void_order_category.value.splice(0, void_order_category.value.length);
      pie_graph_sales.value.splice(0, pie_graph_sales.value.length);
      loadData();
    };

    async function loadData() {
      let result =  await (await fetch(route('api.reports.get')+'?from=' + form.from+'&to=' + form.to)).json();

      total_completed_order.value = result.data.total_completed;
      total_void_order.value = result.data.total_void;

      let sales_result = result.data.total_sales;
      total_sales.value = sales_result['gross_sales'];
      pie_graph_sales.value.push(sales_result['gross_sales']);
      pie_graph_sales.value.push(sales_result['net_sales']);
      pie_graph_sales.value.push(sales_result['tax_sales']);
      pie_graph_sales.value.push(sales_result['discount_sales']);

      Object.entries(result.data.pending.data).forEach(([key, value]) => {
          pending_order_category.value.push({
              x: key,
              y: value
          });
        });

      Object.entries(result.data.completed.data).forEach(([key, value]) => {
          completed_order_category.value.push({
              x: key,
              y: value
          });
        });

      Object.entries(result.data.void.data).forEach(([key, value]) => {
          void_order_category.value.push({
              x: key,
              y: value
          });
        });

      Object.entries(result.data.total.data).forEach(([key, value]) => {
          total_order_category.value.push({
              x: key,
              y: value
          });
        });
    }

    onMounted(() => {
      loadData();
    })

    const sales = ref({
          series: pie_graph_sales,
          chartOptions: {
            chart: {
              type: 'pie',
            },
            labels: ["Gross Sales", "Net Sales", "Sales Tax", "Discount"],
            plotOptions: {
              pie: {
                dataLabels: {
                  offset: -5
                }
              }
            },
            title: {
              text: "Sales"
            },
            dataLabels: {
              formatter(val, opts) {
                const name = opts.w.globals.labels[opts.seriesIndex]
                return [name, val.toFixed(1) + '%']
              }
            },
            legend: {
              show: false
            }
          },
    })
    
  const orders = ref({
    series: [{
      name: 'Pending',
      type: 'column',
      data: pending_order_category
    },
    {
      name: 'Completed',
      type: 'column',
      data: completed_order_category
    },
    {
      name: 'Void',
      type: 'column',
      data: void_order_category
    },
  ],
    chartOptions: {
      chart: {
        height: 350,
        type: 'line',
        stacked: false
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        width: [1, 1, 4]
      },
      title: {
        text: 'Transactions',
        align: 'left',
        offsetX: 110
      },
      xaxis: {
        categories: [],
      },
      yaxis: [
        {
          axisTicks: {
            show: false,
          },
          axisBorder: {
            show: true,
            color: '#008FFB'
          },
          labels: {
            style: {
              colors: '#008FFB',
            },
            formatter: function(val, index) {
              return val;
            }
          },
          title: {
            text: "Orders",
            style: {
              color: '#008FFB',
            }
          },
          tooltip: {
            enabled: false,

            intersect: false,
              y: {
                formatter: function (y) {
                  if (typeof y !== "undefined") {
                    return y.toFixed(0) + " points";
                  }
                  return y;
            
                }
              }
          },
        },
      ],
      tooltip: {
        fixed: {
          enabled: true,
          position: ' ', // topRight, topLeft, bottomRight, bottomLeft
          offsetY: 30,
          offsetX: 60
        },
      },
      legend: {
        horizontalAlign: 'center',
        offsetX: 40
      },
    },
  });
</script>

<template>
    <Head title="Reports" />

    <AuthenticatedLayout>
        
        <div class="py-12">
            <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4 lg:ml-60 md:ml-64 sm:px-6 lg:px-8 ">
                <SalesCard
                title="Total Sales"
                :amount="'P '+total_sales.toFixed(2)"
                icon="<div class='p-3 mr-4 text-green-500 bg-green-100 rounded-full'>
                  <svg class='w-5 h-5' fill='currentColor' viewBox='0 0 20 20'>
                    <path fill-rule='evenodd' d='M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z' clip-rule='evenodd'></path>
                  </svg>
                </div>">
              </SalesCard>
                <SalesCard
                title="Completed Orders"
                :amount="total_completed_order"
                icon="<div class='p-3 mr-4 text-blue-500 bg-blue-100 rounded-full'>
                  <svg class='w-5 h-5' fill='currentColor' viewBox='0 0 20 20'>
                    <path d='M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z'></path>
                  </svg>
                </div>">
              </SalesCard>
                <SalesCard
                title="Void/Cancelled"
                :amount="total_void_order"
                icon="<div class='p-3 mr-4 text-red-500 bg-red-100 rounded-full'>
                    <svg class='h-5 w-5 text-error' fill='none' viewBox='0 0 24 24' stroke='currentColor'>
                      <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z'></path>
                    </svg>
                </div>">
              </SalesCard>
            </div>
            <div class="flex sm:mt-0 sm:ml-4 sm:mx-5">
              <form @submit.prevent="filter">
                <div class="flex-1 min-w-0 ">
                  <div class="grid gap-4 md:grid-cols-3 xl:grid-cols-4 lg:ml-60 md:ml-64 sm:px-6 lg:px-8">
                    <div class="flex items-center">
                      <TextInput id="from" type="text" onfocus="(this.type='date')"  class="mt-1 block w-full ml-2 mr-2" placeholder="From" v-model="form.from" autofocues/>
                      <InputError class="mt-2" :message="form.errors.from" />
                    </div>
                    <div class="flex items-center">
                      <TextInput id="to" type="text" onfocus="(this.type='date')"  class="mt-1 block w-full ml-2 mr-2" placeholder="To"  v-model="form.to" autofocues/>
                      <InputError class="mt-2" :message="form.errors.to" />
                    </div>
                      <div class="flex items-center mt-1 ml-2 mr-2">
                        <PrimaryButton type="submit" >Filter</PrimaryButton>
                      </div>
                  </div>
                </div>
              </form>
            </div>

            <!-- Chart -->
            <div class="card px-4 pb-4 sm:px-5 grid gap-6 mb-8 lg:ml-60 md:ml-64">
                <div class="w-full flex items-center p-4">
                  <apexchart
                      class="w-full flex items-center p-4 rounded-md shadow-lg"
                      type="line"
                      :options="orders.chartOptions"
                      :series="orders.series"
                  ></apexchart>
                </div>
                <div class="w-full flex items-center p-4" v-if="total_sales > 0">
                  <apexchart
                      class="w-full flex items-center p-4 rounded-md shadow-lg"
                      type="pie"
                      :options="sales.chartOptions"
                      :series="sales.series"
                  ></apexchart>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

</template>
