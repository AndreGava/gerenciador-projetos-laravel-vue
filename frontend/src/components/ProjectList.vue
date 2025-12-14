<script setup>
import { onMounted } from 'vue';
import { useProjectStore } from '../stores/ProjectStore';
import ProgressBar from './ProgressBar.vue';


// Instancia o Store
const store = useProjectStore();

// Chama a action fetchProjects quando o componente for montado
onMounted(() => {
    store.fetchProjects();
});

</script>

<template>
    <div class="space-y-6">
        <h2 class="pb-3 mb-6 text-3xl font-extrabold text-center text-indigo-400 border-b border-gray-700">Meus Projetos
        </h2>

        <p v-if="store.loading" class="text-lg text-center text-indigo-400 animate-pulse">Carregando projetos...</p>

        <div v-else-if="store.error"
            class="relative p-4 text-sm text-red-300 bg-red-900 border border-red-600 rounded-lg" role="alert">
            <strong class="font-bold">Erro de Conexão:</strong>
            <span class="block sm:inline"> {{ store.error }}</span>
        </div>

        <p v-else-if="store.projects.length === 0" class="italic text-center text-gray-400">
            Nenhum projeto encontrado. Crie um novo usando o formulário acima!
        </p>

        <div v-else class="space-y-4">
            <div v-for="project in store.projects" :key="project.id"
                class="p-6 transition duration-300 bg-gray-800 border-l-4 border-indigo-500 shadow-xl cursor-pointer rounded-xl hover:shadow-2xl hover:bg-gray-700">

                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-100">{{ project.name }}</h3>
                        <p class="mt-2 text-gray-400 text-md">{{ project.description }}</p>
                    </div>

                    <div class="text-right shrink-0 min-w-[100px]">
                        <p class="text-sm font-bold"
                            :class="{ 'text-green-400': project.weighted_progress === 100, 'text-indigo-400': project.weighted_progress < 100 }">
                            {{ project.weighted_progress }}% Concluído
                        </p>
                    </div>
                </div>

                <div class="mt-4">
                    <ProgressBar :percentage="project.weighted_progress" />
                </div>
                <div class="pt-4 mt-4 border-t border-gray-600">
                    <p class="text-sm italic text-indigo-400">Clique para ver e gerenciar tarefas...</p>
                </div>
            </div>
        </div>
    </div>
</template>
