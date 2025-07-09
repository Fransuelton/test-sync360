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
  maxlength: Number,
  minlength: Number,
  min: Number,
  max: Number,
});

const emit = defineEmits(["update:modelValue", "file-change"]);

const handleInput = (event) => {
  if (props.type === "file") {
    const file = event.target.files[0];
    emit("file-change", file);
  } else {
    let value = event.target.value;

    // Se for um campo de estado e tem maxlength de 2, converte para maiúsculas
    if (
      props.maxlength === 2 &&
      (props.name === "state" || props.name?.includes("estado"))
    ) {
      value = value.toUpperCase();
    }

    emit("update:modelValue", value);
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
      :maxlength="maxlength"
      :minlength="minlength"
      :min="min"
      :max="max"
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
  font-weight: 500;
  color: var(--default-text-color);
}

.input-wrapper .input {
  border-radius: 0.8rem;
  outline: none;
  padding: 1.4rem 1.6rem;
  border: 0.1rem solid var(--aux-text-color);
  font-size: var(--default-font-size);
  transition: all 0.2s ease;
  background-color: var(--profile-card-background-color);
}

.input-wrapper .input:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(102, 67, 209, 0.1);
}

.input-wrapper .input:hover {
  border-color: var(--primary-color);
}

/* Tablet - 768px+ */
@media (min-width: 768px) {
  .input-wrapper .input {
    padding: 1.6rem 2rem;
    font-size: 1.8rem;
    border-radius: 1rem;
  }

  .input-wrapper .label {
    font-size: 1.8rem;
  }
}

/* Desktop - 1024px+ */
@media (min-width: 1024px) {
  .input-wrapper .input {
    padding: 1.8rem 2.4rem;
    font-size: 2rem;
  }

  .input-wrapper .label {
    font-size: 2rem;
  }

  .input-wrapper .input:focus {
    box-shadow: 0 0 0 4px rgba(102, 67, 209, 0.15);
  }
}
</style>
