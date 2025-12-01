<template>
    <h3>Educational Background</h3>
	<FieldArray name="educations" v-slot="{ fields, remove, push }">
		<div v-for="(educations, idx) in formData.educations" :key="`educ-${idx}`" class="education-form-grid">
			<div class="input-wrapper span-2">
				<label>Highest Educational Attainment</label>
				<Field 
					:name="`educations[${idx}].highest_educational_attainment`" 
					v-model="formData.educations[idx].highest_educational_attainment" 
					v-slot="{ field }">
					<select v-bind="field">
						<option value="">Select</option>
						<option value="elementary">Elementary</option>
						<option value="secondary">Secondary</option>
						<option value="vocational">Vocational/Trade Course</option>
						<option value="college">College</option>
						<option value="graduate">Graduate Studies</option>
					</select>
				</Field>
				<ErrorMessage :name="`educations[${idx}].highest_educational_attainment`" class="input-error-msg" />
			</div>

			<div class="input-wrapper span-2">
				<label>Name of School</label>
				<Field 
					:name="`educations[${idx}].school_name`" 
					v-model="formData.educations[idx].school_name" 
					v-slot="{ field }">
					<input type="text" v-bind="field" placeholder="Enter school name" />
				</Field>
				<ErrorMessage :name="`educations[${idx}].school_name`" class="input-error-msg" />
			</div>

			<div class="input-wrapper">
				<label>Period of Attendance (From - To)</label>
				<div class="date-range">
					<div class="input-wrapper span-2">
						<Field :name="`educations[${idx}].attendance_from`" v-model="formData.educations[idx].attendance_from" v-slot="{ field }">
							<input type="date" placeholder="From" v-bind="field" />
						</Field>
						<ErrorMessage :name="`educations[${idx}].attendance_from`" class="input-error-msg" />
					</div>
					<div class="input-wrapper span-2">
						<Field :name="`educations[${idx}].attendance_to`" v-model="formData.educations[idx].attendance_to" v-slot="{ field }">
							<input type="date" placeholder="To" v-bind="field" />
						</Field>
						<ErrorMessage :name="`educations[${idx}].attendance_to`" class="input-error-msg" />
					</div>
				</div>
			</div>

			<div class="input-wrapper span-2">
				<label>Scholarships/Academic Honors Received</label>
				<Field :name="`educations[${idx}].scholarships`" v-model="formData.educations[idx].scholarships" v-slot="{ field }">
					<input type="text" v-bind="field" />
				</Field>
				<ErrorMessage :name="`educations[${idx}].scholarships`" class="input-error-msg" />
			</div>

			<div>
				<label>Year Graduated</label>
				<Field :name="`educations[${idx}].year_graduated`" v-model="formData.educations[idx].year_graduated" v-slot="{ field }">
					<input type="text" v-bind="field" />
				</Field>
				<ErrorMessage :name="`educations[${idx}].year_graduated`" class="input-error-msg" />
			</div>

			<div>
				<label>Highest Level/Units Earned</label>
				<Field :name="`educations[${idx}].highest_level_units`" v-model="formData.educations[idx].highest_level_units" v-slot="{ field }">
					<input type="text" v-bind="field" />
				</Field>
				<ErrorMessage :name="`educations[${idx}].highest_level_units`" class="input-error-msg" />
			</div>

			<div class="input-wrapper span-2">
				<label>Basic Educational Degree/Course</label>
				<Field :name="`educations[${idx}].degree_course`" v-model="formData.educations[idx].degree_course" v-slot="{ field }">
					<input type="text" v-bind="field" />
				</Field>
				<ErrorMessage :name="`educations[${idx}].degree_course`" class="input-error-msg" />
			</div>
			<img src="images/icons/close.png" class="remove-educ-icon" @click.prevent="formData.educations.splice(idx,1)" />
		</div>
		<Button class="btn-add-education" variant="secondary" @click.prevent="addEducation">
      	<img src="images/icons/plus.png" />
    	</Button>
	</FieldArray>
</template>

<script setup>
import { Field, ErrorMessage, FieldArray, configure } from "vee-validate";
import Button from "@/components/Common/Button.vue";
import { reactive } from "vue";

const props = defineProps({
    internalForm: {
        type: Object,
        required: true,
    },
});

configure({
    validateOnInput: true,
});

const formData = reactive({
    ...props.internalForm,
    educations: props.internalForm.educations || []
});

function addEducation() {
    formData.educations.push({
        highest_educational_attainment: '',
        school_name: '',
        attendance_from: '',
        attendance_to: '',
        scholarships: '',
        year_graduated: '',
        highest_level_units: '',
        degree_course: ''
    });
}
</script>
