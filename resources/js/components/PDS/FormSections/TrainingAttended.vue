<template>
    <h3>Training Attended</h3>
    <div class="training-form-grid">
        <div class="input-wrapper span-2">
            <label>Title of Learning and Development Program (Write in full)</label>
            <Field name="trainings[0].title" v-model="internalForm.trainings[0].title" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="trainings[0].title" class="input-error-msg" />
        </div>

        <div class="input-wrapper span-2">
            <label>Inclusive Date (From - To)</label>
            <div class="date-range">
                <div>
                    <Field name="trainings[0].from" v-model="internalForm.trainings[0].from" v-slot="{ field }">
                        <input type="date" placeholder="From" v-bind="field" />
                    </Field>
                    <ErrorMessage name="trainings[0].from" class="input-error-msg" />
                </div>
                <div>
                    <Field name="trainings[0].to" v-model="internalForm.trainings[0].to" v-slot="{ field }">
                        <input type="date" placeholder="To" v-bind="field" />
                    </Field>
                    <ErrorMessage name="trainings[0].to" class="input-error-msg" />
                </div>
            </div>
        </div>

        <div class="input-wrapper span-2">
            <label>Type of L&amp;D (Managerial, Supervisory, Technical, etc)</label>
            <Field name="trainings[0].type" v-model="internalForm.trainings[0].type" v-slot="{ field }">
                <input type="text"  v-bind="field" />
            </Field>
            <ErrorMessage name="trainings[0].type" class="input-error-msg" />
        </div>

        <div class="input-wrapper span-2">
            <label>Conducted Sponsored By</label>
            <Field name="trainings[0].conducted_by" v-model="internalForm.trainings[0].conducted_by" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="trainings[0].conducted_by" class="input-error-msg" />
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
