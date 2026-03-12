<template>
  <PanelItem :index="index" :field="field">
    <template #value>
      <template v-if="field.inlineOnDetail">
        <button
          type="button"
          class="inline-toggle-switch"
          :class="{ 'is-on': isOn, 'is-loading': loading }"
          :style="switchStyle"
          :disabled="loading"
          @click.stop.prevent="toggle"
        >
          <span class="inline-toggle-knob"></span>
        </button>
      </template>

      <template v-else>
        <span
          class="inline-toggle-badge"
          :style="badgeStyle"
        >
          {{ isOn ? 'ON' : 'OFF' }}
        </span>
      </template>
    </template>
  </PanelItem>
</template>

<script>
export default {
  props: ['resourceName', 'field', 'index'],

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

    badgeStyle() {
      const color = this.isOn ? this.onColor : this.offColor
      return {
        backgroundColor: color,
        color: this.contrastColor(color),
      }
    },
  },

  methods: {
    contrastColor(hex) {
      const r = parseInt(hex.substring(1, 3), 16)
      const g = parseInt(hex.substring(3, 5), 16)
      const b = parseInt(hex.substring(5, 7), 16)
      const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255
      return luminance > 0.6 ? '#1f2937' : '#ffffff'
    },

    async toggle() {
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
