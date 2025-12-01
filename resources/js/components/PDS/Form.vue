<template>
    <form class="form-section">
        <div v-show="activeTab === 'personal'">
            <PersonalDetails :internalForm="internalForm" />
        </div>

        <div v-show="activeTab === 'family'">
            <FamilyBackground :internalForm="internalForm" />
        </div>

        <div v-show="activeTab === 'education'">
            <EducationBackground :internalForm="internalForm" />
        </div>

        <div v-show="activeTab === 'eligibility'">
            <CivilServiceEligibility :internalForm="internalForm" />
        </div>

        <div v-show="activeTab === 'work'">
            <WorkExperience :internalForm="internalForm" />
        </div>

        <div v-show="activeTab === 'voluntary'">
            <VoluntaryInvolvement :internalForm="internalForm" />
        </div>

        <div v-show="activeTab === 'training'">
            <TrainingAttended :internalForm="internalForm" />
        </div>

        <div v-show="activeTab === 'other'">
            <OtherRelevantInfo :internalForm="internalForm" />
        </div>
    </form>
</template>

<script setup>
import Button from "@/components/Common/Button.vue";
import { reactive, watch } from "vue";

const modules = import.meta.glob("@/components/PDS/FormSections/*.vue", { eager: true });

const components = {};
for (const path in modules) {
    const name = path.split("/").pop().replace(".vue", "");
    components[name] = modules[path].default;
}

const {
    PersonalDetails,
    FamilyBackground,
    EducationBackground,
    CivilServiceEligibility,
    WorkExperience,
    VoluntaryInvolvement,
    TrainingAttended,
    OtherRelevantInfo
} = components;

defineProps({
    activeTab: {
        type: String,
        required: true
    },
    formData: {
        type: Object,
        default: () => ({})
    },
    errors: Object,
    validateField: Function
});

const emit = defineEmits(['update:formData']);

const internalForm = reactive({
    // Personal
    last_name: (typeof formData !== 'undefined' && formData.last_name) ? formData.last_name : "",
    first_name: (typeof formData !== 'undefined' && formData.first_name) ? formData.first_name : "",
    middle_name: (typeof formData !== 'undefined' && formData.middle_name) ? formData.middle_name : "",
    name_extension: (typeof formData !== 'undefined' && formData.name_extension) ? formData.name_extension : "",
    birth_date: (typeof formData !== 'undefined' && formData.birth_date) ? formData.birth_date : "",
    place_of_birth: (typeof formData !== 'undefined' && formData.place_of_birth) ? formData.place_of_birth : "",
    sex: (typeof formData !== 'undefined' && formData.sex) ? formData.sex : "",
    civil_status: (typeof formData !== 'undefined' && formData.civil_status) ? formData.civil_status : "",
    height: (typeof formData !== 'undefined' && formData.height) ? formData.height : "",
    weight: (typeof formData !== 'undefined' && formData.weight) ? formData.weight : "",
    blood_type: (typeof formData !== 'undefined' && formData.blood_type) ? formData.blood_type : "",
    email_no: (typeof formData !== 'undefined' && formData.email_no) ? formData.email_no : "",
    pagibig_id_no: (typeof formData !== 'undefined' && formData.pagibig_id_no) ? formData.pagibig_id_no : "",
    philhealth_no: (typeof formData !== 'undefined' && formData.philhealth_no) ? formData.philhealth_no : "",
    sss_no: (typeof formData !== 'undefined' && formData.sss_no) ? formData.sss_no : "",
    tin_no: (typeof formData !== 'undefined' && formData.tin_no) ? formData.tin_no : "",
    agency_employee_no: (typeof formData !== 'undefined' && formData.agency_employee_no) ? formData.agency_employee_no : "",
    citizenship: (typeof formData !== 'undefined' && formData.citizenship) ? formData.citizenship : "",
    residential_address: (typeof formData !== 'undefined' && formData.residential_address) ? formData.residential_address : "",
    residential_zip: (typeof formData !== 'undefined' && formData.residential_zip) ? formData.residential_zip : "",
    permanent_address: (typeof formData !== 'undefined' && formData.permanent_address) ? formData.permanent_address : "",
    permanent_zip: (typeof formData !== 'undefined' && formData.permanent_zip) ? formData.permanent_zip : "",
    email: (typeof formData !== 'undefined' && formData.email) ? formData.email : "",
    telephone_no: (typeof formData !== 'undefined' && formData.telephone_no) ? formData.telephone_no : "",
    mobile_no: (typeof formData !== 'undefined' && formData.mobile_no) ? formData.mobile_no : "",
    ctc_place_of_issuance: (typeof formData !== 'undefined' && formData.ctc_place_of_issuance) ? formData.ctc_place_of_issuance : "",
    ctc_number: (typeof formData !== 'undefined' && formData.ctc_number) ? formData.ctc_number : "",
    ctc_date_of_issuance: (typeof formData !== 'undefined' && formData.ctc_date_of_issuance) ? formData.ctc_date_of_issuance : "",

    // Family
    spouse: (typeof formData !== 'undefined' && formData.spouse) ? formData.spouse : { name: "", birth_date: "", relationship: "", occupation: "", business_address: "", telephone_no: "", employer: "" },
    father: (typeof formData !== 'undefined' && formData.father) ? formData.father : { name: "", birth_date: "", relationship: "", occupation: "", business_address: "", telephone_no: "", employer: "" },
    mother: (typeof formData !== 'undefined' && formData.mother) ? formData.mother : { name: "", birth_date: "", relationship: "", occupation: "", business_address: "", telephone_no: "", employer: "" },
    children: (typeof formData !== 'undefined' && Array.isArray(formData.children) && formData.children.length) ? formData.children : [{ full_name: "", birth_date: "" }],

    // Education
    educations: (typeof formData !== 'undefined' && Array.isArray(formData.educations) && formData.educations.length) ? formData.educations : [{
        highest_educational_attainment: "",
        school_name: "",
        attendance_from: "",
        attendance_to: "",
        scholarships: "",
        year_graduated: "",
        highest_level_units: "",
        degree_course: ""
    }],

    // Eligibility
    eligibilities: (typeof formData !== 'undefined' && Array.isArray(formData.eligibilities) && formData.eligibilities.length) ? formData.eligibilities : [{
        career_service: "",
        rating: "",
        date_of_examination: "",
        place_of_examination: "",
        license_no: "",
        date_of_validity: ""
    }],

    // Work
    work_experiences: (typeof formData !== 'undefined' && Array.isArray(formData.work_experiences) && formData.work_experiences.length) ? formData.work_experiences : [{
        position_title: "",
        agency: "",
        monthly_salary: "",
        status_of_appointment: "",
        inclusive_from: "",
        inclusive_to: "",
        salary_job_grade: ""
    }],

    // Voluntary
    voluntary_works: (typeof formData !== 'undefined' && Array.isArray(formData.voluntary_works) && formData.voluntary_works.length) ? formData.voluntary_works : [{
        organization_name: "",
        organization_address: "",
        from: "",
        to: "",
        position: "",
        hours: "",
        nature_of_work: ""
    }],

    // Trainings
    trainings: (typeof formData !== 'undefined' && Array.isArray(formData.trainings) && formData.trainings.length) ? formData.trainings : [{
        title: "",
        from: "",
        to: "",
        type: "",
        conducted_by: ""
    }],

    // Other infos
    other_infos: (typeof formData !== 'undefined' && Array.isArray(formData.other_infos) && formData.other_infos.length) ? formData.other_infos : [{
        special_skills: "",
        distinctions: "",
        membership: "",
        sponsored_by: ""
    }],

    // References
    references: (typeof formData !== 'undefined' && Array.isArray(formData.references) && formData.references.length) ? formData.references : [{ fullname: "", telephone_no: "", address: "" }]
});

watch(internalForm, (newVal) => {
    emit('update:formData', JSON.parse(JSON.stringify(newVal)));
}, { deep: true });

function addChild() {
    internalForm.children.push({ full_name: "", birth_date: "" });
}
function removeChild(index) {
    internalForm.children.splice(index, 1);
}

function addReference() {
    internalForm.references.push({ fullname: "", telephone_no: "", address: "" });
}
function removeReference(index) {
    internalForm.references.splice(index, 1);
}
</script>