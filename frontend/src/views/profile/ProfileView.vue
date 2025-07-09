<script setup>
import { ref, onMounted, computed, watch } from "vue";
import { useRouter } from "vue-router";
import { Button, Modal, Input } from "../../components/index.js";
import { useToast, usePageTitle } from "../../composables/index.js";
import { userService } from "../../services/index.js";

const router = useRouter();
const { success, error: showError, warning, info } = useToast();
const { setPageTitle } = usePageTitle();

const userData = ref({
  id: null,
  full_name: "",
  biography: "",
  age: 0,
  full_address: "",
  profile_image: "default.png",
});

const loading = ref(true);
const fetchError = ref(null);
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
  street: "",
  neighborhood: "",
  state: "",
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
      fetchError.value = "Nenhum usuário encontrado";
      showError("Usuário não encontrado", "Nenhum perfil foi encontrado no sistema");
    }
  } catch (err) {
    fetchError.value = err.message;
    showError("Erro de conexão", "Não foi possível carregar os dados do perfil");
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
    street: userData.value.address_details?.street || "",
    neighborhood: userData.value.address_details?.neighborhood || "",
    state: userData.value.address_details?.state || "",
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
      success("Perfil deletado!", "Seu perfil foi removido com sucesso");
      
      // Aguardar um pouco antes de redirecionar
      setTimeout(() => {
        router.push("/");
      }, 1500);
    } else {
      throw new Error(result.message || "Erro ao deletar perfil");
    }
  } catch (err) {
    deleteError.value = err.message;
    showError("Erro ao deletar", err.message || "Não foi possível deletar o perfil");
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
    formData.append("street", editData.value.street);
    formData.append("neighborhood", editData.value.neighborhood);
    formData.append("state", editData.value.state);
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
      success("Perfil atualizado!", "Suas informações foram salvas com sucesso");
      console.log("Perfil atualizado com sucesso!");
    } else {
      throw new Error(result.message || "Erro ao atualizar perfil");
    }
  } catch (err) {
    updateError.value = err.message;
    showError("Erro ao atualizar", err.message || "Não foi possível salvar as alterações");
    console.error("Erro ao atualizar perfil:", err);
  } finally {
    isUpdating.value = false;
  }
};

onMounted(() => {
  fetchUserData();
});

// Atualizar título quando dados do usuário carregarem
watch(userData, (newData) => {
  if (newData.full_name) {
    setPageTitle('profile', newData.full_name);
  }
}, { deep: true });

// Atualizar título quando modal de edição abrir/fechar
watch(isModalOpen, (isOpen) => {
  if (isOpen) {
    setPageTitle('edit', userData.value.full_name || 'Perfil');
  } else if (userData.value.full_name) {
    setPageTitle('profile', userData.value.full_name);
  }
});

// Definir título inicial
onMounted(() => {
  setPageTitle('profile');
});
</script>

<template>
  <section class="container">
    <div v-if="loading" class="loading">
      <p>Carregando perfil...</p>
    </div>

    <div v-else-if="fetchError" class="error">
      <p>❌ {{ fetchError }}</p>
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
          title="Rua/Endereço"
          placeholder="Rua das Flores, 123"
          v-model="editData.street"
          :required="true"
        />

        <Input
          title="Bairro"
          placeholder="Centro"
          v-model="editData.neighborhood"
          :required="true"
        />

        <Input
          title="Estado"
          placeholder="Ex: SP, RJ, MG"
          v-model="editData.state"
          :required="true"
          :maxlength="2"
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
  min-height: 100vh;
  margin: 1rem;
}

.profile-card-wrapper {
  background-color: var(--profile-card-background-color);
  border-radius: 1.5rem;
  text-align: center;
  padding: 1.6rem;
  width: 100%;
  max-width: 500px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
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
  border: 4px solid var(--profile-card-background-color);
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
  line-height: 1.6;
  max-width: 80%;
  margin: 0 auto;
}

.profile-card-wrapper .user-info-wrapper .info-wrapper {
  display: flex;
  gap: 1rem;
  font-size: 1.2rem;
  margin-top: 1rem;
  flex-wrap: wrap;
  justify-content: center;
}

.profile-card-wrapper .button-wrapper {
  margin-top: 4rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.loading,
.error {
  text-align: center;
  padding: 2rem;
  background-color: var(--profile-card-background-color);
  border-radius: 1rem;
  width: 100%;
  max-width: 500px;
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
  flex-direction: column;
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

/* Tablet - 768px+ */
@media (min-width: 768px) {
  .container {
    margin: 2rem;
  }
  
  .profile-card-wrapper {
    max-width: 600px;
    padding: 2rem;
  }
  
  .profile-card-wrapper .profile-background {
    height: 12rem;
  }
  
  .profile-card-wrapper .profile-background .profile-image {
    width: 12rem;
    height: 12rem;
    top: 5rem;
  }
  
  .profile-card-wrapper .user-info-wrapper {
    margin-top: 6rem;
  }
  
  .profile-card-wrapper .user-info-wrapper .biography {
    font-size: 1.4rem;
    max-width: 90%;
  }
  
  .profile-card-wrapper .button-wrapper {
    flex-direction: row;
    justify-content: space-between;
    gap: 2rem;
  }
  
  .modal-buttons {
    flex-direction: row;
    justify-content: flex-end;
  }
  
  .edit-form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
  }
  
  .edit-form > div:first-child,
  .edit-form > div:nth-child(2),
  .edit-form > div:nth-child(4) {
    grid-column: 1 / -1; /* Nome, biografia e rua ocupam toda a largura */
  }
  
  .edit-form > div:last-child {
    grid-column: 1 / -1; /* Upload de imagem ocupa toda a largura */
  }
}

/* Desktop - 1024px+ */
@media (min-width: 1024px) {
  .profile-card-wrapper {
    max-width: 700px;
    padding: 3rem;
  }
  
  .profile-card-wrapper .profile-background {
    height: 15rem;
  }
  
  .profile-card-wrapper .profile-background .profile-image {
    width: 15rem;
    height: 15rem;
    top: 6rem;
  }
  
  .profile-card-wrapper .user-info-wrapper {
    margin-top: 8rem;
  }
  
  .profile-card-wrapper .user-info-wrapper .full-name {
    font-size: 3rem;
  }
  
  .profile-card-wrapper .user-info-wrapper .biography {
    font-size: 1.6rem;
    max-width: 100%;
  }
  
  .profile-card-wrapper .user-info-wrapper .info-wrapper {
    font-size: 1.4rem;
    gap: 2rem;
  }
}

/* Desktop Large - 1440px+ */
@media (min-width: 1440px) {
  .profile-card-wrapper {
    max-width: 800px;
  }
}
</style>
