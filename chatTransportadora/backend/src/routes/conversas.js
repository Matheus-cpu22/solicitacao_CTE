import { Router } from 'express';
import { db } from '../db.js';

const router = Router();

// GET /api/conversas/usuario/:id
router.get('/usuario/:id', async (req, res) => {
  const userId = Number(req.params.id);
  try {
    const [rows] = await db.query(
      `SELECT c.id, c.nome, c.criacao
       FROM conversa c
       JOIN conversa_membros m ON m.id_conversa = c.id
       WHERE m.id_usuario = ?
       ORDER BY c.criacao DESC`,
      [userId]
    );

    // Opcional: última mensagem e participantes
    const conversations = await Promise.all(rows.map(async (c) => {
      const [lastMsgRows] = await db.query(
        `SELECT conteudo, tipo, enviado_em FROM mensagem WHERE id_conversa = ? ORDER BY enviado_em DESC LIMIT 1`,
        [c.id]
      );
      const [membersRows] = await db.query(
        `SELECT u.id, u.nome FROM conversa_membros m JOIN usuario u ON u.id = m.id_usuario WHERE m.id_conversa = ?`,
        [c.id]
      );
      return {
        ...c,
        ultimaMensagem: lastMsgRows[0]?.conteudo || null,
        timestamp: lastMsgRows[0]?.enviado_em || c.criacao,
        participantes: membersRows.map(r => ({ id: r.id, nome: r.nome })),
      };
    }));

    res.json(conversations);
  } catch (err) {
    console.error('Erro ao listar conversas:', err);
    res.status(500).json({ error: 'Erro ao listar conversas' });
  }
});

// POST /api/conversas { participantes: number[] }
router.post('/', async (req, res) => {
  const { participantes = [] } = req.body || {};
  if (!Array.isArray(participantes) || participantes.length === 0) {
    return res.status(400).json({ error: 'Participantes obrigatórios' });
  }

  const conn = await db.getConnection();
  try {
    await conn.beginTransaction();
    const [result] = await conn.query('INSERT INTO conversa (nome) VALUES (?)', ['Conversa']);
    const conversaId = result.insertId;

    for (const userId of participantes) {
      await conn.query('INSERT INTO conversa_membros (id_conversa, id_usuario) VALUES (?, ?)', [conversaId, userId]);
    }

    await conn.commit();
    res.status(201).json({ id: conversaId, nome: 'Conversa', participantes: participantes.map(id => ({ id })) });
  } catch (err) {
    await conn.rollback();
    console.error('Erro ao criar conversa:', err);
    res.status(500).json({ error: 'Erro ao criar conversa' });
  } finally {
    conn.release();
  }
});

export default router;



