<template>
    <h3>Family Background</h3>
    <div class="family-form-grid">
        <div class="family span-2">
            <!-- SPOUSE -->
            <div class="input-wrapper">
                <label>Spouse</label>
                <div class="spouse-info">
                    <div>
                        <label>Name</label>
                        <Field name="spouse.name" v-model="internalForm.spouse.name" v-slot="{ field }">
                            <input type="text" v-bind="field" />
                        </Field>
                        <ErrorMessage name="spouse.name" class="input-error-msg" />
                    </div>
                    <div>
                        <label>Birth Date</label>
                        <Field name="spouse.birth_date" v-model="internalForm.spouse.birth_date" v-slot="{ field }">
                            <input type="date" v-bind="field" />
                        </Field>
                        <ErrorMessage name="spouse.birth_date" class="input-error-msg" />
                    </div>
                    <div>
                        <label>Relationship</label>
                        <Field name="spouse.relationship" v-model="internalForm.spouse.relationship" v-slot="{ field }">
                            <input type="text" v-bind="field" />
                        </Field>
                        <ErrorMessage name="spouse.relationship" class="input-error-msg" />
                    </div>
                    <div>
                        <label>Occupation</label>
                        <Field name="spouse.occupation" v-model="internalForm.spouse.occupation" v-slot="{ field }">
                            <input type="text" v-bind="field" />
                        </Field>
                        <ErrorMessage name="spouse.occupation" class="input-error-msg" />
                    </div>
                    <div class="input-wrapper span-2">
                        <label>Business Address</label>
                        <Field name="spouse.business_address" v-model="internalForm.spouse.business_address" v-slot="{ field }">
                            <input type="text" v-bind="field" />
                        </Field>
                        <ErrorMessage name="spouse.business_address" class="input-error-msg" />
                    </div>
                    <div>
                        <label>Telephone No.</label>
                        <Field name="spouse.telephone_no" v-model="internalForm.spouse.telephone_no" v-slot="{ field }">
                            <input type="text" v-bind="field" />
                        </Field>
                        <ErrorMessage name="spouse.telephone_no" class="input-error-msg" />
                    </div>
                    <div>
                        <label>Employer/Business Name</label>
                        <Field name="spouse.employer" v-model="internalForm.spouse.employer" v-slot="{ field }">
                            <input type="text" v-bind="field" />
                        </Field>
                        <ErrorMessage name="spouse.employer" class="input-error-msg" />
                    </div>
                </div>
            </div>

            <!-- FATHER -->
            <div class="input-wrapper">
                <label>Father</label>
                <div class="father-info">
                    <div>
                        <label>Name</label>
                        <Field name="father.name" v-model="internalForm.father.name" v-slot="{ field }">
                            <input type="text" v-bind="field" />
                        </Field>
                        <ErrorMessage name="father.name" class="input-error-msg" />
                    </div>
                    <div>
                        <label>Birth Date</label>
                        <Field name="father.birth_date" v-model="internalForm.father.birth_date" v-slot="{ field }">
                            <input type="date" v-bind="field" />
                        </Field>
                        <ErrorMessage name="father.birth_date" class="input-error-msg" />
                    </div>
                    <div>
                        <label>Relationship</label>
                        <Field name="father.relationship" v-model="internalForm.father.relationship" v-slot="{ field }">
                            <input type="text" v-bind="field" />
                        </Field>
                        <ErrorMessage name="father.relationship" class="input-error-msg" />
                    </div>
                    <div>
                        <label>Occupation</label>
                        <Field name="father.occupation" v-model="internalForm.father.occupation" v-slot="{ field }">
                            <input type="text" v-bind="field" />
                        </Field>
                        <ErrorMessage name="father.occupation" class="input-error-msg" />
                    </div>
                    <div class="input-wrapper span-2">
                        <label>Business Address</label>
                        <Field name="father.business_address" v-model="internalForm.father.business_address" v-slot="{ field }">
                            <input type="text" v-bind="field" />
                        </Field>
                        <ErrorMessage name="father.business_address" class="input-error-msg" />
                    </div>
                    <div>
                        <label>Telephone No.</label>
                        <Field name="father.telephone_no" v-model="internalForm.father.telephone_no" v-slot="{ field }">
                            <input type="text" v-bind="field" />
                        </Field>
                        <ErrorMessage name="father.telephone_no" class="input-error-msg" />
                    </div>
                    <div>
                        <label>Employer/Business Name</label>
                        <Field name="father.employer" v-model="internalForm.father.employer" v-slot="{ field }">
                            <input type="text" v-bind="field" />
                        </Field>
                        <ErrorMessage name="father.employer" class="input-error-msg" />
                    </div>
                </div>
            </div>

            <!-- MOTHER -->
            <div class="input-wrapper">
                <label>Mother</label>
                <div class="mother-info">
                    <div>
                        <label>Name (Maiden's Name)</label>
                        <Field name="mother.name" v-model="internalForm.mother.name" v-slot="{ field }">
                            <input type="text" v-bind="field" placeholder="Enter your mother's name" />
                        </Field>
                        <ErrorMessage name="mother.name" class="input-error-msg" />
                    </div>
                    <div>
                        <label>Birth Date</label>
                        <Field name="mother.birth_date" v-model="internalForm.mother.birth_date" v-slot="{ field }">
                            <input type="date" v-bind="field" />
                        </Field>
                        <ErrorMessage name="mother.birth_date" class="input-error-msg" />
                    </div>
                    <div>
                        <label>Relationship</label>
                        <Field name="mother.relationship" v-model="internalForm.mother.relationship" v-slot="{ field }">
                            <input type="text" v-bind="field" />
                        </Field>
                        <ErrorMessage name="mother.relationship" class="input-error-msg" />
                    </div>
                    <div>
                        <label>Occupation</label>
                        <Field name="mother.occupation" v-model="internalForm.mother.occupation" v-slot="{ field }">
                            <input type="text" v-bind="field" />
                        </Field>
                        <ErrorMessage name="mother.occupation" class="input-error-msg" />
                    </div>
                    <div class="input-wrapper span-2">
                        <label>Business Address</label>
                        <Field name="mother.business_address" v-model="internalForm.mother.business_address" v-slot="{ field }">
                            <input type="text" v-bind="field" />
                        </Field>
                        <ErrorMessage name="mother.business_address" class="input-error-msg" />
                    </div>
                    <div>
                        <label>Telephone No.</label>
                        <Field name="mother.telephone_no" v-model="internalForm.mother.telephone_no" v-slot="{ field }">
                            <input type="text" v-bind="field" />
                        </Field>
                        <ErrorMessage name="mother.telephone_no" class="input-error-msg" />
                    </div>
                    <div>
                        <label>Employer/Business Name</label>
                        <Field name="mother.employer" v-model="internalForm.mother.employer" v-slot="{ field }">
                            <input type="text" v-bind="field" />
                        </Field>
                        <ErrorMessage name="mother.employer" class="input-error-msg" />
                    </div>
                </div>
            </div>

            <!-- CHILDREN -->
            <div class="input-wrapper">
                <label>Children's</label>
                <FieldArray name="children" v-slot="{ fields, remove, push }">
                    <div v-for="(children, idx) in fields" :key="`child-${idx}`" class="children-info">
                        <div class="input-wrapper span-2">
                            <label>Full Name</label>
                            <Field :name="`children[${idx}].full_name`" v-model="children.full_name" v-slot="{ field }">
                                <input type="text" v-bind="field" />
                            </Field>
                            <ErrorMessage :name="`children[${idx}].full_name`" class="input-error-msg" />
                        </div>
                        <div class="input-wrapper span-1">
                            <label>Date of Birth</label>
                            <Field :name="`children[${idx}].birth_date`" v-model="children.birth_date" v-slot="{ field }">
                                <input type="date" v-bind="field" />
                            </Field>
                            <ErrorMessage :name="`children[${idx}].birth_date`" class="input-error-msg" />
                        </div>
                        <img class="remove-children-icon" src="images/icons/close.png" @click.prevent="remove(idx)"/>
                    </div>
                    <Button class="btn-add-children" variant="secondary" @click.prevent="push({ 
                        full_name: '', 
                        birth_date: null 
                    })">
                        <img src="images/icons/plus.png" />
                    </Button>
                </FieldArray>
            </div>
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
