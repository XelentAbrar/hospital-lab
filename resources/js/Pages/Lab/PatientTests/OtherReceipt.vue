<template>
    <Head title="Other Receipts" />
    <AppLayout title="Other Receipts">
      <div class="w-full mx-auto pt-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
            <h1 class="font-semibold text-3xl text-primary">
              Other Receipts
            </h1>

          <div class="flex items-center gap-2">
              <div class="mt-2 ml-auto sm:ml-0 sm:mt-0">
                <input
                    id="name"
                    name="name"
                    type="text"
                    autocomplete="name"
                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                    step="0.01"
                    placeholder="Search"
                    :value="filters?.search"
                    @keyup.enter="searchItem($event.target.value, type)"
                  />
            </div>
              <div class="mt-2 ml-auto sm:ml-0 sm:mt-0">
                  <input
                      id="from_date"
                      name="from_date"
                      type="date"
                      autocomplete="from_date"
                      class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                      placeholder="From Date"
                      :value="filters?.from_date"
                      @change="fetchData"
                  />
              </div>
              <div class="mt-2 ml-auto sm:ml-0 sm:mt-0">
                  <input
                      id="to_date"
                      name="to_date"
                      type="date"
                      autocomplete="to_date"
                      class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                      placeholder="To Date"
                      :value="filters?.to_date"
                      @change="fetchData"
                  />
              </div>
          </div>

            <inertia-link
              :href="route('patient-tests.create')"
              class="block rounded bg-primary w-fit px-4 py-2 text-center text-sm md:text-base font-medium text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer"
              >Create Other Receipt</inertia-link
            >
          </div>
          <div class="mt-6 flow-root">
            <div class="ring-1 ring-gray-200 sm:rounded-lg overflow-x-auto overflow-y-hidden">
              <table class="min-w-full border-separate border-spacing-0 divide-y divide-gray-200 overflow-auto">
                <thead>
                  <tr class="divide-x divide-gray-200">
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      ID
                    </th> <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      User
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      Date
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      Test
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      Patient name
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      Lab no
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      Test nature
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      Test type
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      Status
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      Total amount
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                    >
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    class="divide-x divide-gray-200"
                    v-for="(patient_test, index) in patient_tests.data"
                    :key="patient_test.id"
                    :class="patient_test.cancel == '1' ? 'bg-red-300' : (index % 2 === 0 ? 'bg-gray-50' : 'bg-white')"
                  >
                    <td
                      class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                    >
                      {{ patient_test.id }}
                    </td>
                    <td
                      class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                    >
                      {{ patient_test?.creator?.name }}
                    </td>
                     <td
                      class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                    >
                    {{ new Date(patient_test.test_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}
                  </td>
                  <td class="break-all w-64">
                                        <template
                                            v-for="(test_general_detail, index) in patient_test.patient_test_details"
                                            :key="index">
                                <tr v-if="test_general_detail.package != null">
                                    <td>{{ test_general_detail.package?.name }}</td>
                                </tr>

                                <template v-if="!test_general_detail.package_id">
                                    <template v-for="(subpkg, subindex) in test_general_detail.test_general_details"
                                        :key="subindex">
                                        <template v-if="subpkg.sub_package != null">
                                            <tr
                                                v-if="subindex === 0 || test_general_detail.test_general_details[subindex - 1].sub_package?.name !== subpkg.sub_package?.name">
                                                <td>{{ subpkg.sub_package?.name }}</td>
                                            </tr>
                                        </template>
                                    </template>

                                    <template
                                        v-if="!test_general_detail.test_general_details.some(detail => detail.sub_package)">
                                        <template v-for="(test, testIndex) in test_general_detail.test_general_details"
                                            :key="testIndex">
                                            <tr
                                                v-if="testIndex === 0 || test_general_detail.test_general_details[testIndex - 1].lab_test?.name !== test.lab_test?.name">
                                                <td>{{ test.lab_test?.name }}</td>
                                            </tr>
                                        </template>
                                    </template>
                                </template>
</template>


</td>
                    <td
                      class="whitespace-nowrap text-left relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                    >
                      {{ patient_test.patient_name }}
                    </td>
                    <td
                      class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                    >
                      {{ patient_test.lab_no }}
                    </td>
                    <td
                      class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                    >
                      {{ patient_test.test_nature }}
                    </td>
                    <td
                      class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                    >
                      {{ patient_test.test_type }}
                    </td>
                    <td
                      class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                    >
                      {{ patient_test.status }}
                    </td>
                    <td
                      class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                    >
                      {{ patient_test.total_amount }}
                    </td>
                    <td class="relative whitespace-nowrap border-b border-gray-200 py-2 px-4">
                      <div class="flex items-center justify-center space-x-3">
                        <!-- <span
                            v-if="patient_test.cancel == '0'"
                            class="cursor-pointer"
                            @click="cancelReceipt(patient_test.id)"
                          >
                            <svg data-slot="icon" fill="none" class="h-6 w-6 text-red-600" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                          </span> -->
                      <InertiaLink
                        :href="route('patient-tests.edit', patient_test.id)"
                        >
                        <!-- <svg
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                          class="w-5 lg:w-6 h-5 lg:h-6 text-primary hover:text-accent"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                          />
                        </svg> -->
                        <div class="block rounded bg-primary w-fit px-4 py-2 text-center text-sm md:text-base font-medium text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer">
                        Result</div>
                      </InertiaLink>
                      <button v-if="role && role.role_id === 1"
                        @click.prevent="deletePatientTest(patient_test.id)"
                        type="button"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                          class="w-5 lg:w-6 h-5 lg:h-6 text-red-600 hover:text-red-900"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                          />
                        </svg>
                      </button>
                      <button @click="printPatientTest(patient_test)"  v-if="role && role.role_id === 1">
                        <svg class="w-5 lg:w-6 h-5 lg:h-6 text-primary hover:text-accent" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                        </svg>
                      </button>
                      <button @click="printDetailPatientTest(patient_test)" class="block rounded bg-primary w-fit px-4 py-2 text-center text-sm md:text-base font-medium text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer">
                        Print
                      </button>
                      <input type="checkbox" class="px-2 py-2 w-8 h-8 rounded-md"   v-model="patient_test.bloodTestConsent" >
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- <div class="pagination-container py-4 flex justify-between px-2">
                        <div class="pagination-info flex ">
                              Page <span class="font-semibold px-1 text-gray-900 report">{{ patient_tests.current_page }}</span> of
                                <span class="font-semibold px-1 text-gray-900 report">{{ patient_tests.last_page }}</span>
                              <div >
                                <p class="pagination-info">
                                  Showing
                                  <span class="font-semibold text-gray-900 report">{{ patient_tests.from }}</span>
                                  to
                                  <span class="font-semibold text-gray-900 report">{{ patient_tests.to }}</span>
                                  of
                                  <span class="font-semibold text-gray-900 report">{{ patient_tests.total }}</span>
                                  entries
                                </p>
                              </div>
                            </div>

                        <div class="pagination-boxes">
                              <template v-if="patient_tests.prev_page_url">
                                  <InertiaLink
                                      :key="'prev'"
                                      :href="patient_tests.prev_page_url"
                                      class="pagination-box"
                                  >
                                      <span>Previous</span>
                                  </InertiaLink>
                              </template>

                              <template v-for="page in patient_tests.last_page">
                                  <InertiaLink
                                      :key="`page-${page}`"
                                      v-if="page === patient_tests.current_page"
                                      :href="
                                          route('patient-tests.index', {
                                              page: page,

                                          })
                                      "
                                      class="pagination-box current"
                                  >
                                      <span>{{ page }}</span>
                                  </InertiaLink>
                                  <InertiaLink
                                      :key="`page-${page}`"
                                      v-else
                                      :href="
                                          route('patient-tests.index', {
                                              page: page,

                                          })
                                      "
                                      class="pagination-box"
                                  >
                                      <span>{{ page }}</span>
                                  </InertiaLink>
                              </template>
                              <template v-if="patient_tests.next_page_url">
                                  <InertiaLink
                                      :key="'next'"
                                      :href="patient_tests.next_page_url"
                                      class="pagination-box"
                                  >
                                      <span>Next</span>
                                  </InertiaLink>
                              </template>
                          </div>


            </div> -->
            <div class="pagination-container py-4 flex justify-between px-4">
                              <div class="pagination-info flex">
                                  Page
                                  <span class="font-semibold px-2 text-gray-900 report">{{
                                      patient_tests.current_page }}</span>
                                  of
                                  <span class="font-semibold px-2 text-gray-900 report">{{ patient_tests.last_page
                                      }}</span>
                                  <div>
                                      <p class="pagination-info">
                                          Showing
                                          <span class="font-semibold px-1  text-gray-900 report">{{
                                              patient_tests.from }}</span>
                                          to
                                          <span class="font-semibold px-1  text-gray-900 report">{{
                                              patient_tests.to }}</span>
                                          of
                                          <span class="font-semibold px-1  text-gray-900 report">{{
                                              patient_tests.total }}</span>
                                          entries
                                      </p>
                                  </div>
                              </div>
                              <div class="pagination-links">
                                  <template v-if="patient_tests.links">
                                      <template v-for="(link, index) in patient_tests.links" :key="index">
                                          <InertiaLink v-if="link.url" :href="link.url" v-html="link.label"
                                              :class="['pagination-box', { 'pagination-box-current': link.active }]">
                                          </InertiaLink>
                                      </template>
                                  </template>
                              </div>
                          </div>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>
  <script setup>
  import AppLayout from "@/Layouts/AppLayout.vue";
  import { Head, Link as InertiaLink ,router } from "@inertiajs/vue3";
  import { Inertia } from "@inertiajs/inertia";
  import { ref } from "vue";
  import Swal from 'sweetalert2';
  const searchItem = async (val) => {
    try {
      router.visit(
          route( "patient-tests.other_receipt" , {
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
  const deletePatientTest = (id) => {

  Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
          if (result.isConfirmed) {
              Inertia.delete(route("patient-tests.destroy", id));
          }
      });
  };
  const bloodTest = ref(false);
  const cancelReceipt = (id) => {
  Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
          if (result.isConfirmed) {
              router.post(route("patient-tests.cancelReceipt", id));
          }
      });
  };

  const printPatientTest = (patientTest) => {
    const url = route('patient-tests.print', patientTest.id);
    const printWindow = window.open(url, '_blank');
    setTimeout(() => {
      printWindow.focus();
    }, 500);
  };
  const fetchData = () => {
    const fromDate = document.getElementById('from_date').value;
    const toDate = document.getElementById('to_date').value;

    getData(fromDate, toDate);
  };

  const getData = async (fromDate, toDate) => {
    try {
      router.visit(
        route('patient-tests.other_receipt', {
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


  // const printDetailPatientTest = (patientTest) => {
  //   const url = route('patient-tests.detail-print', patientTest.id);
  //   const printWindow = window.open(url, '_blank');
  //   setTimeout(() => {
  //     printWindow.focus();
  //     printWindow.print();
  //   }, 500);
  // };
  const printDetailPatientTest = (patientTest) => {
      const bloodTestConsent = patientTest.bloodTestConsent ? 1 : 0;
      const url = route('patient-tests.detail-print', { id: patientTest.id }) + `?bloodTestConsent=${bloodTestConsent}`;
    const printWindow = window.open(url, '_blank');
    setTimeout(() => {
      printWindow.focus();
    }, 500);
  };


  </script>

  <!-- <script setup>
  import AppLayout from "@/Layouts/AppLayout.vue";
  import { Head, Link as InertiaLink } from "@inertiajs/vue3";
  import { Inertia } from "@inertiajs/inertia";

  const deletePatientTest = (id) => {
    if (confirm("Are you sure you want to delete this patient test?")) {
      return Inertia.delete(route("patient-tests.destroy", id));
    }
  };

  const printPatientTest = (patient_test) => {
    const url = route('patient-tests.print', patient_test.id);
    const printWindow = window.open(url, '_blank');
    setTimeout(function(){
    printWindow.focus();
    printWindow.print();
    }, 500);
  };

  const printDetailPatientTest = (patient_test) => {
    const url = route('patient-tests.detail-print', patient_test.id);
    const printWindow = window.open(url, '_blank');
    setTimeout(function(){
    printWindow.focus();
    printWindow.print();
  };
  </script> -->
  <script>
  export default {
    props: {
      patient_tests: Array,
      role: Object,
      filters: Object,
    },
    components: {
      AppLayout,
      Head,
      InertiaLink,
    },
  };
  </script>
  <style scoped>
  .pagination-box {
      padding: 10px 16px;
      border: 1px solid #ccc;
      border-radius: 0.25rem;
      margin: 0 0.25rem;
      text-decoration: none;
  }

  .pagination-box-current {
      background-color: #6D4C41;
      color: #fff;
  }

  .pagination-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 1rem;
  }

  .pagination-info {
      display: flex;
      align-items: center;
  }

  .pagination-links {
      display: flex;
  }
  </style>
