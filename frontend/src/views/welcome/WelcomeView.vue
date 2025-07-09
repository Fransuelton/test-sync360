<script setup>
import { useRouter } from "vue-router";
import { ref, onMounted } from "vue";
import { Button, Input } from "../../components/index.js";
import { useToast, usePageTitle } from "../../composables/index.js";
import { userService } from "../../services/index.js";

const router = useRouter();
const { success, error } = useToast();
const { setPageTitle, setStatusTitle } = usePageTitle();

onMounted(() => {
  setPageTitle("welcome");
});

const formData = ref({
  fullName: "",
  age: "",
  street: "",
  neighborhood: "",
  state: "",
  biography: "",
  profileImageFile: null,
});

const isSubmitting = ref(false);

const handleImageUpload = (file) => {
  formData.value.profileImageFile = file;
  console.log("Arquivo selecionado:", file);
};

const submitForm = async () => {
  if (
    !formData.value.fullName ||
    !formData.value.age ||
    !formData.value.street ||
    !formData.value.neighborhood ||
    !formData.value.state ||
    !formData.value.biography
  ) {
    error(
      "Campos obrigatórios",
      "Por favor, preencha todos os campos obrigatórios para continuar."
    );
    return;
  }

  try {
    isSubmitting.value = true;
    setStatusTitle("welcome", "saving", "Criando perfil");

    let requestBody;
    let headers = {};

    if (formData.value.profileImageFile) {
      requestBody = new FormData();
      requestBody.append("full_name", formData.value.fullName);
      requestBody.append("age", parseInt(formData.value.age));
      requestBody.append("street", formData.value.street);
      requestBody.append("neighborhood", formData.value.neighborhood);
      requestBody.append("state", formData.value.state);
      requestBody.append("biography", formData.value.biography);
      requestBody.append("profile_image", formData.value.profileImageFile);
    } else {
      headers["Content-Type"] = "application/json";
      requestBody = JSON.stringify({
        full_name: formData.value.fullName,
        age: parseInt(formData.value.age),
        street: formData.value.street,
        neighborhood: formData.value.neighborhood,
        state: formData.value.state,
        biography: formData.value.biography,
      });
    }

    const response = await fetch("http://localhost:8000/api/users", {
      method: "POST",
      headers: headers,
      body: requestBody,
    });

    const data = await response.json();

    if (response.ok) {
      setStatusTitle("welcome", "success", "Perfil criado");
      success("Perfil criado!", "Seu perfil foi criado com sucesso");

      formData.value = {
        fullName: "",
        age: "",
        street: "",
        neighborhood: "",
        state: "",
        biography: "",
        profileImageFile: null,
      };

      setTimeout(() => {
        router.push("/profile");
      }, 1000);
    } else {
      const errorMessage = data.message || "Erro ao criar perfil";
      setStatusTitle("welcome", "error", "Falha no cadastro");
      error("Erro no cadastro", errorMessage);
      console.error("Erro ao criar perfil:", data);
    }
  } catch (err) {
    setStatusTitle("welcome", "error", "Erro de conexão");
    error("Erro de conexão", "Não foi possível conectar com o servidor");
    console.error("Erro na requisição:", err);
  } finally {
    isSubmitting.value = false;

    setTimeout(() => {
      setPageTitle("welcome");
    }, 3000);
  }
};
</script>

<template>
  <section class="container">
    <article class="form-card-wrapper">
      <h1 class="title">👋 Bem-vindo!</h1>
      <p class="info">
        Preencha suas informações abaixo — leva apenas um minuto.
      </p>

      <form class="form">
        <Input
          labelTitle="Imagem de perfil"
          type="file"
          name="profile_image"
          @file-change="handleImageUpload"
        />
        <Input
          labelTitle="Nome completo *"
          name="full_name"
          placeholder="Digite seu nome completo"
          :required="true"
          v-model="formData.fullName"
        />
        <Input
          labelTitle="Idade *"
          type="number"
          name="age"
          placeholder="Digite sua idade"
          :required="true"
          v-model="formData.age"
        />
        <Input
          labelTitle="Rua *"
          name="street"
          placeholder="Ex: Av. Paulista, 1000"
          :required="true"
          v-model="formData.street"
        />
        <Input
          labelTitle="Bairro *"
          name="neighborhood"
          placeholder="Ex: Centro"
          :required="true"
          v-model="formData.neighborhood"
        />
        <Input
          labelTitle="Estado *"
          name="state"
          placeholder="Ex: SP, RJ, MG"
          :required="true"
          v-model="formData.state"
          :maxlength="2"
        />
        <div class="textarea-wrapper">
          <label for="biography" class="label">Biografia *</label>
          <textarea
            name="biography"
            id="biography"
            cols="30"
            rows="5"
            placeholder="Escreva uma breve introdução para mostrar no seu perfil..."
            class="textarea"
            v-model="formData.biography"
          ></textarea>
          <p class="required-fields">* Campos obrigatórios</p>
        </div>

        <Button
          :title="isSubmitting ? 'Criando perfil...' : 'Criar perfil'"
          @click="submitForm"
          :disabled="isSubmitting"
        />
      </form>
    </article>
  </section>
</template>

<style scoped>
.container {
  margin: 2rem;
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}

.form-card-wrapper {
  background-color: var(--profile-card-background-color);
  text-align: center;
  padding: 1rem;
  border-radius: 1rem;
  width: 100%;
  max-width: 500px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.form-card-wrapper .title {
  font-size: var(--default-h1-font-size);
  margin-bottom: 1rem;
}

.form-card-wrapper .info {
  font-size: var(--default-font-size);
  margin-bottom: 2rem;
}

.form-card-wrapper .form {
  text-align: left;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.form-card-wrapper .form .textarea-wrapper {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}
.form-card-wrapper .form .textarea-wrapper .label {
  font-size: var(--default-font-size);
}

.form-card-wrapper .form .textarea-wrapper .textarea {
  border-radius: 0.8rem;
  outline: none;
  padding: 1.4rem 1.6rem;
  border: 0.1rem solid var(--aux-text-color);
  font-size: var(--default-font-size);
  font-family: "Inter", sans-serif;
  resize: none;
}

.required-fields {
  font-size: var(--default-font-size);
  color: var(--aux-text-color);
  margin-top: 2rem;
}

/* Tablet - 768px+ */
@media (min-width: 768px) {
  .container {
    margin: 3rem;
  }

  .form-card-wrapper {
    padding: 2rem;
    max-width: 600px;
  }

  .form-card-wrapper .form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
  }

  .form-card-wrapper .form .textarea-wrapper {
    grid-column: 1 / -1;
  }

  .form-card-wrapper .form > div:first-child {
    grid-column: 1 / -1;
  }
}

/* Desktop - 1024px+ */
@media (min-width: 1024px) {
  .form-card-wrapper {
    max-width: 700px;
    padding: 3rem;
  }

  .form-card-wrapper .title {
    font-size: 4rem;
  }

  .form-card-wrapper .info {
    font-size: 1.8rem;
    margin-bottom: 3rem;
  }

  .form-card-wrapper .form {
    gap: 2rem;
  }
}

/* Desktop Large - 1440px+ */
@media (min-width: 1440px) {
  .form-card-wrapper {
    max-width: 800px;
  }
}
</style>
