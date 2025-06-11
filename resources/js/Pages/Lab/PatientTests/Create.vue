<template>

    <Head title="Create Patient Test" />
    <AppLayout>
        <div class="w-full pt-6">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                    <h1 class="font-semibold text-3xl text-primary">
                        {{ patient_test?.id ? "Update" : "Create" }} Patient Test
                    </h1>
                    <div class="text-gray-900">
                        <form class="pt-2" enctype="multipart/form-data">
                            <div v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                <h2 class="text-2xl font-medium leading-6 text-gray-900">
                                    Patient test
                                </h2>
                                <p class="mt-1 text-sm text-gray-900">
                                    This information will be displayed publicly
                                    so be careful what you share.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-7 py-6">
                                <div
                                    v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                    <label for="patient_id"
                                        class="block text-sm md:text-base font-medium text-gray-900">Patient</label>
                                    <multiselect class="mt-1" v-model="selectedPatient" :options="patients"
                                        @select="onPatientSelect" label="name" track-by="id"
                                        placeholder="Select a Patient"></multiselect>
                                    <InputError v-if="patient_test?.errors?.patient_id" :message="patient_test?.errors?.patient_id
                                        " />
                                </div>

                                <div
                                    v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                    <label for="test_type"
                                        class="block text-sm md:text-base font-medium text-gray-900">Test type
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <multiselect class="mt-1" v-model="selectedTestType" :options="testTypeOptions"
                                        @select="onTestTypeSelect" label="label" track-by="value"
                                        placeholder="Select test type"></multiselect>
                                    <InputError v-if="patient_test?.errors?.test_type" :message="patient_test?.errors?.test_type
                                        " />
                                </div>
                                <div
                                    v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                    <label for="test_date"
                                        class="block text-sm md:text-base font-medium text-gray-900">Date</label>
                                    <input type="date" name="test_date" id="test_date" autocomplete="test_date"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="patient_test.test_date" />
                                    <InputError v-if="patient_test?.errors?.test_date" :message="patient_test?.errors?.test_date
                                        " />
                                </div>
                                <div>
                                    <label for="lab_no" class="block text-sm md:text-base font-medium text-gray-900">MR Number.
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" step="0.01" name="mr_number" id="mr_number" @input="generateMRNumber"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        :value="patient_test.mr_number" />
                                    <InputError v-if="patient_test?.errors?.mr_number"
                                        :message="patient_test?.errors?.mr_number" />
                                </div>
                                <div v-if="patient_test?.id">
                                    <label for="lab_no" class="block text-sm md:text-base font-medium text-gray-900">Lab
                                        no
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="lab_no" id="lab_no" readonly
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="patient_test.lab_no" />
                                    <InputError v-if="patient_test?.errors?.lab_no"
                                        :message="patient_test?.errors?.lab_no" />
                                </div>
                                <div>
                                    <label for="chart_no"
                                        class="block text-sm md:text-base font-medium text-gray-900">Chart no
                                    </label>
                                    <input type="number" name="chart_no" id="chart_no"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="patient_test.chart_no" />
                                    <InputError v-if="patient_test?.errors?.chart_no" :message="patient_test?.errors?.chart_no
                                        " />
                                </div>
                                <div >
                                    <label for="referred_by"
                                        class="block text-sm md:text-base font-medium text-gray-900">Referred by
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="referred_by" id="referred_by"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="patient_test.referred_by" />
                                    <InputError v-if="patient_test?.errors?.referred_by" :message="patient_test?.errors?.referred_by
                                        " />
                                </div>
                            </div>

                            <div class="py-2">
                                <h2 class="text-2xl font-medium leading-6 text-gray-900">
                                    Patient detail
                                </h2>
                                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-7">
                                    <div>
                                        <label for="patient_name"
                                            class="block text-sm md:text-base font-medium text-gray-900">Name
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <input id="patient_name" name="patient_name" type="text" autocomplete="name"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            step="0.01" v-model="patient_test.patient_name" :disabled="patient_test?.id" />
                                        <InputError v-if="
                                            patient_test.errors
                                                ?.patient_name
                                        " :message="patient_test.errors
                                            ?.patient_name
                                            " />
                                    </div>
                                    <div>
                                        <label for="patient_phone"
                                            class="block text-sm md:text-base font-medium text-gray-900">Phone
                                            <span class="text-red-500">*</span> </label>
                                        <input id="patient_phone" name="patient_phone" type="text" autocomplete="phone"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            v-model="patient_test.patient_phone"  :disabled="patient_test?.id" />
                                        <InputError v-if="
                                            patient_test.errors
                                                ?.patient_phone
                                        " :message="patient_test.errors
                                            ?.patient_phone
                                            " />
                                    </div>
                                    <div>
                                        <label for="patient_age"
                                            class="block text-sm md:text-base font-medium text-gray-900">Patient Age</label>
                                        <input id="patient_age" name="patient_age" type="text"
                                            autocomplete="patient_age"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            step="0.01" v-model="patient_test.patient_age" :disabled="patient_test?.id" />
                                        <InputError v-if="
                                            patient_test.errors?.patient_age
                                        " :message="patient_test.errors?.patient_age
                                            " />
                                    </div>
                                    <div>
                                        <label for="lab_incharge"
                                            class="block text-sm md:text-base font-medium text-gray-900">Lab
                                            Incharge</label>
                                        <input id="lab_incharge" name="lab_incharge" type="text"
                                            autocomplete="lab_incharge"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            v-model="patient_test.lab_incharge" />
                                        <InputError v-if="
                                            patient_test.errors
                                                ?.lab_incharge
                                        " :message="patient_test.errors
                                            ?.lab_incharge
                                            " />
                                    </div>

                                    <div
                                        v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                        <label for="patient_cnic"
                                            class="block text-sm md:text-base font-medium text-gray-900">CNIC

                                        </label>
                                        <input id="patient_cnic" name="patient_cnic" type="text"
                                            autocomplete="patient_cnic"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            step="0.01" v-model="patient_test.patient_cnic" />
                                        <InputError v-if="
                                            patient_test.errors
                                                ?.patient_cnic
                                        " :message="patient_test.errors
                                            ?.patient_cnic
                                            " />
                                    </div>

                                    <div v-if="(props.roles?.role_id === 1)">
                                        <label for="patient_gender"
                                            class="block text-sm md:text-base font-medium text-gray-900">Gender</label>
                                        <div class="mt-1 flex rounded-md shadow-sm">
                                            <multiselect v-model="selectedGender" :options="genderOptions"
                                                @select="onGenderSelect" label="label" track-by="value"
                                                placeholder="Select Gender"></multiselect>
                                        </div>
                                        <InputError v-if="
                                            patient_test.errors
                                                ?.patient_gender
                                        " :message="patient_test.errors
                                            ?.patient_gender
                                            " />
                                    </div>
                                    <div
                                        v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                        <label for="patient_relation_name"
                                            class="block text-sm md:text-base font-medium text-gray-900">Relation
                                            name</label>
                                        <input id="patient_relation_name" name="patient_relation_name" type="text"
                                            autocomplete="patient_relation_name"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            step="0.01" v-model="patient_test.patient_relation_name
                                                " />
                                        <InputError v-if="
                                            patient_test.errors
                                                ?.patient_relation_name
                                        " :message="patient_test.errors
                                            ?.patient_relation_name
                                            " />
                                    </div>
                                    <div
                                        v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                        <label for="patient_relation_type"
                                            class="block text-sm md:text-base font-medium text-gray-900">Relation
                                            type</label>
                                        <div class="mt-1 flex rounded-md shadow-sm">
                                            <multiselect v-model="selectedRelationType" :options="relationTypeOptions"
                                                @select="onRelationTypeSelect" label="label" track-by="value"
                                                placeholder="Select relation type"></multiselect>
                                        </div>
                                        <InputError v-if="
                                            patient_test.errors
                                                ?.patient_relation_type
                                        " :message="patient_test.errors
                                            ?.patient_relation_type
                                            " />
                                    </div>

                                    <div
                                        v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                        <label for="patient_address"
                                            class="block text-sm md:text-base font-medium text-gray-900">Address</label>
                                        <input id="patient_address" name="patient_address" type="text"
                                            autocomplete="patient_address"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            v-model="patient_test.patient_address
                                                " />
                                        <InputError v-if="
                                            patient_test.errors
                                                ?.patient_address
                                        " :message="patient_test.errors
                                            ?.patient_address
                                            " />
                                    </div>
                                    <div
                                        v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                        <label for="patient_country_id"
                                            class="block text-sm md:text-base font-medium text-gray-900">Country</label>
                                        <div class="mt-1">
                                            <multiselect v-model="selectedCountry" :options="countryOptions"
                                                @select="onCountrySelect" label="name" track-by="id"
                                                placeholder="Select a Country"></multiselect>
                                        </div>
                                        <InputError v-if="
                                            patient_test?.errors
                                                ?.patient_country_id
                                        " :message="patient_test?.errors
                                            ?.patient_country_id
                                            " />
                                    </div>
                                    <div
                                        v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                        <label for="patient_state_id"
                                            class="block text-sm md:text-base font-medium text-gray-900">State</label>
                                        <div class="mt-1">
                                            <multiselect v-model="selectedState" :options="stateOptions"
                                                @select="onStateSelect" label="name" track-by="id"
                                                placeholder="Select a State"></multiselect>
                                        </div>
                                        <InputError v-if="
                                            patient_test?.errors
                                                ?.patient_state_id
                                        " :message="patient_test?.errors
                                            ?.patient_state_id
                                            " />
                                    </div>
                                    <div
                                        v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                        <label for="patient_city_id"
                                            class="block text-sm md:text-base font-medium text-gray-900">City</label>
                                        <div class="mt-1">
                                            <multiselect v-model="selectedCity" :options="cityOptions"
                                                @select="onCitySelect" label="name" track-by="id"
                                                placeholder="Select a City"></multiselect>
                                        </div>
                                        <InputError v-if="
                                            patient_test?.errors
                                                ?.patient_city_id
                                        " :message="patient_test?.errors
                                            ?.patient_city_id
                                            " />
                                    </div>

                                    <div
                                        v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                        <label for="patient_email"
                                            class="block text-sm md:text-base font-medium text-gray-900">Email</label>
                                        <input id="patient_email" name="patient_email" type="email" autocomplete="email"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            v-model="patient_test.patient_email" />
                                        <InputError v-if="
                                            patient_test.errors
                                                ?.patient_email
                                        " :message="patient_test.errors
                                            ?.patient_email
                                            " />
                                    </div>
                                    <div
                                        v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                        <label for="patient_blood_group"
                                            class="block text-sm md:text-base font-medium text-gray-900">Blood
                                            Group</label>
                                        <multiselect class="mt-1" v-model="selectedBloodGroup"
                                            :options="bloodGroupOptions" @select="onBloodGroupSelect" label="type"
                                            track-by="type" placeholder="Select Blood Group"></multiselect>
                                        <InputError v-if="
                                            patient_test?.errors
                                                ?.patient_blood_group
                                        " :message="patient_test?.errors
                                            ?.patient_blood_group
                                            " />
                                    </div>
                                    <div
                                        v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                        <label for="patient_rh_factor"
                                            class="block text-sm md:text-base font-medium text-gray-900">RH
                                            Factor</label>
                                        <input id="patient_rh_factor" name="patient_rh_factor" type="text"
                                            autocomplete="patient_rh_factor"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            v-model="patient_test.patient_rh_factor
                                                " />
                                        <InputError v-if="
                                            patient_test.errors
                                                ?.patient_rh_factor
                                        " :message="patient_test.errors
                                            ?.patient_rh_factor
                                            " />
                                    </div>
                                    <div
                                        v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                        <label for="specimen"
                                            class="block text-sm md:text-base font-medium text-gray-900">Specimen</label>
                                        <input id="specimen" name="specimen" type="text" autocomplete="specimen"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            v-model="patient_test.specimen" />
                                        <InputError v-if="patient_test.errors?.specimen" :message="patient_test.errors?.specimen
                                            " />
                                    </div>
                                    <div>
                                        <label for="date_sample"
                                            class="block text-sm md:text-base font-medium text-gray-900">Date/Time:Sample</label>
                                        <input id="date_sample" name="date_sample" type="datetime-local"
                                            autocomplete="date_sample"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            v-model="patient_test.date_sample" />
                                        <InputError v-if="
                                            patient_test.errors?.date_sample
                                        " :message="patient_test.errors?.date_sample
                                            " />
                                    </div>
                                    <div v-if="(props.roles?.role_id === 1)">
                                        <label for="date_report_ready"
                                            class="block text-sm md:text-base font-medium text-gray-900">Date/Time:Report
                                            Ready</label>
                                        <input id="date_report_ready" name="date_report_ready" type="datetime-local"
                                            autocomplete="date_report_ready"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            v-model="patient_test.date_report_ready" />
                                        <InputError v-if="
                                            patient_test.errors
                                                ?.date_report_ready
                                        " :message="patient_test.errors
                                            ?.date_report_ready
                                            " />
                                    </div>
                                    <div>
                                        <label for="date_report_collection"
                                            class="block text-sm md:text-base font-medium text-gray-900">Date/Time:Report
                                            Collection</label>
                                        <input id="date_report_collection" name="date_report_collection"
                                            type="datetime-local" autocomplete="date_report_collection"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            v-model="patient_test.date_report_collection
                                                " />
                                        <InputError v-if="
                                            patient_test.errors
                                                ?.date_report_collection
                                        " :message="patient_test.errors
                                            ?.date_report_collection
                                            " />
                                    </div>
                                    <div v-if="(props.roles?.role_id === 1)">
                                        <label for="date_report_verification"
                                            class="block text-sm md:text-base font-medium text-gray-900">Date/Time:Report
                                            Verification</label>
                                        <input id="date_report_verification" name="date_report_verification"
                                            type="datetime-local" autocomplete="date_report_verification"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            v-model="patient_test.date_report_verification
                                                " />
                                        <InputError v-if="
                                            patient_test.errors
                                                ?.date_report_verification
                                        " :message="patient_test.errors
                                            ?.date_report_verification
                                            " />
                                    </div>

                                    <div
                                        v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                        <label for="card-expiration-date"
                                            class="block text-sm md:text-base font-medium text-gray-900">Doctor
                                        </label>
                                        <div class="mt-1">
                                            <multiselect v-model="selectedDoctor" :options="doctorOptions"
                                                @update:modelValue="onDoctorSelect
                                                    " label="name" track-by="id" placeholder="Select a Doctor">
                                            </multiselect>
                                        </div>
                                        <InputError v-if="
                                            patient_test?.errors?.doctor_id
                                        " :message="patient_test?.errors?.doctor_id
                                            " />
                                    </div>
                                    <div>
                                        <label for="test_nature"
                                            class="block text-sm md:text-base font-medium text-gray-900">Test nature
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <multiselect class="mt-1" v-model="selectedTestNature"
                                            :options="testNatureOptions" @select="onTestNatureSelect" label="label"
                                            track-by="value" placeholder="Select test nature"></multiselect>
                                        <InputError v-if="patient_test?.errors?.test_nature" :message="patient_test?.errors?.test_nature
                                            " />
                                    </div>
                                </div>

                            </div>
                            <div class="divide-y divide-gray-200 pt-4 pb-4 border"
                                v-if="patient_test?.test_nature == 'General'">
                                <div v-for="(
                                        packageDetail, packageIndex
                                    ) in patient_test.patient_test_details" :key="packageIndex">
                                    <div
                                        class="mb-1 px-4 sm:px-6 grid lg:grid-cols-5 md:grid-cols-5 sm:grid-cols-1 gap-2">
                                        <div>
                                            <label for="card-expiration-date"
                                                class="block text-sm md:text-base font-medium text-gray-900">Text
                                                Category
                                            </label>
                                            <div class="">
                                                <multiselect v-model="packageDetail.test_category_data
                                                    " :options="test_categories" label="name" track-by="id"
                                                    placeholder="Select a Test"></multiselect>
                                            </div>

                                            <InputError v-if="
                                                packageDetail
                                                    .errors?.[
                                                `test_general_details.${index}.test_category_id`
                                                ]
                                            " :message="packageDetail
                                                .errors?.[
                                                `test_general_details.${index}.test_category_id`
                                            ]
                                                " />
                                        </div>
                                        <div>
                                            <label for="card-expiration-date"
                                                class="block text-sm md:text-base font-medium text-gray-900">Package
                                                <span v-if="packageDetail?.selectedPackage != null"> ({{
                                                    packageDetail?.selectedPackage?.price }})</span>
                                            </label>
                                            <div class="mt-1">
                                                <multiselect v-model="packageDetail.selectedPackage
                                                    " :options="packageOptions" @update:modelValue="(selectedPackage) =>
                                                        onPackageSelect(
                                                            selectedPackage,
                                                            packageIndex
                                                        )
                                                        " label="name" track-by="id" placeholder="Select a Package" >
                                                </multiselect>
                                            </div>
                                        </div>
                                        <InputError v-if="
                                            packageDetail?.errors
                                                ?.package_id
                                        " :message="packageDetail?.errors
                                            ?.package_id
                                            " />

                                        <div>
                                            <div>
                                                <label for="card-expiration-date"
                                                    class="block text-sm md:text-base font-medium text-gray-900">Test
                                                    Package <span> ({{ totalSubPackagePrice(packageDetail) }})</span>
                                                </label>
                                                <div class="mt-1">
                                                    <multiselect v-model="packageDetail.selectedSubPackages"
                                                        :options="sub_packages" @update:modelValue="(selectedSubPackage) =>
                                                            onSubPackageSelect(
                                                                selectedSubPackage,
                                                                packageIndex
                                                            )
                                                            " label="name" track-by="id"
                                                        placeholder="Select a Test Package" multiple></multiselect>

                                                </div>
                                                <InputError v-if="
                                                    packageDetail
                                                        .errors?.[
                                                    `test_general_details.${index}.sub_package_id`
                                                    ]
                                                " :message="packageDetail
                                                    .errors?.[
                                                    `test_general_details.${index}.sub_package_id`
                                                ]
                                                    " />
                                            </div>
                                        </div>
                                        <div>
                                            <label for="card-expiration-date"
                                                class="block text-sm md:text-base font-medium text-gray-900">Remarks
                                            </label>
                                            <div class="mt-1">
                                                <textarea id="remarks" name="remarks" type="text" autocomplete="remarks"
                                                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01" v-model="packageDetail.remarks
                                                        "></textarea>
                                            </div>
                                        </div>
                                        <InputError v-if="
                                            packageDetail?.errors
                                                ?.package_id
                                        " :message="packageDetail?.errors
                                            ?.package_id
                                            " />
                                        <div class="mt-4 py-6 flex justify-end">
                                            <button type="button" @click="
                                                removePackageDetail(
                                                    packageIndex
                                                )
                                                "
                                                class="inline-flex items-center justify-center ml-2 px-3 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="px-4 sm:px-6">
                                        <h3 class="text-lg font-semibold py-2" v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                            General test detail
                                        </h3>
                                        <InputError v-if="
                                            packageDetail.errors?.[
                                            `test_general_details`
                                            ]
                                        " :message="packageDetail.errors?.[
                                            `test_general_details`
                                        ]
                                            " />

                                        <div v-for="(
                                                test_general_detail, index
                                            ) in packageDetail.test_general_details" :key="index" class="my-2">

                                            <div v-if="index === 0 || packageDetail.test_general_details[index - 1]?.sub_package?.id !== test_general_detail?.sub_package?.id"
                                                class="font-semibold text-blue-600 mb-2">
                                                {{ test_general_detail?.sub_package?.name }}
                                            </div>
                                            <div class="flex -space-x-px gap-2">


                                                <div class="w-1/2 min-w-0 flex-1">
                                                    <label for="card-expiration-date" class="sr-only">Service</label>
                                                    <multiselect v-model="test_general_detail.lab_test" :options="lab_tests" @update:modelValue="calculatePaymentCharges
                                                            " :custom-label="labTestLabel" track-by="id" class="custom-select"
                                                        placeholder="Select Test"></multiselect>
                                                    <InputError v-if="
                                                        packageDetail
                                                            .errors?.[
                                                        `test_general_details.${index}.lab_test.id`
                                                        ]
                                                    " :message="packageDetail
                                                        .errors?.[
                                                        `test_general_details.${index}.lab_test.id`
                                                    ]
                                                        " />
                                                </div>

                                                <div class="min-w-0 flex-1">
                                                    <label for="card-cvc" class="sr-only">Result</label>
                                                    <input v-model="test_general_detail.result
                                                        " @keyup.enter="focusNextRow(index, 'result')" type="text"
                                                        class="relative block w-full text-red-600 rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6 result-input"
                                                        placeholder="Result" />
                                                    <InputError v-if="
                                                        packageDetail
                                                            .errors?.[
                                                        `test_general_details.${index}.result`
                                                        ]
                                                    " :message="packageDetail
                                                        .errors?.[
                                                        `test_general_details.${index}.result`
                                                    ]
                                                        " />
                                                </div>

                                                <div class="min-w-0 flex-1">
                                                    <label for="card-cvc" class="sr-only">Result 2</label>

                                                    <multiselect v-model="test_general_detail.lab_test_detail"
                                                        :modelValue="test_general_detail.result_2" :options="lab_test_details.filter((res) => {
                                                            return res.lab_test_id === test_general_detail.lab_test?.id;
                                                        })" @keyup.enter="focusNextRow(index, 'result_2')"
                                                        label="test_report" track-by="id" class="result-select"
                                                        placeholder="Select Test"></multiselect>
                                                    <InputError v-if="
                                                        packageDetail
                                                            .errors?.[
                                                        `test_general_details.${index}.result_2`
                                                        ]
                                                    " :message="packageDetail
                                                        .errors?.[
                                                        `test_general_details.${index}.result_2`
                                                    ]
                                                        " />
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <label for="card-cvc" class="sr-only">Description</label>
                                                    <input v-model="test_general_detail.description
                                                        " type="text"
                                                        class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        placeholder="Description" />
                                                    <InputError v-if="
                                                        packageDetail
                                                            .errors?.[
                                                        `test_general_details.${index}.description`
                                                        ]
                                                    " :message="packageDetail
                                                        .errors?.[
                                                        `test_general_details.${index}.description`
                                                    ]
                                                        " />
                                                </div>
                                                <div>
                                                    <button type="button" @click="
                                                        removeTestGeneralDetail(
                                                            packageIndex,
                                                            index
                                                        )
                                                        "
                                                        class="inline-flex items-center justify-center ml-2 px-3 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="font-bold" v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)"> Total Lab Test Price : {{
                                            totalLabTestPrice(packageDetail) }} </div>

                                        <div
                                            v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                            <button type="button" @click="
                                                addTestGeneralDetail(
                                                    packageIndex
                                                )
                                                "
                                                class="inline-flex items-center mt-1 px-3 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                v-if="
                                                    packageDetail?.selectedPackage ==
                                                    null
                                                ">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                                Add More
                                            </button>
                                        </div>
                                        <button
                                            v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)"
                                            type="button" @click="
                                                addNewPackageDetail(packageIndex)
                                                "
                                            class="inline-flex items-center mt-1 px-3 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Add New Package
                                        </button>

                                    </div>
                                </div>
                            </div>
                            <div class="divide-y divide-gray-200 pt-4 pb-4 border"
                                v-if="patient_test?.test_nature == 'Other_Receipt'">
                                <div v-for="(
                                        packageDetail, packageIndex
                                    ) in patient_test.patient_test_details" :key="packageIndex">

                                    <div class="px-4 sm:px-6">
                                        <h3 class="text-lg font-semibold py-2">
                                            General test detail
                                        </h3>
                                        <InputError v-if="
                                            packageDetail.errors?.[
                                            `test_general_details`
                                            ]
                                        " :message="packageDetail.errors?.[
                                            `test_general_details`
                                        ]
                                            " />

                                        <div v-for="(
                                                test_general_detail, index
                                            ) in packageDetail.test_general_details" :key="index" class="my-2">

                                            <div v-if="index === 0 || packageDetail.test_general_details[index - 1]?.sub_package?.id !== test_general_detail?.sub_package?.id"
                                                class="font-semibold text-blue-600 mb-2">
                                                {{ test_general_detail?.sub_package?.name }}
                                            </div>
                                            <div class="flex -space-x-px gap-2">


                                                <div class="w-1/2 min-w-0 flex-1">
                                                    <label for="card-expiration-date" class="sr-only">Service</label>
                                                    <multiselect v-model="test_general_detail.lab_test
                                                        " :options="other_tests" @update:modelValue="calculatePaymentCharges
                                                            " :custom-label="labTestLabel" track-by="id"
                                                        placeholder="Select Test"></multiselect>
                                                    <InputError v-if="
                                                        packageDetail
                                                            .errors?.[
                                                        `test_general_details.${index}.lab_test.id`
                                                        ]
                                                    " :message="packageDetail
                                                        .errors?.[
                                                        `test_general_details.${index}.lab_test.id`
                                                    ]
                                                        " />
                                                </div>

                                                <div class="min-w-0 flex-1">
                                                    <label for="card-cvc" class="sr-only">Result</label>
                                                    <input v-model="test_general_detail.result
                                                        " type="text"
                                                        class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        placeholder="Result" />
                                                    <InputError v-if="
                                                        packageDetail
                                                            .errors?.[
                                                        `test_general_details.${index}.result`
                                                        ]
                                                    " :message="packageDetail
                                                        .errors?.[
                                                        `test_general_details.${index}.result`
                                                    ]
                                                        " />
                                                </div>

                                                <div class="min-w-0 flex-1">
                                                    <label for="card-cvc" class="sr-only">Result 2</label>
                                                    <!-- <input
                                                        v-model="
                                                            test_general_detail.result_2
                                                        "
                                                        type="text"
                                                        class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        placeholder="Result 2"
                                                    /> -->

                                                    <multiselect v-model="test_general_detail.lab_test_detail"
                                                        :modelValue="test_general_detail.result_2" :options="lab_test_details.filter((res) => {
                                                            return res.lab_test_id === test_general_detail.lab_test?.id;
                                                        })" label="test_report" track-by="id"
                                                        placeholder="Select Test"></multiselect>
                                                    <InputError v-if="
                                                        packageDetail
                                                            .errors?.[
                                                        `test_general_details.${index}.result_2`
                                                        ]
                                                    " :message="packageDetail
                                                        .errors?.[
                                                        `test_general_details.${index}.result_2`
                                                    ]
                                                        " />
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <label for="card-cvc" class="sr-only">Description</label>
                                                    <input v-model="test_general_detail.description
                                                        " type="text"
                                                        class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        placeholder="Description" />
                                                    <InputError v-if="
                                                        packageDetail
                                                            .errors?.[
                                                        `test_general_details.${index}.description`
                                                        ]
                                                    " :message="packageDetail
                                                        .errors?.[
                                                        `test_general_details.${index}.description`
                                                    ]
                                                        " />
                                                </div>

                                            </div>
                                        </div>
                                        <div class="font-bold"> Total Lab Test Price : {{
                                            totalLabTestPrice(packageDetail) }} </div>

                                        <!-- <div>
                                        <button
                                            type="button"
                                            @click="
                                                addTestGeneralDetail(
                                                    packageIndex
                                                )
                                            "
                                            class="inline-flex items-center mt-1 px-3 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            v-if="
                                                packageDetail?.selectedPackage ==
                                                null
                                            "
                                        >
                                            <svg
                                                class="w-4 h-4 mr-2"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                                />
                                            </svg>
                                            Add More
                                        </button>
                                    </div>
                                    <button
                                        type="button"
                                        @click="
                                            addNewPackageDetail(packageIndex)
                                        "
                                        class="inline-flex items-center mt-1 px-3 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                    >
                                        <svg
                                            class="w-4 h-4 mr-2"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                            />
                                        </svg>
                                        Add New Package
                                    </button> -->

                                    </div>
                                </div>
                            </div>

                            <div class="divide-y divide-gray-200 pt-4 pb-4"
                                v-else-if="patient_test?.test_nature == 'Widal'">
                                <div class="px-4 sm:px-6">
                                    <h3 class="text-lg font-semibold py-2">
                                        Widal test detail
                                    </h3>
                                    <InputError v-if="
                                        patient_test.errors?.[
                                        `test_widal_details`
                                        ]
                                    " :message="patient_test.errors?.[
                                        `test_widal_details`
                                    ]
                                        " />
                                    <div class="my-2" v-for="(
                                            test_widal_detail, index
                                        ) in patient_test.test_widal_details" :key="index">
                                        <div class="flex -space-x-px">
                                            <div class="w-1/2 min-w-0 flex-1">
                                                <label for="card-expiration-date" class="sr-only">Widal test</label>
                                                <multiselect v-model="test_widal_detail[
                                                    'widal_test'
                                                ]
                                                    " :options="widal_tests" @update:modelValue="
                                                        calculatePaymentCharges()
                                                        " label="name" track-by="id" placeholder="Select Test">
                                                </multiselect>
                                                <InputError v-if="
                                                    patient_test.errors?.[
                                                    `test_widal_details.${index}.widal_test.id`
                                                    ]
                                                " :message="patient_test.errors?.[
                                                    `test_widal_details.${index}.widal_test.id`
                                                ]
                                                    " />
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <label for="card-cvc" class="sr-only">1-40</label>
                                                <input v-model="test_widal_detail[
                                                    '1_40'
                                                ]
                                                    " type="text"
                                                    class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                    placeholder="1-40" />
                                                <InputError v-if="
                                                    patient_test.errors?.[
                                                    `test_widal_details.${index}.1_40`
                                                    ]
                                                " :message="patient_test.errors?.[
                                                    `test_widal_details.${index}.1_40`
                                                ]
                                                    " />
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <label for="card-cvc" class="sr-only">1-80</label>
                                                <input v-model="test_widal_detail[
                                                    '1_80'
                                                ]
                                                    " type="text"
                                                    class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                    placeholder="1-80" />
                                                <InputError v-if="
                                                    patient_test.errors?.[
                                                    `test_widal_details.${index}.1_80`
                                                    ]
                                                " :message="patient_test.errors?.[
                                                    `test_widal_details.${index}.1_80`
                                                ]
                                                    " />
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <label for="card-cvc" class="sr-only">1-160</label>
                                                <input v-model="test_widal_detail[
                                                    '1_160'
                                                ]
                                                    " type="text"
                                                    class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                    placeholder="1-160" />
                                                <InputError v-if="
                                                    patient_test.errors?.[
                                                    `test_widal_details.${index}.1_160`
                                                    ]
                                                " :message="patient_test.errors?.[
                                                    `test_widal_details.${index}.1_160`
                                                ]
                                                    " />
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <label for="card-cvc" class="sr-only">1-320</label>
                                                <input v-model="test_widal_detail[
                                                    '1_320'
                                                ]
                                                    " type="text"
                                                    class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                    placeholder="1-320" />
                                                <InputError v-if="
                                                    patient_test.errors?.[
                                                    `test_widal_details.${index}.1_320`
                                                    ]
                                                " :message="patient_test.errors?.[
                                                    `test_widal_details.${index}.1_320`
                                                ]
                                                    " />
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <label for="card-cvc" class="sr-only">1-640</label>
                                                <input v-model="test_widal_detail[
                                                    '1_640'
                                                ]
                                                    " type="text"
                                                    class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                    placeholder="1-640" />
                                                <InputError v-if="
                                                    patient_test.errors?.[
                                                    `test_widal_details.${index}.1_640`
                                                    ]
                                                " :message="patient_test.errors?.[
                                                    `test_widal_details.${index}.1_640`
                                                ]
                                                    " />
                                            </div>
                                            <div>
                                                <button type="button" @click="
                                                    removeTestWidalDetail(
                                                        index
                                                    )
                                                    "
                                                    class="inline-flex items-center justify-center ml-2 px-3 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" @click="addTestWidalDetail"
                                        class="inline-flex items-center mt-1 px-3 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Add More
                                    </button>
                                </div>
                            </div>
                            <div class="divide-y divide-gray-200 pt-4 pb-4" v-else-if="
                                patient_test?.test_nature == 'Cross_Match'
                            ">
                                <div class="px-4 sm:px-6">
                                    <h3 class="text-lg font-semibold py-2">
                                        Donor detail
                                    </h3>
                                    <div class="mt-6 grid grid-cols-12 gap-6">
                                        <div class="col-span-12 sm:col-span-3">
                                            <label for="donor_name"
                                                class="block text-sm md:text-base font-medium text-gray-900">Donor
                                                name</label>
                                            <div class="mt-2">
                                                <input id="donor_name" name="donor_name" type="text"
                                                    autocomplete="donor_name"
                                                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01" v-model="patient_test
                                                        .test_cross_match_details
                                                        .donor_name
                                                        " />
                                            </div>
                                            <InputError v-if="
                                                patient_test.errors?.[
                                                'test_cross_match_details.donor_name'
                                                ]
                                            " :message="patient_test.errors?.[
                                                'test_cross_match_details.donor_name'
                                            ]
                                                " />
                                        </div>
                                        <div class="col-span-12 sm:col-span-3">
                                            <label for="donor_blood_group"
                                                class="block text-sm md:text-base font-medium text-gray-900">Donor blood
                                                group</label>
                                            <div class="mt-2">
                                                <multiselect v-model="selectedDonorBloodGroup
                                                    " :options="bloodGroupOptions" @select="onDonorBloodGroupSelect
                                                        " label="type" track-by="type"
                                                    placeholder="Select Blood Group">
                                                </multiselect>
                                            </div>
                                            <InputError v-if="
                                                patient_test.errors?.[
                                                'test_cross_match_details.donor_blood_group'
                                                ]
                                            " :message="patient_test.errors?.[
                                                'test_cross_match_details.donor_blood_group'
                                            ]
                                                " />
                                        </div>
                                        <div class="col-span-12 sm:col-span-3">
                                            <label for="donor_rh_factor"
                                                class="block text-sm md:text-base font-medium text-gray-900">Donor RH
                                                factor</label>
                                            <div class="mt-2">
                                                <input id="donor_rh_factor" name="donor_rh_factor" type="text"
                                                    autocomplete="donor_rh_factor"
                                                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01" v-model="patient_test
                                                        .test_cross_match_details
                                                        .donor_rh_factor
                                                        " />
                                            </div>
                                            <InputError v-if="
                                                patient_test.errors?.[
                                                'test_cross_match_details.donor_rh_factor'
                                                ]
                                            " :message="patient_test.errors?.[
                                                'test_cross_match_details.donor_rh_factor'
                                            ]
                                                " />
                                        </div>
                                        <div class="col-span-12 sm:col-span-3">
                                            <label for="donor_age"
                                                class="block text-sm md:text-base font-medium text-gray-900">Donor
                                                age</label>
                                            <div class="mt-2">
                                                <input id="donor_age" name="donor_age" type="text"
                                                    autocomplete="donor_age"
                                                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01" v-model="patient_test
                                                        .test_cross_match_details
                                                        .donor_age
                                                        " />
                                            </div>
                                            <InputError v-if="
                                                patient_test.errors?.[
                                                'test_cross_match_details.donor_age'
                                                ]
                                            " :message="patient_test.errors?.[
                                                'test_cross_match_details.donor_age'
                                            ]
                                                " />
                                        </div>
                                        <div class="col-span-12 sm:col-span-3">
                                            <label for="donor_blood_pressure"
                                                class="block text-sm md:text-base font-medium text-gray-900">Donor blood
                                                pressure</label>
                                            <div class="mt-2">
                                                <input id="donor_blood_pressure" name="donor_blood_pressure" type="text"
                                                    autocomplete="donor_blood_pressure"
                                                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01" v-model="patient_test
                                                        .test_cross_match_details
                                                        .donor_blood_pressure
                                                        " />
                                            </div>
                                            <InputError v-if="
                                                patient_test.errors?.[
                                                'test_cross_match_details.donor_blood_pressure'
                                                ]
                                            " :message="patient_test.errors?.[
                                                'test_cross_match_details.donor_blood_pressure'
                                            ]
                                                " />
                                        </div>
                                        <div class="col-span-12 sm:col-span-3">
                                            <label for="donor_anti_hcv"
                                                class="block text-sm md:text-base font-medium text-gray-900">Donor anti
                                                HCV</label>
                                            <div class="mt-2">
                                                <input id="donor_anti_hcv" name="donor_anti_hcv" type="text"
                                                    autocomplete="donor_anti_hcv"
                                                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01" v-model="patient_test
                                                        .test_cross_match_details
                                                        .donor_anti_hcv
                                                        " />
                                            </div>
                                            <InputError v-if="
                                                patient_test.errors?.[
                                                'test_cross_match_details.donor_anti_hcv'
                                                ]
                                            " :message="patient_test.errors?.[
                                                'test_cross_match_details.donor_anti_hcv'
                                            ]
                                                " />
                                        </div>
                                        <div class="col-span-12 sm:col-span-3">
                                            <label for="donor_hbsag"
                                                class="block text-sm md:text-base font-medium text-gray-900">Donor
                                                hbsag</label>
                                            <div class="mt-2">
                                                <input id="donor_hbsag" name="donor_hbsag" type="text"
                                                    autocomplete="donor_hbsag"
                                                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01" v-model="patient_test
                                                        .test_cross_match_details
                                                        .donor_hbsag
                                                        " />
                                            </div>
                                            <InputError v-if="
                                                patient_test.errors?.[
                                                'test_cross_match_details.donor_hbsag'
                                                ]
                                            " :message="patient_test.errors?.[
                                                'test_cross_match_details.donor_hbsag'
                                            ]
                                                " />
                                        </div>
                                        <div class="col-span-12 sm:col-span-3">
                                            <label for="donor_hiv_antibodies"
                                                class="block text-sm md:text-base font-medium text-gray-900">Donor HIV
                                                antibodies</label>
                                            <div class="mt-2">
                                                <input id="donor_hiv_antibodies" name="donor_hiv_antibodies" type="text"
                                                    autocomplete="donor_hiv_antibodies"
                                                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01" v-model="patient_test
                                                        .test_cross_match_details
                                                        .donor_hiv_antibodies
                                                        " />
                                            </div>
                                            <InputError v-if="
                                                patient_test.errors?.[
                                                'test_cross_match_details.donor_hiv_antibodies'
                                                ]
                                            " :message="patient_test.errors?.[
                                                'test_cross_match_details.donor_hiv_antibodies'
                                            ]
                                                " />
                                        </div>
                                        <div class="col-span-12 sm:col-span-3">
                                            <label for="donor_cross_match"
                                                class="block text-sm md:text-base font-medium text-gray-900">Donor cross
                                                match</label>
                                            <div class="mt-2">
                                                <input id="donor_cross_match" name="donor_cross_match" type="text"
                                                    autocomplete="donor_cross_match"
                                                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01" v-model="patient_test
                                                        .test_cross_match_details
                                                        .donor_cross_match
                                                        " />
                                            </div>
                                            <InputError v-if="
                                                patient_test.errors?.[
                                                'test_cross_match_details.donor_cross_match'
                                                ]
                                            " :message="patient_test.errors?.[
                                                'test_cross_match_details.donor_cross_match'
                                            ]
                                                " />
                                        </div>
                                        <div class="col-span-12 sm:col-span-3">
                                            <label for="blood_bag_no"
                                                class="block text-sm md:text-base font-medium text-gray-900">Blood Bag
                                                No</label>
                                            <div class="mt-2">
                                                <input id="blood_bag_no" name="blood_bag_no" type="text"
                                                    autocomplete="blood_bag_no"
                                                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01" v-model="patient_test
                                                        .test_cross_match_details
                                                        .blood_bag_no
                                                        " />
                                            </div>
                                            <InputError v-if="
                                                patient_test.errors?.[
                                                'test_cross_match_details.blood_bag_no'
                                                ]
                                            " :message="patient_test.errors?.[
                                                'test_cross_match_details.blood_bag_no'
                                            ]
                                                " />
                                        </div>
                                        <div class="col-span-12 sm:col-span-3">
                                            <label for="blood_component"
                                                class="block text-sm md:text-base font-medium text-gray-900">Blood
                                                Component</label>
                                            <div class="mt-2">
                                                <input id="blood_component" name="blood_component" type="text"
                                                    autocomplete="blood_component"
                                                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01" v-model="patient_test
                                                        .test_cross_match_details
                                                        .blood_component
                                                        " />
                                            </div>
                                            <InputError v-if="
                                                patient_test.errors?.[
                                                'test_cross_match_details.blood_component'
                                                ]
                                            " :message="patient_test.errors?.[
                                                'test_cross_match_details.blood_component'
                                            ]
                                                " />
                                        </div>
                                        <div class="col-span-12 sm:col-span-3">
                                            <label for="blood_volumn"
                                                class="block text-sm md:text-base font-medium text-gray-900">Blood
                                                Volume</label>
                                            <div class="mt-2">
                                                <input id="blood_volumn" name="blood_volumn" type="text"
                                                    autocomplete="blood_volumn"
                                                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01" v-model="patient_test
                                                        .test_cross_match_details
                                                        .blood_volumn
                                                        " />
                                            </div>
                                            <InputError v-if="
                                                patient_test.errors?.[
                                                'test_cross_match_details.blood_volumn'
                                                ]
                                            " :message="patient_test.errors?.[
                                                'test_cross_match_details.blood_volumn'
                                            ]
                                                " />
                                        </div>
                                        <div class="col-span-12 sm:col-span-3">
                                            <label for="bleeding_date"
                                                class="block text-sm md:text-base font-medium text-gray-900">Bleeding
                                                Date</label>
                                            <div class="mt-2">
                                                <input id="bleeding_date" name="bleeding_date" type="date"
                                                    autocomplete="bleeding_date"
                                                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01" v-model="patient_test
                                                        .test_cross_match_details
                                                        .bleeding_date
                                                        " />
                                            </div>
                                            <InputError v-if="
                                                patient_test.errors?.[
                                                'test_cross_match_details.bleeding_date'
                                                ]
                                            " :message="patient_test.errors?.[
                                                'test_cross_match_details.bleeding_date'
                                            ]
                                                " />
                                        </div>
                                        <div class="col-span-12 sm:col-span-3">
                                            <label for="bag_expiry_date"
                                                class="block text-sm md:text-base font-medium text-gray-900">Bag Expiry
                                                Date</label>
                                            <div class="mt-2">
                                                <input id="bag_expiry_date" name="bag_expiry_date" type="date"
                                                    autocomplete="bag_expiry_date"
                                                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01" v-model="patient_test
                                                        .test_cross_match_details
                                                        .bag_expiry_date
                                                        " />
                                            </div>
                                            <InputError v-if="
                                                patient_test.errors?.[
                                                'test_cross_match_details.bag_expiry_date'
                                                ]
                                            " :message="patient_test.errors?.[
                                                'test_cross_match_details.bag_expiry_date'
                                            ]
                                                " />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="py-4">
                                <h2 class="text-2xl font-medium leading-6 text-gray-900">
                                    Payment detail
                                </h2>
                                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-5">
                                    <div>
                                        <label for="status"
                                            class="block text-sm md:text-base font-medium text-gray-900">Status
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <multiselect class="mt-1" v-model="selectedStatus" :options="statusOptions"
                                            @select="onStatusSelect" label="label" track-by="value"
                                            placeholder="Select status"></multiselect>
                                        <InputError v-if="patient_test?.errors?.status" :message="patient_test?.errors?.status
                                            " />
                                    </div>
                                    <div v-if="
                                        patient_test?.test_nature ==
                                        'Cross_Match'
                                    ">
                                        <label for="total_amount"
                                            class="block text-sm md:text-base font-medium text-gray-900">Total
                                            amount</label>
                                        <input id="total_amount" name="total_amount" type="text"
                                            autocomplete="total_amount"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            v-model="patient_test.total_amount" @input="calculatePaymentCharges()" />
                                        <InputError v-if="
                                            patient_test.errors
                                                ?.total_amount
                                        " :message="patient_test.errors
                                            ?.total_amount
                                            " />
                                    </div>
                                    <div
                                        v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                        <div class="mt-2">
                                            <SwitchGroup as="div" class="flex items-center justify-between">
                                                <span class="flex flex-grow flex-col">
                                                    <SwitchLabel as="span"
                                                        class="text-sm md:text-base font-medium text-gray-900" passive>
                                                        Free test</SwitchLabel>
                                                    <SwitchDescription as="span" class="text-sm text-gray-900">Free test
                                                        when toggled
                                                        on, Paid when toggled
                                                        off.</SwitchDescription>
                                                </span>
                                                <Switch v-model="enabled" :class="[
                                                    enabled
                                                        ? 'bg-indigo-600'
                                                        : 'bg-gray-200',
                                                    'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2',
                                                ]">
                                                    <span aria-hidden="true" :class="[
                                                        enabled
                                                            ? 'translate-x-5'
                                                            : 'translate-x-0',
                                                        'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                                                    ]" />
                                                </Switch>
                                            </SwitchGroup>
                                        </div>
                                        <InputError v-if="
                                            patient_test.errors?.free_test
                                        " :message="patient_test.errors?.free_test
                                            " />
                                    </div>
                                    <template
                                        v-if="!patient_test?.free_test || props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                        <div
                                            v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                            <label for="discount_type"
                                                class="block text-sm md:text-base font-medium text-gray-900">Discount
                                                type</label>
                                            <div class="mt-1">
                                                <multiselect v-model="selectedDiscountType
                                                    " :options="discountTypeOptions
                                                        " @select="onDiscountTypeSelect
                                                            " label="label" track-by="value"
                                                    placeholder="Select dicount type">
                                                </multiselect>
                                            </div>
                                            <InputError v-if="
                                                patient_test.errors
                                                    ?.discount_type
                                            " :message="patient_test.errors
                                                ?.discount_type
                                                " />
                                        </div>
                                        <div v-if="
                                            patient_test?.discount_type !=
                                            null
                                        ">
                                            <label for="discount_value"
                                                class="block text-sm md:text-base font-medium text-gray-900">Discount
                                                value</label>
                                            <input id="discount_value" name="discount_value" type="text"
                                                autocomplete="discount_value"
                                                class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                v-model="patient_test.discount_value
                                                    " @input="
                                                        calculatePaymentCharges()
                                                        " />
                                            <InputError v-if="
                                                patient_test.errors
                                                    ?.discount_value
                                            " :message="patient_test.errors
                                                ?.discount_value
                                                " />
                                        </div>
                                    </template>

                                    <div
                                        v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                        <label for="sub_total"
                                            class="block text-sm md:text-base font-medium text-gray-900">Sub
                                            total</label>
                                        <input id="sub_total" name="sub_total" type="text" autocomplete="sub_total"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            :value="subTotal" tabindex="-1" disabled="disabled" />
                                    </div>
                                    <div
                                        v-if="(props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)) && props.hasDonationModule">
                                        <label for="careoff_id"
                                            class="block text-sm md:text-base font-medium text-gray-900">Care Off
                                        </label>
                                        <div class="mt-1">
                                            <multiselect v-model="selectedCareOff" :options="careoffs"
                                                @update:modelValue="
                                                    onCareOffSelect(
                                                        selectedCareOff
                                                    )
                                                    " label="name" track-by="id" placeholder="Select a CareOff">
                                            </multiselect>
                                            <input id="donor_fee" name="donor_fee" type="text" autocomplete="donor_fee"
                                                placeholder="Careoff Fee"
                                                class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                step="0.01" @input="
                                                    calculatePaymentCharges()
                                                    " v-model="patient_test.donor_fee" v-if="
                                                        patient_test.careoff_id !=
                                                        null &&
                                                        patient_test.careoff_id !=
                                                        ''
                                                    " />
                                        </div>
                                        <InputError v-if="
                                            patient_test?.errors?.careoff_id
                                        " :message="patient_test?.errors?.careoff_id
                                            " />
                                    </div>
                                    <div
                                        v-if="(props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)) && props.hasDonationModule">
                                        <label for="donor_id"
                                            class="block text-sm md:text-base font-medium text-gray-900">Zf
                                        </label>
                                        <input id="name" name="name" type="text" autocomplete="name"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            step="0.01" @input="
                                                checkAmount(
                                                    $event.target.value,
                                                    zf_fees
                                                )
                                                " v-model="patient_test.zf_fee" />
                                        <InputError v-if="patient_test?.errors?.zf_fees" :message="patient_test?.errors?.zf_fees
                                            " />
                                    </div>
                                    <div
                                        v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                        <label for="donor_id"
                                            class="block text-sm md:text-base font-medium text-gray-900">Authorized
                                        </label>
                                        <input id="name" name="name" type="text" autocomplete="name"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            step="0.01" v-model="patient_test.welfare" />
                                        <InputError v-if="patient_test?.errors?.welfare" :message="patient_test?.errors?.welfare
                                            " />
                                    </div>
                                    <div
                                        v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                        <label for="disacount_amount"
                                            class="block text-sm md:text-base font-medium text-gray-900">Discount
                                            amount</label>
                                        <input id="disacount_amount" name="disacount_amount" type="text"
                                            autocomplete="disacount_amount"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            :value="discountAmount" tabindex="-1" disabled="disabled" />
                                    </div>
                                    <div
                                        v-if="props.patient_test == null || !props.patient_test || (props.roles?.role_id === 1)">
                                        <label for="payable_amount"
                                            class="block text-sm md:text-base font-medium text-gray-900">Payable
                                            amount</label>
                                        <input id="payable_amount" name="payable_amount" type="text"
                                            autocomplete="payable_amount"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            :value="payableAmount" tabindex="-1" disabled="disabled" />
                                        <InputError v-if="payableAmount === 0"
                                            message="Payable amount cannot be zero." />
                                    </div>
                                    <div class="col-span-2">
                                        <label for="payable_amount"
                                            class="block text-sm md:text-base font-medium text-gray-900">Remarks</label>
                                        <textarea id="remarks" name="remarks" type="text" autocomplete="remarks"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                                            step="0.01" v-model="patient_test.remarks"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-end gap-4 mt-6 py-4">
                                <InertiaLink
                                    class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                                    :href="route('patient-tests.index')">
                                    Cancel
                                </InertiaLink>
                                <button type="button" @click.prevent="handleSubmit"
                                    class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                                    :disabled="patient_test.processing">
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
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import { ref, watch, computed, onMounted } from "vue";
import Multiselect from "vue-multiselect";
import axios from "axios";
import Swal from 'sweetalert2';

import {
    Switch,
    SwitchDescription,
    SwitchGroup,
    SwitchLabel,
} from "@headlessui/vue";

const props = defineProps({
    hasDonationModule: { type: Boolean, default: null },
    patient_test: { type: Object, default: null },
    lab_tests: { type: Object, required: true },
    other_tests: { type: Object, required: true },
    roles: { type: Object },
    mr_number: String,
    lab_test_details: { type: Array, required: true },
    widal_tests: { type: Array, required: true },
    patients: { type: Object, required: true },
    careoffs: { type: Array, required: true },
    zf_fees: { type: String, required: true },
    doctors: { type: String, required: true },
    packages: { type: String, required: true },
    test_categories: { type: Array, required: true },
    sub_packages: { type: Array, required: true },
    countries: { type: Array, required: true, default: () => [] },
    states: { type: Array, required: false, default: () => [] },
    cities: { type: Array, required: false, default: () => [] },
});

const isEditMode = computed(() => !!props.patient_test);
// const formatDate = (dateString) => {
//     const date = new Date(dateString);
//     // Correcting the date to the Asia/Karachi timezone
//     const options = { year: 'numeric', month: '2-digit', day: '2-digit', timeZone: 'Asia/Karachi' };
//     const formatter = new Intl.DateTimeFormat('en-US', options);
//     const [{ value: month },,{ value: day },,{ value: year }] = formatter.formatToParts(date);
//     return `${year}-${month}-${day}`;
// };

// let testDate = props?.patient_test?.test_date;
// const formattedDate = testDate
//     ? formatDate(testDate)
//     : formatDate(new Date());
const getCurrentDateInPakistanTimezone = () => {
    const options = { year: 'numeric', month: '2-digit', day: '2-digit', timeZone: 'Asia/Karachi' };
    const currentDate = new Date().toLocaleDateString('en-CA', options);
    return currentDate;
};

const formatDate = (date) => {
    const options = { year: 'numeric', month: '2-digit', day: '2-digit', timeZone: 'Asia/Karachi' };
    return new Date(date).toLocaleDateString('en-CA', options);
};

let testDate = props?.patient_test?.test_date;
const formattedDate = testDate
    ? formatDate(testDate)
    : getCurrentDateInPakistanTimezone();

const patient_test = useForm({
    id: props?.patient_test?.id || null,
    test_date: formattedDate,
    lab_no: props?.patient_test?.lab_no || null,
    test_nature: props?.patient_test?.test_nature || null,
    test_type: props?.patient_test?.test_type || null,
    donor_fee: props?.patient_test?.donor_fee || null,
    test_category_id: props?.patient_test?.test_category_id || null,
    status: props?.patient_test?.status || null,
    referred_by: props?.patient_test?.referred_by || null,
    patient_id: null,
    patient_name: props?.patient_test?.patient_name || null,
    patient_cnic: props?.patient_test?.patient_cnic || null,
    patient_age: props?.patient_test?.patient_age || null,
    careoff_id: props?.patient_test?.careoff_id || null,
    zf_fee: props?.patient_test?.zf_fee || null,
    zf_id: props?.patient_test?.zf_id || null,
    mr_number: props?.mr_number || null,
    welfare: props?.patient_test?.welfare || null,
    file_no: props?.patient_test?.file_no || null,
    patient_gender: props?.patient_test?.patient_gender || null,
    patient_relation_name: props?.patient_test?.patient_relation_name || null,
    patient_relation_type: props?.patient_test?.patient_relation_type || null,
    patient_address: props?.patient_test?.patient_address || null,
    patient_state_id: props?.patient_test?.patient_state_id || null,
    patient_city_id: props?.patient_test?.patient_city_id || null,
    patient_country_id: props?.patient_test?.patient_country_id || null,
    patient_phone: props?.patient_test?.patient_phone || null,
    patient_email: props?.patient_test?.patient_email || null,
    patient_blood_group: props?.patient_test?.patient_blood_group || null,
    patient_rh_factor: props?.patient_test?.patient_rh_factor || null,
    specimen: props?.patient_test?.specimen || null,
    date_sample: props?.patient_test?.date_sample || props?.patient_test?.test_date,
    date_report_ready: props?.patient_test?.date_report_ready || null,
    date_report_verification: props?.patient_test?.date_report_verification || null,
    remarks: props?.patient_test?.remarks || null,
    chart_no: props?.patient_test?.chart_no || null,
    date_report_collection: props?.patient_test?.date_report_collection || null,
    lab_incharge: props?.patient_test?.lab_incharge || null,
    doctor_id: props?.patient_test?.doctor_id || null,
    //   package_id: props?.patient_test?.package_id || null,
    free_test: props?.patient_test?.free_test || false,
    discount_type: props?.patient_test?.discount_type || null,
    discount_value: props?.patient_test?.discount_value || null,
    total_amount: props?.patient_test?.total_amount || null,

    patient_test_details: props?.patient_test?.patient_test_details || [
        {
            id: null,
            patient_test_id: null,
            remarks: null,
            package_id: null,
            test_general_details: [
                {
                    id: null,
                    patient_test_detail_id: null,
                    lab_test_detail_id: null,
                    lab_test_id: null,
                    sub_package_id: null,
                    test_category_id: null,
                    lab_test: null,
                    test_category: null,
                    test_category_data: null,
                    sub_package: null,
                    result: null,
                    result_2: null,
                    description: null,
                    lab_serial: 0,
                },
            ],
        },
    ],
    test_widal_details: props?.patient_test?.test_widal_details || [
        {
            id: null,
            patient_test_id: null,
            widal_test: null,
            1_40: null,
            1_80: null,
            1_160: null,
            1_320: null,
            1_640: null,
        },
    ],
    test_cross_match_details: props?.patient_test?.test_cross_match_details || {
        id: null,
        patient_test_id: null,
        donor_name: null,
        donor_blood_group: null,
        donor_rh_factor: null,
        donor_age: null,
        donor_blood_pressure: null,
        donor_anti_hcv: null,
        donor_hbsag: null,
        donor_hiv_antibodies: null,
        donor_cross_match: null,
        blood_bag_no: null,
        blood_component: null,
        blood_volumn: null,
        bleeding_date: null,
        bag_expiry_date: null,
    },
});
// console.log(props?.patient_test);
onMounted(() => {
  patient_test.mr_number = props.mr_number || "";
  });
const patients = ref(props.patients || []);
const selectedPatient = ref(null);

const labTestLabel = (labTest) => {
    return labTest ? `${labTest.name} - ${labTest.price || '0'}` : '';
};
const totalLabTestPrice = (packageDetail) => {
    if (!packageDetail || !packageDetail.test_general_details) {
        return 0;
    }

    return packageDetail.test_general_details.reduce((total, detail) => {
        const price = detail.lab_test?.price || 0;
        return total + price;
    }, 0);
};
const totalSubPackagePrice = (packageDetail) => {
    if (!packageDetail || !packageDetail.selectedSubPackages) {
        return 0;
    }

    return packageDetail.selectedSubPackages.reduce((total, detail) => {
        const price = detail?.price || 0;
        return total + price;
    }, 0);
};
const generateMRNumber = (event) => {
  let input = event.target.value.replace(/\D/g, "");
  let number = input ? parseInt(input) : 1;
  let formattedNumber = number.toString().padStart(5, '0');
  patient_test.mr_number = formattedNumber;
};
// if (props?.patient_test) {
//   const patient = props.patients.find(
//     (c) => c.id === props.patient_test.patient_id
//   );
//   if (patient) {
//     selectedPatient.value = patient;
//   }
// }

const onPatientSelect = async (selectedPatient) => {
    patient_test.patient_id = selectedPatient.id;
    await loadPatient(selectedPatient.id);
};

// Gender
const selectedGender = ref();
const genderOptions = computed(() => {
    return [
        { value: "Male", label: "Male" },
        { value: "Female", label: "Female" },
        { value: "Children", label: "Children" },
    ];
});

const onGenderSelect = async (selectedGender) => {
    patient_test.patient_gender = selectedGender.value;
};
if (props?.patient_test?.patient_gender) {
    selectedGender.value = genderOptions.value.find(
        (option) => option.value === props.patient_test.patient_gender
    );
}

const focusNextRow = (currentField) => {
    const resultInputs = document.querySelectorAll('.result-input');
    const resultSelects = document.querySelectorAll('.result-select');

    const allElements = [...resultInputs, ...resultSelects];

    const currentElement = document.activeElement;

    const currentIndex = Array.from(allElements).indexOf(currentElement);

    let nextElement;
    if (currentIndex === -1) {
        nextElement = allElements[0];
    } else {
        nextElement = allElements[(currentIndex + 1) % allElements.length];
    }

    nextElement.focus();
};

const doctors = ref(props.doctors || []);
const selectedDoctor = ref(null);
if (props?.patient_test) {
    const doctor = props.doctors.find(
        (c) => c.id === props.patient_test.doctor_id
    );
    if (doctor) {
        selectedDoctor.value = doctor;
    }
}

const doctorOptions = computed(() => {
    return doctors.value && doctors.value.length ? doctors.value : [];
});

const onDoctorSelect = async (selectedDoctor) => {
    patient_test.doctor_id = selectedDoctor.id;
};
// const selectedPackage = ref(null);
const packages = ref(props.packages || []);

const packageOptions = computed(() => {
    return packages.value && packages.value.length ? packages.value : [];
});

const onPackageSelect = async (selectedPackage, packageIndex) => {
    const patientTest = patient_test.patient_test_details[packageIndex];
    patientTest.package_id = selectedPackage.id;
    patientTest.test_general_details = [];
    patientTest.selectedSubPackages = [];
    selectedPackage.sub_packages.map(sub_packages => {
        sub_packages.lab_tests.map(lab_tests => {
            patientTest.test_general_details.push({
                id: null,
                patient_test_detail_id: null,
                sub_package_id: sub_packages.id,
                test_category_id: lab_tests.test_category_id,
                lab_test_id: lab_tests.id,
                lab_test: lab_tests,
                test_category: lab_tests.test_category,
                sub_package: sub_packages,
                result: null,
                result_2: null,
                lab_test_detail_id: null,
                description: null,
            });
        })
        patientTest.selectedSubPackages.push(sub_packages);
    })

    calculatePaymentCharges();
}
const onPackageSelect1 = async (selectedPackage, packageIndex) => {
    const patientTest = patient_test.patient_test_details[packageIndex];
    patientTest.package_id = selectedPackage.id;
    patientTest.test_general_details = [];
    patientTest.selectedSubPackages = [];
    selectedPackage.sub_packages.map(sub_packages => {
        sub_packages.other_tests.map(other_tests => {
            patientTest.test_general_details.push({
                id: null,
                patient_test_detail_id: null,
                sub_package_id: sub_packages.id,
                test_category_id: other_tests.test_category_id,
                lab_test_id: other_tests.id,
                lab_test: other_tests,
                test_category: other_tests.test_category,
                sub_package: sub_packages,
                result: null,
                result_2: null,
                lab_test_detail_id: null,
                description: null,
            });
        })
        patientTest.selectedSubPackages.push(sub_packages);
    })

    calculatePaymentCharges();
}
const onSubPackageSelect = async (selectedSubPackage, packageIndex) => {
    // console.log(selectedSubPackage);
    const patientTest = patient_test.patient_test_details[packageIndex];
    patientTest.test_general_details = [];
    patientTest.selectedSubPackages = [];
    selectedSubPackage.map(sub_packages => {
        sub_packages.lab_tests.map(lab_tests => {
            patientTest.test_general_details.push({
                id: null,
                patient_test_detail_id: null,
                sub_package_id: sub_packages.id,
                test_category_id: lab_tests.test_category_id,
                lab_test_id: lab_tests.id,
                lab_test: lab_tests,
                test_category: lab_tests.test_category,
                sub_package: sub_packages,
                result: null,
                result_2: null,
                lab_test_detail_id: null,
                description: null,
            });
        })
        patientTest.selectedSubPackages.push(sub_packages);
    })
    calculatePaymentCharges();

}
const onSubPackageSelectOther = async (selectedSubPackage, packageIndex) => {
    // console.log(selectedSubPackage);
    const patientTest = patient_test.patient_test_details[packageIndex];
    patientTest.test_general_details = [];
    patientTest.selectedSubPackages = [];
    selectedSubPackage.map(sub_packages => {
        sub_packages.other_tests.map(other_tests => {
            patientTest.test_general_details.push({
                id: null,
                patient_test_detail_id: null,
                sub_package_id: sub_packages.id,
                test_category_id: other_tests.test_category_id,
                lab_test_id: other_tests.id,
                lab_test: other_tests,
                test_category: other_tests.test_category,
                sub_package: sub_packages,
                result: null,
                result_2: null,
                lab_test_detail_id: null,
                description: null,
            });
        })
        patientTest.selectedSubPackages.push(sub_packages);
    })
    calculatePaymentCharges();

}

// if (
//     props?.patient_test?.patient_test_details &&
//     Array.isArray(props.patient_test.patient_test_details)
// ) {
//     let patientTestDetails = props.patient_test.patient_test_details;
//     patientTestDetails.forEach((detail, index) => {
//         let packageId = detail.package_id;
//         let pak = props.packages.find((pkg) => pkg.id == packageId);

//         if (pak) {
//             patient_test.patient_test_details[index].selectedPackage = pak;
//         }
//     });
// }
if (
    props?.patient_test?.patient_test_details &&
    Array.isArray(props.patient_test.patient_test_details)
) {
    let patientTestDetails = [...props.patient_test.patient_test_details];

    patientTestDetails.forEach((detail, packageIndex) => {
        if (detail.package) {
            detail.selectedPackage = detail?.package;
            detail.selectedSubPackages = detail?.selectedPackage?.sub_packages;

        }
        else {
            detail.test_general_details.forEach((packageDetail, index) => {
                detail.selectedSubPackages = [];
                if (packageDetail?.sub_package) {
                    detail.selectedSubPackages.push(packageDetail?.sub_package);
                }
            });
        }
        let testGeneralDetails = detail.test_general_details;

        testGeneralDetails.forEach((packageDetail, index) => {
            const testCategoryId = packageDetail?.test_category_data;
            if (testCategoryId !== undefined) {
                let testCategory = props.test_categories.find(
                    (cat) => cat.id === testCategoryId
                );
                if (!testCategory) {
                    testCategory = {
                        id: testCategoryId,
                        name:
                            packageDetail.test_category?.name ||
                            "Unknown",
                    };
                    props.test_categories.push(testCategory);
                }
                patientTestDetails[packageIndex].test_general_details[
                    index
                ].test_category = testCategory;
            }
        });
        // }
    });

    patient_test.patient_test_details = patientTestDetails;
    // console.log(patient_test.patient_test_details);
    // calculatePaymentCharges();
}


const removePackageDetail = (packageIndex) => {
    if (
        packageIndex < 0 ||
        packageIndex >= patient_test.patient_test_details.length
    ) {
        console.error("Invalid packageIndex:", packageIndex);
        return;
    }
    patient_test.patient_test_details.splice(packageIndex, 1);

    calculatePaymentCharges();
};

const addTestGeneralDetail = (packageIndex) => {
    const packageDetail = patient_test.patient_test_details[packageIndex];
    if (!packageDetail.test_general_details) {
        packageDetail.test_general_details = [];
    }
    packageDetail.test_general_details.push({
        id: null,
        patient_test_detail_id: null,
        lab_test_id: null,
        sub_package_id: null,
        test_category_id: null,
        lab_test: null,
        test_category: null,
        sub_package: null,
        result: null,
        result_2: null,
        lab_test_detail_id: null,
        description: null,
    });
};

const addNewPackageDetail = () => {
    patient_test.patient_test_details.push({
        id: null,
        patient_test_id: null,
        remarks: null,
        package_id: null,
        test_general_details: [
            {
                id: null,
                patient_test_detail_id: null,
                lab_test_id: null,
                sub_package_id: null,
                test_category_id: null,
                lab_test: null,
                test_category: null,
                sub_package: null,
                result: null,
                result_2: null,
                lab_test_detail_id: null,
                description: null,
            },
        ],
    });
};


// relation type
const selectedRelationType = ref();
const relationTypeOptions = computed(() => {
    return [
        { value: "Guardian", label: "Guardian" },
        { value: "Spouse", label: "Spouse" },
        { value: "Parent", label: "Parent" },
        { value: "Sibling", label: "Sibling" },
        { value: "Child", label: "Child" },
        { value: "Friend", label: "Friend" },
        { value: "Relative", label: "Relative" },
    ];
});

const onRelationTypeSelect = async (selectedRelationType) => {
    patient_test.patient_relation_type = selectedRelationType.value;
};
if (props?.patient_test?.patient_relation_type) {
    selectedRelationType.value = relationTypeOptions.value.find(
        (option) => option.value === props.patient_test.patient_relation_type
    );
}

const countries = ref(props.countries || []);

const selectedCountry = ref(null);
if (props?.patient_test) {
    const country = props.countries.find(
        (c) => c.id == props.patient_test.patient_country_id
    );
    if (country) {
        selectedCountry.value = country;
    }
}

const countryOptions = computed(() => {
    return countries.value && countries.value.length ? countries.value : [];
});

const onCountrySelect = async (selectedCountry) => {
    patient_test.patient_country_id = selectedCountry.id;
    selectedState.value = null;
    patient_test.patient_state_id = null;
    states.value = [];
    selectedCity.value = null;
    patient_test.patient_city_id = null;
    cities.value = [];
    await loadStates(selectedCountry.id);
};

const loadStates = async (countryId) => {
    try {
        const response = await axios.get(`/states/${countryId}`);
        states.value = response.data.states;
    } catch (error) {
        console.error("Error fetching states:", error);
    }
};

const states = ref(props.states || []);
const selectedState = ref(null);
if (props?.patient_test) {
    const state = props.states.find(
        (s) => s.id == props.patient_test.patient_state_id
    );
    if (state) {
        selectedState.value = state;
    }
}

const stateOptions = computed(() => {
    return states.value && states.value.length ? states.value : [];
});

const onStateSelect = async (selectedState) => {
    patient_test.patient_state_id = selectedState.id;
    selectedCity.value = null;
    patient_test.patient_city_id = null;
    cities.value = [];
    await loadCities(selectedState.id);
};

const loadCities = async (stateId) => {
    try {
        const response = await axios.get(`/cities-by-state/${stateId}`);
        cities.value = response.data.cities;
    } catch (error) {
        console.error("Error fetching cities:", error);
    }
};

const cities = ref(props.cities || []);
const selectedCity = ref(null);
if (props?.patient_test) {
    const city = props.cities.find(
        (c) => c.id == props.patient_test.patient_city_id
    );
    if (city) {
        selectedCity.value = city;
    }
}

const cityOptions = computed(() => {
    return cities.value && cities.value.length ? cities.value : [];
});

const onCitySelect = (selectedCity) => {
    patient_test.patient_city_id = selectedCity.id;
};

const bloodGroupOptions = computed(() => {
    const bloodGroup = [
        { type: "A+" },
        { type: "A-" },
        { type: "B+" },
        { type: "B-" },
        { type: "AB+" },
        { type: "AB-" },
        { type: "O+" },
        { type: "O-" },
    ];
    return bloodGroup;
});

const selectedBloodGroup = ref(null);
if (props?.patient_test?.patient_blood_group) {
    selectedBloodGroup.value = bloodGroupOptions.value.find(
        (option) => option.type === props.patient_test.patient_blood_group
    );
}
const onBloodGroupSelect = async (selectedBloodGroup) => {
    patient_test.patient_blood_group = selectedBloodGroup.type;
};

const testNatureOptions = computed(() => {
    return [
        { value: "General", label: "General" },
        // { value: "Widal", label: "Widal" },
        { value: "Cross_Match", label: "Cross Match" },
        { value: "Other_Receipt", label: "Other Receipt" },
    ];
});

const selectedTestNature = ref(null);
if (props?.patient_test?.test_nature) {
    selectedTestNature.value = testNatureOptions.value.find(
        (option) => option.value == props.patient_test.test_nature
    );
}
const onTestNatureSelect = async (selectedTestNature) => {
    patient_test.test_nature = selectedTestNature.value;
    patient_test.total_amount = 0;
    calculatePaymentCharges();
};

const testTypeOptions = computed(() => {
    return [
        { value: "IPD", label: "IPD" },
        { value: "OPD", label: "OPD" },
        { value: "Private", label: "Private" },
    ];
});

const selectedTestType = ref(null);
if (props?.patient_test?.test_type) {
    selectedTestType.value = testTypeOptions.value.find(
        (option) => option.value == props.patient_test.test_type
    );
}
const onTestTypeSelect = async (selectedTestType) => {
    patient_test.test_type = selectedTestType.value;
};

const test_categories = ref(props.test_categories || []);
const zf_fees = ref(props.zf_fees || []);
const careoffs = ref(props.careoffs || []);

const selectedCareOff = ref(null);
const selectedZf = ref(null);
const selectedTest = ref(null);

if(props.hasDonationModule){
    if (props?.patient_test) {
        const test_category = props.careoffs.find(
            (c) => c.id == props.patient_test.careoff_id
        );
        if (test_category) {
            selectedCareOff.value = test_category;
        }
    }
}
const onTestSelect = async (selectedDoctor, packageIndex, index) => {
    // console.log("Selected Doctor ID:", selectedDoctor.id);

    if (
        !Array.isArray(patient_test.patient_test_details) ||
        patient_test.patient_test_details.length === 0
    ) {
        console.error(
            "patient_test_details is not properly initialized or is empty"
        );
        return;
    }
    if (
        packageIndex < 0 ||
        packageIndex >= patient_test.patient_test_details.length
    ) {
        console.error("Invalid packageIndex");
        return;
    }
    const packageDetail = patient_test.patient_test_details[packageIndex];
    if (!Array.isArray(packageDetail.test_general_details)) {
        console.error("test_general_details is not an array");
        return;
    }
    if (index < 0 || index >= packageDetail.test_general_details.length) {
        console.error("Invalid index");
        return;
    }
    const testDetail = packageDetail.test_general_details[index];
    if (testDetail) {
        testDetail.test_category_id = selectedDoctor.id;
    }
};

// const onTestSelect = async (selectedDoctor) => {
//   patient_test.test_category_id = selectedDoctor.id;
// };

const onCareOffSelect = async (selectedCareOffs) => {
    patient_test.careoff_id = selectedCareOffs.id;
    if (patient_test.careoff_id == null) {
        patient_test.donor_fee = null;
        checkAmount();
    }
};

const checkAmount = (val, zf_fees) => {
    // if (val > zf_fees) {
    //     patient_test.zf_fee = zf_fees;
    // }
    calculatePaymentCharges();
};

const onServiceSelect = async (selectedDoctor, index) => {
    patient_test.appointment_details[index].fee = selectedDoctor.charges;
};
const statusOptions = computed(() => {
    return [
        { value: "Ordered", label: "Ordered" },
        { value: "In_Progress", label: "In Progress" },
        { value: "Critical", label: "Critical" },
        { value: "Completed", label: "Completed" },
        { value: "Cancelled", label: "Cancelled" },
    ];
});

const selectedStatus = ref(null);
if (props?.patient_test?.status) {
    selectedStatus.value = statusOptions.value.find(
        (option) => option.value == props.patient_test.status
    );
}
const onStatusSelect = async (selectedStatus) => {
    patient_test.status = selectedStatus.value;
};

const removeTestGeneralDetail = (packageIndex, index) => {
    const patientTestDetail = patient_test.patient_test_details[packageIndex];
    if (patientTestDetail && patientTestDetail.test_general_details) {
        patientTestDetail.test_general_details.splice(index, 1);
        calculatePaymentCharges();
    }
};

const addTestWidalDetail = () => {
    patient_test.test_widal_details.push({
        id: null,
        patient_test_id: null,
        widal_test: null,
        1_40: null,
        1_80: null,
        1_160: null,
        1_320: null,
        1_640: null,
    });
};

const removeTestWidalDetail = (index) => {
    patient_test.test_widal_details.splice(index, 1);
    calculatePaymentCharges();
};

const selectedDonorBloodGroup = ref(null);
if (props?.patient_test?.test_cross_match_details?.donor_blood_group) {
    selectedDonorBloodGroup.value = bloodGroupOptions.value.find(
        (option) =>
            option.type ===
            props.patient_test.test_cross_match_details.donor_blood_group
    );
}
const onDonorBloodGroupSelect = async (selectedDonorBloodGroup) => {
    patient_test.test_cross_match_details.donor_blood_group =
        selectedDonorBloodGroup.type;
};

const discountTypeOptions = computed(() => {
    return [
        { label: "Fixed", value: "Fixed" },
        { label: "Percentage", value: "Percentage" },
    ];
});

const selectedDiscountType = ref(null);
if (props?.patient_test?.discount_type) {
    selectedDiscountType.value = bloodGroupOptions.value.find(
        (option) => option.value === props.patient_test.discount_type
    );
}
const onDiscountTypeSelect = async (selectedDiscountType) => {
    patient_test.discount_type = selectedDiscountType.value;
};

if (props?.patient_test?.discount_type) {
    selectedDiscountType.value = discountTypeOptions.value.find(
        (option) => option.value === props.patient_test.discount_type
    );
}

const enabled = ref(
    props?.patient_test?.free_test && props?.patient_test?.free_test == true
        ? true
        : false
);

watch(enabled, (newValue) => {
    patient_test.free_test = newValue;
    patient_test.discount_type = null;
    patient_test.discount_value = null;
    calculatePaymentCharges();
});
const subTotal = ref(0);
const discountAmount = ref(0);
const payableAmount = ref(0);
const calculatePaymentCharges = async () => {
    subTotal.value = 0;
    discountAmount.value = 0;
    payableAmount.value = 0;
    let totalTestAmount = 0;
    if (patient_test?.test_nature == "General") {
        patient_test?.patient_test_details.map((patient_test_details) => {
            if (
                patient_test_details &&
                patient_test_details?.package_id != null
            ) {
                totalTestAmount = parseFloat(totalTestAmount) + +patient_test_details?.selectedPackage?.price;
            }
            else if (
                patient_test_details &&
                patient_test_details?.selectedSubPackages != null && patient_test_details?.selectedSubPackages.length > 0
            ) {
                patient_test_details?.selectedSubPackages.map(res => {
                    totalTestAmount = parseFloat(totalTestAmount) + +res?.price;
                })
            }
            else if (patient_test_details?.test_general_details) {
                patient_test_details?.test_general_details.map(
                    (test_general_detail) => {
                        if (test_general_detail?.lab_test?.price) {
                            totalTestAmount =
                                parseFloat(totalTestAmount) +
                                parseFloat(
                                    test_general_detail?.lab_test?.price
                                );
                        }
                    }
                );
            }
        });
    }
    else if (patient_test?.test_nature == "Other_Receipt") {
        patient_test?.patient_test_details.map((patient_test_details) => {
            if (
                patient_test_details &&
                patient_test_details?.package_id != null
            ) {
                totalTestAmount = parseFloat(totalTestAmount) + +patient_test_details?.selectedPackage?.price;
            }
            else if (
                patient_test_details &&
                patient_test_details?.selectedSubPackages != null && patient_test_details?.selectedSubPackages.length > 0
            ) {
                patient_test_details?.selectedSubPackages.map(res => {
                    totalTestAmount = parseFloat(totalTestAmount) + +res?.price;
                })
            }
            else if (patient_test_details?.test_general_details) {
                patient_test_details?.test_general_details.map(
                    (test_general_detail) => {
                        if (test_general_detail?.lab_test?.price) {
                            totalTestAmount =
                                parseFloat(totalTestAmount) +
                                parseFloat(
                                    test_general_detail?.lab_test?.price
                                );
                        }
                    }
                );
            }
        });
    } else if (patient_test?.test_nature == "Widal") {
        if (patient_test?.test_widal_details) {
            patient_test?.test_widal_details.map((test_widal_detail) => {
                if (test_widal_detail?.widal_test?.charges) {
                    totalTestAmount =
                        parseFloat(totalTestAmount) +
                        parseFloat(test_widal_detail?.widal_test?.charges);
                }
            });
        }
    } else if (patient_test?.test_nature == "Cross_Match") {
        totalTestAmount = parseFloat(patient_test?.total_amount);
    }
    subTotal.value = totalTestAmount;
    patient_test.total_amount = totalTestAmount;

    if (patient_test?.free_test) {
        payableAmount.value = 0;
        discountAmount.value = totalTestAmount;
    }

    if (patient_test?.discount_type == "Fixed") {
        discountAmount.value = patient_test?.discount_value || 0;
    } else if (patient_test?.discount_type == "Percentage") {
        discountAmount.value =
            (parseFloat(patient_test?.discount_value) *
                parseFloat(totalTestAmount)) /
            100 || 0;
    }
    payableAmount.value =
        parseFloat(subTotal.value) -
        parseFloat(discountAmount.value) -
        +patient_test.zf_fee -
        +patient_test.donor_fee;
};

if (props?.patient_test) {
    calculatePaymentCharges();
}
// const handleSubmit = () => {
//     console.log(patient_test);

//     // Display confirmation alert
//     Swal.fire({
//         title: 'Are you sure?',
//         text: 'Do you want to proceed with this action?',
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         cancelButtonColor: '#d33',
//         confirmButtonText: 'Yes, proceed!',
//         cancelButtonText: 'No, cancel!'
//     }).then((result) => {
//         if (result.isConfirmed) {
//             if (patient_test.id) {
//                 updateTest();
//             } else {
//                 patient_test.post(route('patient-tests.store'), patient_test);
//             }
//         } else {
//             Swal.fire({
//                 title: 'Cancelled',
//                 text: 'Your action has been canceled.',
//                 icon: 'info',
//                 confirmButtonText: 'Okay'
//             });
//         }
//     });
// };

const handleSubmit = () => {
    console.log(patient_test);
    if (patient_test.id) {
        updateTest();
    } else {
        patient_test.post(route('patient-tests.store'), patient_test);
    }
};
const updateTest = async () => {
    patient_test.put(route('patient-tests.update', { id: patient_test.id }), {
        preserveScroll: true,
        patient_test,
        onSuccess: (res) => {
            // const bloodTestConsent = patient_test.bloodTestConsent ? 1 : 0;
            // const url = route('patient-tests.detail-print', { id: patient_test.id }) + `?bloodTestConsent=${bloodTestConsent}`;
            // const printWindow = window.open(url, '_blank');
            // setTimeout(() => {
            //     printWindow.focus();
            //     // printWindow.print();
            // }, 500);

        }
    })
}
const loadPatient = async (patientId) => {
    try {
        const response = await axios.get(`/patients/${patientId}`);
        let patient = response?.data?.patient || null;
        console.log('patient', patient);
        if (patient) {
            patient_test.patient_name = patient?.name || null;
            patient_test.patient_cnic = patient?.cnic || null;
            patient_test.patient_age = patient?.dob || null;
            patient_test.patient_gender = patient?.gender || null;
            patient_test.patient_relation_name = patient?.relation_name || null;
            patient_test.patient_relation_type = patient?.relation_type || null;
            patient_test.patient_address = patient?.address || null;
            patient_test.patient_state_id = patient?.state_id || null;
            patient_test.patient_city_id = patient?.city_id || null;
            patient_test.patient_country_id = patient?.country_id || null;
            patient_test.patient_phone = patient?.phone || null;
            patient_test.patient_email = patient?.email || null;
            patient_test.patient_blood_group = patient?.blood_group || null;
            patient_test.patient_rh_factor = patient?.rh_factor || null;
            selectedCountry.value = patient?.country || null;
            selectedState.value = patient?.country || null;
            props.mr_number = patient?.mr_number || null;

            if (patient?.country) {
                selectedCountry.value = patient?.country;
                await loadStates(patient?.country_id);
            }
            if (patient?.state) {
                selectedState.value = patient?.state;
                await loadCities(patient?.state_id);
            }
            if (patient?.city) {
                selectedCity.value = patient?.city;
            }

            if (patient?.blood_group) {
                selectedBloodGroup.value = bloodGroupOptions.value.find(
                    (option) => option.type === patient.blood_group
                );
            }

            if (patient?.relation_type) {
                selectedRelationType.value = relationTypeOptions.value.find(
                    (option) => option.value === patient?.relation_type
                );
            }

            if (patient?.gender) {
                selectedGender.value = genderOptions.value.find(
                    (option) => option.value === patient?.gender
                );
            }
        }
    } catch (error) {
        console.error("Error fetching states:", error);
    }
};
</script>

<script>
import { defineComponent } from "vue";
import InputError from "../../Components/InputError.vue";
export default defineComponent({
    components: {
        Multiselect,
        InputError,
    },
});
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style scoped>
.custom-select {
  color: crimson;
}
</style>
