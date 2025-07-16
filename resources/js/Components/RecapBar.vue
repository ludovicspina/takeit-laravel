<template>
    <div class="fixed bottom-0 left-0 right-0 bg-gray-900 text-white text-sm py-2 px-4 flex justify-between items-center z-50 shadow-md">
        <div>
            <span class="font-semibold">Client :</span>
            <span v-if="client">{{ client.nom }}</span>
            <span v-else class="italic text-gray-400">Non sélectionné</span>
        </div>
        <div>
            <span class="font-semibold">Matériel :</span>
            <span v-if="material">{{ material }}</span>
            <span v-else class="italic text-gray-400">Non défini</span>
        </div>
        <div>
            <span class="font-semibold">Étape :</span> {{ step }}
        </div>
    </div>
</template>

<script setup>
defineProps({
    client: Object,
    material: [Object, String, null],
    step: Number,
})
</script>
