import mysql from 'mysql2/promise';

const {
  DB_HOST = 'localhost',
  DB_PORT = '3307',
  DB_USER = 'root',
  DB_PASSWORD = 'aluno',
  DB_NAME = 'chat_transportadora',
} = process.env;

export const db = mysql.createPool({
  host: DB_HOST,
  port: Number(DB_PORT),
  user: DB_USER,
  password: DB_PASSWORD,
  database: DB_NAME,
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0,
});

export async function ensureSchema() {
  // Criação de schema se não existir
  await db.query(`CREATE TABLE IF NOT EXISTS usuario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL
  )`);

  await db.query(`CREATE TABLE IF NOT EXISTS conversa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  )`);

  await db.query(`CREATE TABLE IF NOT EXISTS conversa_membros (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_conversa INT NOT NULL,
    id_usuario INT NOT NULL,
    entrada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_conversa) REFERENCES conversa(id) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id) ON DELETE CASCADE
  )`);

  await db.query(`CREATE TABLE IF NOT EXISTS mensagem (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_conversa INT NOT NULL,
    id_usuario INT NOT NULL,
    conteudo TEXT,
    tipo ENUM('texto', 'documento') DEFAULT 'texto',
    nome_arquivo VARCHAR(255),
    tamanho_arquivo BIGINT,
    enviado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_conversa) REFERENCES conversa(id) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id) ON DELETE CASCADE
  )`);
}



