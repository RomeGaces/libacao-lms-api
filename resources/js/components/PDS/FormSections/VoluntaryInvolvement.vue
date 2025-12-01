<template>
    <h3>Voluntary Involvement</h3>
    <div class="voluntary-form-grid">
        <div class="input-wrapper span-2">
            <label>Organization Name</label>
            <Field name="voluntary_works[0].organization_name" v-model="internalForm.voluntary_works[0].organization_name" v-slot="{ field }">
                <input type="text" v-bind="field"/>
            </Field>
            <ErrorMessage name="voluntary_works[0].organization_name" class="input-error-msg" />
        </div>

        <div class="input-wrapper span-2">
            <label>Organization Address</label>
            <Field name="voluntary_works[0].organization_address" v-model="internalForm.voluntary_works[0].organization_address" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="voluntary_works[0].organization_address" class="input-error-msg" />
        </div>

        <div class="input-wrapper span-2">
            <label>Inclusive Date (From - To)</label>
            <div class="date-range">
                <div>
                    <Field name="voluntary_works[0].from" v-model="internalForm.voluntary_works[0].from" v-slot="{ field }">
                        <input type="date" placeholder="From" v-bind="field" />
                    </Field>
                    <ErrorMessage name="voluntary_works[0].from" class="input-error-msg" />
                </div>
                <div>
                    <Field name="voluntary_works[0].to" v-model="internalForm.voluntary_works[0].to" v-slot="{ field }">
                        <input type="date" placeholder="To"  v-bind="field" />
                    </Field>
                    <ErrorMessage name="voluntary_works[0].to" class="input-error-msg" />
                </div>
            </div>
        </div>

        <div>
            <label>Position</label>
            <Field name="voluntary_works[0].position" v-model="internalForm.voluntary_works[0].position" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="voluntary_works[0].position" class="input-error-msg" />
        </div>

        <div>
            <label>Number of Hours Rendered</label>
            <Field name="voluntary_works[0].hours" v-model="internalForm.voluntary_works[0].hours" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="voluntary_works[0].hours" class="input-error-msg" />
        </div>

        <div class="input-wrapper span-4">
            <label>Nature of Work</label>
            <Field name="voluntary_works[0].nature_of_work" v-model="internalForm.voluntary_works[0].nature_of_work" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="voluntary_works[0].nature_of_work" class="input-error-msg" />
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
