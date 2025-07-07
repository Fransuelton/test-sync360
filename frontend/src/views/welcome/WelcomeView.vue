<script setup>
import { useRouter } from "vue-router";
import { ref } from "vue";
import Button from "../../components/Button.vue";
import Input from "../../components/Input.vue";

const router = useRouter();

const formData = ref({
  fullName: "",
  age: "",
  street: "",
  neighborhood: "",
  state: "",
  biography: "",
  profileImageFile: null,
});

const handleImageUpload = (file) => {
  formData.value.profileImageFile = file;
  console.log("Arquivo selecionado:", file);
};

const submitForm = async () => {
  if (!formData.value.fullName || !formData.value.age) {
    alert("Por favor, preencha os campos obrigatórios");
    return;
  }

  try {
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
      console.log("Perfil criado:", data);

      formData.value = {
        fullName: "",
        age: "",
        street: "",
        neighborhood: "",
        state: "",
        biography: "",
        profileImageFile: null,
      };
      router.push("/profile");
    } else {
      console.error("Erro ao criar perfil:", data);
    }
  } catch (error) {
    console.error("Erro na requisição:", error);
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
          placeholder="Rua, número"
          :required="true"
          v-model="formData.street"
        />
        <Input
          labelTitle="Bairro *"
          name="neighborhood"
          placeholder="Bairro"
          :required="true"
          v-model="formData.neighborhood"
        />
        <Input
          labelTitle="Estado *"
          name="state"
          placeholder="Estado"
          :required="true"
          v-model="formData.state"
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
        </div>
        <Button title="Criar perfil" @click="submitForm" />
      </form>
    </article>
  </section>
</template>

<style scoped>
.container {
  margin: 2rem;
}

.form-card-wrapper {
  background-color: var(--profile-card-background-color);
  text-align: center;
  padding: 1rem;
  border-radius: 1rem;
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
}
</style>
