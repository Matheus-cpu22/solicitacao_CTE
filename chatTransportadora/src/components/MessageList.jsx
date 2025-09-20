import React, { useEffect, useRef } from 'react';
import { useChat } from '../context/ChatContext';
import { FileText, Download, User, MessageCircle } from 'lucide-react';
import './MessageList.css';

const MessageList = ({ messages, loading }) => {
  const { currentUser } = useChat();
  const messagesEndRef = useRef(null);

  // Scroll automático para a última mensagem
  useEffect(() => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  }, [messages]);

  const formatTime = (timestamp) => {
    const date = new Date(timestamp);
    return date.toLocaleTimeString('pt-BR', { 
      hour: '2-digit', 
      minute: '2-digit' 
    });
  };

  const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  };

  const handleDownload = (message) => {
    // Simulação de download - em produção, isso faria uma requisição para o backend
    const link = document.createElement('a');
    link.href = `#`; // URL do arquivo no backend
    link.download = message.nome_arquivo;
    link.click();
  };

  if (loading) {
    return (
      <div className="message-list">
        <div className="loading-messages">
          <div className="loading-spinner"></div>
          <p>Carregando mensagens...</p>
        </div>
      </div>
    );
  }

  if (messages.length === 0) {
    return (
      <div className="message-list">
        <div className="empty-messages">
          <MessageCircle size={48} className="empty-icon" />
          <p>Nenhuma mensagem ainda</p>
          <p>Envie uma mensagem para começar a conversa</p>
        </div>
      </div>
    );
  }

  return (
    <div className="message-list">
      {messages.map((message) => {
        const isOwnMessage = message.id_usuario === currentUser?.id;
        const isDocument = message.tipo === 'documento';

        return (
          <div
            key={message.id}
            className={`message-item ${isOwnMessage ? 'own' : 'other'}`}
          >
            <div className="message-content">
              {!isOwnMessage && (
                <div className="message-avatar">
                  <User size={16} />
                </div>
              )}
              
              <div className="message-bubble">
                {!isOwnMessage && (
                  <div className="message-sender">{message.usuario?.nome}</div>
                )}
                
                {isDocument ? (
                  <div className="document-message">
                    <div className="document-icon">
                      <FileText size={20} />
                    </div>
                    <div className="document-info">
                      <div className="document-name">{message.nome_arquivo}</div>
                      <div className="document-size">
                        {formatFileSize(message.tamanho_arquivo || 0)}
                      </div>
                    </div>
                    <button
                      className="download-button"
                      onClick={() => handleDownload(message)}
                      title="Baixar arquivo"
                    >
                      <Download size={16} />
                    </button>
                  </div>
                ) : (
                  <div className="message-text">{message.conteudo}</div>
                )}
                
                <div className="message-time">
                  {formatTime(message.enviado_em)}
                </div>
              </div>
            </div>
          </div>
        );
      })}
      <div ref={messagesEndRef} />
    </div>
  );
};

export default MessageList;

