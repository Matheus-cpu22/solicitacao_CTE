import { io } from 'socket.io-client';

class SocketService {
  constructor() {
    this.socket = null;
    this.isConnected = false;
    this.useMocks = String(import.meta?.env?.VITE_USE_MOCKS || 'true') === 'true';
  }

  connect() {
    if (this.useMocks) {
      // Em modo mock não conecta ao servidor
      this.isConnected = false;
      return null;
    }
    if (!this.socket) {
      // Em Vite, use import.meta.env.VITE_*
      const serverUrl = (import.meta?.env?.VITE_SOCKET_URL) || 'http://localhost:3001';
      this.socket = io(serverUrl, {
        transports: ['websocket', 'polling'],
        autoConnect: true,
      });

      this.socket.on('connect', () => {
        console.log('Conectado ao servidor WebSocket');
        this.isConnected = true;
      });

      this.socket.on('disconnect', () => {
        console.log('Desconectado do servidor WebSocket');
        this.isConnected = false;
      });

      this.socket.on('connect_error', (error) => {
        console.error('Erro de conexão WebSocket:', error);
        this.isConnected = false;
      });
    }

    return this.socket;
  }

  disconnect() {
    if (this.socket) {
      this.socket.disconnect();
      this.socket = null;
      this.isConnected = false;
    }
  }

  // Entrar em uma sala de conversa
  joinConversation(conversationId) {
    if (this.useMocks) return;
    if (this.socket && this.isConnected) {
      this.socket.emit('join_conversation', { conversationId });
    }
  }

  // Sair de uma sala de conversa
  leaveConversation(conversationId) {
    if (this.useMocks) return;
    if (this.socket && this.isConnected) {
      this.socket.emit('leave_conversation', { conversationId });
    }
  }

  // Enviar mensagem via WebSocket
  sendMessage(messageData) {
    if (this.useMocks) return;
    if (this.socket && this.isConnected) {
      this.socket.emit('send_message', messageData);
    }
  }

  // Escutar novas mensagens
  onNewMessage(callback) {
    if (this.useMocks) return;
    if (this.socket) {
      this.socket.on('new_message', callback);
    }
  }

  // Escutar usuários online
  onUserOnline(callback) {
    if (this.useMocks) return;
    if (this.socket) {
      this.socket.on('user_online', callback);
    }
  }

  // Escutar usuários offline
  onUserOffline(callback) {
    if (this.useMocks) return;
    if (this.socket) {
      this.socket.on('user_offline', callback);
    }
  }

  // Remover listeners
  removeListener(event, callback) {
    if (this.useMocks) return;
    if (this.socket) {
      this.socket.off(event, callback);
    }
  }

  // Verificar se está conectado
  getConnectionStatus() {
    return this.isConnected;
  }
}

// Instância singleton
const socketService = new SocketService();

export default socketService;

