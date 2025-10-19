import { Router } from 'express';
import usuariosRouter from './usuarios.js';
import conversasRouter from './conversas.js';
import mensagensRouter from './mensagens.js';

const router = Router();

router.use('/usuarios', usuariosRouter);
router.use('/conversas', conversasRouter);
router.use('/mensagens', mensagensRouter);

export default router;



