import axios from 'axios';

// Em projetos Vite, as variáveis vêm de import.meta.env e precisam do prefixo VITE_
const API_BASE_URL = (import.meta?.env?.VITE_API_URL) || 'http://localhost:3001/api';
const USE_MOCKS = String(import.meta?.env?.VITE_USE_MOCKS || 'true') === 'true';

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
  // Simulação de usuário logado (para demonstração)
  getCurrentUser: () => {
    return {
      id: 1,
      nome: 'Usuário Demo',
      email: 'demo@exemplo.com'
    };
  },

  // Buscar todos os usuários disponíveis para conversar
  getUsers: async () => {
    if (USE_MOCKS) {
      return [
        { id: 2, nome: 'João Silva', email: 'joao@exemplo.com' },
        { id: 3, nome: 'Maria Santos', email: 'maria@exemplo.com' },
        { id: 4, nome: 'Pedro Costa', email: 'pedro@exemplo.com' },
        { id: 5, nome: 'Ana Oliveira', email: 'ana@exemplo.com' },
      ];
    }
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
    if (USE_MOCKS) {
      return [
        { id: 1, nome: 'João Silva', ultimaMensagem: 'Olá! Como você está?', timestamp: new Date().toISOString(), participantes: [{ id: 2 }] },
        { id: 2, nome: 'Maria Santos', ultimaMensagem: 'Enviei o documento', timestamp: new Date(Date.now() - 3600000).toISOString(), participantes: [{ id: 3 }] },
        { id: 3, nome: 'Pedro Costa', ultimaMensagem: 'Obrigado pela ajuda!', timestamp: new Date(Date.now() - 7200000).toISOString(), participantes: [{ id: 4 }] },
      ];
    }
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
    if (USE_MOCKS) {
      // Retorna uma conversa mockada
      const id = Math.floor(Math.random() * 100000) + 10;
      return { id, nome: 'Nova Conversa', participantes: participantIds.map(id => ({ id })), timestamp: new Date().toISOString() };
    }
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
    if (USE_MOCKS) {
      return [
        {
          id: 1,
          id_conversa: conversationId,
          id_usuario: 2,
          conteudo: 'Olá! Como você está?',
          enviado_em: new Date(Date.now() - 1800000).toISOString(),
          tipo: 'texto',
          usuario: { nome: 'João Silva' }
        },
        {
          id: 2,
          id_conversa: conversationId,
          id_usuario: 1,
          conteudo: 'Oi! Estou bem, obrigado!',
          enviado_em: new Date(Date.now() - 1500000).toISOString(),
          tipo: 'texto',
          usuario: { nome: 'Usuário Demo' }
        },
        {
          id: 3,
          id_conversa: conversationId,
          id_usuario: 2,
          conteudo: 'documento.pdf',
          enviado_em: new Date(Date.now() - 1200000).toISOString(),
          tipo: 'documento',
          nome_arquivo: 'documento.pdf',
          tamanho_arquivo: 1024000,
          usuario: { nome: 'João Silva' }
        }
      ];
    }
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
    if (USE_MOCKS) {
      return {
        id: Math.floor(Math.random() * 100000) + 1000,
        id_conversa: conversationId,
        id_usuario: userId,
        conteudo: content,
        tipo: 'texto',
        enviado_em: new Date().toISOString(),
        usuario: { nome: 'Você' },
      };
    }
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
    if (USE_MOCKS) {
      return {
        id: Math.floor(Math.random() * 100000) + 1000,
        id_conversa: conversationId,
        id_usuario: userId,
        conteudo: file?.name || 'arquivo',
        tipo: 'documento',
        nome_arquivo: file?.name,
        tamanho_arquivo: file?.size,
        enviado_em: new Date().toISOString(),
        usuario: { nome: 'Você' },
      };
    }
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

