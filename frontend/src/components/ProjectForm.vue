<script setup>
import { ref } from 'vue';
import { useProjectStore } from '../stores/ProjectStore';

// Instancia o Store
const store = useProjectStore();

// Estado reativo para os campos do formulário
const name = ref('');
const description = ref('');
const isSubmitting = ref(false); // Para desabilitar o botão enquanto envia

const handleSubmit = async () => {
    // Validação básica
    if (!name.value || !description.value) {
        alert('Por favor, preencha o Nome e a Descrição do projeto.');
        return;
    }

    isSubmitting.value = true;

    // Monta o objeto de dados para a API
    const newProjectData = {
        name: name.value,
        description: description.value,
    };

    // Chama a action do Pinia
    const success = await store.createProject(newProjectData);

    if (success) {
        // Limpa o formulário após o sucesso
        name.value = '';
        description.value = '';
        alert('Projeto criado com sucesso!');
    } else {
        // O erro já foi capturado e armazenado no store.error
        alert(store.error);
    }

    isSubmitting.value = false;
};
</script>

<template>
    <div class="p-8 mb-10 bg-gray-800 shadow-2xl rounded-xl">

        <h2 class="pb-3 mb-6 text-3xl font-extrabold text-center text-indigo-400 border-b border-gray-700">
            Criar Novo Projeto
        </h2>

        <form @submit.prevent="handleSubmit" class="space-y-6">

            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-400">Nome do Projeto:</label>
                <input type="text" id="name" v-model="name" :disabled="isSubmitting" required
                    class="block w-full p-3 mt-1 text-gray-100 transition duration-150 bg-gray-900 border border-gray-600 rounded-lg shadow-inner focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
            </div>

            <div>
                <label for="description" class="block mb-2 text-sm font-medium text-gray-400">Descrição:</label>
                <textarea id="description" v-model="description" :disabled="isSubmitting" required rows="3"
                    class="block w-full p-3 mt-1 text-gray-100 transition duration-150 bg-gray-900 border border-gray-600 rounded-lg shadow-inner focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
            </div>

            <button type="submit" :disabled="isSubmitting || store.loading"
                class="w-full px-6 py-3 text-lg font-bold text-white transition duration-300 bg-indigo-600 border border-transparent rounded-lg shadow-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 disabled:bg-gray-500 disabled:cursor-not-allowed">
                {{ isSubmitting ? 'Criando...' : 'Criar Projeto' }}
            </button>
        </form>
    </div>
</template>
