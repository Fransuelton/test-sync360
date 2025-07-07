import { createWebHistory, createRouter } from "vue-router";

import WelcomeView from "../views/welcome/WelcomeView.vue";
import ProfileView from "../views/profile/ProfileView.vue";

const routes = [
  { path: "/", component: WelcomeView },
  { path: "/profile", component: ProfileView },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
