import React from 'react';
import { useChat } from '../context/ChatContext';
import MessageList from './MessageList';
import MessageInput from './MessageInput';
import { MessageCircle, Users } from 'lucide-react';
import './ChatWindow.css';

const ChatWindow = () => {
  const { currentConversation, messages, loading } = useChat();

  if (!currentConversation) {
    return (
      <div className="chat-window">
        <div className="chat-window-empty">
          <MessageCircle size={64} className="empty-icon" />
          <h2>Selecione uma conversa</h2>
          <p>Escolha um usuário da lista para começar a conversar</p>
        </div>
      </div>
    );
  }

  return (
    <div className="chat-window">
      <div className="chat-window-header">
        <div className="conversation-info">
          <div className="conversation-avatar">
            <Users size={20} />
          </div>
          <div className="conversation-details">
            <h3>{currentConversation.nome}</h3>
            <span className="conversation-status">Online</span>
          </div>
        </div>
      </div>

      <div className="chat-window-messages">
        <MessageList messages={messages} loading={loading.messages} />
      </div>

      <div className="chat-window-input">
        <MessageInput />
      </div>
    </div>
  );
};

export default ChatWindow;


