<template>

    <Head title="Critical Patients Report" />
    <AppLayout title="Critical Patients Report">
        <div class="w-full mx-auto pt-6">
            <div class="bg-white shadow-sm rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                    <h1 class="font-semibold text-3xl text-primary">
                        Critical Patients Report
                    </h1>

                    <div class="flex items-center justify-center gap-2">
                        <div class="mt-2 ml-auto sm:ml-0 sm:mt-0">
                            <input id="name" name="name" type="text" autocomplete="name"
                                class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                                step="0.01" placeholder="Search" :value="filters?.search"
                                @keyup.enter="searchItem($event.target.value, type)" />
                        </div>
                        <div class="mt-2 ml-auto sm:ml-0 sm:mt-0">
                            <input id="from_date" name="from_date" type="text" ref="from_date" autocomplete="from_date"
                                class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                                placeholder="From Date" :value="filters?.from_date" @change="fetchData" />
                        </div>
                        <div class="mt-2 ml-auto sm:ml-0 sm:mt-0">
                            <input id="to_date" name="to_date" type="text" ref="to_date" autocomplete="to_date"
                                class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                                placeholder="To Date" :value="filters?.to_date" @change="fetchData" />
                        </div>
                    </div>

                </div>
                <div class="mt-6 flow-root">
                    <div class="ring-1 ring-gray-300 overflow-x-auto overflow-y-hidden">
                      <div class="table-wrap block table_height overflow-y-auto">
                        <table
                            class="min-w-full border-separate border-spacing-0 divide-y divide-gray-300 overflow-auto">
                            <thead>
                                <tr class="divide-x divide-gray-300">
                                    <th scope="col"
                                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10">
                                        ID
                                    </th>
                                    <th scope="col"
                                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10">
                                        Date
                                    </th>
                                    <th scope="col"
                                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10">
                                        Patient name
                                    </th>
                                    <th scope="col"
                                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10">
                                        Lab no
                                    </th>
                                    <th scope="col"
                                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10">
                                        Tests
                                    </th>
                                    <th scope="col"
                                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10">
                                        Test nature
                                    </th>
                                    <th scope="col"
                                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10">
                                        Test type
                                    </th>
                                    <th scope="col"
                                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10" v-if="hasDonationModule">
                                        CareOFF
                                    </th>
                                    <th scope="col"
                                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10" v-if="hasDonationModule">
                                        ZF
                                    </th>
                                    <th scope="col"
                                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10">
                                        Total amount
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr class="divide-x divide-gray-300" v-for="(patient_test, index) in patient_tests"
                                    :key="patient_test.id"
                                    :class="patient_test.cancel == '1' ? 'bg-red-300' : (index % 2 === 0 ? 'bg-gray-50' : 'bg-white')">
                                    <td
                                        class="whitespace-nowrap relative text-gray-900 border-b border-gray-300">
                                        {{ patient_test.id }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap relative text-gray-900 border-b border-gray-300">
                                        {{ new Date(patient_test.test_date).toLocaleDateString('en-GB').replace(/\//g,
                                        '-') }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap relative text-gray-900 border-b border-gray-300">
                                        {{ patient_test.patient_name }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap relative text-gray-900 border-b border-gray-300">
                                        {{ patient_test.lab_no }}
                                    </td>
                                    <td class="break-all border-b border-gray-300">
                                        <template
                                            v-for="(test_general_detail, index) in patient_test.patient_test_details"
                                            :key="index">
                                <tr v-if="test_general_detail.package != null">
                                    <td class="whitespace-nowrap relative text-gray-900">{{ test_general_detail.package?.name }}</td>
                                </tr>

                                <template v-if="!test_general_detail.package_id">
                                    <template v-for="(subpkg, subindex) in test_general_detail.test_general_details"
                                        :key="subindex">
                                        <template v-if="subpkg.sub_package != null">
                                            <tr
                                                v-if="subindex === 0 || test_general_detail.test_general_details[subindex - 1].sub_package?.name !== subpkg.sub_package?.name">
                                                <td class="whitespace-nowrap relative text-gray-900">{{ subpkg.sub_package?.name }}</td>
                                            </tr>
                                        </template>
                                    </template>

                                    <template
                                        v-if="!test_general_detail.test_general_details.some(detail => detail.sub_package)">
                                        <template v-for="(test, testIndex) in test_general_detail.test_general_details"
                                            :key="testIndex">
                                            <tr
                                                v-if="testIndex === 0 || test_general_detail.test_general_details[testIndex - 1].lab_test?.name !== test.lab_test?.name">
                                                <td class="whitespace-nowrap relative text-gray-900">{{ test.lab_test?.name }}</td>
                                            </tr>
                                        </template>
                                    </template>
                                </template>
                        </template>


</td>
<td class="whitespace-nowrap relative text-gray-900 border-b border-gray-300">
    {{ patient_test.test_nature }}
</td>
<td class="whitespace-nowrap relative text-gray-900 border-b border-gray-300">
    {{ patient_test.test_type }}
</td>
<td class="whitespace-nowrap relative text-gray-900 border-b border-gray-300"  v-if="hasDonationModule">
    {{ patient_test.donor_fee }}
</td>
<td class="whitespace-nowrap relative text-gray-900 border-b border-gray-300" v-if="hasDonationModule">
    {{ patient_test.zf_fee }}
</td>
<td class="whitespace-nowrap relative text-gray-900 border-b border-gray-300">
    {{ patient_test.status }}
</td>
<td class="whitespace-nowrap relative text-gray-900 border-b border-gray-300">
    {{ formatNumber(patient_test.total_amount) }}
</td>

</tr>
</tbody>
<tfoot>
                      <tr
                        class="divide-x divide-gray-300 bg-secondary text-white"
                      >
                        <td colspan="8"
                          class="whitespace-nowrap relative font-bold border-t border-gray-300"

                        >
                          Total
                        </td>

                        <td
                          class="whitespace-nowrap relative font-bold border-t border-gray-300"
                         v-if="hasDonationModule">
                        {{ formatNumber(sum(patient_tests, 'donor_fee', 'report')) }}

                        </td>
                         <td
                          class="whitespace-nowrap font-bold relative border-t border-gray-300"
                         v-if="hasDonationModule">
                        {{ formatNumber(sum(patient_tests, 'zf_fee', 'report')) }}

                        </td>

                        <td
                          class="whitespace-nowrap font-bold text-center relative border-t border-gray-300"
                        >
                        {{ formatNumber(sum(patient_tests, 'total_amount', 'report')) }}
                        </td>
                      </tr>
                    </tfoot>
            </table>
            </div>
            </div>
            </div>
            </div>
            </div>
</AppLayout>
</template>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link as InertiaLink, router } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { ref ,onMounted} from "vue";
import Swal from 'sweetalert2';
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
const from_date = ref(null);
const to_date = ref(null);
    const props = defineProps({
        hasDonationModule: Boolean,
        patient_tests: Array,
        role: Object,
        filters: Object,
  });
  const formatNumber = (number) => {
    return new Intl.NumberFormat().format(number);
};
  const sum = (patient_tests, val, type = null, check = null) => {
    let total = 0;
    patient_tests.forEach(res => {
     if (type === 'report' && res[val] !== null && res[val] !== undefined && !isNaN(res[val])) {
        total += parseFloat(res[val]);
      } else {
        if (check && res[check] !== null && res[check] !== undefined && !isNaN(res[val])) {
          total += parseFloat(res[val]);
        }
      }
    });
    return total;
  }
const searchItem = async (val) => {
    try {
        router.visit(
            route("patient-tests.index", {
                search: val,
            }),
            {
                preserveState: true,
                preserveScroll: true,
            }
        );
    } catch (error) {
        console.error("Error fetching patients:", error);
    }
};

const fetchData = () => {
    const fromDate = document.getElementById('from_date').value;
    const toDate = document.getElementById('to_date').value;

    getData(fromDate, toDate);
};

const getData = async (fromDate, toDate) => {
    try {
        router.visit(
            route('lab.critical-report', {
                from_date: fromDate,
                to_date: toDate,
            }),
            {
                preserveState: true,
                preserveScroll: true,
            }
        );
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};
onMounted(() => {

const flatpickrOptions = (defaultDate) => ({
    enableTime: true,
    dateFormat: "d-m-Y H:i",
    allowInput: true, // Allows manual typing
    clickOpens: true, // Ensures the picker still works
    defaultDate: defaultDate || new Date(), // Use provided default date or current date
});

flatpickr(to_date.value, flatpickrOptions(props.filters?.to_date));
flatpickr(from_date.value, flatpickrOptions(props.filters?.from_date));

});
</script>

<script>
export default {

    components: {
        AppLayout,
        Head,
        InertiaLink,
    },
};
</script>

<style>
        table {
            width: 100%;
            height: auto;
            border-collapse: collapse;
        }
        table tfoot tr {
            border: 1px solid;
        }
        .print-only {
            display: none;
        }
        table th, table tfoot td{
          padding: 12px 14px;
          font-size:16px;
        }
        table td{
          padding: 10px 12px;
          font-size: 14px;
        }
        @media only screen and (min-width: 1600px) {
          table th, table tfoot td{
            padding: 14px 16px;
            font-size:18px;
          }
        }
        .text_white{
          color: #fff;
        }
        .table_height{
          max-height: calc(100vh - 40px);
        }
        .whitespace_nowrap{
          white-space: nowrap;
        }
        /* CSS to show the element only when printing */
        @media print {
            .print-only {
                display: block;
            }
            table th, table tfoot td{
          padding: 4px;
          font-size: 12px !important;
          line-height: 12px !important;
          }
          table td{
          padding: 4px;
          font-size: 10px !important;
          line-height: 12px !important;
          }
          .text_white{
            color: #000 !important;
          }
          .table_height{
            max-height: max-content;
          }
          ::-webkit-scrollbar {
            display: none;
          }
        .whitespace_nowrap{
          white-space: normal !important;
        }
        }
    </style>

