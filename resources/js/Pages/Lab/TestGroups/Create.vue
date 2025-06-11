<template>
  <Head title="Create Test Group" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ form?.id ? "Update" : "Create" }} Test Group
          </h1>
          <!-- <form
              @submit.prevent="
                form?.id
                  ? form.put(route('test-groups.update', { id: form.id }))
                  : form.post(route('test-groups.store'), form)
              "
            > -->
            <form  @submit.prevent="
            form.processing = true;
            form?.id
                ? form.put(route('test-groups.update', { id: form.id }))
                : form.post(route('test-groups.store'), form).finally(() => {
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
                  <div class="sm:col-span-2 pl-4">
                    <div class="mt-4">
                      <SwitchGroup
                        as="div"
                        class="flex items-center justify-between"
                      >
                        <span class="flex flex-grow flex-col">
                          <SwitchLabel
                            as="span"
                            class="text-sm md:text-base font-medium text-gray-900"
                            passive
                            >Status
                            </SwitchLabel
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
                  :href="route('test-groups.index')"
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
  test_group: Object,
});

const form = useForm({
  id: props?.test_group?.id || null,
  name: props?.test_group?.name || null,
  status: props?.test_group?.status || "active",
});

const cancel = () => {
  Inertia.visit(route("test-groups.index"));
};

const enabled = ref(props?.test_group?.status  && props?.test_group?.status == 'inactive' ? false : true);

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
