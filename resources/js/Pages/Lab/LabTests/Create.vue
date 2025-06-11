<template>
  <Head title="Create Lab Test" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ form?.id ? "Update" : "Create" }} Lab Test
          </h1>
          <div class="text-gray-900">
            <!-- <form
              @submit.prevent="
                form?.id
                  ? form.put(route('lab-tests.update', { id: form.id }))
                  : form.post(route('lab-tests.store'), form)
              "
            > -->
            <form  @submit.prevent="
            form.processing = true;
            form?.id
                ? form.put(route('lab-tests.update', { id: form.id }))
                : form.post(route('lab-tests.store'), form).finally(() => {
                    form.processing = false;
                })
            ">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 py-6">
                    <div>
                    <label
                      for="test_category_id"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Test category
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedTestCategory"
                        :options="test_categories"
                        @select="onTestCategoriesSelect"
                        label="name"
                        track-by="id"
                        placeholder="Select test categories"
                      ></multiselect>
                    </div>
                    <InputError
                      v-if="form?.errors?.test_category_id"
                      :message="form?.errors?.test_category_id"
                    />
                  </div>
                  <div>
                    <label
                      for="name"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Name
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        id="name"
                        name="name"
                        type="text"
                        autocomplete="name"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.name"
                      />
                    <InputError
                      v-if="form?.errors?.name"
                      :message="form?.errors?.name"
                    />
                  </div>
                  <div>
                    <label
                      for="price"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Price
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        id="price"
                        name="price"
                        type="text"
                        autocomplete="price"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.price"
                      />
                    <InputError
                      v-if="form?.errors?.price"
                      :message="form?.errors?.price"
                    />
                  </div>
                  <div class="sm:col-span-3">
                    <label
                      for="description"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Description</label
                    >
                      <input
                        id="description"
                        name="description"
                        type="text"
                        autocomplete="description"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.description"
                      />
                    <InputError
                      v-if="form?.errors?.description"
                      :message="form?.errors?.description"
                    />
                  </div>
                  <div>
                    <label
                      for="sample_required"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Sample required</label
                    >
                      <input
                        id="sample_required"
                        name="sample_required"
                        type="text"
                        autocomplete="sample_required"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.sample_required"
                      />
                    <InputError
                      v-if="form?.errors?.sample_required"
                      :message="form?.errors?.sample_required"
                    />
                  </div>
                  <div>
                    <label
                      for="reporting_time"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Reporting time</label
                    >
                      <input
                        id="reporting_time"
                        name="reporting_time"
                        type="text"
                        autocomplete="reporting_time"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.reporting_time"
                      />
                    <InputError
                      v-if="form?.errors?.reporting_time"
                      :message="form?.errors?.reporting_time"
                    />
                  </div>
                  <div>
                    <label
                      for="min_value"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Min value</label
                    >
                      <textarea
                        id="min_value"
                        name="min_value"
                        type="text"
                        autocomplete="min_value"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.min_value"
                      />
                    <InputError
                      v-if="form?.errors?.min_value"
                      :message="form?.errors?.min_value"
                    />
                  </div>
                  <div>
                    <label
                      for="max_value"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Max value</label
                    >
                      <textarea
                        id="max_value"
                        name="max_value"
                        type="text"
                        autocomplete="max_value"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.max_value"
                      />
                    <InputError
                      v-if="form?.errors?.max_value"
                      :message="form?.errors?.max_value"
                    />
                  </div>
                  <div>
                    <label
                      for="value_range"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Value range</label
                    >
                      <input
                        id="value_range"
                        name="value_range"
                        type="text"
                        autocomplete="value_range"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.value_range"
                      />
                    <InputError
                      v-if="form?.errors?.value_range"
                      :message="form?.errors?.value_range"
                    />
                  </div>
                  <div>
                    <label
                      for="unit_id"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Unit</label
                    >
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedUnit"
                        :options="units"
                        @select="onUnitsSelect"
                        label="name"
                        track-by="id"
                        placeholder="Select unit"
                      ></multiselect>
                    </div>
                    <InputError
                      v-if="form?.errors?.unit_id"
                      :message="form?.errors?.unit_id"
                    />
                  </div>
                  <div>
                    <label
                      for="coa_id"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Account Head
                      <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedCoa"
                        :options="coa"
                        @update:modelValue="onCoaSelect"
                        label="acc_desc"
                        track-by="id"
                        placeholder="Select a Account Head"
                      ></multiselect>
                    </div>
                    <InputError
                      v-if="form.errors?.coa_id"
                      :message="form.errors?.coa_id"
                    />
                  </div>
                  <div>
                    <label
                      for="unit_id"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Type</label
                    >
                    <div class="mt-1">
                        <select class="rounded px-6 py-2 bg-white border border-gray-300 w-full focus:outline-none focus:ring-none"
                         id="lab_type" v-model="form.lab_type" >
                           <option value="">Select</option>
                             <option value="lab_test" :selected="form.lab_type == 'lab_test'" >LabTest</option>
                             <option value="other_receipts"  :selected="form.lab_type =='other_receipts'">Other Receipts</option>
                        </select>
                    </div>
                    <InputError
                      v-if="form?.errors?.lab_type"
                      :message="form?.errors?.lab_type"
                    />
                  </div>
                  <div>
                    <label
                      for="unit_id"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Lab TestType</label
                    >
                    <div class="mt-1">
                        <select class="rounded px-6 py-2 bg-white border border-gray-300 w-full focus:outline-none focus:ring-none"
                         id="lab_test_type" v-model="form.lab_test_type" >
                           <option value="">Select</option>
                             <option value="indoor_test" :selected="form.lab_test_type == 'indoor_test'" >Indoor Test</option>
                             <option value="outdoor_test"  :selected="form.lab_test_type =='outdoor_test'">OutDoor Test</option>
                        </select>
                    </div>
                    <InputError
                      v-if="form?.errors?.lab_test_type"
                      :message="form?.errors?.lab_test_type"
                    />
                  </div>
                  <div>
                    <div class="mt-1">
                      <SwitchGroup
                        as="div"
                        class="flex items-center justify-between"
                      >
                        <span class="flex flex-grow flex-col">
                          <SwitchLabel
                            as="span"
                            class="text-sm md:text-base font-medium text-gray-900"
                            passive
                            >Status</SwitchLabel
                          >
                          <SwitchDescription
                            as="span"
                            class="text-sm text-gray-900"
                            >Active when toggled on, Inactive when toggled
                            off.</SwitchDescription
                          >
                        </span>
                        <Switch
                          v-model="enabled"
                          :class="[
                            enabled ? 'bg-indigo-600' : 'bg-gray-200',
                            'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2',
                          ]"
                        >
                          <span
                            aria-hidden="true"
                            :class="[
                              enabled ? 'translate-x-5' : 'translate-x-0',
                              'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                            ]"
                          />
                        </Switch>
                      </SwitchGroup>
                    </div>
                    <InputError
                      v-if="form?.errors?.status"
                      :message="form?.errors?.status"
                    />
                  </div>
                </div>
                <div>
                <label class="block text-sm md:text-base font-medium text-gray-900">Test Report</label>
                <div v-for="(report, index) in form.details" :key="index"   class="grid lg:grid-cols-6 gap-2 mt-2">
<div>
                    <input
                    type="text"
                    v-model="report.test_report"
                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                    placeholder="Enter test report"
                />
            </div>
            <div class="col-span-2 gap-2 space-x-2 mt-1">

                <button
                type="button"
                @click="addTestReport"
                class=" rounded bg-green-500 px-3 py-2 text-white shadow-sm border border-green-500 hover:bg-green-600"
                >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <!-- Add More -->
                </button>
                <button
                    type="button"
                    @click="removeTestReport(index)"
                    class="rounded bg-red-500 px-3 py-2 text-white shadow-sm border border-red-500 hover:bg-red-600"
                >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
                </button>
            </div>
            </div>
        </div>
                <div class="flex items-center justify-center md:justify-end gap-4 py-4">
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('lab-tests.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  type="submit" :disabled="form.processing"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                >
                  Save
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
    <script setup>
import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import Multiselect from "vue-multiselect";
import { ref, defineComponent, watch } from "vue";
import InputError from "../../Components/InputError.vue";
import {
  Switch,
  SwitchDescription,
  SwitchGroup,
  SwitchLabel,
} from "@headlessui/vue";

const props = defineProps({
  lab_test: Object,
  test_categories: Array,
  units: Array,
  coa: Array,
});
const addTestReport = () => {
  form.details.push({
    test_report: '',
    lab_test_id: form.id || null,
    id: null,
  });
};



const removeTestReport = (index) => {
  form.details.splice(index, 1);
};
const form = useForm({
  id: props?.lab_test?.id || null,
  test_category_id: props?.lab_test?.test_category_id || null,
  name: props?.lab_test?.name || null,
  lab_type: props?.lab_test?.lab_type || null,
  description: props?.lab_test?.description || null,
  price: props?.lab_test?.price || null,
  sample_required: props?.lab_test?.sample_required || null,
  reporting_time: props?.lab_test?.reporting_time || null,
  min_value: props?.lab_test?.min_value || null,
  max_value: props?.lab_test?.max_value || null,
  value_range: props?.lab_test?.value_range || null,
  lab_test_type: props?.lab_test?.lab_test_type || null,
  unit_id: props?.lab_test?.unit_id || null,
  coa_id: props?.lab_test?.coa_id || null,
  status: props?.lab_test?.status || "active",
  details: props?.lab_test?.details && props?.lab_test?.details.length > 0 ? props?.lab_test?.details : [
    {
      id: null,
      test_report: null,
      lab_test_id: null,
    },
  ],
});



const selectedCoa = ref(null);
if (props?.lab_test) {
  const coas = props.coa.find(
    (c) => c.id === props.lab_test.coa_id
  );
  if (coas) {
    selectedCoa.value = coas;
  }
}

const onCoaSelect = async (selectedCoa) => {
  form.coa_id = selectedCoa?.id ?? null;
};


const selectedTestCategory = ref(null);
if (props?.lab_test) {
  const group = props.test_categories.find(
    (c) => c.id == props.lab_test.test_category_id
  );
  if (group) {
    selectedTestCategory.value = group;
  }
}

const onTestCategoriesSelect = async (selectedTestCategory) => {
  form.test_category_id = selectedTestCategory.id;
};



const selectedUnit = ref(null);
if (props?.lab_test) {
  const unit = props.units.find(
    (c) => c.id == props.lab_test.unit_id
  );
  if (unit) {
    selectedUnit.value = unit;
  }
}

const onUnitsSelect = async (selectedUnit) => {
  form.unit_id = selectedUnit.id;
};

const cancel = () => {
  Inertia.visit(route("lab-tests.index"));
};

const enabled = ref(
  props?.lab_test?.status && props?.lab_test?.status == "inactive"
    ? false
    : true
);

watch(enabled, (newValue) => {
  form.status = newValue ? "active" : "inactive";
});

defineComponent({
  components: {
    Multiselect,
  },
});
</script>
    <style src="vue-multiselect/dist/vue-multiselect.css"></style>
