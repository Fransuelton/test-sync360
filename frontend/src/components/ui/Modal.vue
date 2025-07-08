<script setup>
import { onMounted, onUnmounted } from "vue";

const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true,
  },
  title: {
    type: String,
    default: "Modal",
  },
  closeOnOverlay: {
    type: Boolean,
    default: true,
  },
});

const emit = defineEmits(["close"]);

const handleOverlayClick = () => {
  if (props.closeOnOverlay) {
    emit("close");
  }
};

const handleEscapeKey = (event) => {
  if (event.key === "Escape" && props.isOpen) {
    emit("close");
  }
};

const toggleBodyScroll = (disable) => {
  if (disable) {
    document.body.style.overflow = "hidden";
  } else {
    document.body.style.overflow = "";
  }
};

onMounted(() => {
  document.addEventListener("keydown", handleEscapeKey);
});

onUnmounted(() => {
  document.removeEventListener("keydown", handleEscapeKey);
  toggleBodyScroll(false);
});

import { watch } from "vue";
watch(
  () => props.isOpen,
  (newValue) => {
    toggleBodyScroll(newValue);
  }
);
</script>

<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="isOpen" class="modal-overlay" @click="handleOverlayClick">
        <div class="modal-container" @click.stop>
          <header class="modal-header">
            <h2>{{ title }}</h2>
            <button class="close-button" @click="$emit('close')" type="button">
              ✕
            </button>
          </header>

          <main class="modal-content">
            <slot></slot>
          </main>

          <footer v-if="$slots.footer" class="modal-footer">
            <slot name="footer"></slot>
          </footer>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  padding: 1rem;
}

.modal-container {
  background-color: var(--profile-card-background-color);
  border-radius: 1rem;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
  max-width: 500px;
  width: 100%;
  max-height: 90vh;
  overflow-y: auto;
  position: relative;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-bottom: 1px solid var(--aux-text-color);
}

.modal-header h2 {
  margin: 0;
  font-size: 1.5rem;
  color: var(--text-color);
}

.close-button {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: var(--aux-text-color);
  width: 2rem;
  height: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transition: background-color 0.2s;
}

.close-button:hover {
  background-color: var(--aux-text-color);
  color: var(--background-color);
}

.modal-content {
  padding: 1.5rem;
}

.modal-footer {
  padding: 1rem 1.5rem;
  border-top: 1px solid var(--aux-text-color);
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
}

.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-active .modal-container,
.modal-leave-active .modal-container {
  transition: transform 0.3s ease;
}

.modal-enter-from .modal-container,
.modal-leave-to .modal-container {
  transform: scale(0.9) translateY(-20px);
}

@media (max-width: 768px) {
  .modal-container {
    max-width: 95%;
    margin: 1rem;
  }

  .modal-header,
  .modal-content,
  .modal-footer {
    padding: 1rem;
  }
}

/* Tablet e Desktop */
@media (min-width: 769px) {
  .modal-container {
    max-width: 600px;
    min-width: 500px;
  }

  .modal-header {
    padding: 2rem;
  }

  .modal-header h2 {
    font-size: 2rem;
  }

  .modal-content {
    padding: 2rem;
  }

  .modal-footer {
    padding: 1.5rem 2rem;
  }
}

/* Desktop Large */
@media (min-width: 1024px) {
  .modal-container {
    max-width: 700px;
    min-width: 600px;
  }
}
</style>
