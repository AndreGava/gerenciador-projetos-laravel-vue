# Gerenciador de Projetos Full Stack (Laravel + Vue.js)

[![Laravel v12.x](https://img.shields.io/badge/Laravel-12.x-red)](https://laravel.com/)
[![Vue.js v3](https://img.shields.io/badge/Vue.js-3-41B883)](https://vuejs.org/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-06B6D4-06B6D4)](https://tailwindcss.com/)
[![Docker/Sail](https://img.shields.io/badge/Docker%2FSail-blue)](https://laravel.com/docs/sail)
[![Pinia](https://img.shields.io/badge/State%20Management-Pinia-000000)](https://pinia.vuejs.org/)

## 📑 Descrição do Projeto

Esta é uma Single-Page Application (SPA) construída para gerenciar projetos e suas tarefas associadas, atendendo aos requisitos do Teste Prático para Desenvolvedor(a) Full Stack PHP.

O projeto é dividido em dois principais componentes:

1.  **Back-end (API RESTful):** Desenvolvido em **Laravel 12** (PHP 8.2), utilizando MySQL e Laravel Sail para containerização.
2.  **Front-end (UI):** Construído com **Vue.js 3** e **Pinia** para gerenciamento de estado, estilizado com **Tailwind CSS**.

### Desafio Principal: Progresso Ponderado

A principal lógica de negócio implementada é o cálculo do progresso de um projeto de forma **ponderada**. O progresso não se baseia apenas na contagem de tarefas, mas no esforço definido pela dificuldade de cada tarefa (Baixa, Média, Alta).

## ✨ Funcionalidades Implementadas

* **CRUD Básico:** Criação de Projetos e Tarefas.
* **Gestão de Tarefas:** Cada tarefa possui um campo `difficulty` (Baixa, Média, Alta).
* **Lógica de Esforço:** Implementação do sistema de pontos para cálculo do progresso ponderado.
* **Atualização de Status:** Alternar tarefas como concluídas ou não (`PATCH /api/tasks/:id/toggle`).
* **Interface:** Design responsivo e tema escuro coeso utilizando Tailwind CSS.

## 💻 Decisões Técnicas

| Área | Tecnologia | Decisão Técnica e Justificativa |
| :--- | :--- | :--- |
| **Back-end Core** | Laravel 12 / PHP 8.2 | Uso da versão mais recente do Laravel para melhor desempenho e a nova estrutura de *bootstrapping* (`Application::configure`). |
| **Front-end Core** | Vue.js 3 / Pinia | Vue 3 foi escolhido pela sua performance e composição de código. **Pinia** (confirmado no `package.json`) oferece uma solução de gerenciamento de estado mais leve e intuitiva. |
| **Containerização** | Laravel Sail | Garante um ambiente de desenvolvimento isolado e consistente (PHP 8.2, MySQL 8.4) sem a necessidade de instalações locais. |
| **Estilo** | Tailwind CSS / Vite | **Vite** (confirmado no `package.json`) para compilação rápida do Front-end e **Tailwind CSS** para aplicação rápida de estilos utilitários. |
| **Lógica de Esforço** | Enums e Accessors | A lógica de cálculo de progresso é encapsulada em um Accessor no Model `Project`, garantindo que o valor seja sempre atualizado e eficiente. Um Enum pode ser usado para tipificar o campo `difficulty`. |
| **Comunicação** | Axios / CORS | **Axios** (confirmado no `package.json`) para requisições HTTP. O CORS foi configurado explicitamente no `bootstrap/app.php` para garantir a comunicação entre o Front-end (Vite) e o Back-end (Sail). |

## 📐 Lógica de Progresso Ponderado

O progresso do projeto é calculado como a porcentagem de **esforço concluído** em relação ao **esforço total** do projeto.

### Sistema de Pontos de Esforço

| Dificuldade (`difficulty`) | Pontos de Esforço |
| :--- | :--- |
| **Baixa** | 1 ponto |
| **Média** | 4 pontos |
| **Alta** | 12 pontos |

### Fórmula de Cálculo

$$\text{Progresso} = \left( \frac{\sum \text{Pontos de Esforço das Tarefas Concluídas}}{\sum \text{Pontos de Esforço de Todas as Tarefas}} \right) \times 100$$

*(Se um projeto não tiver tarefas, seu progresso é 0%)*.

## 🚀 Instalação e Execução Local

O projeto utiliza o **Laravel Sail** e requer apenas o **Docker** e o **Docker Compose** instalados na sua máquina.

### 1. Configuração Inicial

1.  **Clonar o Repositório:**
    ```bash
    git clone [LINK-DO-SEU-REPO]
    cd [NOME-DO-PROJETO]
    ```

2.  **Copiar Arquivo de Ambiente:**
    ```bash
    cp .env.example .env
    ```

3.  **Iniciar o Sail (Docker):**
    ```bash
    ./vendor/bin/sail up -d
    ```

### 2. Back-end Setup (Laravel)

Execute os seguintes comandos **dentro do contêiner Sail**:

```bash
./vendor/bin/sail composer install
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
