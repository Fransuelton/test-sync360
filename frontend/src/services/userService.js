/**
 * 👤 User Service - Gerenciamento de usuários
 * 
 * Contém todos os métodos relacionados ao CRUD de usuários,
 * abstraindo as chamadas de API e fornecendo uma interface limpa.
 */

import { api } from './api.js'

export const userService = {
  /**
   * 📋 Listar todos os usuários
   */
  async getAll() {
    return await api.get('/users')
  },

  /**
   * 👤 Buscar usuário por ID
   */
  async getById(id) {
    return await api.get(`/users/${id}`)
  },

  /**
   * ➕ Criar novo usuário
   */
  async create(userData) {
    // Se tem arquivo, usar FormData
    if (userData.profileImageFile) {
      const formData = new FormData()
      
      // Adicionar campos do formulário
      formData.append('full_name', userData.fullName)
      formData.append('age', userData.age)
      formData.append('street', userData.street)
      formData.append('neighborhood', userData.neighborhood)
      formData.append('state', userData.state)
      formData.append('biography', userData.biography)
      formData.append('profile_image', userData.profileImageFile)
      
      return await api.post('/users', formData)
    }
    
    // Caso contrário, usar JSON
    return await api.post('/users', {
      full_name: userData.fullName,
      age: parseInt(userData.age),
      street: userData.street,
      neighborhood: userData.neighborhood,
      state: userData.state,
      biography: userData.biography
    })
  },

  /**
   * ✏️ Atualizar usuário
   */
  async update(id, userData) {
    // Se tem arquivo, usar FormData
    if (userData.profileImageFile) {
      const formData = new FormData()
      
      // Adicionar campos do formulário
      formData.append('full_name', userData.fullName)
      formData.append('age', userData.age)
      formData.append('street', userData.street)
      formData.append('neighborhood', userData.neighborhood)
      formData.append('state', userData.state)
      formData.append('biography', userData.biography)
      formData.append('profile_image', userData.profileImageFile)
      formData.append('_method', 'PUT') // Method spoofing para Laravel
      
      return await api.post(`/users/${id}`, formData)
    }
    
    // Caso contrário, usar JSON
    return await api.put(`/users/${id}`, {
      full_name: userData.fullName,
      age: parseInt(userData.age),
      street: userData.street,
      neighborhood: userData.neighborhood,
      state: userData.state,
      biography: userData.biography
    })
  },

  /**
   * 🗑️ Deletar usuário
   */
  async delete(id) {
    return await api.delete(`/users/${id}`)
  }
}
