<template>
  <DefaultField :field="currentField" :errors="errors" :show-help-text="showHelpText">
    <template #field>
      <div class="flex items-center">
        <input
          :id="currentField.uniqueKey"
          type="checkbox"
          class="checkbox"
          :checked="value"
          :disabled="currentField.readonly"
          @change="handleChange"
        />
        <label :for="currentField.uniqueKey" class="ml-2 text-sm">
          {{ value ? 'ON' : 'OFF' }}
        </label>
      </div>
    </template>
  </DefaultField>
</template>

<script>
import { DependentFormField, HandlesValidationErrors } from 'laravel-nova'

export default {
  mixins: [DependentFormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],

  methods: {
    setInitialValue() {
      this.value = this.currentField.value || false
    },

    fill(formData) {
      formData.append(this.currentField.attribute, this.value ? 1 : 0)
    },

    handleChange(event) {
      this.value = event.target.checked
    },
  },
}
</script>
