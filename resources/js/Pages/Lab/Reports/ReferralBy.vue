<template>
    <Head title="Referral By" />
    <AppLayout title="Referral By">
      <div class="w-full mx-auto pt-6">
        <div class="bg-white shadow-sm rounded-lg">
          <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div
              class="flex flex-col md:flex-row items-start md:items-center justify-between"
            >
              <h1 class="font-semibold text-3xl text-primary">Referral By</h1>
              <div class="mt-2 ml-auto sm:mt-0">
                <form
                @submit.prevent="report.get(route('lab.referral-by'), report)"
              >
                <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-3">
                  <div>
                      <label
                        for="from_date"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Search
                      </label>
                      <div class="mt-2">
                        <input
                          id="search"
                          name="search"
                          type="text"
                          placeholder="search by doctor"
                          class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                          v-model="report.search"
                        />
                      </div>
                    </div>
                  <div>
                      <label
                        for="from_date"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >From Date
                      </label>
                      <div class="mt-2">
                        <input
                          id="from_date"
                          name="from_date"
                          type="text" ref="from_date"
                          autocomplete="from_date"
                          class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                          step="0.01"
                          v-model="report.from_date"
                        />
                      </div>
                    </div>

                    <div>
                      <label
                        for="to_date"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >To Date
                      </label>
                      <div class="mt-2">
                        <input
                          id="to_date"
                          name="to_date"
                          type="text" ref="to_date"
                          autocomplete="to_date"
                          class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                          step="0.01"
                          v-model="report.to_date"
                        />
                      </div>
                    </div>
                    <div class="mt-2">
                      <label
                        for="to_date"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >&nbsp;
                      </label>
                      <button
                    type="submit"
                    class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                    >
                      Search
                    </button>
                    <button
                    class="rounded bg-blue-700 px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-blue-700 hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 ml-2" @click="print()" v-if="reports.length > 0" >
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
                <div style="display: flex;flex-direction: column; align-items: center;justify-content: center;">
                    <img style="width:100px;object-fit: contain; margin-bottom: 16px;" alt="">
                    <p style="font-size: 24px;font-weight: 600;text-align: center;margin: 0;margin-bottom: 2px;">Chiniot
                        General Hospital</p>
                    <p style="font-size: 18px;font-weight: 500;text-align: center;margin: 0;margin-bottom: 2px;">Nawaz Town
                        Sarghoda Road, Faisalabad</p>

                    <p style="font-size: 18px;font-weight: 500;text-align: center;margin: 0;margin-bottom: 2px;">Ph:
                        041-8848060 | 8787231</p>
                </div>
                <div
                    style="display: flex;align-items: center;justify-content: space-between;flex: initial;margin-top: 20px !important;width: 100%;margin: 10px auto;">
                    <div style="width: 50%;">
                        <div style="display: flex;align-items: center;">
                            <h4 style="margin: 0;font-family: sans-serif; width: 100px;">User</h4>
                            <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                            <p style="margin: 0;margin-left: 2px;text-align: left;">All</p>
                        </div>
                    </div>
                    <div style="width: 50%;display: flex;justify-content: start;">
                        <div style="display: flex;align-items: center;">
                            <h4 style="margin: 0;font-family: sans-serif; width: 100px;">Date & Time</h4>
                            <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                            <p style="margin: 0;margin-left: 2px;text-align: left;white-space: nowrap;">{{ new Date().getDate() + '-' + (new Date().getMonth() + 1) + '-' + new Date().getFullYear() }} {{new Date().toTimeString().slice(0, 5)}}</p>
                        </div>
                    </div>
                </div>
                <div style="display: flex;align-items: center;justify-content: space-between;flex: initial;width: 100%;margin: 10px auto">
                    <div style="width: 50%;">
                        <div style="display: flex;align-items: center;">
                            <h4 style="margin: 0;font-family: sans-serif; width: 100px;">From</h4>
                            <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                            <p style="margin: 0;margin-left: 2px;text-align: left;font-weight: 500;">                            {{ new Date(from_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}
</p>
                        </div>
                    </div>
                    <div style="width: 50%;display: flex;justify-content: start;">
                        <div style="display: flex;align-items: center;">
                            <h4 style="margin: 0;font-family: sans-serif; width: 100px;">To</h4>
                            <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                            <p style="margin: 0;margin-left: 2px;text-align: left;">                            {{ new Date(to_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}
</p>
                        </div>
                    </div>
                </div>
                <div>

                    <p
                        style="font-size: 24px;font-weight: 600;text-align: center;margin: 0;margin-bottom: 20px;margin-top: 20px;">Specialist Detail</p>
                </div>
              </div>
              <div class="ring-1 ring-gray-200">
                    <table class="min-w-full border-separate border-spacing-0 divide-y divide-gray-200 overflow-auto">
                      <thead>
                        <tr class="divide-x divide-gray-200">
                          <th
                          scope="col"
                          class="bg-primary whitespace-nowrap text-center border-b border-gray-300 font-bold text_white sticky top-0 z-10"
                        >
                          Sr#
                        </th>
                        <th
                          scope="col"
                          class="bg-primary whitespace-nowrap text-center border-b border-gray-300 font-bold text_white sticky top-0 z-10"
                        >
                         Referral By
                        </th>
                        <th
                          scope="col"
                          class="bg-primary whitespace-nowrap text-center border-b border-gray-300 font-bold text_white sticky top-0 z-10"
                        >
                          Patient Name
                        </th>
                        <th
                          scope="col"
                          class="bg-primary whitespace-nowrap text-center border-b border-gray-300 font-bold text_white sticky top-0 z-10"
                        >
                         Date
                        </th>
                        <th
                          scope="col"
                          class="bg-primary whitespace-nowrap text-center border-b border-gray-300 font-bold text_white sticky top-0 z-10"
                        >
                         Test
                        </th>
                         <th
                          scope="col"
                          class="bg-primary whitespace-nowrap text-center border-b border-gray-300 font-bold text_white sticky top-0 z-10"
                        >
                         Amount
                        </th>


                    </tr>
                    </thead>
                    <tbody>

                        <tr
                            class="divide-x divide-gray-200"
                            v-for="(report, index) in reports"
                            :key="index"
                            :class="[(index % 2 === 0 ? 'bg-gray-50' : '')]"
                            >
                            <td class="whitespace-nowrap relative">
                                {{ index + 1 }}
                            </td>
                            <td class="whitespace-nowrap relative">
                                {{ report.referred_by }}
                            </td>
                            <td class="whitespace-nowrap relative">
                                {{ report.patient_name }}
                            </td>
                            <td class="whitespace-nowrap relative">
                                {{ report.date }}
                            </td>
                            <td class="whitespace-nowrap relative">
                                <div v-for="(detail, detailIndex) in report.test_details" :key="detailIndex">
                                <div v-if="detailIndex === 0 || report.test_details[detailIndex - 1].package_name !== detail.package_name">
                                    <p class="font-bold text-lg">{{ detail.package_name }}</p>
                                </div>
                                <p class="flex"><b> {{ detail.test_category }}</b> - {{ detail.lab_test_name }} </p>
                                </div>
                            </td>
                            <td class="whitespace-nowrap relative">
                                {{ formatNumber(report.payment) }}
                            </td>
                            </tr>


                    </tbody>
                    <!-- <tfoot>
                      <tr
                        class="divide-x divide-gray-200 bg-secondary text-white"
                      >
                        <td
                          class="whitespace-nowrap relative"
                          colspan="2"
                        >
                          Total
                        </td>
                        <td
                          class="whitespace-nowrap relative p-4 text-sm md:text-base"
                        >

                        </td>

                        <td
                          class="whitespace-nowrap text-center relative p-4 text-sm md:text-base"
                        >
                        {{ sum(reports, 'test_Count', 'report') }}
                        </td>
                        <td
                          class="whitespace-nowrap relative p-4 text-sm md:text-base"
                        >

                        </td>

                        <td
                          class="whitespace-nowrap text-center relative p-4 text-sm md:text-base"
                        >
                        {{ sum(reports, 'total_amount', 'report') }}
                        </td>
                      </tr>
                    </tfoot> -->
                  </table>
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
    reports: Object,
    from_date: String,
    to_date: String,
    search: String,
  });

  const report = useForm({
    from_date: props?.from_date || null,
    to_date: props?.to_date || null,
    search: props?.search || null,
  });
  onMounted(() => {

    const flatpickrOptions = (defaultDate) => ({
        dateFormat: "d-m-Y",
        allowInput: true, // Allows manual typing
        clickOpens: true, // Ensures the picker still works
        defaultDate: defaultDate || new Date(), // Use provided default date or current date
    });

    flatpickr(to_date.value, flatpickrOptions(report.to_date));
    flatpickr(from_date.value, flatpickrOptions(report.from_date));

    });
  const formatNumber = (number) => {
    return new Intl.NumberFormat().format(number);
};
//   const sum = (reports, val, type = null, check = null) => {
//     let total = 0;
//     reports.forEach(res => {
//      if (type === 'report' && res[val] !== null && res[val] !== undefined && !isNaN(res[val])) {
//         total += parseFloat(res[val]);
//       } else {
//         if (check && res[check] !== null && res[check] !== undefined && !isNaN(res[val])) {
//           total += parseFloat(res[val]);
//         }
//       }
//     });
//     return total;
//   }


  const print = () => {
    var header_str = '<html><head><title>' + document.title  + '</title></head><body>';
    var footer_str = '</body></html>';
    var new_str = document.getElementById('printData').innerHTML;
    var old_str = document.body.innerHTML;
    document.body.innerHTML = header_str + new_str + footer_str;
    window.print();

    return false;
  }


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
      <style>
          td {
              text-align: left;
              padding: 10px 8px;
              font-size: 14px;
          }

          th {
              font-size: 14px;
              font-weight: 600;
              color: black;
              padding: 4px 8px;
              text-align: center;
              height: 24px;
          }

          table {
              width: 100%;
              height: auto;
              border-collapse: collapse;
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
          table tfoot td {
              border: none;
              font-size: 14px;
              font-weight: 600;
              text-align: left;
          }
          .print-only {
              display: none;
          }

          /* CSS to show the element only when printing */
          @media print {
              .print-only {
                  display: block;
              }
              table th{
          padding: 4px;
          font-size: 12px !important;
          line-height: 12px !important;
        }
        table td{
          padding: 4px;
          font-size: 10px !important;
          line-height: 12px !important;
        }
        table tfoot td{
            padding: 4px;
            font-size: 12px !important;
            line-height: 14px !important;
          }
          .text_white{
            color: #000 !important;
          }
          }
      </style>
