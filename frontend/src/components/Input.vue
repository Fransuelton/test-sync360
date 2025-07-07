<script setup>
const props = defineProps({
  labelTitle: String,
  type: {
    type: String,
    default: "text",
  },
  name: String,
  placeholder: {
    type: String,
    default: "placeholder text",
  },
  required: {
    type: Boolean,
    default: false,
  },
  modelValue: String,
});

const emit = defineEmits(["update:modelValue", "file-change"]);

const handleInput = (event) => {
  if (props.type === "file") {
    const file = event.target.files[0];
    emit("file-change", file);
  } else {
    emit("update:modelValue", event.target.value);
  }
};
</script>

<template>
  <div class="input-wrapper">
    <label :for="name" class="label">{{ labelTitle }}</label>
    <input
      :type="type"
      :name="name"
      :id="name"
      :placeholder="type !== 'file' ? placeholder : ''"
      :required="required"
      :value="type !== 'file' ? modelValue : ''"
      @input="handleInput"
      :accept="type === 'file' ? 'image/*' : ''"
      class="input"
    />
  </div>
</template>

<style scoped>
.input-wrapper {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.input-wrapper .label {
  font-size: var(--default-font-size);
}

.input-wrapper .input {
  border-radius: 0.8rem;
  outline: none;
  padding: 1.4rem 1.6rem;
  border: 0.1rem solid var(--aux-text-color);
  font-size: var(--default-font-size);
}
</style>
