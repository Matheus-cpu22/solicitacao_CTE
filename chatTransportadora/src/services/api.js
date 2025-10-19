import axios from 'axios';

// Em projetos Vite, as variáveis vêm de import.meta.env e precisam do prefixo VITE_
const API_BASE_URL = (import.meta?.env?.VITE_API_URL) || 'http://localhost:3001/api';

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Interceptor para adicionar token de autenticação (quando implementado)
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('authToken');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Serviços de usuários
export const userService = {
  // Retorna o usuário atual do localStorage (definido pelo fluxo de login externo)
  getCurrentUser: () => {
    try {
      const raw = localStorage.getItem('chatUser');
      if (raw) return JSON.parse(raw);

      // Compatibilidade com chaves separadas
      const id = localStorage.getItem('userId');
      const nome = localStorage.getItem('userName');
      const email = localStorage.getItem('userEmail');
      if (id && nome && email) return { id: Number(id), nome, email };
      return null;
    } catch (e) {
      return null;
    }
  },

  // Buscar todos os usuários disponíveis para conversar
  getUsers: async () => {
    try {
      const response = await api.get('/usuarios');
      return response.data;
    } catch (error) {
      console.error('Erro ao buscar usuários:', error);
      return [];
    }
  }
};

// Serviços de conversas
export const conversationService = {
  // Buscar conversas do usuário
  getConversations: async (userId) => {
    try {
      const response = await api.get(`/conversas/usuario/${userId}`);
      return response.data;
    } catch (error) {
      console.error('Erro ao buscar conversas:', error);
      return [];
    }
  },

  // Criar nova conversa
  createConversation: async (participantIds) => {
    try {
      const response = await api.post('/conversas', { participantes: participantIds });
      return response.data;
    } catch (error) {
      console.error('Erro ao criar conversa:', error);
      throw error;
    }
  }
};

// Serviços de mensagens
export const messageService = {
  // Buscar mensagens de uma conversa
  getMessages: async (conversationId) => {
    try {
      const response = await api.get(`/mensagens/conversa/${conversationId}`);
      return response.data;
    } catch (error) {
      console.error('Erro ao buscar mensagens:', error);
      return [];
    }
  },

  // Enviar mensagem de texto
  sendMessage: async (conversationId, content, userId) => {
    try {
      const response = await api.post('/mensagens', {
        id_conversa: conversationId,
        id_usuario: userId,
        conteudo: content,
        tipo: 'texto'
      });
      return response.data;
    } catch (error) {
      console.error('Erro ao enviar mensagem:', error);
      throw error;
    }
  },

  // Enviar documento
  sendDocument: async (conversationId, file, userId) => {
    try {
      const formData = new FormData();
      formData.append('arquivo', file);
      formData.append('id_conversa', conversationId);
      formData.append('id_usuario', userId);

      const response = await api.post('/mensagens/documento', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });
      return response.data;
    } catch (error) {
      console.error('Erro ao enviar documento:', error);
      throw error;
    }
  }
};

export default api;

