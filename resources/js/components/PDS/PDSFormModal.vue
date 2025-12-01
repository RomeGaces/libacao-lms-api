<template>
    <Modal v-model="showModal" title="PDS Form" size="lg" height="full">
        <div class="pds-form">
            <!-- Sidebar Navigation -->
            <nav class="pds-nav">
                <ul>
                    <li v-for="(tab, index) in tabs" 
                        :key="index"
                        :class="{
                            active: activeTab === tab.key,
                            
                        }"
                            @click="activeTab = tab.key"
                        >
                        {{ tab.label }}
                        <span class="tabs-status" v-if="tabStatus[tab.key]">
                            <img v-if="tabStatus[tab.key] === 'warning'" src="images/icons/warning.png" />
                            <img v-else-if="tabStatus[tab.key] === 'valid'" src="images/icons/check-circle.png" />
                        </span>
                    </li>
                </ul>
            </nav>

            <!-- Form Section -->
            <Form :active-tab="activeTab" v-model:formData="formData"/>
        </div>

        <template #footer>
            <Button variant="secondary" size="md" @click="close">Cancel</Button>

            <Button
                variant="primary"
                size="md"
                @click="onNextTab"
            >
                {{ allTabsValid ? "Submit" : "Next" }}
            </Button>
        </template>
    </Modal>
</template>

<script setup>
import { ref, watch, computed } from "vue";
import Modal from "@/components/Common/Modal.vue";
import Form from "@/components/PDS/Form.vue";
import Button from "../Common/Button.vue";
import employeeService from "@/Services/employeeService.js";
import { usePdsValidation } from "@/Composables/Validation/usePdsValidation.js";
import notify from "@/services/NotificationService.js";

// Define props
const props = defineProps({
    formData: {
        type: Object,
        default: () => ({})
    }
});

// const formData = ref({ ...(props.formData || {})});

const showModal = ref(false);
const activeTab = ref("personal");
const emit = defineEmits(["saved"]);

const tabs = [
    { key: "personal", label: "Personal Details" },
    { key: "family", label: "Family Background" },
    { key: "education", label: "Educational Background" },
    { key: "eligibility", label: "Civil Service Eligibility" },
    { key: "work", label: "Work Experience" },
    { key: "voluntary", label: "Voluntary Involvement" },
    { key: "training", label: "Training Attended" },
    { key: "other", label: "Other Information" },
];

const { handleSubmit, validate, validateAllTabs, formData } = usePdsValidation(
    activeTab, 
    props.formData || {}
);

const currentTabIndex = computed(() =>
  tabs.findIndex((tab) => tab.key === activeTab.value)
);

function buildPayload(f) {
    const payload = {
        // Personal fields
        first_name: f.first_name || "",
        last_name: f.last_name || "",
        middle_name: f.middle_name || "",
        name_extension: f.name_extension || "",
        birth_date: f.birth_date || "",
        place_of_birth: f.place_of_birth || "",
        sex: f.sex || "",
        civil_status: f.civil_status || "",
        citizenship: f.citizenship || "",
        height: f.height || "",
        weight: f.weight || "",
        blood_type: f.blood_type || "",
        email_no: f.email_no || "",
        pagibig_id_no: f.pagibig_id_no || "",
        philhealth_no: f.philhealth_no || "",
        sss_no: f.sss_no || "",
        tin_no: f.tin_no || "",
        agency_employee_no: f.agency_employee_no || "",
        residential_address: f.residential_address || "",
        residential_zip: f.residential_zip || "",
        permanent_address: f.permanent_address || "",
        permanent_zip: f.permanent_zip || "",
        telephone_no: f.telephone_no || "",
        mobile_no: f.mobile_no || "",
        email: f.email || "",
        ctc_number: f.ctc_number || "",
        ctc_place_of_issuance: f.ctc_place_of_issuance || "",
        ctc_date_of_issuance: f.ctc_date_of_issuance || "",

        // relations
        family_members: [],
        educations: [],
        eligibilities: [],
        work_experiences: [],
        voluntary_works: [],
        trainings: [],
        other_infos: [],
        references: []
    };

    // family: spouse/father/mother + children
    if (f.spouse && f.spouse.name) {
        payload.family_members.push({
            relationship: "Spouse",
            name: f.spouse.name || "",
            birth_date: f.spouse.birth_date || "",
            occupation: f.spouse.occupation || "",
            employer: f.spouse.employer || "",
            business_address: f.spouse.business_address || "",
            telephone_no: f.spouse.telephone_no || ""
        });
    }
    if (f.father && f.father.name) {
        payload.family_members.push({
            relationship: "Father",
            name: f.father.name || "",
            birth_date: f.father.birth_date || "",
            occupation: f.father.occupation || "",
            employer: f.father.employer || "",
            business_address: f.father.business_address || "",
            telephone_no: f.father.telephone_no || ""
        });
    }
    if (f.mother && f.mother.name) {
        payload.family_members.push({
            relationship: "Mother",
            name: f.mother.name || "",
            birth_date: f.mother.birth_date || "",
            occupation: f.mother.occupation || "",
            employer: f.mother.employer || "",
            business_address: f.mother.business_address || "",
            telephone_no: f.mother.telephone_no || ""
        });
    }
    if (Array.isArray(f.children) && f.children.length) {
        f.children.forEach((c) => {
            if (c.full_name) {
                payload.family_members.push({
                    relationship: "Child",
                    name: c.full_name || "",
                    birth_date: c.birth_date || ""
                });
            }
        });
    }

    // education(s)
    if (Array.isArray(f.educations) && f.educations.length) {
        payload.educations = f.educations.map(e => ({
            highest_educational_attainment: e.highest_educational_attainment || "",
            school_name: e.school_name || "",
            degree_course: e.degree_course || "",
            year_graduated: e.year_graduated || "",
            highest_level_units: e.highest_level_units || "",
            attendance_from: e.attendance_from || "",
            attendance_to: e.attendance_to || "",
            scholarships: e.scholarships || ""
        }));
    }

    // eligibilities
    if (Array.isArray(f.eligibilities) && f.eligibilities.length) {
        payload.eligibilities = f.eligibilities.map(e => ({
            eligibility: e.eligibility || "",
            rating: e.rating || "",
            exam_date: e.exam_date || "",
            exam_place: e.exam_place || "",
            license_number: e.license_number || "",
            license_validity: e.license_validity || ""
        }));
    }

    // work experiences
    if (Array.isArray(f.work_experiences) && f.work_experiences.length) {
        payload.work_experiences = f.work_experiences.map(w => ({
            period_from: w.period_from || "",
            period_to: w.period_to || "",
            position_title: w.position_title || "",
            department_agency: w.department_agency || "",
            monthly_salary: w.monthly_salary || "",
            salary_grade: w.salary_grade || "",
            status_of_appointment: w.status_of_appointment || "",
            is_gov_service: w.is_gov_service || false
        }));
    }

    // voluntary works
    if (Array.isArray(f.voluntary_works) && f.voluntary_works.length) {
        payload.voluntary_works = f.voluntary_works.map(v => ({
            organization: v.organization || "",
            period_from: v.period_from || "",
            period_to: v.period_to || "",
            hours: v.hours || "",
            position: v.position || ""
        }));
    }

    // trainings
    if (Array.isArray(f.trainings) && f.trainings.length) {
        payload.trainings = f.trainings.map(t => ({
            title: t.title || "",
            period_from: t.period_from || "",
            period_to: t.period_to || "",
            hours: t.hours || "",
            conducted_by: t.conducted_by || ""
        }));
    }

    // other infos
    if (Array.isArray(f.other_infos) && f.other_infos.length) {
        payload.other_infos = f.other_infos.map(o => ({
            skill: o.skill || "",
            recognition: o.recognition || "",
            membership: o.membership || ""
        }));
    }

    // references
    if (Array.isArray(f.references) && f.references.length) {
        payload.references = f.references.map(r => ({
            name: r.name || "",
            address: r.address || "",
            telephone_no: r.telephone_no || ""
        }));
    }

    return payload;
}

const tabStatus = ref(
    tabs.reduce((acc, tab) => {
        acc[tab.key] = ''; 
        return acc;
    }, {})
);

const allTabsValid = computed(() => {
    return Object.values(tabStatus.value).every(status => status === 'valid');
});

async function onNextTab() {
    // Validate current tab first
    const { valid } = await validate();

    if (!valid) {
        tabStatus.value[activeTab.value] = 'warning';
        notify("warning", "Please fill the required fields before proceeding.");
        return;
    }

    tabStatus.value[activeTab.value] = 'valid';

    // Validate all tabs
    const { valid: allValid, invalidTabs } = await validateAllTabs(formData.value);

    // Update tabStatus for all tabs
    tabs.forEach(tab => {
        tabStatus.value[tab.key] = invalidTabs.includes(tab.key) ? 'warning' : 'valid';
    });

    if (allValid) {
        // All tabs valid → submit immediately, regardless of current tab
        handleSubmit(onSubmit)();
        return;
    }

    if (invalidTabs.length > 0) {
        notify("warning", "Please complete all the required information before proceeding.");
        // Move to the first invalid tab to guide user
        const nextInvalidTab = tabs.find(tab => invalidTabs.includes(tab.key));
        if (nextInvalidTab) activeTab.value = nextInvalidTab.key;
    }
};



async function onSubmit(values) {
    const data = values;
    
    console.log("Final Submit:", data);
}

// Watch for prop changes and update formData
watch(
    () => props.formData,
    (newVal) => {
        if (newVal) {
            formData.value = { ...newVal };
        }
    },
    { immediate: true, deep: true }
);

async function handleSave() {
    console.log("save!");

    try {
        const payload = buildPayload(formData?.value || {});
        console.log("Payload being sent:", payload);

        await employeeService.create(payload);

        notify("success", "Employee saved successfully!");

        showModal.value = false;
        emit("saved");
    } catch (error) {
        const errorMsg = error.response?.data || error.message || error;
        console.error("Failed to save employee:", errorMsg);

        notify("warning", "Failed to save employee.", errorMsg);
    }
}

function open() {
    showModal.value = true;
}
function close() {
    showModal.value = false;
}

defineExpose({ open, close });
</script>


