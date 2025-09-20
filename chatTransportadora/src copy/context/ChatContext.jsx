import React, { createContext, useContext, useReducer, useEffect } from 'react';
import { userService, conversationService, messageService } from '../services/api';
import socketService from '../services/socket';

const ChatContext = createContext();

// Estado inicial
const initialState = {
  currentUser: null,
  users: [],
  conversations: [],
  currentConversation: null,
  messages: [],
  loading: {
    users: false,
    conversations: false,
    messages: false,
  },
  error: null,
  onlineUsers: new Set(),
};

// Reducer para gerenciar estado
const chatReducer = (state, action) => {
  switch (action.type) {
    case 'SET_LOADING':
      return {
        ...state,
        loading: {
          ...state.loading,
          [action.payload.key]: action.payload.value,
        },
      };

    case 'SET_ERROR':
      return {
        ...state,
        error: action.payload,
      };

    case 'SET_CURRENT_USER':
      return {
        ...state,
        currentUser: action.payload,
      };

    case 'SET_USERS':
      return {
        ...state,
        users: action.payload,
        loading: {
          ...state.loading,
          users: false,
        },
      };

    case 'SET_CONVERSATIONS':
      return {
        ...state,
        conversations: action.payload,
        loading: {
          ...state.loading,
          conversations: false,
        },
      };

    case 'SET_CURRENT_CONVERSATION':
      return {
        ...state,
        currentConversation: action.payload,
      };

    case 'SET_MESSAGES':
      return {
        ...state,
        messages: action.payload,
        loading: {
          ...state.loading,
          messages: false,
        },
      };

    case 'ADD_MESSAGE':
      return {
        ...state,
        messages: [...state.messages, action.payload],
      };

    case 'UPDATE_CONVERSATION_LAST_MESSAGE':
      return {
        ...state,
        conversations: state.conversations.map(conv =>
          conv.id === action.payload.conversationId
            ? { ...conv, ultimaMensagem: action.payload.message, timestamp: action.payload.timestamp }
            : conv
        ),
      };

    case 'SET_ONLINE_USERS':
      return {
        ...state,
        onlineUsers: new Set(action.payload),
      };

    case 'ADD_ONLINE_USER':
      return {
        ...state,
        onlineUsers: new Set([...state.onlineUsers, action.payload]),
      };

    case 'REMOVE_ONLINE_USER':
      const newOnlineUsers = new Set(state.onlineUsers);
      newOnlineUsers.delete(action.payload);
      return {
        ...state,
        onlineUsers: newOnlineUsers,
      };

    default:
      return state;
  }
};

// Provider do contexto
export const ChatProvider = ({ children }) => {
  const [state, dispatch] = useReducer(chatReducer, initialState);

  // Inicializar usuário atual
  useEffect(() => {
    const currentUser = userService.getCurrentUser();
    dispatch({ type: 'SET_CURRENT_USER', payload: currentUser });
  }, []);

  // Conectar ao WebSocket
  useEffect(() => {
    socketService.connect();

    // Configurar listeners do WebSocket
    const handleNewMessage = (message) => {
      dispatch({ type: 'ADD_MESSAGE', payload: message });
      dispatch({
        type: 'UPDATE_CONVERSATION_LAST_MESSAGE',
        payload: {
          conversationId: message.id_conversa,
          message: message.conteudo,
          timestamp: message.enviado_em,
        },
      });
    };

    const handleUserOnline = (userId) => {
      dispatch({ type: 'ADD_ONLINE_USER', payload: userId });
    };

    const handleUserOffline = (userId) => {
      dispatch({ type: 'REMOVE_ONLINE_USER', payload: userId });
    };

    socketService.onNewMessage(handleNewMessage);
    socketService.onUserOnline(handleUserOnline);
    socketService.onUserOffline(handleUserOffline);

    return () => {
      socketService.removeListener('new_message', handleNewMessage);
      socketService.removeListener('user_online', handleUserOnline);
      socketService.removeListener('user_offline', handleUserOffline);
    };
  }, []);

  // Carregar usuários
  const loadUsers = async () => {
    try {
      dispatch({ type: 'SET_LOADING', payload: { key: 'users', value: true } });
      const users = await userService.getUsers();
      dispatch({ type: 'SET_USERS', payload: users });
    } catch (error) {
      dispatch({ type: 'SET_ERROR', payload: error.message });
    }
  };

  // Carregar conversas
  const loadConversations = async () => {
    if (!state.currentUser) return;

    try {
      dispatch({ type: 'SET_LOADING', payload: { key: 'conversations', value: true } });
      const conversations = await conversationService.getConversations(state.currentUser.id);
      dispatch({ type: 'SET_CONVERSATIONS', payload: conversations });
    } catch (error) {
      dispatch({ type: 'SET_ERROR', payload: error.message });
    }
  };

  // Selecionar conversa
  const selectConversation = async (conversation) => {
    try {
      dispatch({ type: 'SET_CURRENT_CONVERSATION', payload: conversation });
      dispatch({ type: 'SET_LOADING', payload: { key: 'messages', value: true } });

      // Entrar na sala da conversa no WebSocket
      socketService.joinConversation(conversation.id);

      // Carregar mensagens da conversa
      const messages = await messageService.getMessages(conversation.id);
      dispatch({ type: 'SET_MESSAGES', payload: messages });
    } catch (error) {
      dispatch({ type: 'SET_ERROR', payload: error.message });
    }
  };

  // Enviar mensagem
  const sendMessage = async (content) => {
    if (!state.currentConversation || !state.currentUser) return;

    try {
      const message = await messageService.sendMessage(
        state.currentConversation.id,
        content,
        state.currentUser.id
      );

      // Enviar via WebSocket para outros usuários
      socketService.sendMessage({
        ...message,
        id_conversa: state.currentConversation.id,
      });

      // Adicionar mensagem ao estado local
      dispatch({ type: 'ADD_MESSAGE', payload: message });
    } catch (error) {
      dispatch({ type: 'SET_ERROR', payload: error.message });
    }
  };

  // Enviar documento
  const sendDocument = async (file) => {
    if (!state.currentConversation || !state.currentUser) return;

    try {
      const message = await messageService.sendDocument(
        state.currentConversation.id,
        file,
        state.currentUser.id
      );

      // Enviar via WebSocket para outros usuários
      socketService.sendMessage({
        ...message,
        id_conversa: state.currentConversation.id,
      });

      // Adicionar mensagem ao estado local
      dispatch({ type: 'ADD_MESSAGE', payload: message });
    } catch (error) {
      dispatch({ type: 'SET_ERROR', payload: error.message });
    }
  };

  // Criar nova conversa
  const createConversation = async (participantIds) => {
    try {
      const conversation = await conversationService.createConversation(participantIds);
      await loadConversations(); // Recarregar lista de conversas
      return conversation;
    } catch (error) {
      dispatch({ type: 'SET_ERROR', payload: error.message });
      throw error;
    }
  };

  const value = {
    ...state,
    loadUsers,
    loadConversations,
    selectConversation,
    sendMessage,
    sendDocument,
    createConversation,
  };

  return <ChatContext.Provider value={value}>{children}</ChatContext.Provider>;
};

// Hook para usar o contexto
export const useChat = () => {
  const context = useContext(ChatContext);
  if (!context) {
    throw new Error('useChat deve ser usado dentro de um ChatProvider');
  }
  return context;
};

export default ChatContext;


