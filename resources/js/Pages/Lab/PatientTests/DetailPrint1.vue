<template>
    <div class="a4-size relative mx-auto flex flex-col rounded-lg p-6 justify-center items-center shadow-md">
      <div class="vector"></div>
      <div class="flex-initial w-full">
      <div class="flex items-center space-x-8 text-center">
        <img class="h-auto w-24" src="//build/assets/logo-20b0503a.png" alt="" />
        <div>
          <h1 class="doctor-info text-3xl font-bold">CHINIOT GENERAL HOSPITAL</h1>
          <p>Opposit Sui Gas Office Sargodha Road Faisalabad Ph: 041-8848060-8787231</p>
          <p class="font-medium">Under: MOFAD-E-AMMA Chiniot Sheikh Association. Faisalabad</p>
        </div>
      </div>
      </div>
      <div class="flex-auto w-full">
      <div class="grid grid-cols-5 gap-x-6 place-items-center my-8">
        <div class="col-span-2 space-y-6 w-full">
          <p class="flex text-sm"><span class="font-semibold whitespace-nowrap w-40">Patient Name:</span><span class="border-b border-black w-full">{{patient_test.patient_name}}</span></p>
          <p class="flex text-sm"><span class="font-semibold whitespace-nowrap w-40">Lab No:</span><span class="border-b border-black w-full">{{patient_test.lab_no}}</span></p>
          <p class="flex text-sm"><span class="font-semibold whitespace-nowrap w-40">Refered By:</span><span class="border-b border-black w-full">{{patient_test.referred_by}} </span></p>
        </div>
        <div class="col-span-3 space-y-6 w-full">
          <p class="flex text-sm"><span class="font-semibold whitespace-nowrap w-80">Date/Time:Sample</span><span class="border-b border-black w-full">{{patient_test.date_sample}}</span></p>
          <p class="flex text-sm"><span class="font-semibold whitespace-nowrap w-80">Date/Time:Report Ready</span><span class="border-b border-black w-full">{{patient_test.date_report_ready}}</span></p>
          <p class="flex text-sm"><span class="font-semibold whitespace-nowrap w-80">Date/Time:Report Collection</span><span class="border-b border-black w-full">{{patient_test.date_report_collection}}</span></p>
          <p class="flex text-sm"><span class="font-semibold whitespace-nowrap w-80">Age / Sex:</span><span class="border-b border-black w-full">{{patient_test.patient_age}}</span></p>
        </div>
      </div>
      <div class="border border-gray-500 pt-2 p-4" v-if="patient_test.test_nature == 'General'">
        <p><strong>Sample: </strong>Blood</p>
        <h2 class="text-2xl font-semibold text-center"><i> {{patient_test?.package?.name}}</i></h2>
        <table class="w-full mt-3">
          <thead>
            <tr class="border border-gray-500">
              <th class="font-medium p-2 text-left">TEST</th>
              <th class="font-medium p-2">NORMAL VALUES</th>
              <th class="font-medium p-2">UNIT</th>
              <th class="font-medium p-2">RESULT</th>
            </tr>
          </thead>
          <tbody>
            <!--Blood Group-->
            <template v-for="(lab_result, index) in lab_results" :key="index">
                <tr v-if="(index != 0 && lab_results[index-1].category != lab_result.category) || index == 0">
                    <td>
                        <span class="underline font-medium text-lg">{{lab_result.category}}</span>
                    </td>
                </tr>
                <tr class="border-b border-dashed border-gray-500">
                    <td>{{lab_result.test_name}}</td>
                    <td class="text-center">{{lab_result.min_value}}-{{lab_result.max_value}}</td>
                    <td class="text-center">{{lab_result.unit}}</td>
                    <td class="text-center">{{lab_result.result}}</td>
                </tr>
            </template>

          </tbody>
        </table>
      </div>

      <div class="border border-gray-500 pt-2 p-4" v-else>
        <p><strong>Sample: </strong>Blood</p>
        <h2 class="text-2xl font-semibold text-center"><i>Cross Match</i></h2>
        <table class="w-full mt-3">
          <thead>
            <tr class="border border-gray-500">
              <th class="font-medium p-2 text-left">TEST</th>
              <th class="font-medium p-2">NORMAL VALUES</th>
              <th class="font-medium p-2">UNIT</th>
              <th class="font-medium p-2">RESULT</th>
            </tr>
          </thead>
          <tbody>
            <!--Blood Group-->
            <tr class="border-b border-dashed border-gray-500">
                <td class="font-bold">Blood Bag No.</td>
                <td class="font-bold">-</td>
                <td class="font-bold">-</td>
                <td class="text-center">{{patient_test?.test_cross_match_details?.blood_bag_no}}</td>
            </tr>
            <tr class="border-b border-dashed border-gray-500">
                <td class="font-bold">Blood Component</td>
                <td class="font-bold">-</td>
                <td class="font-bold">-</td>
                <td class="text-center">{{patient_test?.test_cross_match_details?.blood_component}}</td>
            </tr>
            <tr class="border-b border-dashed border-gray-500">
                <td class="font-bold">Blood Volumn</td>
                <td class="font-bold">-</td>
                <td class="font-bold">-</td>
                <td class="text-center">{{patient_test?.test_cross_match_details?.blood_volumn}}</td>
            </tr>
            <tr class="border-b border-dashed border-gray-500">
                <td class="font-bold">Bleeding Date</td>
                <td class="font-bold">-</td>
                <td class="font-bold">-</td>
                <td class="text-center">{{patient_test?.test_cross_match_details?.bleeding_date}}</td>
            </tr>
            <tr class="border-b border-dashed border-gray-500">
                <td class="font-bold">Bag Expiry Date</td>
                <td class="font-bold">-</td>
                <td class="font-bold">-</td>
                <td class="text-center">{{patient_test?.test_cross_match_details?.bag_expiry_date}}</td>
            </tr>
            <tr class="border-b border-dashed border-gray-500">
                <td class="font-bold">Donor name</td>
                <td class="font-bold">-</td>
                <td class="font-bold">-</td>
                <td class="text-center">{{patient_test?.test_cross_match_details?.donor_name}}</td>
            </tr>
            <tr class="border-b border-dashed border-gray-500">
                <td class="font-bold">Donor name</td>
                <td class="font-bold">-</td>
                <td class="font-bold">-</td>
                <td class="text-center">{{patient_test?.test_cross_match_details?.donor_name}}</td>
            </tr>
            <tr class="border-b border-dashed border-gray-500">
                <td class="font-bold">Donor blood group</td>
                <td class="font-bold">-</td>
                <td class="font-bold">-</td>
                <td class="text-center">{{patient_test?.test_cross_match_details?.donor_blood_group}}</td>
            </tr>
            <tr class="border-b border-dashed border-gray-500">
                <td class="font-bold">Donor RH factor</td>
                <td class="font-bold">-</td>
                <td class="font-bold">-</td>
                <td class="text-center">{{patient_test?.test_cross_match_details?.donor_rh_factor}}</td>
            </tr>
            <tr class="border-b border-dashed border-gray-500">
                <td class="font-bold">Donor age</td>
                <td class="font-bold">-</td>
                <td class="font-bold">-</td>
                <td class="text-center">{{patient_test?.test_cross_match_details?.donor_age}}</td>
            </tr>
            <tr class="border-b border-dashed border-gray-500">
                <td class="font-bold">Donor blood pressure</td>
                <td class="font-bold">-</td>
                <td class="font-bold">-</td>
                <td class="text-center">{{patient_test?.test_cross_match_details?.donor_blood_pressure}}</td>
            </tr>
            <tr class="border-b border-dashed border-gray-500">
                <td class="font-bold">Donor anti HCV</td>
                <td class="font-bold">-</td>
                <td class="font-bold">-</td>
                <td class="text-center">{{patient_test?.test_cross_match_details?.donor_anti_hcv}}</td>
            </tr>
            <tr class="border-b border-dashed border-gray-500">
                <td class="font-bold">Donor hbsag</td>
                <td class="font-bold">-</td>
                <td class="font-bold">-</td>
                <td class="text-center">{{patient_test?.test_cross_match_details?.donor_hbsag}}</td>
            </tr>
            <tr class="border-b border-dashed border-gray-500">
                <td class="font-bold">Donor HIV antibodies</td>
                <td class="font-bold">-</td>
                <td class="font-bold">-</td>
                <td class="text-center">{{patient_test?.test_cross_match_details?.donor_hiv_antibodies}}</td>
            </tr>
            <tr class="border-b border-dashed border-gray-500">
                <td class="font-bold">Donor cross match</td>
                <td class="font-bold">-</td>
                <td class="font-bold">-</td>
                <td class="text-center">{{patient_test?.test_cross_match_details?.donor_cross_match}}</td>
            </tr>

          </tbody>
        </table>
      </div>
    </div>
      <div class="flex-initial w-full mt-10">
        <div class="text-black h-auto w-full grid grid-cols-3 gap-6 place-items-center py-2 px-4">
          <div>
            <h2 class="doctor-info text-lg font-semibold">{{patient_test?.doctor?.name}}</h2>
            <h3 class="doctor-info">
                <template v-for="(education, index) in patient_test?.doctor?.education">
                {{education.degree}}<span v-if="patient_test.doctor.education.length - 1 != index">,</span>
                </template>
            </h3>
          </div>
          <div>
            <p class="font-medium">Lab Incharge</p>
          </div>
          <div>
            <p class="underline">{{patient_test?.lab_incharge}}</p>
            <p class="font-medium">Lab. Technician</p>
          </div>
        </div>
        <div class="border-t-2 border-gray-500 w-full">
            <p class="text-sm">Test(s) are performed with utmost care however erros/amissions is possible. Text(s) should not be compared with other lab's results because different kits/automation are used.
              This sample was teken without CNIC confirmation. it has no official important & there is no legal accountablitiy.
              Computerized print may not need signature.
            </p>
        </div>
    </div>
    </div>
</template>

<script>
export default {
    props: {
        lab_results:Array,
        patient_test: {
            type: Object,
            required: true,
        },
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
            if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            return age;
        }
    },
};
</script>

<style>
      @media print {
          @page { size: A4; margin: 0; }
          body { margin: 0; }
      }
      .a4-size {
          width: 210mm;
          height: auto;
          /* height: 297mm; */
          padding: 10mm;
      }
      .vector {
          position: absolute;
          width: 100mm;
          height: 100mm;
          background-image: url('//build/assets/logo-20b0503a.png');
          opacity: 0.2;
          background-position: center;
          background-size: 100% 100%;
      }
      .doctor-info {
          font-family: 'Helvetica', Arial, sans-serif;
      }
    </style>
