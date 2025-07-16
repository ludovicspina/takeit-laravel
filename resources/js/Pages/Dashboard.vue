<template>
    <div class="p-8">
        <h1 class="text-3xl font-bold mb-6">📦 Prises en charge</h1>

        <div v-if="prises.length === 0" class="text-gray-500">Aucune prise en charge enregistrée.</div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div
                v-for="prise in prises"
                :key="prise.id"
                @click="selectPrise(prise)"
                class="bg-white shadow-md rounded-lg p-4 hover:bg-blue-50 cursor-pointer border hover:border-blue-400 transition"
            >
                <h2 class="text-lg font-semibold text-blue-800">{{ prise.client_nom }}</h2>
                <p class="text-sm text-gray-700">{{ prise.type }} - {{ prise.modele }}</p>
                <p class="text-xs text-gray-500 mt-1">🆔 #{{ prise.id }} — 📆 {{ formatDate(prise.created_at) }}</p>
            </div>
        </div>

        <div v-if="selected" class="mt-8 p-6 border rounded bg-gray-50">
            <h2 class="text-xl font-bold mb-2">🧾 Détails de la prise en charge #{{ selected.id }}</h2>
            <ul class="text-sm text-gray-800 space-y-1">
                <li><strong>Client :</strong> {{ selected.client_nom }}</li>
                <li><strong>Type :</strong> {{ selected.type }}</li>
                <li><strong>Modèle :</strong> {{ selected.modele }}</li>
                <li><strong>Numéro de série :</strong> {{ selected.numero_serie }}</li>
                <li><strong>Date :</strong> {{ formatDate(selected.created_at) }}</li>
                <li><strong>État :</strong> Voir backend plus tard 😎</li>
            </ul>
            <div v-if="selected" class="mt-8">
                <h3 class="font-semibold text-lg mb-2">📍 Statut de la prise en charge</h3>
                <select v-model="selected.statut" @change="updateStatut" class="border p-2 rounded">
                    <option value="en cours">En cours</option>
                    <option value="en attente">En attente</option>
                    <option value="terminé">Terminé</option>
                </select>

                <div class="mt-6">
                    <h3 class="font-semibold text-lg mb-2">💬 Commentaires</h3>

                    <ul class="space-y-2 mb-4">
                        <li v-for="c in commentaires[selected.id] || []" :key="c.id"
                            class="text-sm bg-gray-100 p-2 rounded">
                            {{ formatDate(c.created_at) }} : {{ c.contenu }}
                        </li>
                    </ul>

                    <div class="flex gap-2">
                        <input v-model="nouveauCommentaire" placeholder="Ajouter un commentaire"
                               class="flex-1 border rounded p-2"/>
                        <button @click="envoyerCommentaire"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Envoyer
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import {ref, watch} from 'vue'

const props = defineProps({
    prises: Array
})

const selected = ref(null)

const selectPrise = async (p) => {
    selected.value = p
    nouveauCommentaire.value = ''
    await chargerCommentaires(p.id)
}


const formatDate = (d) => new Date(d).toLocaleDateString('fr-FR')
const nouveauCommentaire = ref('')
const commentaires = ref({})

const updateStatut = async () => {
    await axios.patch(`/api/prises/${selected.value.id}/statut`, {
        statut: selected.value.statut
    })
}

const envoyerCommentaire = async () => {
    if (!nouveauCommentaire.value) return

    await axios.post(`/api/prises/${selected.value.id}/commentaire`, {
        contenu: nouveauCommentaire.value
    })

    nouveauCommentaire.value = ''
    await chargerCommentaires(selected.value.id)
}

const chargerCommentaires = async (id) => {
    const {data} = await axios.get(`/api/prises/${id}/commentaires`)
    commentaires.value[id] = data
}

watch(selected, async (p) => {
    if (p?.id) {
        nouveauCommentaire.value = '' // Réinitialise le champ
        await chargerCommentaires(p.id)
    }
})


</script>
