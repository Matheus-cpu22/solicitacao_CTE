import { io } from 'socket.io-client';

class SocketService {
  constructor() {
    this.socket = null;
    this.isConnected = false;
    this.useMocks = String(import.meta?.env?.VITE_USE_MOCKS ?? 'false') === 'true';
  }

  // Método para obter usuário atual do localStorage
  getCurrentUser() {
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
  }

  connect() {
    if (this.useMocks) {
      // Em modo mock não conecta ao servidor
      this.isConnected = false;
      return null;
    }
    
    // Desconectar socket anterior se existir
    if (this.socket) {
      this.socket.disconnect();
      this.socket = null;
    }
    
    // Em Vite, use import.meta.env.VITE_*
    const serverUrl = (import.meta?.env?.VITE_SOCKET_URL) || 'http://localhost:3001';
    
    // Obter usuário atual para autenticação
    const currentUser = this.getCurrentUser();
    console.log('Conectando WebSocket com usuário:', currentUser);
    
    this.socket = io(serverUrl, {
      transports: ['websocket', 'polling'],
      autoConnect: true,
      auth: {
        userId: currentUser?.id
      }
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
    console.log('SocketService.sendMessage chamado:', messageData);
    console.log('useMocks:', this.useMocks, 'socket:', !!this.socket, 'isConnected:', this.isConnected);
    
    if (this.useMocks) {
      console.log('Modo mock ativo, não enviando mensagem');
      return;
    }
    if (this.socket && this.isConnected) {
      console.log('Emitindo mensagem via WebSocket');
      this.socket.emit('send_message', messageData);
    } else {
      console.log('WebSocket não conectado, não é possível enviar mensagem');
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

