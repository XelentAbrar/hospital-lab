<template>
    <div class="flex space-x-1">
        <div class="report-container">
            <div class="report-header">
                <div class="flex justify-between items-center">
                    <p class="border border-black px-1 text-xs">
                        {{ patientTest?.creator?.name }}
                    </p>
                    <h1 class="font-semibold text-lg">
                        Chiniot General Hospital
                    </h1>
                    <div class="border border-black px-4 py-0">
                        <p
                            class="font-semibold text-xs"
                            style="margin: 0 !important"
                        >
                            {{ patientTest.test_type }}
                        </p>
                    </div>
                </div>

                <p class="text-xs" v-if="projectType === 'hms'">
                    Nawaz Town Sargodha Road Faisalabad
                </p>
                <p class="text-xs" v-else>209 Jinnah Colony, Faisalabad</p>
                <p class="font-semibold text-xs" v-if="projectType === 'hms'">
                    Ph: 041-8787231-8848060
                </p>
                <p class="font-semibold text-xs" v-else>
                    Ph: 041-2618764-2634890
                </p>
                <p class="text-xs">
                    Under supervision of
                    <span class="font-semibold">MOFAD-E-AMMA</span>
                </p>
                <p class="text-xs">Chiniot Sheikh Association (Regd.)</p>
                <h2 v-if="patientTest.is_printed == '2'" class="bg-gray-200 text-gray-900 text-center border border-gray-300  text-md font-semibold w-24">Duplicate</h2>

                <div class="flex justify-between border-b border-black">
                    <div class="flex flex-col">
                        <p class="text-xs">
                            <span class="font-semibold">Lab. </span
                            >{{ patientTest.lab_no }}
                        </p>
                        <p class="text-xs">
                            <span class="font-semibold">MR. </span
                            >{{ mr_number }}
                        </p>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-xs">
                            <span class="font-semibold">Reg date: </span
                            >{{
                                new Date(patientTest.test_date)
                                    .toLocaleDateString("en-GB")
                                    .replace(/\//g, "-")
                            }}
                            {{ patientTest.test_time }}
                        </p>
                        <p class="text-xs">
                            <span class="font-semibold">Authority: </span
                            >{{ patientTest.welfare }}
                        </p>
                    </div>
                </div>
            </div>
            <!-- <div>
            </div> -->
            <div class="patient-info flex justify-between items-center mb-1">
                <div class="flex justify-between w-full">
                    <p class="text-xs">
                        <strong>P.Name:</strong> {{ patientTest.patient_name }}
                    </p>
                    <p class="text-xs ">
                        <span class="font-semibold">Age/Sex: </span>
                        {{ patientTest.patient_age }} /
                        {{ patientTest.patient_gender }}
                    </p>
                </div>
            </div>
            <div class="patient-info flex justify-between mb-0.5">
                <div class="flex justify-between w-full">
                    <p class="text-xs">
                        <strong>Mob #:</strong> {{ patientTest.patient_phone }}
                    </p>
                    <p class="text-xs">
                       <span class="font-semibold"> Referred By Dr: </span>
                        {{ patientTest.referred_by }}
                    </p>
                </div>
            </div>

            <div class="test-details border-t border-black">
                <table>
                    <thead>
                        <tr>
                            <th class="text-xs">Service</th>
                            <th class="text-xs" style="text-align: center">
                                Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template
                            v-for="(
                                test_general_detail, index
                            ) in patientTest.patient_test_details"
                            :key="index"
                        >
                            <tr v-if="test_general_detail.package != null">
                                <td class="text-xs">
                                    {{ test_general_detail.package?.name }}
                                </td>
                                <td class="text-xs" style="text-align: center">
                                    {{
                                        formatNumber(
                                            test_general_detail.package?.price
                                        )
                                    }}
                                </td>
                            </tr>

                            <template v-if="!test_general_detail.package_id">
                                <template
                                    v-for="(
                                        subpkg, subindex
                                    ) in test_general_detail.test_general_details"
                                    :key="subindex"
                                >
                                    <template
                                        v-if="
                                            subindex === 0 ||
                                            test_general_detail
                                                .test_general_details[
                                                subindex - 1
                                            ].sub_package?.name !==
                                                subpkg.sub_package?.name
                                        "
                                    >
                                        <tr v-if="subpkg.sub_package != null">
                                            <td class="text-xs">
                                                {{ subpkg.sub_package?.name }}
                                            </td>
                                            <td
                                                style="text-align: center"
                                                class="text-xs"
                                            >
                                                {{
                                                    formatNumber(
                                                        subpkg.sub_package
                                                            ?.price
                                                    )
                                                }}
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                                <template
                                    v-if="
                                        !test_general_detail.test_general_details.some(
                                            (detail) => detail.sub_package
                                        )
                                    "
                                >
                                    <template
                                        v-for="(
                                            test, testIndex
                                        ) in test_general_detail.test_general_details"
                                        :key="testIndex"
                                    >
                                        <tr
                                            v-if="
                                                testIndex === 0 ||
                                                test_general_detail
                                                    .test_general_details[
                                                    testIndex - 1
                                                ].lab_test?.name !==
                                                    test.lab_test?.name
                                            "
                                        >
                                            <td class="text-xs">
                                                {{ test.lab_test?.name }}
                                            </td>
                                            <td
                                                style="text-align: center"
                                                class="text-xs"
                                            >
                                                {{
                                                    formatNumber(
                                                        test.lab_test?.price
                                                    )
                                                }}
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                            </template>
                        </template>

                        <tr
                            v-for="test_widal_detail in patientTest.test_widal_details"
                            :key="test_widal_detail.id"
                        >
                            <td class="text-xs">
                                {{ test_general_detail.widal_tests.name }}
                            </td>
                            <td style="text-align: center" class="text-xs">
                                {{
                                    formatNumber(
                                        test_general_detail.widal_tests.charges
                                    )
                                }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="border-t border-black pt-1.5">
                    <div
                        class="total flex justify-between items-center w-4/5 mx-auto"
                    >
                        <p class="w-1/3"><strong>Total:</strong></p>
                        <div class="grid grid-cols-2" v-if="hasDonationModule">
                            <p
                                class="col-span-2 text-xs"
                                v-if="patientTest.zf_fee"
                            >
                                Z/F - {{ formatNumber(patientTest.zf_fee) }}
                                <br />{{ patientTest?.welfare }}
                            </p>
                            <p
                                class="col-span-2 text-xs"
                                v-if="patientTest.donor_fee"
                            >
                                C/O - {{ formatNumber(patientTest.donor_fee)
                                }}<br />{{ patientTest?.careoff?.name }}
                            </p>
                        </div>
                        <p
                            class="w-1/3 text-center border border-black px-4 py-0"
                        >
                            {{
                                hasDonationModule ? formatNumber( patientTest.total_amount - +patientTest.zf_fee - +patientTest.donor_fee) : formatNumber( patientTest.total_amount)
                            }}
                        </p>
                    </div>
                    <div v-if="patientTest.test_nature == 'General'"
                        class="flex text-center border border-black font-bold mt-1"
                    >
                        <p class="noto-nastaliq text-xs">
                            رپورٹ لیتے وقت اپنی سلپ ساتھ لے کر آئیں،اور 15 دن کے
                            اندر اپنی لیب رپورٹ لے لیں، شکریہ۔
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="report-container">
            <div class="report-header">
                <div class="flex justify-between items-center">
                    <p class="border border-black px-1 text-xs">
                        {{ patientTest?.creator?.name }}
                    </p>
                    <h1 class="font-semibold text-lg">
                        Chiniot General Hospital
                    </h1>
                    <div class="border border-black px-4 py-0">
                        <p
                            class="font-semibold text-xs"
                            style="margin: 0 !important"
                        >
                            {{ patientTest.test_type }}
                        </p>
                    </div>
                </div>

                <p class="text-xs" v-if="projectType === 'hms'">
                    Nawaz Town Sargodha Road Faisalabad
                </p>
                <p class="text-xs" v-else>209 Jinnah Colony, Faisalabad</p>
                <p class="font-semibold text-xs" v-if="projectType === 'hms'">
                    Ph: 041-8787231-8848060
                </p>
                <p class="font-semibold text-xs" v-else>
                    Ph: 041-2618764-2634890
                </p>
                <p class="text-xs">
                    Under supervision of
                    <span class="font-semibold">MOFAD-E-AMMA</span>
                </p>
                <p class="text-xs">Chiniot Sheikh Association (Regd.)</p>
                <h2 v-if="patientTest.is_printed == '2'" class="bg-gray-200 text-gray-900 text-center border border-gray-300  text-md font-semibold w-24">Duplicate</h2>

                <div class="flex justify-between border-b border-black">
                    <div class="flex flex-col">
                        <p class="text-xs">
                            <span class="font-semibold">Lab.</span
                            >{{ patientTest.lab_no }}
                        </p>
                        <p class="text-xs">
                            <span class="font-semibold">Mr. </span
                            >{{ mr_number }}
                        </p>

                    </div>
                    <div class="flex flex-col">
                        <p class="text-xs">
                            <span class="font-semibold">Reg date: </span
                            >{{
                                new Date(patientTest.test_date)
                                    .toLocaleDateString("en-GB")
                                    .replace(/\//g, "-")
                            }}
                            {{ patientTest.test_time }}
                        </p>
                        <p class="text-xs">
                            <span class="font-semibold">Authority: </span
                            >{{ patientTest.welfare }}
                        </p>
                    </div>
                </div>
            </div>
            <!-- <div>
            </div> -->
            <div class="patient-info flex justify-between items-center mb-1">
                <div class="flex justify-between w-full">
                    <p class="text-xs">
                        <strong>P.Name:</strong> {{ patientTest.patient_name }}
                    </p>
                    <p class="text-xs ">
                        <span class="font-semibold">Age/Sex: </span
                        >{{ patientTest.patient_age }} /
                        {{ patientTest.patient_gender }}
                    </p>
                </div>
            </div>
            <div class="patient-info flex justify-between mb-0.5">
                <div class="flex justify-between w-full">
                    <p class="text-xs">
                        <strong>Mob #:</strong> {{ patientTest.patient_phone }}
                    </p>
                    <p class="text-xs">
                        <span class="font-semibold"> Referred By Dr: </span>
                        {{ patientTest.referred_by }}
                    </p>
                </div>
            </div>

            <div class="test-details border-t border-black">
                <table>
                    <thead>
                        <tr>
                            <th class="text-xs">Service</th>
                            <th class="text-xs" style="text-align: center">
                                Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template
                            v-for="(
                                test_general_detail, index
                            ) in patientTest.patient_test_details"
                            :key="index"
                        >
                            <tr v-if="test_general_detail.package != null">
                                <td class="text-xs">
                                    {{ test_general_detail.package?.name }}
                                </td>
                                <td class="text-xs" style="text-align: center">
                                    {{
                                        formatNumber(
                                            test_general_detail.package?.price
                                        )
                                    }}
                                </td>
                            </tr>

                            <template v-if="!test_general_detail.package_id">
                                <template
                                    v-for="(
                                        subpkg, subindex
                                    ) in test_general_detail.test_general_details"
                                    :key="subindex"
                                >
                                    <template
                                        v-if="
                                            subindex === 0 ||
                                            test_general_detail
                                                .test_general_details[
                                                subindex - 1
                                            ].sub_package?.name !==
                                                subpkg.sub_package?.name
                                        "
                                    >
                                        <tr v-if="subpkg.sub_package != null">
                                            <td class="text-xs">
                                                {{ subpkg.sub_package?.name }}
                                            </td>
                                            <td
                                                style="text-align: center"
                                                class="text-xs"
                                            >
                                                {{
                                                    formatNumber(
                                                        subpkg.sub_package
                                                            ?.price
                                                    )
                                                }}
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                                <template
                                    v-if="
                                        !test_general_detail.test_general_details.some(
                                            (detail) => detail.sub_package
                                        )
                                    "
                                >
                                    <template
                                        v-for="(
                                            test, testIndex
                                        ) in test_general_detail.test_general_details"
                                        :key="testIndex"
                                    >
                                        <tr
                                            v-if="
                                                testIndex === 0 ||
                                                test_general_detail
                                                    .test_general_details[
                                                    testIndex - 1
                                                ].lab_test?.name !==
                                                    test.lab_test?.name
                                            "
                                        >
                                            <td class="text-xs">
                                                {{ test.lab_test?.name }}
                                            </td>
                                            <td
                                                style="text-align: center"
                                                class="text-xs"
                                            >
                                                {{
                                                    formatNumber(
                                                        test.lab_test?.price
                                                    )
                                                }}
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                            </template>
                        </template>

                        <tr
                            v-for="test_widal_detail in patientTest.test_widal_details"
                            :key="test_widal_detail.id"
                        >
                            <td class="text-xs">
                                {{ test_general_detail.widal_tests.name }}
                            </td>
                            <td style="text-align: center" class="text-xs">
                                {{
                                    formatNumber(
                                        test_general_detail.widal_tests.charges
                                    )
                                }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="border-t border-black pt-1.5">
                    <div
                        class="total flex justify-between items-center w-4/5 mx-auto"
                    >
                        <p class="w-1/3"><strong>Total:</strong></p>
                        <div class="grid grid-cols-2" v-if="hasDonationModule">
                            <p
                                class="col-span-2 text-xs"
                                v-if="patientTest.zf_fee"
                            >
                                Z/F - {{ formatNumber(patientTest.zf_fee) }}
                                <br />{{ patientTest?.welfare }}
                            </p>
                            <p
                                class="col-span-2 text-xs"
                                v-if="patientTest.donor_fee"
                            >
                                C/O - {{ formatNumber(patientTest.donor_fee)
                                }}<br />{{ patientTest?.careoff?.name }}
                            </p>
                        </div>
                        <p
                            class="w-1/3 text-center border border-black px-4 py-0"
                        >
                            {{
                                hasDonationModule ? formatNumber(
                                    patientTest.total_amount -
                                        +patientTest.zf_fee -
                                        +patientTest.donor_fee
                                ) : formatNumber(
                                    patientTest.total_amount
                                )
                            }}
                        </p>
                    </div>
                    <div v-if="patientTest.test_nature == 'General'"
                        class="flex text-center border border-black font-bold mt-1"
                    >
                        <p class="noto-nastaliq text-xs">
                            رپورٹ لیتے وقت اپنی سلپ ساتھ لے کر آئیں،اور 15 دن کے
                            اندر اپنی لیب رپورٹ لے لیں، شکریہ۔
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        patient_test: {
            type: Object,
            required: true,
        },
        hasDonationModule: Boolean,
        user: Object,
        mr_number: String,
    },

    mounted() {
        setTimeout(() => {
        window.print();
        setTimeout(() => {
            window.close();
        }, 500);
    }, 500);
        // setTimeout(() => {
            // window.print();
            // setTimeout(() => {
            //     window.close();
            // }, 800);
        // }, 800);
        // this.$nextTick(() => {
        //     window.print();
        // });
        window.addEventListener("keydown", this.handleKeydown);
    },
    // beforeDestroy() {
    //     setTimeout(() => {
    //         //   window.print();
    //         setTimeout(() => {
    //             window.close();
    //         }, 800);
    //     }, 800);
    //     window.removeEventListener("keydown", this.handleKeydown);
    // },
    data() {
        return {
            projectType: import.meta.env.VITE_PROJECT_TYPE,
        };
    },

    computed: {
        patientTest() {
            return this.patient_test;
        },
        patientAge() {
            const today = new Date();
            const dob = new Date(this.patient_test.patient_dob);
            let age = today.getFullYear() - dob.getFullYear();
            const monthDifference = today.getMonth() - dob.getMonth();
            if (
                monthDifference < 0 ||
                (monthDifference === 0 && today.getDate() < dob.getDate())
            ) {
                age--;
            }
            return age;
        },
    },
    methods: {
        handleKeydown(event) {
            if (event.ctrlKey && event.key === "p") {
                event.preventDefault();
                window.location.reload();
            }
        },
        formatNumber(number) {
            return new Intl.NumberFormat().format(number);
        },
    },
};
</script>

<style scoped>
.report-container {
    width: 70%;
    margin: 0 auto;
    padding: 10px;
    border: 1px solid #000;
    font-family: Arial, sans-serif;
}
.report-header {
    text-align: center;
    margin-bottom: 5px;
}
.patient-info,
.test-details {
    margin-bottom: 5px;
}
.patient-info p,
.test-details p {
    margin: 5px 0;
}
.test-details table {
    width: 90%;
    border-collapse: collapse;
    margin-bottom: 5px !important;
    margin: 0 auto;
}
/* .test-details table,
.test-details th,
.test-details td {
    border: 1px solid #000;
} */
.test-details th,
.test-details td {
    padding: 2px;
    text-align: left;
}
.total p {
    margin: 2px 0;
}
.noto-nastaliq {
    font-family: "Noto Nastaliq Urdu", serif;
}
</style>
