import React from 'react';
import { ChatProvider } from './context/ChatContext';
import ChatPage from './pages/ChatPage';
import './App.css';

function App() {
  return (
    <ChatProvider>
      <div className="app">
        <ChatPage />
      </div>
    </ChatProvider>
  );
}

export default App;
