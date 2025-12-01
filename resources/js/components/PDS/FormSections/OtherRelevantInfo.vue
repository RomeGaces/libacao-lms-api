<template>
    <h3>Other Relevant Info</h3>
    <div class="other-form-grid">
        <div class="input-wrapper span-2">
            <label>Special Skill/Hobbies</label>
            <Field name="other_infos[0].special_skills" v-model="internalForm.other_infos[0].special_skills" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="other_infos[0].special_skills" class="input-error-msg" />
        </div>
        <div class="input-wrapper span-2">
            <label>None-Academic Distinctions/Recognition</label>
            <Field name="other_infos[0].distinctions" v-model="internalForm.other_infos[0].distinctions" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="other_infos[0].distinctions" class="input-error-msg" />
        </div>

        <div class="input-wrapper span-2">
            <label>Membership in Association/Organization</label>
            <Field name="other_infos[0].membership" v-model="internalForm.other_infos[0].membership" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="other_infos[0].membership" class="input-error-msg" />
        </div>

        <div class="input-wrapper span-2">
            <label>Conducted Sponsored By</label>
            <Field name="other_infos[0].sponsored_by" v-model="internalForm.other_infos[0].sponsored_by" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="other_infos[0].sponsored_by" class="input-error-msg" />
        </div>

        <div class="references span-2">
            <label>References (Not related by blood or affinity)</label>
            <FieldArray name="references" v-slot="{ fields, remove, push }">
                <div v-for="(reference, idx) in fields" :key="`ref-${idx}`" class="ref-inputs">
                    <div>
                        <label>Fullname</label>
                        <Field :name="`references[${idx}].name`" v-model="reference.name" v-slot="{ field }">
                            <input type="text" v-bind="field" />
                        </Field>
                        <ErrorMessage :name="`references[${idx}].name`" class="input-error-msg" />
                    </div>
                    <div>
                        <label>Telephone No.</label>
                        <Field :name="`references[${idx}].telephone_no`" v-model="reference.telephone_no" v-slot="{ field }">
                            <input type="text" v-bind="field" />
                        </Field>
                        <ErrorMessage :name="`references[${idx}].telephone_no`" class="input-error-msg" />
                    </div>
                    <div class="address-wrapper">
                        <label>Address</label>
                        <Field :name="`references[${idx}].address`" v-model="reference.address" v-slot="{ field }">
                            <input type="text" v-bind="field" />
                        </Field>
                        <ErrorMessage :name="`references[${idx}].address`" class="input-error-msg" />
                    </div>
                    <img class="remove-ref-icon" src="images/icons/close.png" @click.prevent="remove(idx)"/>
                </div>
                <Button class="btn-add-reference" variant="secondary" @click.prevent="push({ 
                    name: '', 
                    telephone_no: '',
                    address: '' 
                })">
                    <img src="images/icons/plus.png" />
                </Button>
            </FieldArray>
        </div>
    </div>
</template>

<script setup>
    import Button from "@/components/Common/Button.vue";
    import { Field, ErrorMessage, configure, FieldArray } from "vee-validate";

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
