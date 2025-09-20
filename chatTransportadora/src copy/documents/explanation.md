# Chat em Tempo Real - Documentação Técnica

## Visão Geral

Este projeto implementa um sistema de chat em tempo real usando React, inspirado no WhatsApp, com funcionalidades de envio de mensagens de texto e documentos. O sistema foi desenvolvido com foco na escalabilidade e manutenibilidade, seguindo as melhores práticas de desenvolvimento frontend.

## Arquitetura do Sistema

### Estrutura de Pastas

```
src/
├── components/          # Componentes reutilizáveis
│   ├── ChatList.jsx     # Lista de conversas e usuários
│   ├── ChatWindow.jsx   # Janela principal do chat
│   ├── MessageList.jsx  # Lista de mensagens
│   ├── MessageInput.jsx # Campo de entrada de mensagens
│   └── *.css           # Estilos dos componentes
├── context/            # Gerenciamento de estado global
│   └── ChatContext.jsx # Context API para estado do chat
├── services/           # Serviços de comunicação
│   ├── api.js          # Serviços de API REST
│   └── socket.js       # Serviços de WebSocket
├── pages/              # Páginas da aplicação
│   ├── ChatPage.jsx    # Página principal do chat
│   └── ChatPage.css    # Estilos da página
├── hooks/              # Custom hooks (futuro)
├── documents/          # Documentação
└── App.jsx            # Componente raiz
```

## Componentes Principais

### 1. ChatContext (Gerenciamento de Estado)

O `ChatContext` utiliza o padrão Context API do React para gerenciar o estado global da aplicação. Implementa um reducer para gerenciar ações complexas de estado:

**Estado Principal:**
- `currentUser`: Usuário logado atual
- `users`: Lista de usuários disponíveis
- `conversations`: Lista de conversas do usuário
- `currentConversation`: Conversa selecionada
- `messages`: Mensagens da conversa atual
- `loading`: Estados de carregamento
- `error`: Mensagens de erro
- `onlineUsers`: Usuários online

**Funcionalidades:**
- Carregamento de usuários e conversas
- Seleção de conversas
- Envio de mensagens e documentos
- Criação de novas conversas
- Integração com WebSocket

### 2. ChatList (Lista de Conversas)

Componente responsável por exibir a lista de conversas e usuários disponíveis:

**Funcionalidades:**
- Exibe conversas recentes com última mensagem e timestamp
- Lista usuários disponíveis para iniciar conversas
- Criação automática de conversas ao clicar em usuários
- Indicadores visuais de status (online/offline)
- Design responsivo

**Características Técnicas:**
- Scroll infinito para listas longas
- Estados de loading e erro
- Animações suaves de transição
- Otimização de performance com React.memo (futuro)

### 3. ChatWindow (Janela Principal)

Componente que gerencia a área principal do chat:

**Funcionalidades:**
- Exibe informações da conversa selecionada
- Integra MessageList e MessageInput
- Estado vazio quando nenhuma conversa está selecionada
- Header com informações do participante

### 4. MessageList (Lista de Mensagens)

Componente responsável por exibir as mensagens da conversa:

**Funcionalidades:**
- Exibição cronológica de mensagens
- Diferenciação visual entre mensagens próprias e recebidas
- Suporte a mensagens de texto e documentos
- Scroll automático para última mensagem
- Animações de entrada das mensagens

**Tipos de Mensagem Suportados:**
- **Texto**: Mensagens de texto simples
- **Documento**: Arquivos anexados com preview e download

### 5. MessageInput (Campo de Entrada)

Componente para envio de mensagens e documentos:

**Funcionalidades:**
- Campo de texto para mensagens
- Upload de documentos com validação
- Preview de arquivos selecionados
- Validação de tipos e tamanhos de arquivo
- Envio via Enter ou botão

**Validações Implementadas:**
- Tipos permitidos: PDF, DOC, DOCX, XLS, XLSX, TXT, ZIP, RAR
- Tamanho máximo: 10MB
- Feedback visual para arquivos selecionados

## Serviços de Comunicação

### 1. API Service (api.js)

Gerencia a comunicação com o backend via REST API:

**Funcionalidades:**
- Configuração centralizada do Axios
- Interceptors para autenticação
- Serviços específicos para usuários, conversas e mensagens
- Fallback com dados simulados para demonstração
- Tratamento de erros centralizado

**Endpoints Implementados:**
- `GET /usuarios` - Listar usuários
- `GET /conversas/usuario/:id` - Conversas do usuário
- `POST /conversas` - Criar conversa
- `GET /mensagens/conversa/:id` - Mensagens da conversa
- `POST /mensagens` - Enviar mensagem
- `POST /mensagens/documento` - Enviar documento

### 2. Socket Service (socket.js)

Gerencia a comunicação em tempo real via WebSocket:

**Funcionalidades:**
- Conexão automática com servidor Socket.IO
- Gerenciamento de salas de conversa
- Envio e recebimento de mensagens em tempo real
- Detecção de usuários online/offline
- Reconexão automática em caso de falha

**Eventos WebSocket:**
- `join_conversation` - Entrar em sala de conversa
- `leave_conversation` - Sair de sala de conversa
- `send_message` - Enviar mensagem
- `new_message` - Receber nova mensagem
- `user_online` - Usuário ficou online
- `user_offline` - Usuário ficou offline

## Estrutura do Banco de Dados

O sistema foi projetado para trabalhar com as seguintes tabelas MySQL:

### Tabela `usuario`
```sql
CREATE TABLE usuario (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nome VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  senha VARCHAR(255) NOT NULL
);
```

### Tabela `conversa`
```sql
CREATE TABLE conversa (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nome VARCHAR(255) NOT NULL,
  criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Tabela `conversa_membros`
```sql
CREATE TABLE conversa_membros (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_conversa INT NOT NULL,
  id_usuario INT NOT NULL,
  entrada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_conversa) REFERENCES conversa(id),
  FOREIGN KEY (id_usuario) REFERENCES usuario(id)
);
```

### Tabela `mensagem`
```sql
CREATE TABLE mensagem (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_conversa INT NOT NULL,
  id_usuario INT NOT NULL,
  conteudo TEXT,
  tipo ENUM('texto', 'documento') DEFAULT 'texto',
  nome_arquivo VARCHAR(255),
  tamanho_arquivo BIGINT,
  enviado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_conversa) REFERENCES conversa(id),
  FOREIGN KEY (id_usuario) REFERENCES usuario(id)
);
```

## Gerenciamento de Estado

### Context API + useReducer

O sistema utiliza o padrão Context API com useReducer para gerenciar estado complexo:

**Vantagens:**
- Estado centralizado e previsível
- Ações tipadas e organizadas
- Fácil debugging e testes
- Performance otimizada com React.memo

**Padrão de Ações:**
```javascript
// Exemplo de ação
dispatch({
  type: 'ADD_MESSAGE',
  payload: messageData
});
```

### Estados de Loading

Implementação de estados de loading granulares:
- `loading.users` - Carregamento de usuários
- `loading.conversations` - Carregamento de conversas
- `loading.messages` - Carregamento de mensagens

## Design System e UX

### Paleta de Cores
- **Primária**: #2196f3 (Azul)
- **Secundária**: #f8f9fa (Cinza claro)
- **Texto**: #333 (Cinza escuro)
- **Bordas**: #e9ecef (Cinza médio)
- **Sucesso**: #28a745 (Verde)
- **Erro**: #dc3545 (Vermelho)

### Componentes Visuais
- **Bordas arredondadas**: 18px para mensagens, 24px para inputs
- **Sombras sutis**: box-shadow para profundidade
- **Animações suaves**: transições de 0.2s-0.3s
- **Estados de hover**: feedback visual imediato

### Responsividade
- **Desktop**: Layout de duas colunas
- **Tablet**: Layout adaptativo
- **Mobile**: Layout empilhado verticalmente

## Funcionalidades Implementadas

### ✅ Funcionalidades Básicas
- [x] Lista de usuários disponíveis
- [x] Lista de conversas recentes
- [x] Seleção de conversas
- [x] Envio de mensagens de texto
- [x] Envio de documentos
- [x] Visualização de mensagens
- [x] Diferenciação visual de mensagens próprias/recebidas
- [x] Timestamps das mensagens
- [x] Preview de documentos
- [x] Download de documentos

### ✅ Funcionalidades Avançadas
- [x] Chat em tempo real via WebSocket
- [x] Estados de loading
- [x] Tratamento de erros
- [x] Validação de arquivos
- [x] Design responsivo
- [x] Animações e transições
- [x] Scroll automático
- [x] Indicadores de status

### 🔄 Funcionalidades Futuras
- [ ] Autenticação de usuários
- [ ] Notificações push
- [ ] Mensagens de status (entregue, lida)
- [ ] Busca de mensagens
- [ ] Emojis e reações
- [ ] Mensagens de voz
- [ ] Chamadas de vídeo
- [ ] Modo escuro
- [ ] Temas personalizáveis

## Configuração e Instalação

### Dependências Principais
```json
{
  "react": "^19.1.1",
  "react-dom": "^19.1.1",
  "socket.io-client": "^4.7.5",
  "axios": "^1.6.8",
  "lucide-react": "^0.344.0"
}
```

### Variáveis de Ambiente
```env
REACT_APP_API_URL=http://localhost:3001/api
REACT_APP_SOCKET_URL=http://localhost:3001
```

### Scripts Disponíveis
```bash
npm run dev      # Servidor de desenvolvimento
npm run build    # Build de produção
npm run preview  # Preview do build
npm run lint     # Linting do código
```

## Considerações de Escalabilidade

### Performance
- **Lazy Loading**: Componentes carregados sob demanda
- **Memoização**: React.memo para componentes puros
- **Virtualização**: Para listas muito longas (futuro)
- **Debouncing**: Para inputs de busca (futuro)

### Manutenibilidade
- **Separação de responsabilidades**: Cada componente tem uma função específica
- **Reutilização**: Componentes modulares e reutilizáveis
- **Testabilidade**: Estrutura preparada para testes unitários
- **Documentação**: Código bem documentado e comentado

### Extensibilidade
- **Arquitetura modular**: Fácil adição de novas funcionalidades
- **Padrões consistentes**: Facilita manutenção e evolução
- **APIs bem definidas**: Interfaces claras entre componentes
- **Configuração centralizada**: Fácil mudança de comportamentos

## Próximos Passos

1. **Implementar Backend**: Criar servidor Node.js com Express e Socket.IO
2. **Configurar Banco**: Implementar conexão com MySQL
3. **Autenticação**: Sistema de login/registro
4. **Testes**: Implementar testes unitários e de integração
5. **Deploy**: Configurar ambiente de produção
6. **Monitoramento**: Implementar logs e métricas
7. **Otimizações**: Performance e UX avançadas

## Conclusão

Este projeto implementa uma base sólida para um sistema de chat em tempo real, seguindo as melhores práticas de desenvolvimento React. A arquitetura modular e escalável permite fácil manutenção e evolução do sistema, enquanto a interface intuitiva e responsiva garante uma excelente experiência do usuário.

O código está estruturado de forma a facilitar a adição de novas funcionalidades e a integração com um backend real, mantendo a separação clara entre lógica de apresentação e lógica de negócio.


