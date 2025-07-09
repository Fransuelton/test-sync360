import { ref, watch } from "vue";

const pageTitle = ref("Perfil de Usuário | Desafio Técnico");

export function usePageTitle() {
  const setTitle = (title) => {
    pageTitle.value = title;
    document.title = title;
  };

  const setPageTitle = (page, subtitle = "", status = "") => {
    const baseTitle = "Perfil de Usuário";
    const appSuffix = "Desafio Técnico";

    const pageTitles = {
      welcome: "👋 Bem-vindo - Criar Perfil",
      profile: "👤 Meu Perfil",
      edit: "✏️ Editar Perfil",
      loading: "⏳ Carregando...",
      error: "❌ Erro",
      home: "🏠 Início",
    };

    let title = pageTitles[page] || page;

    if (status) {
      const statusIcons = {
        loading: "⏳",
        saving: "💾",
        deleting: "🗑️",
        uploading: "📤",
        success: "✅",
        error: "❌",
      };
      const statusIcon = statusIcons[status] || "";
      title = `${statusIcon} ${title}`;
    }

    if (subtitle) {
      title = `${title} - ${subtitle}`;
    }

    const fullTitle = `${title} | ${baseTitle} | ${appSuffix}`;
    setTitle(fullTitle);
  };

  const setLoadingTitle = (message = "Carregando") => {
    setPageTitle("loading", message);
  };

  const setErrorTitle = (message = "Erro") => {
    setPageTitle("error", message);
  };

  const setStatusTitle = (page, status, subtitle = "") => {
    setPageTitle(page, subtitle, status);
  };

  watch(pageTitle, (newTitle) => {
    if (document.title !== newTitle) {
      document.title = newTitle;
    }
  });

  return {
    pageTitle,
    setTitle,
    setPageTitle,
    setLoadingTitle,
    setErrorTitle,
    setStatusTitle,
  };
}
