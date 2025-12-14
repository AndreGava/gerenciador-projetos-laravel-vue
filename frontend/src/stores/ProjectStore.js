// frontend/src/stores/ProjectStore.js
import { defineStore } from 'pinia';
import axios from 'axios';


const API_URL = 'http://localhost'

const api = axios.create({
    baseURL: API_URL,
});


export const useProjectStore = defineStore('project', {
    state: () => ({
        projects: [],
        loading: false,
        error: null,
    }),

    actions: {
        async fetchProjects() {
            this.loading = true;
            this.error = null;
            try {
                // Requisição GET para listar todos os projetos, incluindo o progresso calculado
                const response = await axios.get(`${API_URL}/api/projects`);

                // Atualiza o estado com os dados
                this.projects = response.data;
            } catch (err) {
                console.error('Erro ao buscar projetos:', err);
                this.error = 'Não foi possível carregar os projetos. Verifique se o Laravel está rodando.';
            } finally {
                this.loading = false;
            }
        },

        async createProject(projectData) {
            this.loading = true;
            this.error = null;
            try {
                // Requisição POST para criar o novo projeto no Laravel
                const response = await axios.post(`${API_URL}/api/projects`, projectData);

                const newProject = response.data;

                // Adiciona o novo projeto criado (com ID e dados da API) à lista local
                this.projects.push(newProject);

                return true; // Indica sucesso

            } catch (err) {
                console.error('Erro ao criar projeto:', err);
                // Armazena a mensagem de erro no estado
                this.error = 'Falha ao criar o projeto. Verifique os dados e o servidor.';
                return false; // Indica falha
            } finally {
                this.loading = false;
            }
        },
    },
});
