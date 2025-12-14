// frontend/vite.config.js
import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },

  build: {
    outDir: 'dist',
    rollupOptions: {
      input: {
        main: fileURLToPath(new URL('./src/main.js', import.meta.url)),
      }
    }
  },

  // Configuração para o servidor de desenvolvimento (dev server)
  server: {
    port: 5174,
    strictPort: true,
  }
})
