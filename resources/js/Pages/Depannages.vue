<template>
    <div class="p-8">
        <a href="../" class="bg-white shadow rounded-lg px-4 py-2 border hover:border-blue-500 hover:bg-blue-50 transition cursor-pointer">
            Menu
        </a>

        <h1 class="text-3xl font-bold mb-6 mt-6">🔧 Dépannages</h1>

        <div v-if="depannages.length === 0" class="text-gray-500">Aucun dépannage enregistré.</div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            <div
                v-for="depannage in depannages"
                :key="depannage.id"
                @click="goToDetails(depannage.id)"
                class="bg-white shadow rounded-lg p-4 border hover:border-blue-500 hover:bg-blue-50 transition cursor-pointer"
            >
                <h2 class="text-lg font-semibold">{{ depannage.client_nom ?? 'Client inconnu' }}</h2>

                <p class="text-sm text-gray-700">
                    {{ depannage.type }} - {{ depannage.marque ?? 'Marque inconnue' }} {{ depannage.modele ?? '' }}
                </p>

                <p class="text-xs text-gray-500 mt-1">
                    📆 {{ formatDate(depannage.created_at) }} — 🆔 #{{ depannage.id }}
                </p>

                <span
                    class="inline-block mt-2 px-2 py-1 rounded-full text-xs font-semibold"
                    :class="badgeClasse(depannage.statut)"
                >
                    {{ depannage.statut ?? 'Statut inconnu' }}
                </span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'

defineProps({ depannages: Array })

function formatDate(date) {
    return new Date(date).toLocaleDateString('fr-FR')
}

function badgeClasse(statut) {
    switch (statut?.toLowerCase()) {
        case 'en attente': return 'bg-yellow-100 text-yellow-700'
        case 'en cours': return 'bg-blue-100 text-blue-700'
        case 'terminé': return 'bg-green-100 text-green-700'
        default: return 'bg-gray-200 text-gray-800'
    }
}

function goToDetails(id) {
    router.visit(`/depannages/${id}`)
}
</script>
