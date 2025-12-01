<template>
    <h3>Civil Service Eligibility</h3>
    <div class="eligibility-form-grid">
        <div class="input-wrapper span-2">
            <label>Career Service/Eligibility</label>
            <Field name="eligibilities[0].career_service" v-model="internalForm.eligibilities[0].career_service" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="eligibilities[0].career_service" class="input-error-msg" />
        </div>

        <div>
            <label>Rating</label>
            <Field name="eligibilities[0].rating" v-model="internalForm.eligibilities[0].rating" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="eligibilities[0].rating" class="input-error-msg" />
        </div>

        <div>
            <label>Date of Examination</label>
            <Field name="eligibilities[0].exam_date" v-model="internalForm.eligibilities[0].exam_date" v-slot="{ field }">
                <input type="date" v-bind="field" />
            </Field>
            <ErrorMessage name="eligibilities[0].exam_date" class="input-error-msg" />
        </div>

        <div class="input-wrapper span-2">
            <label>Place of Examination</label>
            <Field name="eligibilities[0].place_of_examination" v-model="internalForm.eligibilities[0].place_of_examination" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="eligibilities[0].place_of_examination" class="input-error-msg" />
        </div>

        <div>
            <label>License No.</label>
            <Field name="eligibilities[0].license_no" v-model="internalForm.eligibilities[0].license_no" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="eligibilities[0].license_no" class="input-error-msg" />
        </div>

        <div>
            <label>Date of Validity</label>
            <Field name="eligibilities[0].date_of_validity" v-model="internalForm.eligibilities[0].date_of_validity" v-slot="{ field }">
                <input type="date" v-bind="field" />
            </Field>
            <ErrorMessage name="eligibilities[0].date_of_validity" class="input-error-msg" />
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
