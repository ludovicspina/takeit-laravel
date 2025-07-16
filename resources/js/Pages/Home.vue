<template>
    <div class="min-h-screen bg-gray-100 flex flex-col items-center justify-center p-8">
        <h1 class="text-3xl font-bold mb-6">Bienvenue sur le hub <u class="text-blue-600">TakeIt</u>️ !</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 w-full max-w-4xl">
            <a v-for="app in apps" :key="app.name" :href="app.link" class="bg-white shadow-md rounded-xl p-6 text-center hover:bg-blue-100 transition">
                <span class="text-xl">{{ app.icon }} {{ app.name }}</span>
                <p class="text-sm text-gray-600 mt-2">{{ app.description }}</p>
            </a>
        </div>
    </div>
</template>

<script setup>
const apps = [
    {
        name: 'Prise en charge',
        link: '/takeit',
        icon: '🛠️',
        description: 'Créer une fiche de prise en charge de matériel',
    },
    {
        name: 'Dépannages',
        link: '/depannages',
        icon: '🛠️',
        description: 'Imprimer des tickets',
    },
    {
        name: 'Dolibarr',
        link: 'http://192.168.0.202/dolibarr',
        icon: '🧾',
        description: 'Accéder aux clients, commandes, factures',
    },
    {
        name: 'Impression tickets',
        link: 'http://192.168.0.102:5001/',
        icon: '🎟️',
        description: 'Imprimer des tickets',
    },

]
</script>
