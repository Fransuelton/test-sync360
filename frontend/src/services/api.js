/**
 * 🌐 API Service - Configuração base para requisições HTTP
 * 
 * Centraliza a configuração de URLs, headers e interceptors
 * para todas as requisições da aplicação.
 */

// Configuração base da API
const API_CONFIG = {
  baseURL: 'http://localhost:8000/api',
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  }
}

/**
 * Classe para gerenciar requisições HTTP
 */
class ApiService {
  constructor(config = API_CONFIG) {
    this.baseURL = config.baseURL
    this.timeout = config.timeout
    this.defaultHeaders = config.headers
  }

  /**
   * Método base para fazer requisições
   */
  async request(endpoint, options = {}) {
    const url = `${this.baseURL}${endpoint}`
    
    const config = {
      timeout: this.timeout,
      headers: {
        ...this.defaultHeaders,
        ...options.headers
      },
      ...options
    }

    try {
      const response = await fetch(url, config)
      
      // Se não for OK, lança erro com detalhes
      if (!response.ok) {
        const errorData = await response.json().catch(() => ({}))
        throw new Error(errorData.message || `HTTP ${response.status}: ${response.statusText}`)
      }

      return await response.json()
    } catch (error) {
      console.error(`❌ API Error [${options.method || 'GET'}] ${endpoint}:`, error)
      throw error
    }
  }

  /**
   * Métodos de conveniência para diferentes verbos HTTP
   */
  async get(endpoint, options = {}) {
    return this.request(endpoint, { ...options, method: 'GET' })
  }

  async post(endpoint, data, options = {}) {
    const isFormData = data instanceof FormData
    
    return this.request(endpoint, {
      ...options,
      method: 'POST',
      headers: isFormData ? {} : options.headers, // Remove Content-Type para FormData
      body: isFormData ? data : JSON.stringify(data)
    })
  }

  async put(endpoint, data, options = {}) {
    const isFormData = data instanceof FormData
    
    return this.request(endpoint, {
      ...options,
      method: 'PUT',
      headers: isFormData ? {} : options.headers,
      body: isFormData ? data : JSON.stringify(data)
    })
  }

  async delete(endpoint, options = {}) {
    return this.request(endpoint, { ...options, method: 'DELETE' })
  }
}

// Instância singleton da API
export const api = new ApiService()

// Exportar também a classe para casos específicos
export { ApiService }
