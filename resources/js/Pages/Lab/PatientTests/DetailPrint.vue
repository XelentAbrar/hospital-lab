<template>
    <div class="pt_8 relative mx-auto flex flex-col rounded-lg items-center h-full">
        <div class="vector" style="position: fixed;top: 33%; left: 28%;"></div>
        <table style="width: 100%;">
            <thead>
                <tr>
                    <td>
                        <div class="header-space">&nbsp;</div>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px_8">
                        <div class="  px-4 pb-2" style="width: 100%">
                            <div class="  "
                                v-if="patient_test.test_nature == 'General' || patient_test.test_nature == 'Other_Receipt'">
                                <div v-if="lab_results.length > 0" class="border border-gray-600 pt-2 pb-4 px-4 ">
                                    <div>
                                        <table class="w-full mt-3 table">
                                            <thead>
                                                <tr>
                                                <!-- <th colspan="4" class="text-lg font-bold text-center pb-2">
                                                    Sample Test
                                                    </th> -->
                                                </tr>
                                                <tr class="border-t border-b border-gray-300">
                                                    <th class="font-semibold text-md p-2 text-left">TEST</th>
                                                    <th class="font-semibold text-md p-2">UNIT</th>
                                                    <th class="font-semibold text-md p-2">NORMAL VALUES</th>
                                                    <th class="font-semibold text-md p-2">RESULT</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template v-for="(result, index) in lab_results" :key="index">
                                                    <tr v-if="result.result || result.result_2"
                                                        class="border-b border-dashed border-gray-500">
                                                        <td class="font-semibold py-1 w-60 break-all">{{ result.test_name }}</td>

                                                        <td class="text-center font-semibold py-1">
                                                            {{ result.unit }}
                                                        </td>
                                                        <td class="text-center font-semibold py-1 break-all w-44">
                                                            {{ result?.value_range }}
                                                            <span v-html="formatValue(result.min_value)"></span>
                                                            <span v-html="formatValue(result.max_value)"></span>
                                                        </td>
                                                        <td class="text-center font-semibold py-1">
                                                            {{ result.result }}
                                                            {{ result.result_2 }}
                                                        </td>
                                                    </tr>
                                                    <tr class="border-y border-black" v-if="result?.description">
                                                        <td colspan="4">
                                                            <h4 class="font-medium text-xl mb-0 leading-tight">
                                                                Remarks :</h4>
                                                            <div class="flex justify-between items-center">
                                                                <p class="text-sm">
                                                                    {{ result?.description }}
                                                                </p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- <div class="border-y my-3">
                                    </div> -->
                                    <div class="hidden"  :style="package_results.length > 0 && lab_results.length >0 ? 'page-break-after: always;' : ''" ></div>
                                    <!-- <div style="page-break-after: always;" v-if="index != lab_results.length - 1" >{{ index }}</div> -->
                                </div>
                            </div>
                            <!-- <div style="break-after: page" v-if="index > lab_results.length - 1"></div> -->
                            <div v-if="patient_test.test_nature === 'General' || patient_test.test_nature === 'Other_Receipt'">
                                <div v-if="package_results">
                                    <template v-for="(pkg, index1) in package_results" :key="index1">
                                        <div class="border border-gray-600">
                                            <table class="w-full">
                                                <thead>
                                                    <tr>
                                                        <th colspan="4" class="text-md font-semibold text-center pb-2">
                                                            <span v-if="index1 === 0 || pkg.package_category !== package_results[index1 - 1].package_category" class="font-bold text-lg">
                                                                {{ pkg.package_category }}
                                                            </span>
                                                        </th>
                                                    </tr>
                                                    <tr class="border-t border-b border-gray-300">
                                                        <th class="font-bold text-md p-2 text-left">TEST</th>

                                                        <th class="font-bold text-md p-2">UNIT</th>
                                                        <th class="font-bold text-md p-2">NORMAL VALUES</th>
                                                        <th class="font-bold text-md p-2">RESULT</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template v-for="(lab_result, labIndex) in pkg.tests" :key="labIndex">
                                                        <tr v-if="(labIndex === 0 || pkg.tests[labIndex - 1].subPackage !== lab_result.subPackage) && (lab_result.result || lab_result.result_2)">
                                                            <td colspan="4">
                                                                <span class="underline font-bold text-md">{{ lab_result.subPackage }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr v-if="lab_result.result || lab_result.result_2" class="border-b border-dashed border-gray-500 text-sm">
                                                            <td class="-py-12 font-semibold w-60 break-all">{{ lab_result.test_name }}</td>
                                                            <td class="text-center font-semibold">{{ lab_result.unit }}</td>
                                                            <td class="text-center font-semibold  whitespace-normal break-normal w-44">
                                                                {{ lab_result?.value_range }}
                                                                <span v-if="lab_result?.min_value"  v-html="formatValue(lab_result?.min_value)"></span>
                                                                <span v-if="lab_result?.max_value"   v-html="formatValue(lab_result?.max_value)"></span>
                                                            </td>
                                                            <td class="text-center font-semibold whitespace-normal break-all">{{ lab_result.result_2 }} {{ lab_result.result }}</td>

                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="border-y border-black mt-2 my-1" v-if="pkg?.remarks">
                                            <h4 class="font-medium text-2xl">Report Remarks :</h4>
                                            <div class="flex justify-between items-center mb-2">
                                                <h4 class="text-xl" v-if="pkg.remarks">{{ pkg.remarks }}</h4>
                                            </div>
                                        </div>
                                        <div style="page-break-after: always;" v-if="index1 !== package_results.length - 1 && package_results[index1].package_category !== package_results[index1 + 1].package_category"></div>
                                    </template>
                                </div>
                            </div>

                            <!-- <div
                                v-if="patient_test.test_nature === 'General' || patient_test.test_nature === 'Other_Receipt'">
                                <div v-if="package_results">
                                    <template v-for="(pkg, index1) in package_results" :key="index1">
                                        <div class="border border-gray-600"
                                        >
                                            <table class="w-full">
                                                <thead>
                                                    <tr>
                                                        <th colspan="4" class="text-lg font-semibold text-center pb-2">
                                                            {{ pkg.package_category }}
                                                        </th>
                                                    </tr>

                                                    <tr class="border-t border-b border-gray-300">
                                                        <th class="font-semibold p-2 text-left">TEST</th>
                                                        <th class="font-semibold p-2">RESULT</th>
                                                        <th class="font-semibold p-2">UNIT</th>
                                                        <th class="font-semibold p-2">NORMAL VALUES</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template v-for="(lab_result, labIndex) in pkg.tests"
                                                        :key="labIndex">
                                                        <tr
                                                            v-if="(labIndex === 0 || pkg.tests[labIndex - 1].subPackage !== lab_result.subPackage) && (lab_result.result || lab_result.result_2)">
                                                            <td colspan="4">
                                                                <span class="underline font-semibold text-lg">{{
                                                                    lab_result.subPackage }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr v-if="lab_result.result || lab_result.result_2"
                                                            class="border-b border-dashed border-gray-500 text-sm">
                                                            <td class="-py-12">{{ lab_result.test_name }}</td>
                                                            <td class="text-center whitespace-normal break-all">
                                                                {{ lab_result.result_2 }} {{ lab_result.result }}
                                                            </td>
                                                            <td class="text-center text-sm">{{ lab_result.unit }}</td>
                                                            <td class="text-center text-sm whitespace-normal break-normal w-44">
                                                                {{ lab_result?.value_range }}
                                                                <span v-if="lab_result?.min_value" class="text-sm"
                                                                    v-html="formatValue(lab_result?.min_value)"></span>
                                                                <span v-if="lab_result?.max_value" class="text-sm"
                                                                    v-html="formatValue(lab_result?.max_value)"></span>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="border-y border-black mt-2 my-1" v-if="pkg?.remarks">
                                            <h4 class="font-medium text-2xl">Report Remarks :</h4>
                                            <div class="flex justify-between items-center mb-2">
                                                <h4 class="text-xl" v-if="pkg.remarks">{{ pkg.remarks }}</h4>
                                            </div>
                                        </div>
                                        <div style="page-break-after: always;" v-if="index1 != package_results.length - 1" ></div>
                                    </template>
                                </div>
                            </div> -->

                            <div class="pt-2 px-4" v-else>
                                <h2 class="text-2xl font-semibold text-center">
                                    Complete Blood Count(CBC) with absolute Count
                                </h2>
                                <table class="w-full mt-3">
                                    <thead>
                                        <tr class="border-t border-b border-gray-300">
                                            <th class="font-semibold p-2 text-left">TEST</th>
                                            <th class="font-semibold p-2">RESULT</th>
                                            <th class="font-semibold p-2">UNIT</th>
                                            <th class="font-semibold p-2">NORMAL VALUES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--Blood Group-->
                                        <p class="underline font-medium text-lg">
                                            Sample: Blood Cross Match
                                        </p>

                                        <tr class="border-b border-dashed border-gray-500">
                                            <td>Blood Bag No.</td>
                                            <td class="text-center">
                                                {{
                                                    patient_test?.test_cross_match_details?.blood_bag_no
                                                }}
                                            </td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                        <tr class="border-b border-dashed border-gray-500">
                                            <td>Blood Component</td>
                                            <td class="text-center">
                                                {{
                                                    patient_test?.test_cross_match_details
                                                        ?.blood_component
                                                }}
                                            </td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                        <tr class="border-b border-dashed border-gray-500">
                                            <td>Blood Volumn</td>
                                            <td class="text-center">
                                                {{
                                                    patient_test?.test_cross_match_details?.blood_volumn
                                                }}
                                            </td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                        <tr class="border-b border-dashed border-gray-500">
                                            <td>Bleeding Date</td>
                                            <td class="text-center">
                                                {{
                                                    patient_test?.test_cross_match_details?.bleeding_date
                                                }}
                                            </td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                        <tr class="border-b border-dashed border-gray-500">
                                            <td>Bag Expiry Date</td>
                                            <td class="text-center">
                                                {{
                                                    patient_test?.test_cross_match_details
                                                        ?.bag_expiry_date
                                                }}
                                            </td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                        <tr class="border-b border-dashed border-gray-500">
                                            <td>Donor name</td>
                                            <td class="text-center">
                                                {{ patient_test?.test_cross_match_details?.donor_name }}
                                            </td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                        <tr class="border-b border-dashed border-gray-500">
                                            <td>Donor name</td>
                                            <td class="text-center">
                                                {{ patient_test?.test_cross_match_details?.donor_name }}
                                            </td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                        <tr class="border-b border-dashed border-gray-500">
                                            <td>Donor blood group</td>
                                            <td class="text-center">
                                                {{
                                                    patient_test?.test_cross_match_details
                                                        ?.donor_blood_group
                                                }}
                                            </td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                        <tr class="border-b border-dashed border-gray-500">
                                            <td>Donor RH factor</td>
                                            <td class="text-center">
                                                {{
                                                    patient_test?.test_cross_match_details
                                                        ?.donor_rh_factor
                                                }}
                                            </td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                        <tr class="border-b border-dashed border-gray-500">
                                            <td>Donor age</td>
                                            <td class="text-center">
                                                {{ patient_test?.test_cross_match_details?.donor_age }}
                                            </td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                        <tr class="border-b border-dashed border-gray-500">
                                            <td>Donor blood pressure</td>
                                            <td class="text-center">
                                                {{
                                                    patient_test?.test_cross_match_details
                                                        ?.donor_blood_pressure
                                                }}
                                            </td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                        <tr class="border-b border-dashed border-gray-500">
                                            <td>Donor anti HCV</td>
                                            <td class="text-center">
                                                {{
                                                    patient_test?.test_cross_match_details?.donor_anti_hcv
                                                }}
                                            </td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                        <tr class="border-b border-dashed border-gray-500">
                                            <td>Donor hbsag</td>
                                            <td class="text-center">
                                                {{
                                                    patient_test?.test_cross_match_details?.donor_hbsag
                                                }}
                                            </td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                        <tr class="border-b border-dashed border-gray-500">
                                            <td>Donor HIV antibodies</td>
                                            <td class="text-center">
                                                {{
                                                    patient_test?.test_cross_match_details
                                                        ?.donor_hiv_antibodies
                                                }}
                                            </td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                        <tr class="border-b border-dashed border-gray-500">
                                            <td>Donor cross match</td>
                                            <td class="text-center">
                                                {{
                                                    patient_test?.test_cross_match_details
                                                        ?.donor_cross_match
                                                }}
                                            </td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--Complete blood count-->

                            </div>

                        </div>
                        <div class="border-y py-3" v-if="patient_test.test_nature == 'Cross_Match'">
                            <div class="flex justify-between items-center mb-2">
                                <!-- <h4 class="font-medium text-2xl">

                                </h4> -->
                                <h4 class="font-medium text-xl noto-nastaliq leading-10">
                                    (Consent for Blood Transfusion)اجازت نامہ براۓانتقال خون
                                </h4>
                            </div>
                            <p class="text-right noto-nastaliq leading-8">
                                مجھے خون کی ضرورت کے بارے میں آگاہ کردیا گیاہےـ انتقال خون
                                کےبعدکی پیچدگیوں کے بارے میں بتادیا گیا ہے ـ اب میں سمجھتا /
                                سمجھتی ہوں کہ انتقال خون کیلۓ لیے گۓ خون کے تمام ٹیسٹ
                                اورلیبارٹری سےتصدیق شدہ ہونےکےباوجود خون لگانے کے کچھ
                                مضراثرات مرتب ہو سکتے ہیں ـ جن کے لیے مزید پیشگی احتیاطی
                                تدابیر ممکن نہیں ـ یہ سب جانتے ہوۓمیں خودکوخون لگانے کی
                                اجازت دیتا/دیتی ہوں ـ
                            </p>
                            <div class="flex items-center space-x-1 justify-end mt-4">
                                <p class="whitespace-nowrap noto-nastaliq">
                                    -----------نام دستخط ڈاکٹر
                                </p>
                                <p class="whitespace-nowrap noto-nastaliq">
                                    -----------نام/دستخط اسٹاف
                                </p>
                                <p class="whitespace-nowrap noto-nastaliq">
                                    -----------مریض سے رشتہ
                                </p>
                                <p class="whitespace-nowrap noto-nastaliq">
                                    -----------دستخط مریض/لواحقین
                                </p>
                            </div>
                        </div>



                        <div class="border-y py-3" v-if="bloodTestConsent === '1'">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-medium text-2xl">
                                    Consent for Blood Transfusion
                                </h4>
                                <h4 class="font-medium text-xl noto-nastaliq leading-10">
                                    اجازت نامہ براۓانتقال خون
                                </h4>
                            </div>
                            <p class="text-right noto-nastaliq leading-8">
                                مجھے خون کی ضرورت کے بارے میں آگاہ کردیا گیاہےـ انتقال خون
                                کےبعدکی پیچدگیوں کے بارے میں بتادیا گیا ہے ـ اب میں سمجھتا /
                                سمجھتی ہوں کہ انتقال خون کیلۓ لیے گۓ خون کے تمام ٹیسٹ
                                اورلیبارٹری سےتصدیق شدہ ہونےکےباوجود خون لگانے کے کچھ
                                مضراثرات مرتب ہو سکتے ہیں ـ جن کے لیے مزید پیشگی احتیاطی
                                تدابیر ممکن نہیں ـ یہ سب جانتے ہوۓمیں خودکوخون لگانے کی
                                اجازت دیتا/دیتی ہوں ـ
                            </p>
                            <div class="flex items-center space-x-1 justify-end mt-4">
                                <p class="whitespace-nowrap noto-nastaliq">
                                    -----------نام دستخط ڈاکٹر
                                </p>
                                <p class="whitespace-nowrap noto-nastaliq">
                                    -----------نام/دستخط اسٹاف
                                </p>
                                <p class="whitespace-nowrap noto-nastaliq">
                                    -----------مریض سے رشتہ
                                </p>
                                <p class="whitespace-nowrap noto-nastaliq">
                                    -----------دستخط مریض/لواحقین
                                </p>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td>
                        <div class="footer-space">&nbsp;</div>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="header">
            <!-- <img src="/images/print-design.jpg" style="height:120px;width:100%;" alt=""> -->
            <div class="flex items-center justify-between" style="padding: 10px">
                <div class="flex items-center space-x-2 text-left">
                    <!-- <img class="h-auto w-12" src="//build/assets/logo-20b0503a.png" alt="" /> -->
                    <img class="h-auto w-12" src="/images/logo-20b0503a.png" alt="" />
                    <div>
                        <h1 class="doctor-info heading font-bold">
                            CHINIOT GENERAL HOSPITAL
                        </h1>
                        <p class="font-medium text-xs">
                            Under: MOFAD-E-AMMA Chiniot Sheikh Association.
                        </p>
                        <p class="text-xs" v-if="projectType === 'hms'">Opposit Sui Gas Office Sargodha Road Faisalabad
                        </p>
                        <p class="text-xs" v-else>209 Jinnah Colony , Faisalabad</p>
                    </div>
                </div>
                <div class="space-y-2 ml-2">
                    <div class="flex items-center space-x-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-6 h-6 text-green-500">
                            <path fill-rule="evenodd"
                                d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-xs" v-if="projectType === 'hms'">Ph: 041-8848060-8787231</p>
                        <p class="text-xs" v-else>Ph: 041-2618764-2634890</p>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6" style="color: #fbbf24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                        </svg>
                        <p class="text-xs">mail@gmail.com</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center" style="width: 100%">
                <div class="trapezoid"></div>
                <div class="flex items-center space-x-2 -ml-2">
                    <div class="parallelogram"></div>
                    <div class="parallelogram"></div>
                    <div class="parallelogram"></div>
                    <div class="parallelogram"></div>
                    <div style="margin-left:0px; width: 17px;height: 33px;transform: skew(46deg);background: #fff;">
                    </div>
                </div>
                <div class="border-b-[12px] border-gray-800 h-7 w-3/4 -ml-0.5"></div>
            </div>
            <div class="grid grid-cols-9 gap-4 divide-x" style="padding: 10px 16px">
                <div class="col-span-3">
                    <h2 class="doctor-info font-semibold text-xs"><span class="w-24 inline-flex">Patient Name </span>:
                        <span>{{
                            patient_test.patient_name }}</span>
                    </h2>
                    <p class="text-xs"><span class="w-24 inline-flex">Age/Sex </span>: <span class="text-xs">{{
                            patient_test.patient_age }}/{{ patient_test.patient_gender }}</span></p>
                    <p class="text-xs"><span class="w-24 inline-flex">Ref. By DR</span>: <span class="text-xs">{{
                            patient_test.referred_by }}</span></p>
                </div>
                <div class="pl-1.5 col-span-2">
                    <p class="text-xs"><span class="w-14 inline-flex font-medium">Lab No</span>: <span class="text-xs">{{
                        patient_test.lab_no }}</span></p>
                    <p class="text-xs"><span class="w-14 inline-flex font-medium">CH No #</span>: <span class="text-xs">{{
                        patient_test.chart_no }}</span></p>
                    <p class="text-xs"><span class="w-14 inline-flex font-medium">MR #</span>: <span class="text-xs">{{
                        patient_test?.id }}</span></p>
                    <!-- <h2 class="doctor-info font-semibold">
            Sample Collected at:
          </h2>
          <p class="text-sm">125 House sarghoda road, fsd</p>
          <p> <span class="font-semibold border px-2 py-1 text-center flex justify-center"> {{ patient_test.test_type }}</span></p> -->
                </div>
                <div class="pl-1.5 col-span-4">
                    <p class="text-xs whitespace-nowrap">
                        <span class="font-bold">Patient Registered on: </span>
                        {{ new Date(patient_test.created_at).toLocaleDateString('en-GB').replace(/\//g, '-') + ' ' + new
                            Date(patient_test.created_at).toTimeString().split(' ')[0].slice(0, 5) }}

                    </p>
                    <p class="text-xs whitespace-nowrap">
                        <span class="font-bold">Sample Collected on: </span>

                        {{ patient_test.date_sample
                            ? new Date(patient_test.date_sample).toLocaleDateString('en-GB').replace(/\//g, '-') + ' ' + new
                                Date(patient_test.date_sample).toLocaleTimeString('en-GB', {
                                    hour: '2-digit', minute: '2-digit'
                        })
                        : '' }}

                    </p>
                    <p class="text-xs whitespace-nowrap">
                        <span class="font-bold">Report Verified on: </span>
                        {{ patient_test.date_report_verification
                            ? new Date(patient_test.date_report_verification).toLocaleDateString('en-GB').replace(/\//g,
                                '-') + ' '
                            + new Date(patient_test.date_report_verification).toLocaleTimeString('en-GB', {
                                hour: '2-digit',
                        minute:
                        '2-digit' })
                        : '' }}
                    </p>
                    <p class="text-xs whitespace-nowrap">
                        <span class="font-bold">Report Printed on: </span>{{ formattedDate }}
                    </p>
                </div>
            </div>
            <div class="border-b-4 border-gray-700 w-full"></div>
        </div>
        <div class="footer relative">
            <div class="text-black h-auto w-full grid grid-cols-3 gap-6 place-items-center py-2 px_8 pt-12">
                <div>
                    <h2 class="underline font-bold text-center text-medium">Result Entered By</h2>
                    <!-- <p class="font-semibold doctor-info text-xs">(Technician)</p> -->
                    <p class="text-xs">{{ patient_test?.updator?.name }}</p>
                </div>

                <div>
                   <!--  <h2 class="doctor-info text-lg font-semibold">Lab Incharge</h2> -->
                    <!-- <h3 class="doctor-info">(MD, Pathologist)</h3> -->
                </div>
                <div v-if="projectType === 'hms'">
                    <h2 class="font-bold text-medium text-center underline">Result Verified By</h2>
                    <h3 class="doctor-info text-center font-semibold whitespace-nowrap text-sm">
                        DR.AZFAR SATTAR HAQQANI
                    </h3>
                    <h4 class="doctor-info text-center font-bold text-sm">CONSULTANT PATHOLOGIST</h4>
                </div>
                <div v-if="projectType === 'jinnah'">
                    <h2 class="font-bold text-medium text-center underline">Result Verified By</h2>
                    <h3 class="doctor-info text-center font-semibold whitespace-nowrap text-sm">
                        DR. M.Kashif Sarfraz
                    </h3>
                    <h4 class="doctor-info text-center font-semibold text-xs">CONSULTANT PATHOLOGIST</h4>
                    <p class="whitespace-nowrap text-xs text-center">MBBS(MD), M.Phill Histopathology</p>
                    <p class="whitespace-nowrap text-xs text-center">University Of Health Science Lahore</p>
                </div>
                <!-- <div>
          <h2 class="doctor-info text-lg font-semibold">{{ patient_test?.doctor?.name }}</h2>
          <template v-for="(education, index) in patient_test?.doctor?.education">
          <h3 class="doctor-info">({{ education.degree }}<span v-if=" patient_test.doctor.education.length - 1 != index">,</span>)</h3>
        </template>
        </div> -->

            </div>
            <div class="border-t-2 border-gray-500 w-full flex items-end justify-end space-x-12 pt-2 ">
                <!-- <p class="whitespace-nowrap">
          <span class="font-medium">Generated on: </span>{{ formattedDate }}
        </p> -->
                <!-- <div class="page-number">
            Page <span class="page"></span> of <span class="topage"></span>
        </div> -->
            </div>
            <p class="text-sm ml-3 mr-2">Tests are performed with utmost care; however, errors/omissions are possible. Test results should not be compared with other labs' results due to differences in kits/automation. This sample was taken without CNIC confirmation, holding no official importance and no legal accountability. A computerized print may not need a signature.</p>
            <!-- <img src="/images/report-footer.jpg" style="width: 100% ; height: 20%;" class="absolute bottom-0" alt="" /> -->

            <div class="flex items-center" style="width: 100%" v-if="projectType == 'hms'">
                <div class="trapezoid"></div>
                <div class="flex items-center space-x-2 -ml-2">
                    <div class="parallelogram"></div>
                    <div class="parallelogram"></div>
                    <div class="parallelogram"></div>
                    <div class="parallelogram"></div>
                    <div style="margin-left:0px; width: 17px;height: 33px;transform: skew(46deg);background: #fff;">
                    </div>
                </div>
                <div class="border-b-[16px] border-gray-800 h-8 w-3/4 -ml-0.5"></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        lab_results: Array,
        bloodTestConsent: Object,
        patient_test: {
            type: Object,
            required: true,
        },
        package_results: Array,
        updator: Object,
    },
    data() {
        return {
            formattedDate: "",
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
        categorizedLabResults() {
            const categorized = {};
            this.lab_results.forEach(result => {
                if (!categorized[result.testCategory]) {
                    categorized[result.testCategory] = [];
                }
                categorized[result.testCategory].push(result);
            });
            return categorized;
        },
        categorizedPackageResults() {
            return this.package_results.map(pkg => {
                const categorized = {};
                pkg.tests.forEach(test => {
                    if (!categorized[test.testCategory]) {
                        categorized[test.testCategory] = [];
                    }
                    categorized[test.testCategory].push(test);
                });
                return { ...pkg, categorizedTests: categorized };
            });
        }

    },
    mounted() {
        var currentDate = new Date();
        this.formattedDate = this.formatDate(currentDate);
        window.onload = function () {
            let totalPages = Math.ceil(document.body.scrollHeight / window.innerHeight);
            document.querySelector('.total-pages').innerText = `Total Pages: ${totalPages}`;
        };

        this.$nextTick(() => {
        window.print();
        });
        window.addEventListener('keydown', this.handleKeydown);

    },
    beforeDestroy() {
        window.removeEventListener('keydown', this.handleKeydown);
    },
    methods: {
        handleKeydown(event) {
            if (event.ctrlKey && event.key === 'p') {
                event.preventDefault();
                window.location.reload();
            }
        },
        formatValue(value) {
            if (value === null) {
                return '';
            }
            const ranges = value.split(/(?<=\d\.?\d*)\s*(?=[A-Z])/);
            return ranges.join('<br>');
        },
        // formatDate(date) {
        //   var months = [
        //     "Jan",
        //     "Feb",
        //     "Mar",
        //     "Apr",
        //     "May",
        //     "Jun",
        //     "Jul",
        //     "Aug",
        //     "Sep",
        //     "Oct",
        //     "Nov",
        //     "Dec",
        //   ];
        //   var day = date.getDate();
        //   var monthIndex = date.getMonth();
        //   var year = date.getFullYear();
        //   var hours = date.getHours();
        //   var minutes = date.getMinutes();
        //   var ampm = hours >= 12 ? "PM" : "AM";
        //   hours = hours % 12;
        //   hours = hours ? hours : 12;
        //   minutes = minutes < 10 ? "0" + minutes : minutes;
        //   var strTime = hours + ":" + minutes + " " + ampm;
        //   return day + " " + months[monthIndex] + ", " + year + " " + strTime;
        // },
        formatDate(date) {
            var day = date.getDate();
            var monthIndex = date.getMonth() + 1;
            var year = date.getFullYear();
            var hours = date.getHours();
            var minutes = date.getMinutes();
            var seconds = date.getSeconds();

            day = day < 10 ? '0' + day : day;
            monthIndex = monthIndex < 10 ? '0' + monthIndex : monthIndex;
            hours = hours < 10 ? '0' + hours : hours;
            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;

            return `${day}-${monthIndex}-${year} ${hours}:${minutes}:${seconds}`;
        }



    },
};
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400..700&display=swap");

.page-break {
    page-break-before: always;
}

/* .page-number:before {
            content: "Page " counter(page);
        } */
@page {
    margin: 1in;
}

@media print {
    @page {
        size: A4;
        margin: 0;
    }

    .page-break {
        page-break-before: always;
    }

    .page-number:after {
        content: "Page " counter(page);
    }

    body {
        margin: 0;
    }

    table thead th {
        font-size: 11px;
        font-weight: 600;
    }

    td {
        font-size: 11px !important;
        font-weight: 500;
    }
}


.pt_10 {
    padding-top: 10mm;
}

td {
    font-size: 11px !important;
    font-weight: 500;
}

.mx_8 {
    margin-left: 8mm;
    margin-right: 8mm;
}

.px_8 {
    padding-left: 8mm;
    padding-right: 8mm;
}

.vector {
    position: absolute;
    width: 100mm;
    height: 100mm;
    /* background-image: url("//build/assets/logo-20b0503a.png"); */
    background-image: url("/images/logo-20b0503a.png");
    opacity: 0.1;
    background-position: center;
    background-size: 100% 100%;
}

.doctor-info {
    font-family: "Helvetica", Arial, sans-serif;
}

.heading {
    font-size: 24px;
    line-height: 2.15rem;
}

.trapezoid {
    border-bottom: 33px solid #80a5d6;
    border-right: 36px solid transparent;
    height: 0;
    width: 35%;
}

/* .parallelogram {
  width: 10px;
  height: 33px;
  transform: skew(46deg);
  background: #222;
} */
.parallelogram {
    width: 12px;
    height: 33px;
    transform: skew(46deg);
    background: #222;
    border-left: 12px solid #222
}

.trapezoid1 {
    clip-path: polygon(0 0, 100% 0, 100% 100%, 35px 100%);
    height: 33px;
    width: 65%;
    background: #014dae;
}

.header,
.header-space {
    height: 220px;
}

.footer {
    height: 220px;
}

.footer-space {
    height: 260px;
}

.header {
    position: fixed;
    width: 100%;
    top: 0;
}

.footer {
    position: fixed;
    width: 100%;
    bottom: 0;
}

.noto-nastaliq {
    font-family: "Noto Nastaliq Urdu", serif;
}
</style>
