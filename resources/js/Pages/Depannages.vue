<template>
    <div class="p-8 space-y-4">
        <h1 class="text-3xl font-bold mb-4">🛠️ Dépannages en cours</h1>

        <div v-if="depannages.length === 0" class="text-gray-500">Aucun dépannage en cours.</div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="d in depannages"
                :key="d.id"
                class="bg-white border p-4 rounded shadow hover:shadow-md transition"
            >
                <h2 class="text-xl font-semibold">{{ d.client.nom }}</h2>
                <p class="text-sm text-gray-600">📍 {{ d.client.town }} | 📞 {{ d.client.phone }}</p>
                <p class="mt-2 text-sm">🖥️ {{ d.marque }} {{ d.modele }}<br>🔢 SN : {{ d.numero_serie }}</p>
                <p class="text-sm mt-2">📝 {{ d.description }}</p>
                <p class="text-xs text-gray-500 mt-2">📅 {{ d.created_at }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    depannages: Array
})
</script>
