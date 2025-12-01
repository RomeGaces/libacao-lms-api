<template>
    <h3>Personal Details</h3>
    <div class="personal-form-grid">
        <div class="personal-info">
            <div>
                <label>Lastname</label>
                <Field name="last_name" v-model="internalForm.last_name" v-slot="{ field }">
                    <input type="text" v-bind="field" />
                </Field>
                <ErrorMessage name="last_name" class="input-error-msg" />
            </div>
            <div>
                <label>Firstname</label>
                <Field name="first_name" v-model="internalForm.first_name" v-slot="{ field }">
                    <input type="text" v-bind="field" />
                </Field>
                <ErrorMessage name="first_name" class="input-error-msg" />
            </div>
            <div>
                <label>Middlename</label>
                <Field name="middle_name" v-model="internalForm.middle_name" v-slot="{ field }">
                    <input type="text" v-bind="field" />
                </Field>
                <ErrorMessage name="middle_name" class="input-error-msg" />
            </div>
            <div>
                <label>Suffix (e.g., Jr., Sr., II)</label>
                <Field name="name_extension" v-model="internalForm.name_extension" v-slot="{ field }">
                    <input type="text" v-bind="field" />
                </Field>
                <ErrorMessage name="name_extension" class="input-error-msg" />
            </div>
            <div>
                <label>Date of Birth</label>
                <Field name="birth_date" v-model="internalForm.birth_date" v-slot="{ field }">
                    <input type="date" v-bind="field" />
                </Field>
                <ErrorMessage name="birth_date" class="input-error-msg" />
            </div>
            <div class="input-wrapper span-3">
                <label>Place of Birth</label>
                <Field name="place_of_birth" v-model="internalForm.place_of_birth" v-slot="{ field }">
                    <input type="text" v-bind="field" />
                </Field>
                <ErrorMessage name="place_of_birth" class="input-error-msg" />
            </div>
            <div>
                <label>Sex</label>
                <Field name="sex" v-model="internalForm.sex" v-slot="{ field }">
                    <select v-bind="field">
                        <option value="">Select</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </Field>
                <ErrorMessage name="sex" class="input-error-msg" />
            </div>
            <div>
                <label>Civil Status</label>
                <Field name="civil_status" v-model="internalForm.civil_status" v-slot="{ field }">
                    <input type="text" v-bind="field" />
                </Field>
                <ErrorMessage name="civil_status" class="input-error-msg" />
            </div>
            <div>
                <label>Height (cm)</label>
                <Field name="height" v-model="internalForm.height" v-slot="{ field }">
                    <input type="text" v-bind="field"  />
                </Field>
                <ErrorMessage name="height" class="input-error-msg" />
            </div>
            <div>
                <label>Weight (kg)</label>
                <Field name="weight" v-model="internalForm.weight" v-slot="{ field }">
                    <input type="text" v-bind="field" />
                </Field>
                <ErrorMessage name="weight" class="input-error-msg" />
            </div>
            <div>
                <label>Blood Type</label>
                <Field name="blood_type" v-model="internalForm.blood_type" v-slot="{ field }">
                    <input type="text" v-bind="field" />
                </Field>
                <ErrorMessage name="blood_type" class="input-error-msg" />
            </div>
        </div>

        <div class="govt-info">
        <div>
            <label>GSIS ID No.</label>
            <Field name="email_no" v-model="internalForm.email_no" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="email_no" class="input-error-msg" />
        </div>
        <div>
            <label>Pag-Ibig ID No.</label>
            <Field name="pagibig_id_no" v-model="internalForm.pagibig_id_no" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="pagibig_id_no" class="input-error-msg" />
        </div>
        <div>
            <label>Phil-Health No.</label>
            <Field name="philhealth_no" v-model="internalForm.philhealth_no" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="philhealth_no" class="input-error-msg" />
        </div>
        <div>
            <label>SSS No.</label>
            <Field name="sss_no" v-model="internalForm.sss_no" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="sss_no" class="input-error-msg" />
        </div>
        <div>
            <label>Tin No.</label>
            <Field name="tin_no" v-model="internalForm.tin_no" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="tin_no" class="input-error-msg" />
        </div>
        <div>
            <label>Agency Employee No.</label>
            <Field name="agency_employee_no" v-model="internalForm.agency_employee_no" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="agency_employee_no" class="input-error-msg" />
        </div>
        <div>
            <label>Citizenship</label>
            <Field name="citizenship" v-model="internalForm.citizenship" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="citizenship" class="input-error-msg" />
        </div>
        </div>

        <div class="contact-address-info">
        <div class="input-wrapper span-3">
            <label>Residential Address</label>
            <Field name="residential_address" v-model="internalForm.residential_address" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="residential_address" class="input-error-msg" />
        </div>
        <div>
            <label>Residential Zip</label>
            <Field name="residential_zip" v-model="internalForm.residential_zip" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="residential_zip" class="input-error-msg" />
        </div>
        <div class="input-wrapper span-3">
            <label>Permanent Address</label>
            <Field name="permanent_address" v-model="internalForm.permanent_address" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="permanent_address" class="input-error-msg" />
        </div>
        <div>
            <label>Permanent Zip</label>
            <Field name="permanent_zip" v-model="internalForm.permanent_zip" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="permanent_zip" class="input-error-msg" />
        </div>
        <div class="input-wrapper span-2">
            <label>Email Address</label>
            <Field name="email" v-model="internalForm.email" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="email" class="input-error-msg" />
        </div>
        <div>
            <label>Telephone No.</label>
            <Field name="telephone_no" v-model="internalForm.telephone_no" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="telephone_no" class="input-error-msg" />
        </div>
        <div>
            <label>Mobile No.</label>
            <Field name="mobile_no" v-model="internalForm.mobile_no" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="mobile_no" class="input-error-msg" />
        </div>
        <div class="input-wrapper span-2">
            <label>CTC Place of Issuance</label>
            <Field name="ctc_place_of_issuance" v-model="internalForm.ctc_place_of_issuance" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="ctc_place_of_issuance" class="input-error-msg" />
        </div>
        <div>
            <label>CTC No.</label>
            <Field name="ctc_number" v-model="internalForm.ctc_number" v-slot="{ field }">
                <input type="text" v-bind="field" />
            </Field>
            <ErrorMessage name="ctc_number" class="input-error-msg" />
        </div>
        <div>
            <label>CTC Date of Issuance</label>
            <Field name="ctc_date_of_issuance" v-model="internalForm.ctc_date_of_issuance" v-slot="{ field }">
                <input type="date" v-bind="field" />
            </Field>
            <ErrorMessage name="ctc_date_of_issuance" class="input-error-msg" />
        </div>
        </div>
    </div>
</template>

<script setup>
import { Field, ErrorMessage, configure } from 'vee-validate';
defineProps({
  internalForm: { type: Object, required: true },
});

configure({
  validateOnInput: true
});
</script>
