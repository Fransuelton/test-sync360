<script setup>
import { ref, onMounted, computed } from "vue";
import { useRouter } from "vue-router";
import Button from "../../components/Button.vue";
import Modal from "../../components/Modal.vue";
import Input from "../../components/Input.vue";

const router = useRouter();

const userData = ref({
  id: null,
  full_name: "",
  biography: "",
  age: 0,
  full_address: "",
  profile_image: "default.png",
});

const loading = ref(true);
const error = ref(null);
const imageError = ref(false);

const isModalOpen = ref(false);
const isUpdating = ref(false);
const updateError = ref(null);

const isDeleteModalOpen = ref(false);
const isDeleting = ref(false);
const deleteError = ref(null);

const editData = ref({
  full_name: "",
  biography: "",
  age: 0,
  full_address: "",
  profile_image: null,
});

const handleImageError = () => {
  imageError.value = true;
  console.log("Erro ao carregar imagem, usando fallback");
};

const profileImageUrl = computed(() => {
  if (imageError.value) {
    return "https://placehold.co/160x160/6643d1/ffffff/png?text=👤";
  }

  const image = userData.value.profile_image;

  if (!image || image === "default.png" || image === null) {
    return "https://placehold.co/160x160/6643d1/ffffff/png?text=👤";
  }

  if (image.startsWith("/storage/")) {
    return `http://localhost:8000${image}`;
  }

  if (image.startsWith("/") || image.includes("/tmp/")) {
    return "https://placehold.co/160x160/6643d1/ffffff/png?text=👤";
  }

  return image;
});

const fetchUserData = async () => {
  try {
    loading.value = true;

    const response = await fetch("http://localhost:8000/api/users");

    if (!response.ok) {
      throw new Error("Erro ao carregar dados do usuário");
    }

    const data = await response.json();

    if (data.success && data.data.length > 0) {
      const lastUser = data.data[data.data.length - 1];
      userData.value = lastUser;

      console.log("Dados do usuário:", lastUser);
      console.log("Profile image:", lastUser.profile_image);
    } else {
      error.value = "Nenhum usuário encontrado";
    }
  } catch (err) {
    error.value = err.message;
    console.error("Erro ao buscar usuário:", err);
  } finally {
    loading.value = false;
  }
};

const openEditModal = () => {
  editData.value = {
    full_name: userData.value.full_name,
    biography: userData.value.biography,
    age: userData.value.age,
    full_address: userData.value.full_address,
    profile_image: null,
  };
  updateError.value = null;
  isModalOpen.value = true;
};

const closeEditModal = () => {
  isModalOpen.value = false;
  updateError.value = null;
};

const openDeleteModal = () => {
  deleteError.value = null;
  isDeleteModalOpen.value = true;
};

const closeDeleteModal = () => {
  isDeleteModalOpen.value = false;
  deleteError.value = null;
};

const deleteProfile = async () => {
  try {
    isDeleting.value = true;
    deleteError.value = null;

    const response = await fetch(
      `http://localhost:8000/api/users/${userData.value.id}`,
      {
        method: "DELETE",
      }
    );

    if (!response.ok) {
      throw new Error("Erro ao deletar perfil");
    }

    const result = await response.json();

    if (result.success) {
      console.log("Perfil deletado com sucesso!");
      router.push("/");
    } else {
      throw new Error(result.message || "Erro ao deletar perfil");
    }
  } catch (err) {
    deleteError.value = err.message;
    console.error("Erro ao deletar perfil:", err);
  } finally {
    isDeleting.value = false;
  }
};

const handleImageChange = (file) => {
  editData.value.profile_image = file;
  console.log("Arquivo de imagem selecionado:", file);
};

const updateProfile = async () => {
  try {
    isUpdating.value = true;
    updateError.value = null;

    const formData = new FormData();
    formData.append("full_name", editData.value.full_name);
    formData.append("biography", editData.value.biography);
    formData.append("age", editData.value.age.toString());
    formData.append("full_address", editData.value.full_address);
    formData.append("_method", "PUT");

    if (editData.value.profile_image) {
      formData.append("profile_image", editData.value.profile_image);
    }

    const response = await fetch(
      `http://localhost:8000/api/users/${userData.value.id}`,
      {
        method: "POST",
        body: formData,
      }
    );

    if (!response.ok) {
      throw new Error("Erro ao atualizar perfil");
    }

    const result = await response.json();

    if (result.success) {
      userData.value = result.data;
      imageError.value = false;
      closeEditModal();
      console.log("Perfil atualizado com sucesso!");
    } else {
      throw new Error(result.message || "Erro ao atualizar perfil");
    }
  } catch (err) {
    updateError.value = err.message;
    console.error("Erro ao atualizar perfil:", err);
  } finally {
    isUpdating.value = false;
  }
};

onMounted(() => {
  fetchUserData();
});
</script>

<template>
  <section class="container">
    <div v-if="loading" class="loading">
      <p>Carregando perfil...</p>
    </div>

    <div v-else-if="error" class="error">
      <p>❌ {{ error }}</p>
      <button @click="fetchUserData()">Tentar novamente</button>
    </div>

    <article v-else class="profile-card-wrapper">
      <div class="profile-background">
        <div class="profile-image">
          <img
            :src="profileImageUrl"
            :alt="userData.full_name || 'Foto do perfil'"
            @error="handleImageError"
            @load="console.log('Imagem carregada:', profileImageUrl)"
          />
        </div>
      </div>

      <div class="user-info-wrapper">
        <h1 class="full-name">
          {{ userData.full_name || "Nome não informado" }}
        </h1>
        <p class="biography">
          {{ userData.biography || "Biografia não informada" }}
        </p>
        <div class="info-wrapper">
          <p class="address">
            {{ userData.full_address || "Endereço não informado" }}
          </p>
          <hr />
          <p class="age">{{ userData.age }} anos</p>
        </div>
      </div>

      <div class="button-wrapper">
        <Button title="Editar Perfil" @click="openEditModal" />
        <Button title="Deletar Perfil" @click="openDeleteModal" />
      </div>
    </article>

    <Modal :isOpen="isModalOpen" title="Editar Perfil" @close="closeEditModal">
      <form @submit.prevent="updateProfile" class="edit-form">
        <div v-if="updateError" class="update-error">❌ {{ updateError }}</div>

        <Input
          title="Nome Completo"
          placeholder="Digite seu nome completo"
          v-model="editData.full_name"
          :required="true"
        />

        <Input
          title="Biografia"
          placeholder="Conte um pouco sobre você"
          type="textarea"
          v-model="editData.biography"
        />

        <Input
          title="Idade"
          placeholder="Digite sua idade"
          type="number"
          v-model="editData.age"
          :required="true"
        />

        <Input
          title="Endereço Completo"
          placeholder="Rua, número, bairro, cidade, estado"
          v-model="editData.full_address"
          :required="true"
        />

        <Input
          title="Nova Foto de Perfil (opcional)"
          type="file"
          accept="image/*"
          @file-change="handleImageChange"
        />
      </form>

      <template #footer>
        <div class="modal-buttons">
          <Button title="Cancelar" @click="closeEditModal" type="button" />
          <Button
            :title="isUpdating ? 'Salvando...' : 'Salvar Alterações'"
            @click="updateProfile"
            :disabled="isUpdating"
          />
        </div>
      </template>
    </Modal>

    <Modal
      :isOpen="isDeleteModalOpen"
      title="Confirmar Exclusão"
      @close="closeDeleteModal"
    >
      <div class="delete-confirmation">
        <div v-if="deleteError" class="update-error">❌ {{ deleteError }}</div>

        <p>Tem certeza de que deseja deletar seu perfil?</p>
        <p class="warning-text">⚠️ Esta ação não pode ser desfeita!</p>

        <div class="user-preview">
          <strong>{{ userData.full_name }}</strong>
          <small>{{ userData.biography }}</small>
        </div>
      </div>

      <template #footer>
        <div class="modal-buttons">
          <Button title="Cancelar" @click="closeDeleteModal" type="button" />
          <div class="delete-button">
            <Button
              :title="isDeleting ? 'Deletando...' : 'Sim, Deletar'"
              @click="deleteProfile"
              :disabled="isDeleting"
            />
          </div>
        </div>
      </template>
    </Modal>
  </section>
</template>

<style scoped>
.container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 100vh;
  margin: 1rem;
}
.profile-card-wrapper {
  background-color: var(--profile-card-background-color);
  border-radius: 1.5rem;
  text-align: center;
  padding: 1.6rem;
  width: 100%;
}

.profile-card-wrapper .profile-background {
  background-image: url(../../assets/vendor/daniele-levis-pelusi-YKsqkazGaOw-unsplash.jpg);
  height: 10rem;
  background-size: cover;
  border-radius: 1.5rem;
  display: flex;
  justify-content: center;
  position: relative;
}

.profile-card-wrapper .profile-background .profile-image {
  width: 10rem;
  height: 10rem;
  border-radius: 50%;
  background-color: var(--background-color);
  display: flex;
  justify-content: center;
  align-items: center;
  position: absolute;
  top: 4rem;
  overflow: hidden;
}

.profile-card-wrapper .profile-background .profile-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 50%;
}

.profile-card-wrapper .user-info-wrapper {
  margin-top: 5rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
}

.profile-card-wrapper .user-info-wrapper .username {
  font-size: var(--default-font-size);
  color: var(--aux-text-color);
  font-weight: 500;
  margin-bottom: 1rem;
}

.profile-card-wrapper .user-info-wrapper .full-name {
  font-size: var(--default-h2-font-size);
}

.profile-card-wrapper .user-info-wrapper .biography {
  font-size: 1.2rem;
}

.profile-card-wrapper .user-info-wrapper .info-wrapper {
  display: flex;
  gap: 1rem;
  font-size: 1.2rem;
  margin-top: 1rem;
}

.profile-card-wrapper .button-wrapper {
  margin-top: 4rem;
  display: flex;
  justify-content: space-between;
}

.loading,
.error {
  text-align: center;
  padding: 2rem;
  background-color: var(--profile-card-background-color);
  border-radius: 1rem;
  width: 100%;
}

.error {
  color: var(--button-delete-background-color);
}

.error button {
  margin-top: 1rem;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 0.5rem;
  background-color: var(--primary-color);
  color: white;
  cursor: pointer;
}

.edit-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.update-error {
  background-color: rgba(220, 53, 69, 0.1);
  color: #dc3545;
  padding: 0.75rem;
  border-radius: 0.5rem;
  border: 1px solid rgba(220, 53, 69, 0.2);
  font-size: 0.9rem;
}

.modal-buttons {
  display: flex;
  gap: 1rem;
}

.modal-buttons :deep(.button) {
  min-width: 120px;
}

.delete-confirmation {
  text-align: center;
}

.delete-confirmation p {
  margin-bottom: 1rem;
  font-size: 1.1rem;
}

.warning-text {
  color: #dc3545;
  font-weight: 600;
  margin-bottom: 1.5rem !important;
}

.user-preview {
  background-color: rgba(108, 117, 125, 0.1);
  padding: 1rem;
  border-radius: 0.5rem;
  margin-top: 1rem;
  border-left: 4px solid var(--primary-color);
}

.user-preview strong {
  display: block;
  margin-bottom: 0.5rem;
  font-size: 1.1rem;
}

.user-preview small {
  color: var(--aux-text-color);
  font-size: 0.9rem;
}

.delete-button :deep(.button) {
  background-color: #dc3545 !important;
}

.delete-button :deep(.button):hover {
  background-color: #c82333 !important;
}
</style>
