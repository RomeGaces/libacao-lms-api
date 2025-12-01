<template>
    <h3>Work Experience</h3>
    <div class="work-form-grid">
        <div class="input-wrapper span-2">
            <label>Position Title (Write in full - no abbreviation)</label>
            <Field name="work_experiences[0].position_title" v-model="internalForm.work_experiences[0].position_title" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="work_experiences[0].position_title" class="input-error-msg" />
        </div>

        <div class="input-wrapper span-2">
            <label>Department/Agency/Office/Company</label>
            <Field name="work_experiences[0].agency" v-model="internalForm.work_experiences[0].agency" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="work_experiences[0].agency" class="input-error-msg" />
        </div>

        <div>
            <label>Monthly Salary</label>
            <Field name="work_experiences[0].monthly_salary" v-model="internalForm.work_experiences[0].monthly_salary" v-slot="{ field }">
                <input type="text" v-bind="field" placeholder="" />
            </Field>
            <ErrorMessage name="work_experiences[0].monthly_salary" class="input-error-msg" />
        </div>

        <div>
            <label>Status of Appointment</label>
            <Field name="work_experiences[0].status_of_appointment" v-model="internalForm.work_experiences[0].status_of_appointment" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="work_experiences[0].status_of_appointment" class="input-error-msg" />
        </div>

        <div class="input-wrapper span-2">
            <label>Inclusive Date (From - To)</label>
            <div class="date-range">
                <div>
                    <Field name="work_experiences[0].inclusive_from" v-model="internalForm.work_experiences[0].inclusive_from" v-slot="{ field }">
                        <input type="date" placeholder="From" v-bind="field" />
                    </Field>
                    <ErrorMessage name="work_experiences[0].inclusive_from" class="input-error-msg" />
                </div>
                <div>
                    <Field name="work_experiences[0].inclusive_to" v-model="internalForm.work_experiences[0].inclusive_to" v-slot="{ field }">
                        <input type="date" placeholder="To" v-bind="field" />
                    </Field>
                    <ErrorMessage name="work_experiences[0].inclusive_to" class="input-error-msg" />
                </div>
                
            </div>
        </div>

        <div class="input-wrapper span-2">
            <label>Salary/Job/Pay Grade & Step (if government)</label>
            <Field name="work_experiences[0].salary_job_grade" v-model="internalForm.work_experiences[0].salary_job_grade" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="work_experiences[0].salary_job_grade" class="input-error-msg" />
        </div>
    </div>
</template>

<script setup>
    import { Field, ErrorMessage, configure } from "vee-validate";

    defineProps({
        internalForm: {
            type: Object,
            required: true,
        },
    });

    configure({
        validateOnInput: true,
    });
</script>