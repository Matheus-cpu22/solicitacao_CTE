import 'dotenv/config';
import express from 'express';
import http from 'http';
import cors from 'cors';
import morgan from 'morgan';
import { Server as SocketIOServer } from 'socket.io';
import path from 'path';
import { fileURLToPath } from 'url';

import { db, ensureSchema } from './src/db.js';
import apiRouter from './src/routes/index.js';
import { registerSocketHandlers } from './src/socket.js';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();

// Basic middlewares
app.use(cors({
  origin: [process.env.CLIENT_ORIGIN || 'http://localhost:3000', 'http://localhost:5173'],
  methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
  credentials: true,
}));
app.use(morgan('dev'));
app.use(express.json());

// Static uploads
const uploadsDir = path.join(__dirname, 'uploads');
app.use('/uploads', express.static(uploadsDir));

// API routes
app.use('/api', apiRouter);

// Health check
app.get('/health', (_req, res) => res.json({ ok: true }));

// Create HTTP server and Socket.IO
const server = http.createServer(app);
const io = new SocketIOServer(server, {
  cors: {
    origin: [process.env.CLIENT_ORIGIN || 'http://localhost:3000', 'http://localhost:5173'],
    methods: ['GET', 'POST'],
  },
});

registerSocketHandlers(io, db);

const PORT = process.env.PORT || 3001;

async function start() {
  try {
    await ensureSchema();
    server.listen(PORT, () => {
      console.log(`API e WebSocket disponíveis em http://localhost:${PORT}`);
    });
  } catch (err) {
    console.error('Falha ao iniciar servidor:', err);
    process.exit(1);
  }
}

start();



