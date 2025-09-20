import React, { useState, useRef } from 'react';
import { useChat } from '../context/ChatContext';
import { Send, Paperclip, X } from 'lucide-react';
import './MessageInput.css';

const MessageInput = () => {
  const { sendMessage, sendDocument, currentConversation } = useChat();
  const [message, setMessage] = useState('');
  const [selectedFile, setSelectedFile] = useState(null);
  const fileInputRef = useRef(null);

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    if (!message.trim() && !selectedFile) return;
    if (!currentConversation) return;

    try {
      if (selectedFile) {
        await sendDocument(selectedFile);
        setSelectedFile(null);
      } else {
        await sendMessage(message);
      }
      setMessage('');
    } catch (error) {
      console.error('Erro ao enviar mensagem:', error);
    }
  };

  const handleFileSelect = (e) => {
    const file = e.target.files[0];
    if (file) {
      // Validar tipo de arquivo (apenas documentos)
      const allowedTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/plain',
        'application/zip',
        'application/x-rar-compressed'
      ];

      if (!allowedTypes.includes(file.type)) {
        alert('Apenas documentos são permitidos (PDF, DOC, DOCX, XLS, XLSX, TXT, ZIP, RAR)');
        return;
      }

      // Validar tamanho (máximo 10MB)
      const maxSize = 10 * 1024 * 1024; // 10MB
      if (file.size > maxSize) {
        alert('Arquivo muito grande. Tamanho máximo: 10MB');
        return;
      }

      setSelectedFile(file);
    }
  };

  const removeSelectedFile = () => {
    setSelectedFile(null);
    if (fileInputRef.current) {
      fileInputRef.current.value = '';
    }
  };

  const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  };

  return (
    <div className="message-input">
      <form onSubmit={handleSubmit} className="message-form">
        {selectedFile && (
          <div className="selected-file">
            <div className="file-info">
              <Paperclip size={16} />
              <span className="file-name">{selectedFile.name}</span>
              <span className="file-size">({formatFileSize(selectedFile.size)})</span>
            </div>
            <button
              type="button"
              className="remove-file"
              onClick={removeSelectedFile}
            >
              <X size={16} />
            </button>
          </div>
        )}

        <div className="input-container">
          <input
            type="file"
            ref={fileInputRef}
            onChange={handleFileSelect}
            accept=".pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.rar"
            style={{ display: 'none' }}
          />
          
          <button
            type="button"
            className="attach-button"
            onClick={() => fileInputRef.current?.click()}
            title="Anexar documento"
          >
            <Paperclip size={20} />
          </button>

          <input
            type="text"
            value={message}
            onChange={(e) => setMessage(e.target.value)}
            placeholder="Digite sua mensagem..."
            className="message-text-input"
            disabled={!!selectedFile}
          />

          <button
            type="submit"
            className="send-button"
            disabled={!message.trim() && !selectedFile}
          >
            <Send size={20} />
          </button>
        </div>
      </form>
    </div>
  );
};

export default MessageInput;



