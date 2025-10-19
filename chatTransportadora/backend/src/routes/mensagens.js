import { Router } from 'express';
import multer from 'multer';
import path from 'path';
import { fileURLToPath } from 'url';
import fs from 'fs';
import { db } from '../db.js';

const router = Router();

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const uploadsDir = path.join(__dirname, '..', 'uploads');
if (!fs.existsSync(uploadsDir)) {
  fs.mkdirSync(uploadsDir, { recursive: true });
}

const storage = multer.diskStorage({
  destination: (_req, _file, cb) => cb(null, uploadsDir),
  filename: (_req, file, cb) => {
    const unique = Date.now() + '-' + Math.round(Math.random() * 1e9);
    const sanitized = file.originalname.replace(/[^a-zA-Z0-9_.-]/g, '_');
    cb(null, `${unique}-${sanitized}`);
  },
});

const upload = multer({ storage });

// GET /api/mensagens/conversa/:id
router.get('/conversa/:id', async (req, res) => {
  const conversationId = Number(req.params.id);
  try {
    const [rows] = await db.query(
      `SELECT m.id, m.id_conversa, m.id_usuario, m.conteudo, m.tipo, m.nome_arquivo, m.tamanho_arquivo, m.enviado_em
       FROM mensagem m
       WHERE m.id_conversa = ?
       ORDER BY m.enviado_em ASC`,
      [conversationId]
    );
    res.json(rows);
  } catch (err) {
    console.error('Erro ao buscar mensagens:', err);
    res.status(500).json({ error: 'Erro ao buscar mensagens' });
  }
});

// POST /api/mensagens (texto)
router.post('/', async (req, res) => {
  const { id_conversa, id_usuario, conteudo } = req.body || {};
  if (!id_conversa || !id_usuario || !conteudo) {
    return res.status(400).json({ error: 'Campos obrigatórios: id_conversa, id_usuario, conteudo' });
  }
  try {
    const [result] = await db.query(
      `INSERT INTO mensagem (id_conversa, id_usuario, conteudo, tipo) VALUES (?, ?, ?, 'texto')`,
      [id_conversa, id_usuario, conteudo]
    );
    const [rows] = await db.query('SELECT * FROM mensagem WHERE id = ?', [result.insertId]);
    res.status(201).json(rows[0]);
  } catch (err) {
    console.error('Erro ao enviar mensagem:', err);
    res.status(500).json({ error: 'Erro ao enviar mensagem' });
  }
});

// POST /api/mensagens/documento (upload)
router.post('/documento', upload.single('arquivo'), async (req, res) => {
  const { id_conversa, id_usuario } = req.body || {};
  const file = req.file;
  if (!id_conversa || !id_usuario || !file) {
    return res.status(400).json({ error: 'Campos obrigatórios: id_conversa, id_usuario, arquivo' });
  }

  try {
    const relativePath = `/uploads/${file.filename}`;
    const [result] = await db.query(
      `INSERT INTO mensagem (id_conversa, id_usuario, conteudo, tipo, nome_arquivo, tamanho_arquivo)
       VALUES (?, ?, ?, 'documento', ?, ?)`,
      [id_conversa, id_usuario, relativePath, file.originalname, file.size]
    );
    const [rows] = await db.query('SELECT * FROM mensagem WHERE id = ?', [result.insertId]);
    res.status(201).json(rows[0]);
  } catch (err) {
    console.error('Erro ao enviar documento:', err);
    res.status(500).json({ error: 'Erro ao enviar documento' });
  }
});

export default router;



