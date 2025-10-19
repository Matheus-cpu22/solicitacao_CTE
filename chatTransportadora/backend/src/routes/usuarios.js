import { Router } from 'express';
import { db } from '../db.js';

const router = Router();

// GET /api/usuarios
router.get('/', async (_req, res) => {
  try {
    const [rows] = await db.query('SELECT id, nome, email FROM usuario ORDER BY nome');
    res.json(rows);
  } catch (err) {
    console.error('Erro ao listar usuários:', err);
    res.status(500).json({ error: 'Erro ao listar usuários' });
  }
});

export default router;



