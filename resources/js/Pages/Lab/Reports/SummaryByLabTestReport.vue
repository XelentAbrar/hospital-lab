<template>

    <Head title="Lab Test Summary" />
    <AppLayout title="Lab Test Summary">
        <div class="w-full mx-auto pt-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                        <h1 class="font-semibold text-3xl text-primary">Lab Test Detail</h1>
                        <div class="mt-2 ml-auto sm:mt-0">
                            <form @submit.prevent="report.get(route('lab.report-lab-detail-summary'), report)">
                                <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-4">
                                    <div>
                                        <label for="from_date"
                                            class="block text-sm md:text-base font-medium text-gray-900">Search
                                        </label>
                                        <div class="mt-2">
                                            <input id="search" name="search" type="text" placeholder="Search a lab test"
                                                class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                                                v-model="report.search" />
                                        </div>
                                    </div>
                                    <div>
                                        <label for="from_date"
                                            class="block text-sm md:text-base font-medium text-gray-900">Doctor
                                        </label>
                                        <div class="mt-2">
                                            <multiselect v-model="selectedLabTest" :options="labTestOptions"
                                                @update:model-value="onLabTestSelect" label="name" track-by="id"
                                                placeholder="Select a Lab Test"></multiselect>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="from_date"
                                            class="block text-sm md:text-base font-medium text-gray-900">From Date
                                        </label>
                                        <div class="mt-2">
                                            <input id="from_date" name="from_date" type="text" ref="from_date"
                                                autocomplete="from_date"
                                                class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                                                v-model="report.from_date" />
                                        </div>
                                    </div>

                                    <div>
                                        <label for="to_date"
                                            class="block text-sm md:text-base font-medium text-gray-900">To Date
                                        </label>
                                        <div class="mt-2">
                                            <input id="to_date" name="to_date" type="text" ref="to_date"
                                                autocomplete="to_date"
                                                class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                                                v-model="report.to_date" />
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <label for="to_date"
                                            class="block text-sm md:text-base font-medium text-gray-900">&nbsp;
                                        </label>
                                        <button type="submit"
                                            class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                                            Search
                                        </button>
                                        <button
                                            class="rounded bg-blue-700 px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-blue-700 hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 ml-2"
                                            @click="print()" v-if="reports.length > 0">
                                            Print
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="mt-6 flow-root" id="printData">
                        <div
                            style="font-family: Arial, Helvetica, sans-serif; padding: 20px;display: flex;flex-direction: column;">
                            <div class="print-only">
                                <div class="flex items-center justify-between">
                                    <div
                                        class="flex items-center relative space-x-4 mt-2 mb-4 ml-4 pb-2 border-b-4 border-[#FFC470] w-3/5">
                                        <img class="h-auto w-16"
                                            src="/build/assets/logo-20b0503a.png" alt="" />
                                        <h1 class="doctor-info text-2xl font-bold">CHINIOT GENERAL HOSPITAL</h1>
                                        <div class="border border-[#8B322C] w-1/2 h-0.5 absolute right-0 -bottom-[3px]"></div>
                                    </div>
                                    <div class="space-y-3 ml-4 mt-2 w-2/5">
                                        <div class="flex items-center space-x-3">
                                            <div class="border border-[#FFC470] p-1 rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="w-4 h-4 text-[#FFC470]">
                                                    <path fill-rule="evenodd"
                                                        d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <p v-if="projectType === 'hms'">Ph: 041-8848060 | 8787231</p>
                                            <p v-else>Ph: 041-2619764 | 2634890</p>
                                        </div>
                                        <div class="flex items-start space-x-3">
                                            <div class="border border-[#FFC470] p-1 rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="w-4 h-4 text-[#FFC470]">
                                                    <path fill-rule="evenodd"
                                                        d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <p v-if="projectType === 'hms'">Nawaz Town Sargodha Road, Faisalabad</p>
                                            <p v-else>209 Jinnah Colony , Faisalabad</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-dashed border-2 border-gray-400 rounded px-4 mt-5">
                                    <div
                                        style="display: flex;align-items: center;justify-content: space-between;flex: initial;margin-top: 20px !important;width: 100%;margin: 10px auto;">
                                        <div style="width: 50%;">
                                            <div style="display: flex;align-items: center;">
                                                <h4
                                                    style="margin: 0;font-family: sans-serif; width: 100px;font-weight:600;font-size: 16px;">
                                                    User</h4>
                                                <h4
                                                    style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">
                                                    :</h4>
                                                <p style="margin: 0;margin-left: 2px;text-align: left;">{{
                                                    selectedDoctor
                                                        && selectedDoctor.name ? selectedDoctor?.name : 'All' }}</p>
                                            </div>
                                        </div>
                                        <div style="width: 50%;display: flex;justify-content: start;">
                                            <div style="display: flex;align-items: center;">
                                                <h4
                                                    style="margin: 0;font-family: sans-serif; width: 100px;font-weight:600;font-size: 16px;">
                                                    Date & Time</h4>
                                                <h4
                                                    style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">
                                                    :</h4>
                                                <p
                                                    style="margin: 0;margin-left: 2px;text-align: left;white-space: nowrap;">
                                                    {{ new Date().getDate() + '-' + (new Date().getMonth() + 1) + '-' +
                                                        new Date().getFullYear() }} {{ new Date().toTimeString().slice(0,
                                                        5) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        style="display: flex;align-items: center;justify-content: space-between;flex: initial;width: 100%;margin: 10px auto">
                                        <div style="width: 50%;">
                                            <div style="display: flex;align-items: center;">
                                                <h4
                                                    style="margin: 0;font-family: sans-serif; width: 100px;font-weight:600;font-size: 16px;">
                                                    From</h4>
                                                <h4
                                                    style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">
                                                    :</h4>
                                                <p
                                                    style="margin: 0;margin-left: 2px;text-align: left;font-weight: 500;">
                                                    {{ new Date(from_date).toLocaleDateString('en-GB').replace(/\//g,
                                                        '-') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div style="width: 50%;display: flex;justify-content: start;">
                                            <div style="display: flex;align-items: center;">
                                                <h4
                                                    style="margin: 0;font-family: sans-serif; width: 100px;font-weight:600;font-size: 16px;">
                                                    To</h4>
                                                <h4
                                                    style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">
                                                    :</h4>
                                                <p style="margin: 0;margin-left: 2px;text-align: left;"> {{ new
                                                    Date(to_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <p
                                        style="font-size: 24px;font-weight: 600;text-align: center;margin: 0;margin-bottom: 20px;margin-top: 20px;">
                                        General Detail By Doctor</p>
                                </div>
                            </div>
                            <div class="ring-1 ring-gray-300 overflow-x-auto overflow-y-hidden">
                                <div class="table-wrap block  overflow-y-auto forhidescrollbar">
                                <table
                                    class="min-w-full border-separate border-spacing-0 divide-y divide-gray-300 overflow-auto">
                                    <thead>
                                        <tr class="divide-x divide-gray-300">
                                            <th scope="col"
                                                class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10">
                                                Sr#
                                            </th>
                                            <th scope="col"
                                                class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10">
                                                Test Name
                                            </th>
                                            <th scope="col"
                                                class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10">
                                                Patient
                                            </th>
                                            <th scope="col"
                                                class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10">
                                                Lab Serial
                                            </th>
                                            <th scope="col"
                                                class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10">
                                                Age
                                            </th>

                                            <th scope="col"
                                                class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10">
                                                Amount
                                            </th>
                                            <th scope="col"
                                                class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10">
                                                CareOFF Fee
                                            </th>
                                            <th scope="col"
                                                class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10" v-if="hasDonationModule">
                                                Care Off
                                            </th>
                                            <th scope="col"
                                                class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10" v-if="hasDonationModule">
                                                ZF Fee
                                            </th>
                                            <th scope="col"
                                                class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10">
                                                S.D
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="(report, index) in reports">
                                            <tr class="divide-x divide-gray-300 even:bg-gray-50 odd:bg-white">
                                                <td
                                                    class="whitespace-nowrap relative border-b border-gray-300 text-center text-gray-900">
                                                    {{ index + 1 }}
                                                </td>

                                                <td
                                                    class="whitespace-nowrap relative border-b border-gray-300 text-gray-900">
                                                    <table
                                                        v-for="(test_general_detail, index) in report.patient_test_details"
                                                        :key="index">
                                                            <tr v-if="test_general_detail.package != null">
                                                                <td class="whitespace-nowrap relative text-gray-900">{{ test_general_detail.package?.name }}</td>
                                                            </tr>
                                                            <tr v-if="!test_general_detail.package_id">
                                                                <template
                                                                    v-for="(subpkg, subindex) in test_general_detail.test_general_details"
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
                                                                    <template
                                                                        v-for="(test, testIndex) in test_general_detail.test_general_details"
                                                                        :key="testIndex">
                                                                        <tr
                                                                            v-if="testIndex === 0 || test_general_detail.test_general_details[testIndex - 1].lab_test?.name !== test.lab_test?.name">
                                                                            <td class="whitespace-nowrap relative text-gray-900">{{ test.lab_test?.name }}</td>
                                                                        </tr>
                                                                    </template>
                                                                </template>
                                                            </tr>
                                                    </table>
                                                </td>
                                        <td class="whitespace-nowrap relative border-b border-gray-300 text-gray-900">
                                            {{ report?.patient_name }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap relative text-center border-b border-gray-300 text-gray-900">
                                            <span v-if="report.lab_serial === null">
                                                {{ report.test_general_details?.lab_serial }}
                                            </span>
                                            <span v-else>
                                                {{ report.lab_serial }}
                                            </span>
                                        </td>
                                        <td
                                            class="whitespace-nowrap relative text-center border-b border-gray-300 text-gray-900">
                                            {{ report?.patient_age }}
                                        </td>

                                        <td
                                            class="whitespace-nowrap relative text-center border-b border-gray-300 text-gray-900">
                                            {{ formatNumber(report?.total_amount) }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap relative text-center border-b border-gray-300 text-gray-900">
                                            {{ formatNumber(report?.donor_fee ?? 0) }}
                                        </td>
                                        <td class="whitespace-nowrap relative border-b border-gray-300 text-gray-900" v-if="hasDonationModule">
                                            {{ report?.careoff_id ? report?.careoff?.name : '' }}
                                        </td>
                                        <td class="whitespace-nowrap relative border-b border-gray-300 text-gray-900" v-if="hasDonationModule">
                                            {{ formatNumber(report?.zf_fee) }}
                                        </td>
                                        <td class="whitespace-nowrap relative border-b border-gray-300 text-gray-900">
                                            {{ formatNumber(report?.discount_value) }}
                                        </td>
                                        </tr>
</template>
</tbody>
<tfoot>
    <tr class="divide-x divide-gray-300 bg-secondary text-black">
        <td class="whitespace-nowrap relative text-left border-y border-gray-300" colspan="5">
            Total
        </td>
        <td class="whitespace-nowrap relative border-y border-gray-300">
            {{ formatNumber(sum(reports, 'total_amount', 'report')) }}
        </td>
        <td class="whitespace-nowrap relative border-y border-gray-300" v-if="hasDonationModule">
            {{ formatNumber(sum(reports, 'donor_fee', 'report')) }}

        </td>
        <td class="whitespace-nowrap relative border-y border-gray-300" >

        </td>
        <td class="whitespace-nowrap relative border-y border-gray-300" v-if="hasDonationModule">
            {{ formatNumber(sum(reports, 'zf_fee', 'report')) }}

        </td>
        <td class="whitespace-nowrap relative border-y border-gray-300">
            {{ formatNumber(sum(reports, 'discount_value', 'report')) }}

        </td>
    </tr>
</tfoot>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import { ref, watchEffect, computed, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
const from_date = ref(null);
const to_date = ref(null);
const props = defineProps({
    hasDonationModule:Boolean,
    labTests: Array,
    packages: Array,
    subPackages: Array,
    reports: Object,
    lab_test_id: String,
    from_date: String,
    to_date: String,
    search: String,
});
const projectType = import.meta.env.VITE_PROJECT_TYPE;

const formatNumber = (number) => {
    return new Intl.NumberFormat().format(number);
};

const report = useForm({
    lab_test_id: props?.lab_test_id || null,
    from_date: props?.from_date || null,
    to_date: props?.to_date || null,
    search: props?.search || null,
});
onMounted(() => {

const flatpickrOptions = (defaultDate) => ({
    enableTime: true,
    dateFormat: "d-m-Y H:i",
    allowInput: true,
    clickOpens: true,
    defaultDate: defaultDate || new Date(),
});

flatpickr(to_date.value, flatpickrOptions(report.to_date));
flatpickr(from_date.value, flatpickrOptions(report.from_date));

});
const selectedLabTest = ref(null);
if (props?.lab_test_id) {
    const labTest = props.labTests.find(
        (c) => c.id === parseInt(props?.lab_test_id)
    );
    if (labTest) {
        selectedLabTest.value = labTest;
    }
}

const labTests = ref(props.labTests || []);
const labTestOptions = computed(() => {
    return labTests.value && labTests.value.length ? labTests.value : [];
});

const onLabTestSelect = async (selectedLabTest) => {
    if (selectedLabTest) {
        report.lab_test_id = selectedLabTest.id;
    } else {
        report.lab_test_id = null;
    }
};

const sum = (reports, val) => {
    let total = 0;
    reports.map(res => {
        total = +total + +res[val]
    });
    return total;
}
const print = () => {
    var header_str = '<html><head><title>' + document.title + '</title></head><body>';
    var footer_str = '</body></html>';
    var new_str = document.getElementById('printData').innerHTML;
    var old_str = document.body.innerHTML;
    document.body.innerHTML = header_str + new_str + footer_str;
    window.print();
    window.onafterprint = () => {
        document.body.innerHTML = old_str;
        window.location.reload();
    };
    return false;
};
</script>

<script>
import Multiselect from "vue-multiselect";
export default {
    components: {
        Multiselect,
        AppLayout,
        Head,
        InertiaLink,
    },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style scoped>

    .forhidescrollbar::-webkit-scrollbar {
    display: none; /* Chrome, Safari and Opera */
    }
    table {
        width: 100%;
        height: auto;
        border-collapse: collapse;
    }

    table th, table tfoot td{
        padding: 12px 14px;
        font-size:16px;
        font-weight: 600;
        text-align: center;
    }
    table tbody td{
        padding: 10px 12px;
        font-size: 14px;
        line-height: 0;
    }
        .table_height{
          max-height: calc(100vh - 60px);
        }
    table tfoot td {
        border: none;
        font-size: 16px;
        font-weight: 600;
        text-align: center;
    }

    .print-only {
        display: none;
    }

    .text_white {
        color: #fff;
    }
    @media only screen and (min-width: 1600px) {
        table th, table tfoot td{
            padding: 14px 16px;
            font-size:18px;
        }
    }
/* CSS to show the element only when printing */
    @media print {
        .print-only {
            display: block;
        }
        ::-webkit-scrollbar {
            display: none;
        }

        table th,
        table tfoot td {
            padding: 4px;
            font-size: 10px !important;
            line-height: 10px !important;
        }

        table tbody td {
            padding: 4px;
            font-size: 8px !important;
            line-height: 0px !important;
        }

        .text_white {
            color: #000 !important;
        }
        .table_height{
          max-height: max-content;
        }
    }
</style>
