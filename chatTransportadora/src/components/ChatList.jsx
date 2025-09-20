import React, { useEffect } from 'react';
import { useChat } from '../context/ChatContext';
import { Users, MessageCircle, Clock } from 'lucide-react';
import './ChatList.css';

const ChatList = () => {
  const {
    users,
    conversations,
    currentConversation,
    loading,
    loadUsers,
    loadConversations,
    selectConversation,
    createConversation,
  } = useChat();

  useEffect(() => {
    loadUsers();
    loadConversations();
  }, [loadUsers, loadConversations]);

  const handleUserClick = async (user) => {
    // Verificar se já existe uma conversa com este usuário
    const existingConversation = conversations.find(conv => 
      conv.participantes && conv.participantes.some(p => p.id === user.id)
    );

    if (existingConversation) {
      selectConversation(existingConversation);
    } else {
      // Criar nova conversa
      try {
        const newConversation = await createConversation([user.id]);
        selectConversation(newConversation);
      } catch (error) {
        console.error('Erro ao criar conversa:', error);
      }
    }
  };

  const formatTime = (timestamp) => {
    const date = new Date(timestamp);
    const now = new Date();
    const diffInHours = (now - date) / (1000 * 60 * 60);

    if (diffInHours < 24) {
      return date.toLocaleTimeString('pt-BR', { 
        hour: '2-digit', 
        minute: '2-digit' 
      });
    } else {
      return date.toLocaleDateString('pt-BR', { 
        day: '2-digit', 
        month: '2-digit' 
      });
    }
  };

  if (loading.users || loading.conversations) {
    return (
      <div className="chat-list">
        <div className="chat-list-header">
          <h2>Conversas</h2>
        </div>
        <div className="loading">
          <div className="loading-spinner"></div>
          <p>Carregando...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="chat-list">
      <div className="chat-list-header">
        <h2>Conversas</h2>
        <Users className="users-icon" size={20} />
      </div>

      <div className="chat-list-content">
        {/* Lista de conversas existentes */}
        {conversations.length > 0 && (
          <div className="conversations-section">
            <h3>Conversas Recentes</h3>
            {conversations.map((conversation) => (
              <div
                key={conversation.id}
                className={`conversation-item ${
                  currentConversation?.id === conversation.id ? 'active' : ''
                }`}
                onClick={() => selectConversation(conversation)}
              >
                <div className="conversation-avatar">
                  <MessageCircle size={20} />
                </div>
                <div className="conversation-info">
                  <div className="conversation-name">{conversation.nome}</div>
                  <div className="conversation-last-message">
                    {conversation.ultimaMensagem}
                  </div>
                </div>
                <div className="conversation-time">
                  {formatTime(conversation.timestamp)}
                </div>
              </div>
            ))}
          </div>
        )}

        {/* Lista de usuários disponíveis */}
        <div className="users-section">
          <h3>Usuários Disponíveis</h3>
          {users.map((user) => (
            <div
              key={user.id}
              className="user-item"
              onClick={() => handleUserClick(user)}
            >
              <div className="user-avatar">
                <Users size={20} />
              </div>
              <div className="user-info">
                <div className="user-name">{user.nome}</div>
                <div className="user-email">{user.email}</div>
              </div>
              <div className="user-status">
                <div className="status-indicator online"></div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default ChatList;



