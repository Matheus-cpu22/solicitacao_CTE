const onlineUsers = new Map(); // socketId -> userId

export function registerSocketHandlers(io, db) {
  io.on('connection', (socket) => {
    // Expectativa: cliente envia userId na conexão (futuro: autenticação)
    const { userId } = socket.handshake.auth || {};
    if (userId) {
      onlineUsers.set(socket.id, userId);
      io.emit('user_online', { userId });
    }

    socket.on('join_conversation', ({ conversationId }) => {
      if (!conversationId) return;
      socket.join(`conversation:${conversationId}`);
    });

    socket.on('leave_conversation', ({ conversationId }) => {
      if (!conversationId) return;
      socket.leave(`conversation:${conversationId}`);
    });

    socket.on('send_message', async (data) => {
      console.log('Mensagem recebida via socket:', data);
      const { id_conversa, id_usuario, conteudo, tipo = 'texto', nome_arquivo = null, tamanho_arquivo = null } = data || {};
      if (!id_conversa || !id_usuario || !conteudo) {
        console.log('Dados inválidos para envio de mensagem:', { id_conversa, id_usuario, conteudo });
        return;
      }
      try {
        const [result] = await db.query(
          `INSERT INTO mensagem (id_conversa, id_usuario, conteudo, tipo, nome_arquivo, tamanho_arquivo)
           VALUES (?, ?, ?, ?, ?, ?)`,
          [id_conversa, id_usuario, conteudo, tipo, nome_arquivo, tamanho_arquivo]
        );
        const [rows] = await db.query('SELECT * FROM mensagem WHERE id = ?', [result.insertId]);
        const message = rows[0];

        console.log('Enviando mensagem para sala:', `conversation:${id_conversa}`, message);
        io.to(`conversation:${id_conversa}`).emit('new_message', message);
      } catch (err) {
        console.error('Erro no envio via socket:', err);
      }
    });

    socket.on('disconnect', () => {
      const uid = onlineUsers.get(socket.id);
      if (uid) {
        io.emit('user_offline', { userId: uid });
      }
      onlineUsers.delete(socket.id);
    });
  });
}



