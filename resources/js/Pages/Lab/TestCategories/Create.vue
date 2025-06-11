<template>
  <Head title="Create Test Category" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ form?.id ? "Update" : "Create" }} Test Category
          </h1>
          <!-- <form
              @submit.prevent="
                form?.id
                  ? form.put(route('test-categories.update', { id: form.id }))
                  : form.post(route('test-categories.store'), form)
              "
            > -->
            <form  @submit.prevent="
            form.processing = true;
            form?.id
                ? form.put(route('test-categories.update', { id: form.id }))
                : form.post(route('test-categories.store'), form).finally(() => {
                    form.processing = false;
                })
            ">
              <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 py-6">
                <div>
                  <label
                    for="name"
                    class="block text-sm md:text-base font-medium text-gray-900"
                    >Name
                      <span class="text-red-500">*</span>
                  </label>
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
                    for="test_group_id"
                    class="block text-sm md:text-base font-medium text-gray-900"
                    >Test group
                      <span class="text-red-500">*</span>
                  </label>
                  <div class="mt-1">
                    <multiselect
                      v-model="selectedTestGroup"
                      :options="test_groups"
                      @select="onTestGroupSelect"
                      label="name"
                      track-by="id"
                      placeholder="Select test group"
                    ></multiselect>
                  </div>
                  <InputError
                    v-if="form?.errors?.test_group_id"
                    :message="form?.errors?.test_group_id"
                  />
                </div>
                <div>
                  <div class="mt-2">
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
                <div class="flex items-center justify-center md:justify-end gap-4 py-4">
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('test-categories.index')"
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
  test_category: Object,
  test_groups: Array,
});

const form = useForm({
  id: props?.test_category?.id || null,
  name: props?.test_category?.name || null,
  test_group_id: props?.test_category?.test_group_id || null,
  status: props?.test_category?.status || "active",
});

const testGroups = ref(props.test_groups || []);

const selectedTestGroup = ref(null);
if (props?.test_category) {
  const group = props.test_groups.find(
    (c) => c.id == props.test_category.test_group_id
  );
  if (group) {
    selectedTestGroup.value = group;
  }
}

const onTestGroupSelect = async (selectedTestGroup) => {
  form.test_group_id = selectedTestGroup.id;
};

const cancel = () => {
  Inertia.visit(route("test-categories.index"));
};

const enabled = ref(
  props?.test_category?.status && props?.test_category?.status == "inactive"
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
