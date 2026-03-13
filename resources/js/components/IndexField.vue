<template>
  <div class="inline-toggle-wrapper">
    <button
      type="button"
      class="inline-toggle-switch"
      :class="{ 'is-on': isOn, 'is-loading': loading }"
      :style="switchStyle"
      :disabled="loading || field.readonly"
      @click.stop.prevent="toggle"
    >
      <span class="inline-toggle-knob"></span>
    </button>
  </div>
</template>

<script>
export default {
  props: ['resourceName', 'field'],

  data() {
    return {
      value: this.field.value,
      loading: false,
    }
  },

  computed: {
    isOn() {
      return Boolean(this.value)
    },

    onColor() {
      return this.field.onColor || '#22c55e'
    },

    offColor() {
      return this.field.offColor || '#9ca3af'
    },

    switchStyle() {
      return {
        '--toggle-on-color': this.onColor,
        '--toggle-off-color': this.offColor,
      }
    },
  },

  methods: {
    async toggle() {
      if (this.field.readonly) return
      this.loading = true
      const newValue = !this.isOn

      try {
        await Nova.request().post(`/nova-vendor/inline-toggle/update/${this.resourceName}`, {
          id: this.field.id,
          attribute: this.field.attribute,
          value: newValue,
        })

        this.value = newValue
        const message = newValue
          ? (this.field.onMessage || this.field.successMessage)
          : (this.field.offMessage || this.field.successMessage)
        Nova.success(message || this.__('The field was updated successfully.'))
      } catch (error) {
        Nova.error(this.field.errorMessage || this.__('There was a problem updating the field.'))
      } finally {
        this.loading = false
      }
    },
  },
}
</script>
